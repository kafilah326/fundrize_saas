<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\Donation;
use App\Models\Program;
use App\Models\QurbanOrder;
use App\Models\QurbanSaving;
use App\Models\QurbanSavingsDeposit;
use App\Services\MetaConversionService;

class DonationList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 5;
    public $statusFilter = '';
    public $typeFilter = 'program'; // Default to program donations

    // Export Modal
    public $isExportModalOpen = false;
    public $startDate;
    public $endDate;

    // Detail Modal
    public $isOpen = false;
    public $selectedPayment = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $payments = Payment::with(['user', 'program', 'qurbanOrder', 'qurbanSaving'])
            ->where(function($query) {
                $query->where('customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('customer_email', 'like', '%' . $this->search . '%')
                    ->orWhere('external_id', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->typeFilter, function($query) {
                $query->where('transaction_type', $this->typeFilter);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.donation-list', [
            'payments' => $payments
        ])->layout('layouts.admin');
    }

    public function showDetail($id)
    {
        $this->selectedPayment = Payment::with(['user', 'program', 'qurbanOrder.animal', 'qurbanSaving'])->find($id);
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->selectedPayment = null;
    }

    public function confirmPayment($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            session()->flash('error', 'Data pembayaran tidak ditemukan.');
            return;
        }

        // Only allow manual confirmation for bank transfers
        if ($payment->payment_type !== 'bank_transfer') {
             session()->flash('error', 'Hanya pembayaran transfer bank yang dapat dikonfirmasi manual.');
             return;
        }

        if ($payment->status !== 'pending') {
            session()->flash('error', 'Hanya pembayaran dengan status pending yang dapat dikonfirmasi.');
            return;
        }

        // 1. Update Payment status
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // 2. Update related records based on transaction_type
        if ($payment->transaction_type == 'program') {
            $donation = Donation::where('transaction_id', $payment->external_id)->first();
            if ($donation) {
                $donation->update(['status' => 'success']);
                
                // Update Program Stats
                $program = Program::find($donation->program_id);
                if ($program) {
                    $totalDonation = $donation->amount + ($payment->unique_code ?? 0);
                    $program->increment('collected_amount', $totalDonation);
                    $program->increment('donor_count');
                }
            }
        } elseif ($payment->transaction_type == 'qurban_langsung') {
            QurbanOrder::where('transaction_id', $payment->external_id)
                ->update(['status' => 'paid']);

        } elseif ($payment->transaction_type == 'qurban_tabungan') {
            $deposit = QurbanSavingsDeposit::where('transaction_id', $payment->external_id)->first();
            if ($deposit) {
                $deposit->update(['status' => 'paid']);
                
                $saving = QurbanSaving::find($deposit->qurban_saving_id);
                if ($saving) {
                    $saving->increment('saved_amount', $deposit->amount);
                    
                    if ($saving->saved_amount >= $saving->target_amount) {
                        $saving->update(['status' => 'completed']);
                    }
                }
            }
        }

        session()->flash('success', 'Pembayaran berhasil dikonfirmasi.');

        // Send Meta Purchase event via Conversions API
        try {
            $metaService = app(MetaConversionService::class);
            $metaService->sendPurchase($payment);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Meta CAPI Purchase Error (Admin): ' . $e->getMessage());
        }

        $this->closeModal();
    }

    public function openExportModal()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->isExportModalOpen = true;
    }

    public function closeExportModal()
    {
        $this->isExportModalOpen = false;
    }

    public function export()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $payments = Payment::with(['program'])
            ->where('status', 'paid')
            ->where('transaction_type', 'program')
            ->whereBetween('paid_at', [
                $this->startDate . ' 00:00:00', 
                $this->endDate . ' 23:59:59'
            ])
            ->latest('paid_at')
            ->get();

        $filename = 'Donasi_Program_' . $this->startDate . '_sd_' . $this->endDate . '.csv';
        $feePercentage = env('SYSTEM_FEE_PERCENTAGE', 0); // Default 0 if not set

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

            $callback = function() use ($payments, $feePercentage) {
                $file = fopen('php://output', 'w');
                
                // CSV Header
                fputcsv($file, [
                    'ID Transaksi', 
                    'Tanggal Bayar', 
                    'Nama Donatur', 
                    'Email', 
                    'Telepon', 
                    'Tipe', 
                    'Program',
                    'Slug',
                    'Metode Bayar',
                    'Nominal', 
                    'Kode Unik', 
                    'Total Bayar',
                    'SYSTEM_FEE (' . $feePercentage . '%)',
                    'Status'
                ], ';'); // Use semicolon as separator for Excel compatibility

                foreach ($payments as $payment) {
                    $baseAmount = $payment->amount + ($payment->unique_code ?? 0);
                    
                    // Calculate Fee only if status is paid
                    $systemFee = 0;
                    if ($payment->status === 'paid') {
                        $systemFee = $baseAmount * ($feePercentage / 100);
                    }

                    // Determine program name and slug
                    $programName = '-';
                    $programSlug = '-';
                    
                    if ($payment->program) {
                        $programName = $payment->program->title;
                        $programSlug = $payment->program->slug;
                    }

                    // Format numbers with comma as decimal separator
                    $amount = number_format($payment->amount, 0, ',', '');
                    $uniqueCode = number_format($payment->unique_code ?? 0, 0, ',', '');
                    $baseAmountFormatted = number_format($baseAmount, 0, ',', '');
                    // Keep decimals for system fee, 2 decimal places
                    $systemFeeFormatted = number_format($systemFee, 2, ',', '');

                    fputcsv($file, [
                        $payment->external_id,
                        $payment->paid_at,
                        $payment->customer_name,
                        $payment->customer_email,
                        $payment->customer_phone,
                        $payment->transaction_type,
                        $programName,
                        $programSlug,
                        $payment->payment_method,
                        $amount,
                        $uniqueCode,
                        $baseAmountFormatted,
                        $systemFeeFormatted,
                        $payment->status
                    ], ';'); // Use semicolon as separator for Excel compatibility
                }

                fclose($file);
            };

        return response()->stream($callback, 200, $headers);
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\BankFollowup;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\Program;
use App\Models\QurbanOrder;
use App\Models\QurbanSaving;
use App\Models\QurbanSavingsDeposit;
use App\Services\MetaConversionService;
use App\Services\StarSenderService;
use Livewire\Component;
use Livewire\WithPagination;

class DonationList extends Component
{
    use WithPagination;

    protected $starSender;

    public function boot(StarSenderService $starSender)
    {
        $this->starSender = $starSender;
    }

    public $search = '';

    public $perPage = 10;

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

    public function getFollowupUrl($payment, $sequence)
    {
        static $templates = null;
        if ($templates === null) {
            $templates = BankFollowup::where('is_active', true)->get();
        }

        // Determine Type
        $type = match ($payment->transaction_type) {
            'program' => 'donasi',
            'qurban_langsung' => 'qurban',
            'qurban_tabungan' => 'tabungan_qurban',
            default => 'donasi',
        };

        // Find Template
        $template = $templates->where('type', $type)
            ->where('followup_sequence', $sequence)
            ->first();

        if (! $template) {
            return '#';
        }

        $content = $template->content;

        // Common Replacements
        $content = str_replace('{{nama}}', $payment->customer_name ?? 'Hamba Allah', $content);
        $content = str_replace('{{tanggal}}', $payment->created_at->translatedFormat('d F Y'), $content);

        // Specific Replacements based on type
        if ($type == 'donasi' && $payment->program) {
            $content = str_replace('{{program}}', $payment->program->title, $content);
            $content = str_replace('{{nilai_donasi}}', 'Rp '.number_format($payment->amount, 0, ',', '.'), $content);
            $content = str_replace('{{link_donasi}}', route('program.detail', $payment->program->slug), $content);
            $content = str_replace('{{link_pembayaran}}', route('payment.status', ['id' => $payment->external_id]), $content);
        } elseif ($type == 'qurban' && $payment->qurbanOrder) {
            $content = str_replace('{{jenis_hewan}}', $payment->qurbanOrder->animal->name ?? '-', $content);
            $content = str_replace('{{tipe_qurban}}', $payment->qurbanOrder->animal->type ?? '-', $content);
            $content = str_replace('{{harga}}', 'Rp '.number_format($payment->amount, 0, ',', '.'), $content);
            $content = str_replace('{{link_pembayaran}}', route('payment.status', ['id' => $payment->external_id]), $content);
        } elseif ($type == 'tabungan_qurban' && $payment->qurbanSaving) {
            $content = str_replace('{{target_tabungan}}', 'Rp '.number_format($payment->qurbanSaving->target_amount, 0, ',', '.'), $content);
            $content = str_replace('{{saldo_saat_ini}}', 'Rp '.number_format($payment->qurbanSaving->saved_amount, 0, ',', '.'), $content);
            $content = str_replace('{{sisa_pembayaran}}', 'Rp '.number_format($payment->qurbanSaving->target_amount - $payment->qurbanSaving->saved_amount, 0, ',', '.'), $content);
            $content = str_replace('{{link_topup}}', route('qurban.savings.detail', $payment->qurbanSaving->id), $content);
            $content = str_replace('{{link_pembayaran}}', route('payment.status', ['id' => $payment->external_id]), $content);
        }

        // Phone Formatting
        $phone = $payment->customer_phone;
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        return "https://wa.me/{$phone}?text=".urlencode($content);
    }

    public function sendFollowup($paymentId, $sequence)
    {
        $payment = Payment::with(['program', 'qurbanOrder.animal', 'qurbanSaving'])->find($paymentId);

        if (! $payment) {
            $this->dispatch('alert', type: 'error', message: 'Data pembayaran tidak ditemukan.');

            return;
        }

        // Determine Type
        $type = match ($payment->transaction_type) {
            'program' => 'donasi',
            'qurban_langsung' => 'qurban',
            'qurban_tabungan' => 'tabungan_qurban',
            default => 'donasi',
        };

        // Find Template
        $template = BankFollowup::where('type', $type)
            ->where('followup_sequence', $sequence)
            ->where('is_active', true)
            ->first();

        if (! $template) {
            $this->dispatch('alert', type: 'error', message: 'Template followup tidak ditemukan.');

            return;
        }

        $content = $template->content;

        // Common Replacements
        $content = str_replace('{{nama}}', $payment->customer_name ?? 'Hamba Allah', $content);
        $content = str_replace('{{tanggal}}', $payment->created_at->translatedFormat('d F Y'), $content);

        // Specific Replacements based on type
        if ($type == 'donasi' && $payment->program) {
            $content = str_replace('{{program}}', $payment->program->title, $content);
            $content = str_replace('{{nilai_donasi}}', 'Rp '.number_format($payment->amount, 0, ',', '.'), $content);
            $content = str_replace('{{link_donasi}}', route('program.detail', $payment->program->slug), $content);
            $content = str_replace('{{link_pembayaran}}', route('payment.status', ['id' => $payment->external_id]), $content);
        } elseif ($type == 'qurban' && $payment->qurbanOrder) {
            $content = str_replace('{{jenis_hewan}}', $payment->qurbanOrder->animal->name ?? '-', $content);
            $content = str_replace('{{tipe_qurban}}', $payment->qurbanOrder->animal->type ?? '-', $content);
            $content = str_replace('{{harga}}', 'Rp '.number_format($payment->amount, 0, ',', '.'), $content);
            $content = str_replace('{{link_pembayaran}}', route('payment.status', ['id' => $payment->external_id]), $content);
        } elseif ($type == 'tabungan_qurban' && $payment->qurbanSaving) {
            $content = str_replace('{{target_tabungan}}', 'Rp '.number_format($payment->qurbanSaving->target_amount, 0, ',', '.'), $content);
            $content = str_replace('{{saldo_saat_ini}}', 'Rp '.number_format($payment->qurbanSaving->saved_amount, 0, ',', '.'), $content);
            $content = str_replace('{{sisa_pembayaran}}', 'Rp '.number_format($payment->qurbanSaving->target_amount - $payment->qurbanSaving->saved_amount, 0, ',', '.'), $content);
            $content = str_replace('{{link_topup}}', route('qurban.savings.detail', $payment->qurbanSaving->id), $content);
            $content = str_replace('{{link_pembayaran}}', route('payment.status', ['id' => $payment->external_id]), $content);
        }

        // Send via StarSender Service
        $result = $this->starSender->sendMessage(
            $payment->customer_phone,
            $content,
            'followup_'.$sequence,
            $payment->id
        );

        if ($result['status']) {
            $this->dispatch('alert', type: 'success', message: 'Pesan Followup berhasil dikirim.');
        } else {
            $this->dispatch('alert', type: 'error', message: 'Gagal mengirim pesan: '.($result['message'] ?? 'Unknown error'));
        }
    }

    public function render()
    {
        $payments = Payment::with(['user', 'program', 'qurbanOrder', 'qurbanSaving', 'whatsappMessageLogs'])
            ->where(function ($query) {
                $query->where('customer_name', 'like', '%'.$this->search.'%')
                    ->orWhere('customer_email', 'like', '%'.$this->search.'%')
                    ->orWhere('external_id', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('transaction_type', $this->typeFilter);
            })
            ->latest()
            ->paginate($this->perPage);

        $followups = BankFollowup::where('is_active', true)->get()->groupBy('type');

        return view('livewire.admin.donation-list', [
            'payments' => $payments,
            'followups' => $followups,
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

        if (! $payment) {
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
            \Illuminate\Support\Facades\Log::error('Meta CAPI Purchase Error (Admin): '.$e->getMessage());
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
                $this->startDate.' 00:00:00',
                $this->endDate.' 23:59:59',
            ])
            ->latest('paid_at')
            ->get();

        $filename = 'Donasi_Program_'.$this->startDate.'_sd_'.$this->endDate.'.csv';
        $feePercentage = env('SYSTEM_FEE_PERCENTAGE', 0); // Default 0 if not set

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($payments, $feePercentage) {
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
                'SYSTEM_FEE ('.$feePercentage.'%)',
                'Status',
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
                    $payment->status,
                ], ';'); // Use semicolon as separator for Excel compatibility
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

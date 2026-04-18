<?php

namespace App\Livewire\Admin;

use App\Models\AdminNotification;
use App\Models\BankFollowup;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\Program;
use App\Models\QurbanOrder;
use App\Models\QurbanSaving;
use App\Models\QurbanSavingsDeposit;
use App\Services\MetaConversionService;
use App\Services\StarSenderService;
use Livewire\Attributes\Layout;
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

    public $typeFilter = 'program'; // Always filter to program donations only

    public $programFilter = ''; // Filter by specific program ID

    public $dateFrom;

    public $dateTo;

    // Export Modal
    public $isExportModalOpen = false;

    public $startDate;

    public $endDate;

    public $exportProgramId = ''; // '' = semua program

    // Detail Modal
    public $isOpen = false;

    public $selectedPayment = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'programFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    // Manual Donation Modal
    public $isManualDonationModalOpen = false;
    public $manualProgramId = '';
    public $manualDonorName = '';
    public $manualDonorPhone = '';
    public $manualDonorEmail = '';
    public $manualAmount = '';
    public $manualIsAnonymous = false;
    public $manualNote = '';
    public $manualDonationDate = '';

    // Delete Donation Modal
    public $isDeleteConfirmOpen = false;
    public $deleteConfirmId = null;

    // Manual Donation Fundraiser
    public $manualAssignFundraiser = false;
    public $manualFundraiserSearch = '';
    public $manualFundraiserResults = [];
    public $manualFundraiserId = null;
    public $manualFundraiserName = '';
    public $manualCommissionPreview = 0;

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'programFilter', 'dateFrom', 'dateTo']);
    }

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

    #[Layout('layouts.admin')]
    public function render()
    {
        $programs = Program::select('id', 'title')
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        // Calculate Stats
        $statQuery = Payment::where('transaction_type', 'program')->where('status', 'paid');
        
        $statToday = (clone $statQuery)->whereDate('paid_at', now()->toDateString())->sum(\DB::raw('amount + COALESCE(unique_code, 0)'));
        $statYesterday = (clone $statQuery)->whereDate('paid_at', now()->subDay()->toDateString())->sum(\DB::raw('amount + COALESCE(unique_code, 0)'));
        $statThisMonth = (clone $statQuery)->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum(\DB::raw('amount + COALESCE(unique_code, 0)'));
        $statLastMonth = (clone $statQuery)->whereMonth('paid_at', now()->subMonth()->month)->whereYear('paid_at', now()->subMonth()->year)->sum(\DB::raw('amount + COALESCE(unique_code, 0)'));

        $payments = Payment::with(['user', 'program', 'donation', 'qurbanOrder', 'qurbanSaving', 'whatsappMessageLogs'])
            ->where('transaction_type', 'program') // Only show program donations
            ->where(function ($query) {
                $query->where('customer_name', 'like', '%'.$this->search.'%')
                    ->orWhere('customer_email', 'like', '%'.$this->search.'%')
                    ->orWhere('external_id', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->programFilter, function ($query) {
                $query->where('program_id', $this->programFilter);
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->latest()
            ->paginate($this->perPage);

        $followups = BankFollowup::where('is_active', true)->get()->groupBy('type');

        return view('livewire.admin.donation-list', [
            'payments' => $payments,
            'followups' => $followups,
            'programs' => $programs,
            'statToday' => $statToday,
            'statYesterday' => $statYesterday,
            'statThisMonth' => $statThisMonth,
            'statLastMonth' => $statLastMonth,
        ]);
    }

    public function showDetail($id)
    {
        $this->selectedPayment = Payment::with(['user', 'program', 'donation', 'qurbanOrder.animal', 'qurbanSaving'])->find($id);
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
                if ($donation->fundraiserCommission) {
                    $donation->fundraiserCommission->update(['status' => 'success']);
                }

                // Update Program Stats
                $program = Program::find($donation->program_id);
                if ($program) {
                    $totalDonation = $donation->amount + ($payment->unique_code ?? 0);
                    $program->increment('collected_amount', $totalDonation);
                    $program->increment('donor_count');
                }
            }
        } elseif ($payment->transaction_type == 'qurban_langsung') {
            $order = QurbanOrder::where('transaction_id', $payment->external_id)->first();
            if ($order) {
                $order->update(['status' => 'paid']);
                if ($order->fundraiserCommission) {
                    $order->fundraiserCommission->update(['status' => 'success']);
                }
            }
        } elseif ($payment->transaction_type == 'qurban_tabungan') {
            $deposit = QurbanSavingsDeposit::where('transaction_id', $payment->external_id)->first();
            if ($deposit) {
                $deposit->update(['status' => 'paid']);
                if ($deposit->fundraiserCommission) {
                    $deposit->fundraiserCommission->update(['status' => 'success']);
                }

                $saving = QurbanSaving::find($deposit->qurban_saving_id);
                if ($saving) {
                    $saving->increment('saved_amount', $deposit->amount);

                    if ($saving->saved_amount >= $saving->target_amount) {
                        $saving->update(['status' => 'completed']);
                    }
                }
            }
        }

        // Notify admin of confirmed payment (triggers 2.mp3 sound)
        AdminNotification::notify(
            'payment_success',
            'Pembayaran Dikonfirmasi!',
            'Pembayaran ' . $payment->external_id . ' sebesar Rp ' . number_format($payment->total) . ' dikonfirmasi manual (Bank Transfer)',
            ['payment_id' => $payment->id, 'amount' => $payment->total]
        );

        session()->flash('success', 'Pembayaran berhasil dikonfirmasi.');

        // Send Meta Purchase event via Conversions API
        try {
            $metaService = app(MetaConversionService::class);
            $metaService->sendPurchase($payment);
        // Send WhatsApp notification
        try {
            app(\App\Services\WhatsAppNotificationService::class)->notifyPaymentSuccess($payment);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('WhatsApp Notification Error (Admin): '.$e->getMessage());
        }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Meta CAPI Purchase Error (Admin): '.$e->getMessage());
        }

        $this->closeModal();
    }

    public function openExportModal()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        // Carry over active program filter into export modal
        $this->exportProgramId = $this->programFilter;
        $this->isExportModalOpen = true;
    }

    public function closeExportModal()
    {
        $this->isExportModalOpen = false;
    }

    public function openManualDonationModal()
    {
        $this->reset([
            'manualProgramId', 'manualDonorName', 'manualAmount', 
            'manualDonorPhone', 'manualDonorEmail', 'manualIsAnonymous', 'manualNote',
            'manualAssignFundraiser', 'manualFundraiserSearch', 'manualFundraiserResults',
            'manualFundraiserId', 'manualFundraiserName', 'manualCommissionPreview'
        ]);
        $this->manualDonationDate = now()->format('Y-m-d');
        $this->isManualDonationModalOpen = true;
    }

    public function closeManualDonationModal()
    {
        $this->isManualDonationModalOpen = false;
    }

    public function saveManualDonation()
    {
        $this->validate([
            'manualProgramId' => 'required|exists:programs,id',
            'manualDonorName' => 'required|string|max:255',
            'manualAmount' => 'required|numeric|min:1',
            'manualDonationDate' => 'required|date',
            'manualDonorPhone' => 'nullable|string|max:30',
            'manualDonorEmail' => 'nullable|email|max:255',
            'manualIsAnonymous' => 'boolean',
            'manualNote' => 'nullable|string|max:1000',
        ]);

        if ($this->manualAssignFundraiser && !$this->manualFundraiserId) {
            $this->dispatch('alert', type: 'error', message: 'Silakan pilih fundraiser atau hilangkan centang "Berikan Hak Fundraiser".');
            return;
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () {
                $externalId = 'MAN-' . time() . '-' . rand(100, 999);
                $date = \Carbon\Carbon::parse($this->manualDonationDate)->format('Y-m-d H:i:s');

                $payment = new \App\Models\Payment([
                    'external_id' => $externalId,
                    'transaction_type' => 'program',
                    'program_id' => $this->manualProgramId,
                    'customer_name' => $this->manualDonorName,
                    'customer_email' => $this->manualDonorEmail,
                    'customer_phone' => $this->manualDonorPhone,
                    'payment_type' => 'manual',
                    'amount' => $this->manualAmount,
                    'total' => $this->manualAmount,
                    'admin_fee' => 0,
                    'payment_method' => 'MANUAL',
                    'status' => 'paid',
                    'paid_at' => $date,
                ]);
                $payment->created_at = $date;
                $payment->updated_at = $date;
                $payment->save();

                $donation = new \App\Models\Donation([
                    'transaction_id' => $externalId,
                    'program_id' => $this->manualProgramId,
                    'fundraiser_id' => $this->manualAssignFundraiser ? $this->manualFundraiserId : null,
                    'amount' => $this->manualAmount,
                    'total' => $this->manualAmount,
                    'admin_fee' => 0,
                    'donor_name' => $this->manualDonorName,
                    'donor_phone' => $this->manualDonorPhone,
                    'donor_email' => $this->manualDonorEmail,
                    'is_anonymous' => (bool) $this->manualIsAnonymous,
                    'doa' => $this->manualNote,
                    'payment_method' => 'MANUAL',
                    'status' => 'success',
                ]);
                $donation->created_at = $date;
                $donation->updated_at = $date;
                $donation->save();

                $program = \App\Models\Program::find($this->manualProgramId);
                if ($program) {
                    $program->increment('collected_amount', $this->manualAmount);
                    $program->increment('donor_count');
                }

                // Ciptakan komisi jika fundraiser dipilih
                if ($this->manualAssignFundraiser && $this->manualFundraiserId && $this->manualCommissionPreview > 0) {
                    \App\Models\FundraiserCommission::create([
                        'fundraiser_id' => $this->manualFundraiserId,
                        'commissionable_type' => \App\Models\Donation::class,
                        'commissionable_id' => $donation->id,
                        'amount' => $this->manualCommissionPreview,
                        'status' => 'success',
                    ]);
                }
            });

            $this->closeManualDonationModal();
            $this->dispatch('alert', type: 'success', message: 'Donasi manual berhasil ditambahkan!');
        } catch (\Throwable $e) {
            $this->dispatch('alert', type: 'error', message: 'Gagal menyimpan donasi manual: ' . $e->getMessage());
        }
    }

    public function confirmDeleteManual($paymentId)
    {
        $this->deleteConfirmId = $paymentId;
        $this->isDeleteConfirmOpen = true;
    }

    public function closeDeleteConfirmModal()
    {
        $this->isDeleteConfirmOpen = false;
        $this->deleteConfirmId = null;
    }

    public function deleteManualDonation()
    {
        if (!$this->deleteConfirmId) return;

        try {
            \Illuminate\Support\Facades\DB::transaction(function () {
                $payment = Payment::lockForUpdate()->find($this->deleteConfirmId);
                
                if (!$payment || $payment->payment_type !== 'manual') {
                    throw new \Exception("Donasi tidak valid atau bukan donasi manual.");
                }

                $donation = Donation::where('transaction_id', $payment->external_id)->first();
                if ($donation) {
                    // Hapus Fundraiser Commission jika ada
                    if ($donation->fundraiserCommission) {
                        $donation->fundraiserCommission->delete();
                    }

                    // Rollback program stats
                    $program = Program::find($donation->program_id);
                    if ($program) {
                        $program->decrement('collected_amount', $donation->amount);
                        $program->decrement('donor_count', 1);
                    }

                    // Hapus Donation
                    $donation->delete();
                }

                // Hapus WhatsApp Message Logs (foreign key cascade doesn't always exist on logs)
                $payment->whatsappMessageLogs()->delete();

                // Hapus Payment
                $payment->delete();
            });

            $this->closeDeleteConfirmModal();
            $this->dispatch('alert', type: 'success', message: 'Donasi manual berhasil dihapus dan perolehan program dikembalikan.');
        } catch (\Throwable $e) {
            $this->dispatch('alert', type: 'error', message: 'Gagal menghapus donasi: ' . $e->getMessage());
        }
    }

    // --- Fundraiser Search Logic ---
    public function updatedManualAssignFundraiser()
    {
        if ($this->manualAssignFundraiser) {
            $this->calculateCommissionPreview();
        }
    }

    public function updatedManualFundraiserSearch()
    {
        if (strlen($this->manualFundraiserSearch) >= 2) {
            $this->manualFundraiserResults = \App\Models\Fundraiser::where('status', 'approved')
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->manualFundraiserSearch . '%')
                          ->orWhere('referral_code', 'like', '%' . $this->manualFundraiserSearch . '%');
                })
                ->limit(5)
                ->get()
                ->toArray();
        } else {
            $this->manualFundraiserResults = [];
        }
    }

    public function selectFundraiser($id, $name)
    {
        $this->manualFundraiserId = $id;
        $this->manualFundraiserName = $name;
        $this->manualFundraiserSearch = '';
        $this->manualFundraiserResults = [];
        $this->calculateCommissionPreview();
    }

    public function clearFundraiserSelection()
    {
        $this->manualFundraiserId = null;
        $this->manualFundraiserName = '';
        $this->manualCommissionPreview = 0;
    }

    public function updatedManualAmount()
    {
        $this->calculateCommissionPreview();
    }

    public function updatedManualProgramId()
    {
        $this->calculateCommissionPreview();
    }

    private function calculateCommissionPreview()
    {
        $this->manualCommissionPreview = 0;
        
        if (!$this->manualAssignFundraiser || empty($this->manualAmount)) {
            return;
        }

        $amount = (float) $this->manualAmount;
        
        $type = \App\Models\AppSetting::get('fundraiser_program_commission_type', 'none');
        $commissionAmountSetting = \App\Models\AppSetting::get('fundraiser_program_commission_amount', 0);

        if ($type === 'percentage') {
            $this->manualCommissionPreview = ($amount * (float) $commissionAmountSetting) / 100;
        } elseif ($type === 'fixed') {
            $this->manualCommissionPreview = min((float) $commissionAmountSetting, $amount);
        }
    }

    public function exportData()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $query = Payment::with(['program'])
            ->where('status', 'paid')
            ->where('transaction_type', 'program')
            ->whereBetween('paid_at', [
                $this->startDate.' 00:00:00',
                $this->endDate.' 23:59:59',
            ])
            ->when($this->exportProgramId, function ($q) {
                $q->where('program_id', $this->exportProgramId);
            })
            ->latest('paid_at');

        $payments = $query->get();

        // Build filename
        if ($this->exportProgramId) {
            $program = Program::find($this->exportProgramId);
            $programLabel = $program ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $program->title) : 'Program';
        } else {
            $programLabel = 'Semua_Program';
        }
        $filename = 'Donasi_'.$programLabel.'_'.$this->startDate.'_sd_'.$this->endDate.'.csv';
        $tenant = app('current_tenant');
        $feePercentage = $tenant ? $tenant->getSystemFeePercentage() : config('system.system_fee_percentage', env('SYSTEM_FEE_PERCENTAGE', 5));

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

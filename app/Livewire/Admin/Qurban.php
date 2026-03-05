<?php

namespace App\Livewire\Admin;

use App\Models\BankFollowup;
use App\Models\Payment;
use App\Models\QurbanAnimal;
use App\Models\QurbanDocumentation;
use App\Models\QurbanOrder;
use App\Models\QurbanSaving;
use App\Models\QurbanSavingsDeposit;
use App\Models\QurbanTabunganSetting;
use App\Services\MetaConversionService;
use App\Services\StarSenderService;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Qurban extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $starSender;

    public function boot(StarSenderService $starSender)
    {
        $this->starSender = $starSender;
    }

    public $activeTab = 'animals'; // animals, orders, savings, content

    public $animalType = 'langsung'; // langsung | tabungan (sub-tab within animals)

    public $search = '';

    public $perPage = 10;

    public $statusFilter = '';

    public $dateFrom = '';

    public $dateTo = '';

    // Export Modal
    public $isExportModalOpen = false;

    public $startDate;

    public $endDate;

    public $exportAnimalTypeFilter = '';

    // Animal Form
    public $animalId;

    public $type = 'langsung';

    public $name;

    public $category;

    public $weight;

    public $price;

    public $stock;

    public $image;

    public $existingImage;

    public $description;

    public $is_active = true;

    public $commission_type = 'none';

    public $commission_amount = 0;

    public $isAnimalModalOpen = false;

    // Detail Modals
    public $selectedOrder = null;

    public $isOrderModalOpen = false;

    public $selectedSaving = null;

    public $isSavingModalOpen = false;

    // Documentation
    public $docFiles = [];

    public $docCaption = '';

    // Content Settings (Tabungan)
    public $contentTitle;

    public $contentSubtitle;

    public $contentDescription;

    public $contentBenefits = [];

    public $contentAkadTitle;

    public $contentAkadDescription;

    public $contentTerms = [];

    protected $queryString = [
        'activeTab' => ['except' => 'animals'],
        'animalType' => ['except' => 'langsung'],
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    protected $rules = [
        'type' => 'required|in:langsung,tabungan',
        'name' => 'required|min:3',
        'category' => 'required|in:kambing,sapi,domba,kerbau',
        'weight' => 'required|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|max:2048',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
        'commission_type' => 'required|in:none,fixed,percentage',
        'commission_amount' => 'nullable|numeric|min:0',
        'docFiles.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240', // Limit to images for now? No, need videos too.
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->search = '';
        $this->resetPage();

        if ($tab === 'content') {
            $this->loadTabunganContent();
        }
    }

    public function openExportModal()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->exportAnimalTypeFilter = '';
        $this->isExportModalOpen = true;
    }

    public function closeExportModal()
    {
        $this->isExportModalOpen = false;
    }

    public function exportData()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $filename = '';
        $callback = null;
        $feePercentage = env('SYSTEM_FEE_PERCENTAGE', 0);

        if ($this->activeTab === 'orders') {
            $ordersQuery = QurbanOrder::with(['payment', 'animal', 'user'])
                ->where('status', 'paid')
                ->whereBetween('created_at', [
                    $this->startDate.' 00:00:00',
                    $this->endDate.' 23:59:59',
                ])
                ->when($this->exportAnimalTypeFilter, function ($q) {
                    $q->whereHas('animal', fn ($a) => $a->where('type', $this->exportAnimalTypeFilter));
                })
                ->latest();

            $orders = $ordersQuery->get();

            $typeLabel = $this->exportAnimalTypeFilter
                ? ucfirst($this->exportAnimalTypeFilter)
                : 'Semua';
            $filename = 'Qurban_Orders_'.$typeLabel.'_'.$this->startDate.'_sd_'.$this->endDate.'.csv';

            $callback = function () use ($orders, $feePercentage) {
                $file = fopen('php://output', 'w');

                fputcsv($file, [
                    'ID Transaksi', 'Tanggal', 'Nama Donatur', 'Hewan Qurban', 'Tipe', 'Atas Nama',
                    'Harga Hewan', 'Kode Unik', 'Total Bayar', 'SYSTEM_FEE ('.$feePercentage.'%)', 'Status',
                ], ';');

                foreach ($orders as $order) {
                    $payment = $order->payment;

                    $price = $order->animal_price;
                    $uniqueCode = $payment ? $payment->unique_code : 0;
                    // If payment exists, use its total, otherwise calc from price
                    $total = $payment ? ($payment->amount + $uniqueCode) : $price;

                    $systemFee = $total * ($feePercentage / 100);

                    // Formatting
                    $priceFormatted = number_format($price, 0, ',', '');
                    $uniqueCodeFormatted = number_format($uniqueCode, 0, ',', '');
                    $totalFormatted = number_format($total, 0, ',', '');
                    $systemFeeFormatted = number_format($systemFee, 2, ',', '');

                    fputcsv($file, [
                        $order->transaction_id,
                        $order->created_at->format('Y-m-d H:i:s'),
                        $order->donor_name,
                        $order->animal ? $order->animal->name : '-',
                        $order->animal ? $order->animal->type : '-',
                        $order->qurban_name,
                        $priceFormatted,
                        $uniqueCodeFormatted,
                        $totalFormatted,
                        $systemFeeFormatted,
                        $order->status,
                    ], ';');
                }
                fclose($file);
            };

        } elseif ($this->activeTab === 'savings') {
            $deposits = QurbanSavingsDeposit::with(['qurbanSaving', 'payment'])
                ->where('status', 'paid')
                ->whereBetween('created_at', [
                    $this->startDate.' 00:00:00',
                    $this->endDate.' 23:59:59',
                ])
                ->when($this->exportAnimalTypeFilter, function ($q) {
                    $q->whereHas('qurbanSaving', fn ($s) => $s->where('target_animal_type', $this->exportAnimalTypeFilter));
                })
                ->latest()
                ->get();

            $typeLabel = $this->exportAnimalTypeFilter
                ? ucfirst($this->exportAnimalTypeFilter)
                : 'Semua';
            $filename = 'Qurban_Tabungan_'.$typeLabel.'_'.$this->startDate.'_sd_'.$this->endDate.'.csv';
            $feePercentage = env('SYSTEM_FEE_PERCENTAGE', 0);

            $callback = function () use ($deposits, $feePercentage) {
                $file = fopen('php://output', 'w');

                fputcsv($file, [
                    'ID Transaksi', 'Tanggal', 'ID Tabungan', 'Nama Penabung', 'Target Hewan',
                    'Nominal Setoran', 'Kode Unik', 'Total Bayar', 'SYSTEM_FEE ('.$feePercentage.'%)', 'Status',
                ], ';');

                foreach ($deposits as $deposit) {
                    $saving = $deposit->qurbanSaving;
                    $payment = $deposit->payment;

                    $amount = $deposit->amount;
                    $uniqueCode = $payment ? $payment->unique_code : 0;
                    // Usually payment amount is amount + code.
                    // But deposit amount is usually just the principal.
                    // Let's assume Total = Amount + Unique Code.
                    $total = $amount + $uniqueCode;

                    $systemFee = 0;
                    if ($deposit->status === 'paid') {
                        $systemFee = $total * ($feePercentage / 100);
                    }

                    $amountFormatted = number_format($amount, 0, ',', '');
                    $uniqueCodeFormatted = number_format($uniqueCode, 0, ',', '');
                    $totalFormatted = number_format($total, 0, ',', '');
                    $systemFeeFormatted = number_format($systemFee, 2, ',', '');

                    fputcsv($file, [
                        $deposit->transaction_id,
                        $deposit->created_at->format('Y-m-d H:i:s'),
                        $saving->id,
                        $saving->donor_name,
                        $saving->target_animal_type,
                        $amountFormatted,
                        $uniqueCodeFormatted,
                        $totalFormatted,
                        $systemFeeFormatted,
                        $deposit->status,
                    ], ';');
                }
                fclose($file);
            };
        }

        if ($callback) {
            $headers = [
                'Content-type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=$filename",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            return response()->stream($callback, 200, $headers);
        }
    }

    public function loadTabunganContent()
    {
        $setting = QurbanTabunganSetting::first();
        if ($setting) {
            $this->contentTitle = $setting->title;
            $this->contentSubtitle = $setting->subtitle;
            $this->contentDescription = $setting->description;
            $this->contentBenefits = $setting->benefits ?? [];
            $this->contentAkadTitle = $setting->akad_title;
            $this->contentAkadDescription = $setting->akad_description;
            $this->contentTerms = $setting->terms ?? [];
        } else {
            // Defaults just in case seeder wasn't run
            $this->contentBenefits = [''];
            $this->contentTerms = [['title' => '', 'description' => '']];
        }
    }

    public function addBenefit()
    {
        $this->contentBenefits[] = '';
    }

    public function removeBenefit($index)
    {
        unset($this->contentBenefits[$index]);
        $this->contentBenefits = array_values($this->contentBenefits);
    }

    public function addTerm()
    {
        $this->contentTerms[] = ['title' => '', 'description' => ''];
    }

    public function removeTerm($index)
    {
        unset($this->contentTerms[$index]);
        $this->contentTerms = array_values($this->contentTerms);
    }

    public function saveTabunganContent()
    {
        $this->validate([
            'contentTitle' => 'required|string|max:255',
            'contentSubtitle' => 'nullable|string|max:255',
            'contentDescription' => 'nullable|string',
            'contentBenefits.*' => 'required|string',
            'contentAkadTitle' => 'nullable|string|max:255',
            'contentAkadDescription' => 'nullable|string',
            'contentTerms.*.title' => 'required|string',
            'contentTerms.*.description' => 'required|string',
        ]);

        $setting = QurbanTabunganSetting::firstOrNew();
        $setting->title = $this->contentTitle;
        $setting->subtitle = $this->contentSubtitle;
        $setting->description = $this->contentDescription;
        $setting->benefits = $this->contentBenefits;
        $setting->akad_title = $this->contentAkadTitle;
        $setting->akad_description = $this->contentAkadDescription;
        $setting->terms = $this->contentTerms;
        $setting->save();

        session()->flash('success', 'Konten halaman Tabungan Qurban berhasil disimpan.');
    }

    public function getFollowupUrl($model, $sequence, $type)
    {
        static $templates = null;
        if ($templates === null) {
            $templates = BankFollowup::where('is_active', true)->get();
        }

        // Mapping types from caller to DB types
        // $type passed from view: 'order' -> 'qurban' in DB
        // 'saving' -> 'tabungan_qurban' in DB

        $dbType = ($type === 'order') ? 'qurban' : 'tabungan_qurban';

        // Find Template
        $template = $templates->where('type', $dbType)
            ->where('followup_sequence', $sequence)
            ->first();

        if (! $template) {
            return '#';
        }

        $content = $template->content;

        if ($type === 'order') {
            // $model is QurbanOrder
            $order = $model;

            // Common Replacements
            $content = str_replace('{{nama}}', $order->donor_name ?? 'Hamba Allah', $content);
            $content = str_replace('{{tanggal}}', $order->created_at->translatedFormat('d F Y'), $content);

            // Specific Replacements for Qurban Order
            $content = str_replace('{{jenis_hewan}}', $order->animal->name ?? '-', $content);
            $content = str_replace('{{tipe_qurban}}', $order->animal->type ?? '-', $content);
            $paymentAmount = $order->payment ? $order->payment->amount : 0;
            $content = str_replace('{{harga}}', 'Rp '.number_format($paymentAmount, 0, ',', '.'), $content);

            if ($order->payment) {
                $content = str_replace('{{link_pembayaran}}', route('payment.status', ['id' => $order->payment->external_id]), $content);
            }

            // Phone Formatting
            $phone = $order->payment ? $order->payment->customer_phone : '';
            if (empty($phone)) {
                $phone = $order->whatsapp;
            }

        } elseif ($type === 'saving') {
            // $model is QurbanSaving
            $saving = $model;

            // Logic to find latest payment context
            $latestDeposit = $saving->deposits()->latest()->first();
            $latestPayment = $latestDeposit ? $latestDeposit->payment : null;

            $content = str_replace('{{nama}}', $saving->donor_name ?? 'Hamba Allah', $content);
            $content = str_replace('{{tanggal}}', now()->translatedFormat('d F Y'), $content);
            if ($latestPayment) {
                $content = str_replace('{{tanggal}}', $latestPayment->created_at->translatedFormat('d F Y'), $content);
            }

            // Specific Replacements for Qurban Saving
            $content = str_replace('{{target_tabungan}}', 'Rp '.number_format($saving->target_amount, 0, ',', '.'), $content);
            $content = str_replace('{{saldo_saat_ini}}', 'Rp '.number_format($saving->saved_amount, 0, ',', '.'), $content);
            $content = str_replace('{{sisa_pembayaran}}', 'Rp '.number_format($saving->target_amount - $saving->saved_amount, 0, ',', '.'), $content);
            $content = str_replace('{{link_topup}}', route('qurban.savings.detail', $saving->id), $content);

            if ($latestPayment) {
                $content = str_replace('{{link_pembayaran}}', route('payment.status', ['id' => $latestPayment->external_id]), $content);
            }

            // Phone Formatting
            $phone = $saving->whatsapp;
            if ($latestPayment) {
                $phone = $latestPayment->customer_phone;
            } elseif (empty($phone) && $saving->user) {
                $phone = $saving->user->whatsapp;
            }
        }

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

    public function setAnimalType($type)
    {
        $this->animalType = $type;
        $this->search = '';
        $this->resetPage();
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $data = [];
        $followups = BankFollowup::where('is_active', true)->get()->groupBy('type');
        $statToday = $statYesterday = $statThisMonth = $statLastMonth = 0;

        if ($this->activeTab === 'animals') {
            $data = QurbanAnimal::where('type', $this->animalType)
                ->where('name', 'like', '%'.$this->search.'%')
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage);
        } elseif ($this->activeTab === 'orders') {
            // Stats for orders tab
            $statQ = \App\Models\Payment::where('transaction_type', 'qurban_langsung')->where('status', 'paid');
            $statToday = (clone $statQ)->whereDate('created_at', now()->toDateString())->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));
            $statYesterday = (clone $statQ)->whereDate('created_at', now()->subDay()->toDateString())->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));
            $statThisMonth = (clone $statQ)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));
            $statLastMonth = (clone $statQ)->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));

            $data = QurbanOrder::with(['user', 'animal', 'payment.whatsappMessageLogs'])
                ->where(function ($query) {
                    $query->where('transaction_id', 'like', '%'.$this->search.'%')
                        ->orWhere('donor_name', 'like', '%'.$this->search.'%');
                })
                ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
                ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
                ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
                ->latest()
                ->paginate($this->perPage);
        } elseif ($this->activeTab === 'savings') {
            // Stats for savings tab — based on deposits
            $statQ = \App\Models\Payment::where('transaction_type', 'qurban_tabungan')->where('status', 'paid');
            $statToday = (clone $statQ)->whereDate('created_at', now()->toDateString())->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));
            $statYesterday = (clone $statQ)->whereDate('created_at', now()->subDay()->toDateString())->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));
            $statThisMonth = (clone $statQ)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));
            $statLastMonth = (clone $statQ)->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));
            // For Savings tab, we display SAVINGS ACCOUNTS, not individual payments directly in the main table usually.
            // But if the user wants FU on savings, maybe they mean FU on the saving account progress?
            // Or FU on specific deposits?
            // The table currently shows QurbanSaving models.
            // A QurbanSaving has many deposits (Payments).
            // Usually FU is per transaction.
            // BUT, if the request is "FU column", maybe it means FU for the Saving Account owner?
            // Let's assume we can send FU to the Saving User.
            // BUT, BankFollowup templates are usually transaction-based placeholders (nilai_donasi, etc).
            // If we are on 'savings' tab, we list ACCOUNTS.
            // If we send FU, which transaction/payment do we link it to? The latest deposit? Or just the Account?
            // The `sendFollowup` method expects a `paymentId`.
            // So if we add FU to the Savings list, we might need to change logic to support Sending FU to a Saving Account (not a specific payment).
            // However, the requested templates (BankFollowup) are heavily payment-centric (nilai_donasi, link_pembayaran).
            // WAIT, `DonationList` uses `Payment` model.
            // `QurbanOrder` has `payment`. So for 'orders' tab, we can pass `$order->payment->id`.
            // `QurbanSaving` is an ACCOUNT. It has `deposits` (Payments).
            // Maybe for savings tab, we want to follow up about their "Saving Progress"?
            // If so, we need to pass a valid Payment ID to `sendFollowup` OR refactor `sendFollowup` to handle Savings ID.
            // But `sendFollowup` currently fetches `Payment::find($paymentId)`.
            // Let's modify `sendFollowup` or the calling logic.
            // For now, let's implement for 'orders' tab easily.
            // For 'savings' tab, let's see. If I click FU on a Saving Row, what should happen?
            // Maybe follow up on their latest deposit? Or a general "Please Top Up" message?
            // The templates are 'tabungan_qurban'.
            // If I look at `DonationList`, type 'qurban_tabungan' uses: target_tabungan, saldo_saat_ini, sisa_pembayaran, link_topup.
            // These fields exist on `QurbanSaving` model directly (or calculated).
            // So we don't necessarily need a specific Payment for the *content*, but `sendFollowup` expects a Payment to find the user/phone.
            // `QurbanSaving` has `user_id` and `customer_phone` (via user or direct).
            // Let's check `QurbanSaving` model.

            $data = QurbanSaving::with(['user', 'deposits'])
                ->where('donor_name', 'like', '%'.$this->search.'%')
                ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
                ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
                ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
                ->latest()
                ->paginate($this->perPage);
        }

        return view('livewire.admin.qurban', [
            'data' => $data,
            'followups' => $followups,
            'statToday' => $statToday,
            'statYesterday' => $statYesterday,
            'statThisMonth' => $statThisMonth,
            'statLastMonth' => $statLastMonth,
        ]);
    }

    // Animal Methods
    public function createAnimal()
    {
        $this->resetAnimalForm();
        $this->type = $this->animalType;
        $this->isAnimalModalOpen = true;
    }

    public function editAnimal($id)
    {
        $animal = QurbanAnimal::findOrFail($id);
        $this->animalId = $id;
        $this->type = $animal->type;
        $this->name = $animal->name;
        $this->category = $animal->category;
        $this->weight = $animal->weight;
        $this->price = $animal->price;
        $this->stock = $animal->stock;
        $this->existingImage = $animal->image;
        $this->description = $animal->description;
        $this->is_active = $animal->is_active;
        $this->commission_type = $animal->commission_type ?? 'none';
        $this->commission_amount = $animal->commission_amount ?? 0;
        $this->isAnimalModalOpen = true;
    }

    public function saveAnimal()
    {
        $this->validate();

        $data = [
            'type' => $this->type,
            'name' => $this->name,
            'category' => $this->category,
            'weight' => $this->weight,
            'price' => $this->price,
            'stock' => $this->stock,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'commission_type' => $this->commission_type,
            'commission_amount' => $this->commission_amount ?: 0,
        ];

        if ($this->image) {
            $imageName = $this->image->store('qurban-animals', 'public');
            $data['image'] = $imageName;
        }

        QurbanAnimal::updateOrCreate(['id' => $this->animalId], $data);

        session()->flash('success', $this->animalId ? 'Hewan Qurban berhasil diperbarui.' : 'Hewan Qurban berhasil ditambahkan.');
        $this->isAnimalModalOpen = false;
        $this->resetAnimalForm();
    }

    public function deleteAnimal($id)
    {
        $animal = QurbanAnimal::find($id);
        if ($animal) {
            if ($animal->image) {
                Storage::disk('public')->delete($animal->image);
            }
            $animal->delete();
            session()->flash('success', 'Hewan Qurban berhasil dihapus.');
        }
    }

    public function toggleAnimalStatus($id)
    {
        $animal = QurbanAnimal::findOrFail($id);
        $animal->is_active = ! $animal->is_active;
        $animal->save();
    }

    private function resetAnimalForm()
    {
        $this->animalId = null;
        $this->type = 'langsung';
        $this->name = '';
        $this->category = 'kambing';
        $this->weight = '';
        $this->price = '';
        $this->stock = 0;
        $this->image = null;
        $this->existingImage = null;
        $this->description = '';
        $this->is_active = true;
        $this->commission_type = 'none';
        $this->commission_amount = 0;
    }

    public function closeAnimalModal()
    {
        $this->isAnimalModalOpen = false;
        $this->resetAnimalForm();
    }

    // Order Methods
    public function showOrder($id)
    {
        $this->selectedOrder = QurbanOrder::with(['user', 'animal', 'payment', 'documentations'])->find($id);
        $this->docFiles = [];
        $this->docCaption = '';
        $this->isOrderModalOpen = true;
    }

    public function closeOrderModal()
    {
        $this->isOrderModalOpen = false;
        $this->selectedOrder = null;
        $this->docFiles = [];
        $this->docCaption = '';
    }

    public function saveOrderDocumentation()
    {
        $this->validate([
            'docFiles.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi|max:51200', // 50MB
            'docCaption' => 'nullable|string|max:255',
        ]);

        if (! $this->selectedOrder) {
            return;
        }

        foreach ($this->docFiles as $file) {
            $path = $file->store('qurban-documentation/orders/'.$this->selectedOrder->id, 'public');
            $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'photo';

            $this->selectedOrder->documentations()->create([
                'file_path' => $path,
                'file_type' => $type,
                'caption' => $this->docCaption,
            ]);
        }

        $this->docFiles = [];
        $this->docCaption = '';
        $this->selectedOrder->refresh();
        session()->flash('success', 'Dokumentasi berhasil diupload.');
    }

    public function deleteDocumentation($docId, $context = 'order')
    {
        $doc = QurbanDocumentation::find($docId);
        if ($doc) {
            Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
            session()->flash('success', 'Dokumentasi berhasil dihapus.');

            if ($context === 'order' && $this->selectedOrder) {
                $this->selectedOrder->refresh();
            } elseif ($context === 'saving' && $this->selectedSaving) {
                $this->selectedSaving->refresh();
            }
        }
    }

    public function confirmOrderPayment($orderId)
    {
        $order = QurbanOrder::with('payment')->findOrFail($orderId);
        $payment = $order->payment;

        if (! $payment) {
            session()->flash('error', 'Data pembayaran tidak ditemukan.');

            return;
        }

        if ($payment->payment_type !== 'bank_transfer') {
            session()->flash('error', 'Hanya pembayaran transfer bank yang dapat dikonfirmasi manual.');

            return;
        }

        if ($payment->status !== 'pending') {
            session()->flash('error', 'Hanya pembayaran dengan status pending yang dapat dikonfirmasi.');

            return;
        }

        // Update Payment
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Update Order
        $order->update(['status' => 'paid']);

        // Update Fundraiser Commission if exists
        if ($order->fundraiserCommission) {
            $order->fundraiserCommission->update(['status' => 'success']);
        }

        // Refresh data modal
        $this->selectedOrder = QurbanOrder::with(['user', 'animal', 'payment'])->find($orderId);

        // Send Meta Purchase event via Conversions API
        try {
            $metaService = app(MetaConversionService::class);
            $metaService->sendPurchase($payment);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Meta CAPI Purchase Error (Qurban Order): '.$e->getMessage());
        }

        session()->flash('success', 'Pembayaran pesanan berhasil dikonfirmasi.');
    }

    // Saving Methods
    public function showSaving($id)
    {
        $this->selectedSaving = QurbanSaving::with(['user', 'deposits.payment', 'documentations'])->find($id);
        $this->docFiles = [];
        $this->docCaption = '';
        $this->isSavingModalOpen = true;
    }

    public function closeSavingModal()
    {
        $this->isSavingModalOpen = false;
        $this->selectedSaving = null;
        $this->docFiles = [];
        $this->docCaption = '';
    }

    public function saveSavingDocumentation()
    {
        $this->validate([
            'docFiles.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi|max:51200', // 50MB
            'docCaption' => 'nullable|string|max:255',
        ]);

        if (! $this->selectedSaving) {
            return;
        }

        foreach ($this->docFiles as $file) {
            $path = $file->store('qurban-documentation/savings/'.$this->selectedSaving->id, 'public');
            $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'photo';

            $this->selectedSaving->documentations()->create([
                'file_path' => $path,
                'file_type' => $type,
                'caption' => $this->docCaption,
            ]);
        }

        $this->docFiles = [];
        $this->docCaption = '';
        $this->selectedSaving->refresh();
        session()->flash('success', 'Dokumentasi berhasil diupload.');
    }

    public function confirmDepositPayment($depositId)
    {
        $deposit = QurbanSavingsDeposit::with('payment')->findOrFail($depositId);
        $payment = $deposit->payment;

        if (! $payment) {
            session()->flash('error', 'Data pembayaran tidak ditemukan.');

            return;
        }

        if ($payment->payment_type !== 'bank_transfer') {
            session()->flash('error', 'Hanya pembayaran transfer bank yang dapat dikonfirmasi manual.');

            return;
        }

        if ($deposit->status !== 'pending') {
            session()->flash('error', 'Hanya setoran dengan status pending yang dapat dikonfirmasi.');

            return;
        }

        // Update Payment
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Update Deposit
        $deposit->update(['status' => 'paid']);
        if ($deposit->fundraiserCommission) {
            $deposit->fundraiserCommission->update(['status' => 'success']);
        }

        // Update Saving (tambah saldo + cek target)
        $saving = QurbanSaving::find($deposit->qurban_saving_id);
        if ($saving) {
            $saving->increment('saved_amount', $deposit->amount);

            if ($saving->saved_amount >= $saving->target_amount) {
                $saving->update(['status' => 'completed']);
            }
        }

        // Refresh data modal
        $this->selectedSaving = QurbanSaving::with(['user', 'deposits.payment'])->find($deposit->qurban_saving_id);

        // Send Meta Purchase event via Conversions API
        try {
            $metaService = app(MetaConversionService::class);
            $metaService->sendPurchase($payment);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Meta CAPI Purchase Error (Qurban Deposit): '.$e->getMessage());
        }

        session()->flash('success', 'Pembayaran setoran berhasil dikonfirmasi.');
    }
}

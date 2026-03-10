<?php

namespace App\Livewire\Admin;

use App\Models\AdminNotification;
use App\Models\AppSetting;
use App\Models\Payment;
use App\Models\ZakatDistribution;
use App\Models\ZakatTransaction;
use App\Services\MetaConversionService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ZakatList extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';

    public $perPage = 10;

    public $statusFilter = '';

    public $zakatTypeFilter = '';

    public $dateFrom;

    public $dateTo;

    // Tabs
    public $activeTab = 'transactions';

    // Settings
    public $zakat_fitrah_price;

    public $zakat_gold_price_per_gram;

    public $zakatBannerImage;

    public $existingZakatBanner;

    // Distribution CRUD
    public $showDistributionModal = false;

    public $confirmingDistributionDeletion = false;

    public $distributionId;

    public $distributionTitle;

    public $distributionAmount;

    public $distributionDescription;

    public $distributionDate;

    // Export Modal
    public $isExportModalOpen = false;

    public $startDate;

    public $endDate;

    public $exportZakatType = '';

    // Detail Modal
    public $isOpen = false;

    public $selectedPayment = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'zakatTypeFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'activeTab' => ['except' => 'transactions'],
    ];

    public function mount()
    {
        $this->zakat_fitrah_price = AppSetting::get('zakat_fitrah_price', 45000);
        $this->zakat_gold_price_per_gram = AppSetting::get('zakat_gold_price_per_gram', 1500000);
        $this->existingZakatBanner = AppSetting::get('zakat_banner_image');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'zakatTypeFilter', 'dateFrom', 'dateTo']);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function deleteZakatBanner()
    {
        if (! $this->existingZakatBanner) {
            return;
        }

        Storage::disk('public')->delete($this->existingZakatBanner);
        AppSetting::where('key', 'zakat_banner_image')->delete();
        \Illuminate\Support\Facades\Cache::forget('app_setting_zakat_banner_image');

        $this->existingZakatBanner = null;
        $this->zakatBannerImage = null;

        session()->flash('success', 'Banner zakat berhasil dihapus.');
    }

    public function saveZakat()
    {
        $this->validate([
            'zakat_fitrah_price' => 'required|numeric|min:0',
            'zakat_gold_price_per_gram' => 'required|numeric|min:0',
            'zakatBannerImage' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($this->zakatBannerImage) {
            Storage::disk('public')->makeDirectory('zakat');
            $manager = new ImageManager(new GdDriver);
            $image = $manager->read($this->zakatBannerImage->getRealPath());
            $processed = $image->cover(1200, 630)->toJpeg(85);

            $filename = 'zakat-banner-'.time().'.jpg';
            $path = 'zakat/'.$filename;

            if ($this->existingZakatBanner) {
                Storage::disk('public')->delete($this->existingZakatBanner);
            }

            Storage::disk('public')->put($path, (string) $processed);
            AppSetting::updateOrCreate(
                ['key' => 'zakat_banner_image'],
                [
                    'value' => $path,
                    'group' => 'zakat',
                    'type' => 'text',
                    'label' => 'Banner Halaman Zakat',
                    'description' => 'Gambar banner yang ditampilkan di halaman depan zakat',
                ]
            );
            \Illuminate\Support\Facades\Cache::forget('app_setting_zakat_banner_image');

            $this->zakatBannerImage = null;
            $this->existingZakatBanner = $path;
        }

        AppSetting::updateOrCreate(
            ['key' => 'zakat_fitrah_price'],
            [
                'value' => $this->zakat_fitrah_price,
                'group' => 'zakat',
                'type' => 'number',
                'label' => 'Harga Zakat Fitrah per Jiwa',
                'description' => 'Nominal zakat fitrah untuk satu jiwa (dalam rupiah)',
            ]
        );
        \Illuminate\Support\Facades\Cache::forget('app_setting_zakat_fitrah_price');

        AppSetting::updateOrCreate(
            ['key' => 'zakat_gold_price_per_gram'],
            [
                'value' => $this->zakat_gold_price_per_gram,
                'group' => 'zakat',
                'type' => 'number',
                'label' => 'Harga Emas per Gram (untuk Nisab)',
                'description' => 'Harga emas per gram, digunakan untuk menghitung nisab zakat mal (85 gram emas)',
            ]
        );
        \Illuminate\Support\Facades\Cache::forget('app_setting_zakat_gold_price_per_gram');

        session()->flash('success', 'Pengaturan zakat berhasil diperbarui.');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showDetail($id)
    {
        $this->selectedPayment = Payment::with(['user', 'zakatTransaction'])->find($id);
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

        // 2. Update ZakatTransaction & Commission
        if ($payment->transaction_type === 'zakat') {
            $zakatTrx = ZakatTransaction::where('transaction_id', $payment->external_id)->first();
            if ($zakatTrx) {
                $zakatTrx->update(['status' => 'success']);

                // Update related FundraiserCommission if exists
                if ($zakatTrx->fundraiserCommission) {
                    $zakatTrx->fundraiserCommission->update(['status' => 'success']);
                }
            }
        }

        // 3. Notify admin
        AdminNotification::notify(
            'payment_success',
            'Pembayaran Zakat Dikonfirmasi!',
            'Zakat '.$payment->external_id.' sebesar Rp '.number_format($payment->total).' dari '.($payment->customer_name ?? 'Hamba Allah').' — dikonfirmasi manual',
            ['payment_id' => $payment->id, 'amount' => $payment->total]
        );

        // 4. Send Meta Purchase event
        try {
            $metaService = app(MetaConversionService::class);
            $metaService->sendPurchase($payment);
        } catch (\Exception $e) {
            Log::error('Meta CAPI Purchase Error (Zakat): '.$e->getMessage());
        }

        // 5. Send WhatsApp notification
        try {
            app(WhatsAppNotificationService::class)->notifyPaymentSuccess($payment);
        } catch (\Exception $e) {
            Log::error('WhatsApp Notification Error (Zakat): '.$e->getMessage());
        }

        session()->flash('success', 'Pembayaran zakat berhasil dikonfirmasi.');
        $this->closeModal();
    }

    public function createDistribution()
    {
        $this->resetDistributionForm();
        $this->showDistributionModal = true;
    }

    public function editDistribution($id)
    {
        $this->resetDistributionForm();
        $distribution = ZakatDistribution::findOrFail($id);

        $this->distributionId = $distribution->id;
        $this->distributionTitle = $distribution->title;
        $this->distributionAmount = $distribution->amount;
        $this->distributionDescription = $distribution->description;
        $this->distributionDate = $distribution->distribution_date->format('Y-m-d');

        $this->showDistributionModal = true;
    }

    public function storeDistribution()
    {
        $this->validate([
            'distributionTitle' => 'required|string|max:255',
            'distributionAmount' => 'required|numeric|min:1',
            'distributionDescription' => 'required',
            'distributionDate' => 'required|date',
        ]);

        ZakatDistribution::updateOrCreate(
            ['id' => $this->distributionId],
            [
                'title' => $this->distributionTitle,
                'amount' => $this->distributionAmount,
                'description' => $this->distributionDescription,
                'distribution_date' => $this->distributionDate,
            ]
        );

        session()->flash('success', $this->distributionId ? 'Penyaluran zakat berhasil diperbarui.' : 'Penyaluran zakat berhasil ditambahkan.');

        $this->showDistributionModal = false;
        $this->resetDistributionForm();
    }

    public function confirmDeleteDistribution($id)
    {
        $this->distributionId = $id;
        $this->confirmingDistributionDeletion = true;
    }

    public function closeDistributionModal()
    {
        $this->showDistributionModal = false;
        $this->resetDistributionForm();
    }

    public function closeDeleteModal()
    {
        $this->confirmingDistributionDeletion = false;
        $this->resetDistributionForm();
    }

    public function deleteDistribution()
    {
        ZakatDistribution::findOrFail($this->distributionId)->delete();

        session()->flash('success', 'Penyaluran zakat berhasil dihapus.');

        $this->confirmingDistributionDeletion = false;
        $this->resetDistributionForm();
    }

    public function resetDistributionForm()
    {
        $this->reset([
            'distributionId',
            'distributionTitle',
            'distributionAmount',
            'distributionDescription',
            'distributionDate',
        ]);
        $this->resetValidation();
    }

    public function openExportModal()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        // Carry over active filter
        $this->exportZakatType = $this->zakatTypeFilter;
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

        $query = Payment::with(['user', 'zakatTransaction'])
            ->where('status', 'paid')
            ->where('transaction_type', 'zakat')
            ->whereBetween('paid_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59',
            ])
            ->when($this->exportZakatType, function ($q) {
                $q->whereHas('zakatTransaction', function ($q2) {
                    $q2->where('zakat_type', $this->exportZakatType);
                });
            })
            ->latest('paid_at');

        $payments = $query->get();

        // Build filename
        $typeLabel = $this->exportZakatType ? ucfirst($this->exportZakatType) : 'Semua';
        $filename = 'Zakat_' . $typeLabel . '_' . $this->startDate . '_sd_' . $this->endDate . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, [
                'ID Transaksi',
                'Tanggal Bayar',
                'Muzakki',
                'Email',
                'Telepon',
                'Jenis Zakat',
                'Detail Zakat',
                'Metode Bayar',
                'Nominal',
                'Kode Unik',
                'Total Bayar',
                'Status',
            ], ';');

            foreach ($payments as $payment) {
                $zakatType = '-';
                $zakatDetail = '-';

                if ($payment->zakatTransaction) {
                    $zakatType = $payment->zakatTransaction->zakat_type_label;
                    if ($payment->zakatTransaction->zakat_type === 'fitrah') {
                        $zakatDetail = $payment->zakatTransaction->jumlah_jiwa . ' jiwa';
                    } elseif ($payment->zakatTransaction->zakat_type === 'maal') {
                        $totalHarta = number_format($payment->zakatTransaction->total_harta ?? 0, 0, ',', '.');
                        $nisab = number_format($payment->zakatTransaction->nisab_at_time ?? 0, 0, ',', '.');
                        $zakatDetail = "Harta: Rp {$totalHarta} | Nisab: Rp {$nisab}";
                    }
                }

                // Format numbers with comma as decimal separator
                $amount = number_format($payment->amount, 0, ',', '');
                $uniqueCode = number_format($payment->unique_code ?? 0, 0, ',', '');
                $totalAmount = number_format($payment->amount + ($payment->unique_code ?? 0), 0, ',', '');

                fputcsv($file, [
                    $payment->external_id,
                    $payment->paid_at,
                    $payment->customer_name ?? 'Hamba Allah',
                    $payment->customer_email,
                    $payment->customer_phone,
                    $zakatType,
                    $zakatDetail,
                    $payment->payment_method,
                    $amount,
                    $uniqueCode,
                    $totalAmount,
                    $payment->status,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $statQuery = Payment::where('transaction_type', 'zakat')->where('status', 'paid');

        $statToday = (clone $statQuery)->whereDate('paid_at', now()->toDateString())->sum(\DB::raw('amount + COALESCE(unique_code, 0)'));
        $statThisMonth = (clone $statQuery)->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum(\DB::raw('amount + COALESCE(unique_code, 0)'));
        $statTotal = (clone $statQuery)->sum(\DB::raw('amount + COALESCE(unique_code, 0)'));

        $payments = Payment::with(['user', 'zakatTransaction'])
            ->where('transaction_type', 'zakat')
            ->where(function ($query) {
                $query->where('customer_name', 'like', '%'.$this->search.'%')
                    ->orWhere('customer_email', 'like', '%'.$this->search.'%')
                    ->orWhere('external_id', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->zakatTypeFilter, function ($query) {
                $query->whereHas('zakatTransaction', function ($q) {
                    $q->where('zakat_type', $this->zakatTypeFilter);
                });
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->latest()
            ->paginate($this->perPage);

        $distributions = ZakatDistribution::latest()->paginate($this->perPage, ['*'], 'distributionPage');

        return view('livewire.admin.zakat-list', [
            'payments' => $payments,
            'statToday' => $statToday,
            'statThisMonth' => $statThisMonth,
            'statTotal' => $statTotal,
            'distributions' => $distributions,
        ]);
    }
}

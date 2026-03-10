<?php

namespace App\Livewire\Front;

use App\Models\AppSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use App\Models\ZakatDistribution;
use Illuminate\Support\Facades\DB;

class ZakatIndex extends Component
{
    public $activeTab = 'fitrah'; // fitrah or maal

    // Fitrah
    public $fitrahPeople = 1;
    public $fitrahPrice = 45000; // from setting

    // Maal
    public $maalMode = 'calculator'; // 'calculator' or 'manual'
    public $maalManualAmount = '';

    // Maal calculator fields
    public $emas = '';
    public $uang = '';
    public $aset = '';
    public $hutang = '';

    // Personal Info
    public $name;
    public $phone;
    public $email;

    // Gold price & computed nisab (from setting)
    public $goldPricePerGram = 1500000;
    public $nisab = 127500000; // 85 * 1_500_000

    // Computed
    public $calculatedZakat = 0;
    public $totalHarta = 0;
    public $zakatStatus = 'belum'; // 'belum' or 'wajib'
    public $zakatBannerPath;
    public $totalCollected = 0;
    public $totalThisMonth = 0;
    public $totalTransactions = 0;
    public $totalDistributed = 0;
    public $zakatDistributions;

    public function mount()
    {
        $this->fitrahPrice = (int) AppSetting::get('zakat_fitrah_price', 45000);
        $this->goldPricePerGram = (int) AppSetting::get('zakat_gold_price_per_gram', 1500000);
        $this->nisab = $this->goldPricePerGram * 85;

        if (auth()->check()) {
            $user = auth()->user();
            $this->name = $user->name;
            $this->phone = $user->phone;
            $this->email = $user->email;
        }

        $this->calculate();
        $this->zakatBannerPath = AppSetting::get('zakat_banner_image');

        $baseQuery = \App\Models\Payment::where('transaction_type', 'zakat')->where('status', 'paid');
        $this->totalCollected   = (clone $baseQuery)->sum(DB::raw('amount + COALESCE(unique_code, 0)'));
        $this->totalThisMonth   = (clone $baseQuery)->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum(DB::raw('amount + COALESCE(unique_code, 0)'));
        $this->totalTransactions = (clone $baseQuery)->count();
        $this->zakatDistributions = ZakatDistribution::latest()->limit(12)->get();
        $this->totalDistributed  = ZakatDistribution::sum('amount');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->calculate();
    }

    public function setMaalMode(string $mode): void
    {
        $this->maalMode = $mode;
        $this->calculate();
    }

    public function updated($propertyName): void
    {
        $this->calculate();
    }

    public function calculate(): void
    {
        if ($this->activeTab === 'fitrah') {
            $people = max(1, (int) $this->fitrahPeople);
            $this->calculatedZakat = $people * $this->fitrahPrice;
            $this->totalHarta = 0;
            $this->zakatStatus = 'wajib';
        } elseif ($this->activeTab === 'maal') {
            if ($this->maalMode === 'manual') {
                $nominal = (int) preg_replace('/[^0-9]/', '', $this->maalManualAmount);
                $this->calculatedZakat = $nominal;
                $this->zakatStatus = $nominal > 0 ? 'wajib' : 'belum';
                $this->totalHarta = 0;
            } else {
                $emas   = (int) preg_replace('/[^0-9]/', '', $this->emas);
                $uang   = (int) preg_replace('/[^0-9]/', '', $this->uang);
                $aset   = (int) preg_replace('/[^0-9]/', '', $this->aset);
                $hutang = (int) preg_replace('/[^0-9]/', '', $this->hutang);

                $this->totalHarta = max(0, $emas + $uang + $aset - $hutang);

                if ($this->totalHarta >= $this->nisab) {
                    $this->calculatedZakat = (int) round($this->totalHarta * 0.025);
                    $this->zakatStatus = 'wajib';
                } else {
                    $this->calculatedZakat = 0;
                    $this->zakatStatus = 'belum';
                }
            }
        }
    }

    public function submitZakat(): void
    {
        if ($this->calculatedZakat <= 0) {
            session()->flash('error', 'Nominal zakat harus lebih dari 0.');
            return;
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|min:8|max:16',
            'email' => 'nullable|email|max:255',
        ]);

        $checkoutData = [
            'type'         => 'zakat',
            'zakat_type'   => $this->activeTab,
            'amount'       => $this->calculatedZakat,
            'program_name' => 'Zakat ' . ($this->activeTab === 'fitrah' ? 'Fitrah' : 'Mal'),
            'name'         => $this->name,
            'phone'        => $this->phone,
            'email'        => $this->email,
        ];

        // Fitrah metadata
        if ($this->activeTab === 'fitrah') {
            $checkoutData['jumlah_jiwa'] = (int) $this->fitrahPeople;
        }

        // Maal metadata
        if ($this->activeTab === 'maal') {
            $checkoutData['total_harta']      = $this->totalHarta ?: null;
            $checkoutData['nisab_at_time']    = $this->nisab;
            $checkoutData['calculated_zakat'] = $this->calculatedZakat;
        }

        session(['checkout' => $checkoutData]);

        $this->redirect(route('payment.method'));
    }

    #[Layout('layouts.front')]
    #[Title('Tunaikan Zakat')]
    public function render()
    {
        return view('livewire.front.zakat-index', [
            'metaImage' => $this->zakatBannerPath 
                ? Storage::disk('public')->url($this->zakatBannerPath) 
                : null,
        ]);
    }
}

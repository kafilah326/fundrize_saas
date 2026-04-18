<?php

namespace App\Livewire\Front;

use App\Models\Fundraiser;
use App\Models\FoundationSetting;
use App\Models\Donation;
use App\Models\QurbanOrder;
use App\Models\QurbanSavingsDeposit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\ZakatTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FundraiserDashboard extends Component
{
    public $user;
    public $fundraiser;

    // Stats
    public $totalDonationAmount = 0;
    public $totalProgramAmount = 0;
    public $totalZakatAmount = 0;
    public $totalQurbanAmount = 0;
    public $totalDonationCount = 0;
    public $availableBalance = 0;
    public $totalVisits = 0;

    public function mount()
    {
        $this->user = Auth::user()->load('fundraiser');
        $this->fundraiser = $this->user->fundraiser;

        if (!$this->fundraiser || $this->fundraiser->status !== 'approved') {
            return redirect()->route('profile.index');
        }

        // Generate referral code if it doesn't exist (for existing users before this feature)
        if (empty($this->fundraiser->referral_code)) {
            $foundationName = FoundationSetting::first()->name ?? 'Yayasan';
            $words = explode(' ', trim($foundationName));
            $baseCode = '';
            
            if (count($words) == 1) {
                $baseCode = substr($words[0], 0, 3);
            } else {
                $baseCode = substr($words[0], 0, 2) . substr($words[1], 0, 1);
            }
            $baseCode = strtoupper($baseCode);
            
            do {
                $randomStr = strtoupper(Str::random(4));
                $referralCode = $baseCode . $randomStr;
            } while (Fundraiser::where('referral_code', $referralCode)->exists());
            
            $this->fundraiser->update(['referral_code' => $referralCode]);
            $this->fundraiser->refresh();
        }

        $this->calculateStats();
    }

    private function calculateStats()
    {
        $fundraiserId = $this->fundraiser->id;

        // 1. Total Kebaikan Tersalurkan (Donation + Qurban Orders + Qurban Deposits that are PAID)
        $donationsCount = Donation::where('donations.fundraiser_id', $fundraiserId)->where('donations.status', 'success')->count();
        $qurbanOrdersCount = QurbanOrder::where('qurban_orders.fundraiser_id', $fundraiserId)->where('qurban_orders.status', 'paid')->count();
        $qurbanDepositsCount = QurbanSavingsDeposit::whereHas('qurbanSaving', function($q) use ($fundraiserId) {
            $q->where('qurban_savings.fundraiser_id', $fundraiserId);
        })->where('qurban_savings_deposits.status', 'paid')->count();

        $donationsAmount = Donation::where('donations.fundraiser_id', $fundraiserId)->where('donations.status', 'success')
            ->join('payments', 'donations.transaction_id', '=', 'payments.external_id')
            ->sum(DB::raw('donations.amount + COALESCE(payments.unique_code, 0)'));

        $qurbanOrdersAmount = QurbanOrder::where('qurban_orders.fundraiser_id', $fundraiserId)->where('qurban_orders.status', 'paid')
            ->join('payments', 'qurban_orders.transaction_id', '=', 'payments.external_id')
            ->sum(DB::raw('qurban_orders.amount + COALESCE(payments.unique_code, 0)'));

        $qurbanDepositsAmount = QurbanSavingsDeposit::whereHas('qurbanSaving', function($q) use ($fundraiserId) {
            $q->where('qurban_savings.fundraiser_id', $fundraiserId);
        })->where('qurban_savings_deposits.status', 'paid')
        ->join('payments', 'qurban_savings_deposits.transaction_id', '=', 'payments.external_id')
        ->sum(DB::raw('qurban_savings_deposits.amount + COALESCE(payments.unique_code, 0)'));

        $this->totalProgramAmount = $donationsAmount;
        $this->totalQurbanAmount = $qurbanOrdersAmount + $qurbanDepositsAmount;

        // 2. Zakat Amount
        $this->totalZakatAmount = ZakatTransaction::where('zakat_transactions.fundraiser_id', $fundraiserId)->where('zakat_transactions.status', 'success')
            ->join('payments', 'zakat_transactions.transaction_id', '=', 'payments.external_id')
            ->sum(DB::raw('zakat_transactions.amount + COALESCE(payments.unique_code, 0)'));

        $this->totalDonationAmount = $this->totalProgramAmount + $this->totalZakatAmount + $this->totalQurbanAmount;

        // 3. Total Transaction Count (Donation + Zakat + Qurban Orders + Qurban Deposits that are PAID)
        $zakatCount = ZakatTransaction::where('zakat_transactions.fundraiser_id', $fundraiserId)->where('zakat_transactions.status', 'success')->count();
        $this->totalDonationCount = $donationsCount + $zakatCount + $qurbanOrdersCount + $qurbanDepositsCount;

        // 4. Available Balance (Ujroh)
        $this->availableBalance = $this->fundraiser->available_balance;
        
        // 5. Total Visits (Clicks)
        $this->totalVisits = $this->fundraiser->visits()->count();
    }

    #[Layout('layouts.front')]
    #[Title('Dashboard Fundriser')]
    public function render()
    {
        return view('livewire.front.fundraiser-dashboard');
    }
}

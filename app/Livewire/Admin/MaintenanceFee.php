<?php

namespace App\Livewire\Admin;

use App\Models\Donation;
use App\Models\MaintenanceFee as MaintenanceFeeModel;
use App\Models\QurbanOrder;
use App\Models\QurbanSavingsDeposit;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Maintenance Fee')]
class MaintenanceFee extends Component
{
    use WithFileUploads;

    public $year;
    public $month; // Selected month for details
    public $detailTransactions = [];
    public $proofOfPayment;
    public $selectedMonthFee;

    public function mount()
    {
        $this->year = Carbon::now()->year;
    }

    public function showDetail($month)
    {
        $this->month = $month;
        $this->detailTransactions = $this->getTransactionsForMonth($month);
        // Calculate total fee for the selected month
        $totalCollected = collect($this->detailTransactions)->sum('amount');
        $feePercentage = (float) env('SYSTEM_FEE_PERCENTAGE', 0);
        $this->selectedMonthFee = ($totalCollected * $feePercentage) / 100;
        
        $this->dispatch('open-modal', name: 'detail-modal');
    }

    public function showPayment($month)
    {
        $this->month = $month;
        $this->detailTransactions = $this->getTransactionsForMonth($month);
        
        $totalCollected = collect($this->detailTransactions)->sum('amount');
        $feePercentage = (float) env('SYSTEM_FEE_PERCENTAGE', 0);
        $this->selectedMonthFee = ($totalCollected * $feePercentage) / 100;

        $this->dispatch('open-modal', name: 'payment-modal');
    }

    public function pay()
    {
        $this->validate([
            'proofOfPayment' => 'required|image|max:2048', // 2MB Max
        ]);

        $transactions = $this->getTransactionsForMonth($this->month);
        $totalCollected = collect($transactions)->sum('amount');
        $feePercentage = (float) env('SYSTEM_FEE_PERCENTAGE', 0);
        $feeAmount = ($totalCollected * $feePercentage) / 100;

        $path = $this->proofOfPayment->store('maintenance-fees', 'public');

        MaintenanceFeeModel::updateOrCreate(
            ['year' => $this->year, 'month' => $this->month],
            [
                'total_amount' => $totalCollected,
                'fee_amount' => $feeAmount,
                'status' => 'unverified',
                'proof_of_payment' => $path,
            ]
        );

        $this->reset(['proofOfPayment', 'month', 'detailTransactions']);
        $this->dispatch('close-modal', name: 'payment-modal');
        session()->flash('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function getTransactionsForMonth($month)
    {
        $year = $this->year;
        $transactions = [];

        // Donations
        $donations = Donation::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', 'success')
            ->get();

        foreach ($donations as $donation) {
            $transactions[] = [
                'id_trx' => $donation->transaction_id,
                'title' => 'Donasi: ' . ($donation->program->title ?? 'Program'),
                'type' => 'Donasi',
                'amount' => $donation->total, // Using total as base amount
                'fee' => ($donation->total * env('SYSTEM_FEE_PERCENTAGE', 0)) / 100,
                'date' => $donation->created_at,
            ];
        }

        // Qurban Orders
        $qurbanOrders = QurbanOrder::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', 'paid')
            ->get();

        foreach ($qurbanOrders as $order) {
            $transactions[] = [
                'id_trx' => $order->transaction_id,
                'title' => 'Qurban: ' . ($order->qurbanAnimal->name ?? 'Hewan'),
                'type' => 'Qurban',
                'amount' => $order->amount,
                'fee' => ($order->amount * env('SYSTEM_FEE_PERCENTAGE', 0)) / 100,
                'date' => $order->created_at,
            ];
        }

        // Qurban Savings Deposits
        $deposits = QurbanSavingsDeposit::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', 'paid')
            ->get();

        foreach ($deposits as $deposit) {
            $transactions[] = [
                'id_trx' => $deposit->transaction_id,
                'title' => 'Tabungan Qurban',
                'type' => 'Tabungan',
                'amount' => $deposit->amount,
                'fee' => ($deposit->amount * env('SYSTEM_FEE_PERCENTAGE', 0)) / 100,
                'date' => $deposit->created_at,
            ];
        }

        return $transactions;
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $months = [];
        $feePercentage = (float) env('SYSTEM_FEE_PERCENTAGE', 0);
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // If filtering by current year, only show up to current month
        // If filtering by past years, show all 12 months
        $limitMonth = ($this->year == $currentYear) ? $currentMonth : 12;

        for ($m = 1; $m <= $limitMonth; $m++) {
            $monthName = Carbon::createFromDate($this->year, $m, 1)->translatedFormat('F');
            
            // Calculate totals
            $totalDonations = \App\Models\Payment::whereYear('paid_at', $this->year)
                ->whereMonth('paid_at', $m)
                ->where('status', 'paid')
                ->where('transaction_type', 'program')
                ->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));

            $totalQurban = \App\Models\Payment::whereYear('paid_at', $this->year)
                ->whereMonth('paid_at', $m)
                ->where('status', 'paid')
                ->where('transaction_type', 'qurban_langsung')
                ->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));

            $totalSavings = \App\Models\Payment::whereYear('paid_at', $this->year)
                ->whereMonth('paid_at', $m)
                ->where('status', 'paid')
                ->where('transaction_type', 'qurban_tabungan')
                ->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));

            $totalCollected = $totalDonations + $totalQurban + $totalSavings;
            $feeMaintenance = ($totalCollected * $feePercentage) / 100;

            // Check existing record
            $record = MaintenanceFeeModel::where('year', $this->year)
                ->where('month', $m)
                ->first();

            $status = $record ? $record->status : 'pending';
            
            // Determine if button should be hidden (for current active month)
            $isCurrentMonth = ($this->year == $currentYear && $m == $currentMonth);
            
            $months[] = [
                'month_num' => $m,
                'month_name' => $monthName,
                'total_collected' => $totalCollected,
                'fee_maintenance' => $feeMaintenance,
                'status' => $status,
                'record' => $record,
                'is_current_month' => $isCurrentMonth,
            ];
        }

        // Sort months descending (latest first)
        $months = array_reverse($months);

        return view('livewire.admin.maintenance-fee', [
            'months' => $months,
            'years' => range(Carbon::now()->year, Carbon::now()->year - 5), // Last 5 years
            'systemFee' => $feePercentage,
            'bankDetails' => [
                'bank' => env('MAINTENANCE_BANK'),
                'account_number' => env('MAINTENANCE_ACCOUNT_NUMBER'),
                'account_name' => env('MAINTENANCE_ACCOUNT_NAME'),
            ],
        ]);
    }
}

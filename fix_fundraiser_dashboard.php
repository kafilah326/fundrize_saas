<?php
$file = 'app/Livewire/Front/FundraiserDashboard.php';
$content = file_get_contents($file);

$searchStats = <<<'EOL'
        // 1. Total Kebaikan Tersalurkan (Donation + Qurban Orders + Qurban Deposits that are PAID)
        $donations = Donation::where('fundraiser_id', $fundraiserId)->where('status', 'success')->get();
        $qurbanOrders = QurbanOrder::where('fundraiser_id', $fundraiserId)->where('status', 'paid')->get();
        
        // For savings deposit, we need to join or load through saving to check fundraiser_id
        // Since we added fundraiser_id to qurban_savings, we can find deposits of those savings
        $qurbanDeposits = QurbanSavingsDeposit::whereHas('qurbanSaving', function($q) use ($fundraiserId) {
            $q->where('fundraiser_id', $fundraiserId);
        })->where('status', 'paid')->get();

        $this->totalDonationAmount = $donations->sum('amount') + $qurbanOrders->sum('amount') + $qurbanDeposits->sum('amount');
        $this->totalDonationCount = $donations->count() + $qurbanOrders->count() + $qurbanDeposits->count();
EOL;

$replaceStats = <<<'EOL'
        // 1. Total Kebaikan Tersalurkan (Donation + Qurban Orders + Qurban Deposits that are PAID)
        $donationsCount = Donation::where('fundraiser_id', $fundraiserId)->where('status', 'success')->count();
        $qurbanOrdersCount = QurbanOrder::where('fundraiser_id', $fundraiserId)->where('status', 'paid')->count();
        $qurbanDepositsCount = QurbanSavingsDeposit::whereHas('qurbanSaving', function($q) use ($fundraiserId) {
            $q->where('fundraiser_id', $fundraiserId);
        })->where('status', 'paid')->count();

        $this->totalDonationCount = $donationsCount + $qurbanOrdersCount + $qurbanDepositsCount;

        $donationsAmount = Donation::where('fundraiser_id', $fundraiserId)->where('donations.status', 'success')
            ->join('payments', 'donations.transaction_id', '=', 'payments.external_id')
            ->sum(\Illuminate\Support\Facades\DB::raw('donations.amount + COALESCE(payments.unique_code, 0)'));

        $qurbanOrdersAmount = QurbanOrder::where('fundraiser_id', $fundraiserId)->where('qurban_orders.status', 'paid')
            ->join('payments', 'qurban_orders.transaction_id', '=', 'payments.external_id')
            ->sum(\Illuminate\Support\Facades\DB::raw('qurban_orders.amount + COALESCE(payments.unique_code, 0)'));

        $qurbanDepositsAmount = QurbanSavingsDeposit::whereHas('qurbanSaving', function($q) use ($fundraiserId) {
            $q->where('fundraiser_id', $fundraiserId);
        })->where('qurban_savings_deposits.status', 'paid')
        ->join('payments', 'qurban_savings_deposits.transaction_id', '=', 'payments.external_id')
        ->sum(\Illuminate\Support\Facades\DB::raw('qurban_savings_deposits.amount + COALESCE(payments.unique_code, 0)'));

        $this->totalDonationAmount = $donationsAmount + $qurbanOrdersAmount + $qurbanDepositsAmount;
EOL;

$content = str_replace($searchStats, $replaceStats, $content);

file_put_contents($file, $content);
echo "Fixed FundraiserDashboard.php\n";

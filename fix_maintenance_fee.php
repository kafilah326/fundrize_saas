<?php
$file = 'app/Livewire/Admin/MaintenanceFee.php';
$content = file_get_contents($file);

$searchBlocks = <<<'EOL'
            $totalDonations = Donation::whereYear('created_at', $this->year)
                ->whereMonth('created_at', $m)
                ->where('status', 'success')
                ->sum('total');

            $totalQurban = QurbanOrder::whereYear('created_at', $this->year)
                ->whereMonth('created_at', $m)
                ->where('status', 'paid')
                ->sum('amount');

            $totalSavings = QurbanSavingsDeposit::whereYear('created_at', $this->year)
                ->whereMonth('created_at', $m)
                ->where('status', 'paid')
                ->sum('amount');
EOL;

$replaceBlocks = <<<'EOL'
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
EOL;

$content = str_replace($searchBlocks, $replaceBlocks, $content);

file_put_contents($file, $content);
echo "Fixed MaintenanceFee.php\n";

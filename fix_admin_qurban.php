<?php
$file = 'app/Livewire/Admin/Qurban.php';
$content = file_get_contents($file);

$searchOrders = "            // Stats for orders tab
            \$statQ = QurbanOrder::where('status', 'paid');
            \$statToday = (clone \$statQ)->whereDate('created_at', now()->toDateString())->sum('amount');
            \$statYesterday = (clone \$statQ)->whereDate('created_at', now()->subDay()->toDateString())->sum('amount');
            \$statThisMonth = (clone \$statQ)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');
            \$statLastMonth = (clone \$statQ)->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum('amount');";

$replaceOrders = "            // Stats for orders tab
            \$statQ = \App\Models\Payment::where('transaction_type', 'qurban_langsung')->where('status', 'paid');
            \$statToday = (clone \$statQ)->whereDate('created_at', now()->toDateString())->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));
            \$statYesterday = (clone \$statQ)->whereDate('created_at', now()->subDay()->toDateString())->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));
            \$statThisMonth = (clone \$statQ)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));
            \$statLastMonth = (clone \$statQ)->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));";

$content = str_replace($searchOrders, $replaceOrders, $content);

$searchSavings = "            // Stats for savings tab — based on deposits
            \$statQ = QurbanSavingsDeposit::where('status', 'paid');
            \$statToday = (clone \$statQ)->whereDate('created_at', now()->toDateString())->sum('amount');
            \$statYesterday = (clone \$statQ)->whereDate('created_at', now()->subDay()->toDateString())->sum('amount');
            \$statThisMonth = (clone \$statQ)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');
            \$statLastMonth = (clone \$statQ)->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum('amount');";

$replaceSavings = "            // Stats for savings tab — based on deposits
            \$statQ = \App\Models\Payment::where('transaction_type', 'qurban_tabungan')->where('status', 'paid');
            \$statToday = (clone \$statQ)->whereDate('created_at', now()->toDateString())->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));
            \$statYesterday = (clone \$statQ)->whereDate('created_at', now()->subDay()->toDateString())->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));
            \$statThisMonth = (clone \$statQ)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));
            \$statLastMonth = (clone \$statQ)->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum(\Illuminate\Support\Facades\DB::raw('amount + COALESCE(unique_code, 0)'));";

$content = str_replace($searchSavings, $replaceSavings, $content);

file_put_contents($file, $content);
echo "Fixed Qurban.php\n";

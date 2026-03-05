<?php
$file = 'app/Livewire/Admin/Dashboard.php';
$content = file_get_contents($file);

// 1. Fix totalDonations sum
$content = str_replace(
    "Payment::where('status', 'paid')->sum('amount');",
    "Payment::where('status', 'paid')->sum(DB::raw('amount + COALESCE(unique_code, 0)'));",
    $content
);

// 2. Fix chart query sum
$content = str_replace(
    "DB::raw('SUM(total) as total')",
    "DB::raw('SUM(amount + COALESCE(unique_code, 0)) as total')",
    $content
);

file_put_contents($file, $content);
echo "Fixed Dashboard.php\n";

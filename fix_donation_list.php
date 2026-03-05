<?php
$file = 'app/Livewire/Admin/DonationList.php';
$content = file_get_contents($file);

// 1. Add DB facade if missing
if (strpos($content, 'use Illuminate\Support\Facades\DB;') === false) {
    $content = str_replace(
        "use Illuminate\Support\Facades\Auth;",
        "use Illuminate\Support\Facades\Auth;\nuse Illuminate\Support\Facades\DB;",
        $content
    );
}

// 2. Replace sum('amount') with sum(DB::raw('amount + COALESCE(unique_code, 0)'))
$content = str_replace(
    "->sum('amount');",
    "->sum(\DB::raw('amount + COALESCE(unique_code, 0)'));",
    $content
);

file_put_contents($file, $content);
echo "Fixed DonationList.php\n";

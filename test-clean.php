<?php
$lines = file('D:\fundrize\resources\views\livewire\admin\fundraiser-list.blade.php');

$clean = [];

// Part 1: From start to line 269
for ($i = 0; $i < 270; $i++) {
    $clean[] = rtrim($lines[$i]);
}

// Add the missing closing div for space-y-6
$clean[] = '</div>';
$clean[] = '';

// Part 2: Detail Modal
// Lines 272 to 424
for ($i = 272; $i <= 424; $i++) {
    $clean[] = rtrim($lines[$i]);
}

$clean[] = '';

// Part 3: Withdrawal Detail Modal
// Lines 426 to 570
for ($i = 426; $i <= 570; $i++) {
    $clean[] = rtrim($lines[$i]);
}

file_put_contents('D:\fundrize\resources\views\livewire\admin\fundraiser-list.blade.php', implode("\n", $clean) . "\n");
echo "Cleaned file successfully!\n";

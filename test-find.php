<?php
$lines = file('D:\fundrize\resources\views\livewire\admin\fundraiser-list.blade.php');
foreach($lines as $i => $line) {
    if (strpos($line, '<!-- Detail Modal -->') !== false) {
        echo "Line " . ($i+1) . "\n";
    }
    if (strpos($line, '<!-- Withdrawal Detail Modal -->') !== false) {
        echo "Withdrawal Line " . ($i+1) . "\n";
    }
}

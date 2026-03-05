<?php
$file = 'resources/views/livewire/front/qurban-history.blade.php';
$content = file_get_contents($file);

// Replace Qurban Order amount in list
$content = str_replace(
    '<p class="text-base font-bold text-dark">Rp {{ number_format($order->amount, 0, \',\', \'.\') }}</p>',
    '<p class="text-base font-bold text-dark">Rp {{ number_format($order->amount + ($order->payment?->unique_code ?? 0), 0, \',\', \'.\') }}</p>',
    $content
);

file_put_contents($file, $content);
echo "Fixed qurban-history.blade.php\n";

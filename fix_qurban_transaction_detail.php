<?php
$file = 'resources/views/livewire/front/qurban-transaction-detail.blade.php';
$content = file_get_contents($file);

// 1. Fix hero card amount
$content = str_replace(
    '<h2 class="text-3xl font-bold text-dark mt-2 mb-2">Rp {{ number_format($order->amount, 0, \',\', \'.\') }}</h2>',
    '<h2 class="text-3xl font-bold text-dark mt-2 mb-2">Rp {{ number_format($order->amount + ($order->payment?->unique_code ?? 0), 0, \',\', \'.\') }}</h2>',
    $content
);

// 2. Fix itemized summary block at the bottom
$searchSummary = '            <div class="border-t border-gray-100 mt-4 pt-4 flex justify-between items-center">
                <span class="font-bold text-gray-700">Total Pembayaran</span>
                <span class="text-xl font-bold text-primary">Rp {{ number_format($order->amount, 0, \',\', \'.\') }}</span>
            </div>';

$replaceSummary = '            <div class="border-t border-gray-100 mt-4 pt-4 flex justify-between items-center">
                <span class="text-sm text-gray-600">Nominal Qurban</span>
                <span class="text-sm font-semibold text-dark">Rp {{ number_format($order->amount, 0, \',\', \'.\') }}</span>
            </div>
            
            @if ($order->payment && $order->payment->unique_code > 0)
            <div class="mt-2 flex justify-between items-center">
                <span class="text-sm text-gray-600">Kode Unik</span>
                <span class="text-sm font-semibold text-dark">Rp {{ number_format($order->payment->unique_code, 0, \',\', \'.\') }}</span>
            </div>
            @endif

            <div class="border-t border-dashed border-gray-200 mt-3 pt-3 flex justify-between items-center">
                <span class="font-bold text-gray-700">Total Pembayaran</span>
                <span class="text-xl font-bold text-primary">Rp {{ number_format($order->amount + ($order->payment?->unique_code ?? 0), 0, \',\', \'.\') }}</span>
            </div>';

$content = str_replace($searchSummary, $replaceSummary, $content);

file_put_contents($file, $content);
echo "Fixed qurban-transaction-detail.blade.php\n";

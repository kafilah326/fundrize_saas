<?php
$file = 'resources/views/livewire/admin/donation-list.blade.php';
$content = file_get_contents($file);

// Fix in main table (approx line 195)
$content = str_replace(
    'Rp {{ number_format($payment->amount, 0, \',\', \'.\') }}',
    'Rp {{ number_format($payment->amount + $payment->unique_code, 0, \',\', \'.\') }}
                                        @if($payment->unique_code > 0)
                                            <div class="text-[10px] font-normal text-gray-500 mt-1" title="Terdapat kode unik: Rp {{ number_format($payment->unique_code, 0, \',\', \'.\') }}">
                                                Inc. Kode Unik
                                            </div>
                                        @endif',
    $content
);

file_put_contents($file, $content);
echo "Fixed admin donation list\n";

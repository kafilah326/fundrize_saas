<?php
$file = 'resources/views/livewire/admin/user-list.blade.php';
$content = file_get_contents($file);

$content = str_replace(
    '{{ number_format($payment->amount, 0, \',\', \'.\') }}</td>',
    '{{ number_format($payment->amount + $payment->unique_code, 0, \',\', \'.\') }}</td>',
    $content
);

file_put_contents($file, $content);
echo "Fixed admin user list\n";

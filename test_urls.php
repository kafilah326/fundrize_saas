<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "url('foo/bar') -> " . url('foo/bar') . "\n";
echo "url('http://example.com/foo') -> " . url('http://example.com/foo') . "\n";
echo "asset('foo/bar.jpg') -> " . asset('foo/bar.jpg') . "\n";
echo "asset('https://cdn.com/img.jpg') -> " . asset('https://cdn.com/img.jpg') . "\n";
echo "Storage::url('foo.jpg') -> " . Storage::url('foo.jpg') . "\n";
echo "Storage::disk('public')->url('foo.jpg') -> " . Storage::disk('public')->url('foo.jpg') . "\n";

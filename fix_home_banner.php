<?php
$file = 'resources/views/livewire/front/home.blade.php';
$content = file_get_contents($file);

$searchStr = '                                        <h2 class="text-white font-bold text-base mb-2 line-clamp-2">
                                            {{ $banner->title }}</h2>';

$content = str_replace($searchStr, '', $content);

file_put_contents($file, $content);
echo "Fixed home.blade.php\n";

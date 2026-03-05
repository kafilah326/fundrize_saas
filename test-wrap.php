<?php
$content = file_get_contents('D:\fundrize\resources\views\livewire\admin\fundraiser-list.blade.php');

// Remove any existing @section tags if they exist at the very top
$content = preg_replace('/@section\([^\)]+\)\s*/', '', $content);

// Wrap everything in a single div
$newContent = "@section('title', 'Manajemen Fundriser')\n@section('header', 'Data Pendaftar Fundriser')\n\n<div>\n" . $content . "\n</div>\n";

file_put_contents('D:\fundrize\resources\views\livewire\admin\fundraiser-list.blade.php', $newContent);
echo "Wrapped in a single root element.";

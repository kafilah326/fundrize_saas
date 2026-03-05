<?php
$lines = file('D:\fundrize\resources\views\livewire\admin\fundraiser-list.blade.php');
for($i=555; $i<=580; $i++) {
    echo ($i+1) . ': ' . $lines[$i];
}

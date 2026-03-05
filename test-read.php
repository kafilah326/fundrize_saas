<?php
$lines = file('D:\fundrize\resources\views\livewire\admin\fundraiser-list.blade.php');
for($i=258; $i<=280; $i++) {
    echo ($i+1) . ': ' . $lines[$i];
}

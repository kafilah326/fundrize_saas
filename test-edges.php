<?php
$lines = file('D:\fundrize\resources\views\livewire\admin\fundraiser-list.blade.php');
echo "File starts with:\n";
for($i=0;$i<10;$i++) echo $i . ": " . $lines[$i];

echo "\nFile ends with:\n";
$total = count($lines);
for($i=$total-10;$i<$total;$i++) echo $i . ": " . $lines[$i];

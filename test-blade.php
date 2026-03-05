<?php
$content = file_get_contents('D:\fundrize\resources\views\livewire\admin\fundraiser-list.blade.php');
preg_match_all('/@(if|elseif|else|endif)\b/', $content, $matches, PREG_OFFSET_CAPTURE);
$level = 0;
foreach($matches[1] as $match) {
    if ($match[0] == 'if') $level++;
    elseif ($match[0] == 'endif') $level--;
    
    echo "@" . $match[0] . " at offset " . $match[1] . " (Level: " . $level . ")\n";
    if ($level < 0) {
        echo "ERROR: Negative level!\n";
    }
}
echo "Final level: " . $level . "\n";

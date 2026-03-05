<?php
$file = 'resources/views/livewire/admin/fundraiser-list.blade.php';
$content = file_get_contents($file);

// 1. Extract the modal block
$startPattern = '    <!-- Commission Detail Modal -->';
$endPattern = '    </div>
</div>
                                                </div>
                                                <div class="ml-4">';

$startPos = strpos($content, $startPattern);
$endPos = strpos($content, $endPattern);

if ($startPos === false || $endPos === false) {
    echo "Could not find start or end positions.\n";
    exit(1);
}

// Length to end of the modal closing tags (before the ending spaces of the avatar div)
// The modal ends at the </div> inside the </div> of the avatar.
// Let's use string manipulation more safely.

$modalContent = substr($content, $startPos, $endPos - $startPos + 11); // gets up to the </div>\n</div>\n

// The modal block we want to extract is from line 91 to 192.
// Let's read by lines to make it perfect.
$lines = file($file);

$modalLines = [];
$newLines = [];
$inModal = false;

for ($i = 0; $i < count($lines); $i++) {
    if ($i == 90) { // Line 91 is index 90
        $inModal = true;
    }
    
    if ($inModal) {
        $modalLines[] = $lines[$i];
        if ($i == 191) { // Line 192 is index 191
            $inModal = false;
        }
    } else {
        $newLines[] = $lines[$i];
    }
}

// Now we need to insert the modal Lines at the very end, before the last </div>
// The last line is index 661 which is </div>
$lastDivIndex = -1;
for ($i = count($newLines) - 1; $i >= 0; $i--) {
    if (trim($newLines[$i]) === '</div>') {
        $lastDivIndex = $i;
        break;
    }
}

array_splice($newLines, $lastDivIndex, 0, $modalLines);

file_put_contents($file, implode("", $newLines));
echo "File updated successfully.\n";

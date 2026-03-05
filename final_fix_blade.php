<?php
$file = 'resources/views/livewire/admin/fundraiser-list.blade.php';
$content = file_get_contents($file);

// 1. Reset the broken sections first to a known state if possible, 
// but since it's a mess, I will reconstruct the main container logic.

// Identify the main blocks: Tabs Nav, Controls, Table, Settings Panel, Modals.

// Fix the Controls/Table wrapping
// Remove existing broken @if tags we might have added
$content = str_replace("@if(\$activeTab !== 'settings')\n            <!-- Controls -->", "            <!-- Controls -->", $content);
$content = str_replace("@if(\$activeTab !== 'settings')\n            <div class=\"overflow-hidden rounded-xl border border-gray-100\">", "            <div class=\"overflow-hidden rounded-xl border border-gray-100\">", $content);
$content = str_replace("            </div>\n            @endif\n\n            @if(\$activeTab === 'settings')", "            </div>\n\n            @if(\$activeTab === 'settings')", $content);

// Apply a clean structure
// Wrap Controls
$content = str_replace('            <!-- Controls -->', '            @if($activeTab !== \'settings\')' . "\n" . '            <!-- Controls -->', $content);

// Wrap Table & Pagination (which ends before the first modal)
$searchTableEnd = '            @if ($data && $data->hasPages())
                <div class="mt-6">
                    {{ $data->links() }}
                </div>
            @endif';

$replaceTableEnd = '            @if ($data && $data->hasPages())
                <div class="mt-6">
                    {{ $data->links() }}
                </div>
            @endif
            @endif'; // This closes the @if($activeTab !== 'settings')

$content = str_replace($searchTableEnd, $replaceTableEnd, $content);

file_put_contents($file, $content);
echo "Blade syntax fixed.\n";

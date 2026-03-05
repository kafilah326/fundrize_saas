<?php
$file = 'resources/views/livewire/admin/fundraiser-list.blade.php';
$content = file_get_contents($file);

// Fix Program Commission Input Padding
$searchProgram = '<input wire:model="program_commission_amount" type="number" step="0.01" min="0" 
                                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow {{ $program_commission_type === \'none\' ? \'bg-gray-100 cursor-not-allowed opacity-50\' : \'\' }} {{ $program_commission_type === \'fixed\' ? \'pl-10\' : \'\' }}"';

$replaceProgram = '<input wire:model="program_commission_amount" type="number" step="0.01" min="0" 
                                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4 {{ $program_commission_type === \'none\' ? \'bg-gray-100 cursor-not-allowed opacity-50\' : \'\' }} {{ $program_commission_type === \'fixed\' ? \'pl-10\' : \'\' }}"';

$content = str_replace($searchProgram, $replaceProgram, $content);

// Fix Qurban Commission Input Padding
$searchQurban = '<input wire:model="qurban_commission_amount" type="number" step="0.01" min="0" 
                                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow {{ $qurban_commission_type === \'none\' ? \'bg-gray-100 cursor-not-allowed opacity-50\' : \'\' }} {{ $qurban_commission_type === \'fixed\' ? \'pl-10\' : \'\' }}"';

$replaceQurban = '<input wire:model="qurban_commission_amount" type="number" step="0.01" min="0" 
                                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4 {{ $qurban_commission_type === \'none\' ? \'bg-gray-100 cursor-not-allowed opacity-50\' : \'\' }} {{ $qurban_commission_type === \'fixed\' ? \'pl-10\' : \'\' }}"';

$content = str_replace($searchQurban, $replaceQurban, $content);

// Also fix select padding
$searchSelect = 'class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow"';
$replaceSelect = 'class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4"';

$content = str_replace($searchSelect, $replaceSelect, $content);

file_put_contents($file, $content);

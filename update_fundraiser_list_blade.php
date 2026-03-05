<?php
$file = 'resources/views/livewire/admin/fundraiser-list.blade.php';
$content = file_get_contents($file);

// 1. Add "Pengaturan" to Tabs
$searchTabs = '            <button wire:click="setTab(\'commissions\')"
                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $activeTab === \'commissions\' ? \'bg-primary text-white shadow-sm\' : \'text-gray-500 hover:text-gray-700 hover:bg-gray-100\' }}">
                <i class="fa-solid fa-receipt mr-1.5"></i> Riwayat Komisi
            </button>
        </div>';

$replaceTabs = '            <button wire:click="setTab(\'commissions\')"
                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $activeTab === \'commissions\' ? \'bg-primary text-white shadow-sm\' : \'text-gray-500 hover:text-gray-700 hover:bg-gray-100\' }}">
                <i class="fa-solid fa-receipt mr-1.5"></i> Riwayat Komisi
            </button>
            <button wire:click="setTab(\'settings\')"
                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $activeTab === \'settings\' ? \'bg-primary text-white shadow-sm\' : \'text-gray-500 hover:text-gray-700 hover:bg-gray-100\' }}">
                <i class="fa-solid fa-cog mr-1.5"></i> Pengaturan Ujroh
            </button>
        </div>';

$content = str_replace($searchTabs, $replaceTabs, $content);

// 2. Add Settings Content Panel
$searchTableEnd = '        </div>

        @if ($data && $data->hasPages())';

$settingsPanel = <<<'HTML'
        </div>

        @if ($activeTab === 'settings')
            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 mt-4">
                <form wire:submit.prevent="saveSettings">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Program Ujroh -->
                        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                    <i class="fa-solid fa-hand-holding-heart"></i>
                                </div>
                                Ujroh Program Reguler
                            </h3>
                            <p class="text-sm text-gray-500 mb-4">Pengaturan ini akan diterapkan untuk SEMUA program donasi aktif.</p>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Ujroh</label>
                                    <select wire:model.live="program_commission_type" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow">
                                        <option value="none">Tidak Ada Ujroh</option>
                                        <option value="fixed">Nominal Tetap (Rp)</option>
                                        <option value="percentage">Persentase (%)</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Besaran Ujroh</label>
                                    <div class="relative">
                                        @if($program_commission_type === 'fixed')
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">Rp</span>
                                            </div>
                                        @endif
                                        <input wire:model="program_commission_amount" type="number" step="0.01" min="0" 
                                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow {{ $program_commission_type === 'none' ? 'bg-gray-100 cursor-not-allowed opacity-50' : '' }} {{ $program_commission_type === 'fixed' ? 'pl-10' : '' }}"
                                            {{ $program_commission_type === 'none' ? 'disabled' : '' }}>
                                    </div>
                                    @error('program_commission_amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Qurban Ujroh -->
                        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                    <i class="fa-solid fa-cow"></i>
                                </div>
                                Ujroh Qurban
                            </h3>
                            <p class="text-sm text-gray-500 mb-4">Pengaturan ini akan diterapkan untuk SEMUA transaksi Qurban Langsung & Tabungan.</p>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Ujroh</label>
                                    <select wire:model.live="qurban_commission_type" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow">
                                        <option value="none">Tidak Ada Ujroh</option>
                                        <option value="fixed">Nominal Tetap (Rp)</option>
                                        <option value="percentage">Persentase (%)</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Besaran Ujroh</label>
                                    <div class="relative">
                                        @if($qurban_commission_type === 'fixed')
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">Rp</span>
                                            </div>
                                        @endif
                                        <input wire:model="qurban_commission_amount" type="number" step="0.01" min="0" 
                                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow {{ $qurban_commission_type === 'none' ? 'bg-gray-100 cursor-not-allowed opacity-50' : '' }} {{ $qurban_commission_type === 'fixed' ? 'pl-10' : '' }}"
                                            {{ $qurban_commission_type === 'none' ? 'disabled' : '' }}>
                                    </div>
                                    @error('qurban_commission_amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-primary text-white font-bold rounded-xl shadow-sm hover:bg-primary-hover transition-colors flex items-center gap-2">
                            <i class="fa-solid 

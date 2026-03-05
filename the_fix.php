<?php
$file = 'resources/views/livewire/admin/fundraiser-list.blade.php';
$content = file_get_contents($file);

$searchTabs = '                <button wire:click="setTab(\'commissions\')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2
                    {{ $activeTab === \'commissions\' ? \'text-primary border-primary bg-primary/5\' : \'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50\' }}">
                    <i class="fa-solid fa-receipt mr-2"></i> Riwayat Komisi
                </button>
            </nav>';

$replaceTabs = '                <button wire:click="setTab(\'commissions\')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2
                    {{ $activeTab === \'commissions\' ? \'text-primary border-primary bg-primary/5\' : \'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50\' }}">
                    <i class="fa-solid fa-receipt mr-2"></i> Riwayat Komisi
                </button>
                <button wire:click="setTab(\'settings\')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2
                    {{ $activeTab === \'settings\' ? \'text-primary border-primary bg-primary/5\' : \'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50\' }}">
                    <i class="fa-solid fa-cog mr-2"></i> Pengaturan Ujroh
                </button>
            </nav>';

$content = str_replace($searchTabs, $replaceTabs, $content);

$searchControls = '            <!-- Controls -->
            <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 mb-6">';

$replaceControls = '            <!-- Controls -->
            @if($activeTab !== \'settings\')
            <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 mb-6">';

$content = str_replace($searchControls, $replaceControls, $content);

$searchControlsEnd = '            </div>

            <!-- Tab Content -->
            <div class="overflow-hidden rounded-xl border border-gray-100">';

$replaceControlsEnd = '            </div>
            @endif

            <!-- Tab Content -->
            @if($activeTab !== \'settings\')
            <div class="overflow-hidden rounded-xl border border-gray-100">';

$content = str_replace($searchControlsEnd, $replaceControlsEnd, $content);

$searchTableEnd = '            </div>

            @if ($data && $data->hasPages())';

$settingsPanel = '            </div>
            @endif

            @if($activeTab === \'settings\')
            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 mt-4">
                <form wire:submit.prevent="saveSettings">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                    <i class="fa-solid fa-hand-holding-heart"></i>
                                </div>
                                Ujroh Program Reguler
                            </h3>
                            <p class="text-sm text-gray-500 mb-4">Pengaturan ini akan diterapkan secara global untuk SEMUA program donasi aktif.</p>
                            
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
                                    <input wire:model="program_commission_amount" type="number" step="0.01" min="0" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow">
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                    <i class="fa-solid fa-cow"></i>
                                </div>
                                Ujroh Qurban
                            </h3>
                            <p class="text-sm text-gray-500 mb-4">Pengaturan ini akan diterapkan secara global untuk SEMUA transaksi Qurban.</p>
                            
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
                                    <input wire:model="qurban_commission_amount" type="number" step="0.01" min="0" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end border-t border-gray-200 pt-6">
                        <button type="submit" class="px-6 py-2.5 bg-primary text-white font-bold rounded-xl shadow-sm hover:bg-primary-hover transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-save"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
            @endif

            @if ($activeTab !== \'settings\' && $data && $data->hasPages())';

$content = str_replace($searchTableEnd, $settingsPanel, $content);
file_put_contents($file, $content);

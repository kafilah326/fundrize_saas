<?php
$file = 'resources/views/livewire/admin/program.blade.php';
$content = file_get_contents($file);

$searchBlock = <<<'EOL'
                                <!-- Commission Settings -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-orange-50/50 rounded-2xl border border-orange-100">
                                    <div class="md:col-span-2">
                                        <h4 class="text-sm font-bold text-orange-800 flex items-center gap-2">
                                            <i class="fa-solid fa-percent"></i> Pengaturan Komisi Fundriser
                                        </h4>
                                    </div>
                                    <div>
                                        <label for="commission_type" class="block text-sm font-semibold text-gray-700 mb-1">Tipe Komisi Fundriser</label>
                                        <select wire:model.live="commission_type" id="commission_type"
                                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                            <option value="none">Tidak Ada Komisi</option>
                                            <option value="fixed">Nominal Tetap (Rp)</option>
                                            <option value="percentage">Persentase (%)</option>
                                        </select>
                                        @error('commission_type')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="commission_amount" class="block text-sm font-semibold text-gray-700 mb-1">Besaran Komisi</label>
                                        <input wire:model="commission_amount" type="number" step="0.01" min="0" id="commission_amount"
                                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors {{ $commission_type === 'none' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $commission_type === 'none' ? 'disabled' : '' }}>
                                        @error('commission_amount')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
EOL;

$content = str_replace($searchBlock, '', $content);
file_put_contents($file, $content);
echo "Cleaned program.blade.php\n";

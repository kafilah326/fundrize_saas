<div>
    <x-page-header title="Buat Tabungan Qurban" :showBack="true" backUrl="{{ route('qurban.tabungan') }}" />

    <main id="main-content" class="pb-52">
        <!-- Target Selection -->
        <section id="target-section" class="bg-white px-4 py-5 mb-2">
            <h2 class="text-sm font-bold text-dark mb-3">Pilih Target Tabungan</h2>
            <div class="space-y-2">
                @foreach ($targets as $key => $data)
                    <label
                        class="flex items-start gap-3 p-3 border-2 rounded-xl cursor-pointer transition-colors
                    {{ $target === $key ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white hover:border-primary/50' }}">
                        <div class="mt-1">
                            <input type="radio" wire:model.live="target" value="{{ $key }}"
                                class="w-4 h-4 text-primary focus:ring-primary">
                        </div>

                        <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                            <img src="{{ $data['image'] ?? 'https://placehold.co/100x100?text=No+Image' }}"
                                alt="{{ $data['name'] }}" class="w-full h-full object-cover">
                        </div>

                        <div class="flex-1">
                            <div class="text-sm font-bold text-dark">{{ $data['name'] }}</div>
                            <div class="text-primary font-bold text-sm">Rp
                                {{ number_format($data['price'], 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-600 mt-0.5 line-clamp-2">{{ $data['desc'] }}</div>
                        </div>
                    </label>
                @endforeach
            </div>
        </section>

        <!-- Donor Data -->
        <section id="donor-section" class="bg-white px-4 py-5 mb-2">
            <h2 class="text-sm font-bold text-dark mb-3">Data Muqorib</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1.5 block">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model.live="name" placeholder="Masukkan nama lengkap"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        {{ $isAnonymous ? 'disabled' : '' }}>
                    @error('name')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1.5 block">Nomor WhatsApp <span
                            class="text-red-500">*</span></label>
                    <input type="tel" wire:model.live="whatsapp" placeholder="08xxxxxxxxxx"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    @error('whatsapp')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1.5 block">Atas Nama Qurban <span
                            class="text-gray-400 text-xs">(Opsional)</span></label>
                    <input type="text" wire:model.live="qurbanName" placeholder="Untuk penyebutan niat/sertifikat"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1.5 block">Email <span
                            class="text-gray-400 text-xs">(Opsional)</span></label>
                    <input type="email" wire:model.live="email" placeholder="email@contoh.com"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="isAnonymous"
                        class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <span class="text-xs text-gray-700">Anonim (Hamba Allah)</span>
                </label>
            </div>
        </section>

        <!-- Deposit Section -->
        <section id="deposit-section" class="bg-white px-4 py-5 mb-2">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-bold text-dark">Setoran Pertama</h2>
                <span class="text-xs text-gray-500">Disarankan</span>
            </div>

            <div class="grid grid-cols-2 gap-2 mb-3">
                @foreach ([50000, 100000, 250000, 500000] as $val)
                    <button wire:click="setDeposit({{ $val }})"
                        class="nominal-btn py-2.5 px-3 rounded-lg text-sm font-semibold {{ $deposit == $val && !$showCustomDeposit ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Rp {{ number_format($val, 0, ',', '.') }}
                    </button>
                @endforeach
            </div>

            <div>
                <label class="text-xs font-medium text-gray-700 mb-1.5 block">Nominal Lainnya</label>
                <div
                    class="flex items-center gap-2 border rounded-lg px-3 focus-within:ring-2 focus-within:ring-primary/30 transition-all
                    {{ $customDepositError ? 'border-red-400 focus-within:border-red-400' : 'border-gray-300 focus-within:border-primary' }}">
                    <span class="text-sm text-gray-600">Rp</span>
                    <input type="number" wire:model.live="customDeposit" wire:focus="setDeposit('custom')"
                        min="10000" placeholder="0"
                        class="flex-1 py-2.5 text-sm focus:outline-none w-full bg-transparent">
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimum setoran Rp 10.000</p>
                @if ($customDepositError)
                    <p class="text-xs text-red-600 mt-1">{{ $customDepositError }}</p>
                @endif
                @error('deposit')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button wire:click="setDeposit(0)" class="text-xs text-primary mt-3 underline hover:text-primary/80">Lewati
                setoran pertama</button>
        </section>

        <!-- Reminder Section -->
        <section id="reminder-section" class="bg-white px-4 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-dark mb-1">Pengingat</h2>
                    <p class="text-xs text-gray-600">Ingatkan saya untuk menabung</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.live="reminder" class="sr-only peer">
                    <div
                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary transition-colors">
                    </div>
                </label>
            </div>

            @if ($reminder)
                <div class="mt-3 transition-all duration-300">
                    <select wire:model.live="reminderFrequency"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="bulanan">Bulanan</option>
                        <option value="mingguan">Mingguan</option>
                    </select>
                </div>
            @endif
        </section>
    </main>

    <!-- Sticky Summary -->
    <div id="sticky-summary"
        class="fixed bottom-0 left-0 right-0 max-w-[460px] mx-auto bg-white border-t-2 border-gray-200 p-4 z-50">
        <div class="space-y-2 mb-3">
            <div class="flex justify-between text-xs">
                <span class="text-gray-600">Target</span>
                @php
                    $selectedTarget = $targets[$target] ?? collect($targets)->first();
                @endphp
                <span class="font-semibold text-dark">{{ $selectedTarget['name'] }} – Rp
                    {{ number_format($selectedTarget['price'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-600">Setoran pertama</span>
                <span
                    class="font-semibold text-dark">{{ $deposit > 0 ? 'Rp ' . number_format($deposit, 0, ',', '.') : '-' }}</span>
            </div>
            <div class="h-px bg-gray-200"></div>
            <div class="flex justify-between text-sm">
                <span class="font-bold text-dark">Total Bayar</span>
                <span class="font-bold text-primary">Rp {{ number_format($deposit, 0, ',', '.') }}</span>
            </div>
        </div>

        <button wire:click="submit"
            class="w-full bg-primary text-white py-3 rounded-xl text-sm font-bold shadow-lg active:scale-95 transition-transform hover:bg-primary/90">
            {{ $deposit > 0 ? 'Buat Tabungan & Setor' : 'Buat Tabungan' }}
        </button>
    </div>
</div>

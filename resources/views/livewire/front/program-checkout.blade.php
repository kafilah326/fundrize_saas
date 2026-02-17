<div>
    <x-page-header title="Form Donasi" :showBack="true" />

    <main id="main-content" class="pb-52">
        <!-- Program Summary -->
        <section id="program-summary" class="bg-white px-4 py-4">
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($program->categories as $category)
                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">{{ $category->name }}</span>
                @endforeach
            </div>
            <h2 class="text-lg font-bold text-dark">{{ $program->title }}</h2>
        </section>

        <!-- Amount Section -->
        <section id="amount-section" class="bg-white px-4 py-6 mt-2">
            <h3 class="text-base font-bold text-dark mb-4">Nominal Donasi</h3>

            <div class="grid grid-cols-2 gap-3 mb-4">
                @foreach([10000, 25000, 50000, 100000, 250000] as $value)
                    <button wire:click="setAmount({{ $value }})"
                        class="px-4 py-3 border-2 rounded-lg text-sm font-semibold transition-colors
                        {{ $amount == $value && !$showCustomAmount ? 'border-primary bg-primary text-white' : 'border-gray-200 text-gray-700 hover:border-primary hover:text-primary' }}">
                        Rp {{ number_format($value, 0, ',', '.') }}
                    </button>
                @endforeach
                <button wire:click="setAmount('custom')"
                    class="px-4 py-3 border-2 rounded-lg text-sm font-semibold transition-colors
                    {{ $showCustomAmount ? 'border-primary text-primary' : 'border-gray-200 text-gray-700 hover:border-primary hover:text-primary' }}">
                    Lainnya
                </button>
            </div>

            @if($showCustomAmount)
            <div id="custom-amount">
                <label class="block text-sm font-semibold text-dark mb-2">Nominal Bebas</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                    <input type="number" wire:model.live="customAmount"
                        class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary"
                        placeholder="0" />
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimum donasi Rp 10.000</p>
                @error('amount') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            @endif
        </section>

        <!-- Donor Data -->
        <section id="donor-data" class="bg-white px-4 py-6 mt-2">
            <h3 class="text-base font-bold text-dark mb-4">Data Donatur</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-dark mb-2">Nama Lengkap *</label>
                    <input type="text" wire:model="name"
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary"
                        placeholder="Masukkan nama lengkap" 
                        {{ $isAnonymous ? 'disabled' : '' }} />
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-dark mb-2">Nomor WhatsApp *</label>
                    <input type="tel" wire:model="phone"
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary"
                        placeholder="08xxx" />
                    @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-dark mb-2">Email (Opsional)</label>
                    <input type="email" wire:model="email"
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary"
                        placeholder="email@example.com" />
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="anonymous" wire:model.live="isAnonymous"
                        class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" />
                    <label for="anonymous" class="text-sm text-gray-700">Donasi sebagai Hamba Allah (Anonim)</label>
                </div>
            </div>
        </section>
    </main>

    <!-- Summary CTA -->
    <div id="summary-cta" class="fixed bottom-0 left-0 right-0 max-w-[460px] mx-auto bg-white border-t border-gray-200 px-4 py-4 z-50">
        <div class="mb-3">
            <div class="flex items-center justify-between text-sm mb-1">
                <span class="text-gray-600">Total Donasi</span>
                <span class="font-bold text-dark">Rp {{ number_format($showCustomAmount ? (int)$customAmount : $amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500">Biaya Admin</span>
                <span class="text-gray-500">Gratis</span>
            </div>
            <div class="border-t border-gray-200 pt-2 mt-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-dark">Total Bayar</span>
                    <span class="text-lg font-bold text-primary">Rp {{ number_format($showCustomAmount ? (int)$customAmount : $amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <button wire:click="submit"
            class="w-full py-4 bg-primary text-white rounded-xl text-base font-bold shadow-lg active:scale-95 transition-transform disabled:bg-gray-300 disabled:cursor-not-allowed">
            Lanjutkan Pembayaran
        </button>
    </div>
</div>

<div>
    <!-- Topbar -->
    <div class="fixed top-0 left-0 right-0 max-w-[460px] mx-auto bg-white border-b border-gray-100 z-50">
        <div class="flex items-center px-4 h-14">
            <a href="{{ route('program.detail', $slug) }}" wire:navigate
                class="p-2 -ml-2 text-gray-600 hover:bg-gray-50 rounded-full transition-colors">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </a>
            <h1 class="text-lg font-bold text-dark ml-2">Checkout Pembayaran</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="pt-14 pb-24 min-h-screen bg-gray-50">
        <form wire:submit.prevent="submit">
            <!-- Program Info Card -->
            <div class="bg-white p-4 mb-2 shadow-sm">
                <div class="flex gap-3 items-center">
                    <img src="{{ $program->image }}" alt="{{ $program->title }}"
                        class="w-16 h-16 rounded-xl object-cover shrink-0">
                    <div>
                        <h3 class="font-bold text-dark text-sm line-clamp-2 leading-tight">{{ $program->title }}</h3>
                        <div class="text-xs text-primary font-semibold mt-1">
                            Rp {{ number_format($program->package_price, 0, ',', '.') }} / {{ $packageLabel }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Package Input Card -->
            <div class="bg-white p-5 mb-2 shadow-sm">
                <h3 class="font-bold text-dark text-sm mb-4">Pilih Jumlah {{ ucfirst($packageLabel) }}</h3>
                <div class="flex items-center justify-between bg-primary/5 rounded-2xl p-4 border border-primary/20">
                    <div class="text-sm font-semibold text-gray-700">Jumlah</div>
                    <div class="flex items-center gap-4">
                        <button type="button" wire:click="decrementQuantity"
                            class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-full text-gray-600 shadow-sm active:scale-95 transition-all outline-none focus:ring focus:ring-primary/20 hover:bg-gray-50 disabled:opacity-50"
                            {{ $quantity <= 1 ? 'disabled' : '' }}>
                            <i class="fa-solid fa-minus text-sm"></i>
                        </button>

                        <div class="w-16">
                            <input type="number" wire:model.live.debounce.300ms="quantity" min="1"
                                class="w-full text-center text-xl font-bold text-dark bg-transparent border-none p-0 focus:ring-0">
                        </div>

                        <button type="button" wire:click="incrementQuantity"
                            class="w-10 h-10 flex items-center justify-center bg-primary border border-primary rounded-full text-white shadow-sm shadow-primary/30 active:scale-95 transition-all outline-none focus:ring focus:ring-primary/30 hover:bg-primary-hover">
                            <i class="fa-solid fa-plus text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Total Amount Display -->
                <div class="mt-4 pt-4 border-t border-dashed border-gray-200 flex justify-between items-center">
                    <div class="text-sm text-gray-600">Total Pembayaran</div>
                    <div class="text-xl font-bold text-primary">
                        Rp {{ number_format($totalAmount, 0, ',', '.') }}
                    </div>
                </div>
                <div class="text-xs text-gray-500 text-right mt-1">
                    ({{ $quantity }} {{ $packageLabel }} × Rp
                    {{ number_format($program->package_price, 0, ',', '.') }})
                </div>
            </div>

            <!-- Donor Form using same styling as program-checkout -->
            <div class="bg-white p-4 shadow-sm mb-2">
                <h3 class="font-bold text-dark text-sm mb-4">Informasi Donatur</h3>

                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <input wire:model="name" type="text" placeholder="Nama Lengkap"
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-primary focus:bg-white focus:ring focus:ring-primary/20 transition-colors {{ $isAnonymous ? 'opacity-50 bg-gray-100 cursor-not-allowed text-gray-400' : '' }}"
                            {{ $isAnonymous ? 'readonly' : '' }}>
                        @error('name')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone (WhatsApp) -->
                    <div>
                        <input wire:model="phone" type="tel" placeholder="Nomor WhatsApp"
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-primary focus:bg-white focus:ring focus:ring-primary/20 transition-colors">
                        @error('phone')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <input wire:model="email" type="email" placeholder="Email (Opsional)"
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-primary focus:bg-white focus:ring focus:ring-primary/20 transition-colors">
                        @error('email')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Anonymous Toggle -->
                    <label
                        class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 cursor-pointer">
                        <div class="relative flex items-center">
                            <input wire:model="isAnonymous" type="checkbox"
                                class="peer h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary cursor-pointer transition-all checked:bg-primary checked:border-transparent">
                            <i
                                class="fa-solid fa-check absolute text-[10px] text-white opacity-0 peer-checked:opacity-100 left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Sembunyikan nama saya (Hamba Allah)</span>
                    </label>
                </div>
            </div>

            <!-- Doa Section -->
            <div class="bg-white p-4 mb-2 shadow-sm">
                <h3 class="font-bold text-dark text-sm mb-4">Sertakan Doa (Opsional)</h3>
                <textarea wire:model="doa" rows="3" placeholder="Tulis doa atau harapan untuk donasi ini..."
                    class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-primary focus:bg-white focus:ring focus:ring-primary/20 transition-colors resize-none"></textarea>

                <div class="mt-4 flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                    <button type="button" wire:click="$set('doa', 'Semoga menjadi amal jariyah dan membawa berkah.')"
                        class="shrink-0 bg-primary/5 hover:bg-primary/10 text-primary border border-primary/20 px-3 py-1.5 rounded-full text-xs font-medium transition-colors">
                        Amal Jariyah
                    </button>
                    <button type="button"
                        wire:click="$set('doa', 'Semoga Allah memberikan kesehatan dan kelancaran rezeki.')"
                        class="shrink-0 bg-primary/5 hover:bg-primary/10 text-primary border border-primary/20 px-3 py-1.5 rounded-full text-xs font-medium transition-colors">
                        Berkah Kesehatan
                    </button>
                    <button type="button" wire:click="$set('doa', 'Bismillah, niat sedekah karena Allah Ta\'ala.')"
                        class="shrink-0 bg-primary/5 hover:bg-primary/10 text-primary border border-primary/20 px-3 py-1.5 rounded-full text-xs font-medium transition-colors">
                        Niat Sedekah
                    </button>
                </div>
            </div>

            <div class="p-4 bg-orange-50/50 mb-2 border-y border-orange-100">
                <div class="flex items-start gap-3 text-sm text-gray-600">
                    <i class="fa-solid fa-shield-halal text-green-600 mt-0.5 text-lg"></i>
                    <p class="leading-relaxed text-xs">Dengan melanjutkan pembayaran, Anda menyetujui syarat & ketentuan
                        penggalangan dana di platform ini. Transaksi ini aman dan transparan.</p>
                </div>
            </div>

            <!-- CTA -->
            <div
                class="fixed bottom-0 left-0 right-0 max-w-[460px] mx-auto bg-white border-t border-gray-200 px-4 py-3 z-40">
                <button type="submit"
                    class="w-full py-3.5 bg-primary text-white rounded-xl text-base font-bold shadow-lg shadow-primary/30 active:scale-95 transition-all text-center flex items-center justify-center hover:bg-primary-hover gap-2">
                    <span>Lanjutkan Pembayaran</span>
                    <i class="fa-solid fa-arrow-right text-sm relative top-[1px]"></i>
                </button>
            </div>
        </form>
    </div>
</div>

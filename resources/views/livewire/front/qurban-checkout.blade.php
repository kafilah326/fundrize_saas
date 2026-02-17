<div>
    <x-page-header title="Formulir Qurban" :showBack="true" backUrl="{{ route('qurban.index') }}" />

    <main id="main-content" class="pb-32">
        <!-- Summary Section -->
        <section id="summary-section" class="bg-white px-4 py-5 mb-2">
            <h2 class="text-sm font-bold text-dark mb-3">Ringkasan Qurban</h2>
            <div class="flex items-center gap-3 p-3 bg-orange-50 border border-orange-100 rounded-xl">
                <div class="w-16 h-16 bg-white rounded-lg overflow-hidden flex-shrink-0">
                    <img src="{{ $animal['image'] }}" alt="{{ $animal['name'] }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="text-sm font-bold text-dark">{{ $animal['name'] }}</h3>
                    <div class="text-xs text-gray-500 mt-1">{{ $animal['weight'] }}</div>
                    <div class="text-sm font-bold text-primary mt-1">Rp {{ number_format($animal['price'], 0, ',', '.') }}</div>
                </div>
            </div>
        </section>

        <!-- Form Section -->
        <section id="form-section" class="bg-white px-4 py-5 mb-2">
            <h2 class="text-sm font-bold text-dark mb-3">Data Muqorib</h2>
            <div class="space-y-3.5">
                <!-- Name -->
                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1.5 block">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.live="name" placeholder="Masukkan nama lengkap" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary disabled:bg-gray-100 disabled:text-gray-500" {{ $isAnonymous ? 'disabled' : '' }}>
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- WhatsApp -->
                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1.5 block">Nomor WhatsApp <span class="text-red-500">*</span></label>
                    <input type="tel" wire:model.live="whatsapp" placeholder="08xxxxxxxxxx" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    @error('whatsapp') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Atas Nama -->
                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1.5 block">Atas Nama Qurban <span class="text-gray-400 text-xs">(Opsional)</span></label>
                    <input type="text" wire:model.live="qurbanName" placeholder="Untuk penyebutan niat/sertifikat" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary disabled:bg-gray-100 disabled:text-gray-500" {{ $isAnonymous ? 'disabled' : '' }}>
                    <p class="text-[10px] text-gray-500 mt-1">Kosongkan jika sama dengan nama di atas</p>
                </div>

                <!-- Email -->
                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1.5 block">Email <span class="text-gray-400 text-xs">(Opsional)</span></label>
                    <input type="email" wire:model.live="email" placeholder="email@contoh.com" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>

                <!-- Address -->
                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1.5 block">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea wire:model.live="address" rows="3" placeholder="Masukkan alamat lengkap untuk pengiriman daging" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"></textarea>
                    @error('address') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- City & Zip -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-medium text-gray-700 mb-1.5 block">Kota/Kabupaten <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.live="city" placeholder="Contoh: Jakarta" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        @error('city') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-700 mb-1.5 block">Kode Pos <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.live="postalCode" maxlength="5" placeholder="12345" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        @error('postalCode') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Anonim Checkbox -->
                <label class="flex items-center gap-2 cursor-pointer mt-2">
                    <input type="checkbox" wire:model.live="isAnonymous" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <span class="text-xs text-gray-700">Anonim (Hamba Allah)</span>
                </label>
            </div>
        </section>

        <!-- Slaughter Method -->
        <section class="bg-white px-4 py-5 mb-2">
            <h2 class="text-sm font-bold text-dark mb-3">Metode Penyembelihan</h2>
            <div class="space-y-2">
                <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer transition-colors"
                    :class="$wire.slaughterMethod === 'wakalah' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white hover:border-primary/50'">
                    <input type="radio" wire:model.live="slaughterMethod" value="wakalah" class="mt-0.5 w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                    <div>
                        <div class="text-sm font-semibold text-dark">Wakalah (Diwakilkan)</div>
                        <div class="text-xs text-gray-500 mt-0.5">Disembelih oleh panitia qurban Yayasan Peduli</div>
                    </div>
                </label>
                
                <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer transition-colors"
                    :class="$wire.slaughterMethod === 'hadir' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white hover:border-primary/50'">
                    <input type="radio" wire:model.live="slaughterMethod" value="hadir" class="mt-0.5 w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                    <div>
                        <div class="text-sm font-semibold text-dark">Hadir Sendiri</div>
                        <div class="text-xs text-gray-500 mt-0.5">Menyaksikan penyembelihan di lokasi (Ponorogo, Jawa Timur)</div>
                    </div>
                </label>
            </div>
            @error('slaughterMethod') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        </section>

        <!-- Delivery Method -->
        <section class="bg-white px-4 py-5">
            <h2 class="text-sm font-bold text-dark mb-3">Pengiriman Daging</h2>
            <div class="space-y-2">
                <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer transition-colors"
                    :class="$wire.deliveryMethod === 'dikirim' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white hover:border-primary/50'">
                    <input type="radio" wire:model.live="deliveryMethod" value="dikirim" class="mt-0.5 w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                    <div>
                        <div class="text-sm font-semibold text-dark">Dikirim ke Alamat</div>
                        <div class="text-xs text-gray-500 mt-0.5">Dikirim H+3 Idul Adha (Area Jawa Timur)</div>
                    </div>
                </label>
                
                <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer transition-colors"
                    :class="$wire.deliveryMethod === 'ambil' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white hover:border-primary/50'">
                    <input type="radio" wire:model.live="deliveryMethod" value="ambil" class="mt-0.5 w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                    <div>
                        <div class="text-sm font-semibold text-dark">Ambil Sendiri</div>
                        <div class="text-xs text-gray-500 mt-0.5">Diambil di lokasi penyembelihan (Ponorogo, Jawa Timur)</div>
                    </div>
                </label>
                
                <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer transition-colors"
                    :class="$wire.deliveryMethod === 'wakaf' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white hover:border-primary/50'">
                    <input type="radio" wire:model.live="deliveryMethod" value="wakaf" class="mt-0.5 w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                    <div>
                        <div class="text-sm font-semibold text-dark">Tidak diambil (Wakaf)</div>
                        <div class="text-xs text-gray-500 mt-0.5">Disalurkan sepenuhnya kepada yang membutuhkan</div>
                    </div>
                </label>
            </div>
            @error('deliveryMethod') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        </section>
    </main>

    <!-- Sticky Summary -->
    <div id="sticky-button" class="fixed bottom-0 left-0 right-0 max-w-[460px] mx-auto bg-white border-t border-gray-200 p-4 z-50">
        <div class="flex items-center justify-between mb-3 text-sm">
            <span class="font-bold text-dark">Total Pembayaran</span>
            <span class="font-bold text-primary">Rp {{ number_format($animal['price'], 0, ',', '.') }}</span>
        </div>
        <button wire:click="submit" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-bold shadow-lg active:scale-95 transition-transform hover:bg-primary/90">
            Lanjut Pembayaran
        </button>
    </div>
</div>

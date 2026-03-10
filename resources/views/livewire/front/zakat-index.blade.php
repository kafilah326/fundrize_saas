<div x-data="{
    share() {
        if (navigator.share) {
            let url = window.location.href;
            @auth
@if (auth()->user()->fundraiser && auth()->user()->fundraiser->status === 'approved')
                    if (!url.includes('ref=')) {
                        url += (url.includes('?') ? '&' : '?') + 'ref={{ auth()->user()->fundraiser->referral_code }}';
                    }
                @endif @endauth

            navigator.share({
                title: 'Tunaikan Zakat',
                text: 'Mari tunaikan kewajiban Zakat bersama kami.',
                url: url
            });
        } else {
            alert('Fitur share tidak didukung di browser ini');
        }
    }
}">
    <x-page-header title="Tunaikan Zakat" subtitle="Hitung dan bayar zakat Anda dalam satu langkah">
        <x-slot:actions>
            <button @click="share()" class="w-9 h-9 flex items-center justify-center">
                <i class="fa-solid fa-share-nodes text-dark text-lg"></i>
            </button>
        </x-slot:actions>
    </x-page-header>

    <main class="pb-32">
        {{-- Tabs --}}
        <section class="bg-white px-4 py-4">
            <div class="flex bg-gray-100 rounded-xl p-1">
                <button wire:click="setTab('fitrah')"
                    class="flex-1 py-2.5 px-4 rounded-lg text-sm font-semibold transition-all
                           {{ $activeTab === 'fitrah' ? 'bg-white text-dark shadow-sm' : 'text-gray-600' }}">
                    Zakat Fitrah
                </button>
                <button wire:click="setTab('maal')"
                    class="flex-1 py-2.5 px-4 rounded-lg text-sm font-semibold transition-all
                           {{ $activeTab === 'maal' ? 'bg-white text-dark shadow-sm' : 'text-gray-600' }}">
                    Zakat Mal
                </button>
            </div>
        </section>

        <div class="px-4 mt-4">
            {{-- ===== FITRAH TAB ===== --}}
            @if ($activeTab === 'fitrah')
                <section class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                    <h3 class="text-base font-bold text-dark mb-4">Zakat Fitrah</h3>
                    <div class="space-y-4">
                        {{-- Jumlah Jiwa --}}
                        <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                            <div class="bg-gray-100 px-4 py-3 flex items-center border-r border-gray-300 min-w-[110px]">
                                <span class="text-sm text-gray-600 font-medium">Jumlah Jiwa</span>
                            </div>
                            <div class="relative flex-1">
                                <select wire:model.live="fitrahPeople"
                                    class="w-full h-full appearance-none bg-white px-4 py-2.5 text-sm text-dark focus:outline-none cursor-pointer">
                                    @for ($i = 1; $i <= 20; $i++)
                                        <option value="{{ $i }}">{{ $i }} Jiwa</option>
                                    @endfor
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Nominal --}}
                        <div>
                            <label class="block text-base font-bold text-dark mb-2">Nominal:</label>
                            <div class="flex rounded-lg border border-gray-300 overflow-hidden bg-gray-50">
                                <div
                                    class="bg-gray-200/50 px-4 py-3 flex items-center border-r border-gray-300 min-w-[60px] justify-center">
                                    <span class="text-sm text-gray-600 font-semibold">Rp.</span>
                                </div>
                                <input type="text" value="{{ number_format($calculatedZakat, 0, ',', '.') }}"
                                    readonly
                                    class="flex-1 bg-gray-100 px-4 py-3 text-sm text-dark font-medium focus:outline-none">
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                *Nominal otomatis dihitung berdasarkan jumlah jiwa
                                (Rp {{ number_format($fitrahPrice, 0, ',', '.') }}/jiwa)
                            </p>
                        </div>
                    </div>
                </section>
            @endif

            {{-- ===== MAAL TAB ===== --}}
            @if ($activeTab === 'maal')
                <section class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                    <h3 class="text-base font-bold text-dark mb-4">Zakat Mal</h3>

                    <div class="space-y-3 mb-4">
                        {{-- Card: Manual --}}
                        <div wire:click="setMaalMode('manual')"
                            class="border-2 rounded-xl p-3 cursor-pointer transition-all
                                   {{ $maalMode === 'manual' ? 'border-primary' : 'border-gray-200' }}">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-5 h-5 border-2 rounded-full flex items-center justify-center flex-shrink-0
                                            {{ $maalMode === 'manual' ? 'border-primary' : 'border-gray-300' }}">
                                    <div
                                        class="w-2.5 h-2.5 bg-primary rounded-full {{ $maalMode === 'manual' ? '' : 'hidden' }}">
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-dark">Saya sudah tahu nominalnya</span>
                            </div>
                            @if ($maalMode === 'manual')
                                <div class="mt-3">
                                    <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                                        <div class="bg-gray-100 px-4 py-3 flex items-center border-r border-gray-300">
                                            <span class="text-sm text-gray-600 font-semibold">Rp</span>
                                        </div>
                                        <input type="text" wire:model.live="maalManualAmount" placeholder="0"
                                            class="flex-1 px-4 py-3 text-sm text-dark focus:outline-none" x-data
                                            x-on:input="$event.target.value = $event.target.value.replace(/[^0-9]/g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')">
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Card: Calculator --}}
                        <div wire:click="setMaalMode('calculator')"
                            class="border-2 rounded-xl p-3 cursor-pointer transition-all
                                   {{ $maalMode === 'calculator' ? 'border-primary' : 'border-gray-200' }}">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-5 h-5 border-2 rounded-full flex items-center justify-center flex-shrink-0
                                            {{ $maalMode === 'calculator' ? 'border-primary' : 'border-gray-300' }}">
                                    <div
                                        class="w-2.5 h-2.5 bg-primary rounded-full {{ $maalMode === 'calculator' ? '' : 'hidden' }}">
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-dark">Bantu hitung zakat saya</span>
                            </div>

                            @if ($maalMode === 'calculator')
                                <div class="mt-4 space-y-3" wire:click.stop>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Emas, perak &amp;
                                            permata</label>
                                        <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                                            <span
                                                class="px-3 py-2 bg-gray-50 border-r border-gray-300 text-sm text-gray-500">Rp</span>
                                            <input type="text" wire:model.live="emas" placeholder="0"
                                                class="flex-1 px-3 py-2 text-sm focus:outline-none" x-data
                                                x-on:input="$event.target.value = $event.target.value.replace(/[^0-9]/g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Uang tunai, tabungan
                                            &amp; deposito</label>
                                        <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                                            <span
                                                class="px-3 py-2 bg-gray-50 border-r border-gray-300 text-sm text-gray-500">Rp</span>
                                            <input type="text" wire:model.live="uang" placeholder="0"
                                                class="flex-1 px-3 py-2 text-sm focus:outline-none" x-data
                                                x-on:input="$event.target.value = $event.target.value.replace(/[^0-9]/g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Aset usaha &amp;
                                            perdagangan</label>
                                        <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                                            <span
                                                class="px-3 py-2 bg-gray-50 border-r border-gray-300 text-sm text-gray-500">Rp</span>
                                            <input type="text" wire:model.live="aset" placeholder="0"
                                                class="flex-1 px-3 py-2 text-sm focus:outline-none" x-data
                                                x-on:input="$event.target.value = $event.target.value.replace(/[^0-9]/g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Hutang jatuh tempo
                                            (opsional)</label>
                                        <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                                            <span
                                                class="px-3 py-2 bg-gray-50 border-r border-gray-300 text-sm text-gray-500">Rp</span>
                                            <input type="text" wire:model.live="hutang" placeholder="0"
                                                class="flex-1 px-3 py-2 text-sm focus:outline-none" x-data
                                                x-on:input="$event.target.value = $event.target.value.replace(/[^0-9]/g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')">
                                        </div>
                                    </div>

                                    {{-- Summary box --}}
                                    <div class="bg-orange-50 border border-primary rounded-xl p-3 mt-2">
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-600">Total Harta:</span>
                                                <span class="text-xs font-semibold text-dark">
                                                    Rp {{ number_format($totalHarta, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-600">Nisab Saat Ini:</span>
                                                <span class="text-xs font-semibold text-dark">
                                                    Rp {{ number_format($nisab, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs text-gray-600">Status:</span>
                                                @if ($zakatStatus === 'wajib')
                                                    <span
                                                        class="px-2 py-1 bg-primary text-white text-xs rounded-full font-semibold">
                                                        Wajib zakat
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 bg-gray-200 text-gray-600 text-xs rounded-full">
                                                        Belum mencapai nisab
                                                    </span>
                                                @endif
                                            </div>
                                            <hr class="border-primary/20">
                                            <div class="text-center">
                                                <p class="text-sm text-gray-600">Zakat yang harus dibayar:</p>
                                                <p class="text-lg font-bold text-dark">
                                                    Rp {{ number_format($calculatedZakat, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            @endif
        </div>

        <div class="px-4 mt-4">
            <section class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <h3 class="text-base font-bold text-dark mb-4">Informasi Muzakki</h3>
                <div class="space-y-4">
                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Lengkap</label>
                        <div class="relative">
                            <i
                                class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" wire:model="name" placeholder="Masukkan nama lengkap"
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>
                        @error('name')
                            <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- WhatsApp --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Nomor WhatsApp</label>
                        <div class="relative">
                            <i
                                class="fa-brands fa-whatsapp absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" wire:model="phone" placeholder="Contoh: 08123456789"
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>
                        @error('phone')
                            <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Email (Opsional)</label>
                        <div class="relative">
                            <i
                                class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="email" wire:model="email" placeholder="Masukkan alamat email"
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>
                        @error('email')
                            <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>
        </div>

        {{-- Collapsible Info --}}
        <section class="px-4 mt-4 space-y-2" x-data="{ niat: false, nisab: false }">
            <div class="bg-white rounded-xl">
                <button @click="niat = !niat" class="w-full flex items-center justify-between p-4">
                    <span class="text-sm font-semibold text-dark">Lihat niat zakat</span>
                    <i class="fa-solid fa-chevron-down text-gray-400 text-sm transition-transform duration-200"
                        :class="{ 'rotate-180': niat }"></i>
                </button>
                <div x-show="niat" x-transition x-cloak class="px-4 pb-4 space-y-4">
                    {{-- Niat Fitrah --}}
                    <div>
                        <p class="text-[10px] font-bold text-primary uppercase mb-1">Niat Zakat Fitrah</p>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            <em>"Nawaytu an ukhrija zakaata al-fitri 'anni wa an jami'i ma yalzimuniy nafaqatuhum
                                syar'an fardhan lillahi ta'ala"</em>
                        </p>
                        <p class="text-[10px] text-gray-500 mt-1">
                            Artinya: "Aku niat mengeluarkan zakat fitrah untuk diriku dan seluruh orang yang nafkahnya
                            menjadi tanggunganku, fardu karena Allah Ta’âlâ.”
                        </p>
                    </div>

                    <div class="border-t border-gray-100 pt-3">
                        <p class="text-[10px] font-bold text-primary uppercase mb-1">Niat Zakat Mal</p>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            <em>"Nawaitu an ukhrija zakata maali fardhan lillahi ta'ala"</em>
                        </p>
                        <p class="text-[10px] text-gray-500 mt-1">
                            Artinya: "Saya niat mengeluarkan zakat harta saya, fardhu karena Allah Ta'ala"
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl">
                <button @click="nisab = !nisab" class="w-full flex items-center justify-between p-4">
                    <span class="text-sm font-semibold text-dark">Tentang nisab &amp; ketentuan</span>
                    <i class="fa-solid fa-chevron-down text-gray-400 text-sm transition-transform duration-200"
                        :class="{ 'rotate-180': nisab }"></i>
                </button>
                <div x-show="nisab" x-transition x-cloak class="px-4 pb-4">
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Nisab adalah batas minimum harta yang wajib dikeluarkan zakatnya.
                        Nisab zakat mal setara dengan <span class="font-bold text-primary">85 gram emas</span>
                        (saat ini = <span class="font-bold text-primary">Rp
                            {{ number_format($nisab, 0, ',', '.') }}</span>).
                        Zakat wajib dikeluarkan sebesar <span class="font-bold text-primary">2,5%</span> dari total
                        harta yang mencapai nisab
                        dan telah dimiliki selama satu tahun hijriah.
                    </p>
                </div>
            </div>
        </section>
    </main>

    {{-- Fixed Bottom CTA --}}
    <div class="fixed max-w-[480px] bottom-0 left-0 right-0 mx-auto bg-white border-t border-gray-200 p-4 z-50">
        @if (session()->has('error'))
            <p class="text-xs text-red-500 text-center mb-2">{{ session('error') }}</p>
        @endif
        <button wire:click="submitZakat" wire:loading.attr="disabled"
            wire:loading.class="opacity-70 cursor-not-allowed" @disabled($calculatedZakat <= 0)
            class="w-full bg-primary text-white py-4 rounded-xl text-base font-bold disabled:opacity-50 disabled:cursor-not-allowed transition-opacity">
            <span wire:loading.remove>Bayar Zakat</span>
            <span wire:loading class="flex items-center justify-center gap-2">
                <i class="fa-solid fa-spinner fa-spin"></i> Memproses...
            </span>
        </button>
    </div>
</div>

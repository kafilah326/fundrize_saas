<div>
    <x-page-header title="Jadi Fundriser" :showBack="true" backUrl="{{ route('profile.index') }}" />

    <main id="main-content" class="pb-6">
        <section class="bg-white px-4 py-6">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-3">
                    <i class="fa-solid fa-hand-holding-heart text-2xl text-primary"></i>
                </div>
                <h2 class="text-lg font-bold text-dark">Pendaftaran Fundriser</h2>
                <p class="text-sm text-gray-500 mt-1">Lengkapi data diri Anda untuk bergabung menjadi fundriser dan bantu tebarkan kebaikan bersama kami.</p>
            </div>
        </section>

        <section class="bg-white px-4 py-6 mt-2">
            <form wire:submit.prevent="register" class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-semibold text-dark mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2">
                            <i class="fa-solid fa-user text-gray-400"></i>
                        </div>
                        <input type="text" id="name" wire:model="name" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                    @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="whatsapp" class="block text-sm font-semibold text-dark mb-2">Nomor WhatsApp</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2">
                            <i class="fa-solid fa-phone text-gray-400"></i>
                        </div>
                        <input type="tel" id="whatsapp" wire:model="whatsapp" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" placeholder="08...">
                    </div>
                    @error('whatsapp') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-dark mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2">
                            <i class="fa-solid fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" id="email" wire:model="email" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                    @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="domicile" class="block text-sm font-semibold text-dark mb-2">Domisili (Kota/Kabupaten)</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2">
                            <i class="fa-solid fa-map-pin text-gray-400"></i>
                        </div>
                        <input type="text" id="domicile" wire:model="domicile" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Contoh: Jakarta Selatan">
                    </div>
                    @error('domicile') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-semibold text-dark mb-2">Alamat Lengkap</label>
                    <div class="relative">
                        <div class="absolute left-3 top-4">
                            <i class="fa-solid fa-house text-gray-400"></i>
                        </div>
                        <textarea id="address" wire:model="address" rows="3" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"></textarea>
                    </div>
                    @error('address') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary text-white font-semibold py-3.5 rounded-xl shadow-lg shadow-orange-200 active:scale-[0.98] transition-transform hover:bg-orange-600 flex justify-center items-center">
                        <span wire:loading.remove wire:target="register">Kirim Pendaftaran</span>
                        <span wire:loading wire:target="register"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Memproses...</span>
                    </button>
                </div>
            </form>
        </section>

        <section class="px-4 py-4 mt-2">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-circle-info text-blue-500 text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-blue-800 leading-relaxed">Pastikan data yang Anda masukkan sudah benar. Anda akan dihubungi oleh admin melalui WhatsApp setelah pendaftaran disetujui.</p>
                </div>
            </div>
        </section>
    </main>
</div>


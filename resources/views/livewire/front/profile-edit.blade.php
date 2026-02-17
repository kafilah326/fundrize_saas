<div>
    <x-page-header title="Edit Profil" :showBack="true" backUrl="{{ route('profile.index') }}" />

    <main id="main-content" class="pb-6">
        <section id="avatar-section" class="bg-white px-4 py-6">
            <div class="flex flex-col items-center gap-3">
                <div class="relative w-24 h-24">
                    <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-200">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" alt="New Avatar Preview" class="w-full h-full object-cover">
                        @else
                            <img src="{{ $avatar ?? 'https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-5.jpg' }}" alt="User Avatar" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <label for="photo-upload" class="absolute bottom-0 right-0 w-8 h-8 bg-primary rounded-full flex items-center justify-center shadow-lg cursor-pointer">
                        <i class="fa-solid fa-camera text-white text-xs"></i>
                    </label>
                    <input type="file" wire:model="photo" id="photo-upload" class="hidden" accept="image/*">
                </div>
                <p class="text-xs text-gray-500">Ketuk untuk mengubah foto</p>
                @error('photo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </section>

        <section id="form-section" class="bg-white px-4 py-6 mt-2">
            <form wire:submit="save" class="space-y-5">
                <div id="name-field">
                    <label class="block text-sm font-semibold text-dark mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2">
                            <i class="fa-solid fa-user text-gray-400"></i>
                        </div>
                        <input type="text" wire:model="name" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                    @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div id="phone-field">
                    <label class="block text-sm font-semibold text-dark mb-2">Nomor WhatsApp</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2">
                            <i class="fa-solid fa-phone text-gray-400"></i>
                        </div>
                        <input type="tel" wire:model="phone" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Gunakan nomor aktif untuk notifikasi</p>
                    @error('phone') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div id="email-field">
                    <label class="block text-sm font-semibold text-dark mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2">
                            <i class="fa-solid fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" wire:model="email" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                    @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div id="action-section" class="pt-4">
                    <button type="submit" class="w-full bg-primary text-white font-semibold py-3.5 rounded-xl shadow-lg shadow-orange-200 active:scale-[0.98] transition-transform hover:bg-orange-600">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>

        <section id="info-section" class="px-4 py-4">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-circle-info text-blue-500 text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-blue-800 leading-relaxed">Pastikan data yang Anda masukkan sudah benar. Nomor WhatsApp akan digunakan untuk notifikasi donasi.</p>
                </div>
            </div>
        </section>
    </main>
</div>

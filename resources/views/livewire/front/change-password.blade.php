<div x-data="{ showOld: false, showNew: false, showConfirm: false }">
    <x-page-header title="Ubah Password" :showBack="true" backUrl="{{ route('profile.index') }}" />

    <main id="main-content" class="pb-20">
        <section id="password-form" class="bg-white px-4 py-6 mt-2">
            <form wire:submit="save" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-dark mb-2">Password Lama</label>
                    <div class="relative">
                        <input :type="showOld ? 'text' : 'password'" wire:model="current_password"
                               class="w-full px-4 py-3 pr-12 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Masukkan password lama">
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" @click="showOld = !showOld">
                            <i class="fa-solid" :class="showOld ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('current_password') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-dark mb-2">Password Baru</label>
                    <div class="relative">
                        <input :type="showNew ? 'text' : 'password'" wire:model="password"
                               class="w-full px-4 py-3 pr-12 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Masukkan password baru">
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" @click="showNew = !showNew">
                            <i class="fa-solid" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-2">Password minimal 8 karakter</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-dark mb-2">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" wire:model="password_confirmation"
                               class="w-full px-4 py-3 pr-12 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Ulangi password baru">
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" @click="showConfirm = !showConfirm">
                            <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary text-white py-4 rounded-lg font-semibold text-base hover:bg-orange-600 transition-colors shadow-lg active:scale-[0.98] transition-transform">
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </section>

        <section id="password-tips" class="bg-white px-4 py-4 mt-2">
            <h3 class="text-sm font-bold text-dark mb-3">Tips Password Aman</h3>
            <div class="space-y-2">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-check-circle text-green-500 text-sm mt-0.5"></i>
                    <p class="text-sm text-gray-600">Minimal 8 karakter</p>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-check-circle text-green-500 text-sm mt-0.5"></i>
                    <p class="text-sm text-gray-600">Kombinasi huruf besar dan kecil</p>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-check-circle text-green-500 text-sm mt-0.5"></i>
                    <p class="text-sm text-gray-600">Mengandung angka</p>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-check-circle text-green-500 text-sm mt-0.5"></i>
                    <p class="text-sm text-gray-600">Hindari informasi pribadi</p>
                </div>
            </div>
        </section>
    </main>
</div>

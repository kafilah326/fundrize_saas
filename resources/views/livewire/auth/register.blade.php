<div>
    <x-page-header title="Daftar Akun" :showBack="true" backUrl="{{ route('login') }}" />

    <main id="main-content" class="px-4 py-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-dark mb-2">Buat Akun Baru</h1>
            <p class="text-sm text-gray-500">Mulai kebaikanmu hari ini</p>
        </div>

        <form wire:submit="register" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input wire:model="name" type="text" id="name" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors text-sm" placeholder="Masukkan nama lengkap">
                </div>
                @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input wire:model="email" type="email" id="email" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors text-sm" placeholder="Masukkan email">
                </div>
                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-phone"></i>
                    </span>
                    <input wire:model="phone" type="tel" id="phone" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors text-sm" placeholder="Contoh: 08123456789">
                </div>
                @error('phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <div class="relative" x-data="{ show: false }">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input wire:model="password" :type="show ? 'text' : 'password'" id="password" class="w-full pl-10 pr-10 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors text-sm" placeholder="Minimal 8 karakter">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                <div class="relative" x-data="{ show: false }">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input wire:model="password_confirmation" :type="show ? 'text' : 'password'" id="password_confirmation" class="w-full pl-10 pr-10 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors text-sm" placeholder="Ulangi password">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            <button type="submit" wire:loading.attr="disabled" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-semibold shadow-lg active:scale-95 transition-transform hover:bg-primary/90 flex justify-center items-center gap-2">
                <span wire:loading.remove>Daftar Sekarang</span>
                <span wire:loading><i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...</span>
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">Sudah punya akun? <a href="{{ route('login') }}" wire:navigate class="font-semibold text-primary hover:underline">Masuk</a></p>
        </div>
    </main>
</div>

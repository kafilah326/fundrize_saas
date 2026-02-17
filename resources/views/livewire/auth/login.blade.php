<div>
    <x-page-header title="Login" :showBack="true" backUrl="{{ route('home') }}" />

    <main id="main-content" class="px-4 py-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-dark mb-2">Selamat Datang</h1>
            <p class="text-sm text-gray-500">Silakan login untuk melanjutkan donasi</p>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded-lg text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit="login" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input wire:model="email" type="email" id="email" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors text-sm" placeholder="Masukkan email Anda">
                </div>
                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <div class="relative" x-data="{ show: false }">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input wire:model="password" :type="show ? 'text' : 'password'" id="password" class="w-full pl-10 pr-10 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors text-sm" placeholder="Masukkan password Anda">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input wire:model="remember" type="checkbox" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <span class="text-xs text-gray-600">Ingat Saya</span>
                </label>
                <a href="{{ route('password.forgot') }}" wire:navigate class="text-xs font-semibold text-primary hover:underline">Lupa Password?</a>
            </div>

            <button type="submit" wire:loading.attr="disabled" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-semibold shadow-lg active:scale-95 transition-transform hover:bg-primary/90 flex justify-center items-center gap-2">
                <span wire:loading.remove>Masuk</span>
                <span wire:loading><i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...</span>
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">Belum punya akun? <a href="{{ route('register') }}" wire:navigate class="font-semibold text-primary hover:underline">Daftar Sekarang</a></p>
        </div>
    </main>
</div>

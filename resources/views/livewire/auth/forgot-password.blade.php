<div>
    <x-page-header title="Lupa Password" :showBack="true" backUrl="{{ route('login') }}" />

    <main id="main-content" class="px-4 py-8">
        @if ($step === 1)
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-dark mb-2">Reset Password</h1>
                <p class="text-sm text-gray-500">Masukkan email yang terdaftar untuk menerima kode OTP</p>
            </div>

            <form wire:submit="sendOtp" class="space-y-5">
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

                <button type="submit" wire:loading.attr="disabled" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-semibold shadow-lg active:scale-95 transition-transform hover:bg-primary/90 flex justify-center items-center gap-2">
                    <span wire:loading.remove>Kirim Kode OTP</span>
                    <span wire:loading><i class="fa-solid fa-circle-notch fa-spin"></i> Mengirim...</span>
                </button>
            </form>

        @elseif ($step === 2)
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-dark mb-2">Verifikasi OTP</h1>
                <p class="text-sm text-gray-500">Masukkan 6 digit kode yang dikirim ke <span class="font-semibold text-dark">{{ $email }}</span></p>
            </div>

            <div class="space-y-6">
                <!-- Gunakan input OTP yang lebih sederhana agar binding Livewire tidak konflik dengan JS -->
                <div class="flex justify-between gap-2 max-w-[300px] mx-auto">
                    <input type="text" wire:model="otp1" maxlength="1" class="w-10 h-12 text-center text-xl font-bold border border-gray-300 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <input type="text" wire:model="otp2" maxlength="1" class="w-10 h-12 text-center text-xl font-bold border border-gray-300 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <input type="text" wire:model="otp3" maxlength="1" class="w-10 h-12 text-center text-xl font-bold border border-gray-300 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <input type="text" wire:model="otp4" maxlength="1" class="w-10 h-12 text-center text-xl font-bold border border-gray-300 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <input type="text" wire:model="otp5" maxlength="1" class="w-10 h-12 text-center text-xl font-bold border border-gray-300 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <input type="text" wire:model="otp6" maxlength="1" class="w-10 h-12 text-center text-xl font-bold border border-gray-300 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                </div>
                
                @error('otp') <span class="text-xs text-red-500 mt-1 block text-center">{{ $message }}</span> @enderror

                <div class="text-center text-xs text-gray-500">
                    Tidak menerima kode? <button type="button" wire:click="sendOtp" class="text-primary font-semibold hover:underline">Kirim Ulang</button>
                </div>

                <button wire:click="verifyOtp" wire:loading.attr="disabled" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-semibold shadow-lg active:scale-95 transition-transform hover:bg-primary/90 flex justify-center items-center gap-2">
                    <span wire:loading.remove>Verifikasi</span>
                    <span wire:loading><i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...</span>
                </button>
            </div>

        @elseif ($step === 3)
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-dark mb-2">Password Baru</h1>
                <p class="text-sm text-gray-500">Buat password baru untuk akun Anda</p>
            </div>

            <form wire:submit="resetPassword" class="space-y-5">
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
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
                        <input wire:model="password_confirmation" :type="show ? 'text' : 'password'" id="password_confirmation" class="w-full pl-10 pr-10 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors text-sm" placeholder="Ulangi password baru">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" wire:loading.attr="disabled" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-semibold shadow-lg active:scale-95 transition-transform hover:bg-primary/90 flex justify-center items-center gap-2">
                    <span wire:loading.remove>Simpan Password</span>
                    <span wire:loading><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                </button>
            </form>
        @endif
    </main>
</div>

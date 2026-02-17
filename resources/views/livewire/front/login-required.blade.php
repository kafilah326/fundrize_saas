<div>
    <header id="header" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="px-4 py-3 flex items-center gap-3">
            <button onclick="history.back()" class="w-9 h-9 flex items-center justify-center bg-gray-50 rounded-full hover:bg-gray-100 transition-colors">
                <i class="fa-solid fa-arrow-left text-dark text-sm"></i>
            </button>
            <h1 class="text-base font-bold text-dark flex-1">Akses Terbatas</h1>
        </div>
    </header>

    <main id="main-content" class="min-h-screen flex flex-col items-center justify-center px-4 pb-20 -mt-16">
        <section id="login-required" class="text-center max-w-sm mx-auto">
            <div class="mb-8">
                <img class="w-64 h-64 mx-auto object-contain" src="https://storage.googleapis.com/uxpilot-auth.appspot.com/ba1fdee918-7215af54ebe09de81497.png" alt="Login required illustration">
            </div>

            <div class="mb-8">
                <h2 class="text-xl font-bold text-dark mb-3">Silakan Login Terlebih Dahulu</h2>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Untuk mengakses halaman ini dan fitur lainnya, Anda perlu masuk ke akun atau mendaftar akun baru.
                </p>
            </div>

            <div class="space-y-3 mb-6">
                <a href="{{ route('login') }}" wire:navigate class="w-full py-4 bg-primary text-white rounded-xl text-sm font-semibold flex items-center justify-center gap-2 shadow-lg active:scale-95 transition-transform hover:bg-primary/90">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Masuk ke Akun
                </a>
                
                <a href="{{ route('register') }}" wire:navigate class="w-full py-4 bg-white border-2 border-primary text-primary rounded-xl text-sm font-semibold flex items-center justify-center gap-2 active:scale-95 transition-transform hover:bg-orange-50">
                    <i class="fa-solid fa-user-plus"></i>
                    Daftar Akun Baru
                </a>
            </div>

            <div class="text-center">
                <p class="text-xs text-gray-500 mb-3">Atau lanjutkan sebagai tamu</p>
                <a href="{{ route('home') }}" wire:navigate class="text-primary text-sm font-medium underline">
                    Kembali ke Beranda
                </a>
            </div>
        </section>
    </main>

    <x-bottom-nav :active="$tab" />
</div>

<div>
    <header id="header" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="px-4 py-4 flex items-center">
            <h1 class="text-lg font-bold text-dark">Profil</h1>
        </div>
    </header>

    <main id="main-content" class="pb-20">
        <section id="profile-header" class="bg-white px-4 py-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full overflow-hidden bg-primary text-white flex items-center justify-center text-xl font-bold border-2 border-white shadow-lg">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->initials }}" class="w-full h-full object-cover">
                    @else
                        {{ $user->initials }}
                    @endif
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-dark">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-600">{{ $user->phone ?? 'Belum ada nomor telepon' }}</p>
                </div>
            </div>
        </section>

        <section id="qurban-menu" class="bg-white px-4 py-4 mt-2">
            <a href="{{ route('qurban.history') }}" wire:navigate class="w-full flex items-center gap-4 p-3 rounded-xl bg-orange-50 border border-orange-100 hover:bg-orange-100 transition-colors">
                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-cow text-white text-lg"></i>
                </div>
                <div class="flex-1 text-left">
                    <h3 class="text-sm font-semibold text-dark">Qurban & Tabungan Qurban</h3>
                    <p class="text-xs text-gray-600">Kelola program qurban Anda</p>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 text-sm"></i>
            </a>
        </section>

        <section id="account-menu" class="bg-white px-4 py-4 mt-2">
            <h3 class="text-sm font-bold text-dark mb-3">Akun</h3>
            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}" wire:navigate class="w-full flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-user-pen text-gray-600"></i>
                    </div>
                    <span class="flex-1 text-left text-sm font-medium text-dark">Edit Profil</span>
                    <i class="fa-solid fa-chevron-right text-gray-400 text-sm"></i>
                </a>
                <a href="{{ route('profile.change-password') }}" wire:navigate class="w-full flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved text-gray-600"></i>
                    </div>
                    <span class="flex-1 text-left text-sm font-medium text-dark">Ubah Password</span>
                    <i class="fa-solid fa-chevron-right text-gray-400 text-sm"></i>
                </a>
            </div>
        </section>

        <section id="foundation-info" class="bg-white px-4 py-4 mt-2">
            <h3 class="text-sm font-bold text-dark mb-3">Informasi Yayasan</h3>
            <div class="space-y-1">
                <a href="{{ route('foundation.profile') }}" wire:navigate class="w-full flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-building text-gray-600"></i>
                    </div>
                    <span class="flex-1 text-left text-sm font-medium text-dark">Profil Yayasan</span>
                    <i class="fa-solid fa-chevron-right text-gray-400 text-sm"></i>
                </a>
                <a href="{{ route('foundation.legality') }}" wire:navigate class="w-full flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-certificate text-gray-600"></i>
                    </div>
                    <span class="flex-1 text-left text-sm font-medium text-dark">Legalitas</span>
                    <i class="fa-solid fa-chevron-right text-gray-400 text-sm"></i>
                </a>
                <a href="{{ route('report.index') }}" wire:navigate class="w-full flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-file-chart-column text-gray-600"></i>
                    </div>
                    <span class="flex-1 text-left text-sm font-medium text-dark">Laporan Dana</span>
                    <i class="fa-solid fa-chevron-right text-gray-400 text-sm"></i>
                </a>
            </div>
        </section>

        <section id="help-menu" class="bg-white px-4 py-4 mt-2">
            <h3 class="text-sm font-bold text-dark mb-3">Bantuan</h3>
            <div class="space-y-1">
                @php
                    $waNumber = $foundation ? preg_replace('/[^0-9]/', '', $foundation->phone) : '6281234567890';
                    // Ensure it starts with 62
                    if(substr($waNumber, 0, 1) == '0') {
                        $waNumber = '62' . substr($waNumber, 1);
                    }
                @endphp
                <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Admin%2C%20saya%20butuh%20bantuan" target="_blank" class="w-full flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fa-brands fa-whatsapp text-green-600 text-lg"></i>
                    </div>
                    <span class="flex-1 text-left text-sm font-medium text-dark">Hubungi CS</span>
                    <i class="fa-solid fa-chevron-right text-gray-400 text-sm"></i>
                </a>
                <button onclick="alert('Fitur FAQ akan segera tersedia')" class="w-full flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-circle-question text-gray-600"></i>
                    </div>
                    <span class="flex-1 text-left text-sm font-medium text-dark">FAQ</span>
                    <i class="fa-solid fa-chevron-right text-gray-400 text-sm"></i>
                </button>
            </div>
        </section>

        <section id="system-menu" class="bg-white px-4 py-4 mt-2 mb-4">
            <div class="space-y-1">
                <button wire:click="logout" class="w-full flex items-center gap-4 p-3 hover:bg-red-50 rounded-lg text-red-600 transition-colors">
                    <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-arrow-right-from-bracket text-red-600"></i>
                    </div>
                    <span class="flex-1 text-left text-sm font-medium">Logout</span>
                </button>
            </div>
        </section>
    </main>

    <x-bottom-nav active="profile" />
</div>

<div>
    <x-page-header title="Dashboard Fundriser" :showBack="true" backUrl="{{ route('profile.index') }}" />

    <main id="main-content" class="px-4 pb-20">
        <!-- Greeting Section -->
        <section id="greeting-section" class="py-6">
            <div class="bg-primary rounded-2xl p-5 shadow-lg">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <p class="text-orange-100 text-sm font-medium mb-1">Assalamu'alaikum,</p>
                        <h2 class="text-white text-xl font-bold mb-2">{{ explode(' ', trim($fundraiser->name))[0] }}! 🤲</h2>
                        <p class="text-orange-50 text-xs leading-relaxed">Teruslah menyebarkan kebaikan. Setiap langkahmu adalah amal jariyah yang terus mengalir.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section id="stats-section" class="mb-6">
            <h3 class="text-dark font-semibold text-base mb-3 px-1">Dampak Kebaikanmu</h3>
            <div class="grid grid-cols-1 gap-3">
                <div id="stat-card-1" class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 rounded-xl bg-primary flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-hand-holding-heart text-white text-lg"></i>
                        </div>
                        <span class="text-xs font-medium text-primary bg-orange-50 px-2.5 py-1 rounded-full">+0%</span>
                    </div>
                    <p class="text-gray-500 text-xs mb-1 font-medium">Total Kebaikan Tersalurkan</p>
                    <h4 class="text-dark text-2xl font-bold mb-0.5">Rp {{ number_format($totalDonationAmount, 0, ',', '.') }}</h4>
                    <p class="text-gray-400 text-xs">Dari {{ $totalDonationCount }} donasi</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div id="stat-card-2" class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center shadow-md mb-3">
                            <i class="fa-solid fa-users text-white"></i>
                        </div>
                        <p class="text-gray-500 text-xs mb-1 font-medium">Jangkauan Kebaikan</p>
                        <h4 class="text-dark text-xl font-bold">{{ number_format($totalVisits, 0, ',', '.') }}</h4>
                        <p class="text-gray-400 text-xs">Klik link unik</p>
                    </div>

                    <div id="stat-card-3" class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center shadow-md mb-3">
                            <i class="fa-solid fa-coins text-white"></i>
                        </div>
                        <p class="text-gray-500 text-xs mb-1 font-medium">Ujroh Perjuangan</p>
                        <h4 class="text-dark text-xl font-bold">Rp {{ number_format($availableBalance, 0, ',', '.') }}</h4>
                        <p class="text-gray-400 text-xs">Saldo aktif</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Referral Section -->
        <section id="referral-section" class="mb-6" x-data="{ 
            referralLink: '{{ url('/') }}?ref={{ $fundraiser->referral_code }}',
            copied: false,
            copyLink() {
                navigator.clipboard.writeText(this.referralLink).then(() => {
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                });
            },
            shareWhatsApp() {
                window.open('https://wa.me/?text=Mari berdonasi bersama di: ' + encodeURIComponent(this.referralLink), '_blank');
            },
            shareTelegram() {
                window.open('https://t.me/share/url?url=' + encodeURIComponent(this.referralLink) + '&text=Mari berdonasi bersama', '_blank');
            },
            shareOther() {
                if (navigator.share) {
                    navigator.share({
                        title: 'Ayo Berdonasi',
                        text: 'Mari berdonasi bersama melalui link ini',
                        url: this.referralLink,
                    });
                } else {
                    alert('Browser Anda tidak mendukung fitur berbagi ini.');
                }
            }
        }">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-orange-100">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center">
                        <i class="fa-solid fa-link text-white text-sm"></i>
                    </div>
                    <h3 class="text-dark font-bold text-base">Link Kebaikanmu</h3>
                </div>
                <p class="text-gray-600 text-xs mb-4 leading-relaxed">Bagikan link ini untuk mengajak lebih banyak orang berbuat kebaikan</p>
                
                <div class="bg-white rounded-xl p-3 mb-3 border border-gray-200">
                    <p class="text-gray-400 text-xs mb-1 font-medium">Link Utama</p>
                    <p class="text-gray-700 text-sm font-mono truncate" x-text="referralLink"></p>
                </div>
                
                <button @click="copyLink" 
                    class="w-full text-white font-semibold py-3.5 rounded-xl shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-2"
                    :class="copied ? 'bg-emerald-600 shadow-emerald-600/30' : 'bg-primary shadow-primary/30'">
                    <i class="fa-solid" :class="copied ? 'fa-check' : 'fa-copy'"></i>
                    <span x-text="copied ? 'Link Tersalin!' : 'Salin Link Utama'"></span>
                </button>

                {{-- <div class="grid grid-cols-3 gap-2 mt-3">
                    <button @click="shareWhatsApp" class="bg-white border border-gray-200 rounded-lg py-2.5 flex flex-col items-center justify-center gap-1 active:bg-gray-50 transition">
                        <i class="fa-brands fa-whatsapp text-green-600 text-lg"></i>
                        <span class="text-xs text-gray-600 font-medium">WhatsApp</span>
                    </button>
                    <button @click="shareTelegram" class="bg-white border border-gray-200 rounded-lg py-2.5 flex flex-col items-center justify-center gap-1 active:bg-gray-50 transition">
                        <i class="fa-brands fa-telegram text-blue-500 text-lg"></i>
                        <span class="text-xs text-gray-600 font-medium">Telegram</span>
                    </button>
                    <button @click="shareOther" class="bg-white border border-gray-200 rounded-lg py-2.5 flex flex-col items-center justify-center gap-1 active:bg-gray-50 transition">
                        <i class="fa-solid fa-share-nodes text-gray-600 text-lg"></i>
                        <span class="text-xs text-gray-600 font-medium">Lainnya</span>
                    </button>
                </div> --}}
            </div>
        </section>

        <!-- Quick Nav Section -->
        <section id="quick-nav-section" class="mb-6">
            <h3 class="text-dark font-semibold text-base mb-3 px-1">Menu Cepat</h3>
            <div class="space-y-2">
                {{-- <a href="{{ route('fundraiser.programs') }}" wire:navigate class="w-full bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center justify-between active:bg-gray-50 transition block">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-bullhorn text-white"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="text-dark font-semibold text-sm">Program Ber-Ujroh</h4>
                            <p class="text-gray-500 text-xs">Bagikan program dan dapatkan ujroh</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-400"></i>
                </a> --}}

                <a href="{{ route('fundraiser.history') }}" wire:navigate class="w-full bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center justify-between active:bg-gray-50 transition block">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-primary flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-clock-rotate-left text-white"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="text-dark font-semibold text-sm">Riwayat Ujroh</h4>
                            <p class="text-gray-500 text-xs">Lihat detail transaksi</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-400"></i>
                </a>

                <a href="{{ route('fundraiser.banks') }}" wire:navigate class="w-full bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center justify-between active:bg-gray-50 transition block">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-primary flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-building-columns text-white"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="text-dark font-semibold text-sm">Pengaturan Bank Pencairan</h4>
                            <p class="text-gray-500 text-xs">Kelola rekening bank pencairan</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-400"></i>
                </a>
            </div>
        </section>

        <!-- Tips Section -->
        <section id="tips-section" class="mb-6">
            <div class="bg-white rounded-2xl p-5 border border-orange-100">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fa-solid fa-lightbulb text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-dark font-bold text-sm mb-1">Tips Pejuang Kebaikan</h4>
                        <p class="text-gray-600 text-xs leading-relaxed">Bagikan link di grup WhatsApp keluarga dan teman untuk memperluas jangkauan kebaikan. Semakin banyak yang tersentuh, semakin besar pahalanya! ✨</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Bottom Navigation -->
    <x-bottom-nav active="profile" />
</div>


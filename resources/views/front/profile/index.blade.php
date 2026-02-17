<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#FF6B35',
                        secondary: '#FFA07A',
                        dark: '#1A1A1A',
                        light: '#F8F9FA'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <script>
        window.FontAwesomeConfig = {
            autoReplaceSvg: 'nest'
        };
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-light">
    <header id="header" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="px-4 py-3 flex items-center gap-3">
            <button class="w-9 h-9 flex items-center justify-center">
                <i class="fa-solid fa-arrow-left text-dark text-lg"></i>
            </button>
            <h1 class="text-base font-bold text-dark flex-1">Profil</h1>
            <button class="w-9 h-9 flex items-center justify-center">
                <i class="fa-solid fa-cog text-dark text-lg"></i>
            </button>
        </div>
    </header>

    <main id="main-content" class="pb-20">
        <section id="user-profile" class="bg-white px-4 py-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-100">
                    <img class="w-full h-full object-cover"
                        src="https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-5.jpg"
                        alt="User Avatar" />
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-dark">Siti Nurhaliza</h2>
                    <p class="text-sm text-gray-600">siti.nurhaliza@email.com</p>
                    <p class="text-xs text-gray-500">+62 812-3456-7890</p>
                </div>
            </div>
            <button class="w-full py-3 border border-primary text-primary rounded-xl text-sm font-semibold">
                Edit Profil
            </button>
        </section>

        <section id="donation-stats" class="px-4 py-4">
            <div class="bg-gradient-to-r from-primary to-secondary rounded-xl p-4 text-white">
                <h3 class="text-sm font-semibold mb-3">Statistik Donasi Anda</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs opacity-90">Total Donasi</p>
                        <p class="text-lg font-bold">Rp 2.5M</p>
                    </div>
                    <div>
                        <p class="text-xs opacity-90">Program Dibantu</p>
                        <p class="text-lg font-bold">12</p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-white border-opacity-20">
                    <div class="flex justify-between text-xs">
                        <span class="opacity-90">Donatur sejak</span>
                        <span class="font-semibold">Maret 2024</span>
                    </div>
                </div>
            </div>
        </section>

        <section id="menu-section" class="px-4 py-2">
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="menu-item flex items-center justify-between p-4 border-b border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-hand-holding-heart text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-dark">Riwayat Donasi</p>
                            <p class="text-xs text-gray-500">Lihat semua donasi Anda</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-400"></i>
                </div>

                <div class="menu-item flex items-center justify-between p-4 border-b border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-bookmark text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-dark">Program Favorit</p>
                            <p class="text-xs text-gray-500">Program yang Anda simpan</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-400"></i>
                </div>

                <div class="menu-item flex items-center justify-between p-4 border-b border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-bell text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-dark">Notifikasi</p>
                            <p class="text-xs text-gray-500">Pengaturan pemberitahuan</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-400"></i>
                </div>

                <div class="menu-item flex items-center justify-between p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-credit-card text-orange-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-dark">Metode Pembayaran</p>
                            <p class="text-xs text-gray-500">Kelola pembayaran</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-400"></i>
                </div>
            </div>
        </section>

        <section id="about-foundation" class="px-4 py-4">
            <div class="bg-white rounded-xl border border-gray-100 p-4">
                <h3 class="text-sm font-bold text-dark mb-3">Tentang Yayasan Al-Hikmah</h3>
                <p class="text-xs text-gray-600 leading-relaxed mb-4">
                    Yayasan Al-Hikmah adalah lembaga nirlaba yang fokus pada pemberdayaan masyarakat melalui program
                    pendidikan, kesehatan, dan sosial. Berdiri sejak 2015 dengan visi menciptakan masyarakat yang
                    mandiri dan sejahtera.
                </p>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center gap-2 text-xs">
                        <i class="fa-solid fa-map-marker-alt text-gray-400 w-4"></i>
                        <span class="text-gray-600">Jl. Raya Bogor No. 123, Jakarta Timur</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs">
                        <i class="fa-solid fa-phone text-gray-400 w-4"></i>
                        <span class="text-gray-600">(021) 1234-5678</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs">
                        <i class="fa-solid fa-envelope text-gray-400 w-4"></i>
                        <span class="text-gray-600">info@alhikmah.org</span>
                    </div>
                </div>
                <button class="w-full py-2 bg-gray-50 text-gray-700 rounded-lg text-xs font-medium">
                    Lihat Detail Yayasan
                </button>
            </div>
        </section>

        <section id="support-section" class="px-4 py-2">
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="menu-item flex items-center justify-between p-4 border-b border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-question-circle text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-dark">Bantuan & FAQ</p>
                            <p class="text-xs text-gray-500">Pertanyaan umum</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-400"></i>
                </div>

                <div class="menu-item flex items-center justify-between p-4 border-b border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-headset text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-dark">Hubungi Kami</p>
                            <p class="text-xs text-gray-500">Customer service</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-400"></i>
                </div>

                <div class="menu-item flex items-center justify-between p-4 border-b border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-shield-alt text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-dark">Kebijakan Privasi</p>
                            <p class="text-xs text-gray-500">Data & keamanan</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-400"></i>
                </div>

                <div class="menu-item flex items-center justify-between p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-file-alt text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-dark">Syarat & Ketentuan</p>
                            <p class="text-xs text-gray-500">Aturan penggunaan</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-400"></i>
                </div>
            </div>
        </section>

        <section id="logout-section" class="px-4 py-4">
            <button
                class="w-full py-3 bg-red-50 text-red-600 rounded-xl text-sm font-semibold flex items-center justify-center gap-2">
                <i class="fa-solid fa-sign-out-alt"></i>
                Keluar
            </button>
        </section>

        <section id="app-info" class="px-4 py-2 text-center">
            <p class="text-xs text-gray-400">Versi 1.2.3</p>
            <p class="text-xs text-gray-400">© 2026 Yayasan Al-Hikmah</p>
        </section>
    </main>

    <nav id="bottom-nav" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-2 z-50">
        <div class="flex items-center justify-between max-w-md mx-auto">
            <button class="flex flex-col items-center gap-1 min-w-[60px]">
                <i class="fa-solid fa-house text-gray-400 text-lg"></i>
                <span class="text-xs font-medium text-gray-400">Home</span>
            </button>
            <button class="flex flex-col items-center gap-1 min-w-[60px]">
                <i class="fa-solid fa-list text-gray-400 text-lg"></i>
                <span class="text-xs font-medium text-gray-400">Program</span>
            </button>
            <button class="flex flex-col items-center gap-1 min-w-[60px]">
                <i class="fa-solid fa-hand-holding-heart text-gray-400 text-lg"></i>
                <span class="text-xs font-medium text-gray-400">Donasi Saya</span>
            </button>
            <button class="flex flex-col items-center gap-1 min-w-[60px]">
                <i class="fa-solid fa-chart-line text-gray-400 text-lg"></i>
                <span class="text-xs font-medium text-gray-400">Laporan</span>
            </button>
            <button class="flex flex-col items-center gap-1 min-w-[60px]">
                <i class="fa-solid fa-user text-primary text-lg"></i>
                <span class="text-xs font-medium text-primary">Profil</span>
            </button>
        </div>
    </nav>

    <script>
        window.addEventListener('load', function() {
            const menuItems = document.querySelectorAll('.menu-item');

            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.add('bg-gray-50');
                    setTimeout(() => {
                        this.classList.remove('bg-gray-50');
                    }, 150);
                });
            });
        });
    </script>
</body>

</html>

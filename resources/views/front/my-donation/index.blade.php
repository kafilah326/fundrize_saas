<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Saya</title>
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

        .detail-modal {
            transform: translateY(100%);
            transition: transform 0.3s ease-in-out;
        }

        .detail-modal.active {
            transform: translateY(0);
        }

        .overlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease-in-out;
        }

        .overlay.active {
            opacity: 1;
            pointer-events: all;
        }
    </style>
</head>

<body class="bg-light">
    <header id="header" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="px-4 py-3 flex items-center gap-3">
            <button class="w-9 h-9 flex items-center justify-center">
                <i class="fa-solid fa-arrow-left text-dark text-lg"></i>
            </button>
            <h1 class="text-base font-bold text-dark flex-1">Donasi Saya</h1>
            <button class="w-9 h-9 flex items-center justify-center">
                <i class="fa-regular fa-bell text-dark text-lg"></i>
            </button>
        </div>
    </header>

    <section id="filter-tabs" class="bg-white px-4 py-3 border-b border-gray-100 sticky top-[52px] z-40">
        <div class="flex gap-2 overflow-x-auto hide-scrollbar">
            <button
                class="tab-filter px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap bg-primary text-white"
                data-status="semua">
                Semua
            </button>
            <button
                class="tab-filter px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap bg-gray-100 text-gray-600"
                data-status="berhasil">
                Berhasil
            </button>
            <button
                class="tab-filter px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap bg-gray-100 text-gray-600"
                data-status="pending">
                Pending
            </button>
            <button
                class="tab-filter px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap bg-gray-100 text-gray-600"
                data-status="gagal">
                Gagal
            </button>
            <button
                class="tab-filter px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap bg-gray-100 text-gray-600"
                data-status="kadaluarsa">
                Kadaluarsa
            </button>
        </div>
    </section>

    <main id="main-content" class="pb-20">
        <section id="donation-list" class="px-4 py-4">
            <div class="space-y-3">
                <div class="donation-card bg-white rounded-xl border border-gray-100 p-4" data-id="1">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm text-dark mb-1">Bangun Sekolah untuk Anak Yatim</h4>
                            <p class="text-xs text-gray-500">12 Januari 2024, 14:30</p>
                        </div>
                        <span class="px-2 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded">Berhasil</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-base font-bold text-dark">Rp 500.000</p>
                        <button class="text-primary text-sm font-medium">
                            Lihat Detail <i class="fa-solid fa-chevron-right text-xs ml-1"></i>
                        </button>
                    </div>
                </div>

                <div class="donation-card bg-white rounded-xl border border-gray-100 p-4" data-id="2">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm text-dark mb-1">Klinik Gratis untuk Masyarakat Dhuafa</h4>
                            <p class="text-xs text-gray-500">10 Januari 2024, 09:15</p>
                        </div>
                        <span
                            class="px-2 py-1 bg-yellow-50 text-yellow-600 text-xs font-semibold rounded">Pending</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-base font-bold text-dark">Rp 250.000</p>
                        <button class="text-primary text-sm font-medium">
                            Lihat Detail <i class="fa-solid fa-chevron-right text-xs ml-1"></i>
                        </button>
                    </div>
                </div>

                <div class="donation-card bg-white rounded-xl border border-gray-100 p-4" data-id="3">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm text-dark mb-1">Sumur Air Bersih untuk Desa Terpencil</h4>
                            <p class="text-xs text-gray-500">08 Januari 2024, 16:45</p>
                        </div>
                        <span class="px-2 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded">Berhasil</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-base font-bold text-dark">Rp 1.000.000</p>
                        <button class="text-primary text-sm font-medium">
                            Lihat Detail <i class="fa-solid fa-chevron-right text-xs ml-1"></i>
                        </button>
                    </div>
                </div>

                <div class="donation-card bg-white rounded-xl border border-gray-100 p-4" data-id="4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm text-dark mb-1">Pembangunan Masjid Al-Ikhlas</h4>
                            <p class="text-xs text-gray-500">05 Januari 2024, 11:20</p>
                        </div>
                        <span class="px-2 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded">Berhasil</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-base font-bold text-dark">Rp 750.000</p>
                        <button class="text-primary text-sm font-medium">
                            Lihat Detail <i class="fa-solid fa-chevron-right text-xs ml-1"></i>
                        </button>
                    </div>
                </div>

                <div class="donation-card bg-white rounded-xl border border-gray-100 p-4" data-id="5">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm text-dark mb-1">Santunan Bulanan Anak Yatim</h4>
                            <p class="text-xs text-gray-500">03 Januari 2024, 08:00</p>
                        </div>
                        <span class="px-2 py-1 bg-red-50 text-red-600 text-xs font-semibold rounded">Gagal</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-base font-bold text-dark">Rp 300.000</p>
                        <button class="text-primary text-sm font-medium">
                            Lihat Detail <i class="fa-solid fa-chevron-right text-xs ml-1"></i>
                        </button>
                    </div>
                </div>

                <div class="donation-card bg-white rounded-xl border border-gray-100 p-4" data-id="6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm text-dark mb-1">Beasiswa Tahfidz Al-Quran</h4>
                            <p class="text-xs text-gray-500">28 Desember 2023, 15:10</p>
                        </div>
                        <span
                            class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded">Kadaluarsa</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-base font-bold text-dark">Rp 200.000</p>
                        <button class="text-primary text-sm font-medium">
                            Lihat Detail <i class="fa-solid fa-chevron-right text-xs ml-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div id="detail-overlay" class="overlay fixed inset-0 bg-black bg-opacity-50 z-40"></div>

    <div id="detail-modal"
        class="detail-modal fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl z-50 max-h-[90vh] overflow-hidden">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-4 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-dark">Detail Donasi</h3>
                <button id="close-detail-btn" class="w-8 h-8 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-gray-600 text-xl"></i>
                </button>
            </div>
        </div>
        <div class="overflow-y-auto px-4 py-4 pb-32">
            <div class="bg-green-50 rounded-xl p-4 mb-4 text-center">
                <i class="fa-solid fa-circle-check text-green-600 text-4xl mb-2"></i>
                <p class="text-sm font-semibold text-green-600">Donasi Berhasil</p>
            </div>

            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">ID Transaksi</p>
                    <p class="text-sm font-semibold text-dark">TRX-2024-001-12345</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">Nama Program</p>
                    <p class="text-sm font-semibold text-dark">Bangun Sekolah untuk Anak Yatim</p>
                </div>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-1">Akad</p>
                        <p class="text-sm font-semibold text-dark">Sedekah</p>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-1">Kategori</p>
                        <p class="text-sm font-semibold text-dark">Pendidikan</p>
                    </div>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">Nominal Donasi</p>
                    <p class="text-xl font-bold text-primary">Rp 500.000</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">Metode Pembayaran</p>
                    <p class="text-sm font-semibold text-dark">Transfer Bank BCA</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">Tanggal & Waktu</p>
                    <p class="text-sm font-semibold text-dark">12 Januari 2024, 14:30 WIB</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">Nama Donatur</p>
                    <p class="text-sm font-semibold text-dark">Hamba Allah</p>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <button
                        class="w-full py-3 bg-gray-100 text-dark rounded-xl text-sm font-semibold flex items-center justify-center gap-2">
                        <i class="fa-solid fa-download"></i>
                        Unduh Bukti Donasi
                    </button>
                </div>
            </div>
        </div>

        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-4 py-3 space-y-2">
            <button class="w-full py-3 bg-primary text-white rounded-xl text-sm font-semibold">
                Donasi Lagi
            </button>
            <div class="flex gap-2">
                <button class="flex-1 py-3 bg-gray-100 text-dark rounded-xl text-sm font-semibold">
                    Bagikan
                </button>
                <button class="flex-1 py-3 bg-gray-100 text-dark rounded-xl text-sm font-semibold">
                    Hubungi CS
                </button>
            </div>
        </div>
    </div>

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
                <i class="fa-solid fa-hand-holding-heart text-primary text-lg"></i>
                <span class="text-xs font-medium text-primary">Donasi Saya</span>
            </button>
            <button class="flex flex-col items-center gap-1 min-w-[60px]">
                <i class="fa-solid fa-chart-line text-gray-400 text-lg"></i>
                <span class="text-xs font-medium text-gray-400">Laporan</span>
            </button>
            <button class="flex flex-col items-center gap-1 min-w-[60px]">
                <i class="fa-solid fa-user text-gray-400 text-lg"></i>
                <span class="text-xs font-medium text-gray-400">Profil</span>
            </button>
        </div>
    </nav>

    <script>
        window.addEventListener('load', function() {
            const tabFilters = document.querySelectorAll('.tab-filter');
            const donationCards = document.querySelectorAll('.donation-card');
            const detailModal = document.getElementById('detail-modal');
            const detailOverlay = document.getElementById('detail-overlay');
            const closeDetailBtn = document.getElementById('close-detail-btn');

            tabFilters.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabFilters.forEach(t => {
                        t.classList.remove('bg-primary', 'text-white');
                        t.classList.add('bg-gray-100', 'text-gray-600');
                    });
                    this.classList.remove('bg-gray-100', 'text-gray-600');
                    this.classList.add('bg-primary', 'text-white');
                });
            });

            donationCards.forEach(card => {
                card.addEventListener('click', function() {
                    detailModal.classList.add('active');
                    detailOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            });

            function closeDetail() {
                detailModal.classList.remove('active');
                detailOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            closeDetailBtn.addEventListener('click', closeDetail);
            detailOverlay.addEventListener('click', closeDetail);
        });
    </script>
</body>

</html>

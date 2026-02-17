<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>
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

        .expandable-card {
            transition: all 0.3s ease;
        }

        .expandable-card.expanded {
            max-height: 1000px;
        }
    </style>
</head>

<body class="bg-light">
    <header id="header" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="px-4 py-3 flex items-center gap-3">
            <button class="w-9 h-9 flex items-center justify-center">
                <i class="fa-solid fa-arrow-left text-dark text-lg"></i>
            </button>
            <h1 class="text-base font-bold text-dark flex-1">Laporan</h1>
            <button class="w-9 h-9 flex items-center justify-center">
                <i class="fa-regular fa-circle-question text-dark text-lg"></i>
            </button>
        </div>
    </header>

    <section id="period-selector" class="bg-white px-4 py-3 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">Periode Laporan</p>
            <select class="text-sm font-semibold text-dark bg-transparent outline-none">
                <option>Januari 2026</option>
                <option>Desember 2025</option>
                <option>November 2025</option>
                <option>Oktober 2025</option>
            </select>
        </div>
    </section>

    <main id="main-content" class="pb-20">
        <section id="financial-summary" class="px-4 py-4">
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-white">
                    <p class="text-xs opacity-90 mb-1">Dana Masuk</p>
                    <p class="text-lg font-bold">Rp 125.5M</p>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white">
                    <p class="text-xs opacity-90 mb-1">Tersalurkan</p>
                    <p class="text-lg font-bold">Rp 98.2M</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white rounded-xl p-4 border border-gray-100">
                    <p class="text-xs text-gray-600 mb-1">Sisa Dana</p>
                    <p class="text-lg font-bold text-primary">Rp 27.3M</p>
                </div>
                <div class="bg-white rounded-xl p-4 border border-gray-100">
                    <p class="text-xs text-gray-600 mb-1">Biaya Operasional</p>
                    <p class="text-lg font-bold text-gray-700">Rp 3.2M</p>
                </div>
            </div>
        </section>

        <section id="filter-section" class="px-4 py-2">
            <div class="flex gap-2 overflow-x-auto hide-scrollbar">
                <button
                    class="filter-btn px-3 py-2 rounded-full text-xs font-medium whitespace-nowrap bg-primary text-white">
                    Semua
                </button>
                <button
                    class="filter-btn px-3 py-2 rounded-full text-xs font-medium whitespace-nowrap bg-gray-100 text-gray-600">
                    Sedekah
                </button>
                <button
                    class="filter-btn px-3 py-2 rounded-full text-xs font-medium whitespace-nowrap bg-gray-100 text-gray-600">
                    Wakaf
                </button>
                <button
                    class="filter-btn px-3 py-2 rounded-full text-xs font-medium whitespace-nowrap bg-gray-100 text-gray-600">
                    Zakat
                </button>
                <button
                    class="filter-btn px-3 py-2 rounded-full text-xs font-medium whitespace-nowrap bg-gray-100 text-gray-600">
                    Qurban
                </button>
            </div>
        </section>

        <section id="program-reports" class="px-4 py-4">
            <h3 class="text-sm font-bold text-dark mb-3">Laporan Program</h3>
            <div class="space-y-3">
                <div class="program-card expandable-card bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-sm text-dark mb-1">Bangun Sekolah untuk Anak Yatim</h4>
                                <div class="flex gap-2 text-xs">
                                    <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded">Sedekah</span>
                                    <span class="px-2 py-1 bg-purple-50 text-purple-600 rounded">Pendidikan</span>
                                </div>
                            </div>
                            <button class="expand-btn text-gray-400">
                                <i class="fa-solid fa-chevron-down text-sm"></i>
                            </button>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Dana Terkumpul</span>
                                <span class="font-semibold">Rp 15.2M</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Dana Tersalurkan</span>
                                <span class="font-semibold text-green-600">Rp 12.8M</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 84%"></div>
                            </div>
                            <p class="text-xs text-gray-600">84% Tersalurkan</p>
                        </div>
                    </div>
                    <div class="expanded-content hidden px-4 pb-4">
                        <div class="border-t border-gray-100 pt-4">
                            <h5 class="text-xs font-semibold text-dark mb-2">Dokumentasi Penyaluran</h5>
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <div class="h-20 overflow-hidden rounded-lg">
                                    <img class="w-full h-full object-cover"
                                        src="https://storage.googleapis.com/uxpilot-auth.appspot.com/34ef8149e4-0fbfd7f11a723d016e37.png"
                                        alt="construction workers building school foundation for orphan children, bright daylight, progress documentation" />
                                </div>
                                <div class="h-20 overflow-hidden rounded-lg">
                                    <img class="w-full h-full object-cover"
                                        src="https://storage.googleapis.com/uxpilot-auth.appspot.com/7a84742e6f-7997a47ff83aa9c8a555.png"
                                        alt="completed school building with children playing in yard, educational facilities documentation" />
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mb-3">Pembangunan sekolah telah mencapai 84%. Saat ini dalam
                                tahap finishing interior dan persiapan fasilitas pembelajaran.</p>
                            <p class="text-xs text-gray-500">Dokumentasi: 15 Januari 2026</p>
                        </div>
                    </div>
                </div>

                <div class="program-card expandable-card bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-sm text-dark mb-1">Klinik Gratis untuk Masyarakat Dhuafa
                                </h4>
                                <div class="flex gap-2 text-xs">
                                    <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded">Sedekah</span>
                                    <span class="px-2 py-1 bg-green-50 text-green-600 rounded">Kesehatan</span>
                                </div>
                            </div>
                            <button class="expand-btn text-gray-400">
                                <i class="fa-solid fa-chevron-down text-sm"></i>
                            </button>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Dana Terkumpul</span>
                                <span class="font-semibold">Rp 8.5M</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Dana Tersalurkan</span>
                                <span class="font-semibold text-green-600">Rp 8.5M</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                            </div>
                            <p class="text-xs text-green-600 font-semibold">100% Tersalurkan</p>
                        </div>
                    </div>
                    <div class="expanded-content hidden px-4 pb-4">
                        <div class="border-t border-gray-100 pt-4">
                            <h5 class="text-xs font-semibold text-dark mb-2">Dokumentasi Penyaluran</h5>
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <div class="h-20 overflow-hidden rounded-lg">
                                    <img class="w-full h-full object-cover"
                                        src="https://storage.googleapis.com/uxpilot-auth.appspot.com/f96c7a8447-cc04410c56d0af2df6d9.png"
                                        alt="medical clinic interior with doctors treating patients, free healthcare for poor community" />
                                </div>
                                <div class="h-20 overflow-hidden rounded-lg">
                                    <img class="w-full h-full object-cover"
                                        src="https://storage.googleapis.com/uxpilot-auth.appspot.com/49d82200fc-360f3cb1bc847d75e905.png"
                                        alt="medical equipment and supplies in clinic, healthcare documentation" />
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mb-3">Klinik telah beroperasi penuh dan melayani 250+
                                pasien per bulan. Program kesehatan gratis berjalan lancar.</p>
                            <p class="text-xs text-gray-500">Dokumentasi: 10 Januari 2026</p>
                        </div>
                    </div>
                </div>

                <div class="program-card expandable-card bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-sm text-dark mb-1">Sumur Air Bersih untuk Desa Terpencil
                                </h4>
                                <div class="flex gap-2 text-xs">
                                    <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded">Sedekah</span>
                                    <span class="px-2 py-1 bg-orange-50 text-orange-600 rounded">Sosial</span>
                                </div>
                            </div>
                            <button class="expand-btn text-gray-400">
                                <i class="fa-solid fa-chevron-down text-sm"></i>
                            </button>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Dana Terkumpul</span>
                                <span class="font-semibold">Rp 12.3M</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Dana Tersalurkan</span>
                                <span class="font-semibold text-yellow-600">Rp 7.8M</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 63%"></div>
                            </div>
                            <p class="text-xs text-yellow-600">63% Tersalurkan</p>
                        </div>
                    </div>
                    <div class="expanded-content hidden px-4 pb-4">
                        <div class="border-t border-gray-100 pt-4">
                            <h5 class="text-xs font-semibold text-dark mb-2">Dokumentasi Penyaluran</h5>
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <div class="h-20 overflow-hidden rounded-lg">
                                    <img class="w-full h-full object-cover"
                                        src="https://storage.googleapis.com/uxpilot-auth.appspot.com/ff80befd2c-1f78ff87b4be4c02e845.png"
                                        alt="water well drilling construction in remote village, clean water access project" />
                                </div>
                                <div class="h-20 overflow-hidden rounded-lg">
                                    <img class="w-full h-full object-cover"
                                        src="https://storage.googleapis.com/uxpilot-auth.appspot.com/49f9b7772f-f841320eb7535cd85e17.png"
                                        alt="villagers collecting clean water from new well, community benefiting from project" />
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mb-3">Pembangunan sumur sedang dalam proses. 3 dari 5 sumur
                                telah selesai dan dapat digunakan masyarakat.</p>
                            <p class="text-xs text-gray-500">Dokumentasi: 8 Januari 2026</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="download-section" class="px-4 py-4">
            <div class="bg-white rounded-xl border border-gray-100 p-4">
                <h3 class="text-sm font-bold text-dark mb-3">Unduh Laporan</h3>
                <p class="text-xs text-gray-600 mb-4">Laporan lengkap periode Januari 2026 dengan tanda tangan resmi
                    pengurus yayasan.</p>
                <div class="flex gap-2">
                    <button
                        class="flex-1 py-3 bg-primary text-white rounded-xl text-sm font-semibold flex items-center justify-center gap-2">
                        <i class="fa-solid fa-file-pdf"></i>
                        PDF
                    </button>
                    <button
                        class="flex-1 py-3 bg-green-600 text-white rounded-xl text-sm font-semibold flex items-center justify-center gap-2">
                        <i class="fa-solid fa-file-excel"></i>
                        Excel
                    </button>
                </div>
            </div>
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
                <i class="fa-solid fa-chart-line text-primary text-lg"></i>
                <span class="text-xs font-medium text-primary">Laporan</span>
            </button>
            <button class="flex flex-col items-center gap-1 min-w-[60px]">
                <i class="fa-solid fa-user text-gray-400 text-lg"></i>
                <span class="text-xs font-medium text-gray-400">Profil</span>
            </button>
        </div>
    </nav>

    <script>
        window.addEventListener('load', function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const expandBtns = document.querySelectorAll('.expand-btn');
            const programCards = document.querySelectorAll('.program-card');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterBtns.forEach(b => {
                        b.classList.remove('bg-primary', 'text-white');
                        b.classList.add('bg-gray-100', 'text-gray-600');
                    });
                    this.classList.remove('bg-gray-100', 'text-gray-600');
                    this.classList.add('bg-primary', 'text-white');
                });
            });

            expandBtns.forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    const card = programCards[index];
                    const expandedContent = card.querySelector('.expanded-content');
                    const icon = btn.querySelector('i');

                    if (expandedContent.classList.contains('hidden')) {
                        expandedContent.classList.remove('hidden');
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                        card.classList.add('expanded');
                    } else {
                        expandedContent.classList.add('hidden');
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                        card.classList.remove('expanded');
                    }
                });
            });
        });
    </script>
</body>

</html>

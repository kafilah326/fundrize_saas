<html lang="id">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program</title>
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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet">
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

        .bottom-sheet {
            transform: translateY(100%);
            transition: transform 0.3s ease-in-out;
        }

        .bottom-sheet.active {
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
    <style data-grapesjs-styles="true">
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
        }

        body {
            font-family: "Inter", sans-serif;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            scrollbar-width: none;
        }

        .bottom-sheet {
            transform: translateY(100%);
            transition-property: transform;
            transition-duration: 0.3s;
            transition-timing-function: ease-in-out;
            transition-delay: 0s;
        }

        .bottom-sheet.active {
            transform: translateY(0px);
        }

        .overlay {
            opacity: 0;
            pointer-events: none;
            transition-property: opacity;
            transition-duration: 0.3s;
            transition-timing-function: ease-in-out;
            transition-delay: 0s;
        }

        .overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        #i44im {
            max-height: calc(85vh - 140px);
        }

        #i4a1mk {
            width: 72%;
        }

        #ijf12h {
            width: 65%;
        }

        #i8rr37 {
            width: 58%;
        }

        #ihiqb1 {
            width: 85%;
        }

        #ix3acg {
            width: 48%;
        }

        #ihp38g {
            width: 55%;
        }

        #i5jtzt {
            width: 42%;
        }

        #iy8k0v {
            width: 78%;
        }
    </style>
</head>

<body class="bg-light">
    <header id="header" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="px-4 py-3 flex items-center gap-3"><button class="w-9 h-9 flex items-center justify-center"><i
                    class="fa-solid fa-arrow-left text-dark text-lg"></i></button>
            <h1 class="text-base font-bold text-dark flex-1">Program</h1><button
                class="w-9 h-9 flex items-center justify-center bg-light rounded-full"><i
                    class="fa-solid fa-magnifying-glass text-gray-600 text-sm"></i></button>
        </div>
    </header>
    <section id="filter-compact" class="bg-white px-4 py-3 border-b border-gray-100">
        <div class="flex items-center gap-2"><button id="open-filter-btn"
                class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-full"><i
                    class="fa-solid fa-sliders text-dark text-sm"></i><span
                    class="text-sm font-semibold text-dark">Filter</span></button>
            <div id="filter-summary" class="flex-1 flex items-center gap-2 overflow-x-auto hide-scrollbar">
            </div><button id="reset-filter-btn"
                class="hidden w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full flex-shrink-0"><i
                    class="fa-solid fa-xmark text-gray-600 text-sm"></i></button>
        </div>
    </section>
    <div id="filter-overlay" class="overlay fixed inset-0 bg-black bg-opacity-50 z-40"></div>
    <div id="filter-bottom-sheet"
        class="bottom-sheet fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl z-50 max-h-[85vh] overflow-hidden">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-4 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-dark">Filter Program</h3><button id="close-filter-btn"
                    class="w-8 h-8 flex items-center justify-center"><i
                        class="fa-solid fa-xmark text-gray-600 text-xl"></i></button>
            </div>
        </div>
        <div class="overflow-y-auto px-4 py-4 pb-24" id="i44im">
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-dark mb-3">Akad</h4>
                <div class="flex flex-wrap gap-2"><button data-value="semua"
                        class="filter-akad-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Semua
                    </button><button data-value="sedekah"
                        class="filter-akad-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Sedekah
                    </button><button data-value="wakaf"
                        class="filter-akad-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Wakaf
                    </button><button data-value="zakat"
                        class="filter-akad-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Zakat
                    </button><button data-value="qurban"
                        class="filter-akad-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Qurban
                    </button><button data-value="fidyah"
                        class="filter-akad-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Fidyah
                    </button></div>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-dark mb-3">Kategori</h4>
                <div class="flex flex-wrap gap-2"><button data-value="semua"
                        class="filter-kategori-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Semua
                    </button><button data-value="pendidikan"
                        class="filter-kategori-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Pendidikan
                    </button><button data-value="kesehatan"
                        class="filter-kategori-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Kesehatan
                    </button><button data-value="sosial"
                        class="filter-kategori-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Sosial
                    </button><button data-value="kemanusiaan"
                        class="filter-kategori-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Kemanusiaan
                    </button><button data-value="masjid"
                        class="filter-kategori-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Masjid
                    </button><button data-value="dakwah"
                        class="filter-kategori-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Dakwah
                    </button><button data-value="bencana"
                        class="filter-kategori-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Bencana
                    </button><button data-value="lainnya"
                        class="filter-kategori-chip px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-200 text-gray-600">
                        Lainnya
                    </button></div>
            </div>
        </div>
        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-4 py-3 flex gap-3">
            <button id="reset-all-btn" class="flex-1 py-3 bg-gray-100 text-dark rounded-xl text-sm font-semibold">
                Reset
            </button><button id="apply-filter-btn"
                class="flex-1 py-3 bg-primary text-white rounded-xl text-sm font-semibold">
                Terapkan
            </button>
        </div>
    </div>
    <main id="main-content" class="pb-20">
        <section id="program-list" class="bg-white px-4 py-4 mt-2">
            <div class="flex items-center justify-between mb-4">
                <p class="text-xs text-gray-500">Menampilkan <span class="font-semibold text-dark">24 program</span></p>
            </div>
            <div class="space-y-3">
                <div class="flex gap-3 bg-white rounded-xl border border-gray-100 p-2">
                    <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg"><img
                            src="https://storage.googleapis.com/uxpilot-auth.appspot.com/019c3072b0-56d6447c5f07b7a84fd5.png"
                            alt="Children studying in Islamic school classroom, warm lighting, educational fundraising campaign, 16:9 aspect ratio"
                            class="w-full h-full object-cover"></div>
                    <div class="flex-1 flex flex-col justify-between py-1">
                        <div>
                            <div class="flex items-center gap-2 mb-1"></div>
                            <h4 class="font-semibold text-sm text-dark mb-1 line-clamp-2">
                                Bangun Sekolah untuk Anak Yatim</h4>
                            <p class="text-xs font-bold text-dark mb-1">Rp 65.250.000</p>
                        </div>
                        <div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                <div class="bg-primary h-1.5 rounded-full" id="i4a1mk"></div>
                            </div>
                            <div class="flex items-center justify-between"><span class="text-xs text-gray-500">72%
                                    terkumpul</span><button class="text-gray-400"><i
                                        class="fa-solid fa-grip-vertical text-xs"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 bg-white rounded-xl border border-gray-100 p-2">
                    <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg"><img
                            src="https://storage.googleapis.com/uxpilot-auth.appspot.com/ddd27114a1-b09c10ca83260c4a28a3.png"
                            alt="Medical clinic in remote area, doctors helping patients, healthcare charity, 16:9 aspect ratio"
                            class="w-full h-full object-cover"></div>
                    <div class="flex-1 flex flex-col justify-between py-1">
                        <div>
                            <div class="flex items-center gap-2 mb-1"></div>
                            <h4 class="font-semibold text-sm text-dark mb-1 line-clamp-2">
                                Klinik Gratis untuk Masyarakat Dhuafa</h4>
                            <p class="text-xs font-bold text-dark mb-1">Rp 45.250.000</p>
                        </div>
                        <div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                <div class="bg-primary h-1.5 rounded-full" id="ijf12h"></div>
                            </div>
                            <div class="flex items-center justify-between"><span class="text-xs text-gray-500">65%
                                    terkumpul</span><button class="text-gray-400"><i
                                        class="fa-solid fa-grip-vertical text-xs"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 bg-white rounded-xl border border-gray-100 p-2">
                    <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg"><img
                            src="https://storage.googleapis.com/uxpilot-auth.appspot.com/7061fc5325-49d27c992285a75518f3.png"
                            alt="Clean water well project in rural village, community gathering, humanitarian aid, 16:9 aspect ratio"
                            class="w-full h-full object-cover"></div>
                    <div class="flex-1 flex flex-col justify-between py-1">
                        <div>
                            <div class="flex items-center gap-2 mb-1"></div>
                            <h4 class="font-semibold text-sm text-dark mb-1 line-clamp-2">
                                Sumur Air Bersih untuk Desa Terpencil</h4>
                            <p class="text-xs font-bold text-dark mb-1">Rp 38.900.000</p>
                        </div>
                        <div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                <div class="bg-primary h-1.5 rounded-full" id="i8rr37"></div>
                            </div>
                            <div class="flex items-center justify-between"><span class="text-xs text-gray-500">58%
                                    terkumpul</span><button class="text-gray-400"><i
                                        class="fa-solid fa-grip-vertical text-xs"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 bg-white rounded-xl border border-gray-100 p-2">
                    <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg"><img
                            src="https://storage.googleapis.com/uxpilot-auth.appspot.com/015933f6f4-83cfb06f4521f27e73c2.png"
                            alt="Beautiful mosque construction progress, Islamic architecture, community project, 16:9 aspect ratio"
                            class="w-full h-full object-cover"></div>
                    <div class="flex-1 flex flex-col justify-between py-1">
                        <div>
                            <div class="flex items-center gap-2 mb-1"></div>
                            <h4 class="font-semibold text-sm text-dark mb-1 line-clamp-2">
                                Pembangunan Masjid Al-Ikhlas</h4>
                            <p class="text-xs font-bold text-dark mb-1">Rp 82.450.000</p>
                        </div>
                        <div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                <div class="bg-primary h-1.5 rounded-full" id="ihiqb1"></div>
                            </div>
                            <div class="flex items-center justify-between"><span class="text-xs text-gray-500">85%
                                    terkumpul</span><button class="text-gray-400"><i
                                        class="fa-solid fa-grip-vertical text-xs"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 bg-white rounded-xl border border-gray-100 p-2">
                    <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg"><img
                            src="https://storage.googleapis.com/uxpilot-auth.appspot.com/3e63fa336e-1318750ccad1d4eb7d0a.png"
                            alt="Orphanage children happy playing together, Islamic orphanage, charity home, 16:9 aspect ratio"
                            class="w-full h-full object-cover"></div>
                    <div class="flex-1 flex flex-col justify-between py-1">
                        <div>
                            <div class="flex items-center gap-2 mb-1"></div>
                            <h4 class="font-semibold text-sm text-dark mb-1 line-clamp-2">
                                Santunan Bulanan Anak Yatim</h4>
                            <p class="text-xs font-bold text-dark mb-1">Rp 28.500.000</p>
                        </div>
                        <div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                <div class="bg-primary h-1.5 rounded-full" id="ix3acg"></div>
                            </div>
                            <div class="flex items-center justify-between"><span class="text-xs text-gray-500">48%
                                    terkumpul</span><button class="text-gray-400"><i
                                        class="fa-solid fa-grip-vertical text-xs"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 bg-white rounded-xl border border-gray-100 p-2">
                    <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg"><img
                            src="https://storage.googleapis.com/uxpilot-auth.appspot.com/a35a7405d6-5b437669c291a92b08f0.png"
                            alt="Quran teacher with students in pesantren, Islamic education, learning Arabic, 16:9 aspect ratio"
                            class="w-full h-full object-cover"></div>
                    <div class="flex-1 flex flex-col justify-between py-1">
                        <div>
                            <div class="flex items-center gap-2 mb-1"></div>
                            <h4 class="font-semibold text-sm text-dark mb-1 line-clamp-2">
                                Beasiswa Tahfidz Al-Quran</h4>
                            <p class="text-xs font-bold text-dark mb-1">Rp 32.800.000</p>
                        </div>
                        <div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                <div class="bg-primary h-1.5 rounded-full" id="ihp38g"></div>
                            </div>
                            <div class="flex items-center justify-between"><span class="text-xs text-gray-500">55%
                                    terkumpul</span><button class="text-gray-400"><i
                                        class="fa-solid fa-grip-vertical text-xs"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 bg-white rounded-xl border border-gray-100 p-2">
                    <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg"><img
                            src="https://storage.googleapis.com/uxpilot-auth.appspot.com/3fb05f88d4-f6bca6137ca5cc9f8e9a.png"
                            alt="Food distribution for poor families, ramadan charity, humanitarian aid, 16:9 aspect ratio"
                            class="w-full h-full object-cover"></div>
                    <div class="flex-1 flex flex-col justify-between py-1">
                        <div>
                            <div class="flex items-center gap-2 mb-1"></div>
                            <h4 class="font-semibold text-sm text-dark mb-1 line-clamp-2">
                                Paket Sembako untuk Keluarga Dhuafa</h4>
                            <p class="text-xs font-bold text-dark mb-1">Rp 18.450.000</p>
                        </div>
                        <div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                <div class="bg-primary h-1.5 rounded-full" id="i5jtzt"></div>
                            </div>
                            <div class="flex items-center justify-between"><span class="text-xs text-gray-500">42%
                                    terkumpul</span><button class="text-gray-400"><i
                                        class="fa-solid fa-grip-vertical text-xs"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 bg-white rounded-xl border border-gray-100 p-2">
                    <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg"><img
                            src="https://storage.googleapis.com/uxpilot-auth.appspot.com/0e95e923ea-234bbfab81354b72c729.png"
                            alt="Disaster relief team helping flood victims, emergency humanitarian response, 16:9 aspect ratio"
                            class="w-full h-full object-cover"></div>
                    <div class="flex-1 flex flex-col justify-between py-1">
                        <div>
                            <div class="flex items-center gap-2 mb-1"></div>
                            <h4 class="font-semibold text-sm text-dark mb-1 line-clamp-2">
                                Bantuan Korban Banjir Bandang</h4>
                            <p class="text-xs font-bold text-dark mb-1">Rp 52.300.000</p>
                        </div>
                        <div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                <div class="bg-primary h-1.5 rounded-full" id="iy8k0v"></div>
                            </div>
                            <div class="flex items-center justify-between"><span class="text-xs text-gray-500">78%
                                    terkumpul</span><button class="text-gray-400"><i
                                        class="fa-solid fa-grip-vertical text-xs"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div><button id="load-more-btn"
                class="w-full mt-4 py-3 bg-gray-100 text-dark rounded-xl text-sm font-semibold">
                Muat Lebih Banyak
            </button>
        </section>
    </main>
    <nav id="bottom-nav" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-2 z-50">
        <div class="flex items-center justify-between max-w-md mx-auto"><button
                class="flex flex-col items-center gap-1 min-w-[60px]"><i
                    class="fa-solid fa-house text-gray-400 text-lg"></i><span
                    class="text-xs font-medium text-gray-400">Home</span></button><button
                class="flex flex-col items-center gap-1 min-w-[60px]"><i
                    class="fa-solid fa-list text-primary text-lg"></i><span
                    class="text-xs font-medium text-primary">Program</span></button><button
                class="flex flex-col items-center gap-1 min-w-[60px]"><i
                    class="fa-solid fa-hand-holding-heart text-gray-400 text-lg"></i><span
                    class="text-xs font-medium text-gray-400">Donasi
                    Saya</span></button><button class="flex flex-col items-center gap-1 min-w-[60px]"><i
                    class="fa-solid fa-chart-line text-gray-400 text-lg"></i><span
                    class="text-xs font-medium text-gray-400">Laporan</span></button><button
                class="flex flex-col items-center gap-1 min-w-[60px]"><i
                    class="fa-solid fa-user text-gray-400 text-lg"></i><span
                    class="text-xs font-medium text-gray-400">Profil</span></button>
        </div>
    </nav>
    <script>
        window.addEventListener('load', function() {
            const openFilterBtn = document.getElementById('open-filter-btn');
            const closeFilterBtn = document.getElementById('close-filter-btn');
            const filterOverlay = document.getElementById('filter-overlay');
            const filterBottomSheet = document.getElementById(
                'filter-bottom-sheet');
            const applyFilterBtn = document.getElementById('apply-filter-btn');
            const resetAllBtn = document.getElementById('reset-all-btn');
            const resetFilterBtn = document.getElementById('reset-filter-btn');
            const filterSummary = document.getElementById('filter-summary');

            const akadChips = document.querySelectorAll('.filter-akad-chip');
            const kategoriChips = document.querySelectorAll(
                '.filter-kategori-chip');

            let selectedAkad = [];
            let selectedKategori = [];

            function openFilter() {
                filterBottomSheet.classList.add('active');
                filterOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeFilter() {
                filterBottomSheet.classList.remove('active');
                filterOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            function updateFilterSummary() {
                filterSummary.innerHTML = '';
                const allFilters = [...selectedAkad, ...selectedKategori];

                if (allFilters.length > 0) {
                    const summaryText = allFilters.map(f => f.charAt(0)
                        .toUpperCase() + f.slice(1)).join(' • ');
                    const count = allFilters.length;
                    filterSummary.innerHTML =
                        `<span class="text-sm text-gray-600 whitespace-nowrap">${summaryText} (${count})</span>`;
                    resetFilterBtn.classList.remove('hidden');
                } else {
                    resetFilterBtn.classList.add('hidden');
                }
            }

            akadChips.forEach(chip => {
                chip.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');

                    if (value === 'semua') {
                        akadChips.forEach(c => {
                            c.classList.remove('border-primary',
                                'bg-orange-50', 'text-primary');
                            c.classList.add('border-gray-200',
                                'text-gray-600');
                        });
                        this.classList.remove('border-gray-200',
                            'text-gray-600');
                        this.classList.add('border-primary', 'bg-orange-50',
                            'text-primary');
                        selectedAkad = [];
                    } else {
                        akadChips[0].classList.remove('border-primary',
                            'bg-orange-50', 'text-primary');
                        akadChips[0].classList.add('border-gray-200',
                            'text-gray-600');

                        if (this.classList.contains('border-primary')) {
                            this.classList.remove('border-primary',
                                'bg-orange-50', 'text-primary');
                            this.classList.add('border-gray-200',
                                'text-gray-600');
                            selectedAkad = selectedAkad.filter(v => v !==
                                value);
                        } else {
                            this.classList.remove('border-gray-200',
                                'text-gray-600');
                            this.classList.add('border-primary', 'bg-orange-50',
                                'text-primary');
                            selectedAkad.push(value);
                        }
                    }
                });
            });

            kategoriChips.forEach(chip => {
                chip.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');

                    if (value === 'semua') {
                        kategoriChips.forEach(c => {
                            c.classList.remove('border-primary',
                                'bg-orange-50', 'text-primary');
                            c.classList.add('border-gray-200',
                                'text-gray-600');
                        });
                        this.classList.remove('border-gray-200',
                            'text-gray-600');
                        this.classList.add('border-primary', 'bg-orange-50',
                            'text-primary');
                        selectedKategori = [];
                    } else {
                        kategoriChips[0].classList.remove('border-primary',
                            'bg-orange-50', 'text-primary');
                        kategoriChips[0].classList.add('border-gray-200',
                            'text-gray-600');

                        if (this.classList.contains('border-primary')) {
                            this.classList.remove('border-primary',
                                'bg-orange-50', 'text-primary');
                            this.classList.add('border-gray-200',
                                'text-gray-600');
                            selectedKategori = selectedKategori.filter(v =>
                                v !== value);
                        } else {
                            this.classList.remove('border-gray-200',
                                'text-gray-600');
                            this.classList.add('border-primary', 'bg-orange-50',
                                'text-primary');
                            selectedKategori.push(value);
                        }
                    }
                });
            });

            openFilterBtn.addEventListener('click', openFilter);
            closeFilterBtn.addEventListener('click', closeFilter);

            applyFilterBtn.addEventListener('click', function() {
                closeFilter();
                updateFilterSummary();
            });

            resetAllBtn.addEventListener('click', function() {
                selectedAkad = [];
                selectedKategori = [];
                akadChips.forEach(c => c.classList.remove('border-primary',
                    'bg-orange-50', 'text-primary'));
                kategoriChips.forEach(c => c.classList.remove(
                    'border-primary', 'bg-orange-50', 'text-primary'));
                updateFilterSummary();
            });

            resetFilterBtn.addEventListener('click', function() {
                closeFilter();
            });

            const loadMoreBtn = document.getElementById('load-more-btn');
            loadMoreBtn.addEventListener('click', function() {
                this.innerHTML =
                    '<i class="fa-solid fa-spinner fa-spin"></i> Memuat...';
                setTimeout(() => {
                    this.textContent = 'Muat Lebih Banyak';
                }, 1000);
            });
        });
    </script>
</body>

</html>

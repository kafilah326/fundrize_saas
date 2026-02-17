<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Program</title>
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
            <h1 class="text-base font-bold text-dark flex-1">Detail Program</h1>
            <button class="w-9 h-9 flex items-center justify-center">
                <i class="fa-solid fa-share-nodes text-dark text-lg"></i>
            </button>
        </div>
    </header>

    <main id="main-content" class="pb-24">
        <section id="media-section">
            <div class="w-full aspect-video overflow-hidden">
                <img class="w-full h-full object-cover"
                    src="https://storage.googleapis.com/uxpilot-auth.appspot.com/019c3072b0-11dcdc14a4fa39656ba3.png"
                    alt="Children studying in Islamic school classroom, warm natural lighting, educational fundraising campaign, hopeful atmosphere, 16:9 aspect ratio" />
            </div>
        </section>

        <section id="info-section" class="bg-white px-4 py-4">
            <div class="flex flex-wrap gap-2 mb-3">
                <span class="px-3 py-1 bg-orange-50 text-primary text-xs font-semibold rounded-full">Sedekah</span>
                <span class="px-3 py-1 bg-orange-50 text-primary text-xs font-semibold rounded-full">Pendidikan</span>
                <span
                    class="px-3 py-1 bg-red-50 text-red-700 text-xs font-semibold rounded-full flex items-center gap-1">
                    <i class="fa-solid fa-fire text-xs"></i> Mendesak
                </span>
            </div>

            <h2 class="text-xl font-bold text-dark mb-2">Bangun Sekolah untuk Anak Yatim</h2>
            <p class="text-sm text-gray-600 leading-relaxed">Membangun masa depan anak yatim melalui pendidikan
                berkualitas dengan fasilitas sekolah yang layak dan nyaman.</p>
        </section>

        <section id="progress-section" class="bg-white px-4 py-4 mt-2">
            <div class="mb-3">
                <div class="flex items-end gap-2 mb-1">
                    <span class="text-2xl font-bold text-dark">Rp 65.250.000</span>
                    <span class="text-sm text-gray-500 pb-1">terkumpul</span>
                </div>
                <p class="text-sm text-gray-600">dari target Rp 90.000.000</p>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                <div class="bg-primary h-2 rounded-full" style="width: 72%"></div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-1 text-gray-600">
                    <i class="fa-solid fa-users text-xs"></i>
                    <span class="font-semibold text-dark">1,247</span> Donatur
                </div>
                <div class="flex items-center gap-1 text-gray-600">
                    <i class="fa-solid fa-clock text-xs"></i>
                    <span class="font-semibold text-dark">15</span> hari lagi
                </div>
            </div>
        </section>

        <section id="description-section" class="bg-white px-4 py-4 mt-2">
            <h3 class="text-base font-bold text-dark mb-3">Deskripsi Program</h3>
            <div class="text-sm text-gray-700 leading-relaxed space-y-3">
                <p>Ribuan anak yatim di pelosok negeri masih kesulitan mendapatkan akses pendidikan yang layak. Banyak
                    dari mereka harus berjalan berkilo-kilometer untuk sampai ke sekolah terdekat.</p>

                <div id="full-description" class="hidden space-y-3">
                    <p class="font-semibold text-dark">Tujuan Program:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Membangun gedung sekolah 3 lantai dengan 12 ruang kelas</li>
                        <li>Menyediakan fasilitas perpustakaan dan laboratorium</li>
                        <li>Melengkapi sarana olahraga dan tempat ibadah</li>
                        <li>Memberikan beasiswa bagi 200 anak yatim</li>
                    </ul>

                    <p class="font-semibold text-dark">Rencana Penggunaan Dana:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Pembangunan gedung: Rp 60.000.000</li>
                        <li>Peralatan & fasilitas: Rp 20.000.000</li>
                        <li>Operasional awal: Rp 10.000.000</li>
                    </ul>

                    <p>Target penerima manfaat: <span class="font-semibold text-dark">200 anak yatim</span> usia sekolah
                        dasar hingga menengah di wilayah Jawa Barat.</p>
                </div>

                <button id="read-more-btn" class="text-primary font-semibold text-sm flex items-center gap-1 mt-2">
                    Baca Selengkapnya
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
            </div>
        </section>

        <section id="update-section" class="bg-white px-4 py-4 mt-2">
            <h3 class="text-base font-bold text-dark mb-3">Update Program</h3>
            <div class="space-y-4">
                <div class="flex gap-3">
                    <div class="w-1 bg-primary rounded-full flex-shrink-0"></div>
                    <div class="flex-1 pb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-primary">3 hari yang lalu</span>
                        </div>
                        <h4 class="text-sm font-semibold text-dark mb-2">Progres Pondasi 80%</h4>
                        <p class="text-sm text-gray-600 mb-2">Alhamdulillah pekerjaan pondasi telah mencapai 80%. Tim
                            kontraktor bekerja dengan baik sesuai jadwal.</p>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="aspect-video overflow-hidden rounded-lg">
                                <img class="w-full h-full object-cover"
                                    src="https://storage.googleapis.com/uxpilot-auth.appspot.com/2d463f4e75-9b3b8905651f2d71084a.png"
                                    alt="Construction site foundation progress, workers building school, 16:9 aspect ratio" />
                            </div>
                            <div class="aspect-video overflow-hidden rounded-lg">
                                <img class="w-full h-full object-cover"
                                    src="https://storage.googleapis.com/uxpilot-auth.appspot.com/d28818110e-42f5b0f0a9c28c3631ba.png"
                                    alt="School building foundation concrete work, construction progress, 16:9 aspect ratio" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="w-1 bg-gray-200 rounded-full flex-shrink-0"></div>
                    <div class="flex-1 pb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-gray-500">7 hari yang lalu</span>
                        </div>
                        <h4 class="text-sm font-semibold text-dark mb-2">Peletakan Batu Pertama</h4>
                        <p class="text-sm text-gray-600 mb-2">Acara peletakan batu pertama dilaksanakan dengan khidmat.
                            Terima kasih kepada semua donatur yang telah berpartisipasi.</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="w-1 bg-gray-200 rounded-full flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-gray-500">14 hari yang lalu</span>
                        </div>
                        <h4 class="text-sm font-semibold text-dark mb-2">Program Diluncurkan</h4>
                        <p class="text-sm text-gray-600">Program pembangunan sekolah resmi diluncurkan. Mari bersama
                            membangun masa depan anak yatim.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="distribution-section" class="bg-white px-4 py-4 mt-2">
            <h3 class="text-base font-bold text-dark mb-3">Informasi Penyaluran</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Total Tersalurkan</span>
                    <span class="text-sm font-bold text-dark">Rp 45.000.000</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Sisa Dana</span>
                    <span class="text-sm font-bold text-dark">Rp 20.250.000</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-600">Status Penyaluran</span>
                    <span class="text-xs px-3 py-1 bg-blue-50 text-blue-700 font-semibold rounded-full">Dalam
                        Proses</span>
                </div>
                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-600">
                        <span class="font-semibold text-dark">Catatan:</span> Dana sedang digunakan untuk pembangunan
                        pondasi dan struktur utama gedung sekolah. Progres akan diupdate secara berkala.
                    </p>
                </div>
            </div>
        </section>

        <section id="related-section" class="bg-white px-4 py-4 mt-2">
            <h3 class="text-base font-bold text-dark mb-3">Program Terkait</h3>
            <div class="flex gap-3 overflow-x-auto hide-scrollbar pb-2">
                <div class="flex-shrink-0 w-64">
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="aspect-video overflow-hidden">
                            <img class="w-full h-full object-cover"
                                src="https://storage.googleapis.com/uxpilot-auth.appspot.com/a35a7405d6-56eef6ba5933f1c901c5.png"
                                alt="Quran teacher with students in pesantren, Islamic education, 16:9 aspect ratio" />
                        </div>
                        <div class="p-3">
                            <h4 class="text-sm font-semibold text-dark mb-2 line-clamp-2">Beasiswa Tahfidz Al-Quran
                            </h4>
                            <p class="text-xs font-bold text-dark mb-2">Rp 32.800.000</p>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-2">
                                <div class="bg-primary h-1.5 rounded-full" style="width: 55%"></div>
                            </div>
                            <span class="text-xs text-gray-500">55% terkumpul</span>
                        </div>
                    </div>
                </div>

                <div class="flex-shrink-0 w-64">
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="aspect-video overflow-hidden">
                            <img class="w-full h-full object-cover"
                                src="https://storage.googleapis.com/uxpilot-auth.appspot.com/3e63fa336e-0d19f785fbaac98deef9.png"
                                alt="Orphanage children happy playing together, Islamic orphanage, 16:9 aspect ratio" />
                        </div>
                        <div class="p-3">
                            <h4 class="text-sm font-semibold text-dark mb-2 line-clamp-2">Santunan Bulanan Anak Yatim
                            </h4>
                            <p class="text-xs font-bold text-dark mb-2">Rp 28.500.000</p>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-2">
                                <div class="bg-primary h-1.5 rounded-full" style="width: 48%"></div>
                            </div>
                            <span class="text-xs text-gray-500">48% terkumpul</span>
                        </div>
                    </div>
                </div>

                <div class="flex-shrink-0 w-64">
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="aspect-video overflow-hidden">
                            <img class="w-full h-full object-cover"
                                src="https://storage.googleapis.com/uxpilot-auth.appspot.com/015933f6f4-411c5bbc6636fc998da7.png"
                                alt="Beautiful mosque construction progress, Islamic architecture, 16:9 aspect ratio" />
                        </div>
                        <div class="p-3">
                            <h4 class="text-sm font-semibold text-dark mb-2 line-clamp-2">Pembangunan Masjid Al-Ikhlas
                            </h4>
                            <p class="text-xs font-bold text-dark mb-2">Rp 82.450.000</p>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-2">
                                <div class="bg-primary h-1.5 rounded-full" style="width: 85%"></div>
                            </div>
                            <span class="text-xs text-gray-500">85% terkumpul</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div id="cta-button" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-3 z-50">
        <button
            class="w-full py-4 bg-primary text-white rounded-xl text-base font-bold shadow-lg active:scale-95 transition-transform">
            Donasi Sekarang
        </button>
    </div>

    <script>
        window.addEventListener('load', function() {
            const shareBtn = document.querySelector('#header button:last-child');
            shareBtn.addEventListener('click', function() {
                if (navigator.share) {
                    navigator.share({
                        title: 'Bangun Sekolah untuk Anak Yatim',
                        text: 'Mari bersama membangun masa depan anak yatim',
                        url: window.location.href
                    });
                }
            });

            const readMoreBtn = document.getElementById('read-more-btn');
            const fullDescription = document.getElementById('full-description');

            readMoreBtn.addEventListener('click', function() {
                if (fullDescription.classList.contains('hidden')) {
                    fullDescription.classList.remove('hidden');
                    readMoreBtn.innerHTML = 'Sembunyikan <i class="fa-solid fa-chevron-up text-xs"></i>';
                } else {
                    fullDescription.classList.add('hidden');
                    readMoreBtn.innerHTML =
                        'Baca Selengkapnya <i class="fa-solid fa-chevron-down text-xs"></i>';
                }
            });
        });
    </script>
</body>

</html>

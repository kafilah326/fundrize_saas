<html lang="id">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabungan Qurban</title>
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

        .modal-backdrop {
            backdrop-filter: blur(4px);
        }
    </style>
    <style data-grapesjs-styles="true">
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Inter", sans-serif;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
            margin-left: 0px;
        }

        .modal-backdrop {
            backdrop-filter: blur(4px);
        }
    </style>
</head>

<body class="bg-light">
    <header id="header" class="bg-white shadow-sm sticky top-0 z-40">
        <div class="px-4 py-3 flex items-center gap-3"><button
                class="w-9 h-9 flex items-center justify-center bg-light rounded-full"><i
                    class="fa-solid fa-arrow-left text-gray-600 text-sm"></i></button>
            <h1 class="text-base font-bold text-dark">Tabungan Qurban</h1>
        </div>
    </header>
    <main id="main-content" class="pb-24">
        <section id="banner-section" class="bg-white">
            <div class="h-[200px] overflow-hidden"><img
                    src="https://storage.googleapis.com/uxpilot-auth.appspot.com/Fi1siCSktTSfwv8Em1pqs4D0Vek2%2Fcff693df-a22b-4f74-b519-bb1fa7cac475.png"
                    alt="Islamic qurban sacrifice savings program banner, sheep and cattle, mosque background, golden hour lighting, 16:9 aspect ratio"
                    class="w-full h-full object-cover"></div>
        </section>
        <section id="title-section" class="bg-white px-4 py-5">
            <h2 class="text-xl font-bold text-dark mb-2">Tabungan Qurban</h2>
            <p class="text-sm text-gray-600">Nabung sedikit demi sedikit untuk
                ibadah qurban</p>
        </section>
        <section id="benefits-section" class="bg-white px-4 py-5 border-t border-gray-100">
            <h3 class="text-sm font-bold text-dark mb-4">Keunggulan Program</h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check text-green-600 text-xs"></i>
                    </div>
                    <span class="text-sm text-dark">Nabung kapan saja</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check text-green-600 text-xs"></i>
                    </div>
                    <span class="text-sm text-dark">Sesuai syariah</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check text-green-600 text-xs"></i>
                    </div>
                    <span class="text-sm text-dark">Otomatis jadi qurban saat
                        cukup</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check text-green-600 text-xs"></i>
                    </div>
                    <span class="text-sm text-dark">Ada pengingat rutin</span>
                </div>
            </div>
        </section>
        <section id="description-section" class="bg-white px-4 py-5 border-t border-gray-100">
            <h3 class="text-sm font-bold text-dark mb-3">Tentang Program</h3>
            <p class="text-sm text-gray-600 leading-relaxed mb-3">
                Tabungan Qurban memudahkan Anda untuk menyiapkan dana ibadah qurban
                dengan cara menabung secara bertahap.
                Anda bisa menabung sesuai kemampuan dan jadwal sendiri.
            </p>
            <p class="text-sm text-gray-600 leading-relaxed">
                Setelah dana mencukupi untuk membeli hewan qurban, tabungan akan
                otomatis dikonversi menjadi program qurban
                pada waktu yang tepat sesuai kalender hijriah.
            </p>
        </section>
        <section id="akad-section" class="bg-white px-4 py-5 border-t border-gray-100">
            <div class="flex items-center gap-2 mb-2">
                <h3 class="text-sm font-bold text-dark">Informasi Akad</h3><button
                    class="w-5 h-5 bg-gray-100 rounded-full flex items-center justify-center"><i
                        class="fa-solid fa-info text-gray-500 text-xs"></i></button>
            </div>
            <p class="text-sm text-gray-600">Menggunakan akad Wadi'ah Amanah dan
                Wakalah sesuai syariah</p>
        </section>
    </main>
    <div id="sticky-cta" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 z-50">
        <button id="register-btn" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-semibold">
            Daftar Sekarang
        </button>
    </div>
    <div id="terms-modal" class="fixed inset-0 bg-black/50 modal-backdrop z-50 hidden">
        <div class="flex items-end justify-center min-h-screen p-4">
            <div class="bg-white rounded-t-2xl w-full max-w-md max-h-[80vh] overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-dark">Syarat &amp; Ketentuan
                        </h3><button id="close-modal"
                            class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full"><i
                                class="fa-solid fa-xmark text-gray-600 text-sm"></i></button>
                    </div>
                </div>
                <div class="px-4 py-4 overflow-y-auto max-h-[50vh]">
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-semibold text-sm text-dark mb-2">Akad Tabungan
                            </h4>
                            <p class="text-xs text-gray-600">Program ini menggunakan akad
                                Wadi'ah Amanah dan Wakalah yang sesuai dengan prinsip syariah
                                Islam.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm text-dark mb-2">Aturan
                                Penarikan Dana</h4>
                            <p class="text-xs text-gray-600">Dana tabungan dapat ditarik
                                kapan saja sebelum konversi ke program qurban. Setelah
                                konversi, dana tidak dapat ditarik.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm text-dark mb-2">Ketentuan Wafat
                            </h4>
                            <p class="text-xs text-gray-600">Jika penabung meninggal dunia,
                                tabungan akan diserahkan kepada ahli waris sesuai ketentuan
                                syariah.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm text-dark mb-2">Pengalihan Dana
                            </h4>
                            <p class="text-xs text-gray-600">Dana dapat dialihkan ke program
                                lain dengan persetujuan yayasan dan sesuai ketentuan yang
                                berlaku.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm text-dark mb-2">Perubahan Harga
                            </h4>
                            <p class="text-xs text-gray-600">Harga hewan qurban dapat
                                berubah sesuai kondisi pasar. Penabung akan diberitahu jika
                                ada perubahan target dana.</p>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-4 border-t border-gray-200">
                    <div class="flex items-start gap-3 mb-4"><input type="checkbox" id="agree-checkbox"
                            class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary mt-0.5"><label
                            for="agree-checkbox" class="text-xs text-gray-600 leading-relaxed">
                            Saya setuju dengan Syarat &amp; Ketentuan yang berlaku untuk
                            program Tabungan Qurban
                        </label></div><button id="continue-btn" disabled=""
                        class="w-full bg-gray-300 text-gray-500 py-3 rounded-xl text-sm font-semibold">
                        Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('load', function() {
            const registerBtn = document.getElementById('register-btn');
            const termsModal = document.getElementById('terms-modal');
            const closeModal = document.getElementById('close-modal');
            const agreeCheckbox = document.getElementById('agree-checkbox');
            const continueBtn = document.getElementById('continue-btn');

            registerBtn.addEventListener('click', function() {
                termsModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });

            closeModal.addEventListener('click', function() {
                termsModal.classList.add('hidden');
                document.body.style.overflow = '';
            });

            termsModal.addEventListener('click', function(e) {
                if (e.target === termsModal) {
                    termsModal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });

            agreeCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    continueBtn.disabled = false;
                    continueBtn.classList.remove('bg-gray-300',
                        'text-gray-500');
                    continueBtn.classList.add('bg-primary', 'text-white');
                } else {
                    continueBtn.disabled = true;
                    continueBtn.classList.remove('bg-primary', 'text-white');
                    continueBtn.classList.add('bg-gray-300', 'text-gray-500');
                }
            });

            continueBtn.addEventListener('click', function() {
                if (!this.disabled) {
                    console.log('Navigating to target selection page');
                }
            });
        });
    </script>
</body>

</html>

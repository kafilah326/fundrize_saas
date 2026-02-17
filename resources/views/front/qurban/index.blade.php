<html lang="id">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qurban</title>
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

        #i29ji {
            height: 134px;
        }
    </style>
</head>

<body class="bg-light">
    <header id="header" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="px-4 py-3 flex items-center gap-3"><button class="w-10 h-10 flex items-center justify-center"><i
                    class="fa-solid fa-arrow-left text-dark text-lg"></i></button>
            <h1 class="text-lg font-bold text-dark flex-1">Qurban</h1>
        </div>
    </header>
    <main id="main-content" class="pb-24">
        <section id="qurban-banner" class="bg-white px-4 py-4">
            <div class="relative overflow-hidden rounded-2xl">
                <div class="relative h-[180px] overflow-hidden rounded-2xl">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent">
                    </div><img
                        src="https://storage.googleapis.com/uxpilot-auth.appspot.com/Fi1siCSktTSfwv8Em1pqs4D0Vek2%2F34ce4315-b3a6-4bb3-8c3a-601e7d610f4a.png"
                        alt="Islamic qurban sacrifice banner with mosque silhouette and crescent moon, golden hour lighting, traditional Islamic design"
                        class="w-full h-full object-cover">
                    <div class="absolute bottom-0 left-0 right-0 p-4" id="i29ji">
                        <h2 class="text-white font-bold text-base mb-2">Tabungan Qurban
                        </h2>
                        <p class="text-white/90 text-xs mb-3">Cicil qurban mulai dari
                            50rb/bulan</p><button
                            class="bg-primary text-white px-5 py-2 rounded-full text-xs font-semibold">
                            Info Tabungan
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <section id="qurban-selection" class="px-4 py-5">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-dark mb-3">Pilih Hewan Qurban</h3>
                <div class="flex gap-2 overflow-x-auto hide-scrollbar pb-2"><button data-filter="semua"
                        class="filter-animal-btn active px-4 py-2 bg-primary text-white rounded-full text-xs font-semibold whitespace-nowrap">
                        Semua
                    </button><button data-filter="kambing"
                        class="filter-animal-btn px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold whitespace-nowrap">
                        Kambing
                    </button><button data-filter="sapi"
                        class="filter-animal-btn px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold whitespace-nowrap">
                        Sapi
                    </button><button data-filter="domba"
                        class="filter-animal-btn px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold whitespace-nowrap">
                        Domba
                    </button><button data-filter="kerbau"
                        class="filter-animal-btn px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold whitespace-nowrap">
                        Kerbau
                    </button></div>
            </div>
            <div class="space-y-3">
                <div data-price="2500000" data-name="Kambing Jantan Premium" data-category="kambing"
                    class="qurban-card bg-white rounded-xl border border-gray-100 p-4 cursor-pointer transition-all duration-200">
                    <div class="flex gap-3">
                        <div class="w-20 h-20 flex-shrink-0 overflow-hidden rounded-lg">
                            <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/8e8cbc0b24-a5a4c50470e0fa007b4b.png"
                                alt="healthy male goat for qurban sacrifice, Indonesian farm setting, brown and white colored, standing pose"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm text-dark mb-1">Kambing Jantan
                                Premium</h4>
                            <div class="flex items-center gap-3 text-xs text-gray-600 mb-2">
                                <span>35-40 kg</span><span>Stok: 15 ekor</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-primary">Rp 2.500.000</p>
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full select-indicator">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-price="2200000" data-name="Kambing Betina Premium" data-category="kambing"
                    class="qurban-card bg-white rounded-xl border border-gray-100 p-4 cursor-pointer transition-all duration-200">
                    <div class="flex gap-3">
                        <div class="w-20 h-20 flex-shrink-0 overflow-hidden rounded-lg">
                            <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/251a2ff849-6c53b04a80fcebd22e3c.png"
                                alt="healthy female goat for qurban sacrifice, Indonesian farm setting, white colored, standing pose"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm text-dark mb-1">Kambing Betina
                                Premium</h4>
                            <div class="flex items-center gap-3 text-xs text-gray-600 mb-2">
                                <span>30-35 kg</span><span>Stok: 12 ekor</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-primary">Rp 2.200.000</p>
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full select-indicator">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-price="18000000" data-name="Sapi Jantan Premium" data-category="sapi"
                    class="qurban-card bg-white rounded-xl border border-gray-100 p-4 cursor-pointer transition-all duration-200">
                    <div class="flex gap-3">
                        <div class="w-20 h-20 flex-shrink-0 overflow-hidden rounded-lg">
                            <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/868313e8a2-0f1c84baa865fb4f51d3.png"
                                alt="healthy adult bull cow for qurban sacrifice, Indonesian farm setting, brown colored, standing pose"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm text-dark mb-1">Sapi Jantan
                                Premium</h4>
                            <div class="flex items-center gap-3 text-xs text-gray-600 mb-2">
                                <span>400-450 kg</span><span>Stok: 8 ekor</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-primary">Rp 18.000.000</p>
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full select-indicator">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-price="16500000" data-name="Sapi Betina Premium" data-category="sapi"
                    class="qurban-card bg-white rounded-xl border border-gray-100 p-4 cursor-pointer transition-all duration-200">
                    <div class="flex gap-3">
                        <div class="w-20 h-20 flex-shrink-0 overflow-hidden rounded-lg">
                            <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/7a37281599-15f49b6b4475a57334d2.png"
                                alt="healthy adult female cow for qurban sacrifice, Indonesian farm setting, brown and white colored, standing pose"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm text-dark mb-1">Sapi Betina
                                Premium</h4>
                            <div class="flex items-center gap-3 text-xs text-gray-600 mb-2">
                                <span>350-400 kg</span><span>Stok: 6 ekor</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-primary">Rp 16.500.000</p>
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full select-indicator">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-price="3200000" data-name="Domba Jantan Premium" data-category="domba"
                    class="qurban-card bg-white rounded-xl border border-gray-100 p-4 cursor-pointer transition-all duration-200">
                    <div class="flex gap-3">
                        <div class="w-20 h-20 flex-shrink-0 overflow-hidden rounded-lg">
                            <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/a46ba84a78-175d200c6785b351bb56.png"
                                alt="healthy adult male sheep for qurban sacrifice, Indonesian farm setting, white woolly, standing pose"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm text-dark mb-1">Domba Jantan
                                Premium</h4>
                            <div class="flex items-center gap-3 text-xs text-gray-600 mb-2">
                                <span>45-50 kg</span><span>Stok: 10 ekor</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-primary">Rp 3.200.000</p>
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full select-indicator">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-price="2800000" data-name="Domba Betina Premium" data-category="domba"
                    class="qurban-card bg-white rounded-xl border border-gray-100 p-4 cursor-pointer transition-all duration-200">
                    <div class="flex gap-3">
                        <div class="w-20 h-20 flex-shrink-0 overflow-hidden rounded-lg">
                            <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/3db19c03ea-fca78eda432326f9f453.png"
                                alt="healthy adult female sheep for qurban sacrifice, Indonesian farm setting, white woolly, standing pose"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm text-dark mb-1">Domba Betina
                                Premium</h4>
                            <div class="flex items-center gap-3 text-xs text-gray-600 mb-2">
                                <span>40-45 kg</span><span>Stok: 7 ekor</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-primary">Rp 2.800.000</p>
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full select-indicator">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-price="22000000" data-name="Kerbau Jantan Premium" data-category="kerbau"
                    class="qurban-card bg-white rounded-xl border border-gray-100 p-4 cursor-pointer transition-all duration-200">
                    <div class="flex gap-3">
                        <div class="w-20 h-20 flex-shrink-0 overflow-hidden rounded-lg">
                            <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/aab24d66f6-f7d5d23427f5ef6d1701.png"
                                alt="healthy adult male buffalo for qurban sacrifice, Indonesian farm setting, black colored, standing pose"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm text-dark mb-1">Kerbau Jantan
                                Premium</h4>
                            <div class="flex items-center gap-3 text-xs text-gray-600 mb-2">
                                <span>500-550 kg</span><span>Stok: 3 ekor</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-primary">Rp 22.000.000</p>
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full select-indicator">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <div id="sticky-button"
        class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-3 z-50 opacity-50 pointer-events-none transition-all duration-300">
        <button id="qurban-btn" disabled=""
            class="w-full bg-gray-300 text-gray-500 py-3 rounded-xl text-sm font-semibold">
            Tunaikan Qurban
        </button>
    </div>
    <script>
        window.addEventListener('load', function() {
            const qurbanCards = document.querySelectorAll('.qurban-card');
            const stickyButton = document.getElementById('sticky-button');
            const qurbanBtn = document.getElementById('qurban-btn');
            let selectedCard = null;

            qurbanCards.forEach(card => {
                card.addEventListener('click', function() {
                    if (selectedCard) {
                        selectedCard.classList.remove('border-primary',
                            'bg-orange-50');
                        selectedCard.querySelector('.select-indicator')
                            .classList.remove('border-primary', 'bg-primary');
                        selectedCard.querySelector('.select-indicator')
                            .innerHTML = '';
                    }

                    selectedCard = this;
                    this.classList.add('border-primary', 'bg-orange-50');
                    const indicator = this.querySelector(
                        '.select-indicator');
                    indicator.classList.add('border-primary', 'bg-primary');
                    indicator.innerHTML =
                        '<i class="fa-solid fa-check text-white text-xs"></i>';

                    stickyButton.classList.remove('opacity-50',
                        'pointer-events-none');
                    qurbanBtn.classList.remove('bg-gray-300',
                        'text-gray-500');
                    qurbanBtn.classList.add('bg-primary', 'text-white');
                    qurbanBtn.disabled = false;

                    const animalName = this.dataset.name;
                    const price = parseInt(this.dataset.price)
                        .toLocaleString('id-ID');
                    qurbanBtn.textContent = `Tunaikan Qurban - Rp ${price}`;
                });
            });
        });
    </script>
</body>

</html>

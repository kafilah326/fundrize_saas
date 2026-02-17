<html lang="id">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Qurban</title>
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

        ::-webkit-scrollbar {
            display: none;
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

        ::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-light">
    <header id="header" class="bg-white shadow-sm sticky top-0 z-40">
        <div class="px-4 py-3 flex items-center gap-3"><button class="w-9 h-9 flex items-center justify-center"><i
                    class="fa-solid fa-arrow-left text-dark text-base"></i></button>
            <h1 class="text-base font-bold text-dark">Formulir Qurban</h1>
        </div>
    </header>
    <main id="main-content" class="pb-32">
        <section id="summary-section" class="bg-white px-4 py-5 mb-2">
            <h2 class="text-sm font-bold text-dark mb-3">Ringkasan Qurban</h2>
            <div class="flex gap-3 p-3 bg-orange-50 rounded-xl border border-orange-100">
                <div class="w-16 h-16 flex-shrink-0 overflow-hidden rounded-lg"><img
                        src="https://storage.googleapis.com/uxpilot-auth.appspot.com/8e8cbc0b24-a5a4c50470e0fa007b4b.png"
                        alt="Kambing Jantan Premium" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-sm text-dark mb-1">Kambing Jantan
                        Premium</h3>
                    <p class="text-xs text-gray-600 mb-2">35-40 kg</p>
                    <p class="text-sm font-bold text-primary">Rp 2.500.000</p>
                </div>
            </div>
        </section>
        <section id="form-section" class="bg-white px-4 py-5">
            <h2 class="text-sm font-bold text-dark mb-3">Data Muqorib</h2>
            <div class="space-y-3.5">
                <div><label class="text-xs font-medium text-gray-700 mb-1.5 block">Nama
                        Lengkap <span class="text-red-500">*</span></label><input type="text" id="nama"
                        placeholder="Masukkan nama lengkap"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>
                <div><label class="text-xs font-medium text-gray-700 mb-1.5 block">Nomor
                        WhatsApp <span class="text-red-500">*</span></label><input type="tel" id="whatsapp"
                        placeholder="08xxxxxxxxxx"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>
                <div><label class="text-xs font-medium text-gray-700 mb-1.5 block">Atas Nama
                        Qurban <span class="text-gray-400 text-xs">(Opsional)</span></label><input type="text"
                        id="atas-nama" placeholder="Untuk penyebutan niat/sertifikat"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <p class="text-xs text-gray-500 mt-1.5">Kosongkan jika sama dengan
                        nama di atas</p>
                </div>
                <div><label class="text-xs font-medium text-gray-700 mb-1.5 block">Email <span
                            class="text-gray-400 text-xs">(Opsional)</span></label><input type="email" id="email"
                        placeholder="email@contoh.com"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>
                <div><label class="text-xs font-medium text-gray-700 mb-1.5 block">Alamat
                        Lengkap <span class="text-red-500">*</span></label>
                    <textarea id="alamat" rows="3" placeholder="Masukkan alamat lengkap untuk pengiriman daging"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="text-xs font-medium text-gray-700 mb-1.5 block">Kota/Kabupaten
                            <span class="text-red-500">*</span></label><input type="text" id="kota"
                            placeholder="Contoh: Jakarta"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </div>
                    <div><label class="text-xs font-medium text-gray-700 mb-1.5 block">Kode Pos
                            <span class="text-red-500">*</span></label><input type="text" id="kodepos"
                            placeholder="12345" maxlength="5"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </div>
                </div>
                <div><label class="text-xs font-medium text-gray-700 mb-1.5 block">Metode
                        Penyembelihan <span class="text-red-500">*</span></label>
                    <div class="space-y-2"><label
                            class="flex items-center gap-3 p-3 border-2 border-primary bg-primary/5 rounded-lg cursor-pointer"><input
                                type="radio" name="penyembelihan" value="wakalah" checked=""
                                class="w-4 h-4 text-primary">
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">Wakalah
                                    (Diwakilkan)</div>
                                <div class="text-xs text-gray-600">Disembelih oleh panitia
                                </div>
                            </div>
                        </label><label
                            class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer"><input
                                type="radio" name="penyembelihan" value="hadir" class="w-4 h-4 text-primary">
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">Hadir Sendiri
                                </div>
                                <div class="text-xs text-gray-600">Lokasi: Ponorogo, Jawa
                                    Timur</div>
                            </div>
                        </label></div>
                </div>
                <div><label class="text-xs font-medium text-gray-700 mb-1.5 block">Pengiriman
                        Daging <span class="text-red-500">*</span></label>
                    <div class="space-y-2"><label
                            class="flex items-center gap-3 p-3 border-2 border-primary bg-primary/5 rounded-lg cursor-pointer"><input
                                type="radio" name="pengiriman" value="dikirim" checked=""
                                class="w-4 h-4 text-primary">
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">Dikirim ke Alamat
                                </div>
                                <div class="text-xs text-gray-600">Estimasi H+3 Idul Adha
                                </div>
                            </div>
                        </label><label
                            class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer"><input
                                type="radio" name="pengiriman" value="ambil" class="w-4 h-4 text-primary">
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">Ambil Sendiri
                                </div>
                                <div class="text-xs text-gray-600">Lokasi: Ponorogo, Jawa
                                    Timur</div>
                            </div>
                        </label><label
                            class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer"><input
                                type="radio" name="pengiriman" value="wakaf" class="w-4 h-4 text-primary">
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">Tidak diambil<br>
                                </div>
                                <div class="text-xs text-gray-600">Disalurkan ke yang
                                    membutuhkan</div>
                            </div>
                        </label></div>
                </div><label class="flex items-center gap-2 cursor-pointer"></label>
            </div>
        </section>
    </main>
    <div id="sticky-button" class="fixed bottom-0 left-0 right-0 bg-white border-t-2 border-gray-200 p-4 z-50">
        <div class="flex items-center justify-between mb-3"><span class="text-xs text-gray-600">Total
                Pembayaran</span><span class="text-base font-bold text-primary">Rp 2.500.000</span></div>
        <button id="payment-btn" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-semibold">
            Lanjut Pembayaran
        </button>
    </div>
    <script>
        window.addEventListener('load', function() {
            const penyembelihanRadios = document.querySelectorAll(
                'input[name="penyembelihan"]');
            const pengirimanRadios = document.querySelectorAll(
                'input[name="pengiriman"]');
            const anonimCheck = document.getElementById('anonim');
            const namaInput = document.getElementById('nama');
            const atasNamaInput = document.getElementById('atas-nama');
            const paymentBtn = document.getElementById('payment-btn');

            penyembelihanRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    penyembelihanRadios.forEach(r => {
                        r.closest('label').classList.remove(
                            'border-primary', 'bg-primary/5');
                        r.closest('label').classList.add(
                            'border-gray-200');
                    });
                    this.closest('label').classList.remove(
                        'border-gray-200');
                    this.closest('label').classList.add('border-primary',
                        'bg-primary/5');
                });
            });

            pengirimanRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    pengirimanRadios.forEach(r => {
                        r.closest('label').classList.remove(
                            'border-primary', 'bg-primary/5');
                        r.closest('label').classList.add(
                            'border-gray-200');
                    });
                    this.closest('label').classList.remove(
                        'border-gray-200');
                    this.closest('label').classList.add('border-primary',
                        'bg-primary/5');
                });
            });

            anonimCheck.addEventListener('change', function() {
                if (this.checked) {
                    namaInput.value = 'Hamba Allah';
                    atasNamaInput.value = 'Hamba Allah';
                    namaInput.disabled = true;
                    atasNamaInput.disabled = true;
                } else {
                    namaInput.value = '';
                    atasNamaInput.value = '';
                    namaInput.disabled = false;
                    atasNamaInput.disabled = false;
                }
            });

            paymentBtn.addEventListener('click', function() {
                const nama = document.getElementById('nama').value.trim();
                const whatsapp = document.getElementById('whatsapp').value
                    .trim();
                const alamat = document.getElementById('alamat').value.trim();
                const kota = document.getElementById('kota').value.trim();
                const kodepos = document.getElementById('kodepos').value
                    .trim();

                if (!nama || !whatsapp || !alamat || !kota || !kodepos) {
                    return;
                }
            });
        });
    </script>
</body>

</html>

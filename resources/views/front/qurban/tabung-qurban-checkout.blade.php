<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tabungan Qurban</title>
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
    <header id="header" class="bg-white shadow-sm sticky top-0 z-40">
        <div class="px-4 py-3 flex items-center gap-3">
            <button class="w-9 h-9 flex items-center justify-center bg-light rounded-full">
                <i class="fa-solid fa-arrow-left text-gray-600 text-sm"></i>
            </button>
            <h1 class="text-base font-bold text-dark">Buat Tabungan Qurban</h1>
        </div>
    </header>

    <main id="main-content" class="pb-32">
        <section id="target-section" class="bg-white px-4 py-5 mb-2">
            <h2 class="text-sm font-bold text-dark mb-3">Pilih Target Tabungan</h2>
            <div class="space-y-2">
                <label
                    class="flex items-center gap-3 p-3 border-2 border-primary bg-primary/5 rounded-xl cursor-pointer">
                    <input type="radio" name="target" value="kambing" checked class="w-4 h-4 text-primary">
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-dark">Kambing</div>
                        <div class="text-xs text-gray-600">Target Rp 2.500.000</div>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer">
                    <input type="radio" name="target" value="domba" class="w-4 h-4 text-primary">
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-dark">Domba</div>
                        <div class="text-xs text-gray-600">Target Rp 3.000.000</div>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer">
                    <input type="radio" name="target" value="sapi-1-7" class="w-4 h-4 text-primary">
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-dark">Sapi 1/7</div>
                        <div class="text-xs text-gray-600">Target Rp 3.500.000 • Patungan 1/7</div>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer">
                    <input type="radio" name="target" value="sapi-utuh" class="w-4 h-4 text-primary">
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-dark">Sapi Utuh</div>
                        <div class="text-xs text-gray-600">Target Rp 24.000.000</div>
                    </div>
                </label>
            </div>
        </section>

        <section id="donor-section" class="bg-white px-4 py-5 mb-2">
            <h2 class="text-sm font-bold text-dark mb-3">Data Muqorib</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1.5 block">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="nama" placeholder="Masukkan nama lengkap"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1.5 block">Nomor WhatsApp <span
                            class="text-red-500">*</span></label>
                    <input type="tel" id="whatsapp" placeholder="08xxxxxxxxxx"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1.5 block">Atas Nama Qurban <span
                            class="text-gray-400 text-xs">(Opsional)</span></label>
                    <input type="text" id="atas-nama" placeholder="Untuk penyebutan niat/sertifikat"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1.5 block">Email <span
                            class="text-gray-400 text-xs">(Opsional)</span></label>
                    <input type="email" id="email" placeholder="email@contoh.com"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="anonim" class="w-4 h-4 text-primary border-gray-300 rounded">
                    <span class="text-xs text-gray-700">Anonim (Hamba Allah)</span>
                </label>
            </div>
        </section>

        <section id="deposit-section" class="bg-white px-4 py-5 mb-2">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-bold text-dark">Setoran Pertama</h2>
                <span class="text-xs text-gray-500">Disarankan</span>
            </div>

            <div class="grid grid-cols-2 gap-2 mb-3">
                <button class="nominal-btn bg-primary text-white py-2.5 px-3 rounded-lg text-sm font-semibold"
                    data-value="50000">Rp 50.000</button>
                <button class="nominal-btn bg-gray-100 text-gray-700 py-2.5 px-3 rounded-lg text-sm font-semibold"
                    data-value="100000">Rp 100.000</button>
                <button class="nominal-btn bg-gray-100 text-gray-700 py-2.5 px-3 rounded-lg text-sm font-semibold"
                    data-value="250000">Rp 250.000</button>
                <button class="nominal-btn bg-gray-100 text-gray-700 py-2.5 px-3 rounded-lg text-sm font-semibold"
                    data-value="500000">Rp 500.000</button>
            </div>

            <div>
                <label class="text-xs font-medium text-gray-700 mb-1.5 block">Nominal Lainnya</label>
                <div class="flex items-center gap-2 border border-gray-300 rounded-lg px-3">
                    <span class="text-sm text-gray-600">Rp</span>
                    <input type="text" id="custom-nominal" placeholder="0"
                        class="flex-1 py-2.5 text-sm focus:outline-none">
                </div>
            </div>

            <button id="skip-deposit" class="text-xs text-primary mt-3 underline">Lewati setoran pertama</button>
        </section>

        <section id="reminder-section" class="bg-white px-4 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-dark mb-1">Pengingat</h2>
                    <p class="text-xs text-gray-600">Ingatkan saya untuk menabung</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="reminder-toggle" class="sr-only peer">
                    <div
                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                    </div>
                </label>
            </div>

            <div id="reminder-frequency" class="mt-3 hidden">
                <select
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <option value="bulanan">Bulanan</option>
                    <option value="mingguan">Mingguan</option>
                </select>
            </div>
        </section>
    </main>

    <div id="sticky-summary" class="fixed bottom-0 left-0 right-0 bg-white border-t-2 border-gray-200 p-4 z-50">
        <div class="space-y-2 mb-3">
            <div class="flex justify-between text-xs">
                <span class="text-gray-600">Target</span>
                <span class="font-semibold text-dark" id="summary-target">Kambing – Rp 2.500.000</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-600">Setoran pertama</span>
                <span class="font-semibold text-dark" id="summary-deposit">Rp 50.000</span>
            </div>
            <div class="h-px bg-gray-200"></div>
            <div class="flex justify-between text-sm">
                <span class="font-bold text-dark">Total Bayar</span>
                <span class="font-bold text-primary" id="summary-total">Rp 50.000</span>
            </div>
        </div>

        <button id="submit-btn" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-semibold">
            Buat Tabungan & Setor
        </button>
    </div>

    <script>
        window.addEventListener('load', function() {
            const targetRadios = document.querySelectorAll('input[name="target"]');
            const nominalBtns = document.querySelectorAll('.nominal-btn');
            const customNominal = document.getElementById('custom-nominal');
            const skipDeposit = document.getElementById('skip-deposit');
            const reminderToggle = document.getElementById('reminder-toggle');
            const reminderFrequency = document.getElementById('reminder-frequency');
            const submitBtn = document.getElementById('submit-btn');
            const namaInput = document.getElementById('nama');
            const whatsappInput = document.getElementById('whatsapp');
            const anonimCheck = document.getElementById('anonim');

            let selectedTarget = {
                name: 'Kambing',
                value: 2500000
            };
            let selectedDeposit = 50000;
            let skipDepositMode = false;

            targetRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    targetRadios.forEach(r => {
                        r.closest('label').classList.remove('border-primary',
                            'bg-primary/5');
                        r.closest('label').classList.add('border-gray-200');
                    });

                    this.closest('label').classList.remove('border-gray-200');
                    this.closest('label').classList.add('border-primary', 'bg-primary/5');

                    const targetText = this.closest('label').querySelector('.text-sm').textContent;
                    const targetValue = parseInt(this.closest('label').querySelector('.text-xs')
                        .textContent.replace(/\D/g, ''));

                    selectedTarget = {
                        name: targetText,
                        value: targetValue
                    };
                    updateSummary();
                });
            });

            nominalBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    nominalBtns.forEach(b => {
                        b.classList.remove('bg-primary', 'text-white');
                        b.classList.add('bg-gray-100', 'text-gray-700');
                    });

                    this.classList.remove('bg-gray-100', 'text-gray-700');
                    this.classList.add('bg-primary', 'text-white');

                    selectedDeposit = parseInt(this.dataset.value);
                    customNominal.value = '';
                    skipDepositMode = false;
                    updateSummary();
                });
            });

            customNominal.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value) {
                    nominalBtns.forEach(b => {
                        b.classList.remove('bg-primary', 'text-white');
                        b.classList.add('bg-gray-100', 'text-gray-700');
                    });
                    selectedDeposit = parseInt(value);
                    skipDepositMode = false;
                    updateSummary();
                }
            });

            skipDeposit.addEventListener('click', function() {
                selectedDeposit = 0;
                skipDepositMode = true;
                customNominal.value = '';
                nominalBtns.forEach(b => {
                    b.classList.remove('bg-primary', 'text-white');
                    b.classList.add('bg-gray-100', 'text-gray-700');
                });
                updateSummary();
            });

            reminderToggle.addEventListener('change', function() {
                if (this.checked) {
                    reminderFrequency.classList.remove('hidden');
                } else {
                    reminderFrequency.classList.add('hidden');
                }
            });

            function updateSummary() {
                document.getElementById('summary-target').textContent = selectedTarget.name + ' – Rp ' +
                    selectedTarget.value.toLocaleString('id-ID');
                document.getElementById('summary-deposit').textContent = selectedDeposit === 0 ? '-' : 'Rp ' +
                    selectedDeposit.toLocaleString('id-ID');
                document.getElementById('summary-total').textContent = 'Rp ' + selectedDeposit.toLocaleString(
                    'id-ID');

                if (skipDepositMode || selectedDeposit === 0) {
                    submitBtn.textContent = 'Buat Tabungan';
                } else {
                    submitBtn.textContent = 'Buat Tabungan & Setor';
                }
            }

            submitBtn.addEventListener('click', function() {
                if (!namaInput.value.trim() || !whatsappInput.value.trim()) {
                    return;
                }
            });

            anonimCheck.addEventListener('change', function() {
                if (this.checked) {
                    namaInput.value = 'Hamba Allah';
                    namaInput.disabled = true;
                } else {
                    namaInput.value = '';
                    namaInput.disabled = false;
                }
            });
        });
    </script>
</body>

</html>

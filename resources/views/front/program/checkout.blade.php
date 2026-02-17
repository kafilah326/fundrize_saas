<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Donasi</title>
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
            <h1 class="text-base font-bold text-dark flex-1">Form Donasi</h1>
        </div>
    </header>

    <main id="main-content" class="pb-32">
        <section id="program-summary" class="bg-white px-4 py-4">
            <div class="flex flex-wrap gap-2 mb-3">
                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">Sedekah</span>
                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">Pendidikan</span>
            </div>
            <h2 class="text-lg font-bold text-dark">Bangun Sekolah untuk Anak Yatim</h2>
        </section>

        <section id="amount-section" class="bg-white px-4 py-6 mt-2">
            <h3 class="text-base font-bold text-dark mb-4">Nominal Donasi</h3>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <button
                    class="amount-btn px-4 py-3 border-2 border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:border-primary hover:text-primary transition-colors"
                    data-amount="10000">
                    Rp 10.000
                </button>
                <button
                    class="amount-btn px-4 py-3 border-2 border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:border-primary hover:text-primary transition-colors"
                    data-amount="25000">
                    Rp 25.000
                </button>
                <button
                    class="amount-btn px-4 py-3 border-2 border-primary bg-primary text-white rounded-lg text-sm font-semibold transition-colors"
                    data-amount="50000">
                    Rp 50.000
                </button>
                <button
                    class="amount-btn px-4 py-3 border-2 border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:border-primary hover:text-primary transition-colors"
                    data-amount="100000">
                    Rp 100.000
                </button>
                <button
                    class="amount-btn px-4 py-3 border-2 border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:border-primary hover:text-primary transition-colors"
                    data-amount="250000">
                    Rp 250.000
                </button>
                <button
                    class="amount-btn px-4 py-3 border-2 border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:border-primary hover:text-primary transition-colors"
                    data-amount="custom">
                    Lainnya
                </button>
            </div>

            <div id="custom-amount" class="hidden">
                <label class="block text-sm font-semibold text-dark mb-2">Nominal Bebas</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                    <input type="text" id="amount-input"
                        class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary"
                        placeholder="0" />
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimum donasi Rp 10.000</p>
                <p id="amount-error" class="text-xs text-red-600 mt-1 hidden">Nominal minimal Rp 10.000</p>
            </div>
        </section>

        <section id="donor-data" class="bg-white px-4 py-6 mt-2">
            <h3 class="text-base font-bold text-dark mb-4">Data Donatur</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-dark mb-2">Nama Lengkap *</label>
                    <input type="text" id="donor-name"
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary"
                        placeholder="Masukkan nama lengkap" />
                    <p id="name-error" class="text-xs text-red-600 mt-1 hidden">Nama wajib diisi</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-dark mb-2">Nomor WhatsApp *</label>
                    <input type="tel" id="donor-phone"
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary"
                        placeholder="08xxx" />
                    <p id="phone-error" class="text-xs text-red-600 mt-1 hidden">Nomor WhatsApp wajib diisi</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-dark mb-2">Email (Opsional)</label>
                    <input type="email" id="donor-email"
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary"
                        placeholder="email@example.com" />
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="anonymous"
                        class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" />
                    <label for="anonymous" class="text-sm text-gray-700">Donasi sebagai Hamba Allah (Anonim)</label>
                </div>
            </div>
        </section>

        <section id="payment-method" class="bg-white px-4 py-6 mt-2">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-base font-bold text-dark">Metode Pembayaran</h3>
                <button id="payment-toggle" class="text-primary text-sm font-semibold flex items-center gap-1">
                    Pilih Metode
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
            </div>

            <div id="payment-options" class="hidden space-y-3">
                <div class="payment-option border border-gray-200 rounded-lg p-3 cursor-pointer hover:border-primary transition-colors"
                    data-method="qris">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                                <i class="fa-solid fa-qrcode text-gray-600"></i>
                            </div>
                            <span class="text-sm font-semibold text-dark">QRIS</span>
                        </div>
                        <i class="fa-solid fa-circle-dot text-gray-300 method-radio"></i>
                    </div>
                </div>

                <div class="payment-option border border-gray-200 rounded-lg p-3 cursor-pointer hover:border-primary transition-colors"
                    data-method="va">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                                <i class="fa-solid fa-building-columns text-gray-600"></i>
                            </div>
                            <span class="text-sm font-semibold text-dark">Virtual Account</span>
                        </div>
                        <i class="fa-solid fa-circle-dot text-gray-300 method-radio"></i>
                    </div>
                </div>

                <div class="payment-option border border-gray-200 rounded-lg p-3 cursor-pointer hover:border-primary transition-colors"
                    data-method="ewallet">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                                <i class="fa-solid fa-wallet text-gray-600"></i>
                            </div>
                            <span class="text-sm font-semibold text-dark">E-Wallet</span>
                        </div>
                        <i class="fa-solid fa-circle-dot text-gray-300 method-radio"></i>
                    </div>
                </div>

                <div class="payment-option border border-gray-200 rounded-lg p-3 cursor-pointer hover:border-primary transition-colors"
                    data-method="transfer">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                                <i class="fa-solid fa-money-bill-transfer text-gray-600"></i>
                            </div>
                            <span class="text-sm font-semibold text-dark">Transfer Bank</span>
                        </div>
                        <i class="fa-solid fa-circle-dot text-gray-300 method-radio"></i>
                    </div>
                </div>
            </div>

            <div id="selected-method" class="hidden">
                <div class="flex items-center gap-3 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                    <div class="w-8 h-8 bg-primary rounded flex items-center justify-center">
                        <i class="selected-icon fa-solid fa-qrcode text-white"></i>
                    </div>
                    <span class="selected-text text-sm font-semibold text-dark">QRIS</span>
                    <button class="ml-auto text-primary text-xs font-semibold">Ubah</button>
                </div>
            </div>

            <p id="payment-error" class="text-xs text-red-600 mt-1 hidden">Pilih metode pembayaran</p>
        </section>
    </main>

    <div id="summary-cta" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-4 z-50">
        <div class="mb-3">
            <div class="flex items-center justify-between text-sm mb-1">
                <span class="text-gray-600">Total Donasi</span>
                <span id="total-amount" class="font-bold text-dark">Rp 50.000</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500">Biaya Admin</span>
                <span class="text-gray-500">Gratis</span>
            </div>
            <div class="border-t border-gray-200 pt-2 mt-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-dark">Total Bayar</span>
                    <span id="total-pay" class="text-lg font-bold text-primary">Rp 50.000</span>
                </div>
            </div>
        </div>

        <button id="continue-btn"
            class="w-full py-4 bg-primary text-white rounded-xl text-base font-bold shadow-lg active:scale-95 transition-transform disabled:bg-gray-300 disabled:cursor-not-allowed">
            Lanjutkan Pembayaran
        </button>
    </div>

    <script>
        window.addEventListener('load', function() {
            let selectedAmount = 50000;
            let selectedMethod = null;

            const amountBtns = document.querySelectorAll('.amount-btn');
            const customAmountDiv = document.getElementById('custom-amount');
            const amountInput = document.getElementById('amount-input');
            const totalAmountSpan = document.getElementById('total-amount');
            const totalPaySpan = document.getElementById('total-pay');
            const continueBtn = document.getElementById('continue-btn');

            const paymentToggle = document.getElementById('payment-toggle');
            const paymentOptions = document.getElementById('payment-options');
            const selectedMethodDiv = document.getElementById('selected-method');
            const paymentOptionBtns = document.querySelectorAll('.payment-option');

            function formatRupiah(amount) {
                return 'Rp ' + amount.toLocaleString('id-ID');
            }

            function updateTotal() {
                totalAmountSpan.textContent = formatRupiah(selectedAmount);
                totalPaySpan.textContent = formatRupiah(selectedAmount);
            }

            function validateForm() {
                const name = document.getElementById('donor-name').value.trim();
                const phone = document.getElementById('donor-phone').value.trim();

                const isValid = name && phone && selectedAmount >= 10000 && selectedMethod;
                continueBtn.disabled = !isValid;

                if (!isValid) {
                    continueBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
                    continueBtn.classList.remove('bg-primary');
                } else {
                    continueBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                    continueBtn.classList.add('bg-primary');
                }
            }

            amountBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    amountBtns.forEach(b => {
                        b.classList.remove('border-primary', 'bg-primary', 'text-white');
                        b.classList.add('border-gray-200', 'text-gray-700');
                    });

                    if (this.dataset.amount === 'custom') {
                        customAmountDiv.classList.remove('hidden');
                        this.classList.add('border-primary', 'text-primary');
                        amountInput.focus();
                    } else {
                        customAmountDiv.classList.add('hidden');
                        selectedAmount = parseInt(this.dataset.amount);
                        this.classList.add('border-primary', 'bg-primary', 'text-white');
                        updateTotal();
                        validateForm();
                    }
                });
            });

            amountInput.addEventListener('input', function() {
                const value = this.value.replace(/[^\d]/g, '');
                this.value = value;
                selectedAmount = parseInt(value) || 0;
                updateTotal();
                validateForm();

                const errorEl = document.getElementById('amount-error');
                if (selectedAmount > 0 && selectedAmount < 10000) {
                    errorEl.classList.remove('hidden');
                } else {
                    errorEl.classList.add('hidden');
                }
            });

            paymentToggle.addEventListener('click', function() {
                paymentOptions.classList.toggle('hidden');
                const chevron = this.querySelector('i');
                chevron.classList.toggle('fa-chevron-down');
                chevron.classList.toggle('fa-chevron-up');
            });

            paymentOptionBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    paymentOptionBtns.forEach(b => {
                        b.classList.remove('border-primary');
                        b.querySelector('.method-radio').classList.remove('text-primary');
                        b.querySelector('.method-radio').classList.add('text-gray-300');
                    });

                    this.classList.add('border-primary');
                    this.querySelector('.method-radio').classList.add('text-primary');
                    this.querySelector('.method-radio').classList.remove('text-gray-300');

                    selectedMethod = this.dataset.method;

                    const methodText = this.querySelector('span').textContent;
                    const methodIcon = this.querySelector('i').className;

                    document.querySelector('.selected-text').textContent = methodText;
                    document.querySelector('.selected-icon').className = methodIcon.replace(
                        'text-gray-600', 'text-white');

                    paymentOptions.classList.add('hidden');
                    selectedMethodDiv.classList.remove('hidden');
                    paymentToggle.style.display = 'none';

                    validateForm();
                });
            });

            selectedMethodDiv.addEventListener('click', function(e) {
                if (e.target.textContent === 'Ubah') {
                    paymentOptions.classList.remove('hidden');
                    selectedMethodDiv.classList.add('hidden');
                    paymentToggle.style.display = 'flex';
                    selectedMethod = null;
                    validateForm();
                }
            });

            document.getElementById('donor-name').addEventListener('input', validateForm);
            document.getElementById('donor-phone').addEventListener('input', validateForm);

            continueBtn.addEventListener('click', function() {
                if (!this.disabled) {
                    console.log('Proceeding to payment...');
                }
            });

            updateTotal();
            validateForm();
        });
    </script>
</body>

</html>

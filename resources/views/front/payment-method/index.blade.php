<html lang="id">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metode Pembayaran</title>
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
    </style>
</head>

<body class="bg-light">
    <header id="header" class="bg-white shadow-sm sticky top-0 z-40">
        <div class="px-4 py-3 flex items-center gap-3"><button
                class="w-9 h-9 flex items-center justify-center bg-light rounded-full"><i
                    class="fa-solid fa-arrow-left text-gray-600 text-sm"></i></button>
            <h1 class="text-base font-bold text-dark">Metode Pembayaran</h1>
        </div>
    </header>
    <main id="main-content" class="pb-32">
        <section id="order-summary" class="bg-white px-4 py-4 mb-2">
            <div class="flex items-center gap-3 p-3 bg-light rounded-lg">
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-dark">Tabungan Qurban Kambing
                    </h3>
                    <p class="text-xs text-gray-600">Setoran ke-1</p>
                </div>
                <div class="text-right">
                    <div class="text-sm font-bold text-dark">Rp 50.000</div>
                </div>
            </div>
        </section>
        <section id="payment-methods" class="bg-white px-4 py-5 mb-2">
            <h2 class="text-sm font-bold text-dark mb-4">Pilih Metode Pembayaran
            </h2>
            <div class="space-y-3">
                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">
                        Bank Transfer</h3>
                    <div class="space-y-2"><label
                            class="flex items-center gap-3 p-3 border-2 border-primary bg-primary/5 rounded-xl cursor-pointer"><input
                                type="radio" name="payment" value="bca" checked=""
                                class="w-4 h-4 text-primary">
                            <div class="w-10 h-8 bg-white rounded flex items-center justify-center">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 30'%3E%3Ctext x='50' y='20' text-anchor='middle' fill='%230066CC' font-family='Arial' font-weight='bold' font-size='12'%3EBCA%3C/text%3E%3C/svg%3E"
                                    alt="BCA" class="w-8 h-6">
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">Bank BCA</div>
                                <div class="text-xs text-gray-600">Transfer manual</div>
                            </div>
                            <div class="text-xs text-green-600 font-medium">Gratis</div>
                        </label><label
                            class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer"><input
                                type="radio" name="payment" value="mandiri" class="w-4 h-4 text-primary">
                            <div class="w-10 h-8 bg-white rounded flex items-center justify-center">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 30'%3E%3Ctext x='50' y='20' text-anchor='middle' fill='%23FFD700' font-family='Arial' font-weight='bold' font-size='10'%3EMANDIRI%3C/text%3E%3C/svg%3E"
                                    alt="Mandiri" class="w-8 h-6">
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">Bank Mandiri
                                </div>
                                <div class="text-xs text-gray-600">Transfer manual</div>
                            </div>
                            <div class="text-xs text-green-600 font-medium">Gratis</div>
                        </label><label
                            class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer"><input
                                type="radio" name="payment" value="bri" class="w-4 h-4 text-primary">
                            <div class="w-10 h-8 bg-white rounded flex items-center justify-center">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 30'%3E%3Ctext x='50' y='20' text-anchor='middle' fill='%23003366' font-family='Arial' font-weight='bold' font-size='12'%3EBRI%3C/text%3E%3C/svg%3E"
                                    alt="BRI" class="w-8 h-6">
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">Bank BRI</div>
                                <div class="text-xs text-gray-600">Transfer manual</div>
                            </div>
                            <div class="text-xs text-green-600 font-medium">Gratis</div>
                        </label></div>
                </div>
                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">
                        E-Wallet</h3>
                    <div class="space-y-2"><label
                            class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer"><input
                                type="radio" name="payment" value="dana" class="w-4 h-4 text-primary">
                            <div class="w-10 h-8 bg-blue-500 rounded flex items-center justify-center">
                                <span class="text-white text-xs font-bold">DANA</span>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">DANA</div>
                                <div class="text-xs text-gray-600">Pembayaran instan</div>
                            </div>
                            <div class="text-xs text-gray-600">Rp 1.500</div>
                        </label><label
                            class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer"><input
                                type="radio" name="payment" value="gopay" class="w-4 h-4 text-primary">
                            <div class="w-10 h-8 bg-green-500 rounded flex items-center justify-center">
                                <span class="text-white text-xs font-bold">GO</span>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">GoPay</div>
                                <div class="text-xs text-gray-600">Pembayaran instan</div>
                            </div>
                            <div class="text-xs text-gray-600">Rp 1.500</div>
                        </label><label
                            class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer"><input
                                type="radio" name="payment" value="ovo" class="w-4 h-4 text-primary">
                            <div class="w-10 h-8 bg-purple-600 rounded flex items-center justify-center">
                                <span class="text-white text-xs font-bold">OVO</span>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">OVO</div>
                                <div class="text-xs text-gray-600">Pembayaran instan</div>
                            </div>
                            <div class="text-xs text-gray-600">Rp 1.500</div>
                        </label></div>
                </div>
                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">
                        Virtual Account</h3>
                    <div class="space-y-2"><label
                            class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer"><input
                                type="radio" name="payment" value="va-bni" class="w-4 h-4 text-primary">
                            <div class="w-10 h-8 bg-white rounded flex items-center justify-center">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 30'%3E%3Ctext x='50' y='20' text-anchor='middle' fill='%23FF6600' font-family='Arial' font-weight='bold' font-size='12'%3EBNI%3C/text%3E%3C/svg%3E"
                                    alt="BNI" class="w-8 h-6">
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">BNI Virtual
                                    Account</div>
                                <div class="text-xs text-gray-600">Otomatis terverifikasi
                                </div>
                            </div>
                            <div class="text-xs text-gray-600">Rp 4.000</div>
                        </label><label
                            class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer"><input
                                type="radio" name="payment" value="va-permata" class="w-4 h-4 text-primary">
                            <div class="w-10 h-8 bg-white rounded flex items-center justify-center">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 30'%3E%3Ctext x='50' y='20' text-anchor='middle' fill='%23CC0000' font-family='Arial' font-weight='bold' font-size='8'%3EPERMATA%3C/text%3E%3C/svg%3E"
                                    alt="Permata" class="w-8 h-6">
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">Permata Virtual
                                    Account</div>
                                <div class="text-xs text-gray-600">Otomatis terverifikasi
                                </div>
                            </div>
                            <div class="text-xs text-gray-600">Rp 4.000</div>
                        </label></div>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">
                        Lainnya</h3>
                    <div class="space-y-2"><label
                            class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer"><input
                                type="radio" name="payment" value="qris" class="w-4 h-4 text-primary">
                            <div class="w-10 h-8 bg-red-600 rounded flex items-center justify-center">
                                <i class="fa-solid fa-qrcode text-white text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">QRIS</div>
                                <div class="text-xs text-gray-600">Scan QR untuk bayar</div>
                            </div>
                            <div class="text-xs text-gray-600">Rp 750</div>
                        </label></div>
                </div>
            </div>
        </section>
        <section id="payment-info" class="bg-blue-50 border border-blue-200 mx-4 p-3 rounded-lg mb-2">
            <div class="flex gap-2"><i class="fa-solid fa-info-circle text-blue-600 text-sm mt-0.5"></i>
                <div>
                    <p class="text-xs text-blue-800 font-medium">Informasi Pembayaran
                    </p>
                    <p class="text-xs text-blue-700 mt-1">Setelah pembayaran berhasil,
                        tabungan Anda akan aktif dan Anda bisa mulai menabung kapan saja.
                    </p>
                </div>
            </div>
        </section>
    </main>
    <div id="sticky-payment" class="fixed bottom-0 left-0 right-0 bg-white border-t-2 border-gray-200 p-4 z-50">
        <div class="space-y-2 mb-3">
            <div class="flex justify-between text-xs"><span class="text-gray-600">Setoran ke-1</span><span
                    class="font-semibold text-dark">Rp 50.000</span></div>
            <div class="flex justify-between text-xs"><span class="text-gray-600">Biaya admin</span><span
                    id="admin-fee" class="font-semibold text-dark">Gratis</span></div>
            <div class="h-px bg-gray-200"></div>
            <div class="flex justify-between text-sm"><span class="font-bold text-dark">Total Pembayaran</span><span
                    id="total-payment" class="font-bold text-primary">Rp 50.000</span>
            </div>
        </div><button id="pay-btn" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-semibold">
            Bayar Sekarang
        </button>
    </div>
    <script>
        window.addEventListener('load', function() {
            const paymentRadios = document.querySelectorAll(
                'input[name="payment"]');
            const adminFee = document.getElementById('admin-fee');
            const totalPayment = document.getElementById('total-payment');
            const payBtn = document.getElementById('pay-btn');

            const baseAmount = 50000;
            const fees = {
                'bca': 0,
                'mandiri': 0,
                'bri': 0,
                'dana': 1500,
                'gopay': 1500,
                'ovo': 1500,
                'va-bni': 4000,
                'va-permata': 4000,
                'qris': 750
            };

            paymentRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    paymentRadios.forEach(r => {
                        r.closest('label').classList.remove(
                            'border-primary', 'bg-primary/5');
                        r.closest('label').classList.add(
                            'border-gray-200');
                    });

                    this.closest('label').classList.remove(
                        'border-gray-200');
                    this.closest('label').classList.add('border-primary',
                        'bg-primary/5');

                    const fee = fees[this.value] || 0;
                    const total = baseAmount + fee;

                    adminFee.textContent = fee === 0 ? 'Gratis' : 'Rp ' +
                        fee.toLocaleString('id-ID');
                    totalPayment.textContent = 'Rp ' + total.toLocaleString(
                        'id-ID');
                });
            });

            payBtn.addEventListener('click', function() {
                const selectedPayment = document.querySelector(
                    'input[name="payment"]:checked');
                if (selectedPayment) {
                    console.log('Processing payment with:', selectedPayment
                        .value);
                }
            });
        });
    </script>
</body>

</html>

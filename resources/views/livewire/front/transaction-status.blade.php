<div>
    <x-page-header title="Status Transaksi" :showBack="true" />

    <main id="main-content" class="pb-24">
        @if (!$isValid)
            <div class="px-4 py-8 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-search text-gray-400 text-2xl"></i>
                </div>
                <h2 class="text-lg font-bold text-dark mb-1">Transaksi Tidak Ditemukan</h2>
                <p class="text-sm text-gray-500 mb-6">Mohon periksa kembali link atau ID transaksi Anda.</p>
                <a href="{{ route('home') }}"
                    class="inline-block px-6 py-2 bg-primary text-white rounded-lg text-sm font-semibold">Kembali ke
                    Beranda</a>
            </div>
        @else
            <!-- Status Banner -->
            <section id="status-banner" class="bg-white px-4 py-6 text-center border-b border-gray-100">
                @if ($paymentStatus === 'paid' || $paymentStatus === 'success')
                    <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-check text-green-600 text-2xl"></i>
                    </div>
                    <h2 class="text-lg font-bold text-dark mb-1">Pembayaran Berhasil</h2>
                    <p class="text-sm text-gray-500">Terima kasih atas donasi Anda</p>
                @elseif($paymentStatus === 'expired')
                    <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-times text-red-600 text-2xl"></i>
                    </div>
                    <h2 class="text-lg font-bold text-dark mb-1">Pembayaran Kadaluarsa</h2>
                    <p class="text-sm text-gray-500">Silakan lakukan donasi ulang</p>
                @elseif($paymentStatus === 'failed')
                    <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-triangle-exclamation text-red-600 text-2xl"></i>
                    </div>
                    <h2 class="text-lg font-bold text-dark mb-1">Pembayaran Gagal</h2>
                    <p class="text-sm text-gray-500">Mohon coba beberapa saat lagi</p>
                @else
                    <div class="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-clock text-orange-500 text-2xl"></i>
                    </div>
                    <h2 class="text-lg font-bold text-dark mb-1">Menunggu Pembayaran</h2>
                    <p class="text-sm text-gray-500">Selesaikan pembayaran sebelum</p>
                    <p class="text-sm font-semibold text-orange-500 mt-1">
                        {{ \Carbon\Carbon::parse($expiryTime)->translatedFormat('d F Y, H:i') }} WIB</p>
                @endif
            </section>

            <!-- Payment Instructions (Only if Pending) -->
            @if ($paymentStatus === 'pending')
                <section id="payment-instruction" class="bg-white px-4 py-6 mt-2">
                    <h3 class="text-sm font-bold text-dark mb-4">Instruksi Pembayaran</h3>

                    @if ($paymentGroup === 'bank_transfer' && $bankAccount)
                        <!-- Bank Transfer -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-semibold text-gray-600">Bank
                                    {{ strtoupper($bankAccount->bank_name) }}</span>
                                @if ($bankAccount->icon)
                                    <img src="{{ Storage::url($bankAccount->icon) }}" alt="Bank Logo"
                                        class="h-6 object-contain" onerror="this.style.display='none'">
                                @else
                                    <span class="text-xs font-bold text-gray-500">LOGO</span>
                                @endif
                            </div>
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 mb-1">Nomor Rekening</p>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-lg font-bold text-dark tracking-wide">{{ $bankAccount->account_number }}</span>
                                    <button x-data
                                        @click="navigator.clipboard.writeText('{{ $bankAccount->account_number }}'); alert('Tersalin!')"
                                        class="text-primary text-xs font-semibold hover:underline">Salin</button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">a.n {{ $bankAccount->account_holder_name }}</p>
                            </div>

                            <div class="bg-blue-50 p-3 rounded-lg mb-3">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-600">Nominal Donasi</span>
                                    <span class="font-semibold text-dark">Rp
                                        {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                </div>
                                @if ($uniqueCode > 0)
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="text-gray-600">Kode Unik</span>
                                        <span class="font-bold text-primary">{{ $uniqueCode }}</span>
                                    </div>
                                @endif
                                <div class="h-px bg-blue-200 my-1"></div>
                                <div class="flex justify-between text-sm">
                                    <span class="font-bold text-dark">Total Transfer</span>
                                    <span class="font-bold text-primary">Rp
                                        {{ number_format($totalTransfer, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="bg-orange-50 p-3 rounded-lg flex gap-2">
                                <i class="fa-solid fa-circle-info text-orange-500 text-xs mt-0.5"></i>
                                <p class="text-xs text-gray-600">PENTING: Mohon transfer TEPAT hingga 3 digit terakhir
                                    (kode unik) agar pembayaran terverifikasi otomatis.</p>
                            </div>
                        </div>
                    @elseif($paymentGroup === 'xendit')
                        <!-- Xendit Pending -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 text-center">
                            <p class="text-sm text-dark mb-3">Silakan selesaikan pembayaran melalui halaman pembayaran
                                online.</p>
                            @if ($payment->xendit_invoice_url)
                                <a href="{{ $payment->xendit_invoice_url }}"
                                    class="inline-block px-6 py-2 bg-primary text-white rounded-lg text-sm font-semibold">Lanjut
                                    ke Pembayaran</a>
                            @else
                                <p class="text-xs text-red-500">Link pembayaran tidak tersedia.</p>
                            @endif
                        </div>
                    @endif
                </section>
            @endif

            <!-- Transaction Details -->
            <section id="transaction-detail" class="bg-white px-4 py-4 mt-2">
                <h3 class="text-sm font-bold text-dark mb-3">Detail Transaksi</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">ID Transaksi</span>
                        <span class="font-semibold text-dark text-xs">{{ $trxId }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tanggal</span>
                        <span
                            class="font-semibold text-dark">{{ $payment->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Donatur</span>
                        <span class="font-semibold text-dark">{{ $payment->customer_name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Program</span>
                        <span class="font-semibold text-dark text-right max-w-[60%] line-clamp-1">
                            {{ $checkout['program_name'] ?? ($checkout['target_name'] ?? ($checkout['qurban_name'] ?? 'Donasi')) }}
                        </span>
                    </div>

                    <div class="h-px bg-gray-100 my-2"></div>

                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Nominal</span>
                        <span class="font-semibold text-dark">Rp
                            {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>
                    @if ($payment->admin_fee > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Biaya Admin</span>
                            <span class="font-semibold text-dark">Rp
                                {{ number_format($payment->admin_fee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if ($uniqueCode > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Kode Unik</span>
                            <span class="font-semibold text-primary">{{ $uniqueCode }}</span>
                        </div>
                    @endif

                    <div class="h-px bg-gray-100 my-2"></div>

                    <div class="flex justify-between text-base">
                        <span class="font-bold text-dark">Total</span>
                        <span class="font-bold text-primary">Rp {{ number_format($totalTransfer, 0, ',', '.') }}</span>
                    </div>
                </div>
            </section>

        @endif
    </main>

    <!-- Bottom Actions -->
    <div
        class="fixed bottom-0 left-0 right-0 max-w-[460px] mx-auto bg-white border-t border-gray-200 px-4 py-4 z-50 flex gap-3">
        <a href="{{ route('home') }}"
            class="flex-1 py-3 bg-white border border-gray-300 text-dark rounded-xl text-sm font-semibold text-center hover:bg-gray-50">
            Beranda
        </a>
        <a href="https://wa.me/{{ preg_replace('/^0/', '62', \App\Models\FoundationSetting::value('phone') ?? '') }}"
            target="_blank"
            class="flex-1 py-3 bg-green-500 text-white rounded-xl text-sm font-semibold text-center hover:bg-green-600 flex items-center justify-center gap-2">
            <i class="fa-brands fa-whatsapp"></i> Hubungi Admin
        </a>
    </div>

    @if ($isValid)
        @php
            $metaPixelId = \App\Models\AppSetting::get('meta_pixel_id');
            $metaPixelEnabled = \App\Models\AppSetting::get('meta_pixel_enabled');
        @endphp

        @if ($metaPixelEnabled && $metaPixelId)
            @if ($paymentStatus === 'pending')
                <script>
                    if (typeof fbq !== 'undefined') {
                        fbq('track', 'InitiateCheckout', {
                            value: {{ (float) ($checkout['amount'] ?? 0) }},
                            currency: 'IDR',
                            content_name: '{{ addslashes($checkout['program_name'] ?? ($checkout['target_name'] ?? 'Donasi')) }}'
                        }, {
                            eventID: 'IC-{{ $trxId }}'
                        });
                    }
                </script>
            @elseif($paymentStatus === 'paid')
                <script>
                    if (typeof fbq !== 'undefined') {
                        fbq('track', 'Purchase', {
                            value: {{ (float) ($checkout['amount'] ?? 0) }},
                            currency: 'IDR',
                            content_name: '{{ addslashes($checkout['program_name'] ?? ($checkout['target_name'] ?? 'Donasi')) }}'
                        }, {
                            eventID: 'PUR-{{ $trxId }}'
                        });
                    }
                </script>
            @endif
        @endif
    @endif
</div>

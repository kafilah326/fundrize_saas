<div>
    <x-page-header title="Metode Pembayaran" :showBack="true" />

    <main id="main-content" class="pb-48">
        <section id="order-summary" class="bg-white px-4 py-4 mb-2">
            <div class="flex items-center gap-3 p-3 bg-light rounded-lg">
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-dark">{{ $programName }}</h3>
                    <p class="text-xs text-gray-600">Nominal Pembayaran</p>
                </div>
                <div class="text-right">
                    <div class="text-sm font-bold text-dark">Rp {{ number_format($amount, 0, ',', '.') }}</div>
                </div>
            </div>
        </section>

        @if (session()->has('error'))
            <div class="mx-4 mb-4 p-3 bg-red-100 border border-red-300 rounded-lg text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <section id="payment-methods" class="bg-white px-4 py-5 mb-2">
            <h2 class="text-sm font-bold text-dark mb-4">Pilih Metode Pembayaran</h2>
            <div class="space-y-3">
                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Transfer Bank (Cek
                        Manual)</h3>
                    <div class="space-y-2">
                        @foreach ($bankAccounts as $bank)
                            <label
                                class="flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition-colors
                            {{ $selectedMethod === strtolower($bank->bank_name) && $paymentGroup === 'bank_transfer' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white hover:border-primary/50' }}">
                                <input type="radio" wire:click="selectBank('{{ strtolower($bank->bank_name) }}')"
                                    name="payment" value="{{ strtolower($bank->bank_name) }}"
                                    class="w-4 h-4 text-primary"
                                    {{ $selectedMethod === strtolower($bank->bank_name) && $paymentGroup === 'bank_transfer' ? 'checked' : '' }}>
                                <div
                                    class="w-10 h-8 bg-white rounded flex items-center justify-center border border-gray-100 overflow-hidden">
                                    @if ($bank->icon)
                                        <img src="{{ Storage::url($bank->icon) }}" alt="{{ $bank->bank_name }}"
                                            class="w-full h-full object-contain p-1">
                                    @else
                                        <span
                                            class="text-xs font-bold text-gray-700">{{ strtoupper(substr($bank->bank_name, 0, 3)) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-dark">Bank {{ $bank->bank_name }}</div>
                                    <div class="text-xs text-gray-600">Transfer manual</div>
                                </div>
                                <div class="text-xs text-green-600 font-medium">Gratis</div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Pembayaran Otomatis
                    </h3>
                    <div class="space-y-2">
                        @if($xenditAvailable)
                        <label
                            class="flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition-colors
                            {{ $paymentGroup === 'xendit' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white hover:border-primary/50' }}">
                            <input type="radio" wire:click="selectXendit" name="payment" value="xendit"
                                class="w-4 h-4 text-primary" {{ $paymentGroup === 'xendit' ? 'checked' : '' }}>
                            <div class="w-10 h-8 bg-blue-600 rounded flex items-center justify-center">
                                <i class="fa-solid fa-bolt text-white text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">Pembayaran Online</div>
                                <div class="text-xs text-gray-600">E-Wallet, Virtual Account, QRIS (Otomatis)</div>
                            </div>
                            <div class="text-xs text-green-600 font-medium">Gratis</div>
                        </label>
                        @endif

                        @if($pakasirAvailable)
                        <label
                            class="flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition-colors
                            {{ $paymentGroup === 'pakasir' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white hover:border-primary/50' }}">
                            <input type="radio" wire:click="selectPakasir" name="payment" value="pakasir"
                                class="w-4 h-4 text-primary" {{ $paymentGroup === 'pakasir' ? 'checked' : '' }}>
                            <div class="w-10 h-8 bg-green-600 rounded flex items-center justify-center">
                                <i class="fa-solid fa-qrcode text-white text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-dark">Pembayaran Online</div>
                                <div class="text-xs text-gray-600">QRIS, Virtual Account, PayPal (Otomatis)</div>
                            </div>
                            <div class="text-xs text-gray-500 font-medium">Sesuai metode</div>
                        </label>
                        @endif

                        @if(!$xenditAvailable && !$pakasirAvailable)
                        <div class="flex items-center gap-3 p-3 border-2 border-gray-100 rounded-xl bg-gray-50 opacity-60">
                            <div class="w-10 h-8 bg-gray-400 rounded flex items-center justify-center">
                                <i class="fa-solid fa-bolt text-white text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-gray-400">Pembayaran Online</div>
                                <div class="text-xs text-gray-400">Belum dikonfigurasi</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section id="payment-info"
            class="mx-4 p-3 rounded-lg mb-2 {{ $paymentGroup === 'bank_transfer' ? 'bg-orange-50 border border-orange-200' : 'bg-blue-50 border border-blue-200' }}">
            <div class="flex gap-2">
                <i
                    class="fa-solid {{ $paymentGroup === 'bank_transfer' ? 'fa-triangle-exclamation text-orange-600' : 'fa-info-circle text-blue-600' }} text-sm mt-0.5"></i>
                <div>
                    <p
                        class="text-xs {{ $paymentGroup === 'bank_transfer' ? 'text-orange-800' : 'text-blue-800' }} font-medium">
                        {{ $paymentGroup === 'bank_transfer' ? 'Konfirmasi Manual Diperlukan' : 'Verifikasi Otomatis' }}
                    </p>
                    <p
                        class="text-xs {{ $paymentGroup === 'bank_transfer' ? 'text-orange-700' : 'text-blue-700' }} mt-1">
                        @if($paymentGroup === 'bank_transfer')
                            Sistem akan menambahkan 3 digit kode unik pada total transfer untuk memudahkan verifikasi otomatis.
                        @elseif($paymentGroup === 'pakasir')
                            Anda akan diarahkan ke halaman pembayaran untuk menyelesaikan transaksi via QRIS, Virtual Account, atau PayPal. Biaya layanan dikenakan sesuai metode pembayaran yang dipilih.
                        @else
                            Pembayaran online akan diverifikasi secara otomatis oleh sistem setelah Anda menyelesaikan pembayaran.
                        @endif
                    </p>
                </div>
            </div>
        </section>
    </main>

    <div id="sticky-payment"
        class="fixed bottom-0 left-0 right-0 max-w-[460px] mx-auto bg-white border-t-2 border-gray-200 p-4 z-50">
        <div class="space-y-2 mb-3">
            <div class="flex justify-between text-xs">
                <span class="text-gray-600">Nominal</span>
                <span class="font-semibold text-dark">Rp {{ number_format($amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-600">Biaya admin</span>
                <span
                    class="font-semibold text-dark">
                    @if($paymentGroup === 'pakasir')
                        Sesuai metode pembayaran
                    @else
                        {{ $adminFee === 0 ? 'Gratis' : 'Rp ' . number_format($adminFee, 0, ',', '.') }}
                    @endif
                </span>
            </div>
            <div class="h-px bg-gray-200"></div>
            <div class="flex justify-between text-sm">
                <span class="font-bold text-dark">Total</span>
                <span class="font-bold text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>
        <button wire:click="pay" wire:loading.attr="disabled"
            class="w-full bg-primary text-white py-3 rounded-xl text-sm font-semibold shadow-lg active:scale-95 transition-transform hover:bg-primary/90 flex items-center justify-center gap-2">
            <span wire:loading.remove>Bayar Sekarang</span>
            <span wire:loading><i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...</span>
        </button>
    </div>
</div>

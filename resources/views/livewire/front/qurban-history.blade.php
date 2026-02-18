<div>
    <x-page-header title="Qurban" :showBack="true" backUrl="{{ route('profile.index') }}">
        <x-slot:actions>
            <button class="w-9 h-9 flex items-center justify-center">
                <i class="fa-brands fa-whatsapp text-green-600 text-xl"></i>
            </button>
        </x-slot:actions>
    </x-page-header>

    <main id="main-content" class="pb-24">
        @if($orders->isNotEmpty())
        <section id="qurban-transactions" class="bg-white px-4 py-4 mt-2">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-bold text-dark">Riwayat Transaksi Qurban</h2>
            </div>
            <div class="space-y-2">
                @foreach($orders as $order)
                <div class="border border-gray-200 rounded-lg p-3">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-dark">{{ $order->animal->name }}</h3>
                            <p class="text-xs text-gray-600 mt-0.5">{{ $order->hijri_year }}</p>
                        </div>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'success' => 'bg-green-100 text-green-800',
                                'settled' => 'bg-green-100 text-green-800',
                                'failed' => 'bg-red-100 text-red-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'expired' => 'bg-gray-100 text-gray-800',
                            ];
                            $colorClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 {{ $colorClass }} text-xs font-medium rounded capitalize">{{ $order->status }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-bold text-dark">Rp {{ number_format($order->amount, 0, ',', '.') }}</p>
                        <a href="{{ route('qurban.transaction.detail', $order->id) }}" wire:navigate class="text-xs font-medium text-primary hover:text-primary/80">Lihat Detail</a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <section id="qurban-savings" class="bg-white px-4 py-4 mt-2">
            <h2 class="text-sm font-bold text-dark mb-3">Tabungan Qurban</h2>
            
            @if($savings)
            <div id="savings-status" class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 mb-4 border border-orange-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="text-base font-bold text-dark capitalize">{{ str_replace('-', ' ', $savings->target_animal_type) }}</h3>
                        <p class="text-xs text-gray-600 mt-1">Target: {{ $savings->target_hijri_year }}</p>
                    </div>
                    <!-- Mockup countdown -->
                    <span class="px-2.5 py-1 bg-orange-600 text-white text-xs font-semibold rounded-full">Active</span>
                </div>
                <div class="mb-3">
                    <div class="flex items-baseline justify-between mb-1.5">
                        <span class="text-lg font-bold text-dark">Rp {{ number_format($savings->saved_amount, 0, ',', '.') }}</span>
                        <span class="text-xs text-gray-600">/ Rp {{ number_format($savings->target_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-primary h-2.5 rounded-full" style="width: {{ $savings->progress }}%;"></div>
                    </div>
                    <p class="text-xs text-gray-600 mt-1.5">{{ $savings->progress }}% terkumpul</p>
                </div>
                <div class="flex gap-2">
                    <button wire:click="openDepositModal({{ $savings->id }})" class="flex-1 bg-primary hover:bg-orange-600 text-white font-semibold py-3 rounded-lg text-sm text-center">
                        Setor Sekarang
                    </button>
                    <a href="{{ route('qurban.savings.detail', $savings->id) }}" wire:navigate class="flex-1 bg-white border border-primary text-primary font-semibold py-3 rounded-lg text-sm text-center">
                        Lihat Detail
                    </a>
                </div>
            </div>

            <!-- Deposit Modal -->
            @if($showDepositModal)
            <div class="fixed inset-0 z-50 flex items-end justify-center sm:items-center">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="closeDepositModal"></div>
                
                <div class="bg-white w-full max-w-[460px] rounded-t-2xl sm:rounded-2xl p-5 relative z-10 transform transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-dark">Setor Tabungan</h3>
                        <button wire:click="closeDepositModal" class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full hover:bg-gray-200">
                            <i class="fa-solid fa-xmark text-gray-600"></i>
                        </button>
                    </div>

                    <div class="mb-4">
                        <label class="text-sm font-semibold text-gray-700 mb-2 block">Pilih Nominal Setor</label>
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            @foreach([50000, 100000, 250000, 500000] as $amount)
                            <button wire:click="setDepositAmount({{ $amount }})" 
                                class="py-2.5 px-3 rounded-lg text-sm font-semibold transition-colors {{ $depositAmount == $amount && !$showCustomDepositInput ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Rp {{ number_format($amount, 0, ',', '.') }}
                            </button>
                            @endforeach
                        </div>

                        <div class="relative">
                            <label class="text-xs font-medium text-gray-600 mb-1.5 block">Nominal Lainnya</label>
                            <div class="flex items-center gap-2 border border-gray-300 rounded-lg px-3 focus-within:ring-2 focus-within:ring-primary/30 focus-within:border-primary transition-all {{ $showCustomDepositInput ? 'ring-2 ring-primary/30 border-primary' : '' }}">
                                <span class="text-sm text-gray-600">Rp</span>
                                <input type="text" wire:model.live="customDepositAmount" wire:focus="setDepositAmount('custom')" placeholder="Minimal Rp 10.000" class="flex-1 py-2.5 text-sm focus:outline-none w-full bg-transparent">
                            </div>
                        </div>
                    </div>

                    <button wire:click="submitDeposit" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-bold shadow-lg active:scale-95 transition-transform hover:bg-primary/90">
                        Lanjut Pembayaran
                    </button>
                </div>
            </div>
            @endif

            <div id="savings-history">
                <h3 class="text-sm font-semibold text-dark mb-3">Riwayat Setoran</h3>
                <div class="space-y-2">
                    @foreach($savings->deposits as $deposit)
                    <div class="border border-gray-200 rounded-lg p-3">
                        <div class="flex items-start justify-between mb-1.5">
                            <div class="flex-1">
                                <p class="text-xs text-gray-600">{{ $deposit->created_at->format('d M Y • H:i') }}</p>
                                <p class="text-sm font-semibold text-dark mt-1">Rp {{ number_format($deposit->amount, 0, ',', '.') }}</p>
                            </div>
                            @php
                                $depositStatusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'success' => 'bg-green-100 text-green-800',
                                    'settled' => 'bg-green-100 text-green-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'expired' => 'bg-gray-100 text-gray-800',
                                ];
                                $depositColorClass = $depositStatusColors[$deposit->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 {{ $depositColorClass }} text-xs font-medium rounded capitalize">{{ $deposit->status }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-600">{{ $deposit->payment_method }}</span>
                            <span class="text-xs text-gray-400">•</span>
                            <span class="text-xs text-gray-500">#{{ $deposit->transaction_id }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-sm text-gray-500 mb-4">Belum ada tabungan qurban.</p>
                <a href="{{ route('qurban.tabungan') }}" wire:navigate class="inline-block px-6 py-2 bg-primary text-white rounded-full text-sm font-semibold">
                    Mulai Menabung
                </a>
            </div>
            @endif
        </section>
    </main>

</div>

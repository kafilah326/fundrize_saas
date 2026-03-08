<div>
    <x-page-header title="Zakat Saya" :showBack="true" />

    <main class="pb-20">
        {{-- Statistics Section --}}
        <section class="px-4 pt-4 pb-2">
            <div
                class="bg-gradient-to-br from-primary to-primary/80 rounded-2xl p-6 text-white shadow-lg shadow-primary/20 relative overflow-hidden">
                {{-- Decorative circles --}}
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full"></div>
                <div class="absolute -left-2 -bottom-2 w-16 h-16 bg-white/5 rounded-full"></div>

                <div class="relative z-10">
                    <p class="text-xs text-white/80 font-medium uppercase tracking-wider mb-1">Total Zakat Ditunaikan
                    </p>
                    <h2 class="text-3xl font-black mb-4">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</h2>

                    <div class="grid grid-cols-2 gap-4 border-t border-white/20 pt-4">
                        <div>
                            <p class="text-[10px] text-white/80 font-medium uppercase mb-0.5">Zakat Fitrah</p>
                            <p class="text-sm font-bold">{{ $stats['fitrah_count'] }} Transaksi</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-white/80 font-medium uppercase mb-0.5">Zakat Mal</p>
                            <p class="text-sm font-bold">{{ $stats['maal_count'] }} Transaksi</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Filter Section --}}
        <section
            class="px-4 py-2 flex items-center justify-between gap-3 overflow-x-auto whitespace-nowrap scrollbar-hide">
            <div class="flex items-center gap-2">
                <select wire:model.live="selectedYear"
                    class="bg-white border border-gray-100 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:ring-0 shadow-sm outline-none">
                    <option value="">Semua Tahun</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>

                <select wire:model.live="selectedType"
                    class="bg-white border border-gray-100 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:ring-0 shadow-sm outline-none">
                    <option value="">Semua Jenis</option>
                    <option value="fitrah">Zakat Fitrah</option>
                    <option value="maal">Zakat Mal</option>
                </select>
            </div>

            <div class="text-[10px] text-gray-400 font-medium italic">
                {{ count($zakatTransactions) }} Transaksi
            </div>
        </section>

        {{-- Transactions List --}}
        @if ($zakatTransactions->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-moon text-gray-300 text-2xl"></i>
                </div>
                <h3 class="text-sm font-bold text-dark mb-1">Ops, Data Kosong</h3>
                <p class="text-[11px] text-gray-500 max-w-[200px]">Tidak ditemukan transaksi zakat untuk kriteria ini.
                </p>
                <button wire:click="$set('selectedYear', ''); $set('selectedType', '');"
                    class="mt-4 text-primary text-xs font-bold underline">Resest Filter</button>
            </div>
        @else
            <section class="px-4 py-2 space-y-4">
                @foreach ($zakatTransactions as $trx)
                    <div
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-11 h-11 {{ $trx->zakat_type === 'fitrah' ? 'bg-secondary text-primary' : 'bg-blue-50 text-blue-600' }} rounded-2xl flex items-center justify-center shadow-inner">
                                        <i
                                            class="fa-solid {{ $trx->zakat_type === 'fitrah' ? 'fa-people-group' : 'fa-coins' }} text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-dark leading-tight">
                                            {{ $trx->zakat_type_label }}</h4>
                                        <p class="text-[10px] text-gray-500 font-medium mt-0.5">
                                            <i class="fa-regular fa-calendar-alt mr-1"></i>
                                            {{ $trx->created_at->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                            'success' => 'bg-green-50 text-green-600 border-green-100',
                                            'failed' => 'bg-red-50 text-red-600 border-red-100',
                                            'expired' => 'bg-gray-50 text-gray-500 border-gray-200',
                                        ];
                                        $statusLabel = [
                                            'pending' => 'Menunggu',
                                            'success' => 'Berhasil',
                                            'failed' => 'Gagal',
                                            'expired' => 'Kadaluarsa',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider border {{ $statusClasses[$trx->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $statusLabel[$trx->status] ?? ucfirst($trx->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 bg-gray-50 p-3 rounded-xl mb-4">
                                <div>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-0.5 tracking-tight">
                                        Nominal Zakat</p>
                                    <p class="text-sm font-black text-dark leading-none">Rp
                                        {{ number_format($trx->total, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-0.5 tracking-tight">Detail
                                    </p>
                                    <p class="text-[11px] font-bold text-gray-700 leading-none">
                                        @if ($trx->zakat_type === 'fitrah')
                                            {{ $trx->jumlah_jiwa }} Jiwa
                                        @else
                                            Harta
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span
                                    class="text-[10px] text-gray-400 font-mono">#{{ substr($trx->transaction_id, -8) }}</span>

                                @if ($trx->status === 'pending')
                                    <a href="{{ route('transaction.status', $trx->transaction_id) }}" wire:navigate
                                        class="bg-primary hover:bg-primary-hover text-white px-5 py-2 rounded-xl text-[10px] font-bold shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                                        <i class="fa-solid fa-wallet"></i> Bayar Sekarang
                                    </a>
                                @else
                                    <a href="{{ route('transaction.status', $trx->transaction_id) }}" wire:navigate
                                        class="bg-white border border-gray-100 text-dark px-4 py-2 rounded-xl text-[10px] font-bold shadow-sm transition-all flex items-center gap-2 hover:bg-gray-50">
                                        Lihat Detail <i class="fa-solid fa-chevron-right text-[8px]"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </section>
        @endif
    </main>

    <x-bottom-nav active="profile" />
</div>

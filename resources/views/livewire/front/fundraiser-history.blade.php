<div>
    <x-page-header title="Riwayat Ujroh" :showBack="true" backUrl="{{ route('fundraiser.dashboard') }}" />

    <main id="main-content" class="px-4 pb-20">
        <!-- Summary Section -->
        <section id="ujroh-summary" class="py-6">
            <div class="grid grid-cols-1 gap-3 mb-4">
                <div class="bg-primary rounded-2xl p-5 shadow-lg">
                    <div class="flex items-center gap-3 mb-3">
                        <div>
                            <p class="text-orange-100 text-xs font-medium">Saldo Ujroh</p>
                            <h2 class="text-white text-2xl font-bold">Rp {{ number_format($availableBalance, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                    <a href="{{ route('fundraiser.withdrawal') }}" wire:navigate class="w-full bg-white/20 backdrop-blur-sm text-white font-semibold py-3 rounded-xl border border-white/30 active:scale-95 transition-transform flex items-center justify-center gap-2">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                        <span>Cairkan Ujroh</span>
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                        <p class="text-gray-500 text-xs mb-1 font-medium">Total Ujroh</p>
                        <h4 class="text-dark text-xl font-bold">Rp {{ number_format($totalUjroh, 0, ',', '.') }}</h4>
                        <p class="text-gray-400 text-xs">Keseluruhan</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                        <p class="text-gray-500 text-xs mb-1 font-medium">Ujroh Dicairkan</p>
                        <h4 class="text-dark text-xl font-bold">Rp {{ number_format($withdrawnUjroh, 0, ',', '.') }}</h4>
                        <p class="text-gray-400 text-xs">Sudah ditransfer</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Filter Section -->
        <section id="ujroh-filter" class="mb-4">
            <div class="flex gap-2 overflow-x-auto pb-2 hide-scrollbar">
                <button wire:click="setFilter('all')" 
                    class="{{ $filter === 'all' ? 'bg-primary text-white border-primary' : 'bg-white text-gray-600 border-gray-200' }} px-4 py-2 rounded-lg text-xs font-medium border whitespace-nowrap transition-colors">
                    Semua
                </button>
                <button wire:click="setFilter('month')" 
                    class="{{ $filter === 'month' ? 'bg-primary text-white border-primary' : 'bg-white text-gray-600 border-gray-200' }} px-4 py-2 rounded-lg text-xs font-medium border whitespace-nowrap transition-colors">
                    Bulan Ini
                </button>
                <button wire:click="setFilter('week')" 
                    class="{{ $filter === 'week' ? 'bg-primary text-white border-primary' : 'bg-white text-gray-600 border-gray-200' }} px-4 py-2 rounded-lg text-xs font-medium border whitespace-nowrap transition-colors">
                    7 Hari
                </button>
                <button wire:click="setFilter('today')" 
                    class="{{ $filter === 'today' ? 'bg-primary text-white border-primary' : 'bg-white text-gray-600 border-gray-200' }} px-4 py-2 rounded-lg text-xs font-medium border whitespace-nowrap transition-colors">
                    Hari Ini
                </button>
            </div>
        </section>

        <!-- History Section -->
        <section id="ujroh-history" class="mb-6">
            <h3 class="text-dark font-semibold text-base mb-3 px-1">Histori Ujroh Masuk</h3>
            <div class="space-y-3">
                @forelse($commissions as $commission)
                    @php
                        $title = 'Transaksi';
                        $donorName = '-';
                        $baseAmount = 0;
                        $commissionText = '';
                        
                        if ($commission->commissionable_type === \App\Models\Donation::class) {
                            $title = $commission->commissionable->program->title ?? 'Program Donasi';
                            $donorName = $commission->commissionable->donor_name ?? 'Hamba Allah';
                            $baseAmount = $commission->commissionable->amount;
                        } elseif ($commission->commissionable_type === \App\Models\QurbanOrder::class) {
                            $title = 'Qurban: ' . ($commission->commissionable->animal->name ?? '-');
                            $donorName = $commission->commissionable->donor_name ?? 'Hamba Allah';
                            $baseAmount = $commission->commissionable->amount;
                        } elseif ($commission->commissionable_type === \App\Models\QurbanSavingsDeposit::class) {
                            $title = 'Setoran Tabungan Qurban';
                            $donorName = $commission->commissionable->qurbanSaving->donor_name ?? 'Hamba Allah';
                            $baseAmount = $commission->commissionable->amount;
                        }

                        // Determine if it was a percentage or fixed based on amount.
                        // Actually, it's better to just show the base amount it was derived from.
                        if ($baseAmount > 0) {
                            $percentage = round(($commission->amount / $baseAmount) * 100, 1);
                            if ($percentage > 0 && $percentage < 100) {
                                $commissionText = $percentage . '% dari Rp ' . number_format($baseAmount, 0, ',', '.');
                            } else {
                                $commissionText = 'Komisi Tetap';
                            }
                        }
                    @endphp
                    
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h4 class="text-dark font-semibold text-sm mb-1 line-clamp-2">{{ $title }}</h4>
                                <p class="text-gray-500 text-xs mb-2">Donatur: {{ $donorName }}</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-gray-400">{{ $commission->created_at->format('d M Y, H:i') }}</span>
                                    <span class="bg-emerald-50 text-emerald-600 text-[10px] font-medium px-2 py-0.5 rounded-full">Berhasil</span>
                                </div>
                            </div>
                            <div class="text-right ml-3 flex-shrink-0">
                                <p class="text-primary font-bold text-base">+Rp {{ number_format($commission->amount, 0, ',', '.') }}</p>
                                <p class="text-gray-400 text-[10px] mt-0.5">{{ $commissionText }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl p-6 text-center border border-gray-100">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-receipt text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-dark">Belum ada riwayat ujroh</p>
                        <p class="text-xs text-gray-500 mt-1">Bagikan link kebaikanmu untuk mendapatkan ujroh.</p>
                    </div>
                @endforelse
            </div>
            
            @if($commissions->hasPages())
            <div class="mt-4">
                {{ $commissions->links(data: ['scrollTo' => '#ujroh-history']) }}
            </div>
            @endif
        </section>
    </main>

    <x-bottom-nav active="profile" />
</div>

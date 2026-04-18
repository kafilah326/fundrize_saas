@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

<div wire:poll.10s class="space-y-8 animate-fade-in-up">
    <!-- Premium Welcome Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-900 via-primary to-orange-600 p-8 text-white shadow-2xl">
        <!-- Abstract glowing shapes -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-orange-300 opacity-20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
            <div>
                <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md rounded-full px-4 py-1.5 mb-4 border border-white/20">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    <span class="text-xs font-semibold tracking-wider text-green-100 uppercase">Sistem Online</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold mb-2 tracking-tight">Assalamualaikum, <span class="text-orange-200">{{ Auth::user()->name }}</span>!</h2>
                <p class="text-white/80 text-lg max-w-xl font-light leading-relaxed mb-6">Ringkasan performa yayasan dan aktivitas donasi terbaru hari ini: <span class="font-semibold text-white">{{ now()->translatedFormat('l, d F Y') }}</span>.</p>
                
                <div class="flex gap-4">
                    <a href="{{ route('admin.donations') }}" class="bg-white text-primary hover:bg-orange-50 font-bold py-2.5 px-6 rounded-xl transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm flex items-center group">
                        <i class="fa-solid fa-bolt text-yellow-500 mr-2 group-hover:scale-125 transition-transform"></i> Lihat Donasi
                    </a>
                </div>
            </div>
            
            <div class="hidden md:flex justify-end relative">
                <!-- Decorative element -->
                <div class="relative w-48 h-48 bg-white/10 backdrop-blur-lg rounded-3xl border border-white/20 flex items-center justify-center transform rotate-12 hover:rotate-0 transition duration-500 shadow-2xl">
                     <i class="fa-solid fa-mosque text-7xl text-white drop-shadow-xl"></i>
                     <!-- floating elements -->
                     <div class="absolute -top-6 -left-6 bg-yellow-400/20 backdrop-blur-md p-3 rounded-2xl animate-bounce" style="animation-duration: 3s;">
                         <i class="fa-solid fa-hand-holding-heart text-2xl text-yellow-300"></i>
                     </div>
                     <div class="absolute -bottom-4 -right-4 bg-emerald-400/20 backdrop-blur-md p-3 rounded-2xl animate-bounce" style="animation-duration: 4s;">
                         <i class="fa-solid fa-up-right-dots text-xl text-emerald-300"></i>
                     </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Total Overall -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform group-hover:scale-110 duration-500 text-6xl text-primary">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-4 shadow-inner group-hover:bg-primary group-hover:text-white transition-colors">
                    <i class="fa-solid fa-vault text-xl"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Penerimaan</h3>
                <div class="text-2xl lg:text-3xl font-extrabold text-gray-800">
                    <span class="text-sm text-gray-400 font-medium mr-1">Rp</span>{{ number_format($totalDonations, 0, ',', '.') }}
                </div>
                <div class="mt-3 inline-flex items-center text-[11px] font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">
                    <i class="fa-solid fa-check-circle mr-1.5"></i> Semua Kategori
                </div>
            </div>
        </div>

        <!-- Card 2: Program -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden group">
             <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 duration-500 text-6xl text-blue-500">
                <i class="fa-solid fa-hand-holding-heart"></i>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-4 shadow-inner group-hover:bg-blue-500 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-hand-holding-heart text-xl"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Dana Program</h3>
                <div class="text-2xl lg:text-3xl font-extrabold text-gray-800">
                    <span class="text-sm text-gray-400 font-medium mr-1">Rp</span>{{ number_format($totalDanaProgram, 0, ',', '.') }}
                </div>
                @php
                    $progPct = $totalDonations > 0 ? round(($totalDanaProgram / $totalDonations) * 100) : 0;
                @endphp
                <div class="mt-3 flex items-center text-xs font-medium text-blue-600">
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mr-2 overflow-hidden">
                      <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $progPct }}%"></div>
                    </div>
                    <span>{{ $progPct }}%</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Zakat -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden group">
             <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 duration-500 text-6xl text-emerald-500">
                <i class="fa-solid fa-moon"></i>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-4 shadow-inner group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-mosque text-xl"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Dana Zakat</h3>
                <div class="text-2xl lg:text-3xl font-extrabold text-gray-800">
                    <span class="text-sm text-gray-400 font-medium mr-1">Rp</span>{{ number_format($totalDanaZakat, 0, ',', '.') }}
                </div>
                @php
                    $zakatPct = $totalDonations > 0 ? round(($totalDanaZakat / $totalDonations) * 100) : 0;
                @endphp
                <div class="mt-3 flex items-center text-xs font-medium text-emerald-600">
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mr-2 overflow-hidden">
                      <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $zakatPct }}%"></div>
                    </div>
                    <span>{{ $zakatPct }}%</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Qurban -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden group">
             <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 duration-500 text-6xl text-orange-500">
                <i class="fa-solid fa-cow"></i>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 mb-4 shadow-inner group-hover:bg-orange-500 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-cow text-xl"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Dana Qurban</h3>
                <div class="text-2xl lg:text-3xl font-extrabold text-gray-800">
                    <span class="text-sm text-gray-400 font-medium mr-1">Rp</span>{{ number_format($totalDanaQurban, 0, ',', '.') }}
                </div>
                @php
                    $qurbanPct = $totalDonations > 0 ? round(($totalDanaQurban / $totalDonations) * 100) : 0;
                @endphp
                <div class="mt-3 flex items-center text-xs font-medium text-orange-600">
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mr-2 overflow-hidden">
                      <div class="bg-orange-500 h-1.5 rounded-full" style="width: {{ $qurbanPct }}%"></div>
                    </div>
                    <span>{{ $qurbanPct }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area: Chart and List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Activity Chart (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex flex-col relative overflow-hidden">
            <!-- Decorative background elements for premium feel -->
            <div class="absolute -top-10 -right-10 w-48 h-48 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-8 relative z-10">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Aktivitas Transaksi</h3>
                    <p class="text-sm text-gray-500 mt-1">Grafik pemasukan untuk bulan <span class="font-semibold text-gray-700">{{ now()->translatedFormat('F Y') }}</span></p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 shadow-inner border border-gray-100">
                    <i class="fa-solid fa-chart-column"></i>
                </div>
            </div>

            <div class="flex-1 relative min-h-[300px] z-10" x-data="{
                activeTooltip: null
            }">
                @if (count($chartData) > 0)
                    <div class="flex items-end justify-between h-[250px] space-x-1 sm:space-x-2 pt-6">
                        @php
                            $maxTotal = max(array_column($chartData, 'total'));
                            $maxTotal = $maxTotal == 0 ? 1 : $maxTotal;
                        @endphp
                        @foreach ($chartData as $index => $data)
                            @php
                                $heightPct = ($data['total'] / $maxTotal) * 100;
                            @endphp
                            <div class="flex flex-col items-center flex-1 h-full justify-end group cursor-pointer">
                                <div class="w-full relative flex items-end justify-center h-full" 
                                     @mouseenter="activeTooltip = {{ $index }}" 
                                     @mouseleave="activeTooltip = null">
                                    
                                    <!-- Tooltip (Alpine controlled) -->
                                    <div x-show="activeTooltip === {{ $index }}" 
                                         x-transition.opacity
                                         class="absolute bottom-full mb-3 z-30"
                                         style="display: none;">
                                        <div class="bg-gray-900 border border-gray-700 text-white rounded-xl shadow-2xl py-2.5 px-4 text-xs whitespace-nowrap transform -translate-x-1/2 left-1/2 relative">
                                            <div class="font-bold mb-1.5 text-gray-300 pb-1.5 border-b border-gray-700 flex items-center justify-between">
                                                <span>{{ $data['date'] }}</span>
                                            </div>
                                            <div class="text-emerald-400 font-extrabold text-sm tracking-wide">
                                                Rp {{ number_format($data['total'], 0, ',', '.') }}
                                            </div>
                                            <!-- Tooltip arrow -->
                                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2.5 h-2.5 bg-gray-900 border-b border-r border-gray-700 rotate-45"></div>
                                        </div>
                                    </div>

                                    <!-- Bar Background Guide -->
                                    <div class="absolute inset-x-0 bottom-0 top-0 mx-auto w-full sm:w-4/5 max-w-[2rem] bg-gray-50 rounded-t-lg group-hover:bg-gray-100 transition-colors duration-300 z-0 opacity-50"></div>

                                    <!-- Active Bar -->
                                    <div class="w-full sm:w-4/5 max-w-[2rem] rounded-t-lg bg-gradient-to-t from-primary to-[#ff8c60] relative overflow-hidden transition-all duration-500 ease-out transform group-hover:scale-x-110 group-hover:-translate-y-1 group-hover:shadow-[0_0_15px_rgba(255,107,53,0.4)] z-10"
                                         style="height: {{ max(1, $heightPct) }}%;">
                                        <!-- Shimmer effect overlay on Hover -->
                                        <div class="absolute inset-0 w-full h-full bg-gradient-to-t from-transparent via-white/30 to-transparent -translate-y-full opacity-0 group-hover:opacity-100 group-hover:animate-shimmer z-20"></div>
                                    </div>
                                </div>

                                <!-- Label -->
                                <div class="mt-4 text-[10px] sm:text-xs font-semibold text-gray-400 text-center w-full truncate group-hover:text-primary transition-colors">
                                    {{ \Carbon\Carbon::createFromFormat('d M', $data['date'])->format('d') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-full text-gray-400">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <i class="fa-solid fa-chart-line text-3xl text-gray-300"></i>
                        </div>
                        <p>Belum ada data grafik bulan ini.</p>
                    </div>
                @endif
                
                <!-- Chart grid lines (decorative) -->
                <div class="absolute inset-x-0 bottom-[32px] border-b border-dashed border-gray-200 z-0"></div>
                <div class="absolute inset-x-0 bottom-[calc(250px*.5+32px)] border-b border-dashed border-gray-100 z-0"></div>
                <div class="absolute inset-x-0 top-[24px] border-b border-dashed border-gray-100 z-0"></div>
            </div>
        </div>

        <!-- Recent Transactions List (1 col) -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full relative">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 backdrop-blur-md relative z-10">
                <h3 class="text-base font-bold text-gray-800">Hari Ini</h3>
                <span class="text-xs font-medium px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 shadow-sm">{{ $todayTransactions->count() }} Transaksi</span>
            </div>
            
            <div class="p-4 flex-1 overflow-y-auto z-10" style="max-height: 400px;" id="custom-scroll">
                @if ($todayTransactions->count() > 0)
                    <div class="space-y-3">
                        @foreach ($todayTransactions as $payment)
                            <div class="p-3.5 rounded-2xl border border-transparent hover:border-gray-100 hover:bg-gray-50 hover:shadow-md transition-all duration-300 flex items-center space-x-4 group cursor-default">
                                <div class="flex-shrink-0">
                                    @php
                                        $iconBg = match ($payment->transaction_type) {
                                            'program' => 'bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600',
                                            'zakat' => 'bg-gradient-to-br from-emerald-100 to-emerald-50 text-emerald-600',
                                            'qurban_langsung', 'qurban_tabungan' => 'bg-gradient-to-br from-orange-100 to-orange-50 text-orange-600',
                                            default => 'bg-gradient-to-br from-gray-100 to-gray-50 text-gray-600'
                                        };
                                        $iconPath = match ($payment->transaction_type) {
                                            'program' => 'fa-hand-holding-heart',
                                            'zakat' => 'fa-moon',
                                            'qurban_langsung', 'qurban_tabungan' => 'fa-cow',
                                            default => 'fa-receipt'
                                        };
                                    @endphp
                                    <div class="h-12 w-12 rounded-xl {{ $iconBg }} flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                                        <i class="fa-solid {{ $iconPath }} text-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-bold text-gray-900 truncate">
                                        {{ $payment->customer_name ?? 'Hamba Allah' }}
                                    </p>
                                    <p class="text-[11px] text-gray-500 truncate flex items-center mt-1">
                                        <span class="truncate max-w-[140px] font-medium">
                                            @if ($payment->transaction_type == 'program')
                                                {{ $payment->program->title ?? 'Program' }}
                                            @elseif($payment->transaction_type == 'qurban_langsung')
                                                {{ $payment->qurbanOrder->animal->name ?? 'Qurban' }}
                                            @elseif($payment->transaction_type == 'qurban_tabungan')
                                                Tabungan Qurban
                                            @else
                                                Zakat
                                            @endif
                                        </span>
                                        <span class="mx-1.5 text-gray-300">•</span>
                                        <span class="text-gray-400 group-hover:text-primary transition-colors"><i class="fa-regular fa-clock mr-1"></i>{{ $payment->created_at->format('H:i') }}</span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-extrabold text-gray-800 tracking-tight">
                                        Rp {{ number_format($payment->total, 0, ',', '.') }}
                                    </p>
                                    @php
                                        $status = $payment->status;
                                        $badgeClass = match ($status) {
                                            'paid', 'settled' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'failed', 'canceled' => 'bg-red-50 text-red-700 border-red-200',
                                            default => 'bg-gray-50 text-gray-700 border-gray-200',
                                        };
                                        $label = match ($status) {
                                            'paid', 'settled' => 'Berhasil',
                                            'pending' => 'Pending',
                                            'failed' => 'Gagal',
                                            'canceled' => 'Batal',
                                            default => ucfirst($status),
                                        };
                                    @endphp
                                    <span class="inline-flex items-center justify-center mt-1.5 px-2.5 py-0.5 rounded-md text-[10px] font-bold border shadow-sm {{ $badgeClass }}">
                                        @if(in_array($status, ['paid', 'settled'])) <i class="fa-solid fa-circle-check mr-1 text-[9px]"></i> @endif
                                        {{ $label }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-16 px-6 text-center h-full flex flex-col justify-center items-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 mb-5 shadow-inner border border-gray-100">
                            <i class="fa-solid fa-mug-hot text-gray-300 text-3xl"></i>
                        </div>
                        <p class="text-base text-gray-600 font-bold">Belum ada transaksi hari ini</p>
                        <p class="text-sm text-gray-400 mt-1 max-w-[200px] leading-relaxed">Transaksi baru donatur Anda akan muncul di riwayat ini.</p>
                    </div>
                @endif
            </div>
            @if ($todayTransactions->count() > 0)
                <div class="p-4 border-t border-gray-100 bg-white text-center relative z-10">
                    <a href="{{ route('admin.donations') }}" class="inline-flex items-center justify-center w-full py-2.5 text-sm font-bold text-primary bg-primary/5 hover:bg-primary/10 rounded-xl transition-colors">
                        Lihat Semua Transaksi <i class="fa-solid fa-arrow-right ml-2 text-[10px]"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
@keyframes shimmer {
    100% {
        transform: translateY(100%);
    }
}
.animate-shimmer {
    animation: shimmer 1s;
}
.animate-fade-in-up {
    animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
/* Sleeker scrollbar for the recent transactions */
#custom-scroll::-webkit-scrollbar {
    width: 4px;
}
#custom-scroll::-webkit-scrollbar-track {
    background: transparent;
}
#custom-scroll::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 4px;
}
#custom-scroll::-webkit-scrollbar-thumb:hover {
    background: #FF6B35;
}
</style>

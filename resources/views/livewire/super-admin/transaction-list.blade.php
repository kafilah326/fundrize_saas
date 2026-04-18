<div class="space-y-8 pb-12">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-money-bill-trend-up"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-1">Total Pendapatan</p>
                <p class="text-xl font-black text-slate-800 tracking-tight">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-1">Berhasil (Paid)</p>
                <p class="text-xl font-black text-slate-800 tracking-tight">{{ number_format($stats['paid_count']) }} Trx</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-1">Menunggu (Pending)</p>
                <p class="text-xl font-black text-slate-800 tracking-tight">{{ number_format($stats['pending_count']) }} Trx</p>
            </div>
        </div>
    </div>

    <!-- Header & Filters -->
    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                    <i class="fa-solid fa-list-ul text-indigo-500"></i>
                    Semua Transaksi
                </h2>
                <p class="text-slate-500 font-medium text-sm mt-1">Gunakan filter di samping untuk mencari data spesifik.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <!-- Search -->
                <div class="relative min-w-[200px] flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama yayasan..."
                        class="pl-11 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-4 focus:ring-indigo-500/10 transition-all w-full">
                </div>

                <!-- Type Filter -->
                <select wire:model.live="type"
                    class="pl-4 pr-10 py-3 bg-slate-50 border-none rounded-2xl text-xs font-black text-slate-600 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none cursor-pointer appearance-none">
                    <option value="all">🚀 SEMUA TIPE</option>
                    <option value="registration">REGISTRASI</option>
                    <option value="maintenance">MAINTENANCE</option>
                    <option value="addon_purchase">ADD-ON PURCHASE</option>
                </select>

                <!-- Status Filter -->
                <select wire:model.live="status"
                    class="pl-4 pr-10 py-3 bg-slate-50 border-none rounded-2xl text-xs font-black text-slate-600 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none cursor-pointer appearance-none">
                    <option value="all">💎 SEMUA STATUS</option>
                    <option value="paid">PAID</option>
                    <option value="pending">PENDING</option>
                    <option value="failed">FAILED</option>
                    <option value="expired">EXPIRED</option>
                </select>

                <!-- Date Range -->
                <select wire:model.live="dateRange"
                    class="pl-4 pr-10 py-3 bg-slate-50 border-none rounded-2xl text-xs font-black text-slate-600 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none cursor-pointer appearance-none">
                    <option value="all">📅 SEMUA WAKTU</option>
                    <option value="today">HARI INI</option>
                    <option value="this_week">MINGGU INI</option>
                    <option value="this_month">BULAN INI</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-y-2">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                        <th class="px-6 py-4">Informasi Yayasan</th>
                        <th class="px-6 py-4">Jenis Produk</th>
                        <th class="px-6 py-4 text-center">Metode</th>
                        <th class="px-6 py-4 text-right">Nominal</th>
                        <th class="px-6 py-4 text-right">Status Transaksi</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="space-y-4">
                    @forelse($transactions as $trx)
                        <tr class="bg-white hover:bg-slate-50 transition-all duration-300 group shadow-sm ring-1 ring-slate-100 rounded-2xl">
                            <td class="px-6 py-5 first:rounded-l-2xl">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-white border border-slate-100 flex items-center justify-center text-slate-400 font-black text-lg group-hover:scale-105 transition-transform duration-300">
                                        {{ substr($trx->tenant->name ?? '?', 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-800">{{ $trx->tenant->name ?? 'User Tak Terdaftar' }}</span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $trx->external_id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col gap-1">
                                    @php
                                        $typeColors = [
                                            'registration' => 'from-indigo-500 to-indigo-600',
                                            'maintenance' => 'from-rose-500 to-rose-600',
                                            'addon_purchase' => 'from-amber-500 to-amber-600'
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded-lg text-[9px] font-black text-white bg-gradient-to-r {{ $typeColors[$trx->type] ?? 'from-slate-500 to-slate-600' }} uppercase tracking-wider self-start">
                                        {{ str_replace('_', ' ', $trx->type) }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-400 mt-0.5">{{ $trx->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="text-[11px] font-black text-slate-600 uppercase tracking-tighter">{{ $trx->payment_method ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="text-sm font-black text-slate-800 tracking-tight">Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                @php
                                    $statusClasses = [
                                        'paid' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                                        'pending' => 'bg-amber-50 text-amber-600 ring-amber-100',
                                        'failed' => 'bg-rose-50 text-rose-600 ring-rose-100',
                                        'expired' => 'bg-slate-50 text-slate-600 ring-slate-100',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 py-1.5 px-3.5 rounded-full text-[10px] font-black {{ $statusClasses[$trx->status] ?? 'bg-slate-50 text-slate-600' }} ring-1 uppercase tracking-widest">
                                    @if($trx->status === 'paid')
                                        <i class="fa-solid fa-check-circle translate-y-[0.5px]"></i>
                                    @elseif($trx->status === 'pending')
                                        <i class="fa-solid fa-spinner animate-spin text-[8px]"></i>
                                    @endif
                                    {{ $trx->status }}
                                </span>
                            </td>
                            <td class="px-6 py-5 last:rounded-r-2xl text-right">
                                <button class="w-8 h-8 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-indigo-500 transition-all duration-300">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-200 mb-6 group-hover:rotate-12 transition-all duration-500">
                                        <i class="fa-solid fa-layer-group text-4xl"></i>
                                    </div>
                                    <h3 class="text-lg font-black text-slate-800 mb-1">Data Tidak Ditemukan</h3>
                                    <p class="text-sm font-medium text-slate-400 max-w-[280px]">Maaf, kami tidak menemukan transaksi yang sesuai dengan filter Anda saat ini.</p>
                                    <button wire:click="$set('search', '')" class="mt-6 px-6 py-2 bg-indigo-50 text-indigo-600 text-xs font-black rounded-xl hover:bg-indigo-100 transition-all uppercase tracking-widest leading-loose">Hapus Semua Filter</button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-10 px-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>

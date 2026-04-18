<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Riwayat Transaksi (Duitku)</h2>
            <p class="text-slate-500 font-medium mt-1">Pantau seluruh aliran dana masuk dari registrasi dan biaya pemeliharaan secara otomatis.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" wire:model.live="search" placeholder="Cari Yayasan..." class="pl-12 pr-6 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 transition-all w-full md:w-64 font-medium">
            </div>
            <select wire:model.live="type" class="px-6 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-600 focus:border-indigo-500 transition-all outline-none cursor-pointer">
                <option value="all">Semua Tipe</option>
                <option value="registration">Registrasi</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl border border-emerald-100 flex items-center shadow-sm animate-in slide-in-from-top duration-300">
            <i class="fa-solid fa-circle-check mr-3 text-emerald-500"></i>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Transactions List -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">
                    <th class="px-8 py-5">Tanggal</th>
                    <th class="px-6 py-5">Yayasan</th>
                    <th class="px-6 py-5">Tipe</th>
                    <th class="px-6 py-5 text-right">Amount</th>
                    <th class="px-8 py-5 text-right">Status & Metode</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($transactions as $trx)
                <tr class="hover:bg-slate-50/30 transition group">
                    <td class="px-8 py-5">
                        <span class="text-xs font-bold text-slate-500">{{ $trx->created_at->format('d M Y H:i') }}</span>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-bold group-hover:scale-110 transition-transform">
                                {{ substr($trx->tenant->name ?? '?', 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-800">{{ $trx->tenant->name ?? 'External' }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $trx->external_id }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <span class="px-3 py-1 {{ $trx->type === 'registration' ? 'bg-indigo-50 text-indigo-600 border-indigo-100' : 'bg-amber-50 text-amber-600 border-amber-100' }} text-[10px] font-black rounded-lg border uppercase tracking-wider">
                            {{ $trx->type }}
                        </span>
                    </td>
                    <td class="px-6 py-5 text-right font-black text-slate-800 text-sm">
                        Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-8 py-5 text-right">
                        @if($trx->status === 'paid')
                            <div class="flex flex-col items-end">
                                <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200 uppercase tracking-widest">
                                    <i class="fa-solid fa-check"></i> Paid
                                </span>
                                <span class="text-[9px] text-slate-400 font-bold mt-1 uppercase">{{ $trx->payment_method }}</span>
                            </div>
                        @elseif($trx->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 border border-amber-200 uppercase tracking-widest">
                                Pending
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-[10px] font-black bg-red-100 text-red-800 border border-red-200 uppercase tracking-widest">
                                {{ strtoupper($trx->status) }}
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-4">
                                <i class="fa-solid fa-inbox text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Belum ada transaksi Duitku</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-8 py-4 border-t border-slate-50">
            {{ $transactions->links() }}
        </div>
    </div>
</div>

<div class="space-y-8 pb-12">
    <!-- Header with Breadcrumbs & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 text-sm font-bold text-slate-400 uppercase tracking-[0.2em] mb-3">
                <span>Manajemen Sistem</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-slate-900">Semua Tenant</span>
            </div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Eksplorasi Tenant</h2>
            <p class="text-slate-500 font-medium mt-1">Kelola, pantau, dan verifikasi seluruh lembaga yang terdaftar di platform.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.tenants.create') }}" class="flex items-center gap-2 px-6 py-3.5 bg-indigo-600 rounded-2xl text-sm font-black text-white hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20 active:translate-y-0.5">
                <i class="fa-solid fa-plus"></i> <span class="uppercase tracking-widest">Tambah Tenant</span>
            </a>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl border border-emerald-100 flex items-center shadow-sm shadow-emerald-500/5 animate-in slide-in-from-top duration-300">
            <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center mr-3 shadow-md">
                <i class="fa-solid fa-check"></i>
            </div>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <input type="text" wire:model.live="search" placeholder="Cari nama yayasan, email, atau subdomain..." 
                class="w-full pl-14 pr-6 py-4 rounded-2xl border-transparent bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 transition-all font-medium text-slate-800 placeholder:text-slate-400">
        </div>
        <div class="flex gap-4">
            <div class="relative min-w-[180px]">
                <select wire:model.live="status" class="w-full pl-6 pr-12 py-4 rounded-2xl border-transparent bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 transition-all font-bold text-slate-700 appearance-none">
                    <option value="">Semua Status</option>
                    <option value="active">Active</option>
                    <option value="trial">Trial</option>
                    <option value="suspended">Suspended</option>
                </select>
                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </div>
            </div>
            <button class="w-14 h-14 rounded-2xl bg-slate-50 text-slate-500 flex items-center justify-center border border-transparent hover:bg-white hover:border-slate-200 hover:text-indigo-600 transition-all">
                <i class="fa-solid fa-sliders"></i>
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">
                        <th class="px-8 py-6 font-black">Detail Yayasan / Lembaga</th>
                        <th class="px-6 py-6 font-black text-center">Status & Paket</th>
                        <th class="px-6 py-6 font-black text-center">Statistik</th>
                        <th class="px-8 py-6 font-black text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-slate-700">
                    @forelse ($tenants as $tenant)
                    <tr class="hover:bg-slate-50/50 transition group">
                        <td class="px-8 py-5">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500/10 to-indigo-600/10 text-indigo-600 flex items-center justify-center font-black mr-5 border border-indigo-100 shadow-sm transition-transform group-hover:scale-110">
                                    {{ substr($tenant->name, 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <div class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors text-base">
                                        @if(\Illuminate\Support\Facades\Route::has('superadmin.tenants.detail'))
                                            <a href="{{ route('superadmin.tenants.detail', $tenant->id) }}">{{ $tenant->name }}</a>
                                        @else
                                            {{ $tenant->name }}
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs font-medium text-slate-500">{{ $tenant->email }}</span>
                                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                        <a href="http://{{ $tenant->slug }}.{{ config('tenancy.base_domain') }}" target="_blank" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline">
                                            {{ $tenant->slug }}.{{ config('tenancy.base_domain') }} <i class="fa-solid fa-arrow-up-right-from-square text-[8px] ml-0.5"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col items-center gap-2">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black rounded-lg border border-indigo-100 uppercase tracking-widest shadow-sm">{{ $tenant->getPlanName() }}</span>
                                
                                @if($tenant->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200 uppercase tracking-widest"><span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span> Active</span>
                                @elseif($tenant->status === 'trial')
                                    <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 border border-amber-200 uppercase tracking-widest"><span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> Trial</span>
                                @elseif($tenant->status === 'suspended')
                                    <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-[10px] font-black bg-red-100 text-red-800 border border-red-200 uppercase tracking-widest"><span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Suspended</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-[10px] font-black bg-slate-100 text-slate-800 border border-slate-200 uppercase tracking-widest">{{ ucfirst($tenant->status) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex flex-col items-center space-y-1.5">
                                <div class="text-[10px] font-black text-slate-500 flex items-center uppercase tracking-wider">
                                    <i class="fa-solid fa-users w-4 text-center mr-1.5 text-slate-300"></i>
                                    <span class="font-black text-slate-900">{{ $tenant->users_count ?? 1 }}</span> &nbsp;Admins
                                </div>
                                <div class="text-[10px] font-black text-slate-400 flex items-center uppercase tracking-widest">
                                    <i class="fa-solid fa-clock w-4 text-center mr-1.5 text-slate-300"></i>
                                    {{ $tenant->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2 text-xs">
                                @if(\Illuminate\Support\Facades\Route::has('superadmin.tenants.detail'))
                                    <a href="{{ route('superadmin.tenants.detail', $tenant->id) }}" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-100 hover:bg-white hover:text-indigo-600 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all" title="Detail Panel">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                @endif
                                
                                <button class="w-10 h-10 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-100 hover:bg-white hover:text-amber-600 hover:border-amber-100 hover:shadow-xl hover:shadow-amber-500/5 transition-all" title="Edit Data">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                @if($tenant->status === 'suspended')
                                    <button wire:click="activateTenant({{ $tenant->id }})" class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Aktifkan">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                @else
                                    <button wire:click="suspendTenant({{ $tenant->id }})" onclick="confirm('Yakin ingin menangguhkan tenant ini?') || event.stopImmediatePropagation()" class="w-10 h-10 rounded-xl bg-red-50 text-red-400 flex items-center justify-center border border-red-100 hover:bg-red-500 hover:text-white transition-all shadow-sm" title="Tangguhkan">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 mb-6">
                                    <i class="fa-solid fa-building-circle-xmark text-4xl"></i>
                                </div>
                                <h3 class="text-lg font-black text-slate-800 mb-2">Data Tenant Kosong</h3>
                                <p class="text-sm font-medium text-slate-400 max-w-xs mx-auto">Kami tidak menemukan data tenant yang sesuai dengan kriteria pencarian Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($tenants->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-50">
            <div class="flex items-center justify-between">
                {{ $tenants->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

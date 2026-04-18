<div class="space-y-8 pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('superadmin.tenants') }}" class="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div class="flex flex-col">
                <div class="flex items-center gap-3">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">{{ $tenant->name }}</h2>
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
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Global ID: #{{ str_pad($tenant->id, 4, '0', STR_PAD_LEFT) }}</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Daftar Pada {{ $tenant->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-6 py-3.5 bg-white border border-slate-200 rounded-2xl text-xs font-black text-slate-600 hover:bg-slate-50 transition-all shadow-sm uppercase tracking-widest">
                <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Profil
            </button>
            @if($tenant->status === 'suspended')
                <button wire:click="activateTenant" class="px-6 py-3.5 bg-emerald-600 text-white rounded-2xl text-xs font-black hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-600/20 uppercase tracking-widest">
                    <i class="fa-solid fa-check mr-2"></i> Aktifkan Kembali
                </button>
            @else
                <button wire:click="suspendTenant" onclick="confirm('Yakin ingin menangguhkan tenant ini?') || event.stopImmediatePropagation()" class="px-6 py-3.5 bg-red-50 text-red-600 rounded-2xl text-xs font-black hover:bg-red-600 hover:text-white transition-all border border-red-100 uppercase tracking-widest">
                    <i class="fa-solid fa-ban mr-2"></i> Tangguhkan
                </button>
            @endif
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Tenant Info & Plan -->
        <div class="space-y-8 lg:col-span-1">
            <!-- General Info Card -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 flex flex-col group">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em] mb-8 pb-4 border-b border-slate-50 flex justify-between items-center">
                    Informasi Kontak
                    <i class="fa-solid fa-id-card text-indigo-200"></i>
                </h3>
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 mr-4 shrink-0 transition-colors group-hover:text-indigo-500">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1">Email Korespondensi</p>
                            <p class="text-sm font-black text-slate-800 break-all">{{ $tenant->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 mr-4 shrink-0 transition-colors group-hover:text-indigo-500">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1">Telepon / WhatsApp</p>
                            <p class="text-sm font-black text-slate-800">{{ $tenant->phone ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 mr-4 shrink-0 transition-colors group-hover:text-indigo-500">
                            <i class="fa-solid fa-link"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1">URL Akses Utama</p>
                            <a href="http://{{ $tenant->slug }}.{{ config('tenancy.base_domain') }}" target="_blank" class="text-sm font-black text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-2 group/link underline decoration-indigo-200 decoration-2 underline-offset-4 transition-all">
                                {{ $tenant->slug }}.{{ config('tenancy.base_domain') }}
                                <i class="fa-solid fa-external-link text-[10px] opacity-50 group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Plan Card -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 flex flex-col">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em]">Paket Saat Ini</h3>
                    <button wire:click="openPlanModal" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-800">Ubah Paket <i class="fa-solid fa-chevron-right ml-1"></i></button>
                </div>
                <div class="p-6 rounded-3xl bg-indigo-600 text-white shadow-xl shadow-indigo-600/30 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12 group-hover:scale-125 transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-md">
                                <i class="fa-solid fa-gem"></i>
                            </div>
                            <span class="text-sm font-black uppercase tracking-[0.15em]">{{ $tenant->getPlanName() }} Plan</span>
                        </div>
                        <div class="space-y-2">
                            <p class="text-[10px] font-bold text-indigo-100 uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-check text-[8px]"></i> Fitur Lanjutan Aktif
                            </p>
                            <p class="text-[10px] font-bold text-indigo-100 uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-check text-[8px]"></i> Kuota Server Optimal
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Administrators List Card -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em]">Administrator</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $tenant->users->count() }} Terdaftar</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="space-y-4">
                    @foreach($tenant->users->take(5) as $user)
                    <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-sm font-black text-slate-500 border border-slate-100 shadow-sm group-hover:scale-105 transition-transform">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-800">{{ $user->name }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">{{ $user->role }}</span>
                            </div>
                        </div>
                        <button class="w-8 h-8 rounded-lg text-slate-300 hover:text-indigo-600 hover:bg-white transition-all"><i class="fa-solid fa-ellipsis"></i></button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column: Domains & Settings -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Domains Listing Table Card -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                <div class="px-8 py-7 border-b border-slate-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-black text-slate-800 tracking-tight">Daftar Host & Domain</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Kelola subdomain dan domain kustom yayasan</p>
                    </div>
                    <button class="px-4 py-2 bg-slate-50 rounded-xl text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:bg-indigo-50 transition-colors">Tambah Custom Domain</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">
                                <th class="px-8 py-5 font-black">Hostname / Alamat</th>
                                <th class="px-6 py-5 font-black text-center">Tipe</th>
                                <th class="px-6 py-5 font-black text-center">Utama</th>
                                <th class="px-8 py-5 font-black text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-slate-700">
                            @foreach($tenant->domains as $domain)
                            <tr class="hover:bg-slate-50/30 transition group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 transition-colors group-hover:text-indigo-500">
                                            <i class="fa-solid fa-{{ $domain->type === 'subdomain' ? 'network-wired' : 'globe' }} text-xs"></i>
                                        </div>
                                        <a href="http://{{ $domain->domain }}" target="_blank" class="font-bold text-slate-800 hover:text-indigo-600 transition-colors">
                                            {{ $domain->domain }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($domain->type === 'subdomain')
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-500 text-[9px] font-black rounded-lg uppercase tracking-widest border border-slate-200/50">Subdomain</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-purple-100 text-purple-700 text-[9px] font-black rounded-lg uppercase tracking-widest border border-purple-200/50">Custom</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($domain->is_primary)
                                        <div class="w-6 h-6 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mx-auto shadow-sm border border-amber-100">
                                            <i class="fa-solid fa-star text-[10px]"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200 uppercase tracking-widest">Akitf</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Quick System Logs / Tenant Summary -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left"></i> Ringkasan Sistem Tenant
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 group hover:bg-white hover:shadow-xl hover:shadow-indigo-500/5 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-indigo-500 shadow-sm border border-slate-100 transition-transform group-hover:scale-110">
                                <i class="fa-solid fa-database text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Database Isolation</p>
                                <p class="text-base font-black text-slate-800">Tenant Shared DB</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 group hover:bg-white hover:shadow-xl hover:shadow-emerald-500/5 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-emerald-500 shadow-sm border border-slate-100 transition-transform group-hover:scale-110">
                                <i class="fa-solid fa-shield-halved text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Security Level</p>
                                <p class="text-base font-black text-slate-800">Advanced Multi-tenancy</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 p-10 border-2 border-dashed border-slate-100 rounded-[2rem] flex flex-col items-center justify-center text-center bg-slate-50/30">
                    <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center text-slate-200 mb-6 shadow-sm border border-slate-50">
                        <i class="fa-solid fa-screwdriver-wrench text-2xl"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">Fitur Monitoring Menunggu</h4>
                    <p class="text-[11px] font-medium text-slate-400 mt-2 max-w-xs">Grafik penggunaan server dan statistik donasi real-time untuk superadmin akan tersedia di pembaruan sistem berikutnya.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Change Plan (Standardized UI) -->
    @if($isPlanModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-data x-cloak>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" wire:click="$set('isPlanModalOpen', false)"></div>

        <div class="relative bg-white w-full max-w-md rounded-[3rem] shadow-2xl flex flex-col overflow-hidden animate-in zoom-in duration-300">
            <div class="px-10 py-8 border-b border-slate-50 shrink-0">
                <h3 class="text-2xl font-black text-slate-800 tracking-tight">Upgrade / Migrasi Paket</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Tenant: {{ $tenant->name }}</p>
            </div>
            
            <form wire:submit.prevent="updatePlan">
                <div class="p-10 space-y-8">
                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-4 ml-1">Pilih Paket Tujuan</label>
                        <select wire:model="selectedPlanId" 
                            class="w-full px-5 py-4 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-700 appearance-none">
                            <option value="">-- Pilih Paket Baru --</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} (Rp{{ number_format($plan->price, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="p-5 rounded-2xl bg-amber-50 border border-amber-100 flex gap-4">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-1"></i>
                        <p class="text-[11px] font-bold text-amber-700 leading-relaxed uppercase tracking-wider">
                            Perubahan paket akan langsung mengubah batasan fitur dan kuota sistem bagi tenant ini secara instan.
                        </p>
                    </div>
                </div>

                <div class="px-10 py-8 bg-slate-50 border-t border-slate-100 flex items-center justify-between shrink-0">
                    <button type="button" wire:click="$set('isPlanModalOpen', false)" class="text-[10px] font-black text-slate-400 hover:text-slate-700 uppercase tracking-[0.2em] transition-colors">Batal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-8 py-3.5 rounded-2xl shadow-xl shadow-indigo-600/20 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                        <span class="tracking-widest uppercase text-[10px]">Simpan Migrasi</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

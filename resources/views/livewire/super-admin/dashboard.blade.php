<div class="space-y-8 pb-12">
    <!-- Header with Breadcrumbs & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Dashboard Overview</h2>
            <p class="text-sm text-slate-500 font-medium">Pantau performa dan statistik seluruh ekosistem Fundrize SaaS.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all shadow-sm">
                <i class="fa-solid fa-download text-slate-400"></i> Export Laporan
            </button>
            <a href="{{ route('superadmin.tenants.create') }}" class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 rounded-xl text-sm font-bold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20">
                <i class="fa-solid fa-plus"></i> Tambah Tenant
            </a>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Tenants -->
        <div class="group bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50/50 rounded-full -mr-12 -mt-12 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i class="fa-solid fa-server text-xl"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total</span>
                    <span class="text-xs font-bold text-indigo-600">+12% Bulan Ini</span>
                </div>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-1 relative z-10">Total Terdaftar</p>
            <h3 class="text-4xl font-black text-slate-900 tracking-tight relative z-10">{{ $totalTenants }}</h3>
        </div>

        <!-- Active Tenants -->
        <div class="group bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50/50 rounded-full -mr-12 -mt-12 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Berlangganan</span>
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse mt-1"></div>
                </div>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-1 relative z-10">Tenant Aktif</p>
            <h3 class="text-4xl font-black text-slate-900 tracking-tight relative z-10">{{ $activeTenants }}</h3>
        </div>

        <!-- Trial Tenants -->
        <div class="group bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col hover:shadow-xl hover:shadow-amber-500/5 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50/50 rounded-full -mr-12 -mt-12 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <i class="fa-solid fa-hourglass-half text-xl"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Masa Uji</span>
                    <span class="text-[10px] font-bold text-amber-600 mt-1">14 Hari</span>
                </div>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-1 relative z-10">Tenant Trial</p>
            <h3 class="text-4xl font-black text-slate-900 tracking-tight relative z-10">{{ $trialTenants }}</h3>
        </div>

        <!-- Suspended Tenants -->
        <div class="group bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col hover:shadow-xl hover:shadow-red-500/5 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-red-50/50 rounded-full -mr-12 -mt-12 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-600">
                    <i class="fa-solid fa-shield-slash text-xl"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Masalah</span>
                    <span class="text-xs font-bold text-red-600">Perlu Tindakan</span>
                </div>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-1 relative z-10">Tenant Ditangguhkan</p>
            <h3 class="text-4xl font-black text-slate-900 tracking-tight relative z-10">{{ $suspendedTenants }}</h3>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Tenants -->
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Pendaftaran Terbaru</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">5 Tenant Terakhir Terdaftar</p>
                </div>
                <a href="{{ route('superadmin.tenants') }}" class="px-4 py-2 rounded-xl bg-slate-50 text-xs font-black text-indigo-600 uppercase tracking-widest hover:bg-indigo-50 transition-colors">Lihat Semua &rarr;</a>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] bg-slate-50/30">
                            <th class="px-8 py-4 font-black">Lembaga / Yayasan</th>
                            <th class="px-4 py-4 font-black text-center">Paket</th>
                            <th class="px-4 py-4 font-black text-center">Status</th>
                            <th class="px-8 py-4 font-black text-right">Tanggal Berdiri</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-slate-700">
                        @forelse ($recentTenants as $tenant)
                        <tr class="hover:bg-slate-50/50 transition group cursor-default">
                            <td class="px-8 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500/10 to-indigo-600/10 text-indigo-600 flex items-center justify-center font-black mr-4 border border-indigo-100 shadow-sm transition-transform group-hover:scale-105">
                                        {{ substr($tenant->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                                            @if(\Illuminate\Support\Facades\Route::has('superadmin.tenants.detail'))
                                                <a href="{{ route('superadmin.tenants.detail', $tenant->id) }}">{{ $tenant->name }}</a>
                                            @else
                                                {{ $tenant->name }}
                                            @endif
                                        </div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $tenant->slug }}.{{ config('tenancy.base_domain') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-center">
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-lg border border-indigo-100 shadow-sm uppercase tracking-widest">{{ $tenant->getPlanName() }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-center">
                                    @if($tenant->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200 uppercase tracking-widest"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active</span>
                                    @elseif($tenant->status === 'trial')
                                        <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 border border-amber-200 uppercase tracking-widest"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Trial</span>
                                    @elseif($tenant->status === 'suspended')
                                        <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-[10px] font-black bg-red-100 text-red-800 border border-red-200 uppercase tracking-widest"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Suspended</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-[10px] font-black bg-slate-100 text-slate-800 border border-slate-200 uppercase tracking-widest">{{ ucfirst($tenant->status) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-800">{{ $tenant->created_at->format('d M Y') }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $tenant->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 mb-4">
                                        <i class="fa-solid fa-folder-open text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-400">Belum ada pendaftaran tenant terbaru.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- System Summary Card -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 flex flex-col">
            <h3 class="text-lg font-black text-slate-800 tracking-tight mb-8">System Summary</h3>
            
            <div class="space-y-8 flex-1">
                <!-- Domains stat -->
                <div class="flex items-center p-4 rounded-3xl bg-slate-50 border border-slate-100 group hover:bg-white hover:shadow-xl hover:shadow-blue-500/5 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-globe text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Domain Aktif</p>
                        <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $totalDomains }}</p>
                    </div>
                </div>
                
                <!-- Plan distribution bar -->
                <div class="pt-6 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-xs font-black text-slate-800 uppercase tracking-[0.15em]">Statistik Paket</p>
                        <span class="text-[10px] font-bold text-slate-400">Berdasarkan Tenant Aktif</span>
                    </div>
                    @php
                        $total = $totalTenants > 0 ? $totalTenants : 1;
                        $colors = ['bg-indigo-600', 'bg-emerald-500', 'bg-amber-500', 'bg-rose-500', 'bg-sky-500', 'bg-purple-500'];
                    @endphp
                    <div class="w-full h-4 bg-slate-100 rounded-full overflow-hidden flex mb-6 shadow-inner border border-slate-200/50">
                        @foreach($planDistribution as $index => $plan)
                            @php
                                $pct = round(($plan->tenants_count / $total) * 100);
                                $color = $colors[$index % count($colors)];
                            @endphp
                            @if($pct > 0)
                                <div class="{{ $color }} h-full transition-all duration-1000" style="width: {{ $pct }}%" title="{{ $plan->name }}: {{ $pct }}%"></div>
                            @endif
                        @endforeach
                    </div>
                    <div class="space-y-3">
                        @foreach($planDistribution as $index => $plan)
                            @php
                                $pct = round(($plan->tenants_count / $total) * 100);
                                $color = $colors[$index % count($colors)];
                            @endphp
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center">
                                    <div class="w-2.5 h-2.5 rounded-full {{ $color }} mr-3 shadow-lg shadow-black/10 transition-all group-hover:scale-125"></div>
                                    <span class="text-xs font-bold text-slate-600 transition-colors group-hover:text-slate-900">{{ $plan->name }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-slate-900 bg-slate-100 px-2 py-0.5 rounded-lg">{{ $plan->tenants_count }}</span>
                                    <span class="text-xs font-black text-slate-900">{{ $pct }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Quick Actions Title -->
                <div class="pt-8 border-t border-slate-100">
                    <p class="text-xs font-black text-slate-800 uppercase tracking-[0.15em] mb-6">Navigasi Cepat</p>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('superadmin.tenants.create') }}" class="flex flex-col items-center justify-center p-5 rounded-[2rem] border border-slate-100 bg-slate-50 hover:bg-white hover:shadow-xl hover:shadow-indigo-500/5 hover:border-indigo-100 group transition-all text-center">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-plus text-indigo-600"></i>
                            </div>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">Add Tenant</span>
                        </a>
                        <a href="{{ route('superadmin.plans') }}" class="flex flex-col items-center justify-center p-5 rounded-[2rem] border border-slate-100 bg-slate-50 hover:bg-white hover:shadow-xl hover:shadow-indigo-500/5 hover:border-indigo-100 group transition-all text-center">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-tags text-indigo-600"></i>
                            </div>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">Manage Plans</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

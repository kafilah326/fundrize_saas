<div>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Paket & Langganan</h2>
        <p class="text-gray-600">Kelola paket layanan yayasan Anda dan pantau penggunaan fitur.</p>
    </div>

    <!-- Current Plan & Usage Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Current Plan Card -->
        <div class="lg:col-span-1 bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-8 bg-gradient-to-br from-primary to-orange-500 text-white">
                <p class="text-sm font-bold uppercase tracking-widest opacity-80 mb-1">Paket Saat Ini</p>
                <h3 class="text-3xl font-black mb-4">{{ $currentTenant->plan->name }}</h3>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-bold">Rp
                        {{ number_format($currentTenant->plan->price, 0, ',', '.') }}</span>
                    <span class="text-sm opacity-80">/ Sekali Bayar</span>
                </div>
            </div>
            <div class="p-8">
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Status Langganan</span>
                        <span
                            class="px-3 py-1 bg-green-100 text-green-600 rounded-full font-bold text-xs uppercase tracking-tighter">Aktif</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Biaya Maintenance</span>
                        <span class="font-bold text-gray-800">{{ $currentTenant->plan->system_fee_percentage }}% per
                            transaksi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage Stats Card -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-200 p-8">
            <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-primary"></i>
                Statistik Penggunaan Kuota
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Program Usage -->
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-sm font-bold text-gray-700 uppercase tracking-tight">Program Donasi</span>
                        <span class="text-xs font-medium text-gray-500">{{ $usage['programs']['current'] }} /
                            {{ $usage['programs']['max'] }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        @php $progPercent = ($usage['programs']['current'] / ($usage['programs']['max'] ?: 1)) * 100; @endphp
                        <div class="bg-primary h-3 rounded-full transition-all duration-1000"
                            style="width: {{ min(100, $progPercent) }}%"></div>
                    </div>
                    <p class="mt-2 text-[10px] text-gray-400">Batas maksimal pembuatan kampanye donasi aktif.</p>
                </div>

                <!-- User Usage -->
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-sm font-bold text-gray-700 uppercase tracking-tight">Tim Pengelola</span>
                        <span class="text-xs font-medium text-gray-500">{{ $usage['users']['current'] }} /
                            {{ $usage['users']['max'] }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        @php $userPercent = ($usage['users']['current'] / ($usage['users']['max'] ?: 1)) * 100; @endphp
                        <div class="bg-blue-500 h-3 rounded-full transition-all duration-1000"
                            style="width: {{ min(100, $userPercent) }}%"></div>
                    </div>
                    <p class="mt-2 text-[10px] text-gray-400">Batas jumlah akun administrator & pengelola.</p>
                </div>

                <!-- Storage Usage -->
                <div class="md:col-span-2">
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-sm font-bold text-gray-700 uppercase tracking-tight">Penyimpanan Digital
                            (Storage)</span>
                        <span class="text-xs font-medium text-gray-500">Estimasi Terpakai /
                            {{ $usage['storage']['max'] }} MB</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full" style="width: 5%"></div> <!-- Scaled down for now -->
                    </div>
                    <p class="mt-2 text-[10px] text-gray-400">Media promosi, foto program, dan dokumen legalitas.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Plans -->
    <div class="mb-8">
        <h3 class="text-xl font-bold text-gray-800">Upgrade Paket Layanan</h3>
        <p class="text-gray-600">Pilih paket yang lebih tinggi untuk membuka fitur premium dan meningkatkan limitasi.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach ($plans as $plan)
            @php $isCurrent = $plan->id === $currentTenant->plan_id; @endphp
            <div
                class="relative p-8 rounded-3xl bg-white border border-gray-200 shadow-sm flex flex-col {{ $plan->slug === 'pro' && !$isCurrent ? 'ring-2 ring-primary scale-105 z-10' : '' }} {{ $isCurrent ? 'opacity-75 border-primary bg-primary/5' : '' }}">
                @if ($plan->slug === 'pro' && !$isCurrent)
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-primary text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest leading-none">
                        Paling Populer</div>
                @endif

                @if ($isCurrent)
                    <div class="absolute top-4 right-4">
                        <span class="flex h-3 w-3">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                    </div>
                @endif

                <div class="mb-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $plan->name }}</h4>
                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-2 h-8">{{ $plan->description }}</p>
                </div>

                <div class="mb-6">
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-black text-gray-900">Rp
                            {{ number_format($plan->price, 0, ',', '.') }}</span>
                        <span class="text-xs font-medium text-gray-500">
                            @if ($plan->price > 0)
                                Sekali Bayar
                            @else
                                Gratis
                            @endif
                        </span>
                    </div>
                    @if ($plan->system_fee_percentage > 0)
                        <p class="text-[10px] font-bold text-primary mt-1 uppercase tracking-tighter">Maintenance Fee:
                            {{ $plan->system_fee_percentage }}%</p>
                    @endif
                </div>

                <div class="mb-8 flex-grow">
                    <ul class="space-y-3">
                        @foreach ($allFeatures as $featureKey => $label)
                            @php $isEnabled = $plan->hasFeature($featureKey); @endphp
                            <li
                                class="flex items-center gap-2 text-[11px] {{ $isEnabled ? 'text-gray-600' : 'text-gray-400' }}">
                                @if ($isEnabled)
                                    <i class="fas fa-check-circle text-green-500"></i>
                                @else
                                    <i class="fas fa-times-circle text-gray-300"></i>
                                @endif
                                <span
                                    class="{{ !$isEnabled ? 'line-through opacity-70' : '' }}">{{ $label }}</span>
                            </li>
                        @endforeach

                        <li class="pt-2 border-t border-gray-50"></li>

                        @foreach ($plan->limits as $limit => $value)
                            <li class="flex items-center gap-2 text-[11px] text-gray-700 font-medium">
                                <i class="fas fa-microchip text-primary/60"></i>
                                <span>{{ $value == -1 ? 'Unlimited' : $value }}
                                    {{ ucwords(str_replace('_', ' ', $limit)) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if ($isCurrent)
                    <button disabled
                        class="w-full py-3 px-4 rounded-xl text-center font-bold text-sm bg-gray-100 text-gray-400 cursor-not-allowed">
                        Paket Aktif
                    </button>
                @else
                    <button wire:click="upgrade('{{ $plan->id }}')" wire:loading.attr="disabled"
                        class="w-full py-3 px-4 rounded-xl text-center font-bold text-sm transition-all {{ $plan->slug === 'pro' ? 'bg-primary text-white shadow-lg shadow-primary/20 hover:bg-primary-hover hover:-translate-y-0.5' : 'bg-gray-800 text-white hover:bg-gray-900 hover:-translate-y-0.5' }}">
                        <span wire:loading.remove wire:target="upgrade('{{ $plan->id }}')">
                            Upgrade Sekarang
                        </span>
                        <span wire:loading wire:target="upgrade('{{ $plan->id }}')">
                            <i class="fa-solid fa-circle-notch animate-spin"></i> Memproses...
                        </span>
                    </button>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Extra Add-ons Section -->
    <div class="mt-20">
        <div class="mb-8">
            <h3 class="text-xl font-bold text-gray-800">Add-on Ekstra</h3>
            <p class="text-gray-600">Butuh kuota lebih atau fitur spesifik tanpa upgrade paket? Pilih add-on sesuai kebutuhan Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($availableAddons as $addon)
                @php 
                    $isActive = $activeAddons->contains('addon_id', $addon->id);
                @endphp
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col transition-all hover:shadow-md hover:border-primary/20">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                            @if($addon->type === 'feature')
                                <i class="fa-solid fa-puzzle-piece"></i>
                            @else
                                <i class="fa-solid fa-plus-minus"></i>
                            @endif
                        </div>
                        @if($isActive)
                            <span class="px-2 py-1 bg-green-100 text-green-600 text-[10px] font-bold rounded-lg uppercase tracking-tighter">Aktif</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h4 class="font-bold text-gray-900 leading-tight">{{ $addon->name }}</h4>
                        <p class="text-[11px] text-gray-500 mt-1 line-clamp-2">{{ $addon->description }}</p>
                    </div>

                    <div class="mb-6 mt-auto">
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg font-black text-gray-900">Rp {{ number_format($addon->price, 0, ',', '.') }}</span>
                            <span class="text-[10px] text-gray-400 font-medium">/ {{ $addon->duration === 'monthly' ? 'Bulan' : 'Sekali' }}</span>
                        </div>
                        <p class="text-[10px] text-primary font-bold mt-1">
                            @if($addon->type === 'limit')
                                +{{ number_format($addon->value) }} {{ $addon->target }}
                            @else
                                Unlock Fitur {{ $addon->target }}
                            @endif
                        </p>
                    </div>

                    <button wire:click="buyAddon('{{ $addon->id }}')" wire:loading.attr="disabled"
                        class="w-full py-2.5 rounded-xl text-xs font-bold transition-all {{ $isActive ? 'bg-gray-100 text-gray-500 hover:bg-gray-200' : 'bg-primary text-white hover:bg-primary-hover shadow-sm hover:shadow-primary/20' }}">
                        <span wire:loading.remove wire:target="buyAddon('{{ $addon->id }}')">
                            {{ $isActive ? 'Beli Lagi' : 'Beli Sekarang' }}
                        </span>
                        <span wire:loading wire:target="buyAddon('{{ $addon->id }}')">
                            <i class="fa-solid fa-circle-notch animate-spin"></i>
                        </span>
                    </button>

                    @if (config('app.env') === 'local')
                        <button wire:click="simulateAddon('{{ $addon->id }}')"
                            class="w-full mt-2 py-2 rounded-xl text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 hover:bg-emerald-100 transition-all">
                            <i class="fa-solid fa-vial mr-1"></i> Simulasi Aktif (Local Only)
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Duitku POP Info -->
    {{-- <div class="mt-12 p-6 rounded-2xl bg-blue-50 border border-blue-100 flex items-start gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-500 text-white flex items-center justify-center flex-shrink-0 text-xl">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <div>
            <h5 class="font-bold text-blue-900">Pembayaran Aman & Otomatis</h5>
            <p class="text-sm text-blue-700/80 leading-relaxed">Upgrade paket Anda menggunakan infrastruktur pembayaran Duitku. Setelah pembayaran berhasil, fitur dan limitasi baru akan langsung diaktifkan secara otomatis tanpa perlu konfirmasi manual.</p>
        </div>
    </div> --}}
</div>

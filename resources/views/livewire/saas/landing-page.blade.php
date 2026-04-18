<div class="pt-20">
    <!-- Hero Section -->
    <section class="relative overflow-hidden pt-16 pb-32">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary-100/50 rounded-full blur-[120px]">
            </div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-100/50 rounded-full blur-[120px]">
            </div>
        </div>

        <div class="container mx-auto px-6 text-center">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 shadow-sm mb-8 animate-bounce">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                </span>
                <span class="text-sm font-semibold text-slate-600">{{ $settings['hero_badge'] }}</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 mb-8 leading-[1.1]">
                {!! $settings['hero_title'] !!}
            </h1>

            <p class="text-xl text-slate-600 max-w-2xl mx-auto mb-12 leading-relaxed">
                {{ $settings['hero_subtitle'] }}
            </p>

            <div class="flex flex-col sm:row items-center justify-center gap-4">
                {{-- <a href="#pricing" class="w-full sm:w-auto bg-primary-600 hover:bg-primary-700 text-white font-bold px-10 py-4 rounded-full shadow-xl shadow-primary-200 transition-all hover:-translate-y-1">
                    Coba Gratis 14 Hari
                </a> --}}
                <a href="#features"
                    class="w-full sm:w-auto bg-white hover:bg-slate-50 text-slate-800 font-bold px-10 py-4 rounded-full border border-slate-200 shadow-sm transition-all hover:border-slate-300">
                    {{ $settings['hero_cta_text'] }}
                </a>
            </div>

            <div class="mt-20 relative max-w-5xl mx-auto">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-50 via-transparent to-transparent z-10"></div>
                <!-- Mockup Image -->
                <div class="rounded-2xl border border-slate-200 shadow-2xl overflow-hidden bg-white">
                    @if($settings['hero_image'])
                        <img src="{{ asset('storage/' . $settings['hero_image']) }}" 
                            alt="Dashboard Preview" class="w-full h-auto">
                    @else
                        <img src="https://placehold.co/1200x800/f97316/ffffff?text=Dashboard+Yayasan+Modern"
                            alt="Dashboard Preview" class="w-full h-auto">
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-32 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-primary-600 font-bold tracking-wider uppercase text-sm mb-4">Fitur Unggulan</h2>
                <h3 class="text-4xl font-extrabold text-slate-900 mb-6">{{ $settings['features_title'] }}</h3>
                <p class="text-lg text-slate-600">{{ $settings['features_subtitle'] }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="p-8 rounded-3xl bg-slate-50 border border-transparent hover:border-primary-200 transition-all group">
                    <div
                        class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-primary-600 shadow-sm mb-6 group-hover:bg-primary-600 group-hover:text-white transition-all">
                        <i class="fas fa-hand-holding-heart text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-4">Donasi Program</h4>
                    <p class="text-slate-600">Buat kampanye donasi tak terbatas dengan berbagai metode pembayaran
                        otomatis.</p>
                </div>

                <div
                    class="p-8 rounded-3xl bg-slate-50 border border-transparent hover:border-primary-200 transition-all group">
                    <div
                        class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-primary-600 shadow-sm mb-6 group-hover:bg-primary-600 group-hover:text-white transition-all">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-4">Sistem Fundraiser</h4>
                    <p class="text-slate-600">Berdayakan relawan Anda dengan link referral khusus dan sistem komisi
                        (ujroh) otomatis.</p>
                </div>

                <div
                    class="p-8 rounded-3xl bg-slate-50 border border-transparent hover:border-primary-200 transition-all group">
                    <div
                        class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-primary-600 shadow-sm mb-6 group-hover:bg-primary-600 group-hover:text-white transition-all">
                        <i class="fas fa-cow text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-4">Tabungan Qurban</h4>
                    <p class="text-slate-600">Fitur unik tabungan qurban digital dengan laporan progres simpanan untuk
                        donatur.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-32 relative overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-primary-600 font-bold tracking-wider uppercase text-sm mb-4">Harga & Paket</h2>
                <h3 class="text-4xl font-extrabold text-slate-900 mb-6">Investasi Terbaik Untuk <br> Digitalisasi Dakwah
                    Anda</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach ($plans as $plan)
                    <div
                        class="relative p-10 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col {{ $plan->slug === 'pro' ? 'ring-2 ring-primary-500 scale-105 z-20' : '' }}">
                        @if ($plan->slug === 'pro')
                            <div
                                class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-primary-600 text-white text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest">
                                Paling Populer</div>
                        @endif

                        <div class="mb-8">
                            <h4 class="text-xl font-bold text-slate-900 mb-2">{{ $plan->name }}</h4>
                            <p class="text-slate-500 text-sm leading-relaxed">{{ $plan->description }}</p>
                        </div>

                        <div class="mb-8">
                            <span class="text-4xl font-extrabold text-slate-900">Rp
                                {{ number_format($plan->price, 0, ',', '.') }}</span>
                            @if ($plan->price > 0)
                                <span class="text-slate-500 font-medium">(Sekali Bayar)</span>
                            @else
                                <span class="text-slate-500 font-medium">Bebas Biaya</span>
                            @endif
                        </div>

                        @if ($plan->system_fee_percentage > 0)
                            <div class="mb-6 p-4 rounded-xl bg-primary-50 border border-primary-100">
                                <div class="text-xs font-bold text-primary-600 uppercase tracking-widest mb-1">Biaya
                                    Maintenance</div>
                                <div class="text-lg font-black text-primary-700">{{ $plan->system_fee_percentage }}%
                                    <span class="text-xs font-medium text-primary-500">per transaksi sukses</span></div>
                            </div>
                        @endif

                        <div class="mb-10 flex-grow">
                            <p class="text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider">Modul & Fitur:</p>
                            <ul class="space-y-4">
                                @foreach ($allFeatures as $featureKey => $label)
                                    @php $isEnabled = $plan->hasFeature($featureKey); @endphp
                                    <li
                                        class="flex items-center gap-3 {{ $isEnabled ? 'text-slate-600' : 'text-slate-400' }}">
                                        @if ($isEnabled)
                                            <i class="fas fa-check-circle text-green-500"></i>
                                        @else
                                            <i class="fas fa-times-circle text-slate-300"></i>
                                        @endif
                                        <span
                                            class="text-sm {{ !$isEnabled ? 'line-through opacity-70' : '' }}">{{ $label }}</span>
                                    </li>
                                @endforeach

                                <li class="pt-4 border-t border-slate-50"></li>

                                @foreach ($plan->limits as $limit => $value)
                                    <li class="flex items-center gap-3 text-slate-600">
                                        <i class="fas fa-check-circle text-primary-500"></i>
                                        <span class="text-xs font-bold uppercase tracking-tight">
                                            {{ $value == -1 ? 'Unlimited' : $value }}
                                            {{ ucwords(str_replace('_', ' ', $limit)) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <a href="{{ route('central.register', ['plan' => $plan->slug]) }}"
                            class="w-full py-4 px-6 rounded-2xl text-center font-bold transition-all {{ $plan->slug === 'pro' ? 'bg-primary-600 text-white shadow-lg shadow-primary-200 hover:bg-primary-700' : 'bg-slate-100 text-slate-800 hover:bg-slate-200' }}">
                            Pilih Paket Ini
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-32 bg-white">
        <div class="container mx-auto px-6 max-w-4xl">
            <h3 class="text-3xl font-extrabold text-center text-slate-900 mb-16">Pertanyaan yang Sering Diajukan</h3>

            <div class="space-y-6">
                @foreach($settings['faqs'] as $faq)
                    <div class="p-6 rounded-2xl bg-slate-50">
                        <h5 class="font-bold text-lg mb-2">{{ $faq['q'] }}</h5>
                        <p class="text-slate-600">{{ $faq['a'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-20">
        <div class="container mx-auto px-6">
            <div
                class="bg-primary-600 rounded-[3rem] p-12 md:p-20 text-center text-white shadow-2xl shadow-primary-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/10 rounded-full -ml-32 -mb-32"></div>

                <h3 class="text-3xl md:text-5xl font-extrabold mb-8 relative z-10">{{ $settings['cta_title'] }}</h3>
                <p class="text-xl text-primary-100 mb-12 max-w-2xl mx-auto relative z-10">{{ $settings['cta_subtitle'] }}</p>
                <a href="#pricing"
                    class="inline-block bg-white text-primary-600 font-extrabold px-12 py-5 rounded-full shadow-lg shadow-black/10 transition-all hover:-translate-y-1 relative z-10">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </section>
</div>

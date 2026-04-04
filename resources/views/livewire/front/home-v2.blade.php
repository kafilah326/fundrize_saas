{{--
  HOME TEMPLATE: v2
  ========================
  DATA CONTRACT — All home templates receive these Livewire public properties:
    $foundation      → FoundationSetting model (name, tagline, logo, favicon, about, vision, mission, address, phone, email, social_media, focus_areas)
    $banners         → Collection<Banner> — active banners for 'home' placement, ordered by priority asc
    $featuredPrograms→ Collection<Program> — up to 5 active + featured programs (latest first)
    $otherPrograms   → Collection<Program> — up to 5 latest active programs
    $categories      → Collection<Category> — all active categories
    $akads           → Collection<AkadType> — all active akad types

  NAMING CONVENTION: home-{slug}.blade.php
  ROOT ELEMENT: Must keep identical root <div> wrapper as other templates (Livewire/Alpine morphing requirement)

  To add a new template:
    1. Create resources/views/livewire/front/home-{newslug}.blade.php
    2. Add <option value="{{ newslug }}">Label</option> to admin/homepage-template.blade.php dropdown
    3. Run php artisan view:clear
--}}
<div>
    <style>
        ::-webkit-scrollbar {
            display: none;
        }

        .carousel-container {
            scroll-snap-type: x mandatory;
        }

        .carousel-item {
            scroll-snap-align: start;
        }

        .progress-bar {
            transition: width 0.3s ease;
        }
    </style>

    <header id="header" class="sticky top-0 bg-white shadow-sm z-50">
        <div class="flex items-center justify-between px-4 py-3 w-full">
            <div class="flex items-center gap-3">
                <img src="{{ $foundation->logo }}" alt="Logo" class="w-36 h-auto object-contain">
            </div>
            <a href="{{ route('search.index') }}" wire:navigate
                class="w-10 h-10 flex items-center justify-center text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-full transition">
                <i class="fa-solid fa-magnifying-glass"></i>
            </a>
        </div>
    </header>

    <main class=" pb-20">

        <section id="hero-slider" class="relative w-full aspect-video overflow-hidden" x-data="{
            currentIndex: 0,
            itemsCount: {{ $banners->count() }},
            init() {
                if (this.itemsCount > 1) {
                    setInterval(() => {
                        this.next();
                    }, 5000);
                }
            },
            next() {
                this.currentIndex = (this.currentIndex + 1) % this.itemsCount;
                this.$refs.carousel.scrollTo({
                    left: this.$refs.carousel.clientWidth * this.currentIndex,
                    behavior: 'smooth'
                });
            }
        },
        next() {
            this.currentIndex = (this.currentIndex + 1) % this.itemsCount;
            this.$refs.carousel.scrollTo({
                left: this.$refs.carousel.clientWidth * this.currentIndex,
                behavior: 'smooth'
            });
        }
    }">
        <div x-ref="carousel" class="carousel-container flex overflow-x-auto snap-x snap-mandatory h-full" @scroll="currentIndex = Math.round($event.target.scrollLeft / $event.target.clientWidth)">
            @foreach($banners as $banner)
            <div class="carousel-item min-w-full h-full relative">
                <img class="w-full h-full object-cover" src="{{ Storage::url($banner->image) }}" alt="{{ $banner->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-dark via-dark/60 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 md:p-8">
                    <div class="w-full">
                        @if($banner->badge_text)
                            <span class="inline-block bg-primary text-white text-xs font-semibold px-3 py-1 rounded-full mb-2">{{ $banner->badge_text }}</span>
                        @endif
                        <h2 class="text-white font-bold text-xl sm:text-2xl md:text-3xl mb-2">{{ $banner->title }}</h2>
                        {{-- @if($banner->description)
                            <div class="text-slate-200 text-sm mb-4 max-w-md">{!! $banner->description !!}</div>
                        @endif --}}
                        @if($banner->link)
                        <a href="{{ $banner->link }}" class="inline-block bg-primary hover:bg-primary text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition">
                            {{ $banner->button_text ?? 'Lihat Detail' }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            @foreach($banners as $index => $banner)
            <span class="w-2 h-2 rounded-full transition-colors" :class="currentIndex === {{ $index }} ? 'bg-white' : 'bg-white/50'"></span>
            @endforeach
        </div>
    </section>

    <section id="pilih-akad" class="px-4 py-6 w-full">
        <h3 class="text-dark font-bold text-lg mb-4">Pilih Akad</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach($akads as $akad)
            <a href="{{ route('program.index', ['akad' => $akad->slug]) }}" class="bg-white border-2 border-slate-200 hover:border-primary rounded-xl p-4 transition flex flex-col items-center space-y-2 min-h-[110px]">
                <div class="w-12 h-12 from-teal-500 to-teal-600 rounded-full flex items-center justify-center">
                    @if($akad->icon)
                        <i class="{{ $akad->icon }} text-white text-xl"></i>
                    @else
                        <i class="fa-solid fa-hand-holding-dollar text-white text-xl"></i>
                    @endif
                </div>
                <span class="text-dark font-semibold text-sm">{{ $akad->name }}</span>
            </a>
            @endforeach
        </div>
    </section>

    <section id="program-unggulan" class="px-4 py-6 w-full">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-dark font-bold text-lg">Program Unggulan</h3>
            <a href="{{ route('program.index') }}" class="text-primary text-sm font-semibold hover:text-primary">Lihat Semua</a>
        </div>
        <div class="flex overflow-x-auto space-x-4 pb-2 -mx-4 px-4 carousel-container">
            @foreach($featuredPrograms as $program)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden min-w-[280px] sm:min-w-[320px] border border-slate-100">
            <a href="{{ route('program.detail', $program->slug) }}" class="block">
                    <div class="h-40 overflow-hidden relative">
                        <img class="w-full h-full object-cover" src="{{ Storage::url($program->image) }}" alt="{{ $program->title }}">
                        @if($program->category)
                        <span class="absolute top-3 left-3 inline-block bg-white/90 text-primary text-xs font-semibold px-2 py-1 rounded backdrop-blur-sm shadow-sm">{{ $program->category->name }}</span>
                        @endif
                    </div>
                </a>
                <div class="p-4">
                    <a href="{{ route('program.detail', $program->slug) }}">
                        <h4 class="text-dark font-bold text-base mb-2 line-clamp-2 hover:text-primary transition-colors">{{ $program->title }}</h4>
                    </a>
                    
                    <div class="mb-3">
                        <div class="flex justify-between text-xs text-slate-600 mb-1">
                            <span>Terkumpul</span>
                            <span class="font-semibold">{{ $program->progress_percentage }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                            <div class="progress-bar bg-primary h-full rounded-full" style="width: {{ $program->progress_percentage }}%"></div>
                        </div>
                        <div class="flex justify-between mt-2">
                            <span class="text-dark font-bold text-sm">Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</span>
                            @if($program->target_amount > 0)
                            <span class="text-slate-500 text-xs">dari Rp {{ number_format($program->target_amount, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between text-xs text-slate-500 mb-3 border-t border-slate-50 pt-3">
                        <div class="flex items-center space-x-1">
                            <i class="fa-regular fa-clock"></i>
                            <span>{{ $program->days_left > 0 ? $program->days_left . ' hari lagi' : 'Tanpa Batas' }}</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i class="fa-solid fa-users"></i>
                            <span>{{ $program->donors_count ?? 0 }} donatur</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('program.detail', $program->slug) }}" class="block w-full text-center bg-primary/5 hover:bg-primary text-primary hover:text-white font-semibold py-2.5 rounded-lg text-sm transition-colors border border-primary/20 hover:border-transparent">
                        Donasi Sekarang
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <section id="menu-program" class="px-4 py-6 w-full">
        <h3 class="text-dark font-bold text-lg mb-4">Menu Program</h3>
        <div class="flex overflow-x-auto space-x-2 pb-2 -mx-4 px-4 carousel-container">
            <a href="{{ route('program.index') }}" class="bg-primary text-white font-semibold px-4 py-2 rounded-full text-sm whitespace-nowrap shadow-sm">
                Semua Program
            </a>
            @foreach($categories as $category)
            <a href="{{ route('program.index', ['category' => $category->slug]) }}" class="bg-white border border-slate-200 text-slate-600 hover:border-primary hover:text-primary font-semibold px-4 py-2 rounded-full text-sm whitespace-nowrap transition-colors">
                {{ $category->name }}
            </a>
            @endforeach
        </div>
    </section>

    <section id="program-lainnya" class="px-4 py-6 w-full">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-dark font-bold text-lg">Program Lainnya</h3>
            <a href="{{ route('program.index') }}" class="text-slate-500 hover:text-dark transition-colors">
                <i class="text-lg fa-solid fa-sliders"></i>
            </a>
        </div>
        <div class="space-y-3">
            @foreach($otherPrograms as $program)
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex hover:shadow-md transition-shadow">
                <a href="{{ route('program.detail', $program->slug) }}" class="w-28 h-28 flex-shrink-0 overflow-hidden relative">
                    <img class="w-full h-full object-cover" src="{{ Storage::url($program->image) }}" alt="{{ $program->title }}">
                </a>
                <div class="flex-1 p-3 flex flex-col justify-between">
                    <div>
                        @if($program->category)
                        <span class="inline-block text-primary text-[10px] font-bold uppercase tracking-wider mb-1">{{ $program->category->name }}</span>
                        @endif
                        <a href="{{ route('program.detail', $program->slug) }}">
                            <h4 class="text-dark font-bold text-sm mb-1 line-clamp-2 hover:text-primary transition-colors leading-snug">{{ $program->title }}</h4>
                        </a>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden mb-1.5">
                            <div class="progress-bar from-teal-500 to-teal-400 h-full rounded-full" style="width: {{ $program->progress_percentage }}%"></div>
                        </div>
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="text-dark font-bold text-xs">Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                @foreach ($banners as $index => $banner)
                    <span class="w-2 h-2 rounded-full transition-colors"
                        :class="currentIndex === {{ $index }} ? 'bg-white' : 'bg-white/50'"></span>
                @endforeach
            </div>
        </section>

        <section id="pilih-akad" class="px-4 py-6 w-full">
            <h3 class="text-dark font-bold text-lg mb-4">Pilih Akad</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach ($akads as $akad)
                    @if (!in_array(strtolower($akad->name), ['qurban', 'zakat']))
                        <a href="{{ route('program.index', ['akad' => $akad->slug]) }}"
                            class="bg-white border-2 border-slate-200 hover:border-primary rounded-xl p-4 transition flex flex-col items-center space-y-2 min-h-[110px]">
                            <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center">
                                @if ($akad->icon)
                                    <i class="fa-solid {{ $akad->icon }} text-primary text-xl"></i>
                                @else
                                    <i class="fa-solid fa-hand-holding-dollar text-primary text-xl"></i>
                                @endif
                            </div>
                            <span class="text-dark font-semibold text-sm">{{ $akad->name }}</span>
                        </a>
                    @endif
                @endforeach

                <!-- Explicit Zakat Link -->
                <a href="{{ route('zakat.index') }}" wire:navigate
                    class="bg-white border-2 border-slate-200 hover:border-primary rounded-xl p-4 transition flex flex-col items-center space-y-2 min-h-[110px]">
                    <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-scale-balanced text-primary text-xl"></i>
                    </div>
                    <span class="text-dark font-semibold text-sm">Zakat</span>
                </a>

                <!-- Explicit Qurban Link -->
                <a href="{{ route('qurban.index') }}" wire:navigate
                    class="bg-white border-2 border-slate-200 hover:border-primary rounded-xl p-4 transition flex flex-col items-center space-y-2 min-h-[110px]">
                    <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-cow text-primary text-xl"></i>
                    </div>
                    <span class="text-dark font-semibold text-sm">Qurban</span>
                </a>
            </div>
        </section>

        <section id="program-unggulan" class="px-4 py-6 w-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-dark font-bold text-lg">Program Unggulan</h3>
                <a href="{{ route('program.index') }}"
                    class="text-primary text-sm font-semibold hover:text-primary">Lihat Semua</a>
            </div>
            <div class="flex overflow-x-auto space-x-4 pb-2 -mx-4 px-4 carousel-container">
                @foreach ($featuredPrograms as $program)
                    <div
                        class="bg-white rounded-xl shadow-sm overflow-hidden min-w-[280px] sm:min-w-[320px] border border-slate-100">
                        <a href="{{ route('program.detail', $program->slug) }}" class="block">
                            <div class="h-40 overflow-hidden relative">
                                <img class="w-full h-full object-cover" src="{{ $program->image }}"
                                    alt="{{ $program->title }}">
                                @if ($program->category)
                                    <span
                                        class="absolute top-3 left-3 inline-block bg-white/90 text-primary text-xs font-semibold px-2 py-1 rounded backdrop-blur-sm shadow-sm">{{ $program->category->name }}</span>
                                @endif
                            </div>
                        </a>
                        <div class="p-4">
                            <a href="{{ route('program.detail', $program->slug) }}">
                                <h4
                                    class="text-dark font-bold text-base mb-2 line-clamp-2 hover:text-primary transition-colors">
                                    {{ $program->title }}</h4>
                            </a>

                            <div class="mb-3">
                                <div class="flex justify-between text-xs text-slate-600 mb-1">
                                    <span>Terkumpul</span>
                                    <span class="font-semibold">{{ $program->progress_percentage }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                    <div class="progress-bar bg-primary h-full rounded-full"
                                        style="width: {{ $program->progress_percentage }}%"></div>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <span class="text-dark font-bold text-sm">Rp
                                        {{ number_format($program->collected_amount, 0, ',', '.') }}</span>
                                    @if ($program->target_amount > 0)
                                        <span class="text-slate-500 text-xs">dari Rp
                                            {{ number_format($program->target_amount, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-between text-xs text-slate-500 mb-3 border-t border-slate-50 pt-3">
                                <div class="flex items-center space-x-1">
                                    <i class="fa-regular fa-clock"></i>
                                    <span>{{ $program->days_left > 0 ? $program->days_left . ' hari lagi' : 'Tanpa Batas' }}</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <i class="fa-solid fa-users"></i>
                                    <span>{{ $program->donors_count ?? 0 }} donatur</span>
                                </div>
                            </div>

                            <a href="{{ route('program.detail', $program->slug) }}"
                                class="block w-full text-center bg-primary/5 hover:bg-primary text-primary hover:text-white font-semibold py-2.5 rounded-lg text-sm transition-colors border border-primary/20 hover:border-transparent">
                                Donasi Sekarang
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section id="menu-program" class="px-4 py-6 w-full">
            <h3 class="text-dark font-bold text-lg mb-4">Menu Program</h3>
            <div class="flex overflow-x-auto space-x-2 pb-2 -mx-4 px-4 carousel-container">
                <a href="{{ route('program.index') }}"
                    class="bg-primary text-white font-semibold px-4 py-2 rounded-full text-sm whitespace-nowrap shadow-sm">
                    Semua Program
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('program.index', ['category' => $category->slug]) }}"
                        class="bg-white border border-slate-200 text-slate-600 hover:border-primary hover:text-primary font-semibold px-4 py-2 rounded-full text-sm whitespace-nowrap transition-colors">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </section>

        <section id="program-lainnya" class="px-4 py-6 w-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-dark font-bold text-lg">Program Lainnya</h3>
                <a href="{{ route('program.index') }}" class="text-slate-500 hover:text-dark transition-colors">
                    <i class="text-lg fa-solid fa-sliders"></i>
                </a>
            </div>
            <div class="space-y-3">
                @foreach ($otherPrograms as $program)
                    <div
                        class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex hover:shadow-md transition-shadow">
                        <a href="{{ route('program.detail', $program->slug) }}"
                            class="w-28 h-28 flex-shrink-0 overflow-hidden relative">
                            <img class="w-full h-full object-cover" src="{{ $program->image }}"
                                alt="{{ $program->title }}">
                        </a>
                        <div class="flex-1 p-3 flex flex-col justify-between">
                            <div>
                                @if ($program->category)
                                    <span
                                        class="inline-block text-primary text-[10px] font-bold uppercase tracking-wider mb-1">{{ $program->category->name }}</span>
                                @endif
                                <a href="{{ route('program.detail', $program->slug) }}">
                                    <h4
                                        class="text-dark font-bold text-sm mb-1 line-clamp-2 hover:text-primary transition-colors leading-snug">
                                        {{ $program->title }}</h4>
                                </a>
                            </div>

                            <div class="mt-auto">
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden mb-1.5">
                                    <div class="progress-bar from-teal-500 to-teal-400 h-full rounded-full"
                                        style="width: {{ $program->progress_percentage }}%"></div>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div>
                                        <span class="text-dark font-bold text-xs">Rp
                                            {{ number_format($program->collected_amount, 0, ',', '.') }}</span>
                                    </div>
                                    <span
                                        class="text-slate-500 text-[10px] font-medium bg-slate-50 px-1.5 py-0.5 rounded">{{ $program->progress_percentage }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('program.index') }}"
                class="block text-center w-full mt-4 bg-white border border-slate-200 hover:border-primary text-slate-600 hover:text-primary font-semibold py-3 rounded-lg text-sm transition-colors shadow-sm">
                Muat Lebih Banyak Program
            </a>
        </section>

        <section id="transparansi" class="px-4 py-6 w-full">
            <h3 class="text-dark font-bold text-lg mb-4">Transparansi &amp; Akuntabilitas</h3>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('report.index') }}" wire:navigate
                    class="relative bg-primary rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 block group overflow-hidden">
                    <!-- Decorative backround elements -->
                    <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-white opacity-10"></div>
                    <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 rounded-full bg-white opacity-5"></div>

                    <div class="relative z-10 flex items-center justify-between mb-4">
                        <div
                            class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md group-hover:bg-white/30 transition-colors">
                            <i class="text-2xl fa-solid fa-chart-line"></i>
                        </div>
                        <div
                            class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm group-hover:bg-white/20 transition-all group-hover:translate-x-1">
                            <i class="text-lg fa-solid fa-arrow-right"></i>
                        </div>
                    </div>
                    <div class="relative z-10">
                        <h4 class="font-bold text-lg mb-2">Laporan Program & Keuangan</h4>
                        <p class="text-sm text-teal-50 mb-4 opacity-90 leading-relaxed max-w-[280px]">Pantau penyaluran
                            donasi dan laporan keuangan secara transparan.</p>
                        <div
                            class="inline-flex items-center px-2.5 py-1 rounded-full bg-white/20 text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-300 mr-2 animate-pulse"></span>
                            Update Berkala
                        </div>
                    </div>
                </a>
            </div>
        </section>

        <section id="about-yayasan" class="px-4 py-6 w-full">
            <h3 class="text-dark font-bold text-lg mb-4">Tentang {{ $foundation->name }}</h3>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-start space-x-3 mb-4">
                    <div
                        class="w-16 h-16 bg-slate-50 rounded-xl flex items-center justify-center flex-shrink-0 border border-slate-100 overflow-hidden p-2">
                        @if ($foundation->favicon)
                            <img src="{{ $foundation->favicon }}" alt="Logo" class="w-full object-contain">
                        @else
                            <i class="fa-solid fa-hand-holding-heart text-primary text-2xl"></i>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-dark font-bold text-base mb-1">{{ $foundation->name }}</h4>
                        <p class="text-slate-500 text-xs leading-relaxed"><i
                                class="fa-solid fa-location-dot mr-1"></i>
                            {{ Str::limit($foundation->address ?? '-', 60) }}</p>
                    </div>
                </div>
                @if ($foundation->about)
                    <p class="text-slate-600 text-sm leading-relaxed mb-4 line-clamp-4">
                        {{ strip_tags($foundation->about) }}
                    </p>
                @endif
                <div class="flex flex-wrap gap-2 mb-4">
                    <span
                        class="bg-primary/5 text-primary border border-primary/20 text-[10px] font-semibold px-2.5 py-1 rounded-full uppercase tracking-wide">Terdaftar
                        & Berizin</span>
                    <span
                        class="bg-dark/5 text-dark border border-dark/20 text-[10px] font-semibold px-2.5 py-1 rounded-full uppercase tracking-wide">Transparan</span>
                </div>
            </div>
        </section>

        <section id="cta-banner" class="px-4 py-6 w-full mb-4">
            <div class="bg-primary rounded-2xl p-8 text-white text-center shadow-lg relative overflow-hidden">
                <!-- Decorative circles -->
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10"></div>
                <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-24 h-24 rounded-full bg-white opacity-10"></div>

                <div class="relative z-10">
                    <h3 class="font-bold text-2xl mb-3">
                        {{ $foundation->tagline ?? 'Mulai Berbagi Kebaikan Hari Ini' }}</h3>
                    <p class="text-white/90 text-sm mb-6 max-w-md mx-auto opacity-90">Setiap donasi Anda membawa
                        perubahan nyata bagi mereka yang membutuhkan</p>
                    <a href="{{ route('program.index') }}"
                        class="inline-block bg-white text-primary hover:bg-slate-50 font-bold px-8 py-3.5 rounded-xl text-sm transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                        Mulai Berdonasi <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </section>

    </main>

    <x-bottom-nav />
</div>

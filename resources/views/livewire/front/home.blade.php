<div>
    <header id="header" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ $foundation->logo }}" alt="Logo" class="w-36 h-auto object-contain">
            </div>
            <a href="{{ route('search.index') }}" wire:navigate
                class="w-9 h-9 flex items-center justify-center bg-light rounded-full">
                <i class="fa-solid fa-magnifying-glass text-gray-600 text-sm"></i>
            </a>
        </div>
    </header>

    <!-- PWA Install Banner -->
    <div x-data="{
        installPrompt: null,
        showInstall: false,
        init() {
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                this.installPrompt = e;
                this.showInstall = true;
            });
        },
        installApp() {
            if (this.installPrompt) {
                this.installPrompt.prompt();
                this.installPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        this.showInstall = false;
                    }
                    this.installPrompt = null;
                });
            }
        }
    }" x-show="showInstall" x-transition
        class="bg-primary/10 px-4 py-3 sticky top-[60px] z-40 flex items-center justify-between gap-3 border-b border-primary/20"
        style="display: none;">

        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                <img src="{{ $foundation->logo ?? asset('icons/icon-192.png') }}" class="w-8 h-8 object-contain">
            </div>
            <div>
                <h4 class="text-sm font-bold text-dark">Pasang Aplikasi</h4>
                <p class="text-xs text-gray-600">Akses lebih cepat & ringan</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button @click="showInstall = false" class="text-xs text-gray-500 font-medium px-2 py-1.5">Nanti</button>
            <button @click="installApp()"
                class="bg-primary text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-sm hover:bg-primary-dark">
                Install
            </button>
        </div>
    </div>

    <main id="main-content" class="pb-20">
        <!-- Campaign Slider -->
        @if ($banners->isNotEmpty())
            <section id="campaign-slider" class="bg-white px-4 py-4" x-data="{
                currentSlide: 0,
                totalSlides: {{ $banners->count() }},
                touchStartX: 0,
                touchEndX: 0,
                interval: null,
                nextSlide() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                },
                prevSlide() {
                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                },
                startAutoSlide() {
                    this.interval = setInterval(() => this.nextSlide(), 4000);
                },
                stopAutoSlide() {
                    clearInterval(this.interval);
                },
                handleTouchStart(e) {
                    this.touchStartX = e.changedTouches[0].screenX;
                    this.stopAutoSlide();
                },
                handleTouchEnd(e) {
                    this.touchEndX = e.changedTouches[0].screenX;
                    if (this.touchStartX - this.touchEndX > 50) {
                        this.nextSlide();
                    }
                    if (this.touchEndX - this.touchStartX > 50) {
                        this.prevSlide();
                    }
                    this.startAutoSlide();
                }
            }" x-init="startAutoSlide()"
                @touchstart="handleTouchStart($event)" @touchend="handleTouchEnd($event)">

                <div class="relative overflow-hidden rounded-2xl">
                    <div id="slider-container" class="flex transition-transform duration-500"
                        :style="'transform: translateX(-' + (currentSlide * 100) + '%)'">

                        @foreach ($banners as $banner)
                            <!-- Slide -->
                            <div class="min-w-full">
                                <div class="relative aspect-video overflow-hidden rounded-2xl">
                                    <img src="{{ $banner->image }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-4">

                                        @if ($banner->link_url)
                                            <a href="{{ $banner->link_url }}"
                                                class="bg-primary text-white px-5 py-2 rounded-full text-xs font-semibold inline-block">
                                                {{ $banner->cta_text ?: 'Selengkapnya' }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-center gap-1.5 mt-3">
                        <template x-for="i in totalSlides">
                            <div class="slider-dot w-2 h-2 rounded-full transition-colors duration-300"
                                :class="(i - 1) === currentSlide ? 'bg-primary' : 'bg-gray-300'"></div>
                        </template>
                    </div>
                </div>
            </section>
        @endif

        <!-- Akad Section -->
        <section id="akad-section" class="bg-white px-4 py-5 mt-2">
            <h3 class="text-sm font-bold text-dark mb-4">Pilih Akad</h3>
            <div class="grid grid-cols-4 gap-3">
                @foreach ($akads as $akad)
                    @if (strtolower($akad->name) !== 'qurban')
                        <a href="{{ route('program.index', ['akad' => $akad->slug]) }}" wire:navigate
                            class="flex flex-col items-center gap-2">
                            <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center">
                                <i class="fa-solid {{ $akad->icon }} text-primary text-xl"></i>
                            </div>
                            <span class="text-xs font-medium text-dark">{{ $akad->name }}</span>
                        </a>
                    @endif
                @endforeach

                <!-- Explicit Qurban Link -->
                <a href="{{ route('qurban.index') }}" wire:navigate class="flex flex-col items-center gap-2">
                    <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-cow text-primary text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-dark">Qurban</span>
                </a>
            </div>
        </section>

        <!-- Featured Programs -->
        @if ($featuredPrograms->isNotEmpty())
            <section id="featured-programs" class="bg-white px-4 py-5 mt-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-dark">Program Unggulan</h3>
                    <a href="{{ route('program.index') }}" wire:navigate
                        class="text-xs text-primary font-semibold">Lihat Semua</a>
                </div>
                <div class="flex gap-3 overflow-x-auto hide-scrollbar pb-2">
                    @foreach ($featuredPrograms as $program)
                        <a href="{{ route('program.detail', $program->slug) }}" wire:navigate
                            class="min-w-[280px] bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm p-3 gap-3 flex flex-row hover:shadow-md transition-shadow">
                            <!-- Image Left -->
                            <div class="w-24 h-24 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                                <img src="{{ $program->image }}" class="w-full h-full object-cover">
                            </div>

                            <!-- Content Right -->
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <h4 class="font-semibold text-sm text-dark mb-1 line-clamp-2">{{ $program->title }}
                                    </h4>
                                    <p class="text-xs font-bold text-dark mb-1">Rp
                                        {{ number_format($program->collected_amount, 0, ',', '.') }}</p>
                                </div>

                                <div>
                                    @if ($program->target_amount)
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                            <div class="bg-primary h-1.5 rounded-full"
                                                style="width: {{ $program->progress }}%"></div>
                                        </div>
                                        <div class="flex justify-between items-center text-[10px] text-gray-500">
                                            <span>Terkumpul</span>
                                            <span>{{ $program->progress }}%</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1 text-primary">
                                            <i class="fa-solid fa-infinity text-xs"></i>
                                            <span class="text-[10px] font-bold">Unlimited</span>
                                        </div>
                                        <p class="text-[10px] text-gray-500">Donasi Terkumpul</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Program Menu -->
        <section id="program-menu" class="bg-white px-4 py-5 mt-2">
            <h3 class="text-sm font-bold text-dark mb-4">Menu Program</h3>
            <div class="grid grid-cols-4 gap-3">

                @foreach ($categories as $category)
                    <a href="{{ route('program.index', ['category' => $category->slug]) }}" wire:navigate
                        class="flex flex-col items-center gap-2">
                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                            <i class="fa-solid {{ $category->icon }} text-gray-600 text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-medium text-gray-600 text-center leading-tight">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- New Programs -->
        @if ($otherPrograms->isNotEmpty())
            <section id="new-programs" class="bg-white px-4 py-5 mt-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-dark">Program Terbaru</h3>
                    <a href="{{ route('program.index') }}" wire:navigate
                        class="text-xs text-primary font-semibold">Lihat Semua</a>
                </div>
                <div class="flex flex-col gap-3">
                    @foreach ($otherPrograms as $program)
                        <a href="{{ route('program.detail', $program->slug) }}" wire:navigate
                            class="flex bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm p-3 gap-3 hover:shadow-md transition-shadow">
                            <!-- Image Left -->
                            <div class="w-24 h-24 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                                <img src="{{ $program->image }}" class="w-full h-full object-cover">
                            </div>

                            <!-- Content Right -->
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <h4 class="font-semibold text-sm text-dark mb-1 line-clamp-2">
                                        {{ $program->title }}</h4>
                                    <p class="text-xs font-bold text-dark mb-1">Rp
                                        {{ number_format($program->collected_amount, 0, ',', '.') }}</p>
                                </div>

                                <div>
                                    @if ($program->target_amount)
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                            <div class="bg-primary h-1.5 rounded-full"
                                                style="width: {{ $program->progress }}%"></div>
                                        </div>
                                        <div class="flex justify-between items-center text-[10px] text-gray-500">
                                            <span>Terkumpul</span>
                                            <span>{{ $program->progress }}%</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1 text-primary">
                                            <i class="fa-solid fa-infinity text-xs"></i>
                                            <span class="text-[10px] font-bold">Unlimited</span>
                                        </div>
                                        <p class="text-[10px] text-gray-500">Donasi Terkumpul</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif



        <!-- About Foundation -->
        <section id="about-foundation" class="bg-white px-4 py-5 mt-2 mb-20">
            <h3 class="text-sm font-bold text-dark mb-3">Tentang Yayasan</h3>
            <p class="text-xs text-gray-600 leading-relaxed mb-3 line-clamp-3">
                {{ Str::limit(strip_tags($foundation->about), 200) }}</p>
            <a href="{{ route('foundation.profile') }}" wire:navigate
                class="w-full bg-primary text-white py-3 rounded-xl text-sm font-semibold block text-center">Lihat
                Selengkapnya</a>
        </section>
    </main>

    <x-bottom-nav active="home" />
</div>

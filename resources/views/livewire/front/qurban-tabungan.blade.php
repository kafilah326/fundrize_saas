<div x-data="{ showModal: false, agreed: false }">
    <x-page-header title="Tabungan Qurban" :showBack="true" />

    <main id="main-content" class="pb-24">
        <!-- Banner Section -->
        <section id="banner-section" class="bg-white">
            <div class="h-[200px] overflow-hidden relative">
                <img src="{{ $banner && $banner->image ? $banner->image : 'https://storage.googleapis.com/uxpilot-auth.appspot.com/Fi1siCSktTSfwv8Em1pqs4D0Vek2%2Fcff693df-a22b-4f74-b519-bb1fa7cac475.png' }}" alt="{{ $settings->title }}" class="w-full h-full object-cover">
                @if($banner && $banner->title)
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex flex-col justify-end p-4">
                    <h3 class="text-white font-bold text-lg mb-1">{{ $banner->title }}</h3>
                    @if($banner->description)
                    <div class="text-white/90 text-xs line-clamp-2">{!! $banner->description !!}</div>
                    @endif
                </div>
                @endif
            </div>
        </section>

        <!-- Title Section -->
        <section id="title-section" class="bg-white px-4 py-5">
            <h2 class="text-xl font-bold text-dark mb-2">{{ $settings->title }}</h2>
            @if($settings->subtitle)
            <p class="text-sm text-gray-600">{{ $settings->subtitle }}</p>
            @endif
        </section>

        <!-- Benefits Section -->
        @if($settings->benefits && count($settings->benefits) > 0)
        <section id="benefits-section" class="bg-white px-4 py-5 border-t border-gray-100">
            <h3 class="text-sm font-bold text-dark mb-4">Keunggulan Program</h3>
            <div class="space-y-3">
                @foreach($settings->benefits as $benefit)
                <div class="flex items-center gap-3">
                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check text-green-600 text-xs"></i>
                    </div>
                    <span class="text-sm text-dark">{{ $benefit }}</span>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Description Section -->
        @if($settings->description)
        <section id="description-section" class="bg-white px-4 py-5 border-t border-gray-100">
            <h3 class="text-sm font-bold text-dark mb-3">Tentang Program</h3>
            <div class="text-sm text-gray-600 leading-relaxed space-y-3 rich-text-content">
                {!! $settings->description !!}
            </div>
        </section>
        @endif

        <!-- Akad Section -->
        @if($settings->akad_title || $settings->akad_description)
        <section id="akad-section" class="bg-white px-4 py-5 border-t border-gray-100">
            <div class="flex items-center gap-2 mb-2">
                <h3 class="text-sm font-bold text-dark">{{ $settings->akad_title ?? 'Informasi Akad' }}</h3>
                <button class="w-5 h-5 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-info text-gray-500 text-xs"></i>
                </button>
            </div>
            <p class="text-sm text-gray-600">{{ $settings->akad_description }}</p>
        </section>
        @endif
    </main>

    <!-- Sticky CTA -->
    <div id="sticky-cta" class="fixed bottom-0 left-0 right-0 max-w-[460px] mx-auto bg-white border-t border-gray-200 p-4 z-50">
        <button @click="showModal = true" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-semibold">
            {{ $banner->cta_text ?? 'Daftar Sekarang' }}
        </button>
    </div>

    <!-- Terms Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-end justify-center" style="display: none;">
        <!-- Backdrop -->
        <div @click="showModal = false" x-transition:enter="transition opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition opacity duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

        <!-- Modal Content -->
        <div x-transition:enter="transition transform duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition transform duration-300" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="bg-white w-full max-w-[460px] rounded-t-2xl max-h-[80vh] overflow-hidden flex flex-col relative z-10">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-bold text-dark">Syarat & Ketentuan</h3>
                <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full">
                    <i class="fa-solid fa-xmark text-gray-600 text-sm"></i>
                </button>
            </div>
            
            <div class="px-4 py-4 overflow-y-auto max-h-[50vh]">
                <div class="space-y-4">
                    @if($settings->terms && count($settings->terms) > 0)
                        @foreach($settings->terms as $term)
                        <div>
                            <h4 class="font-semibold text-sm text-dark mb-2">{{ $term['title'] }}</h4>
                            <p class="text-xs text-gray-600">{{ $term['description'] }}</p>
                        </div>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-500 text-center">Tidak ada syarat & ketentuan khusus.</p>
                    @endif
                </div>
            </div>

            <div class="px-4 py-4 border-t border-gray-200">
                <div class="flex items-start gap-3 mb-4">
                    <input type="checkbox" id="agree-checkbox" x-model="agreed" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary mt-0.5">
                    <label for="agree-checkbox" class="text-xs text-gray-600 leading-relaxed">
                        Saya setuju dengan Syarat & Ketentuan yang berlaku untuk program {{ $settings->title }}
                    </label>
                </div>
                
                <a href="{{ route('qurban.tabungan.checkout') }}" wire:navigate 
                   :class="agreed ? 'bg-primary text-white' : 'bg-gray-300 text-gray-500 pointer-events-none'"
                   class="block w-full py-3 rounded-xl text-sm font-semibold text-center transition-colors">
                    Lanjutkan
                </a>
            </div>
        </div>
    </div>
</div>

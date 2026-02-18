<div x-data="{ showFilter: false }">

    <x-page-header title="Program" :showBack="true">
        <x-slot:actions>
            <a href="{{ route('search.index') }}" wire:navigate
                class="w-9 h-9 flex items-center justify-center bg-light rounded-full">
                <i class="fa-solid fa-magnifying-glass text-gray-600 text-sm"></i>
            </a>
        </x-slot:actions>
    </x-page-header>

    <!-- Compact Filter Bar -->
    <section id="filter-compact" class="bg-white px-4 py-3 border-b border-gray-100">
        <div class="flex items-center gap-2">
            <button @click="showFilter = true" class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-full">
                <i class="fa-solid fa-sliders text-dark text-sm"></i>
                <span class="text-sm font-semibold text-dark">Filter</span>
            </button>

            <div class="flex-1 flex items-center gap-2 overflow-x-auto hide-scrollbar">
                @if (count($selectedAkad) > 0 || count($selectedKategori) > 0)
                    <span class="text-sm text-gray-600 whitespace-nowrap">
                        {{ count($selectedAkad) + count($selectedKategori) }} filter aktif
                    </span>
                @endif
            </div>

            @if (count($selectedAkad) > 0 || count($selectedKategori) > 0)
                <button wire:click="resetFilter"
                    class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full flex-shrink-0">
                    <i class="fa-solid fa-xmark text-gray-600 text-sm"></i>
                </button>
            @endif
        </div>
    </section>

    <!-- Filter Modal (Bottom Sheet) -->
    <div x-show="showFilter" class="fixed inset-0 z-[60] flex items-end justify-center" style="display: none;">
        <!-- Backdrop -->
        <div @click="showFilter = false" x-transition:enter="transition opacity duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition opacity duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50"></div>

        <!-- Sheet -->
        <div x-transition:enter="transition transform duration-300" x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0" x-transition:leave="transition transform duration-300"
            x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
            class="bg-white w-full max-w-[460px] rounded-t-3xl max-h-[85vh] overflow-hidden flex flex-col relative z-10">
            <div class="sticky top-0 bg-white border-b border-gray-100 px-4 py-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-dark">Filter Program</h3>
                <button @click="showFilter = false" class="w-8 h-8 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-gray-600 text-xl"></i>
                </button>
            </div>

            <div class="overflow-y-auto px-4 py-4 pb-24 flex-1">
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-dark mb-3">Akad</h4>
                    <div class="flex flex-wrap gap-2">
                        <button wire:key="akad-semua" wire:click="toggleAkad('semua')"
                            class="px-4 py-2 rounded-full text-sm font-medium border-2 transition-colors {{ empty($selectedAkad) ? 'border-primary bg-orange-50 text-primary' : 'border-gray-200 text-gray-600' }}">
                            Semua
                        </button>
                        @foreach ($akads as $akad)
                            <button wire:key="akad-{{ $akad->id }}" wire:click="toggleAkad('{{ $akad->slug }}')"
                                class="px-4 py-2 rounded-full text-sm font-medium border-2 transition-colors {{ in_array($akad->slug, $selectedAkad) ? 'border-primary bg-orange-50 text-primary' : 'border-gray-200 text-gray-600' }}">
                                {{ $akad->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-semibold text-dark mb-3">Kategori</h4>
                    <div class="flex flex-wrap gap-2">
                        <button wire:key="cat-semua" wire:click="toggleKategori('semua')"
                            class="px-4 py-2 rounded-full text-sm font-medium border-2 transition-colors {{ empty($selectedKategori) ? 'border-primary bg-orange-50 text-primary' : 'border-gray-200 text-gray-600' }}">
                            Semua
                        </button>
                        @foreach ($categories as $cat)
                            <button wire:key="cat-{{ $cat->id }}" wire:click="toggleKategori('{{ $cat->slug }}')"
                                class="px-4 py-2 rounded-full text-sm font-medium border-2 transition-colors {{ in_array($cat->slug, $selectedKategori) ? 'border-primary bg-orange-50 text-primary' : 'border-gray-200 text-gray-600' }}">
                                {{ $cat->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 bg-white border-t border-gray-100 px-4 py-3 flex gap-3">
                <button wire:click="resetFilter"
                    class="flex-1 py-3 bg-gray-100 text-dark rounded-xl text-sm font-semibold">Reset</button>
                <button @click="showFilter = false"
                    class="flex-1 py-3 bg-primary text-white rounded-xl text-sm font-semibold">Tutup</button>
            </div>
        </div>
    </div>

    <main id="main-content" class="pb-20">
        <section id="program-list" class="bg-white px-4 py-4 mt-2">
            <div class="flex items-center justify-between mb-4">
                <p class="text-xs text-gray-500">Menampilkan <span class="font-semibold text-dark">{{ $totalPrograms }}
                        program</span></p>
            </div>
            <div class="space-y-3">
                @foreach ($programs as $program)
                    <!-- Program Item -->
                    <a wire:key="program-{{ $program->id }}" href="{{ route('program.detail', $program->slug) }}" wire:navigate
                        class="flex gap-3 bg-white rounded-xl border border-gray-100 p-2 block">
                        <div class="w-24 h-24 aspect-square flex-shrink-0 overflow-hidden rounded-lg">
                            <img src="{{ $program->image }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 flex flex-col justify-between py-1">
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
                                            :style="{ width: '{{ $program->progress ?? 0 }}%' }"></div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">{{ $program->progress }}% terkumpul</span>
                                        <button class="text-gray-400"><i
                                                class="fa-solid fa-ellipsis-vertical text-xs"></i></button>
                                    </div>
                                @else
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs font-bold text-primary flex items-center gap-1">
                                            <i class="fa-solid fa-infinity"></i> Unlimited
                                        </span>
                                        <button class="text-gray-400"><i
                                                class="fa-solid fa-ellipsis-vertical text-xs"></i></button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($programs->count() < $totalPrograms)
                <button wire:click="loadMore"
                    class="w-full mt-4 py-3 bg-gray-100 text-dark rounded-xl text-sm font-semibold">
                    Muat Lebih Banyak
                </button>
            @endif
        </section>
    </main>

    <x-bottom-nav active="program" />
</div>

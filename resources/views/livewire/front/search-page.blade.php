<div x-data>
    <header id="search-header" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="px-4 py-3 flex items-center gap-3">
            <a href="{{ url()->previous() == url()->current() ? route('home') : url()->previous() }}" wire:navigate class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors">
                <i class="fa-solid fa-arrow-left text-dark text-lg"></i>
            </a>
            <div class="flex-1 relative">
                <input type="text" 
                       wire:model.live.debounce.300ms="query" 
                       placeholder="Cari program donasi..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-gray-100 border-none rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 placeholder-gray-500"
                       x-init="$nextTick(() => $el.focus())">
                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </div>
                @if(!empty($query))
                <button wire:click="$set('query', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 w-5 h-5 flex items-center justify-center">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
                @endif
            </div>
        </div>
    </header>

    <main id="main-content" class="pb-6">
        @if(empty($query))
            <!-- Urgent Programs -->
            <section class="bg-white px-4 py-4 mb-2">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-dark flex items-center gap-2">
                        <i class="fa-solid fa-fire text-orange-500"></i>
                        Program Mendesak
                    </h3>
                </div>
                <div class="space-y-3">
                    @foreach($urgentPrograms as $program)
                    <a href="{{ route('program.detail', $program['slug']) }}" wire:navigate class="flex gap-3 bg-white rounded-xl border border-gray-100 p-2 hover:border-primary/30 transition-colors block">
                        <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
                            <img src="{{ $program['image'] }}" alt="{{ $program['title'] }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 flex flex-col justify-between py-1">
                            <div>
                                <span class="inline-block px-2 py-0.5 bg-red-50 text-red-600 text-[10px] font-bold rounded mb-1.5">Sisa {{ $program['days_left'] }} Hari</span>
                                <h3 class="text-sm font-bold text-dark leading-snug line-clamp-2 mb-2">{{ $program['title'] }}</h3>
                            </div>
                            <div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5 mb-2 overflow-hidden">
                                    <div class="bg-primary h-1.5 rounded-full" style="width: {{ min(($program['collected'] / $program['target']) * 100, 100) }}%"></div>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-[10px] text-gray-500 mb-0.5">Terkumpul</p>
                                        <p class="text-xs font-bold text-primary">Rp {{ number_format($program['collected'] / 1000000, 1, ',', '.') }}jt</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>

            <!-- Featured Programs -->
            <section class="bg-white px-4 py-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-dark flex items-center gap-2">
                        <i class="fa-solid fa-star text-yellow-500"></i>
                        Program Pilihan
                    </h3>
                </div>
                <div class="space-y-3">
                    @foreach($featuredPrograms as $program)
                    <a href="{{ route('program.detail', $program['slug']) }}" wire:navigate class="flex gap-3 bg-white rounded-xl border border-gray-100 p-2 hover:border-primary/30 transition-colors block">
                        <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
                            <img src="{{ $program['image'] }}" alt="{{ $program['title'] }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 flex flex-col justify-between py-1">
                            <div>
                                <span class="inline-block px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded mb-1.5">{{ $program['donor_count'] }} Donatur</span>
                                <h3 class="text-sm font-bold text-dark leading-snug line-clamp-2 mb-2">{{ $program['title'] }}</h3>
                            </div>
                            <div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5 mb-2 overflow-hidden">
                                    <div class="bg-primary h-1.5 rounded-full" style="width: {{ min(($program['collected'] / $program['target']) * 100, 100) }}%"></div>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-[10px] text-gray-500 mb-0.5">Terkumpul</p>
                                        <p class="text-xs font-bold text-primary">Rp {{ number_format($program['collected'] / 1000000, 1, ',', '.') }}jt</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>
        
        @elseif(count($results) > 0)
            <section id="search-results" class="bg-white px-4 py-4">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs text-gray-500">Ditemukan <span class="font-semibold text-dark">{{ count($results) }} program</span></p>
                </div>
                <div class="space-y-3">
                    @foreach($results as $program)
                    <a href="{{ route('program.detail', $program['slug']) }}" wire:navigate class="flex gap-3 bg-white rounded-xl border border-gray-100 p-2 hover:border-primary/30 transition-colors block">
                        <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
                            <img src="{{ $program['image'] }}" alt="{{ $program['title'] }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 flex flex-col justify-between py-1">
                            <div>
                                <span class="inline-block px-2 py-0.5 bg-orange-50 text-primary text-[10px] font-bold rounded mb-1.5">{{ $program['category'] }}</span>
                                <h3 class="text-sm font-bold text-dark leading-snug line-clamp-2 mb-2">{{ $program['title'] }}</h3>
                            </div>
                            <div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5 mb-2 overflow-hidden">
                                    <div class="bg-primary h-1.5 rounded-full" style="width: {{ min(($program['collected'] / $program['target']) * 100, 100) }}%"></div>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-[10px] text-gray-500 mb-0.5">Terkumpul</p>
                                        <p class="text-xs font-bold text-primary">Rp {{ number_format($program['collected'] / 1000000, 1, ',', '.') }}jt</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-gray-500 mb-0.5">Sisa Waktu</p>
                                        <p class="text-xs font-bold text-dark">{{ $program['days_left'] }} Hari</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>
        @else
            <div class="flex flex-col items-center justify-center py-20 px-4 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-file-circle-xmark text-gray-300 text-3xl"></i>
                </div>
                <h3 class="text-base font-bold text-dark mb-1">Program Tidak Ditemukan</h3>
                <p class="text-sm text-gray-500 max-w-[240px]">Coba gunakan kata kunci lain atau periksa ejaan Anda</p>
            </div>
        @endif
    </main>
</div>

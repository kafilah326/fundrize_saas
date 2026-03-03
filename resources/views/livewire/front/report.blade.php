<div>
    <header id="header" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="px-4 py-3 flex items-center gap-3">
            <h1 class="text-base font-bold text-dark flex-1">Laporan</h1>
            <button class="w-9 h-9 flex items-center justify-center bg-light rounded-full">
                <i class="fa-regular fa-circle-question text-dark text-lg"></i>
            </button>
        </div>
    </header>

    <section id="period-selector" class="bg-white px-4 py-3 border-b border-gray-100 sticky top-[52px] z-40">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">Periode Laporan</p>
            <select wire:model.live="period" class="text-sm font-semibold text-dark bg-transparent outline-none cursor-pointer focus:ring-2 focus:ring-primary/20 rounded">
                @for($i = 0; $i < 6; $i++)
                    <option>{{ now()->subMonths($i)->format('F Y') }}</option>
                @endfor
            </select>
        </div>
    </section>

    <main id="main-content" class="pb-24">
        <section id="financial-summary" class="px-4 py-4">
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-white shadow-lg shadow-green-200">
                    <p class="text-xs opacity-90 mb-1">Dana Masuk</p>
                    <p class="text-lg font-bold">{{ $this->formatRupiah($financials['dana_masuk']) }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white shadow-lg shadow-blue-200">
                    <p class="text-xs opacity-90 mb-1">Tersalurkan</p>
                    <p class="text-lg font-bold">{{ $this->formatRupiah($financials['tersalurkan']) }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-600 mb-1">Sisa Dana</p>
                    <p class="text-lg font-bold text-primary">{{ $this->formatRupiah($financials['sisa_dana']) }}</p>
                </div>
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-600 mb-1">Biaya Operasional</p>
                    <p class="text-lg font-bold text-gray-700">{{ $this->formatRupiah($financials['biaya_operasional']) }}</p>
                </div>
            </div>
        </section>

        <section id="filter-section" class="px-4 py-2">
            <div class="flex gap-2 overflow-x-auto hide-scrollbar">
                <button wire:click="setFilter('semua')"
                    class="px-3 py-2 rounded-full text-xs font-medium whitespace-nowrap transition-colors
                    {{ $filter === 'semua' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Semua
                </button>
                @foreach($akadTypes as $akad)
                <button wire:click="setFilter('{{ $akad->slug }}')"
                    class="px-3 py-2 rounded-full text-xs font-medium whitespace-nowrap transition-colors
                    {{ $filter === $akad->slug ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $akad->name }}
                </button>
                @endforeach
            </div>
        </section>

        <section id="program-reports" class="px-4 py-4 space-y-3">
            <h3 class="text-sm font-bold text-dark mb-3">Laporan Program</h3>

            @forelse($programs as $program)
            <div x-data="{ expanded: false }" class="bg-white rounded-xl border border-gray-100 overflow-hidden transition-all duration-300" :class="expanded ? 'shadow-md' : 'shadow-sm'">
                <div class="p-4 cursor-pointer" @click="expanded = !expanded">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm text-dark mb-1">{{ $program->title }}</h4>
                            <div class="flex gap-2 text-xs">
                                @foreach($program->akads as $akad)
                                <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded">{{ $akad->name }}</span>
                                @endforeach
                                @foreach($program->categories as $cat)
                                <span class="px-2 py-1 bg-purple-50 text-purple-600 rounded">{{ $cat->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        <button class="text-gray-400 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </button>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Dana Terkumpul</span>
                            <span class="font-semibold">{{ $this->formatRupiah($program->collected_amount) }}</span>
                        </div>
                        @php
                            $programDistributed = $program->distributions->sum('amount_distributed');
                            $distributedPercent = $program->collected_amount > 0 ? ($programDistributed / $program->collected_amount) * 100 : 0;
                        @endphp
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Dana Tersalurkan</span>
                            <span class="font-semibold text-green-600">{{ $this->formatRupiah($programDistributed) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ min(100, $distributedPercent) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-600">{{ round($distributedPercent) }}% Tersalurkan</p>
                    </div>
                </div>
                
                <div x-show="expanded" x-collapse>
                    <div class="px-4 pb-4">
                        @foreach($program->distributions as $dist)
                        <div class="border-t border-gray-100 pt-4 mt-2">
                            <div class="flex items-center justify-between mb-2">
                                <h5 class="text-xs font-semibold text-dark">Penyaluran {{ $dist->documentation_date->format('d M Y') }}</h5>
                                <span class="text-xs font-semibold text-green-600">{{ $this->formatRupiah($dist->amount_distributed) }}</span>
                            </div>
                            <div class="text-xs text-gray-600 mb-3 rich-text-content">{!! $dist->description !!}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <p class="text-sm text-gray-500">Belum ada laporan program untuk periode ini.</p>
            </div>
            @endforelse
        </section>

        {{-- <section id="download-section" class="px-4 py-4">
            <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                <h3 class="text-sm font-bold text-dark mb-3">Unduh Laporan</h3>
                <p class="text-xs text-gray-600 mb-4">Laporan lengkap periode {{ $period }} dengan tanda tangan resmi pengurus yayasan.</p>
                <div class="flex gap-2">
                    <button class="flex-1 py-3 bg-primary text-white rounded-xl text-sm font-semibold flex items-center justify-center gap-2 hover:bg-primary/90 transition-colors">
                        <i class="fa-solid fa-file-pdf"></i>
                        PDF
                    </button>
                    <button class="flex-1 py-3 bg-green-600 text-white rounded-xl text-sm font-semibold flex items-center justify-center gap-2 hover:bg-green-700 transition-colors">
                        <i class="fa-solid fa-file-excel"></i>
                        Excel
                    </button>
                </div>
            </div>
        </section> --}}
    </main>

    <x-bottom-nav active="report" />
</div>

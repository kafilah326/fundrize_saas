<div x-data="{
    showUpdateModal: false,
    showDonorModal: false,
    share() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $program->title }}',
                text: 'Mari bantu {{ $program->title }}',
                url: window.location.href
            });
        } else {
            alert('Fitur share tidak didukung di browser ini');
        }
    }
}">
    <x-page-header title="Detail Program" :showBack="true">
        <x-slot:actions>
            <button @click="share()" class="w-9 h-9 flex items-center justify-center">
                <i class="fa-solid fa-share-nodes text-dark text-lg"></i>
            </button>
        </x-slot:actions>
    </x-page-header>

    <main id="main-content" class="pb-24">
        <!-- Media Section -->
        <section id="media-section">
            <div class="w-full aspect-video overflow-hidden">
                <img class="w-full h-full object-cover" src="{{ $program->image }}" alt="{{ $program->title }}" />
            </div>
        </section>

        <!-- Info Section -->
        {{-- <section id="info-section" class="bg-white px-4 py-4">
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach ($program->categories as $category)
                    <span
                        class="px-3 py-1 bg-orange-50 text-primary text-xs font-semibold rounded-full">{{ $category->name }}</span>
                @endforeach

                @if ($program->is_urgent)
                    <span
                        class="px-3 py-1 bg-red-50 text-red-700 text-xs font-semibold rounded-full flex items-center gap-1">
                        <i class="fa-solid fa-fire text-xs"></i> Mendesak
                    </span>
                @endif
            </div>
            <h2 class="text-xl font-bold text-dark">{{ $program->title }}</h2>
            <p class="text-sm text-gray-600 leading-relaxed">{!! Str::limit(strip_tags($program->description), 100) !!}</p>
        </section> --}}

        <!-- Progress Section -->
        <section id="progress-section" class="bg-white px-4 py-4">
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach ($program->categories as $category)
                    <span
                        class="px-3 py-1 bg-orange-50 text-primary text-xs font-semibold rounded-full">{{ $category->name }}</span>
                @endforeach

                @if ($program->is_urgent)
                    <span
                        class="px-3 py-1 bg-red-50 text-red-700 text-xs font-semibold rounded-full flex items-center gap-1">
                        <i class="fa-solid fa-fire text-xs"></i> Mendesak
                    </span>
                @endif
            </div>
            <h2 class="text-xl font-bold text-dark">{{ $program->title }}</h2>
            <div class="mb-3">
                <div class="flex items-end gap-2 mb-1">
                    <span class="text-2xl font-bold text-dark">Rp
                        {{ number_format($program->collected_amount, 0, ',', '.') }}</span>
                    <span class="text-sm text-gray-500 pb-1">terkumpul</span>
                </div>
                @if ($program->target_amount)
                    <p class="text-sm text-gray-600">dari target Rp
                        {{ number_format($program->target_amount, 0, ',', '.') }}</p>
                @else
                    <p class="text-sm text-gray-600 flex items-center gap-1">
                        dari target <i class="fa-solid fa-infinity text-xs"></i> Unlimited
                    </p>
                @endif
            </div>

            @if ($program->target_amount)
                <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $program->progress }}%"></div>
                </div>
            @endif

            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-1 text-gray-600">
                    <i class="fa-solid fa-users text-xs"></i>
                    <span class="font-semibold text-dark">{{ $program->donor_count }}</span> Donatur
                </div>
                <div class="flex items-center gap-1 text-gray-600">
                    <i class="fa-solid fa-clock text-xs"></i>
                    @if ($program->end_date)
                        <span class="font-semibold text-dark">{{ $program->days_left }}</span> hari lagi
                    @else
                        <span class="font-semibold text-dark flex items-center gap-1"><i
                                class="fa-solid fa-infinity text-xs"></i> Tanpa Batas</span>
                    @endif
                </div>
            </div>
        </section>

        <!-- Description Section -->
        <section id="description-section" class="bg-white px-4 py-4 mt-2" x-data="{ expanded: false }">
            <h3 class="text-base font-bold text-dark mb-3">Deskripsi Program</h3>
            <div class="text-sm text-gray-700 leading-relaxed space-y-3">
                <div x-show="!expanded" class="relative">
                    <div class="line-clamp-4 text-gray-600">
                        {!! strip_tags($program->description) !!}
                    </div>
                    <div class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-white to-transparent"></div>
                </div>

                <div x-show="expanded" x-collapse>
                    <div class="space-y-3 pt-3 rich-text-content text-gray-700">
                        {!! $program->description !!}
                    </div>
                </div>

                <button @click="expanded = !expanded"
                    class="text-primary font-semibold text-sm flex items-center gap-1 mt-2 w-full justify-center py-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <span x-text="expanded ? 'Sembunyikan' : 'Baca Selengkapnya'"></span>
                    <i class="fa-solid text-xs" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
            </div>
        </section>

        <!-- Update Section -->
        @if ($program->updates->isNotEmpty())
            <section id="update-section" class="bg-white px-4 py-4 mt-2">
                <h3 class="text-base font-bold text-dark mb-3">Update Program</h3>

                <div class="relative max-h-72 overflow-hidden">
                    <div class="space-y-4 pb-8">
                        @foreach ($program->updates as $update)
                            <div class="flex gap-3">
                                <div class="w-1 bg-primary rounded-full flex-shrink-0"></div>
                                <div class="flex-1 pb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span
                                            class="text-xs font-semibold text-primary">{{ $update->published_at->diffForHumans() }}</span>
                                    </div>
                                    <h4 class="text-sm font-semibold text-dark mb-2">{{ $update->title }}</h4>
                                    <div class="text-sm text-gray-600 mb-2 rich-text-content">{!! $update->description !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Gradient Overlay -->
                    <div
                        class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-white via-white/80 to-transparent flex items-end justify-center pb-0">
                    </div>
                </div>

                <button @click="showUpdateModal = true"
                    class="text-primary font-semibold text-sm w-full py-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors mt-2">
                    Selengkapnya
                </button>
            </section>
        @endif

        <!-- Distribution Info -->
        @if ($program->distributions->isNotEmpty())
            <section id="distribution-section" class="bg-white px-4 py-4 mt-2" x-data="{ expanded: false }">
                <h3 class="text-base font-bold text-dark mb-3">Informasi Penyaluran</h3>

                @php
                    $totalDistributed = $program->distributions->sum('amount_distributed');
                    $remaining = $program->collected_amount - $totalDistributed;
                @endphp

                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Total Tersalurkan</span>
                    <span class="text-sm font-bold text-dark">Rp
                        {{ number_format($totalDistributed, 0, ',', '.') }}</span>
                </div>

                <div x-show="expanded" x-collapse>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Sisa Dana</span>
                        <span class="text-sm font-bold text-dark">Rp
                            {{ number_format($remaining, 0, ',', '.') }}</span>
                    </div>

                    <div class="space-y-3 mt-3">
                        @foreach ($program->distributions as $dist)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <div class="flex justify-between mb-1">
                                    <span class="text-xs font-bold text-dark">Penyaluran #{{ $loop->iteration }}</span>
                                    <span
                                        class="text-xs text-gray-500">{{ $dist->documentation_date->format('d M Y') }}</span>
                                </div>
                                <div class="text-xs text-gray-600 rich-text-content mb-2">
                                    {!! $dist->description !!}
                                </div>
                                <div class="text-xs font-bold text-primary">
                                    Disalurkan: Rp {{ number_format($dist->amount_distributed, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button @click="expanded = !expanded"
                    class="text-primary font-semibold text-sm w-full py-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors mt-2 flex items-center justify-center gap-1">
                    <span x-text="expanded ? 'Tutup' : 'Selengkapnya'"></span>
                    <i class="fa-solid text-xs" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
            </section>
        @endif

        <!-- Donor List Section -->
        @if ($donations->isNotEmpty())
            <!-- Doa Section -->
            @php
                $doaDonations = $donations->filter(function($d) { return !empty($d->doa); });
            @endphp
            @if ($doaDonations->isNotEmpty())
            <section id="doa-section" class="bg-white px-4 py-4 mt-2">
                <h3 class="text-base font-bold text-dark mb-3">Doa Orang Baik</h3>
                <div class="flex gap-3 overflow-x-auto hide-scrollbar pb-2">
                    @foreach ($doaDonations->take(10) as $donation)
                        <div class="flex-shrink-0 w-64 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                    <i class="fa-solid fa-user text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-dark line-clamp-1">
                                        {{ $donation->is_anonymous ? 'Hamba Allah' : $donation->donor_name }}
                                    </p>
                                    <p class="text-[10px] text-gray-500">{{ $donation->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 italic line-clamp-3">"{{ $donation->doa }}"</p>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

            <section id="donor-section" class="bg-white px-4 py-4 mt-2">
                <h3 class="text-base font-bold text-dark mb-3">List Donatur ({{ $donations->count() }})</h3>

                <div class="space-y-3">
                    @foreach ($donations->take(5) as $donation)
                        <div class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-user text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-dark">
                                        {{ $donation->is_anonymous ? 'Hamba Allah' : $donation->donor_name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $donation->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-primary">
                                    Rp
                                    {{ number_format($donation->amount + ($donation->payment?->unique_code ?? 0), 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($donations->count() > 5)
                    <button @click="showDonorModal = true"
                        class="text-primary font-semibold text-sm w-full py-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors mt-2">
                        Lihat Semua
                    </button>
                @endif
            </section>
        @endif

        <!-- Related Programs -->
        <section id="related-section" class="bg-white px-4 py-4 mt-2">
            <h3 class="text-base font-bold text-dark mb-3">Program Terkait</h3>
            <div class="flex gap-3 overflow-x-auto hide-scrollbar pb-2">
                @foreach (\App\Models\Program::where('id', '!=', $program->id)->where('is_active', true)->take(3)->get() as $related)
                    <div class="flex-shrink-0 w-64">
                        <a href="{{ route('program.detail', $related->slug) }}" wire:navigate
                            class="block w-full h-full bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                            <div class="aspect-video overflow-hidden h-32 w-full">
                                <img class="w-full h-full object-cover" src="{{ $related->image }}"
                                    alt="{{ $related->title }}" />
                            </div>
                            <div class="p-3">
                                <h4 class="text-sm font-semibold text-dark mb-1 line-clamp-2 h-10">
                                    {{ $related->title }}</h4>
                                <div class="mt-2">
                                    <div class="flex justify-between items-end mb-1">
                                        <span class="text-xs text-gray-500">Terkumpul</span>
                                        <span class="text-xs font-bold text-primary">Rp
                                            {{ number_format($related->collected_amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                                        <div class="bg-primary h-1.5 rounded-full"
                                            style="width: {{ $related->progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

    </main>

    <!-- CTA Button -->
    <div id="cta-button"
        class="fixed bottom-0 left-0 right-0 max-w-[460px] mx-auto bg-white border-t border-gray-200 px-4 py-3 z-40">
        <a href="{{ route('program.checkout', $slug) }}" wire:navigate
            class="block w-full py-3 bg-primary text-white rounded-xl text-base font-bold shadow-lg active:scale-95 transition-transform text-center hover:bg-primary/90">
            Donasi Sekarang
        </a>
    </div>

    <!-- Modals -->
    <!-- Update Modal -->
    <div x-show="showUpdateModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

        <div class="bg-white w-full max-w-[460px] max-h-[85vh] rounded-2xl overflow-hidden flex flex-col shadow-xl"
            @click.away="showUpdateModal = false">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                <h3 class="font-bold text-dark text-lg">Update Program</h3>
                <button @click="showUpdateModal = false"
                    class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <div class="space-y-6">
                    @foreach ($program->updates as $update)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-2 h-2 bg-primary rounded-full"></div>
                                <div class="w-0.5 bg-gray-200 flex-1 my-1"></div>
                            </div>
                            <div class="flex-1 pb-2">
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span
                                            class="text-xs font-semibold text-primary">{{ $update->published_at->diffForHumans() }}</span>
                                    </div>
                                    <h4 class="text-sm font-semibold text-dark mb-2">{{ $update->title }}</h4>
                                    <div class="text-sm text-gray-600 rich-text-content">{!! $update->description !!}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Donor Modal -->
    <div x-show="showDonorModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

        <div class="bg-white w-full max-w-[460px] max-h-[85vh] rounded-2xl overflow-hidden flex flex-col shadow-xl"
            @click.away="showDonorModal = false">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                <h3 class="font-bold text-dark text-lg">Semua Donatur</h3>
                <button @click="showDonorModal = false"
                    class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <div class="space-y-3">
                    @foreach ($donations as $donation)
                        <div class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-user text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-dark">
                                        {{ $donation->is_anonymous ? 'Hamba Allah' : $donation->donor_name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $donation->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-primary">
                                    Rp
                                    {{ number_format($donation->amount + ($donation->payment?->unique_code ?? 0), 0, ',', '.') }}
                                </p>
                                @if ($donation->payment?->unique_code)
                                    <span class="text-[10px] text-gray-400 block">+kode unik
                                        {{ $donation->payment->unique_code }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

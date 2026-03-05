<div>
    <x-page-header title="Program Ber-Ujroh" :showBack="true" backUrl="{{ route('fundraiser.dashboard') }}" />

    <main id="main-content" class="px-4 pb-24 pt-4">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-dark mb-2">Sebarkan Kebaikan</h2>
            <p class="text-sm text-gray-600 leading-relaxed">
                Bagikan program-program di bawah ini menggunakan link unik Anda. Setiap donasi yang berhasil melalui link Anda akan mendapatkan ujroh.
            </p>
        </div>

        <div class="space-y-4">
            @forelse ($programs as $program)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col"
                    x-data="{
                        referralLink: '{{ url('/program/' . $program->slug) }}?ref={{ $fundraiser->referral_code }}',
                        copied: false,
                        share() {
                            if (navigator.share) {
                                navigator.share({
                                    title: '{{ addslashes($program->title) }}',
                                    text: 'Mari bantu {{ addslashes($program->title) }}',
                                    url: this.referralLink
                                }).catch(console.error);
                            } else {
                                navigator.clipboard.writeText(this.referralLink).then(() => {
                                    this.copied = true;
                                    setTimeout(() => this.copied = false, 2000);
                                });
                            }
                        }
                    }">
                    <a href="{{ route('program.detail', $program->slug) }}" class="block relative w-full aspect-video">
                        <img src="{{ $program->image }}" alt="{{ $program->title }}" class="w-full h-full object-cover">
                        
                        <!-- Ujroh Badge -->
                        <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5">
                            <i class="fa-solid fa-coins text-primary text-xs"></i>
                            <span class="text-xs font-bold text-dark">
                                Ujroh: 
                                @if($program->commission_type === 'percentage')
                                    {{ (int)$program->commission_amount }}%
                                @else
                                    Rp {{ number_format($program->commission_amount, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                    </a>

                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <h3 class="font-bold text-dark text-base leading-snug line-clamp-2">
                                <a href="{{ route('program.detail', $program->slug) }}">
                                    {{ $program->title }}
                                </a>
                            </h3>
                            
                            <button @click.prevent="share()" class="flex-shrink-0 w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center transition-colors relative" :class="copied ? 'bg-emerald-100 text-emerald-600' : 'hover:bg-primary hover:text-white'">
                                <i class="fa-solid" :class="copied ? 'fa-check' : 'fa-share-nodes'"></i>
                                
                                <!-- Tooltip Copied (Desktop/Fallback) -->
                                <span x-show="copied" x-transition class="absolute -top-8 right-0 bg-dark text-white text-[10px] font-bold px-2 py-1 rounded whitespace-nowrap" style="display: none;">
                                    Link Tersalin!
                                </span>
                            </button>
                        </div>

                        <div class="mt-auto">
                            <div class="flex items-end gap-2 mb-1.5">
                                <span class="text-sm font-bold text-dark">Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-500 pb-0.5">terkumpul</span>
                            </div>
                            
                            @if ($program->target_amount)
                                <div class="w-full bg-gray-100 rounded-full h-1.5 mb-2">
                                    <div class="bg-primary h-1.5 rounded-full" style="width: {{ $program->progress }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500">Target Rp {{ number_format($program->target_amount, 0, ',', '.') }}</span>
                                    @if ($program->end_date)
                                        <span class="font-semibold text-dark">{{ $program->days_left }} hari lagi</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 rounded-2xl p-8 text-center border border-gray-100">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <i class="fa-solid fa-box-open text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-dark font-bold mb-1">Belum Ada Program</h3>
                    <p class="text-sm text-gray-500">Saat ini belum ada program yang memiliki ujroh aktif.</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Bottom Navigation -->
    <x-bottom-nav active="profile" />
</div>
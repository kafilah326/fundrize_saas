<?php
$file = 'resources/views/livewire/front/program-detail.blade.php';
$content = file_get_contents($file);

// 1. Add showFundraiserModal to x-data
$content = str_replace(
    "showDoaModal: false,", 
    "showDoaModal: false,\n    showFundraiserModal: false,", 
    $content
);

// 2. Extract the Fundraiser List Section
$fundraiserStart = '        <!-- Fundraiser List Section -->';
$fundraiserEnd = '        <!-- Related Programs -->';

$startPos = strpos($content, $fundraiserStart);
$endPos = strpos($content, $fundraiserEnd);

if ($startPos !== false && $endPos !== false) {
    // Remove it from the original place
    $fundraiserBlockRaw = substr($content, $startPos, $endPos - $startPos);
    $content = substr_replace($content, '', $startPos, $endPos - $startPos);
    
    // Build the new fundraiser block with CTA and button
    $newFundraiserBlock = '
            <!-- Fundraiser List Section -->
            @if ($programFundraisers && $programFundraisers->isNotEmpty())
                <section id="fundraiser-section" class="bg-white px-4 py-4 mt-2">
                    <h3 class="text-base font-bold text-dark mb-3">Pejuang Kebaikan ({{ $programFundraisers->count() }})</h3>
                    
                    <div class="space-y-3">
                        @foreach ($programFundraisers->take(5) as $fundraiser)
                            <div class="flex items-start gap-3 border-b border-gray-50 pb-3 last:border-0 last:pb-0">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
                                    {{ substr(trim($fundraiser->name), 0, 2) }}
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-dark mb-1">
                                        {{ $fundraiser->name }}
                                    </p>
                                    <p class="text-xs text-gray-600 leading-relaxed">
                                        Telah mengajak <span class="font-bold text-dark">{{ $fundraiser->donor_count }} donatur</span> untuk berdonasi, dengan total nominal <span class="font-bold text-primary">Rp {{ number_format($fundraiser->total_amount, 0, ',', '.') }}</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($programFundraisers->count() > 5)
                        <button @click="showFundraiserModal = true"
                            class="text-primary font-semibold text-sm w-full py-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors mt-2 mb-3">
                            Lihat Semua
                        </button>
                    @endif

                    {{-- CTA Ajakan --}}
                    @guest
                        <div class="mt-3 bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
                            <p class="text-sm text-gray-700 mb-3 leading-relaxed">
                                Ingin ikut menyebarkan kebaikan? Buat akun terlebih dahulu dan jadilah <span class="font-bold text-primary">Pejuang Kebaikan!</span>
                            </p>
                            <a href="/register" class="block text-center bg-primary hover:bg-primary-hover text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors shadow-sm">
                                Daftar Sekarang
                            </a>
                        </div>
                    @endguest
                    @auth
                        @if(!auth()->user()->fundraiser || auth()->user()->fundraiser->status === \'rejected\')
                            <div class="mt-3 bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
                                <p class="text-sm text-gray-700 mb-3 leading-relaxed">
                                    Ingin ikut menyebarkan kebaikan? Jadilah <span class="font-bold text-primary">Pejuang Kebaikan!</span>
                                </p>
                                <a href="/fundraiser/register" wire:navigate class="block text-center bg-primary hover:bg-primary-hover text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors shadow-sm">
                                    Gabung Sekarang
                                </a>
                            </div>
                        @elseif(auth()->user()->fundraiser->status === \'pending\')
                            <div class="mt-3 bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                                <p class="text-sm text-yellow-800 flex items-center gap-2">
                                    <i class="fa-solid fa-hourglass-half"></i> Pendaftaran Pejuang Kebaikan Anda sedang diproses.
                                </p>
                            </div>
                        @endif
                    @endauth
                </section>
            @endif

';

    // Insert newFundraiserBlock exactly after the Doa Section ends
    $doaEndStr = '            @endif

            <section id="donor-section" class="bg-white px-4 py-4 mt-2">';
    
    $content = str_replace($doaEndStr, "            @endif\n" . $newFundraiserBlock . "            <section id=\"donor-section\" class=\"bg-white px-4 py-4 mt-2\">", $content);
}

// 3. Add Fundraiser Modal at the end, just before the last </div>
$modalBlock = '
    <!-- Fundraiser Modal -->
    <div x-show="showFundraiserModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

        <div class="bg-white w-full max-w-[460px] max-h-[85vh] rounded-2xl overflow-hidden flex flex-col shadow-xl"
            @click.away="showFundraiserModal = false">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                <h3 class="font-bold text-dark text-lg">Semua Pejuang Kebaikan</h3>
                <button @click="showFundraiserModal = false"
                    class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <div class="space-y-4">
                    @if ($programFundraisers && $programFundraisers->isNotEmpty())
                        @foreach ($programFundraisers as $fundraiser)
                            <div class="flex items-start gap-3 border-b border-gray-50 pb-3 last:border-0 last:pb-0">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
                                    {{ substr(trim($fundraiser->name), 0, 2) }}
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-dark mb-1">
                                        {{ $fundraiser->name }}
                                    </p>
                                    <p class="text-xs text-gray-600 leading-relaxed">
                                        Telah mengajak <span class="font-bold text-dark">{{ $fundraiser->donor_count }} donatur</span> untuk berdonasi, dengan total nominal <span class="font-bold text-primary">Rp {{ number_format($fundraiser->total_amount, 0, ',', '.') }}</span>
                                    </p>
                                </div>
                            </div>
                        @endforeac

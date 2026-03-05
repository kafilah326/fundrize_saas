<div x-data="{
    share() {
        if (navigator.share) {
            let url = window.location.href;
            @auth
                @if(auth()->user()->fundraiser && auth()->user()->fundraiser->status === 'approved')
                    if (!url.includes('ref=')) {
                        url += (url.includes('?') ? '&' : '?') + 'ref={{ auth()->user()->fundraiser->referral_code }}';
                    }
                @endif
            @endauth

            navigator.share({
                title: 'Tabungan Qurban {{ str_replace('-', ' ', $saving->target_animal_type) }}',
                text: 'Mari ikut menabung qurban',
                url: url
            });
        } else {
            alert('Fitur share tidak didukung di browser ini');
        }
    }
}">
    <x-page-header title="Detail Tabungan Qurban" :showBack="true" backUrl="{{ route('qurban.history') }}" />

    <main id="main-content" class="pb-20">
        <section id="status-section" class="bg-gradient-to-br from-orange-50 to-orange-100 px-4 py-5 border-b-2 border-orange-200">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-dark capitalize">{{ str_replace('-', ' ', $saving->target_animal_type) }}</h2>
                    <p class="text-xs text-gray-600 mt-1">Target: {{ $saving->target_hijri_year }}</p>
                </div>
                <!-- Mockup countdown for now -->
                <span class="px-2.5 py-1 bg-orange-600 text-white text-xs font-semibold rounded-full">Active</span>
            </div>
            <div class="mb-4">
                <div class="flex items-baseline justify-between mb-2">
                    <span class="text-2xl font-bold text-dark">Rp {{ number_format($saving->saved_amount, 0, ',', '.') }}</span>
                    <span class="text-sm text-gray-600">/ Rp {{ number_format($saving->target_amount, 0, ',', '.') }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-primary h-3 rounded-full" style="width: {{ $saving->progress }}%;"></div>
                </div>
                <p class="text-sm text-gray-600 mt-2">{{ $saving->progress }}% terkumpul • Sisa Rp {{ number_format($saving->target_amount - $saving->saved_amount, 0, ',', '.') }}</p>
            </div>
            <div class="flex gap-2">
                <button wire:click="openDepositModal({{ $saving->id }})" class="flex-1 bg-primary hover:bg-orange-600 text-white font-semibold py-3 rounded-lg text-sm text-center flex items-center justify-center gap-1">
                    <i class="fa-solid fa-plus mr-1"></i> Setor Lagi
                </button>
                <button @click="share()" class="bg-white border border-primary text-primary font-semibold py-3 px-4 rounded-lg text-sm hover:bg-orange-50 transition-colors">
                    <i class="fa-solid fa-share-alt"></i>
                </button>
            </div>
        </section>

        <!-- Deposit Modal -->
        @if($showDepositModal)
        <div class="fixed inset-0 z-50 flex items-end justify-center sm:items-center">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="closeDepositModal"></div>
            
            <div class="bg-white w-full max-w-[460px] rounded-t-2xl sm:rounded-2xl p-5 relative z-10 transform transition-all">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-dark">Setor Tabungan</h3>
                    <button wire:click="closeDepositModal" class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full hover:bg-gray-200">
                        <i class="fa-solid fa-xmark text-gray-600"></i>
                    </button>
                </div>

                <div class="mb-4">
                    <label class="text-sm font-semibold text-gray-700 mb-2 block">Pilih Nominal Setor</label>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        @foreach([50000, 100000, 250000, 500000] as $amount)
                        <button wire:click="setDepositAmount({{ $amount }})" 
                            class="py-2.5 px-3 rounded-lg text-sm font-semibold transition-colors {{ $depositAmount == $amount && !$showCustomDepositInput ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Rp {{ number_format($amount, 0, ',', '.') }}
                        </button>
                        @endforeach
                    </div>

                    <div class="relative">
                        <label class="text-xs font-medium text-gray-600 mb-1.5 block">Nominal Lainnya</label>
                        <div class="flex items-center gap-2 border border-gray-300 rounded-lg px-3 focus-within:ring-2 focus-within:ring-primary/30 focus-within:border-primary transition-all {{ $showCustomDepositInput ? 'ring-2 ring-primary/30 border-primary' : '' }}">
                            <span class="text-sm text-gray-600">Rp</span>
                            <input type="text" wire:model.live="customDepositAmount" wire:focus="setDepositAmount('custom')" placeholder="Minimal Rp 10.000" class="flex-1 py-2.5 text-sm focus:outline-none w-full bg-transparent">
                        </div>
                    </div>
                </div>

                <button wire:click="submitDeposit" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-bold shadow-lg active:scale-95 transition-transform hover:bg-primary/90">
                    Lanjut Pembayaran
                </button>
            </div>
        </div>
        @endif

        <section id="info-section" class="bg-white px-4 py-5 mb-2">
            <h3 class="text-sm font-bold text-dark mb-3">Informasi Tabungan</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-xs text-gray-600">Muqorib</span>
                    <span class="text-xs font-medium text-dark">{{ $saving->donor_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-600">Atas Nama Qurban</span>
                    <span class="text-xs font-medium text-dark">{{ $saving->qurban_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-600">WhatsApp</span>
                    <span class="text-xs font-medium text-dark">{{ $saving->whatsapp }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-600">Tanggal Dibuat</span>
                    <span class="text-xs font-medium text-dark">{{ $saving->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-600">ID Tabungan</span>
                    <span class="text-xs font-medium text-dark">#{{ $saving->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-600">Pengingat</span>
                    <span class="text-xs font-medium text-dark capitalize">{{ $saving->reminder_frequency }}</span>
                </div>
            </div>
        </section>

        <section id="certificate-section" class="bg-white px-4 py-5 mb-2">
            <h3 class="text-sm font-bold text-dark mb-3">Sertifikat Qurban</h3>
            <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-certificate text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-dark">Sertifikat Digital</p>
                        <p class="text-xs text-gray-600">Tersedia setelah penyembelihan</p>
                    </div>
                    <button class="px-3 py-2 bg-primary text-white text-xs font-medium rounded-lg hover:bg-orange-600 transition-colors">
                        Unduh
                    </button>
                </div>
            </div>
        </section>

        <section id="qurban-documentation" class="bg-white px-4 py-5 mb-20">
            <h3 class="text-sm font-bold text-dark mb-3">Dokumentasi Qurban</h3>
            
            @if($saving->documentations->count() > 0)
                <div class="bg-gray-50 rounded-lg p-3 mb-3">
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($saving->documentations as $doc)
                            <div class="rounded-lg overflow-hidden bg-gray-200 aspect-video relative group">
                                @if($doc->file_type === 'photo')
                                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank">
                                        <img src="{{ Storage::url($doc->file_path) }}" class="w-full h-full object-cover" alt="Dokumentasi">
                                    </a>
                                @else
                                    <video src="{{ Storage::url($doc->file_path) }}" class="w-full h-full object-cover" controls></video>
                                @endif
                                @if($doc->caption)
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[10px] p-1 truncate">
                                        {{ $doc->caption }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-3 mb-3">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-camera text-gray-600 text-xs"></i>
                        <span class="text-xs font-medium text-dark">Foto & Video Penyembelihan</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-3">Dokumentasi akan tersedia setelah proses penyembelihan selesai.</p>
                    
                    <div class="grid grid-cols-2 gap-2">
                        <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-image text-gray-400 text-xl"></i>
                        </div>
                        <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-video text-gray-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <div class="flex items-start gap-2">
                    <i class="fa-solid fa-bell text-blue-600 text-xs mt-0.5"></i>
                    <div class="flex-1">
                        <p class="text-xs font-medium text-blue-800 mb-1">Notifikasi Otomatis</p>
                        <p class="text-xs text-blue-700">Anda akan menerima notifikasi via WhatsApp ketika dokumentasi sudah tersedia</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

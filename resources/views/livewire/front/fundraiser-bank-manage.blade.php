<div>
    <x-page-header title="Kelola Rekening" :showBack="true" backUrl="{{ route('fundraiser.withdrawal') }}" />

    <main id="main-content" class="px-4 py-6 pb-20 min-h-screen bg-gray-50">
        
        <!-- Alerts -->
        @if (session()->has('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fa-solid fa-check-circle text-emerald-500"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6 flex items-center justify-between">
            <h3 class="text-base font-bold text-dark">Daftar Rekening</h3>
            <button wire:click="openModal" class="text-primary text-sm font-bold flex items-center gap-1 hover:bg-orange-50 px-3 py-1.5 rounded-lg transition-colors">
                <i class="fa-solid fa-plus"></i> Tambah
            </button>
        </div>

        <div class="space-y-4">
            @forelse($banks as $bank)
                <div class="bg-white rounded-xl p-4 shadow-sm border {{ $bank->is_primary ? 'border-primary ring-1 ring-primary/20' : 'border-gray-100' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-10 bg-blue-600 rounded-lg flex items-center justify-center shrink-0 shadow-inner">
                                <span class="text-white text-xs font-bold">{{ strtoupper(substr($bank->bank_name, 0, 4)) }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <p class="text-dark font-bold text-sm">{{ $bank->bank_name }}</p>
                                    @if($bank->is_primary)
                                        <span class="bg-primary/10 text-primary text-[10px] font-bold px-2 py-0.5 rounded-full">UTAMA</span>
                                    @endif
                                </div>
                                <p class="text-gray-500 text-sm font-mono tracking-wide">{{ $bank->account_number }}</p>
                                <p class="text-gray-400 text-xs mt-0.5">a.n {{ $bank->account_name }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <button wire:click="openModal({{ $bank->id }})" class="w-8 h-8 rounded-full bg-gray-50 text-gray-500 flex items-center justify-center hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <button wire:click="delete({{ $bank->id }})" wire:confirm="Hapus rekening ini?" class="w-8 h-8 rounded-full bg-gray-50 text-gray-500 flex items-center justify-center hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                    @if(!$bank->is_primary)
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <button wire:click="setAsPrimary({{ $bank->id }})" class="text-xs font-semibold text-gray-500 hover:text-primary w-full text-left transition-colors">
                                Jadikan Rekening Utama
                            </button>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-xl p-8 text-center border border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-building-columns text-blue-300 text-2xl"></i>
                    </div>
                    <p class="text-base font-bold text-dark mb-1">Belum ada rekening</p>
                    <p class="text-sm text-gray-500 mb-6">Tambahkan rekening bank untuk menerima pencairan ujroh.</p>
                    <button wire:click="openModal" class="bg-primary text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-primary/30 hover:bg-primary-hover active:scale-95 transition-all inline-flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tambah Rekening
                    </button>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Modal Form -->
    <div x-data="{ show: @entangle('showModal') }" 
         x-show="show" 
         class="fixed inset-0 z-50 flex items-end justify-center sm:items-center" 
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="show = false"></div>
        
        <!-- Modal Content -->
        <div class="bg-white w-full max-w-[460px] rounded-t-2xl sm:rounded-2xl p-5 relative z-10 transform transition-all shadow-2xl">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-dark">{{ $bankId ? 'Edit Rekening' : 'Tambah Rekening Baru' }}</h3>
                <button wire:click="closeModal" class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full hover:bg-gray-200">
                    <i class="fa-solid fa-xmark text-gray-600"></i>
                </button>
            </div>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-1.5">Nama Bank</label>
                    <input type="text" wire:model="bank_name" placeholder="Contoh: Bank BCA, Bank Mandiri" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-gray-50 focus:bg-white transition-colors text-sm">
                    @error('bank_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-1.5">Nomor Rekening</label>
                    <input type="text" wire:model="account_number" placeholder="Contoh: 1234567890" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-gray-50 focus:bg-white transition-colors font-mono text-sm">
                    @error('account_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-1.5">Atas Nama (Sesuai Buku Tabungan)</label>
                    <input type="text" wire:model="account_name" placeholder="Contoh: Budi Santoso" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-gray-50 focus:bg-white transition-colors text-sm">
                    @error('account_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full bg-primary text-white py-3.5 rounded-xl text-sm font-bold shadow-lg mt-6 active:scale-95 transition-transform hover:bg-primary/90 flex justify-center items-center gap-2">
                    <span wire:loading.remove wire:target="save">Simpan Rekening</span>
                    <span wire:loading wire:target="save"><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                </button>
            </form>
        </div>
    </div>

</div>

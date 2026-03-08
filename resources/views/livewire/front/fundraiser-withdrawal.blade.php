<div>
    <x-page-header title="Pencairan Ujroh" :showBack="true" backUrl="{{ route('fundraiser.history') }}" />

    <main id="main-content" class="px-4 pb-20">
        <!-- Balance Info Section -->
        <section id="balance-info" class="py-6">
            <div class="bg-primary rounded-2xl p-6 shadow-lg text-center">
                <div class="mb-4">
                    <p class="text-orange-100 text-sm font-medium">Saldo Tersedia</p>
                    <h2 class="text-white text-3xl font-bold">Rp {{ number_format($availableBalance, 0, ',', '.') }}</h2>
                    <p class="text-orange-100 text-xs mt-1">Minimal pencairan Rp 50.000</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                    <p class="text-white text-xs font-medium">Estimasi sampai rekening</p>
                    <p class="text-orange-100 text-xs">1-2 hari kerja</p>
                </div>
            </div>
        </section>

        <!-- Session Alert -->
        @if (session()->has('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fa-solid fa-check-circle text-emerald-500"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Withdrawal Form -->
        <section id="withdrawal-form" class="mb-6">
            @if($hasPendingWithdrawal)
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-5 shadow-sm text-center">
                    <div class="w-12 h-12 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                    </div>
                    <h3 class="text-dark font-bold text-base mb-1">Pencairan Sedang Diproses</h3>
                    <p class="text-gray-600 text-sm">Form pencairan dikunci karena Anda masih memiliki pengajuan yang sedang diproses oleh admin. Silakan tunggu hingga selesai.</p>
                </div>
            @else
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <h3 class="text-dark font-semibold text-base mb-4">Form Pencairan</h3>
                    
                    <form wire:submit.prevent="submitWithdrawal" class="space-y-4" x-data="{ amount: @entangle('amount').live, balance: {{ $availableBalance }} }">
                    <!-- Amount Input -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Jumlah Pencairan</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                            <input type="number" x-model="amount" placeholder="0" class="w-full pl-9 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-lg font-bold text-dark bg-gray-50/50" :class="amount > balance ? 'border-red-500 focus:ring-red-500' : ''">
                        </div>
                        <span x-show="amount > balance" class="text-red-500 text-xs mt-1 block font-medium">Nominal melebihi saldo aktif Anda!</span>
                        @error('amount') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        
                        <div class="flex flex-wrap gap-2 mt-3">
                            <button type="button" wire:click="setAmount(50000)" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">50K</button>
                            <button type="button" wire:click="setAmount(100000)" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">100K</button>
                            <button type="button" wire:click="setAmount(200000)" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">200K</button>
                            <button type="button" wire:click="setAmount('all')" class="bg-primary/10 hover:bg-primary/20 text-primary px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">Semua</button>
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Bank Tujuan</label>
                        @if($primaryBank)
                            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-8 bg-blue-600 rounded flex items-center justify-center shadow-inner">
                                            <span class="text-white text-xs font-bold">{{ strtoupper(substr($primaryBank->bank_name, 0, 4)) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-dark font-semibold text-sm">{{ $primaryBank->bank_name }}</p>
                                            <p class="text-gray-500 text-xs font-mono">{{ $primaryBank->account_number }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('fundraiser.banks') }}" wire:navigate class="text-primary text-sm font-medium hover:bg-orange-50 p-2 rounded-lg transition-colors">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="border border-red-200 bg-red-50 rounded-lg p-4 text-center">
                                <p class="text-xs text-red-600 font-medium mb-2">Anda belum menambahkan rekening bank.</p>
                                <a href="{{ route('fundraiser.banks') }}" wire:navigate class="w-full inline-block text-primary text-sm font-bold py-2 border border-primary rounded-lg bg-white hover:bg-orange-50 transition-colors">
                                    + Tambah Rekening Baru
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Fee Info embedded inside form for better flow -->
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-2">
                        <h4 class="text-amber-800 font-bold text-xs mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-info"></i> Informasi Biaya
                        </h4>
                        <div class="space-y-1.5 text-xs">
                            <div class="flex justify-between">
                                <span class="text-amber-700">Biaya Admin Platform</span>
                                <span class="text-amber-700 font-bold">Gratis</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" 
                        :disabled="amount > balance || amount < 50000 || !{{ $primaryBank ? 'true' : 'false' }}"
                        wire:loading.attr="disabled" 
                        class="w-full bg-primary hover:bg-primary-hover text-white font-bold py-3.5 rounded-xl shadow-lg active:scale-95 transition-all mt-4 disabled:opacity-50 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="submitWithdrawal">Ajukan Pencairan</span>
                        <span wire:loading wire:target="submitWithdrawal"><i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...</span>
                    </button>
                        <p class="text-center text-gray-500 text-[10px] mt-3 px-4">
                            Dengan mengajukan pencairan, Anda menyetujui syarat dan ketentuan yang berlaku
                        </p>
                    </form>
                </div>
            @endif
        </section>

        <!-- Withdrawal History Section -->
        <section id="withdrawal-history" class="mb-6">
            <h3 class="text-dark font-bold text-base mb-3 px-1">Riwayat Pencairan</h3>
            <div class="space-y-3">
                @forelse($withdrawals as $wd)
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="text-dark font-bold text-sm">Pencairan ke {{ $wd->bank_name }}</p>
                                <p class="text-gray-500 text-xs mt-0.5">{{ $wd->account_number }} a.n {{ $wd->account_name }}</p>
                                <p class="text-gray-400 text-[10px] mt-1">{{ $wd->created_at->format('d M Y, H:i') }}</p>
                                
                                @if($wd->status === 'rejected' && $wd->rejected_reason)
                                    <p class="text-red-500 text-[10px] mt-1 mt-2 bg-red-50 p-2 rounded-lg italic">Alasan Ditolak: {{ $wd->rejected_reason }}</p>
                                @endif
                                
                                @if($wd->status === 'approved' && $wd->processed_at)
                                    <p class="text-green-600 text-[10px] mt-1 mt-2 bg-green-50 p-2 rounded-lg"><i class="fa-solid fa-check mr-1"></i> Diproses: {{ $wd->processed_at->format('d M Y, H:i') }}</p>
                                @endif
                            </div>
                            <div class="text-right flex flex-col items-end gap-1.5 ml-3">
                                <p class="text-dark font-bold text-sm">Rp {{ number_format($wd->amount, 0, ',', '.') }}</p>
                                @if($wd->status === 'pending')
                                    <span class="bg-orange-50 border border-orange-100 text-orange-600 text-[10px] font-bold px-2.5 py-1 rounded-full">Proses</span>
                                @elseif($wd->status === 'approved')
                                    <span class="bg-emerald-50 border border-emerald-100 text-emerald-600 text-[10px] font-bold px-2.5 py-1 rounded-full">Berhasil</span>
                                @else
                                    <span class="bg-red-50 border border-red-100 text-red-600 text-[10px] font-bold px-2.5 py-1 rounded-full">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl p-6 text-center border border-gray-100">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-money-bill-transfer text-gray-300 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-dark">Belum ada riwayat pencairan</p>
                        <p class="text-xs text-gray-500 mt-1">Ajukan pencairan pertamamu sekarang.</p>
                    </div>
                @endforelse
            </div>
            
            @if($withdrawals->hasPages())
                <div class="mt-4">
                    {{ $withdrawals->links() }}
                </div>
            @endif
        </section>
    </main>

    <x-bottom-nav active="profile" />
</div>

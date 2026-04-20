<div class="min-h-screen pt-32 pb-20 px-6">
    <div class="max-w-4xl mx-auto">
        <!-- Progress Bar -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-4">
                @foreach([1 => 'Paket', 2 => 'Admin', 3 => 'Yayasan', 4 => 'Pembayaran'] as $num => $label)
                    @if($num === 4 && $selectedPlan && $selectedPlan->price == 0) @continue @endif
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all
                            {{ $step >= $num ? 'bg-primary-600 text-white shadow-lg shadow-primary-200' : 'bg-slate-200 text-slate-500' }}">
                            {{ $num }}
                        </div>
                        <span class="text-xs font-bold mt-2 {{ $step >= $num ? 'text-primary-600' : 'text-slate-400' }} uppercase tracking-widest">{{ $label }}</span>
                    </div>
                    @if($num < ($selectedPlan && $selectedPlan->price == 0 ? 3 : 4))
                        <div class="flex-grow h-1 mx-4 bg-slate-200 rounded-full -mt-6">
                            <div class="h-full bg-primary-600 rounded-full transition-all duration-500" style="width: {{ min(100, max(0, ($step - $num) * 100)) }}%"></div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200 border border-slate-100 overflow-hidden">
            @if($step === 1)
                <!-- Step 1: Plan Selection -->
                <div class="p-10 md:p-16">
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Pilih Paket Anda</h2>
                    <p class="text-slate-500 mb-10">Pilih fondasi yang tepat untuk pertumbuhan digital yayasan Anda.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($plans as $plan)
                            <label class="relative flex flex-col p-6 rounded-2xl border-2 cursor-pointer transition-all hover:bg-slate-50
                                {{ $selectedPlanSlug === $plan->slug ? 'border-primary-500 bg-primary-50/30' : 'border-slate-100' }}">
                                <input type="radio" wire:model.live="selectedPlanSlug" value="{{ $plan->slug }}" class="sr-only">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="font-bold text-xl text-slate-900">{{ $plan->name }}</div>
                                    @if($selectedPlanSlug === $plan->slug)
                                        <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center text-white">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-2xl font-black text-slate-900">Rp {{ number_format($plan->price, 0, ',', '.') }}</div>
                                @if($plan->price > 0)
                                    <div class="text-[10px] font-bold text-slate-400 uppercase mb-2">(Sekali Bayar)</div>
                                @endif
                                
                                <p class="text-slate-500 text-sm mb-4 line-clamp-2">{{ $plan->description }}</p>
                                
                                @if($plan->system_fee_percentage > 0)
                                    <div class="mb-4 py-2 px-3 rounded-lg bg-primary-50 border border-primary-100">
                                        <div class="text-[10px] font-bold text-primary-600 uppercase tracking-widest">Biaya Maintenance</div>
                                        <div class="text-sm font-black text-primary-700">{{ $plan->system_fee_percentage }}% <span class="text-[10px] font-medium opacity-70">/transaksi</span></div>
                                    </div>
                                @endif
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Fitur Unggulan:</div>
                                <ul class="mt-2 space-y-1">
                                    <li class="text-xs text-slate-600 flex items-center gap-2"><i class="fas fa-check text-primary-500"></i> {{ $plan->getLimit('max_users') == -1 ? 'Unlimited' : $plan->getLimit('max_users') }} User</li>
                                    <li class="text-xs text-slate-600 flex items-center gap-2"><i class="fas fa-check text-primary-500"></i> {{ ucwords($plan->slug) }} Features</li>
                                </ul>
                            </label>
                        @endforeach
                    </div>
                    
                    <div class="mt-12 flex justify-end">
                        <button wire:click="nextStep" class="bg-primary-600 hover:bg-primary-700 text-white font-extrabold px-12 py-4 rounded-2xl shadow-xl shadow-primary-200 transition-all flex items-center gap-3">
                            Lanjut ke Informasi Admin <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

            @elseif($step === 2)
                <!-- Step 2: Admin Info -->
                <div class="p-10 md:p-16">
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Informasi Admin</h2>
                    <p class="text-slate-500 mb-10">Data ini akan digunakan sebagai akun superadmin utama yayasan Anda.</p>
                    
                    <div class="space-y-6 max-w-2xl">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Lengkap</label>
                            <input type="text" wire:model="adminName" placeholder="Masukkan nama lengkap Anda" 
                                class="w-full px-6 py-4 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
                            @error('adminName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Alamat Email Aktif</label>
                            <input type="email" wire:model="adminEmail" placeholder="nama@email.com" 
                                class="w-full px-6 py-4 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
                            @error('adminEmail') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Keamanan Password</label>
                            <input type="password" wire:model="adminPassword" placeholder="Minimum 8 karakter" 
                                class="w-full px-6 py-4 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
                            @error('adminPassword') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="mt-12 flex justify-between">
                        <button wire:click="prevStep" class="text-slate-500 font-bold hover:text-slate-800 transition-colors">Kembali</button>
                        <button wire:click="nextStep" class="bg-primary-600 hover:bg-primary-700 text-white font-extrabold px-12 py-4 rounded-2xl shadow-xl shadow-primary-200 transition-all flex items-center gap-3">
                            Lanjut ke Informasi Yayasan <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

            @elseif($step === 3)
                <div class="p-10 md:p-16">
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Identitas Yayasan</h2>
                    <p class="text-slate-500 mb-10">Bangun profil digital yayasan Anda sendiri.</p>
                    
                    <div class="space-y-6 max-w-2xl">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Yayasan / Lembaga</label>
                            <input type="text" 
                                wire:model="foundationName" 
                                x-on:blur="if (!$wire.foundationSlug) { 
                                    $wire.set('foundationSlug', $el.value.toLowerCase().trim().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, ''));
                                }"
                                placeholder="Contoh: Yayasan Peduli Kemanusiaan" 
                                class="w-full px-6 py-4 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
                            @error('foundationName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <div class="relative">
                                <input type="text" 
                                    wire:model="foundationSlug" 
                                    id="foundationSlug"
                                    placeholder="yayasan-anda" 
                                    class="w-full px-6 py-4 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all pr-40">
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold border-l pl-4 border-slate-100">
                                    .{{ config('tenancy.base_domain') }}
                                </div>
                            </div>
                            <p class="mt-2 text-[10px] text-slate-400 font-medium">Contoh: https://yayasan-anda.{{ config('tenancy.base_domain') }}</p>
                            @error('foundationSlug') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nomor WhatsApp Yayasan</label>
                            <input type="text" wire:model="foundationPhone" placeholder="0812xxxxxxxx" 
                                class="w-full px-6 py-4 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
                            @error('foundationPhone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="mt-12 flex justify-between items-center">
                        <button wire:click="prevStep" class="text-slate-500 font-bold hover:text-slate-800 transition-colors">Kembali</button>
                        
                        <div class="text-right">
                             @if($selectedPlan->price > 0)
                                <button wire:click="nextStep" class="bg-primary-600 hover:bg-primary-700 text-white font-extrabold px-12 py-4 rounded-2xl shadow-xl shadow-primary-200 transition-all flex items-center gap-3">
                                    Lanjut ke Pembayaran <i class="fas fa-arrow-right"></i>
                                </button>
                             @else
                                <button wire:click="register" wire:loading.attr="disabled" class="bg-primary-600 hover:bg-primary-700 text-white font-extrabold px-12 py-4 rounded-2xl shadow-xl shadow-primary-200 transition-all flex items-center gap-3">
                                    <span wire:loading.remove>Selesaikan Pendaftaran</span>
                                    <span wire:loading><i class="fas fa-circle-notch animate-spin mr-2"></i> Memproses...</span>
                                </button>
                             @endif
                        </div>
                    </div>
                </div>

            @elseif($step === 4)
                <!-- Step 4: Billing Summary -->
                <div class="p-10 md:p-16">
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Ringkasan Pembayaran</h2>
                    <p class="text-slate-500 mb-10">Satu langkah lagi untuk mengaktifkan paket Premium Anda.</p>
                    
                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 mb-10">
                        <div class="flex justify-between items-center mb-6 pb-6 border-b border-slate-200">
                            <div>
                                <div class="font-bold text-slate-900 text-lg">Paket {{ $selectedPlan->name }}</div>
                                <div class="text-sm text-slate-500">Aktivasi Dashboard (Satu Kali Bayar)</div>
                            </div>
                            <div class="text-xl font-black text-slate-900">Rp {{ number_format($selectedPlan->price, 0, ',', '.') }}</div>
                        </div>
                        
                        @if($selectedPlan->system_fee_percentage > 0)
                            <div class="flex justify-between items-center mb-6 text-sm">
                                <span class="text-slate-500 font-medium">Biaya Maintenance (Sistem)</span>
                                <span class="text-slate-900 font-bold">{{ $selectedPlan->system_fee_percentage }}% per transaksi</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-center font-black text-xl text-primary-600">
                            <span>Total Pembayaran Pokok</span>
                            <span>Rp {{ number_format($selectedPlan->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <button wire:click="prevStep" class="text-slate-500 font-bold hover:text-slate-800 transition-colors">Kembali</button>
                        <button wire:click="register" wire:loading.attr="disabled" class="bg-primary-600 hover:bg-primary-700 text-white font-extrabold px-12 py-4 rounded-2xl shadow-xl shadow-primary-200 transition-all flex items-center gap-3">
                            <span wire:loading.remove>Layar Pembayaran <i class="fas fa-external-link-alt ml-2"></i></span>
                            <span wire:loading><i class="fas fa-circle-notch animate-spin mr-2"></i> Menyiapkan Invoice...</span>
                        </button>
                    </div>
                </div>
            @endif
        </div>
        
        <p class="text-center mt-8 text-slate-400 text-sm font-medium">
            Punya pertanyaan? <a href="#" class="text-primary-600 hover:underline">Hubungi Tim Support Kami</a>
        </p>
    </div>

    <!-- Duitku Pop Script -->
    <script src="{{ config('duitku.sandbox') ? 'https://app-sandbox.duitku.com/lib/js/duitku.js' : 'https://app-prod.duitku.com/lib/js/duitku.js' }}"></script>
    <script>
        window.addEventListener('open-duitku-pop', event => {
            const data = event.detail[0];
            checkout.process(data.reference, {
                successEvent: function(result) {
                    window.location.href = data.returnUrl + '?status=success&reference=' + result.reference;
                },
                pendingEvent: function(result) {
                    window.location.href = data.returnUrl + '?status=pending&reference=' + result.reference;
                },
                errorEvent: function(result) {
                    alert('Pembayaran gagal atau dibatalkan.');
                },
                closeEvent: function(result) {
                    // Do nothing or show message
                }
            });
        });
    </script>
</div>

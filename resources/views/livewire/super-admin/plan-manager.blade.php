<div class="space-y-8 pb-12">
    <!-- Header with Breadcrumbs & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 text-sm font-bold text-slate-400 uppercase tracking-[0.2em] mb-3">
                <span>Konfigurasi Platform</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-slate-900">Paket & Harga</span>
            </div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Paket SaaS</h2>
            <p class="text-slate-500 font-medium mt-1">Atur jenis layanan, batasan fitur, dan skema harga untuk calon tenant.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="createPlan" class="flex items-center gap-2 px-6 py-3.5 bg-indigo-600 rounded-2xl text-sm font-black text-white hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20 active:translate-y-0.5">
                <i class="fa-solid fa-plus text-xs"></i> <span class="uppercase tracking-widest">Buat Paket Baru</span>
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl border border-emerald-100 flex items-center shadow-sm shadow-emerald-500/5 animate-in slide-in-from-top duration-300">
            <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center mr-3 shadow-md">
                <i class="fa-solid fa-check"></i>
            </div>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-2xl border border-red-100 flex items-center shadow-sm shadow-red-500/5 animate-in slide-in-from-top duration-300">
            <div class="w-8 h-8 rounded-lg bg-red-500 text-white flex items-center justify-center mr-3 shadow-md">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Plans Display Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($plans as $plan)
        <div class="group bg-white rounded-[2.5rem] shadow-sm border {{ $plan->slug == 'pro' ? 'border-indigo-400 ring-4 ring-indigo-500/5' : 'border-slate-100' }} overflow-hidden flex flex-col transition-all hover:shadow-2xl hover:shadow-indigo-500/10 relative">
            @if(!$plan->is_active)
                <div class="absolute inset-x-0 top-0 bg-slate-900/10 backdrop-blur-[2px] z-10 flex items-center justify-center py-2 border-b border-slate-200">
                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] flex items-center gap-1.5"><i class="fa-solid fa-eye-slash"></i> Draft / Inactive</span>
                </div>
            @endif

            @if($plan->slug == 'pro')
                <div class="absolute top-8 right-8 z-20">
                    <span class="bg-indigo-600 text-white text-[9px] font-black px-3 py-1 rounded-lg uppercase tracking-widest shadow-lg shadow-indigo-600/30">Most Popular</span>
                </div>
            @endif
            
            <!-- Plan Header Info -->
            <div class="p-8 pt-12 border-b border-slate-50 {{ $plan->slug == 'pro' ? 'bg-indigo-50/10' : '' }}">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex flex-col">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight mb-1 group-hover:text-indigo-600 transition-colors">{{ $plan->name }}</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">{{ $plan->slug }}</p>
                    </div>
                </div>
                
                <div class="mb-8">
                    <div class="flex items-baseline gap-1">
                        <span class="text-4xl font-black text-slate-900">Rp{{ number_format($plan->price, 0, ',', '.') }}</span>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">/ Setup</span>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 mt-2">Biaya sekali bayar untuk aktivasi sistem.</p>
                </div>
                
                <div class="bg-white border border-slate-100 p-4 rounded-3xl shadow-sm mb-6 flex items-center">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mr-4 shrink-0 transition-transform group-hover:scale-110">
                        <i class="fa-solid fa-percent text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-800 tracking-tight">{{ number_format($plan->system_fee_percentage, 1) }}% <span class="text-[10px] font-bold text-slate-400 uppercase ml-1 tracking-widest">Maintenance Fee</span></p>
                        <p class="text-[9px] font-bold text-indigo-500 uppercase tracking-wider">Per Transaksi Sukses</p>
                    </div>
                </div>

                <p class="text-sm text-slate-500 font-medium leading-relaxed italic">"{{ $plan->description }}"</p>
            </div>
            
            <!-- Features Section -->
            <div class="p-8 bg-slate-50/50 flex-1 flex flex-col">
                <div class="flex items-center gap-2 mb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <span class="w-8 h-[1px] bg-slate-200"></span> Fitur & Batasan <span class="w-8 h-[1px] bg-slate-200"></span>
                </div>
                <ul class="space-y-4 mb-8 flex-1">
                    <li class="flex items-center group/item transition-all">
                        <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mr-3 group-hover/item:scale-110 transition-transform">
                            <i class="fa-solid fa-check text-xs"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-700">Maks. <b>{{ $plan->getLimit('max_users') == -1 ? 'Unlimited' : $plan->getLimit('max_users') }}</b> Admin User</span>
                    </li>
                    <li class="flex items-center group/item transition-all">
                        <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mr-3 group-hover/item:scale-110 transition-transform">
                            <i class="fa-solid fa-check text-xs"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-700">Maks. <b>{{ $plan->getLimit('max_programs') == -1 ? 'Unlimited' : $plan->getLimit('max_programs') }}</b> Program Aktif</span>
                    </li>
                    <li class="flex items-center group/item transition-all {{ !(isset($plan->features['custom_domain']) && $plan->features['custom_domain']) ? 'opacity-40 grayscale' : '' }}">
                        <div class="w-6 h-6 rounded-lg {{ (isset($plan->features['custom_domain']) && $plan->features['custom_domain']) ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center mr-3">
                            <i class="fa-solid fa-{{ (isset($plan->features['custom_domain']) && $plan->features['custom_domain']) ? 'globe' : 'minus' }} text-xs"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-700 tracking-tight">Custom Domain Support</span>
                    </li>
                    <li class="flex items-center group/item transition-all {{ !(isset($plan->features['whatsapp']) && $plan->features['whatsapp']) ? 'opacity-40 grayscale' : '' }}">
                        <div class="w-6 h-6 rounded-lg {{ (isset($plan->features['whatsapp']) && $plan->features['whatsapp']) ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center mr-3">
                            <i class="fa-solid fa-{{ (isset($plan->features['whatsapp']) && $plan->features['whatsapp']) ? 'comment-dots' : 'minus' }} text-xs"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-700 tracking-tight">WhatsApp Notification</span>
                    </li>
                </ul>

                <!-- Plan Actions Row -->
                <div class="flex items-center justify-between gap-3 pt-6 border-t border-slate-100">
                    <div class="flex gap-2">
                        <button wire:click="editPlan({{ $plan->id }})" class="w-10 h-10 rounded-xl bg-white text-slate-400 hover:bg-white hover:text-indigo-600 hover:shadow-xl hover:shadow-indigo-500/10 border border-slate-100 transition-all flex items-center justify-center" title="Edit Paket">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button wire:click="toggleStatus({{ $plan->id }})" class="w-10 h-10 rounded-xl bg-white text-slate-400 hover:bg-white hover:text-amber-500 hover:shadow-xl hover:shadow-amber-500/10 border border-slate-100 transition-all flex items-center justify-center" title="{{ $plan->is_active ? 'Sembunyikan' : 'Tampilkan' }}">
                            <i class="fa-solid {{ $plan->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                        </button>
                    </div>
                    <button wire:click="deletePlan({{ $plan->id }})" 
                        onclick="confirm('Yakin ingin menghapus paket ini? Hanya bisa dihapus jika tidak ada tenant yang memakai.') || event.stopImmediatePropagation()" 
                        class="w-10 h-10 rounded-xl bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center shadow-sm" title="Hapus">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach

        @if(count($plans) < 6)
        <button wire:click="createPlan" class="group relative rounded-[2.5rem] border-2 border-dashed border-slate-200 p-8 flex flex-col items-center justify-center text-center hover:border-indigo-300 hover:bg-indigo-50/50 transition-all min-h-[400px]">
            <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                <i class="fa-solid fa-plus text-3xl"></i>
            </div>
            <h4 class="text-xl font-black text-slate-800 tracking-tight transition-colors group-hover:text-indigo-600">Buat Paket Baru</h4>
            <p class="text-sm font-medium text-slate-400 mt-2 max-w-[200px]">Mulai definisikan skema layanan baru untuk platform Anda.</p>
        </button>
        @endif
    </div>

    <!-- Modal Manager (Premium Design) -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 lg:p-12 overflow-hidden" x-data x-cloak>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" wire:click="$set('isModalOpen', false)"></div>

        <div class="relative bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl flex flex-col max-h-full overflow-hidden animate-in zoom-in duration-300">
            <!-- Modal Header -->
            <div class="px-10 py-8 border-b border-slate-100 flex items-center justify-between shrink-0">
                <div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ $planId ? 'Edit Konfigurasi Paket' : 'Definisikan Paket Baru' }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Lengkapi parameter paket langganan</p>
                </div>
                <button wire:click="$set('isModalOpen', false)" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-all"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form wire:submit.prevent="savePlan" class="flex flex-col flex-1 overflow-hidden">
                <!-- Modal Content (Scrollable) -->
                <div class="flex-1 overflow-y-auto px-10 py-10 space-y-10 scrollbar-thin">
                    
                    <!-- Basic Info -->
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-3 ml-1">Nama Paket</label>
                                <input type="text" wire:model.live="name" placeholder="Misal: Enterprise" class="w-full px-5 py-4 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-800">
                                @error('name') <span class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-2 ml-2 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-3 ml-1">Slug Identitas</label>
                                <input type="text" wire:model="slug" class="w-full px-5 py-4 rounded-2xl border border-slate-200 bg-slate-100 font-bold text-slate-400 cursor-not-allowed" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-3 ml-1">Deskripsi Pemasaran</label>
                            <textarea wire:model="description" rows="2" placeholder="Jelaskan keunggulan paket ini kepada calon tenant..." class="w-full px-5 py-4 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-700"></textarea>
                        </div>
                    </div>

                    <!-- Pricing & Global Fee -->
                    <div class="p-8 rounded-[2rem] bg-indigo-50/50 border border-indigo-100 space-y-6">
                        <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest flex items-center gap-2"><i class="fa-solid fa-money-bill-transfer"></i> Finansial & Fee</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Aktivasi (Sekali Bayar)</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                                    <input type="number" wire:model="price" class="w-full pl-12 pr-5 py-4 rounded-2xl border border-slate-200 bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-black text-slate-800">
                                </div>
                                @error('price') <span class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-2 ml-2 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Maintenance Fee (%)</label>
                                <div class="relative">
                                    <input type="number" step="0.01" wire:model="system_fee_percentage" class="w-full px-5 py-4 rounded-2xl border border-slate-200 bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-black text-slate-800">
                                    <span class="absolute right-5 top-1/2 -translate-y-1/2 font-bold text-slate-400">%</span>
                                </div>
                                @error('system_fee_percentage') <span class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-2 ml-2 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Features & Limits Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">Fitur Aktif</h4>
                            <div class="space-y-4">
                                @foreach(['dynamic_program' => 'Program Dinamis (Paket)', 'custom_domain' => 'Custom Domain', 'whatsapp' => 'WhatsApp Notify', 'fundraiser' => 'Manajemen Fundraiser', 'qurban' => 'Modul Qurban', 'zakat' => 'Modul Zakat'] as $key => $label)
                                <label class="flex items-center group cursor-pointer">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" wire:model="features.{{ $key }}" class="peer sr-only">
                                        <div class="w-10 h-6 bg-slate-200 rounded-full peer-checked:bg-indigo-600 transition-colors"></div>
                                        <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-4"></div>
                                    </div>
                                    <span class="ml-4 text-xs font-bold text-slate-600 group-hover:text-slate-900 transition-colors">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="space-y-6">
                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">Batasan Sistem</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Maks. Admin User</label>
                                    <input type="number" wire:model="limits.max_users" class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 transition-all font-bold text-slate-700">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Maks. Program Aktif</label>
                                    <input type="number" wire:model="limits.max_programs" class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 transition-all font-bold text-slate-700">
                                    <p class="text-[9px] text-slate-400 mt-1 ml-1 leading-none font-medium text-right italic">Gunakan -1 untuk tanpa batasan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-10 py-8 bg-slate-50 border-t border-slate-100 flex items-center justify-between shrink-0">
                    <button type="button" wire:click="$set('isModalOpen', false)" class="text-xs font-black text-slate-400 hover:text-slate-700 uppercase tracking-[0.2em] transition-colors">Batal & Tutup</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-10 py-4 rounded-2xl shadow-xl shadow-indigo-600/20 transition-all hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-3">
                        <span class="tracking-widest uppercase text-xs" wire:loading.remove wire:target="savePlan">Simpan Konfigurasi</span>
                        <span class="tracking-widest uppercase text-xs" wire:loading wire:target="savePlan">Memproses...</span>
                        <i class="fa-solid fa-circle-check text-indigo-400" wire:loading.remove wire:target="savePlan"></i>
                        <i class="fa-solid fa-circle-notch animate-spin text-white" wire:loading wire:target="savePlan"></i>
                    </button>
                </div>
            </form>

        </div>
    </div>
    @endif
</div>

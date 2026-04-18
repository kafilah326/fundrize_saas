<div class="max-w-4xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-3 text-sm font-bold text-slate-400 uppercase tracking-[0.2em] mb-3">
            <a href="{{ route('superadmin.tenants') }}" class="hover:text-indigo-600 transition-colors">Manajemen Tenant</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-slate-900">Registrasi Baru</span>
        </div>
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Tambah Yayasan Baru</h2>
        <p class="text-slate-500 font-medium mt-1">Daftarkan lembaga sosial baru ke dalam ekosistem Fundrize SaaS.</p>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <form wire:submit="save">
            <!-- Form Sections -->
            <div class="p-8 md:p-12 space-y-12">
                
                <!-- Section 1: Identitas Yayasan -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-black text-slate-800 tracking-tight mb-2">Identitas Yayasan</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">Informasi profil dasar dan alamat akses subdomain yayasan.</p>
                    </div>
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2 ml-1">Nama Lengkap Yayasan</label>
                            <input type="text" wire:model.live="name" placeholder="Misal: Yayasan Peduli Kemanusiaan" 
                                class="w-full px-5 py-4 rounded-2xl border border-slate-200 bg-slate-50/30 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 placeholder:text-slate-400">
                            @error('name') <span class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-2 ml-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2 ml-1">Subdomain (Slug)</label>
                            <div class="flex group">
                                <input type="text" wire:model="slug" placeholder="yayasan-peduli" 
                                    class="flex-1 px-5 py-4 rounded-l-2xl border border-slate-200 bg-slate-50/30 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 placeholder:text-slate-400">
                                <span class="inline-flex items-center px-6 rounded-r-2xl border border-l-0 border-slate-200 bg-slate-100 text-slate-500 font-bold select-none">
                                    .{{ config('tenancy.base_domain') }}
                                </span>
                            </div>
                            <p class="mt-2 text-[10px] text-slate-400 font-medium ml-2">Akan diakses melalui: <span class="text-indigo-600">https://[slug].{{ config('tenancy.base_domain') }}</span></p>
                            @error('slug') <span class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-2 ml-2 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-50"></div>

                <!-- Section 2: Kontak & Paket -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-black text-slate-800 tracking-tight mb-2">Kontak & Paket</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">Detail kontak pengelola dan pemilihan tingkat layanan.</p>
                    </div>
                    <div class="md:col-span-2 space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2 ml-1">Email Pengelola</label>
                                <input type="email" wire:model="email" placeholder="admin@yayasan.com" 
                                    class="w-full px-5 py-4 rounded-2xl border border-slate-200 bg-slate-50/30 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 placeholder:text-slate-400">
                                @error('email') <span class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-2 ml-2 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2 ml-1">WhatsApp / No. HP</label>
                                <input type="text" wire:model="phone" placeholder="08xxxxxxxxxx" 
                                    class="w-full px-5 py-4 rounded-2xl border border-slate-200 bg-slate-50/30 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 placeholder:text-slate-400">
                                @error('phone') <span class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-2 ml-2 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2 ml-1">Pilih Paket Layanan</label>
                            <select wire:model="plan_id" 
                                class="w-full px-5 py-4 rounded-2xl border border-slate-200 bg-slate-50/30 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-700 appearance-none">
                                <option value="">-- Pilih Paket Langganan --</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} (Rp{{ number_format($plan->price, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                            @error('plan_id') <span class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-2 ml-2 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-50"></div>

                <!-- Section 3: Keamanan -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-black text-slate-800 tracking-tight mb-2">Keamanan Utama</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">Kredensial untuk akses login administrator pertama.</p>
                    </div>
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2 ml-1">Password Default</label>
                            <div class="relative group">
                                <input type="password" wire:model="password" placeholder="Minimal 8 karakter" 
                                    class="w-full px-5 py-4 rounded-2xl border border-slate-200 bg-slate-50/30 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-800 placeholder:text-slate-400">
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                            </div>
                            @error('password') <span class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-2 ml-2 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

            </div>

            <!-- Form Actions -->
            <div class="px-8 py-8 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('superadmin.tenants') }}" class="text-sm font-black text-slate-500 hover:text-slate-800 transition-colors uppercase tracking-widest px-6 py-4">Batal & Kembali</a>
                <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-10 py-4 rounded-2xl shadow-xl shadow-indigo-600/20 transition-all hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-3">
                    <span wire:loading.remove>Simpan & Daftarkan Yayasan</span>
                    <span wire:loading><i class="fa-solid fa-circle-notch animate-spin"></i> Memproses...</span>
                    <i class="fa-solid fa-arrow-right text-xs" wire:loading.remove></i>
                </button>
            </div>
        </form>
    </div>
</div>

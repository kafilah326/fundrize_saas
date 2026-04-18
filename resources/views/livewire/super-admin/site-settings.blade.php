<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Pengaturan Situs</h2>
            <p class="text-slate-500 font-medium mt-1">Kelola konten dan tampilan halaman depan paltform Fundrize SaaS.</p>
        </div>
        <div>
            <button wire:click="saveSettings" wire:loading.attr="disabled"
                class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold shadow-lg shadow-indigo-200 transition-all flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up" wire:loading.remove wire:target="saveSettings"></i>
                <i class="fa-solid fa-circle-notch animate-spin" wire:loading wire:target="saveSettings"></i>
                Simpan Perubahan
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 flex items-center shadow-sm">
            <i class="fa-solid fa-circle-check mr-3 text-emerald-500 text-lg"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Tabs -->
        <div class="lg:col-span-1 space-y-2">
            <button wire:click="setTab('hero')" 
                class="w-full text-left px-6 py-4 rounded-2xl font-bold transition-all flex items-center gap-3 {{ $activeTab === 'hero' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
                <i class="fa-solid fa-rocket"></i> Hero Section
            </button>
            <button wire:click="setTab('features')" 
                class="w-full text-left px-6 py-4 rounded-2xl font-bold transition-all flex items-center gap-3 {{ $activeTab === 'features' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
                <i class="fa-solid fa-star"></i> Fitur Unggulan
            </button>
            <button wire:click="setTab('faq')" 
                class="w-full text-left px-6 py-4 rounded-2xl font-bold transition-all flex items-center gap-3 {{ $activeTab === 'faq' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
                <i class="fa-solid fa-circle-question"></i> Pertanyaan (FAQ)
            </button>
            <button wire:click="setTab('cta')" 
                class="w-full text-left px-6 py-4 rounded-2xl font-bold transition-all flex items-center gap-3 {{ $activeTab === 'cta' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
                <i class="fa-solid fa-bullhorn"></i> Final CTA
            </button>
        </div>

        <!-- Content Panel -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-12 overflow-hidden">
                
                <!-- Hero Tab -->
                @if($activeTab === 'hero')
                    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 mb-2">Konfigurasi Hero Section</h3>
                            <p class="text-sm text-slate-500">Sesuaikan tampilan sambutan pertama bagi pengunjung situs Anda.</p>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Badge Text</label>
                                    <input type="text" wire:model="hero_badge" 
                                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 text-sm font-bold transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">CTA Button Text</label>
                                    <input type="text" wire:model="hero_cta_text" 
                                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 text-sm font-bold transition-all">
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Hero Title</label>
                                <input type="text" wire:model="hero_title" 
                                    class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 text-sm font-bold transition-all">
                                <p class="text-[10px] text-slate-400 font-medium italic pl-1">Tips: Gunakan &lt;br&gt; untuk membuat baris baru pada judul.</p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Hero Subtitle</label>
                                <textarea wire:model="hero_subtitle" rows="3"
                                    class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 text-sm font-bold transition-all"></textarea>
                            </div>

                            <div class="space-y-4 pt-4">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Hero Mockup Image</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                                    <div class="space-y-4">
                                        <div class="relative group cursor-pointer">
                                            <input type="file" wire:model="hero_image" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer">
                                            <div class="border-2 border-dashed border-slate-200 rounded-[2rem] p-8 text-center group-hover:border-indigo-400 group-hover:bg-indigo-50 transition-all">
                                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-300 group-hover:text-indigo-500 mb-3 block"></i>
                                                <span class="text-xs font-bold text-slate-500 group-hover:text-indigo-600">Klik atau Tarik Gambar Ke Sini</span>
                                                <p class="text-[10px] text-slate-400 mt-1">PNG, JPG atau WEBP (Maks. 2MB)</p>
                                            </div>
                                        </div>
                                        <div wire:loading wire:target="hero_image" class="text-[10px] font-bold text-indigo-500">
                                            <i class="fa-solid fa-circle-notch animate-spin mr-1"></i> Mengunggah...
                                        </div>
                                    </div>

                                    @if ($hero_image || $existing_hero_image)
                                        <div class="relative rounded-2xl overflow-hidden border border-slate-200 shadow-sm bg-slate-50">
                                            @if ($hero_image)
                                                <img src="{{ $hero_image->temporaryUrl() }}" class="w-full h-auto object-cover">
                                                <div class="absolute top-2 right-2 px-2 py-1 bg-amber-500 text-white text-[8px] font-black rounded-lg uppercase tracking-widest shadow-lg">Preview Baru</div>
                                            @elseif($existing_hero_image)
                                                <img src="{{ asset('storage/' . $existing_hero_image) }}" class="w-full h-auto object-cover">
                                                <div class="absolute top-2 right-2 px-2 py-1 bg-indigo-600 text-white text-[8px] font-black rounded-lg uppercase tracking-widest shadow-lg">Aktif</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Features Tab -->
                @if($activeTab === 'features')
                    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 mb-2">Fitur Unggulan</h3>
                            <p class="text-sm text-slate-500">Atur judul dan deskripsi bagian fitur yang ditampilkan di landing page.</p>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Section Title</label>
                                <input type="text" wire:model="features_title" 
                                    class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 text-sm font-bold transition-all">
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Section Subtitle</label>
                                <textarea wire:model="features_subtitle" rows="3"
                                    class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 text-sm font-bold transition-all"></textarea>
                            </div>

                            <div class="p-6 rounded-2xl bg-amber-50 border border-amber-100 flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 text-xl shadow-sm">
                                    <i class="fa-solid fa-lightbulb"></i>
                                </div>
                                <p class="text-xs text-amber-700 font-medium leading-relaxed">
                                    <strong>Info:</strong> Konten per masing-masing box fitur (Donasi, Fundraiser, Tagungan) saat ini masih menggunakan konten standar sistem untuk menjaga konsistensi nilai platform.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- FAQ Tab -->
                @if($activeTab === 'faq')
                    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-slate-800 mb-2">Pertanyaan Populer (FAQ)</h3>
                                <p class="text-sm text-slate-500">Daftar tanya jawab untuk membantu meyakinkan calon tenant.</p>
                            </div>
                            <button wire:click="addFaq" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-all">
                                + Tambah FAQ
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($faqs as $index => $faq)
                                <div class="p-6 rounded-2xl border border-slate-100 bg-slate-50/50 space-y-4 relative group">
                                    <button wire:click="removeFaq({{ $index }})" 
                                        class="absolute top-4 right-4 text-slate-300 hover:text-rose-500 transition-all opacity-0 group-hover:opacity-100">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                    
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Pertanyaan #{{ $index + 1 }}</label>
                                        <input type="text" wire:model="faqs.{{ $index }}.q" placeholder="Masukkan pertanyaan..."
                                            class="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl focus:ring-4 focus:ring-indigo-500/5 text-sm font-bold transition-all shadow-sm">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Jawaban</label>
                                        <textarea wire:model="faqs.{{ $index }}.a" rows="2" placeholder="Masukkan jawaban lengkap..."
                                            class="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl focus:ring-4 focus:ring-indigo-500/5 text-sm font-bold transition-all shadow-sm"></textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- CTA Tab -->
                @if($activeTab === 'cta')
                    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 mb-2">Final Call to Action (CTA)</h3>
                            <p class="text-sm text-slate-500">Bagian penutup di paling bawah halaman untuk mengajak registrasi.</p>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">CTA Title</label>
                                <input type="text" wire:model="cta_title" 
                                    class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 text-sm font-bold transition-all">
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">CTA Subtitle</label>
                                <textarea wire:model="cta_subtitle" rows="3"
                                    class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 text-sm font-bold transition-all"></textarea>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

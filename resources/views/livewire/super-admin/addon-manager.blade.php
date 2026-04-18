<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Add-on</h2>
            <p class="text-sm text-slate-500 font-medium mt-1">Kelola fitur tambahan dan peningkatan kapasitas untuk semua paket.</p>
        </div>
        <button wire:click="createAddon" class="flex items-center justify-center space-x-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl transition-all shadow-lg shadow-indigo-600/20 font-bold text-sm">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Add-on Baru</span>
        </button>
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center space-x-3 shadow-sm animate-fade-in shadow-emerald-500/5">
            <i class="fa-solid fa-circle-check text-xl"></i>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Add-on</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Tipe & Target</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Harga & Durasi</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Status</th>
                        <th class="px-6 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($addons as $addon)
                        <tr class="hover:bg-slate-50/40 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-800">{{ $addon->name }}</span>
                                    <span class="text-[10px] font-medium text-slate-400 mt-0.5">{{ $addon->slug }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-tighter {{ $addon->type === 'feature' ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600' }}">
                                            {{ $addon->type === 'feature' ? 'Fitur' : 'Limit' }}
                                        </span>
                                        <span class="text-xs font-semibold text-slate-600">{{ $addon->type === 'limit' ? '+' . $addon->value : '' }} {{ $addon->target }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-800">Rp {{ number_format($addon->price, 0, ',', '.') }}</span>
                                    <span class="text-[10px] font-bold uppercase tracking-tight {{ $addon->duration === 'one_time' ? 'text-indigo-500' : 'text-rose-500' }}">
                                        {{ $addon->duration === 'one_time' ? 'Lifetime' : 'Per Bulan' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <button wire:click="toggleStatus({{ $addon->id }})" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $addon->is_active ? 'bg-indigo-600' : 'bg-slate-200' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $addon->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <button wire:click="editAddon({{ $addon->id }})" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </button>
                                    <button wire:confirm="Yakin ingin menghapus add-on ini?" wire:click="deleteAddon({{ $addon->id }})" class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-box-open text-4xl mb-4 block opacity-20"></i>
                                <p class="text-sm font-medium">Belum ada add-on yang ditambahkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50/50">
            {{ $addons->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-zoom-in">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xl font-black text-slate-800 tracking-tight">{{ $addonId ? 'Edit Add-on' : 'Tambah Add-on Baru' }}</h3>
                <button wire:click="$set('isModalOpen', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div class="p-8 space-y-4 max-h-[70vh] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Add-on</label>
                        <input type="text" wire:model.blur="name" class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm">
                        @error('name') <span class="text-rose-500 text-[10px] mt-1 font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Slug (URL)</label>
                        <input type="text" wire:model="slug" class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm">
                        @error('slug') <span class="text-rose-500 text-[10px] mt-1 font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Deskripsi</label>
                    <textarea wire:model="description" class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm" rows="3"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Harga (Rp)</label>
                        <input type="number" wire:model="price" class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Durasi</label>
                        <select wire:model="duration" class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm">
                            <option value="one_time">Lifetime (Sekali Bayar)</option>
                            <option value="monthly">Bulanan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tipe</label>
                        <select wire:model.live="type" class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm">
                            <option value="limit">Kapasitas (Limit)</option>
                            <option value="feature">Fitur Baru</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Target</label>
                        <select wire:model.live="target" class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm">
                            <option value="">Pilih Target...</option>
                            @if($type === 'limit')
                                @foreach($limitTargets as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            @else
                                @foreach($featureTargets as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                @if($type === 'limit')
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Jumlah Tambahan (Value)</label>
                    <input type="number" wire:model="value" class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 font-semibold text-sm" placeholder="Contoh: 10">
                </div>
                @endif
            </div>
            <div class="px-8 py-6 bg-slate-50 flex items-center justify-end gap-3">
                <button wire:click="$set('isModalOpen', false)" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">Batal</button>
                <button wire:click="saveAddon" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-600/20 font-bold text-sm transition-all">
                    Simpan Add-on
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

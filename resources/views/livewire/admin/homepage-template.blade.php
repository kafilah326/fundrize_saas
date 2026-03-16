<div class="space-y-6">
    <div class="bg-white overflow-hidden shadow-soft rounded-2xl border border-gray-100">
        <div class="p-6 md:p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Template Halaman Utama</h2>

            @if (session()->has('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3 text-green-700">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <div>
                        <h4 class="font-bold text-sm">Berhasil!</h4>
                        <p class="text-xs">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Template</label>
                        <select wire:model="selectedTemplate" 
                                class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                            @foreach($availableTemplates as $slug => $label)
                                <option value="{{ $slug }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-sm text-gray-500 italic">
                            Template aktif saat ini: <span class="font-bold text-primary">{{ $availableTemplates[$selectedTemplate] ?? $selectedTemplate }}</span>
                        </p>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button wire:click="save" 
                                class="bg-primary hover:bg-primary-hover text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all hover:-translate-y-0.5 inline-flex items-center">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>











        </div>
    </div>
</div>

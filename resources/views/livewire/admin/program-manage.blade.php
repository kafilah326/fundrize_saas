<div>
    @section('title', 'Kelola Program')
    @section('header', 'Kelola Program Donasi')

    <div class="space-y-6">
        <!-- Program Summary Header -->
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-gray-100 flex flex-col md:flex-row gap-6 items-start">
            <div class="flex-shrink-0">
                <img src="{{ $program->image }}" 
                     class="w-32 h-32 rounded-xl object-cover shadow-md border border-gray-100" alt="{{ $program->title }}">
            </div>
            <div class="flex-grow">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $program->title }}</h2>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($program->categories as $category)
                                <span class="px-2 py-1 bg-blue-50 text-blue-600 text-xs rounded-lg font-medium border border-blue-100">{{ $category->name }}</span>
                            @endforeach
                            @foreach($program->akads as $akad)
                                <span class="px-2 py-1 bg-emerald-50 text-emerald-600 text-xs rounded-lg font-medium border border-emerald-100">{{ $akad->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <a href="{{ route('admin.programs') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Target</p>
                        <p class="text-lg font-bold text-gray-900">Rp {{ number_format($program->target_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-xl p-3 border border-orange-100">
                        <p class="text-xs text-orange-600 uppercase font-semibold">Terkumpul</p>
                        <p class="text-lg font-bold text-orange-700">Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Progres</p>
                        <div class="flex items-center gap-2">
                            <div class="flex-grow bg-gray-200 rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full" style="width: {{ $program->progress }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-700">{{ $program->progress }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex space-x-1 bg-gray-100 p-1 rounded-xl w-fit">
            <button wire:click="$set('activeTab', 'updates')" 
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-all {{ $activeTab === 'updates' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="fa-solid fa-bullhorn mr-2"></i> Kabar Terbaru
            </button>
            <button wire:click="$set('activeTab', 'distributions')" 
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-all {{ $activeTab === 'distributions' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="fa-solid fa-hand-holding-dollar mr-2"></i> Penyaluran Dana
            </button>
        </div>

        <!-- Content Area -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden min-h-[400px]">
            
            <!-- Updates Tab -->
            <div x-show="$wire.activeTab === 'updates'" class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Kabar Terbaru ({{ $updates->total() }})</h3>
                    <button wire:click="createUpdate" class="px-4 py-2 bg-primary text-white rounded-xl text-sm font-medium hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all hover:-translate-y-0.5">
                        <i class="fa-solid fa-plus mr-2"></i> Buat Kabar
                    </button>
                </div>

                <div class="space-y-4">
                    @forelse($updates as $update)
                        <div class="border border-gray-100 rounded-xl p-4 hover:bg-gray-50 transition-colors flex flex-col sm:flex-row gap-4">
                            <div class="flex-shrink-0 pt-1">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                    <i class="fa-solid fa-newspaper"></i>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $update->title }}</h4>
                                        <p class="text-xs text-gray-500 mb-2">
                                            <i class="fa-regular fa-calendar-days mr-1"></i> {{ $update->published_at->format('d M Y') }}
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button wire:click="editUpdate({{ $update->id }})" class="text-gray-400 hover:text-blue-600 transition-colors">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button wire:click="confirmDeleteUpdate({{ $update->id }})" class="text-gray-400 hover:text-red-600 transition-colors">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600 line-clamp-2">{!! strip_tags($update->description) !!}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-500">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-regular fa-newspaper text-2xl text-gray-300"></i>
                            </div>
                            <p>Belum ada kabar terbaru untuk program ini.</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-4">
                    {{ $updates->links() }}
                </div>
            </div>

            <!-- Distributions Tab -->
            <div x-show="$wire.activeTab === 'distributions'" class="p-6" style="display: none;">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Riwayat Penyaluran ({{ $distributions->total() }})</h3>
                    <button wire:click="createDistribution" class="px-4 py-2 bg-primary text-white rounded-xl text-sm font-medium hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all hover:-translate-y-0.5">
                        <i class="fa-solid fa-plus mr-2"></i> Input Penyaluran
                    </button>
                </div>

                <div class="space-y-4">
                    @forelse($distributions as $dist)
                        <div class="border border-gray-100 rounded-xl p-4 hover:bg-gray-50 transition-colors flex flex-col sm:flex-row gap-4">
                            <div class="flex-shrink-0 pt-1">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold text-gray-900">Rp {{ number_format($dist->amount_distributed, 0, ',', '.') }}</h4>
                                        <p class="text-xs text-gray-500 mb-2">
                                            <i class="fa-regular fa-calendar-days mr-1"></i> {{ $dist->documentation_date->format('d M Y') }}
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button wire:click="editDistribution({{ $dist->id }})" class="text-gray-400 hover:text-blue-600 transition-colors">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button wire:click="confirmDeleteDistribution({{ $dist->id }})" class="text-gray-400 hover:text-red-600 transition-colors">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600 rich-text-content">{!! $dist->description !!}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-500">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-hand-holding-dollar text-2xl text-gray-300"></i>
                            </div>
                            <p>Belum ada riwayat penyaluran dana.</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-4">
                    {{ $distributions->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->

    <!-- Update Modal -->
    <div x-data="{ show: $wire.entangle('showUpdateModal') }"
         x-show="show"
         class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" @click="show = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="storeUpdate">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ $updateId ? 'Edit Kabar' : 'Buat Kabar Baru' }}</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Judul Kabar</label>
                                <input type="text" wire:model="updateTitle" class="block w-full mt-1 border-gray-300 rounded-xl shadow-sm focus:ring-primary focus:border-primary py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                @error('updateTitle') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Publish</label>
                                <input type="date" wire:model="updatePublishedAt" class="block w-full mt-1 border-gray-300 rounded-xl shadow-sm focus:ring-primary focus:border-primary py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                @error('updatePublishedAt') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                <div wire:ignore class="rounded-xl overflow-hidden bg-white border border-gray-300 shadow-sm focus-within:border-primary focus-within:ring focus-within:ring-primary/20 transition-all" 
                                     x-data="quillEditor($wire.entangle('updateDescription').live)">
                                    <div x-ref="quillEditor" class="min-h-[150px]"></div>
                                </div>
                                @error('updateDescription') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Simpan
                        </button>
                        <button type="button" wire:click="$set('showUpdateModal', false)" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Distribution Modal -->
    <div x-data="{ show: $wire.entangle('showDistributionModal') }"
         x-show="show"
         class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" @click="show = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="storeDistribution">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ $distributionId ? 'Edit Penyaluran' : 'Input Penyaluran Baru' }}</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah Disalurkan (Rp)</label>
                                <input type="number" wire:model="distributionAmount" class="block w-full mt-1 border-gray-300 rounded-xl shadow-sm focus:ring-primary focus:border-primary py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                @error('distributionAmount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Dokumentasi</label>
                                <input type="date" wire:model="distributionDate" class="block w-full mt-1 border-gray-300 rounded-xl shadow-sm focus:ring-primary focus:border-primary py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                @error('distributionDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                                <div wire:ignore class="rounded-xl overflow-hidden bg-white border border-gray-300 shadow-sm focus-within:border-primary focus-within:ring focus-within:ring-primary/20 transition-all" 
                                     x-data="quillEditor($wire.entangle('distributionDescription').live)">
                                    <div x-ref="quillEditor" class="min-h-[150px]"></div>
                                </div>
                                @error('distributionDescription') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Simpan
                        </button>
                        <button type="button" wire:click="$set('showDistributionModal', false)" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal (Generic) -->
    <div x-data="{ show: $wire.entangle('confirmingUpdateDeletion') }"
         x-show="show"
         class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" @click="show = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Hapus Kabar?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus kabar ini?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteUpdate" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Hapus
                    </button>
                    <button wire:click="$set('confirmingUpdateDeletion', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ show: $wire.entangle('confirmingDistributionDeletion') }"
         x-show="show"
         class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" @click="show = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Hapus Penyaluran?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus data penyaluran ini?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteDistribution" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Hapus
                    </button>
                    <button wire:click="$set('confirmingDistributionDeletion', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

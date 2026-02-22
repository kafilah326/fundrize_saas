<div>
    @section('title', 'Banner')
    @section('header', 'Manajemen Banner')

    <div class="space-y-6">
        <div class="bg-white overflow-hidden shadow-soft rounded-2xl border border-gray-100">
            <div class="p-6">
                @if (session()->has('success'))
                    <div
                        class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3 text-green-700">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                        <div>
                            <h4 class="font-bold text-sm">Berhasil!</h4>
                            <p class="text-xs">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div
                        class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 text-red-700">
                        <i class="fa-solid fa-circle-xmark text-xl"></i>
                        <div>
                            <h4 class="font-bold text-sm">Gagal!</h4>
                            <p class="text-xs">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Top Controls -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                    <div class="relative w-full sm:w-1/3 group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i
                                class="fa-solid fa-search text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari banner..."
                            class="pl-10 w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 transition-all duration-200 py-2.5 px-4 text-base">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <select wire:model.live="perPage"
                            class="rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 cursor-pointer py-2.5 px-4 text-base transition-colors">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>

                        <button wire:click="create"
                            class="w-full sm:w-auto bg-primary hover:bg-primary-hover text-white font-semibold py-2.5 px-5 rounded-xl inline-flex items-center justify-center transition-all duration-200 shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5">
                            <i class="fa-solid fa-plus mr-2"></i> Tambah Banner
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-hidden rounded-xl border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Gambar</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Judul</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Halaman</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Link</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Periode</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Urutan</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($banners as $banner)
                                    <tr class="hover:bg-orange-50/30 transition-colors duration-150 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img class="h-12 w-20 object-cover rounded-lg shadow-sm group-hover:shadow-md transition-shadow border border-gray-100"
                                                src="{{ $banner->image }}" alt="">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div
                                                class="text-sm font-semibold text-gray-900 group-hover:text-primary transition-colors">
                                                {{ Str::limit($banner->title, 40) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md 
                                        {{ $banner->placement === 'home'
                                            ? 'bg-blue-100 text-blue-800'
                                            : ($banner->placement === 'qurban'
                                                ? 'bg-orange-100 text-orange-800'
                                                : ($banner->placement === 'qurban_tabungan'
                                                    ? 'bg-purple-100 text-purple-800'
                                                    : 'bg-gray-100 text-gray-800')) }}">
                                                {{ $banner->placement === 'qurban' ? 'Qurban' : ($banner->placement === 'qurban_tabungan' ? 'Tabungan Qurban' : 'Home') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <a href="{{ $banner->link_url }}" target="_blank"
                                                class="text-blue-500 hover:text-blue-700 hover:underline truncate w-32 block flex items-center">
                                                <i class="fa-solid fa-up-right-from-square mr-1 text-xs"></i> Link
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex flex-col">
                                                <span class="text-xs text-gray-400">Mulai:
                                                    {{ $banner->start_date ? $banner->start_date->format('d M Y') : '-' }}</span>
                                                <span class="text-xs text-gray-400">Selesai:
                                                    {{ $banner->end_date ? $banner->end_date->format('d M Y') : '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium pl-8">
                                            {{ $banner->priority }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button wire:click="toggleStatus({{ $banner->id }})"
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full transition-colors duration-200 {{ $banner->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                                {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button wire:click="edit({{ $banner->id }})"
                                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button wire:click="confirmDelete({{ $banner->id }})"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <div
                                                    class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400 text-2xl">
                                                    <i class="fa-regular fa-images"></i>
                                                </div>
                                                <p class="text-lg font-medium text-gray-900">Belum ada banner</p>
                                                <p class="text-sm text-gray-500 mt-1">Tambahkan banner untuk promosi di
                                                    halaman utama.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $banners->links() }}
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div x-data="{ show: $wire.entangle('isOpen') }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true" style="display: none;">

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                    @click="show = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                    <form wire:submit.prevent="store">
                        <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900" id="modal-title">
                                    {{ $bannerId ? 'Edit Banner' : 'Tambah Banner Baru' }}
                                </h3>
                                <button type="button" wire:click="closeModal"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fa-solid fa-xmark text-xl"></i>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-6 sm:grid-cols-6">
                                <!-- Placement -->
                                <div class="sm:col-span-6">
                                    <label for="placement"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Halaman</label>
                                    <select wire:model="placement" id="placement"
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                        <option value="home">Home (Carousel)</option>
                                        <option value="qurban">Qurban (Single Banner)</option>
                                        <option value="qurban_tabungan">Tabungan Qurban (Single Banner)</option>
                                    </select>
                                    @error('placement')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Title -->
                                <div class="sm:col-span-6">
                                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">Judul
                                        Banner</label>
                                    <input wire:model="title" type="text" id="title"
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('title')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Image -->
                                <div class="sm:col-span-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Banner
                                        (16:9)</label>
                                    <div
                                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-primary/50 transition-colors bg-gray-50/50">
                                        <div class="space-y-1 text-center">
                                            @if ($image)
                                                <img src="{{ $image->temporaryUrl() }}"
                                                    class="mx-auto h-32 w-auto object-cover rounded-lg shadow-sm mb-3">
                                            @elseif($existingImage)
                                                <img src="{{ Storage::url($existingImage) }}"
                                                    class="mx-auto h-32 w-auto object-cover rounded-lg shadow-sm mb-3">
                                            @else
                                                <div class="mx-auto h-12 w-12 text-gray-400">
                                                    <i class="fa-solid fa-cloud-arrow-up text-3xl"></i>
                                                </div>
                                            @endif

                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label for="file-upload"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-hover focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                                    <span>Upload a file</span>
                                                    <input id="file-upload" wire:model="image" type="file"
                                                        accept="image/*" class="sr-only">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                        </div>
                                    </div>
                                    <div wire:loading wire:target="image"
                                        class="text-xs text-primary mt-1 font-medium animate-pulse">Uploading...</div>
                                    @error('image')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Link URL -->
                                <div class="sm:col-span-4">
                                    <label for="link_url" class="block text-sm font-semibold text-gray-700 mb-1">Link
                                        URL</label>
                                    <div class="relative rounded-xl shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fa-solid fa-link text-gray-400"></i>
                                        </div>
                                        <input wire:model="link_url" type="url" id="link_url"
                                            placeholder="https://"
                                            class="pl-10 block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    </div>
                                    @error('link_url')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- CTA Text -->
                                <div class="sm:col-span-2">
                                    <label for="cta_text" class="block text-sm font-semibold text-gray-700 mb-1">Teks
                                        Tombol</label>
                                    <input wire:model="cta_text" type="text" id="cta_text"
                                        placeholder="Donasi Sekarang"
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('cta_text')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Dates -->
                                <div class="sm:col-span-3">
                                    <label for="start_date"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Mulai
                                        Tayang</label>
                                    <input wire:model="start_date" type="date" id="start_date"
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('start_date')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="end_date"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Selesai
                                        Tayang</label>
                                    <input wire:model="end_date" type="date" id="end_date"
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('end_date')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Priority & Checkbox -->
                                <div class="sm:col-span-3">
                                    <label for="priority"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Urutan
                                        Prioritas</label>
                                    <input wire:model="priority" type="number" id="priority" min="0"
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    <p class="text-xs text-gray-500 mt-1">Angka lebih kecil tampil lebih dulu.</p>
                                    @error('priority')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="sm:col-span-3 flex items-center pt-8">
                                    <label class="flex items-center cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input wire:model="is_active" type="checkbox"
                                                class="peer h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary cursor-pointer transition-all checked:bg-primary checked:border-transparent">
                                        </div>
                                        <span
                                            class="ml-2 text-sm text-gray-700 group-hover:text-gray-900 font-medium">Aktif</span>
                                    </label>
                                </div>

                                <!-- Description -->
                                <div class="sm:col-span-6">
                                    <label for="description"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi
                                        (Opsional)</label>
                                    <div wire:ignore
                                        class="rounded-xl overflow-hidden bg-white border border-gray-300 shadow-sm focus-within:border-primary focus-within:ring focus-within:ring-primary/20 transition-all"
                                        x-data="quillEditor($wire.entangle('description').live)">
                                        <div x-ref="quillEditor" class="min-h-[150px]"></div>
                                    </div>
                                    @error('description')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-gray-50/80 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-100 rounded-b-2xl">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg shadow-primary/30 px-5 py-2.5 bg-primary text-base font-semibold text-white hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm transition-all hover:-translate-y-0.5">
                                Simpan Banner
                            </button>
                            <button wire:click="closeModal" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all hover:bg-gray-100">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-data="{ show: $wire.entangle('confirmingDeletion') }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                    @click="show = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10 mb-4 sm:mb-0">
                                <i class="fa-solid fa-triangle-exclamation text-red-600 text-lg"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                    Hapus Banner
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menghapus banner ini? Tindakan ini tidak dapat
                                        dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                        <button wire:click="delete" type="button"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Ya, Hapus
                        </button>
                        <button wire:click="$set('confirmingDeletion', false)" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    @section('title', 'Dokumen Legalitas')
    @section('header', 'Manajemen Dokumen Legalitas')

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
                        <input wire:model.live.debounce.300ms="search" type="text"
                            placeholder="Cari dokumen..."
                            class="pl-10 w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 transition-all duration-200 py-2.5 px-4 text-base">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <select wire:model.live="perPage"
                            class="rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 cursor-pointer py-2.5 px-4 text-base transition-colors">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                        </select>

                        <button wire:click="create"
                            class="w-full sm:w-auto bg-primary hover:bg-primary-hover text-white font-semibold py-2.5 px-5 rounded-xl inline-flex items-center justify-center transition-all duration-200 shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5">
                            <i class="fa-solid fa-plus mr-2"></i> Tambah Dokumen
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
                                        Urutan</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Judul Dokumen</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Nomor Dokumen</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Penerbit</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Kadaluarsa</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        File</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($documents as $doc)
                                    <tr class="hover:bg-orange-50/30 transition-colors duration-150 group">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                            {{ $doc->sort_order }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div
                                                class="text-sm font-semibold text-gray-900 group-hover:text-primary transition-colors">
                                                {{ $doc->title }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-600">
                                                {{ Str::limit($doc->document_number, 30) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $doc->issuing_authority ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-green-100 text-green-800">
                                                {{ $doc->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $doc->expiry_date ? $doc->expiry_date->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($doc->file_url)
                                                <a href="{{ $doc->file_url }}" target="_blank"
                                                    class="text-blue-500 hover:text-blue-700 hover:underline flex items-center text-sm">
                                                    <i class="fa-solid fa-file-pdf mr-1 text-xs"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-gray-400 text-sm">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button wire:click="edit({{ $doc->id }})"
                                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button wire:click="confirmDelete({{ $doc->id }})"
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
                                                    <i class="fa-regular fa-file-lines"></i>
                                                </div>
                                                <p class="text-lg font-medium text-gray-900">Belum ada dokumen
                                                    legalitas</p>
                                                <p class="text-sm text-gray-500 mt-1">Tambahkan dokumen legalitas
                                                    yayasan Anda.</p>
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
                    {{ $documents->links() }}
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div x-data="{ show: $wire.entangle('isOpen') }" x-show="show"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true" style="display: none;">

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                    @click="show = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                    aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                    <form wire:submit.prevent="store">
                        <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900" id="modal-title">
                                    {{ $documentId ? 'Edit Dokumen' : 'Tambah Dokumen Baru' }}
                                </h3>
                                <button type="button" wire:click="closeModal"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fa-solid fa-xmark text-xl"></i>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-6 sm:grid-cols-6">
                                <!-- Title -->
                                <div class="sm:col-span-6">
                                    <label for="title"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Judul
                                        Dokumen</label>
                                    <input wire:model="title" type="text" id="title"
                                        placeholder="contoh: Akta Pendirian"
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('title')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Document Number -->
                                <div class="sm:col-span-6">
                                    <label for="document_number"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Nomor
                                        Dokumen</label>
                                    <input wire:model="document_number" type="text" id="document_number"
                                        placeholder="contoh: AHU-0012345.AH.01.04.Tahun 2020"
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('document_number')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Issuing Authority -->
                                <div class="sm:col-span-3">
                                    <label for="issuing_authority"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Penerbit
                                        (Opsional)</label>
                                    <input wire:model="issuing_authority" type="text" id="issuing_authority"
                                        placeholder="contoh: Kemenkumham RI"
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('issuing_authority')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="sm:col-span-3">
                                    <label for="status"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                                    <input wire:model="status" type="text" id="status"
                                        placeholder="contoh: Terverifikasi"
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('status')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Expiry Date -->
                                <div class="sm:col-span-3">
                                    <label for="expiry_date"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Tanggal
                                        Kadaluarsa (Opsional)</label>
                                    <input wire:model="expiry_date" type="date" id="expiry_date"
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('expiry_date')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Sort Order -->
                                <div class="sm:col-span-3">
                                    <label for="sort_order"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Urutan</label>
                                    <input wire:model="sort_order" type="number" id="sort_order" min="0"
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    <p class="text-xs text-gray-500 mt-1">Angka lebih kecil tampil lebih dulu.
                                    </p>
                                    @error('sort_order')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- File Upload -->
                                <div class="sm:col-span-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">File Dokumen
                                        (PDF/Gambar)</label>
                                    <div
                                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-primary/50 transition-colors bg-gray-50/50">
                                        <div class="space-y-1 text-center">
                                            @if ($file)
                                                <div class="flex items-center gap-2 text-primary">
                                                    <i class="fa-solid fa-file-circle-check text-2xl"></i>
                                                    <span
                                                        class="text-sm font-medium">{{ $file->getClientOriginalName() }}</span>
                                                </div>
                                            @elseif($existingFile)
                                                <div class="flex items-center gap-2 text-green-600">
                                                    <i class="fa-solid fa-file-pdf text-2xl"></i>
                                                    <span class="text-sm font-medium">File tersimpan</span>
                                                </div>
                                            @else
                                                <div class="mx-auto h-12 w-12 text-gray-400">
                                                    <i class="fa-solid fa-cloud-arrow-up text-3xl"></i>
                                                </div>
                                            @endif

                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label for="file-upload"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-hover focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                                    <span>Upload file</span>
                                                    <input id="file-upload" wire:model="file" type="file"
                                                        accept=".pdf,.jpg,.jpeg,.png" class="sr-only">
                                                </label>
                                                <p class="pl-1">atau drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PDF, JPG, PNG (maks 5MB)</p>
                                        </div>
                                    </div>
                                    <div wire:loading wire:target="file"
                                        class="text-xs text-primary mt-1 font-medium animate-pulse">Uploading...
                                    </div>
                                    @error('file')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-gray-50/80 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-100 rounded-b-2xl">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg shadow-primary/30 px-5 py-2.5 bg-primary text-base font-semibold text-white hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm transition-all hover:-translate-y-0.5">
                                Simpan Dokumen
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
        <div x-data="{ show: $wire.entangle('confirmingDeletion') }" x-show="show"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true" style="display: none;">

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                    @click="show = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                    aria-hidden="true">&#8203;</span>

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
                                    Hapus Dokumen
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menghapus dokumen ini? Tindakan ini tidak dapat
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

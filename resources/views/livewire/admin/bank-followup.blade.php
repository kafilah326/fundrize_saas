<div>
    @section('title', 'Template Followup')
    @section('header', 'Kelola Template Followup')

    <!-- Actions Bar -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="relative w-full sm:w-64">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-search text-gray-400"></i>
            </div>
            <input wire:model.live="search" type="text"
                class="block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 text-base"
                placeholder="Cari template...">
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
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-white bg-primary hover:bg-primary-hover shadow-lg shadow-primary/30 transform hover:-translate-y-0.5 transition-all duration-200">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Template
            </button>
        </div>
    </div>

    <!-- Content Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Nama Template
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Tipe & Sequence
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Preview Pesan
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($followups as $followup)
                        <tr class="hover:bg-orange-50/30 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $followup->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium max-w-max
                                        {{ $followup->type === 'donasi'
                                            ? 'bg-blue-100 text-blue-800'
                                            : ($followup->type === 'qurban'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-purple-100 text-purple-800') }}">
                                        {{ ucwords(str_replace('_', ' ', $followup->type)) }}
                                    </span>
                                    <span class="text-xs text-gray-500 font-medium ml-1">
                                        <i class="fa-solid fa-list-ol mr-1"></i> {{ $followup->followup_sequence }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600 line-clamp-2 max-w-md" title="{{ $followup->content }}">
                                    {{ $followup->content }}
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $followup->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $followup->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $followup->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3 transition-colors duration-200">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $followup->id }})"
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fa-regular fa-clipboard text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-lg font-medium text-gray-900">Belum ada template</p>
                                    <p class="text-sm text-gray-500 mt-1">Silakan tambahkan template followup baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $followups->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div x-data="{ show: $wire.entangle('showModal') }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">

                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-6 flex items-center">
                                    <i
                                        class="fa-solid {{ $isEditing ? 'fa-pen-to-square' : 'fa-plus-circle' }} mr-2 text-primary"></i>
                                    {{ $isEditing ? 'Edit Template Followup' : 'Buat Template Baru' }}
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                    <!-- Name -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama
                                            Template</label>
                                        <input wire:model="name" type="text"
                                            class="w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 transition-all px-4 py-2.5"
                                            placeholder="Contoh: Followup Donasi Harian">
                                        @error('name')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Is Active -->
                                    <div class="flex items-center h-full pt-6">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                            <div
                                                class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                            </div>
                                            <span class="ms-3 text-sm font-medium text-gray-700">Aktifkan
                                                Template</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                    <!-- Type -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe
                                            Program</label>
                                        <select wire:model.live="type"
                                            class="w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 transition-all px-4 py-2.5">
                                            <option value="donasi">Program Donasi</option>
                                            <option value="qurban">Qurban</option>
                                            <option value="tabungan_qurban">Tabungan Qurban</option>
                                        </select>
                                        @error('type')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Sequence -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Urutan
                                            Followup</label>
                                        <select wire:model="followup_sequence"
                                            class="w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 transition-all px-4 py-2.5">
                                            <option value="FollowUp1">FollowUp 1 (Pertama)</option>
                                            <option value="FollowUp2">FollowUp 2 (Kedua)</option>
                                            <option value="FollowUp3">FollowUp 3 (Ketiga)</option>
                                            <option value="FollowUp4">FollowUp 4 (Keempat)</option>
                                        </select>
                                        @error('followup_sequence')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Dynamic Parameters -->
                                <div class="mb-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Parameter Tersedia
                                        (Klik untuk menyisipkan)</label>
                                    <div class="flex flex-wrap gap-2 p-3 bg-gray-50 rounded-xl border border-gray-200">
                                        @php
                                            $commonParams = ['{{ nama }}', '{{ tanggal }}'];
                                            $params = [];
                                            if ($type == 'donasi') {
                                                $params = array_merge($commonParams, [
                                                    '{{ program }}',
                                                    '{{ nilai_donasi }}',
                                                    '{{ link_donasi }}',
                                                    '{{ link_pembayaran }}',
                                                ]);
                                            } elseif ($type == 'qurban') {
                                                $params = array_merge($commonParams, [
                                                    '{{ jenis_hewan }}',
                                                    '{{ tipe_qurban }}',
                                                    '{{ harga }}',
                                                    '{{ link_pembayaran }}',
                                                ]);
                                            } elseif ($type == 'tabungan_qurban') {
                                                $params = array_merge($commonParams, [
                                                    '{{ target_tabungan }}',
                                                    '{{ saldo_saat_ini }}',
                                                    '{{ sisa_pembayaran }}',
                                                    '{{ link_topup }}',
                                                    '{{ link_pembayaran }}',
                                                ]);
                                            }
                                        @endphp

                                        @foreach ($params as $param)
                                            <button type="button"
                                                wire:click="insertParameter('{{ $param }}')"
                                                class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium bg-white border border-gray-300 text-gray-700 shadow-sm hover:bg-gray-50 hover:text-primary hover:border-primary transition-all cursor-pointer group">
                                                <i
                                                    class="fa-solid fa-code mr-1.5 text-gray-400 group-hover:text-primary"></i>
                                                {{ $param }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Content -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Isi Pesan</label>
                                    <textarea wire:model="content" rows="6"
                                        class="w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 transition-all font-mono text-sm px-4 py-3"
                                        placeholder="Tulis pesan followup disini..."></textarea>
                                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                                        <i class="fa-solid fa-circle-info mr-1"></i>
                                        Gunakan parameter di atas untuk membuat pesan dinamis sesuai data donatur.
                                    </p>
                                    @error('content')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                            {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Template' }}
                        </button>
                        <button type="button" wire:click="$set('showModal', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: $wire.entangle('showDeleteModal') }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Hapus Template
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin menghapus template followup ini? Tindakan ini tidak dapat
                                    dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="delete"
                        class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Hapus
                    </button>
                    <button type="button" wire:click="$set('showDeleteModal', false)"
                        class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

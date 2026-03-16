<div>
    @section('title', 'Template Pesan WhatsApp')
    @section('header', 'Template Pesan WhatsApp')

    <!-- Info Cards - Active Template Count per Type -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @php
            $types = [
                'donasi' => ['label' => 'Donasi Program', 'icon' => 'fa-hand-holding-heart', 'color' => 'blue'],
                'qurban' => ['label' => 'Qurban', 'icon' => 'fa-cow', 'color' => 'green'],
                'tabungan_qurban' => ['label' => 'Tabungan Qurban', 'icon' => 'fa-piggy-bank', 'color' => 'purple'],
                'zakat' => ['label' => 'Zakat', 'icon' => 'fa-star-and-crescent', 'color' => 'amber'],
            ];
            $events = ['payment_created', 'payment_success', 'payment_expired'];
        @endphp

        @foreach ($types as $typeKey => $typeInfo)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-{{ $typeInfo['color'] }}-100 flex items-center justify-center">
                        <i class="fa-solid {{ $typeInfo['icon'] }} text-{{ $typeInfo['color'] }}-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800">{{ $typeInfo['label'] }}</h3>
                </div>
                <div class="space-y-1.5">
                    @foreach ($events as $evt)
                        @php
                            $count = isset($templateCounts[$typeKey]) ? ($templateCounts[$typeKey][$evt] ?? 0) : 0;
                            $evtLabel = match($evt) {
                                'payment_created' => 'Dibuat',
                                'payment_success' => 'Berhasil',
                                'payment_expired' => 'Expired',
                            };
                        @endphp
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">{{ $evtLabel }}</span>
                            <span class="font-medium {{ $count > 0 ? 'text-green-600' : 'text-red-500' }}">
                                {{ $count }} template aktif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Actions Bar -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input wire:model.live="search" type="text"
                    class="block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 text-base"
                    placeholder="Cari template...">
            </div>

            <select wire:model.live="filterType"
                class="rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 cursor-pointer py-2.5 px-4 text-sm transition-colors">
                <option value="">Semua Tipe</option>
                <option value="donasi">Donasi Program</option>
                <option value="qurban">Qurban</option>
                <option value="tabungan_qurban">Tabungan Qurban</option>
                <option value="zakat">Zakat</option>
            </select>

            <select wire:model.live="filterEvent"
                class="rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 cursor-pointer py-2.5 px-4 text-sm transition-colors">
                <option value="">Semua Event</option>
                <option value="payment_created">Pembayaran Dibuat</option>
                <option value="payment_success">Pembayaran Berhasil</option>
                <option value="payment_expired">Pembayaran Expired</option>
            </select>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <select wire:model.live="perPage"
                class="rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 cursor-pointer py-2.5 px-4 text-sm transition-colors">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>

            <button wire:click="create"
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-white bg-primary hover:bg-primary-hover shadow-lg shadow-primary/30 transform hover:-translate-y-0.5 transition-all duration-200">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Template
            </button>
        </div>
    </div>

    <!-- Hint -->
    <div class="mb-4 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-start">
            <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-blue-800">
                <p class="font-medium mb-1">Cara Kerja Template Pesan</p>
                <p>Buat beberapa variasi template pesan untuk setiap kombinasi <strong>tipe transaksi</strong> dan <strong>event</strong>. Sistem akan memilih <strong>secara acak (random)</strong> dari template yang aktif saat mengirim pesan WhatsApp otomatis. Jika tidak ada template yang aktif, sistem akan menggunakan pesan default.</p>
            </div>
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
                            Tipe & Event
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
                    @forelse($templates as $template)
                        <tr class="hover:bg-orange-50/30 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $template->name ?? '-' }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $template->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium max-w-max
                                        {{ $template->type === 'donasi'
                                            ? 'bg-blue-100 text-blue-800'
                                            : ($template->type === 'qurban'
                                                ? 'bg-green-100 text-green-800'
                                                : ($template->type === 'zakat'
                                                    ? 'bg-amber-100 text-amber-800'
                                                    : 'bg-purple-100 text-purple-800')) }}">
                                        {{ $template->type_label }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium max-w-max
                                        {{ $template->event === 'payment_created'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : ($template->event === 'payment_success'
                                                ? 'bg-emerald-100 text-emerald-800'
                                                : 'bg-red-100 text-red-800') }}">
                                        <i class="fa-solid {{ $template->event === 'payment_created' ? 'fa-clock' : ($template->event === 'payment_success' ? 'fa-check-circle' : 'fa-times-circle') }} mr-1"></i>
                                        {{ $template->event_label }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600 line-clamp-2 max-w-md whitespace-pre-line" title="{{ $template->content }}">
                                    {{ Str::limit($template->content, 120) }}
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button wire:click="toggleActive({{ $template->id }})"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full cursor-pointer transition-colors
                                    {{ $template->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                    {{ $template->is_active ? 'Aktif' : 'Non-aktif' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="preview({{ $template->id }})"
                                    class="text-gray-500 hover:text-primary mr-2 transition-colors duration-200" title="Preview">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button wire:click="edit({{ $template->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-2 transition-colors duration-200" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $template->id }})"
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fa-brands fa-whatsapp text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-lg font-medium text-gray-900">Belum ada template</p>
                                    <p class="text-sm text-gray-500 mt-1">Tambahkan template pesan WhatsApp untuk notifikasi otomatis.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $templates->links() }}
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
                                    <i class="fa-solid {{ $isEditing ? 'fa-pen-to-square' : 'fa-plus-circle' }} mr-2 text-primary"></i>
                                    {{ $isEditing ? 'Edit Template Pesan' : 'Buat Template Baru' }}
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                    <!-- Name -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Template</label>
                                        <input wire:model="name" type="text"
                                            class="w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 transition-all px-4 py-2.5"
                                            placeholder="Contoh: Ucapan Donasi Variasi 1">
                                        @error('name')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Is Active -->
                                    <div class="flex items-center h-full pt-6">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                            </div>
                                            <span class="ms-3 text-sm font-medium text-gray-700">Aktifkan Template</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                    <!-- Type -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Transaksi</label>
                                        <select wire:model.live="type"
                                            class="w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 transition-all px-4 py-2.5">
                                            <option value="donasi">Donasi Program</option>
                                            <option value="qurban">Qurban</option>
                                            <option value="tabungan_qurban">Tabungan Qurban</option>
                                            <option value="zakat">Zakat</option>
                                        </select>
                                        @error('type')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Event -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Event Notifikasi</label>
                                        <select wire:model.live="event"
                                            class="w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 transition-all px-4 py-2.5">
                                            <option value="payment_created">Pembayaran Dibuat</option>
                                            <option value="payment_success">Pembayaran Berhasil</option>
                                            <option value="payment_expired">Pembayaran Expired</option>
                                        </select>
                                        @error('event')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Dynamic Parameters -->
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Parameter Tersedia
                                        <span class="font-normal text-gray-500">(Klik untuk menyisipkan)</span>
                                    </label>
                                    <div class="flex flex-wrap gap-2 p-3 bg-gray-50 rounded-xl border border-gray-200">
                                        @foreach ($this->availableParameters as $paramItem)
                                            <button type="button"
                                                wire:click="insertParameter('{{ $paramItem['key'] }}')"
                                                class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium bg-white border border-gray-300 text-gray-700 shadow-sm hover:bg-gray-50 hover:text-primary hover:border-primary transition-all cursor-pointer group"
                                                title="{{ $paramItem['desc'] }}">
                                                <i class="fa-solid fa-code mr-1.5 text-gray-400 group-hover:text-primary"></i>
                                                {{ $paramItem['label'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1.5">
                                        <i class="fa-solid fa-circle-info mr-1"></i>
                                        Hover tombol parameter untuk melihat deskripsi. Parameter akan diganti otomatis dengan data transaksi saat pengiriman.
                                    </p>
                                </div>

                                <!-- Content -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Isi Pesan</label>
                                    <textarea wire:model="content" rows="8"
                                        class="w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-sm focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 transition-all font-mono text-sm px-4 py-3"
                                        placeholder="Tulis template pesan WhatsApp disini. Gunakan tombol parameter di atas untuk menyisipkan variabel dinamis."></textarea>
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

    <!-- Preview Modal -->
    <div x-data="{ show: $wire.entangle('showPreviewModal') }" x-show="show" x-transition:enter="transition ease-out duration-300"
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

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-brands fa-whatsapp text-green-600 text-lg"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Preview Pesan</h3>
                            <p class="text-xs text-gray-500 mt-1">Pesan dengan data contoh</p>
                            <div class="mt-4 bg-green-50 border border-green-200 rounded-xl p-4">
                                <p class="text-sm text-gray-800 whitespace-pre-line leading-relaxed">{{ $previewContent }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                    <button type="button" wire:click="$set('showPreviewModal', false)"
                        class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm transition-colors duration-200">
                        Tutup
                    </button>
                </div>
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
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Hapus Template</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin menghapus template ini? Tindakan ini tidak dapat dibatalkan.
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

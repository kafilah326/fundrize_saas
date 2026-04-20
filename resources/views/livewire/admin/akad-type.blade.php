<div>
    @section('title', 'Manajemen Tipe Akad')
    @section('header', 'Daftar Tipe Akad Program')

    <!-- Actions Bar -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="relative w-full sm:w-64">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-search text-gray-400"></i>
            </div>
            <input wire:model.live="search" type="text"
                class="block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 text-base"
                placeholder="Cari tipe akad...">
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
                <i class="fa-solid fa-plus mr-2"></i> Tambah Tipe Akad
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
                            Info Akad
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Icon (FontAwesome)
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($akadTypes as $akad)
                        <tr class="hover:bg-orange-50/30 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $akad->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $akad->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-900">
                                    <div
                                        class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center mr-3 text-primary">
                                        <i class="fa-solid {{ $akad->icon ?? 'fa-hand-holding-heart' }}"></i>
                                    </div>
                                    <span
                                        class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $akad->icon ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $akad->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $akad->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $akad->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3 transition-colors duration-200">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $akad->id }})"
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-hand-holding-heart text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-lg font-medium text-gray-900">Belum ada tipe akad</p>
                                    <p class="text-sm text-gray-500 mt-1">Silakan tambahkan tipe akad baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $akadTypes->links() }}
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
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $isEditing ? 'Edit Tipe Akad' : 'Tambah Tipe Akad Baru' }}
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama
                                        Akad</label>
                                    <input type="text" wire:model.live="name" id="name"
                                        class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm py-2.5 px-4 focus:outline-none focus:ring-primary focus:border-primary text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('name')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                                    <div x-data="{ slugify(val) { return val.toLowerCase().replace(/ /g, '-').replace(/[^a-z0-9-]/g, '') } }">
                                        <input type="text" 
                                            wire:model.live.blur="slug" 
                                            x-on:input="$el.value = slugify($el.value)"
                                            id="slug"
                                            class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm py-2.5 px-4 focus:outline-none focus:ring-primary focus:border-primary text-base bg-gray-50 focus:bg-white transition-colors">
                                    </div>
                                    @error('slug')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Icon FontAwesome
                                    </label>
                                    <div class="flex rounded-xl shadow-sm border border-gray-300 bg-gray-50 overflow-hidden group">
                                        <button type="button" @click="$dispatch('open-icon-picker')"
                                            class="inline-flex items-center px-4 py-2.5 border-r border-gray-300 hover:bg-gray-200 text-gray-700 font-medium text-sm transition-colors cursor-pointer focus:outline-none focus:bg-gray-200">
                                            <i class="fa-solid fa-magnifying-glass mr-2"></i> Pilih Icon
                                        </button>
                                        <div class="flex-1 px-4 py-2.5 flex items-center justify-between bg-white cursor-pointer hover:bg-gray-50 transition-colors" @click="$dispatch('open-icon-picker')">
                                             <span x-text="$wire.icon || 'Belum ada icon'" class="font-mono text-sm" :class="$wire.icon ? 'text-gray-800' : 'text-gray-400'"></span>
                                             <i :class="$wire.icon ? $wire.icon : 'fa-solid fa-circle-question'" class="text-xl text-primary transform group-hover:scale-110 transition-transform"></i>
                                        </div>
                                        <button type="button" @click="$wire.set('icon', '')" x-show="$wire.icon"
                                            class="inline-flex items-center px-4 border-l border-gray-300 hover:bg-red-50 hover:text-red-600 text-gray-400 transition-colors focus:outline-none">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-500">Klik tombol Pilih Icon untuk menyesuaikan gambar tipe akad.</p>
                                    @error('icon')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="is_active" id="is_active"
                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                        Aktifkan Tipe Akad ini
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="{{ $isEditing ? 'update' : 'store' }}"
                        class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        {{ $isEditing ? 'Simpan Perubahan' : 'Simpan' }}
                    </button>
                    <button type="button" wire:click="$set('showModal', false)"
                        class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Batal
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
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Hapus Tipe Akad
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin menghapus tipe akad ini? Tindakan ini tidak dapat
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

    <!-- Icon Picker Modal -->
    <div x-data="{ 
            showPicker: false, 
            searchIcon: '', 
            icons: ['fa-solid fa-tag', 'fa-solid fa-tags', 'fa-solid fa-hand-holding-heart', 'fa-solid fa-handshake', 'fa-solid fa-mosque', 'fa-solid fa-kaaba', 'fa-solid fa-moon', 'fa-solid fa-star', 'fa-solid fa-heart', 'fa-solid fa-basket-shopping', 'fa-solid fa-graduation-cap', 'fa-solid fa-book', 'fa-solid fa-school', 'fa-solid fa-hospital', 'fa-solid fa-stethoscope', 'fa-solid fa-briefcase-medical', 'fa-solid fa-house-chimney-medical', 'fa-solid fa-wheelchair', 'fa-solid fa-crutch', 'fa-solid fa-droplet', 'fa-solid fa-bowl-food', 'fa-solid fa-bowl-rice', 'fa-solid fa-wheat-awn', 'fa-solid fa-bottle-water', 'fa-solid fa-glass-water', 'fa-solid fa-faucet-drip', 'fa-solid fa-utensils', 'fa-solid fa-cow', 'fa-solid fa-sheep', 'fa-solid fa-hippo', 'fa-solid fa-house-crack', 'fa-solid fa-house-flood-water', 'fa-solid fa-fire', 'fa-solid fa-wind', 'fa-solid fa-cloud-showers-heavy', 'fa-solid fa-kit-medical', 'fa-solid fa-tent', 'fa-solid fa-coins', 'fa-solid fa-money-bill-wave', 'fa-solid fa-sack-dollar', 'fa-solid fa-wallet', 'fa-solid fa-credit-card', 'fa-solid fa-piggy-bank', 'fa-solid fa-chart-line', 'fa-solid fa-hand-holding-dollar', 'fa-solid fa-building-ngo', 'fa-solid fa-people-roof', 'fa-solid fa-users', 'fa-solid fa-child-reaching', 'fa-solid fa-person-praying', 'fa-solid fa-box', 'fa-solid fa-gift', 'fa-solid fa-truck', 'fa-solid fa-motorcycle', 'fa-solid fa-car', 'fa-solid fa-bus', 'fa-solid fa-plane', 'fa-solid fa-ship', 'fa-solid fa-seedling', 'fa-solid fa-tree', 'fa-solid fa-leaf', 'fa-solid fa-sun', 'fa-solid fa-cloud', 'fa-solid fa-bolt', 'fa-solid fa-snowflake', 'fa-solid fa-fire-flame-curved', 'fa-solid fa-fire-burner', 'fa-solid fa-temperature-empty', 'fa-solid fa-check', 'fa-solid fa-plus', 'fa-solid fa-minus', 'fa-solid fa-xmark', 'fa-solid fa-info', 'fa-solid fa-exclamation', 'fa-solid fa-triangle-exclamation', 'fa-solid fa-circle-info', 'fa-solid fa-circle-check', 'fa-solid fa-arrow-right', 'fa-solid fa-circle', 'fa-solid fa-square', 'fa-solid fa-play', 'fa-solid fa-pause', 'fa-solid fa-stop', 'fa-solid fa-bullhorn', 'fa-solid fa-microphone', 'fa-solid fa-camera', 'fa-solid fa-video', 'fa-solid fa-image', 'fa-solid fa-music', 'fa-solid fa-globe', 'fa-solid fa-earth-asia', 'fa-solid fa-map-location-dot', 'fa-solid fa-location-dot', 'fa-solid fa-compass', 'fa-solid fa-bell', 'fa-solid fa-envelope', 'fa-solid fa-phone', 'fa-solid fa-mobile-screen', 'fa-solid fa-laptop', 'fa-solid fa-desktop', 'fa-solid fa-battery-full', 'fa-solid fa-print', 'fa-solid fa-calendar', 'fa-solid fa-clock', 'fa-solid fa-percent', 'fa-solid fa-ribbon', 'fa-solid fa-award', 'fa-solid fa-medal', 'fa-solid fa-trophy', 'fa-solid fa-crown', 'fa-solid fa-key', 'fa-solid fa-lock', 'fa-solid fa-unlock', 'fa-solid fa-eye', 'fa-solid fa-eye-slash'],
            get filteredIcons() {
                if (this.searchIcon === '') return this.icons;
                return this.icons.filter(i => i.toLowerCase().includes(this.searchIcon.toLowerCase()));
            },
            selectIcon(icon) {
                $wire.set('icon', icon);
                this.showPicker = false;
            }
        }"
        @open-icon-picker.window="showPicker = true; searchIcon = ''; setTimeout(() => $refs.searchInput.focus(), 50);"
        x-show="showPicker"
        x-transition.opacity
        class="fixed inset-0 z-[60] overflow-y-auto"
        style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" @click="showPicker = false"></div>

            <div x-show="showPicker" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative inline-block w-full max-w-3xl overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle">
                <!-- Header -->
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Pilih Icon</h4>
                    <button @click="showPicker = false" type="button" class="text-gray-400 hover:text-red-500 transition-colors focus:outline-none">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <!-- Search Field -->
                    <div class="relative mb-6">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="fa-solid fa-search text-gray-400"></i>
                        </div>
                        <input x-ref="searchInput" x-model="searchIcon" type="text" class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary text-sm bg-gray-50 focus:bg-white transition-colors outline-none" placeholder="Cari icon (contoh: mosque, heart, money)...">
                    </div>

                    <!-- Icon Grid -->
                    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3 max-h-[350px] overflow-y-auto p-1 custom-scrollbar">
                        <template x-for="icon in filteredIcons" :key="icon">
                            <button @click="selectIcon(icon)" type="button" 
                                class="flex flex-col items-center justify-center p-3 border border-gray-100 rounded-xl hover:bg-primary/5 hover:border-primary/30 hover:text-primary transition-all group focus:outline-none focus:ring-2 focus:ring-primary/50"
                                :class="$wire.icon === icon ? 'bg-primary/10 border-primary text-primary shadow-sm' : 'bg-white text-gray-600'">
                                <i :class="icon" class="text-2xl mb-1.5 group-hover:scale-125 transition-transform duration-200"></i>
                            </button>
                        </template>
                        
                        <!-- Empty State -->
                        <div x-show="filteredIcons.length === 0" class="col-span-full py-8 text-center text-gray-500">
                            <i class="fa-solid fa-search-minus text-3xl mb-2 text-gray-300"></i>
                            <p>Icon tidak ditemukan.</p>
                        </div>
                    </div>
                </div>
                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-100">
                    <p class="text-xs text-gray-400">Menampilkan lebih dari 100 icon FontAwesome</p>
                    <a href="https://fontawesome.com/search?o=r&m=free" target="_blank" class="text-xs font-semibold text-primary hover:underline flex items-center">
                        Cari selengkapnya <i class="fa-solid fa-up-right-from-square ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

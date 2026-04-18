@section('title', 'Pengaturan')
@section('header', 'Pengaturan Sistem')

<div class="space-y-6">
    <div class="bg-white overflow-hidden shadow-soft rounded-2xl border border-gray-100">
        <!-- Modern Tabs -->
        <div class="border-b border-gray-100 overflow-x-auto">
            <nav class="flex space-x-1 px-4 pt-2 min-w-max" aria-label="Tabs">
                <button wire:click="setTab('foundation')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2 
                    {{ $activeTab === 'foundation' ? 'text-primary border-primary bg-primary/5' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-building mr-2"></i> Profil Yayasan
                </button>
                <button wire:click="setTab('bank')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2 
                    {{ $activeTab === 'bank' ? 'text-primary border-primary bg-primary/5' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-building-columns mr-2"></i> Rekening Bank
                </button>
                <button wire:click="setTab('api')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2 
                    {{ $activeTab === 'api' ? 'text-primary border-primary bg-primary/5' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-plug mr-2"></i> API & Integrasi
                </button>
                <button wire:click="setTab('appearance')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2 
                    {{ $activeTab === 'appearance' ? 'text-primary border-primary bg-primary/5' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-paintbrush mr-2"></i> Tampilan
                </button>
                <button wire:click="setTab('domain')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2 
                    {{ $activeTab === 'domain' ? 'text-primary border-primary bg-primary/5' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-globe mr-2"></i> Domain
                </button>
            </nav>
        </div>

        <div class="p-6 md:p-8">
            @if (session()->has('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3 text-green-700">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <div>
                        <h4 class="font-bold text-sm">Berhasil!</h4>
                        <p class="text-xs">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 text-red-700">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    <div>
                        <h4 class="font-bold text-sm">Gagal</h4>
                        <p class="text-xs">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Foundation Tab -->
            @if ($activeTab === 'foundation')
                <form wire:submit.prevent="saveFoundation">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Column: Identity -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Identitas Yayasan</h3>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Yayasan</label>
                                <input wire:model="name" type="text"
                                    class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                @error('name')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Tagline</label>
                                <input wire:model="tagline" type="text"
                                    class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Logo</label>
                                <div
                                    class="flex items-center space-x-6 p-4 border border-gray-200 rounded-xl bg-gray-50">
                                    @if ($logo)
                                        <img src="{{ $logo->temporaryUrl() }}"
                                            class="h-24 w-24 object-contain rounded-xl shadow-sm border border-white bg-white">
                                    @elseif($existingLogo)
                                        <img src="{{ $existingLogo }}"
                                            class="h-24 w-24 object-contain rounded-xl shadow-sm border border-white bg-white">
                                    @else
                                        <div
                                            class="h-24 w-24 rounded-xl bg-gray-200 flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-image text-3xl"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <input wire:model="logo" type="file" accept="image/*"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                                        <p class="text-xs text-gray-500 mt-2">Format: PNG transparan. Rekomendasi
                                            ukuran: 512x128 px (Landscape). Max: 2MB.</p>
                                    </div>
                                </div>
                                @error('logo')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Favicon</label>
                                <div
                                    class="flex items-center space-x-6 p-4 border border-gray-200 rounded-xl bg-gray-50">
                                    @if ($favicon)
                                        <img src="{{ $favicon->temporaryUrl() }}"
                                            class="h-12 w-12 object-contain rounded-xl shadow-sm border border-white bg-white">
                                    @elseif($existingFavicon)
                                        <img src="{{ $existingFavicon }}"
                                            class="h-12 w-12 object-contain rounded-xl shadow-sm border border-white bg-white">
                                    @else
                                        <div
                                            class="h-12 w-12 rounded-xl bg-gray-200 flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-image text-xl"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <input wire:model="favicon" type="file" accept="image/*"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                                        <p class="text-xs text-gray-500 mt-2">Format: PNG. Rekomendasi ukuran: 512x512
                                            px (Square). Max: 1MB.</p>
                                    </div>
                                </div>
                                @error('favicon')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div wire:ignore>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Tentang Yayasan</label>
                                <div class="bg-white rounded-xl border border-gray-300 overflow-hidden"
                                    x-data="quillEditor($wire.entangle('about').live)">
                                    <div x-ref="quillEditor" class="min-h-[200px]"></div>
                                </div>
                                @error('about')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column: Contact & Vision -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Kontak & Visi Misi</h3>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                    <input wire:model="email" type="email"
                                        class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('email')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Telepon / WA</label>
                                    <input wire:model="phone" type="text"
                                        class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('phone')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap</label>
                                <textarea wire:model="address" rows="2"
                                    class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors"></textarea>
                                @error('address')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div wire:ignore>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Visi</label>
                                <div class="bg-white rounded-xl border border-gray-300 overflow-hidden"
                                    x-data="quillEditor($wire.entangle('vision').live)">
                                    <div x-ref="quillEditor" class="min-h-[150px]"></div>
                                </div>
                                @error('vision')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Misi (Pisahkan
                                    baris)</label>
                                <textarea wire:model="mission" rows="4"
                                    class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Section underneath -->
                    <div class="mt-8 space-y-6">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Media Sosial (Opsional)</h3>
                        <p class="text-sm text-gray-500 mb-4">Meninggalkan kolom kosong berarti icon link tidak akan
                            dimunculkan di halaman depan profil.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Facebook URL</label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        <i class="fa-brands fa-facebook"></i>
                                    </span>
                                    <input wire:model="social_facebook" type="url"
                                        placeholder="https://facebook.com/yayasan"
                                        class="flex-1 block w-full rounded-r-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-white transition-colors">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Instagram URL</label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        <i class="fa-brands fa-instagram"></i>
                                    </span>
                                    <input wire:model="social_instagram" type="url"
                                        placeholder="https://instagram.com/yayasan"
                                        class="flex-1 block w-full rounded-r-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-white transition-colors">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">WhatsApp URL /
                                    WA.me</label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </span>
                                    <input wire:model="social_whatsapp" type="url"
                                        placeholder="https://wa.me/6281xxxxxx"
                                        class="flex-1 block w-full rounded-r-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-white transition-colors">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">YouTube Channel
                                    URL</label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        <i class="fa-brands fa-youtube"></i>
                                    </span>
                                    <input wire:model="social_youtube" type="url"
                                        placeholder="https://youtube.com/@yayasan"
                                        class="flex-1 block w-full rounded-r-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-white transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="bg-primary hover:bg-primary-hover text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all hover:-translate-y-0.5 inline-flex items-center">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Profil
                        </button>
                    </div>
                </form>

                <!-- Bank Tab -->
            @elseif($activeTab === 'bank')
                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Rekening Bank</h3>
                    <button wire:click="createBank"
                        class="bg-primary hover:bg-primary-hover text-white font-semibold py-2.5 px-5 rounded-xl inline-flex items-center shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all hover:-translate-y-0.5">
                        <i class="fa-solid fa-plus mr-2"></i> Tambah Rekening
                    </button>
                </div>

                <div class="overflow-hidden rounded-xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Icon</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Bank</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    No. Rekening</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Atas Nama</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($bankAccounts as $bank)
                                <tr class="hover:bg-orange-50/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($bank->icon)
                                            <img src="{{ Storage::url($bank->icon) }}"
                                                class="h-8 object-contain rounded" alt="{{ $bank->bank_name }}">
                                        @else
                                            <div
                                                class="h-8 w-12 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs text-center border border-gray-200">
                                                No Icon
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $bank->bank_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">
                                        {{ $bank->account_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $bank->account_holder_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button wire:click="toggleBankStatus({{ $bank->id }})"
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full transition-colors {{ $bank->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                            {{ $bank->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button wire:click="editBank({{ $bank->id }})"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button wire:click="deleteBank({{ $bank->id }})"
                                                onclick="return confirm('Yakin hapus rekening ini?') || event.stopImmediatePropagation()"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 italic">Belum ada
                                        rekening bank yang terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $bankAccounts->links() }}
                </div>

                <!-- API Tab -->
            @elseif($activeTab === 'api')
                <form wire:submit.prevent="saveApi">

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-900 mb-3">Pilih Gateway Pembayaran Otomatis
                            Utama</label>
                        <div class="flex flex-wrap gap-4">
                            <label
                                class="relative flex cursor-pointer rounded-2xl border-2 p-4 transition-all {{ $payment_gateway === 'xendit' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="payment_gateway" value="xendit"
                                    class="peer sr-only">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-xl {{ $payment_gateway === 'xendit' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-400' }}">
                                        <i class="fa-solid fa-credit-card text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">Xendit</p>
                                        <p class="text-sm text-gray-500">Gateway pembayaran populer di Indonesia</p>
                                    </div>
                                    @if ($payment_gateway === 'xendit')
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-primary">
                                            <i class="fa-solid fa-circle-check text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </label>

                            <label
                                class="relative flex cursor-pointer rounded-2xl border-2 p-4 transition-all {{ $payment_gateway === 'pakasir' ? 'border-teal-500 bg-teal-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="payment_gateway" value="pakasir"
                                    class="peer sr-only">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-xl {{ $payment_gateway === 'pakasir' ? 'bg-teal-500 text-white' : 'bg-gray-100 text-gray-400' }}">
                                        <i class="fa-solid fa-wallet text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">Pakasir</p>
                                        <p class="text-sm text-gray-500">Gateway pembayaran via URL</p>
                                    </div>
                                    @if ($payment_gateway === 'pakasir')
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-teal-500">
                                            <i class="fa-solid fa-circle-check text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        @if ($payment_gateway === 'xendit')
                            <!-- Xendit Info -->
                            <div
                                class="rounded-2xl border p-6 {{ $xendit_mode === 'live' ? 'bg-red-50/50 border-red-200' : 'bg-blue-50/50 border-blue-100' }} transition-colors duration-300">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center">
                                        <span
                                            class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ $xendit_mode === 'live' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                                            <i class="fa-solid fa-credit-card text-lg"></i>
                                        </span>
                                        <h3
                                            class="text-lg font-bold {{ $xendit_mode === 'live' ? 'text-red-900' : 'text-blue-900' }}">
                                            Xendit Payment</h3>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $xendit_mode === 'live' ? 'bg-red-100 text-red-700 border border-red-300' : 'bg-green-100 text-green-700 border border-green-300' }}">
                                        <span
                                            class="w-2 h-2 rounded-full mr-1.5 {{ $xendit_mode === 'live' ? 'bg-red-500 animate-pulse' : 'bg-green-500' }}"></span>
                                        {{ $xendit_mode === 'live' ? 'LIVE / Production' : 'TEST / Sandbox' }}
                                    </span>
                                </div>

                                {{-- Mode Toggle --}}
                                <div class="mb-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mode
                                        Environment</label>
                                    <div class="flex items-center gap-2 p-1 bg-gray-100 rounded-xl w-fit">
                                        <button type="button" wire:click="$set('xendit_mode', 'test')"
                                            class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $xendit_mode === 'test' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                            <i class="fa-solid fa-flask mr-1"></i> Test
                                        </button>
                                        <button type="button" wire:click="$set('xendit_mode', 'live')"
                                            class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $xendit_mode === 'live' ? 'bg-white text-red-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                            <i class="fa-solid fa-globe mr-1"></i> Live
                                        </button>
                                    </div>

                                    @if ($xendit_mode === 'live')
                                        <div class="mt-3 p-3 bg-red-100 border border-red-300 rounded-xl">
                                            <div class="flex items-start gap-2">
                                                <i class="fa-solid fa-triangle-exclamation text-red-600 mt-0.5"></i>
                                                <div>
                                                    <p class="text-xs font-bold text-red-700">Mode Production Aktif</p>
                                                    <p class="text-xs text-red-600 mt-0.5">Semua transaksi akan
                                                        diproses secara nyata. Pastikan credential yang digunakan adalah
                                                        credential production dari Dashboard Xendit.</p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-xl">
                                            <div class="flex items-start gap-2">
                                                <i class="fa-solid fa-shield-halved text-green-600 mt-0.5"></i>
                                                <div>
                                                    <p class="text-xs font-bold text-green-700">Mode Sandbox Aktif</p>
                                                    <p class="text-xs text-green-600 mt-0.5">Transaksi tidak akan
                                                        diproses secara nyata. Gunakan credential test dari Dashboard
                                                        Xendit.</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Secret
                                            Key</label>
                                        <input wire:model="xendit_secret_key" type="password"
                                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500/20 bg-white focus:bg-white transition-colors"
                                            placeholder="{{ $xendit_mode === 'live' ? 'xnd_production_...' : 'xnd_development_...' }}">
                                        @error('xendit_secret_key')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Webhook
                                            Token</label>
                                        <input wire:model="xendit_webhook_token" type="password"
                                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500/20 bg-white focus:bg-white transition-colors"
                                            placeholder="Webhook verification token">
                                        @error('xendit_webhook_token')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div
                                        class="bg-white p-4 rounded-xl border {{ $xendit_mode === 'live' ? 'border-red-200' : 'border-blue-200' }} shadow-sm mt-4">
                                        <p
                                            class="text-xs font-bold {{ $xendit_mode === 'live' ? 'text-red-600' : 'text-blue-600' }} uppercase mb-1">
                                            Webhook URL</p>
                                        <code
                                            class="font-mono text-gray-700 block break-all text-sm">{{ route('webhooks.xendit.invoice') }}</code>
                                    </div>
                                    <p
                                        class="text-xs {{ $xendit_mode === 'live' ? 'text-red-600' : 'text-blue-600' }}">
                                        <i class="fa-solid fa-info-circle mr-1"></i> Pastikan URL ini didaftarkan di
                                        Dashboard Xendit Anda pada bagian Invoice callback.
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if ($payment_gateway === 'pakasir')
                            <!-- Pakasir Info -->
                            <div
                                class="rounded-2xl border p-6 {{ $pakasir_mode === 'live' ? 'bg-red-50/50 border-red-200' : 'bg-teal-50/50 border-teal-100' }} transition-colors duration-300">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center">
                                        <span
                                            class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ $pakasir_mode === 'live' ? 'bg-red-100 text-red-600' : 'bg-teal-100 text-teal-600' }}">
                                            <i class="fa-solid fa-wallet text-lg"></i>
                                        </span>
                                        <h3
                                            class="text-lg font-bold {{ $pakasir_mode === 'live' ? 'text-red-900' : 'text-teal-900' }}">
                                            Pakasir Payment</h3>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $pakasir_mode === 'live' ? 'bg-red-100 text-red-700 border border-red-300' : 'bg-green-100 text-green-700 border border-green-300' }}">
                                        <span
                                            class="w-2 h-2 rounded-full mr-1.5 {{ $pakasir_mode === 'live' ? 'bg-red-500 animate-pulse' : 'bg-green-500' }}"></span>
                                        {{ $pakasir_mode === 'live' ? 'LIVE / Production' : 'TEST / Sandbox' }}
                                    </span>
                                </div>

                                {{-- Mode Toggle --}}
                                <div class="mb-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mode
                                        Environment</label>
                                    <div class="flex items-center gap-2 p-1 bg-gray-100 rounded-xl w-fit">
                                        <button type="button" wire:click="$set('pakasir_mode', 'sandbox')"
                                            class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $pakasir_mode === 'sandbox' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                            <i class="fa-solid fa-flask mr-1"></i> Sandbox
                                        </button>
                                        <button type="button" wire:click="$set('pakasir_mode', 'live')"
                                            class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $pakasir_mode === 'live' ? 'bg-white text-red-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                            <i class="fa-solid fa-globe mr-1"></i> Live
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Slug
                                            Proyek</label>
                                        <input wire:model="pakasir_slug" type="text"
                                            class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500/20 bg-white focus:bg-white transition-colors"
                                            placeholder="nama-proyek">
                                        @error('pakasir_slug')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">API Key</label>
                                        <input wire:model="pakasir_api_key" type="password"
                                            class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500/20 bg-white focus:bg-white transition-colors"
                                            placeholder="API Key dari Dashboard Pakasir">
                                        @error('pakasir_api_key')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div
                                        class="bg-white p-4 rounded-xl border {{ $pakasir_mode === 'live' ? 'border-red-200' : 'border-teal-200' }} shadow-sm mt-4">
                                        <p
                                            class="text-xs font-bold {{ $pakasir_mode === 'live' ? 'text-red-600' : 'text-teal-600' }} uppercase mb-1">
                                            Webhook URL</p>
                                        <code
                                            class="font-mono text-gray-700 block break-all text-sm">{{ route('webhooks.pakasir.invoice') }}</code>
                                    </div>
                                    <p
                                        class="text-xs {{ $pakasir_mode === 'live' ? 'text-red-600' : 'text-teal-600' }}">
                                        <i class="fa-solid fa-info-circle mr-1"></i> Masukkan URL ini ke form Webhook
                                        URL di pengaturan proyek Pakasir Anda.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="bg-primary hover:bg-primary-hover text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all hover:-translate-y-0.5 inline-flex items-center">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Pengaturan API
                        </button>
                    </div>
                </form>

                <!-- Appearance Tab -->
            @elseif($activeTab === 'appearance')
                <form wire:submit.prevent="saveAppearance" x-data="{
                    themeColor: @entangle('theme_color'),
                    secondaryColor: @entangle('secondary_color')
                }">
                    <div class="space-y-8">
                        <div class="bg-orange-50/50 rounded-2xl border border-orange-100 p-6">
                            <div class="flex items-center mb-6">
                                <span
                                    class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center mr-3">
                                    <i class="fa-solid fa-palette text-lg"></i>
                                </span>
                                <h3 class="text-lg font-bold text-orange-900">Warna Tema</h3>
                            </div>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Warna Utama
                                            (Primary)</label>
                                        <div class="flex items-center space-x-4">
                                            <input x-model="themeColor" type="color"
                                                class="h-12 w-24 p-1 rounded-lg border border-gray-300 cursor-pointer">
                                            <div class="flex-1">
                                                <input x-model="themeColor" type="text"
                                                    class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring-primary/20 bg-white focus:bg-white transition-colors uppercase"
                                                    placeholder="#FF6B35">
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">Warna ini akan digunakan untuk tombol,
                                            link, dan elemen penting lainnya di halaman depan.</p>
                                        @error('theme_color')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Warna Sekunder
                                            (Background Tint)</label>
                                        <div class="flex items-center space-x-4">
                                            <input x-model="secondaryColor" type="color"
                                                class="h-12 w-24 p-1 rounded-lg border border-gray-300 cursor-pointer">
                                            <div class="flex-1">
                                                <input x-model="secondaryColor" type="text"
                                                    class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring-primary/20 bg-white focus:bg-white transition-colors uppercase"
                                                    placeholder="#FDF2EB">
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">Warna ini menggantikan warna latar
                                            belakang lembut (seperti orange-50). Sebaiknya pilih warna yang sangat
                                            muda/pucat.</p>
                                        @error('secondary_color')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="bg-white p-4 rounded-xl border border-orange-200 shadow-sm mt-4">
                                    <h4 class="text-sm font-bold text-gray-800 mb-3">Preview</h4>
                                    <div class="space-y-3">
                                        <button type="button"
                                            class="w-full py-2.5 px-4 rounded-xl text-white font-bold shadow-lg transition-all"
                                            :style="'background-color: ' + themeColor">
                                            Tombol Utama
                                        </button>
                                        <div class="p-3 rounded-lg border border-dashed border-gray-300"
                                            :style="'background-color: ' + secondaryColor">
                                            <p class="text-sm font-medium" :style="'color: ' + themeColor">Contoh
                                                Background Sekunder</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium text-gray-600">Link text:</span>
                                            <a href="#" class="font-medium underline"
                                                :style="'color: ' + themeColor">Contoh Link</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-orange-200">
                                    <button type="button" wire:click="resetThemeColor"
                                        onclick="return confirm('Kembalikan ke warna default?') || event.stopImmediatePropagation()"
                                        class="text-sm text-gray-500 hover:text-gray-700 flex items-center transition-colors">
                                        <i class="fa-solid fa-rotate-left mr-2"></i> Reset ke Default
                                        ({{ $default_theme_color }} / {{ $default_secondary_color }})
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="bg-primary hover:bg-primary-hover text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all hover:-translate-y-0.5 inline-flex items-center">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Tampilan
                        </button>
                    </div>
                </form>

                <!-- Domain Tab -->
            @elseif($activeTab === 'domain')
                <div class="space-y-8">
                    <!-- Subdomain Card -->
                    <div class="bg-indigo-50/50 rounded-2xl border border-indigo-100 p-6">
                        <h3 class="text-lg font-bold text-indigo-900 mb-2">Subdomain Sistem</h3>
                        <p class="text-sm text-indigo-600 mb-4">Setiap yayasan secara otomatis mendapatkan subdomain gratis.</p>
                        <div class="bg-white p-4 rounded-xl border border-indigo-200 flex items-center justify-between">
                            @php
                                $subdomain = collect($tenantDomains)->firstWhere('type', 'subdomain');
                            @endphp
                            @if($subdomain)
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center mr-3">
                                        <i class="fa-solid fa-link"></i>
                                    </div>
                                    <div>
                                        <a href="http://{{ $subdomain->domain }}" target="_blank" class="font-bold text-slate-800 hover:text-indigo-600">{{ $subdomain->domain }}</a>
                                        @if($subdomain->is_primary)
                                            <p class="text-xs text-slate-500"><i class="fa-solid fa-star text-amber-400 mr-1"></i> Domain Utama</p>
                                        @endif
                                    </div>
                                </div>
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full"><i class="fa-solid fa-check mr-1"></i> Aktif</span>
                            @endif
                        </div>
                    </div>

                    <!-- Custom Domain Section -->
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Custom Domain</h3>
                                    <p class="text-sm text-gray-500">Gunakan nama domain Anda sendiri (contoh: donasi.yayasan.com)</p>
                                </div>
                                @if(!$canUseCustomDomain)
                                    <span class="bg-slate-100 text-slate-600 px-3 py-1 text-xs font-bold rounded-full"><i class="fa-solid fa-lock mr-1"></i> Fitur Premium</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6">
                            @if($canUseCustomDomain)
                                <!-- Add Domain Form -->
                                <form wire:submit.prevent="addCustomDomain" class="mb-8 p-5 rounded-xl border border-gray-200 bg-white shadow-sm flex flex-col md:flex-row gap-4 items-end">
                                    <div class="flex-1 w-full">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tambahkan Domain Baru</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fa-solid fa-globe text-gray-400"></i>
                                            </div>
                                            <input wire:model="customDomain" type="text" placeholder="donasi.yayasan.com"
                                                class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 pl-10 py-2.5 text-base bg-gray-50 focus:bg-white transition-colors">
                                        </div>
                                        @error('customDomain') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <button type="submit" class="bg-primary hover:bg-primary-hover text-white font-semibold py-2.5 px-6 rounded-xl transition-colors w-full md:w-auto h-[46px]">
                                        Tambahkan
                                    </button>
                                </form>

                                <!-- DNS Instructions -->
                                <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-5 mb-8">
                                    <h4 class="text-sm font-bold text-blue-900 mb-3 flex items-center"><i class="fa-solid fa-circle-info mr-2"></i> Cara Mengarahkan Domain (DNS Setup)</h4>
                                    <p class="text-sm text-blue-800 mb-4">Masuk ke panel pengaturan DNS penyedia domain Anda, lalu tambahkan record berikut:</p>
                                    
                                    <div class="space-y-3">
                                        <div class="bg-white p-3 rounded-lg border border-blue-200 grid grid-cols-1 md:grid-cols-4 gap-2 text-sm text-gray-700 font-mono">
                                            <div><span class="text-xs text-gray-400 uppercase font-sans font-bold block mb-1">Type</span>CNAME</div>
                                            <div><span class="text-xs text-gray-400 uppercase font-sans font-bold block mb-1">Name / Host</span>donasi (atau subdomain)</div>
                                            <div class="md:col-span-2"><span class="text-xs text-gray-400 uppercase font-sans font-bold block mb-1">Target / Value</span>{{ config('tenancy.base_domain') }}</div>
                                        </div>
                                        <p class="text-xs text-gray-500 italic ml-1">Untuk menggunakan domain utama (tanpa www), gunakan A Record yang mengarah ke IP: <strong>{{ env('SERVER_IP', 'IP Server') }}</strong></p>
                                    </div>
                                </div>

                                <!-- Domain List -->
                                <h4 class="text-sm font-bold text-gray-900 mb-4">Daftar Custom Domain</h4>
                                <div class="space-y-3">
                                    @foreach(collect($tenantDomains)->where('type', 'custom') as $domain)
                                        <div class="p-4 rounded-xl border {{ $domain->is_primary ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white' }} flex flex-col md:flex-row md:items-center justify-between gap-4">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h5 class="font-bold text-gray-900 text-lg">{{ $domain->domain }}</h5>
                                                    @if($domain->is_primary)
                                                        <span class="bg-primary/10 text-primary text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">Utama</span>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex items-center gap-3 text-xs mt-2">
                                                    @if($domain->dns_verified)
                                                        <span class="text-emerald-600 font-medium flex items-center bg-emerald-50 px-2 py-1 rounded-md"><i class="fa-solid fa-circle-check mr-1.5"></i> Terverifikasi ({{ $domain->verified_at->format('d/m/Y') }})</span>
                                                    @else
                                                        <span class="text-red-500 font-medium flex items-center bg-red-50 px-2 py-1 rounded-md border border-red-100"><i class="fa-solid fa-circle-xmark mr-1.5"></i> DNS Belum Terdeteksi</span>
                                                    @endif
                                                    
                                                    @if($domain->last_checked_at)
                                                        <span class="text-gray-400">Pengecekan terakhir: {{ $domain->last_checked_at->diffForHumans() }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center space-x-2 shrink-0 border-t md:border-t-0 md:border-l border-gray-100 pt-3 md:pt-0 md:pl-4 mt-2 md:mt-0">
                                                @if(!$domain->dns_verified)
                                                    <button wire:click="verifyDomain({{ $domain->id }})" wire:loading.attr="disabled" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-2 rounded-lg transition-colors flex items-center">
                                                        <span wire:loading.remove wire:target="verifyDomain({{ $domain->id }})"><i class="fa-solid fa-rotate mr-1.5"></i> Verifikasi</span>
                                                        <span wire:loading wire:target="verifyDomain({{ $domain->id }})"><i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Mengecek...</span>
                                                    </button>
                                                @endif
                                                
                                                @if($domain->dns_verified && !$domain->is_primary)
                                                    <button wire:click="setPrimaryDomain({{ $domain->id }})" class="bg-primary/10 hover:bg-primary/20 text-primary text-xs font-semibold px-3 py-2 rounded-lg transition-colors">
                                                        Jadikan Utama
                                                    </button>
                                                @endif
                                                
                                                <button wire:click="removeDomain({{ $domain->id }})" onclick="return confirm('Hapus custom domain ini? Domain tidak akan dapat diakses lagi.') || event.stopImmediatePropagation()" class="text-gray-400 hover:text-red-500 p-2 rounded-lg hover:bg-red-50 transition-colors">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if(collect($tenantDomains)->where('type', 'custom')->isEmpty())
                                        <div class="p-8 text-center bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                            <div class="w-12 h-12 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center mx-auto mb-3">
                                                <i class="fa-solid fa-globe text-xl"></i>
                                            </div>
                                            <h5 class="text-sm font-bold text-gray-700">Belum ada custom domain</h5>
                                            <p class="text-xs text-gray-500 mt-1">Tambahkan domain Anda melalui form di atas.</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <!-- Upgrade CTA -->
                                <div class="text-center py-12 px-4 border-2 border-dashed border-indigo-200 rounded-2xl bg-indigo-50/30">
                                    <div class="w-16 h-16 rounded-2xl bg-indigo-100 text-indigo-500 flex items-center justify-center mx-auto mb-4">
                                        <i class="fa-solid fa-lock text-3xl"></i>
                                    </div>
                                    <h4 class="text-xl font-bold text-indigo-950 mb-2">Fitur Terkunci</h4>
                                    <p class="text-indigo-700/80 mb-6 max-w-sm mx-auto">Paket langganan yayasan Anda saat ini tidak mendukung fitur Custom Domain.</p>
                                    <a href="#" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5">
                                        Hubungi Admin untuk Upgrade Paket
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Bank Modal -->
    <div x-data="{ show: $wire.entangle('isBankModalOpen') }" x-show="show" x-transition:enter="transition ease-out duration-300"
        class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="show = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <form wire:submit.prevent="saveBank">
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900">
                                {{ $bankId ? 'Edit Rekening' : 'Tambah Rekening' }}</h3>
                            <button type="button" wire:click="closeBankModal"
                                class="text-gray-400 hover:text-gray-500"><i
                                    class="fa-solid fa-xmark text-xl"></i></button>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Bank</label>
                                <input wire:model="bank_name" type="text" placeholder="Contoh: BCA, Mandiri"
                                    class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                @error('bank_name')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Icon Bank
                                    (Opsional)</label>
                                <div
                                    class="flex items-center space-x-4 p-3 border border-gray-200 rounded-xl bg-gray-50">
                                    @if ($bank_icon)
                                        <img src="{{ $bank_icon->temporaryUrl() }}"
                                            class="h-12 w-20 object-contain rounded-lg border border-gray-200 bg-white p-1">
                                    @elseif ($existingBankIcon)
                                        <img src="{{ Storage::url($existingBankIcon) }}"
                                            class="h-12 w-20 object-contain rounded-lg border border-gray-200 bg-white p-1">
                                    @else
                                        <div
                                            class="h-12 w-20 rounded-lg bg-gray-200 flex items-center justify-center text-gray-400 border border-gray-300">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <input wire:model="bank_icon" type="file" accept="image/*"
                                            class="block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WebP, SVG. Maks 2MB.
                                        </p>
                                    </div>
                                </div>
                                @error('bank_icon')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Rekening</label>
                                <input wire:model="account_number" type="text"
                                    class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 font-mono py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                @error('account_number')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Atas Nama</label>
                                <input wire:model="account_holder_name" type="text"
                                    class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                @error('account_holder_name')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex items-center pt-2">
                                <label class="flex items-center cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input wire:model="is_active" type="checkbox"
                                            class="peer h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary cursor-pointer transition-all checked:bg-primary checked:border-transparent">
                                    </div>
                                    <span
                                        class="ml-2 text-sm text-gray-700 group-hover:text-gray-900 font-medium">Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50/80 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-100 rounded-b-2xl">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg shadow-primary/30 px-5 py-2.5 bg-primary text-base font-semibold text-white hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm transition-all hover:-translate-y-0.5">
                            Simpan
                        </button>
                        <button wire:click="closeBankModal" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all hover:bg-gray-100">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

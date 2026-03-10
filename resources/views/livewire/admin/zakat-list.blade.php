@section('title', 'Transaksi Zakat')
@section('header', 'Transaksi Zakat')

<div class="space-y-6">
    {{-- Tab Navigation --}}
    <div class="flex border-b border-gray-200">
        <button wire:click="setTab('transactions')"
            class="px-6 py-3 text-sm font-bold border-b-2 transition-all {{ $activeTab === 'transactions' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <i class="fa-solid fa-list-ul mr-2"></i> Transaksi
        </button>
        <button wire:click="setTab('settings')"
            class="px-6 py-3 text-sm font-bold border-b-2 transition-all {{ $activeTab === 'settings' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <i class="fa-solid fa-gears mr-2"></i> Pengaturan
        </button>
        <button wire:click="setTab('laporan')"
            class="px-6 py-3 text-sm font-bold border-b-2 transition-all {{ $activeTab === 'laporan' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <i class="fa-solid fa-hand-holding-heart mr-2"></i> Laporan Penyaluran
        </button>
    </div>

    @if ($activeTab === 'transactions')
        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-soft flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-moon text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Hari Ini</p>
                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($statToday, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-soft flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-calendar text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Bulan Ini</p>
                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($statThisMonth, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-soft flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-hand-holding-heart text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Total Terkumpul</p>
                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($statTotal, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Main Card - Transactions --}}
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100">
            {{-- Filter Bar --}}
            <div class="p-4 border-b border-gray-100 flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[200px]">
                    <input wire:model.live="search" type="text" placeholder="Cari nama, email, ID transaksi..."
                        class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white transition-colors">
                </div>
                <div>
                    <select wire:model.live="statusFilter"
                        class="rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Lunas</option>
                        <option value="expired">Expired</option>
                        <option value="failed">Gagal</option>
                    </select>
                </div>
                <div>
                    <select wire:model.live="zakatTypeFilter"
                        class="rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white">
                        <option value="">Semua Jenis</option>
                        <option value="fitrah">Zakat Fitrah</option>
                        <option value="maal">Zakat Mal</option>
                    </select>
                </div>
                <div>
                    <input wire:model.live="dateFrom" type="date"
                        class="rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white">
                </div>
                <div>
                    <input wire:model.live="dateTo" type="date"
                        class="rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white">
                </div>
                <button wire:click="resetFilters"
                    class="py-2.5 px-4 rounded-xl border border-gray-300 text-sm text-gray-600 hover:bg-gray-50 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </button>
                <div class="flex-1 flex justify-end">
                    <button wire:click="openExportModal"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center transition-all shadow-sm shadow-green-600/20 text-sm whitespace-nowrap">
                        <i class="fa-solid fa-file-excel mr-2"></i> Export
                    </button>
                </div>
            </div>

            @if (session()->has('success') && $activeTab === 'transactions')
                <div
                    class="mx-4 mt-4 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3 text-green-700">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif
            @if (session()->has('error'))
                <div
                    class="mx-4 mt-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 text-red-700">
                    <i class="fa-solid fa-circle-xmark text-xl"></i>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">ID Transaksi
                            </th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Muzakki</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jenis</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nominal</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Metode</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-4 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($payments as $payment)
                            <tr class="hover:bg-orange-50/30 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="font-mono text-xs text-gray-700">{{ $payment->external_id }}</span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $payment->created_at->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ $payment->customer_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $payment->customer_phone }}</p>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if ($payment->zakatTransaction)
                                        @if ($payment->zakatTransaction->zakat_type === 'fitrah')
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                                <i class="fa-solid fa-users mr-1"></i> Fitrah
                                                @if ($payment->zakatTransaction->jumlah_jiwa)
                                                    ({{ $payment->zakatTransaction->jumlah_jiwa }} jiwa)
                                                @endif
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                                                <i class="fa-solid fa-coins mr-1"></i> Mal
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <p class="text-sm font-bold text-gray-900">Rp
                                        {{ number_format($payment->amount + ($payment->unique_code ?? 0), 0, ',', '.') }}
                                    </p>
                                    @if ($payment->unique_code)
                                        <p class="text-xs text-gray-400">+ {{ $payment->unique_code }} kode unik</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $payment->payment_method ?? '-' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @php
                                        $statusMap = [
                                            'pending' => ['bg-yellow-100 text-yellow-700', 'Menunggu'],
                                            'paid' => ['bg-green-100 text-green-700', 'Lunas'],
                                            'failed' => ['bg-red-100 text-red-700', 'Gagal'],
                                            'expired' => ['bg-gray-100 text-gray-600', 'Expired'],
                                        ];
                                        [$badgeClass, $badgeLabel] = $statusMap[$payment->status] ?? [
                                            'bg-gray-100 text-gray-600',
                                            $payment->status,
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 py-1 {{ $badgeClass }} text-xs font-semibold rounded-full">{{ $badgeLabel }}</span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="showDetail({{ $payment->id }})"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Detail">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        @if ($payment->status === 'pending' && $payment->payment_type === 'bank_transfer')
                                            <button wire:click="confirmPayment({{ $payment->id }})"
                                                onclick="return confirm('Konfirmasi pembayaran {{ $payment->external_id }}?') || event.stopImmediatePropagation()"
                                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                title="Konfirmasi Lunas">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500 italic">
                                    Belum ada transaksi zakat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $payments->links() }}
            </div>
        </div>
    @elseif($activeTab === 'settings')
        {{-- Settings Tab --}}
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6">
            @if (session()->has('success') && $activeTab === 'settings')
                <div
                    class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3 text-green-700">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <form wire:submit.prevent="saveZakat">
                <div class="space-y-8">
                    <div class="bg-green-50/50 rounded-2xl border border-green-100 p-6">
                        <div class="flex items-center mb-6">
                            <span
                                class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center mr-3">
                                <i class="fa-solid fa-moon text-lg"></i>
                            </span>
                            <h3 class="text-lg font-bold text-green-900">Konfigurasi Perhitungan Zakat</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Zakat Fitrah --}}
                            <div class="bg-white rounded-xl border border-green-200 p-5 shadow-sm">
                                <h4 class="text-sm font-bold text-gray-800 mb-1">Zakat Fitrah</h4>
                                <p class="text-xs text-gray-500 mb-4">Harga zakat fitrah per jiwa dalam rupiah.
                                    Nilai ini akan menjadi dasar perhitungan di halaman depan.</p>
                                <div class="space-y-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga per Jiwa
                                        (Rp)</label>
                                    <div
                                        class="flex rounded-xl overflow-hidden border border-gray-300 focus-within:border-primary focus-within:ring focus-within:ring-primary/20 transition-all">
                                        <span
                                            class="inline-flex items-center px-4 bg-gray-50 border-r border-gray-300 text-gray-500 text-sm font-semibold font-mono">Rp</span>
                                        <input wire:model="zakat_fitrah_price" type="number" min="0"
                                            step="1000"
                                            class="flex-1 block w-full py-2.5 px-4 text-base bg-white focus:outline-none"
                                            placeholder="45000">
                                    </div>
                                    @error('zakat_fitrah_price')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                    <p class="text-[10px] text-gray-400 mt-2 italic">* Contoh: 45000 = Rp 45.000/jiwa
                                    </p>
                                </div>
                            </div>

                            {{-- Zakat Mal / Nisab --}}
                            <div class="bg-white rounded-xl border border-green-200 p-5 shadow-sm">
                                <h4 class="text-sm font-bold text-gray-800 mb-1">Zakat Mal — Harga Emas</h4>
                                <p class="text-xs text-gray-500 mb-4">Harga emas per gram digunakan untuk
                                    menghitung nisab zakat mal. Nisab = harga emas × 85 gram.</p>
                                <div class="space-y-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Emas per Gram
                                        (Rp)</label>
                                    <div
                                        class="flex rounded-xl overflow-hidden border border-gray-300 focus-within:border-primary focus-within:ring focus-within:ring-primary/20 transition-all">
                                        <span
                                            class="inline-flex items-center px-4 bg-gray-50 border-r border-gray-300 text-gray-500 text-sm font-semibold font-mono">Rp</span>
                                        <input wire:model="zakat_gold_price_per_gram" type="number" min="0"
                                            step="1000"
                                            class="flex-1 block w-full py-2.5 px-4 text-base bg-white focus:outline-none"
                                            placeholder="1500000">
                                    </div>
                                    @error('zakat_gold_price_per_gram')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                    @if ($zakat_gold_price_per_gram)
                                        <div class="mt-3 p-3 bg-green-600 rounded-lg text-white shadow-soft">
                                            <p
                                                class="text-[10px] uppercase font-bold tracking-wider opacity-80 mb-0.5">
                                                Nisab Wajib Zakat Mal</p>
                                            <p class="text-lg font-black">
                                                Rp {{ number_format($zakat_gold_price_per_gram * 85, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Banner Upload Section --}}
                    <div class="bg-blue-50/50 rounded-2xl border border-blue-100 p-6">
                        <div class="flex items-center mb-6">
                            <span
                                class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                                <i class="fa-solid fa-image text-lg"></i>
                            </span>
                            <h3 class="text-lg font-bold text-blue-900">Banner Halaman Zakat</h3>
                        </div>

                        <div class="space-y-6">
                            @if ($existingZakatBanner)
                                <div
                                    class="relative w-full max-w-2xl rounded-2xl overflow-hidden border-4 border-white shadow-lg group">
                                    <img src="{{ Storage::url($existingZakatBanner) }}"
                                        class="w-full h-auto object-cover" alt="Banner Zakat">
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <button type="button" wire:click="deleteZakatBanner"
                                            wire:confirm="Hapus banner ini?"
                                            class="bg-red-600 hover:bg-red-700 text-white p-3 rounded-full transition-colors">
                                            <i class="fa-solid fa-trash-can text-xl"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <div x-data="{ preview: null }" class="space-y-4">
                                <label class="block">
                                    <span class="text-sm font-bold text-gray-700 block mb-2">Unggah Banner Baru</span>
                                    <div class="flex items-center justify-center w-full">
                                        <label
                                            class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-blue-300 rounded-2xl cursor-pointer bg-blue-50/30 hover:bg-blue-50 transition-colors relative overflow-hidden">
                                            <template x-if="!preview">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <i
                                                        class="fa-solid fa-cloud-arrow-up text-3xl text-blue-400 mb-3"></i>
                                                    <p class="mb-2 text-sm text-blue-600 font-semibold">Klik untuk
                                                        unggah banner</p>
                                                    <p class="text-xs text-blue-400 font-medium">PNG, JPG (Rekomendasi:
                                                        1200x400px)</p>
                                                </div>
                                            </template>
                                            <template x-if="preview">
                                                <img :src="preview"
                                                    class="absolute inset-0 w-full h-full object-cover">
                                            </template>
                                            <input type="file" wire:model="zakatBannerImage" class="hidden"
                                                accept="image/*"
                                                @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result }; reader.readAsDataURL(file); }">
                                        </label>
                                    </div>
                                </label>
                                @error('zakatBannerImage')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                                <div wire:loading wire:target="zakatBannerImage"
                                    class="text-xs text-blue-600 font-bold animate-pulse">
                                    <i class="fa-solid fa-spinner fa-spin mr-1"></i> Sedang mengunggah...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="bg-primary hover:bg-primary-hover text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all hover:-translate-y-0.5 inline-flex items-center">
                        <i class="fa-solid fa-save mr-2"></i> Simpan Pengaturan Zakat
                    </button>
                </div>
            </form>
        </div>
    @elseif($activeTab === 'laporan')
        {{-- Distribution Tab --}}
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Laporan Penyaluran Zakat</h3>
                    <p class="text-xs text-gray-500">Kelola data penyaluran dana zakat kepada mustahik</p>
                </div>
                <button wire:click="createDistribution"
                    class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Penyaluran
                </button>
            </div>

            @if (session()->has('success') && $activeTab === 'laporan')
                <div
                    class="mx-4 mt-4 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3 text-green-700">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Judul Laporan
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nominal</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($distributions as $dist)
                            <tr class="hover:bg-orange-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900">{{ $dist->title }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $dist->distribution_date->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm font-bold text-primary">Rp
                                        {{ number_format($dist->amount, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="editDistribution({{ $dist->id }})"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button wire:click="confirmDeleteDistribution({{ $dist->id }})"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic">
                                    Belum ada data penyaluran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($distributions->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $distributions->links() }}
                </div>
            @endif
        </div>
    @endif

    {{-- Detail Modal --}}
    <div x-data="{ show: $wire.entangle('isOpen') }" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        style="display:none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="show = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 relative">
                @if ($selectedPayment)
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-xl font-bold text-gray-900">Detail Transaksi Zakat</h3>
                            <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">ID Transaksi</span>
                                <span
                                    class="font-mono font-semibold text-gray-800">{{ $selectedPayment->external_id }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">Muzakki</span>
                                <span class="font-semibold text-gray-800">{{ $selectedPayment->customer_name }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">Telepon</span>
                                <span class="text-gray-800">{{ $selectedPayment->customer_phone ?? '-' }}</span>
                            </div>
                            @if ($selectedPayment->zakatTransaction)
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-500">Jenis Zakat</span>
                                    <span
                                        class="font-semibold text-gray-800">{{ $selectedPayment->zakatTransaction->zakat_type_label }}</span>
                                </div>
                                @if ($selectedPayment->zakatTransaction->zakat_type === 'fitrah' && $selectedPayment->zakatTransaction->jumlah_jiwa)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-500">Jumlah Jiwa</span>
                                        <span
                                            class="font-semibold text-gray-800">{{ $selectedPayment->zakatTransaction->jumlah_jiwa }}
                                            jiwa</span>
                                    </div>
                                @endif
                                @if ($selectedPayment->zakatTransaction->zakat_type === 'maal' && $selectedPayment->zakatTransaction->total_harta)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-500">Total Harta</span>
                                        <span class="text-gray-800">Rp
                                            {{ number_format($selectedPayment->zakatTransaction->total_harta, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-500">Nisab saat itu</span>
                                        <span class="text-gray-800">Rp
                                            {{ number_format($selectedPayment->zakatTransaction->nisab_at_time, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            @endif
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">Nominal Zakat</span>
                                <span class="font-bold text-gray-900">Rp
                                    {{ number_format($selectedPayment->amount, 0, ',', '.') }}</span>
                            </div>
                            @if ($selectedPayment->unique_code)
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-500">Kode Unik</span>
                                    <span class="text-gray-800">+{{ $selectedPayment->unique_code }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">Metode Bayar</span>
                                <span class="text-gray-800">{{ $selectedPayment->payment_method ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">Status</span>
                                <span
                                    class="font-semibold {{ $selectedPayment->status === 'paid' ? 'text-green-600' : ($selectedPayment->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ ucfirst($selectedPayment->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-500">Tanggal</span>
                                <span
                                    class="text-gray-800">{{ $selectedPayment->created_at->translatedFormat('d F Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                        @if ($selectedPayment->status === 'pending' && $selectedPayment->payment_type === 'bank_transfer')
                            <button wire:click="confirmPayment({{ $selectedPayment->id }})"
                                onclick="return confirm('Konfirmasi pembayaran ini?') || event.stopImmediatePropagation()"
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-5 rounded-xl flex items-center gap-2 transition-colors">
                                <i class="fa-solid fa-circle-check"></i> Konfirmasi Lunas
                            </button>
                        @endif
                        <button wire:click="closeModal"
                            class="bg-white border border-gray-300 text-gray-700 font-semibold py-2 px-5 rounded-xl hover:bg-gray-50 transition-colors">
                            Tutup
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Distribution Create/Edit Modal --}}
    <div x-data="{ show: $wire.entangle('showDistributionModal') }" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        style="display:none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="show = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100 relative">
                <form wire:submit.prevent="storeDistribution">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-xl font-bold text-gray-900">{{ $distributionId ? 'Edit' : 'Tambah' }}
                                Penyaluran Zakat</h3>
                            <button type="button" wire:click="closeDistributionModal"
                                class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Laporan</label>
                                <input wire:model="distributionTitle" type="text"
                                    class="block w-full rounded-xl bg-gray-50 border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm"
                                    placeholder="Contoh: Penyaluran Zakat Fitrah 1445H">
                                @error('distributionTitle')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal
                                        Penyaluran</label>
                                    <input wire:model="distributionDate" type="date"
                                        class="block w-full rounded-xl bg-gray-50 border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm">
                                    @error('distributionDate')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nominal Disalurkan
                                        (Rp)</label>
                                    <input wire:model="distributionAmount" type="number"
                                        class="block w-full rounded-xl bg-gray-50 border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm"
                                        placeholder="1000000">
                                    @error('distributionAmount')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi / Laporan
                                    Detail</label>
                                <div wire:ignore>
                                    <div x-data="quillEditor($wire.entangle('distributionDescription').live)">
                                        <div x-ref="quillEditor" class="min-h-[200px]"></div>
                                    </div>
                                </div>
                                @error('distributionDescription')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                        <button type="button" wire:click="closeDistributionModal"
                            class="bg-white border border-gray-300 text-gray-700 font-semibold py-2 px-5 rounded-xl hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-primary hover:bg-primary-hover text-white font-semibold py-2 px-5 rounded-xl flex items-center gap-2 transition-colors">
                            <i class="fa-solid fa-save"></i> Simpan Penyaluran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-data="{ show: $wire.entangle('confirmingDistributionDeletion') }" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        style="display:none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="show = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100 relative">
                <div class="p-6 text-center">
                    <div
                        class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-trash-can text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Laporan?</h3>
                    <p class="text-gray-500 mb-6">Apakah Anda yakin ingin menghapus laporan penyaluran ini? Tindakan
                        ini tidak dapat dibatalkan.</p>
                    <div class="flex justify-center gap-3">
                        <button type="button" wire:click="closeDeleteModal"
                            class="bg-white border border-gray-300 text-gray-700 font-semibold py-2.5 px-6 rounded-xl hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="button" wire:click="deleteDistribution"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-6 rounded-xl transition-colors">
                            Ya, Hapus Laporan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div x-data="{ show: $wire.entangle('isExportModalOpen').live }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="show = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                            <i class="fa-solid fa-file-excel text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">Export Data Zakat</h3>
                            <p class="text-sm text-gray-500 mt-1 mb-5">
                                Pilih rentang tanggal dan jenis zakat untuk mengekspor data transaksi ke file Excel.
                            </p>

                            <div class="space-y-4">
                                {{-- Pilih Jenis Zakat --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Jenis Zakat
                                    </label>
                                    <select wire:model="exportZakatType"
                                        class="block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring focus:ring-green-500/20 py-2.5 px-3 text-sm">
                                        <option value="">— Semua Jenis Zakat —</option>
                                        <option value="fitrah">Zakat Fitrah</option>
                                        <option value="maal">Zakat Mal</option>
                                    </select>
                                    @if ($exportZakatType)
                                        <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check"></i>
                                            Hanya transaksi zakat jenis ini yang akan diexport
                                        </p>
                                    @else
                                        <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-info"></i>
                                            Semua jenis zakat akan diexport
                                        </p>
                                    @endif
                                </div>

                                {{-- Tanggal Mulai --}}
                                <div>
                                    <label for="startDate" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tanggal Mulai
                                    </label>
                                    <input wire:model="startDate" type="date" id="startDate"
                                        class="block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring focus:ring-green-500/20 py-2.5 px-3 text-sm">
                                    @error('startDate')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Tanggal Selesai --}}
                                <div>
                                    <label for="endDate" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tanggal Selesai
                                    </label>
                                    <input wire:model="endDate" type="date" id="endDate"
                                        class="block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring focus:ring-green-500/20 py-2.5 px-3 text-sm">
                                    @error('endDate')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl border-t border-gray-100">
                    <button wire:click="exportData" type="button"
                        class="inline-flex items-center justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-green-600 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all">
                        <i class="fa-solid fa-file-excel mr-2"></i> Export Excel
                    </button>
                    <button wire:click="closeExportModal" type="button"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-all">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

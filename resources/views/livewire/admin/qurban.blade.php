@section('title', 'Manajemen Qurban')
@section('header', 'Qurban & Tabungan')

<div class="space-y-6">
    <div class="bg-white overflow-hidden shadow-soft rounded-2xl border border-gray-100">
        <!-- Modern Tabs -->
        <div class="border-b border-gray-100">
            <nav class="flex space-x-1 px-4 pt-2" aria-label="Tabs">
                <button wire:click="setTab('animals')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2 
                    {{ $activeTab === 'animals' ? 'text-primary border-primary bg-primary/5' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-cow mr-2"></i> Hewan Qurban
                </button>
                <button wire:click="setTab('orders')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2 
                    {{ $activeTab === 'orders' ? 'text-primary border-primary bg-primary/5' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-cart-shopping mr-2"></i> Pesanan
                </button>
                <button wire:click="setTab('savings')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2 
                    {{ $activeTab === 'savings' ? 'text-primary border-primary bg-primary/5' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-wallet mr-2"></i> Tabungan
                </button>
                <button wire:click="setTab('content')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2 
                    {{ $activeTab === 'content' ? 'text-primary border-primary bg-primary/5' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-file-pen mr-2"></i> Konten Halaman
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Sub-tabs for Animals -->
            @if ($activeTab === 'animals')
                <div class="flex items-center gap-2 mb-4">
                    <button wire:click="setAnimalType('langsung')"
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200
                    {{ $animalType === 'langsung' ? 'bg-primary text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        <i class="fa-solid fa-bolt mr-1.5"></i> Qurban Langsung
                    </button>
                    <button wire:click="setAnimalType('tabungan')"
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200
                    {{ $animalType === 'tabungan' ? 'bg-primary text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        <i class="fa-solid fa-wallet mr-1.5"></i> Qurban Tabungan
                    </button>
                </div>
            @endif

            {{-- Stat Cards - hanya tampil di tab orders & savings --}}
            @if ($activeTab === 'orders' || $activeTab === 'savings')
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div
                        class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Hari Ini
                                </p>
                                <h3 class="text-lg font-bold text-gray-900">Rp
                                    {{ number_format($statToday, 0, ',', '.') }}</h3>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-green-50 text-green-500 flex items-center justify-center">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kemarin</p>
                                <h3 class="text-lg font-bold text-gray-900">Rp
                                    {{ number_format($statYesterday, 0, ',', '.') }}</h3>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                                <i class="fa-solid fa-calendar-minus"></i>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Bulan Ini
                                </p>
                                <h3 class="text-lg font-bold text-gray-900">Rp
                                    {{ number_format($statThisMonth, 0, ',', '.') }}</h3>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                <i class="fa-solid fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Bulan Lalu
                                </p>
                                <h3 class="text-lg font-bold text-gray-900">Rp
                                    {{ number_format($statLastMonth, 0, ',', '.') }}</h3>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center">
                                <i class="fa-solid fa-calendar-xmark"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Controls -->
            @if ($activeTab !== 'content')
                @if ($activeTab === 'orders' || $activeTab === 'savings')
                    {{-- Filter Bar 2-row untuk orders & savings --}}
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 mb-6 space-y-4">
                        {{-- Baris 1: Search & Status --}}
                        <div class="flex flex-col md:flex-row gap-3">
                            <div class="relative flex-1 group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i
                                        class="fa-solid fa-search text-gray-400 group-focus-within:text-primary transition-colors"></i>
                                </div>
                                <input wire:model.live.debounce.300ms="search" type="text"
                                    placeholder="{{ $activeTab === 'orders' ? 'Cari ID transaksi, nama donatur...' : 'Cari nama penabung...' }}"
                                    class="pl-10 w-full rounded-xl border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm transition-all shadow-sm">
                            </div>
                            <div class="flex gap-3">
                                @if ($activeTab === 'orders')
                                    <select wire:model.live="statusFilter"
                                        class="rounded-xl border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-3 text-sm shadow-sm">
                                        <option value="">Semua Status</option>
                                        <option value="paid">Paid</option>
                                        <option value="pending">Pending</option>
                                        <option value="expired">Expired</option>
                                        <option value="failed">Gagal</option>
                                    </select>
                                @else
                                    <select wire:model.live="statusFilter"
                                        class="rounded-xl border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-3 text-sm shadow-sm">
                                        <option value="">Semua Status</option>
                                        <option value="active">Aktif</option>
                                        <option value="completed">Selesai</option>
                                    </select>
                                @endif
                            </div>
                        </div>

                        {{-- Baris 2: Tanggal & Aksi --}}
                        <div
                            class="flex flex-col md:flex-row justify-between items-center gap-3 pt-4 border-t border-gray-200/60">
                            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                                <span class="text-sm text-gray-500 font-medium hidden sm:block">Rentang:</span>
                                <div class="grid grid-cols-2 sm:flex items-center gap-2 w-full sm:w-auto">
                                    <input wire:model.live="dateFrom" type="date"
                                        class="w-full sm:w-auto rounded-xl border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 py-2 px-3 text-sm shadow-sm">
                                    <span class="text-gray-400 text-sm hidden sm:block">s/d</span>
                                    <input wire:model.live="dateTo" type="date"
                                        class="w-full sm:w-auto rounded-xl border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 py-2 px-3 text-sm shadow-sm">
                                </div>
                                @if ($search || $statusFilter || $dateFrom || $dateTo)
                                    <button wire:click="resetFilters"
                                        class="text-xs font-semibold text-gray-500 hover:text-red-500 underline transition-colors">
                                        Reset Filter
                                    </button>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <span>Tampil:</span>
                                    <select wire:model.live="perPage"
                                        class="rounded-lg border-gray-200 bg-white focus:border-primary focus:ring py-1.5 px-3 shadow-sm text-sm">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                                <button wire:click="openExportModal"
                                    class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-xl inline-flex items-center transition-all shadow-sm text-sm whitespace-nowrap">
                                    <i class="fa-solid fa-file-excel mr-2"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Controls original untuk tab animals --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                        <div class="relative w-full sm:w-1/3 group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i
                                    class="fa-solid fa-search text-gray-400 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari data..."
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
                            <button wire:click="createAnimal"
                                class="w-full sm:w-auto bg-primary hover:bg-primary-hover text-white font-semibold py-2.5 px-5 rounded-xl inline-flex items-center justify-center transition-all duration-200 shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5">
                                <i class="fa-solid fa-plus mr-2"></i> Tambah
                                {{ $animalType === 'tabungan' ? 'Target Tabungan' : 'Hewan' }}
                            </button>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Content -->
            <div class="overflow-hidden rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    @if ($activeTab === 'animals')
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Gambar</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Kategori</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Harga</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Stok</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($data as $animal)
                                    <tr class="hover:bg-orange-50/30 transition-colors duration-150 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img class="h-12 w-12 rounded-xl object-cover shadow-sm group-hover:shadow-md transition-shadow border border-gray-100"
                                                src="{{ $animal->image }}" alt="">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ $animal->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                            <span
                                                class="px-2 py-1 bg-gray-100 rounded-md text-xs font-medium">{{ $animal->category }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp
                                            {{ number_format($animal->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span
                                                class="{{ $animal->stock < 5 ? 'text-red-500 font-bold' : '' }}">{{ $animal->stock }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button wire:click="toggleAnimalStatus({{ $animal->id }})"
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full transition-colors duration-200 {{ $animal->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                                {{ $animal->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button wire:click="editAnimal({{ $animal->id }})"
                                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button wire:click="deleteAnimal({{ $animal->id }})"
                                                    onclick="return confirm('Yakin hapus hewan ini?') || event.stopImmediatePropagation()"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">Tidak ada data
                                            hewan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @elseif($activeTab === 'orders')
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        ID Transaksi</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Donatur</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Hewan</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Atas Nama</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        FU</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($data as $order)
                                    <tr class="hover:bg-orange-50/30 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                            {{ $order->transaction_id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">{{ $order->donor_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <span class="font-medium">{{ $order->animal->name ?? '-' }}</span>
                                            <span class="text-xs text-gray-400 ml-1">({{ $order->hijri_year }})</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->qurban_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->status == 'paid' ? 'bg-green-100 text-green-800' : ($order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($order->payment)
                                                <div class="flex justify-center space-x-1">
                                                    @php
                                                        $typeTemplates = $followups['qurban'] ?? collect();
                                                    @endphp

                                                    @foreach (['FollowUp1' => 1, 'FollowUp2' => 2, 'FollowUp3' => 3, 'FollowUp4' => 4] as $seqKey => $seqIndex)
                                                        @php
                                                            $hasTemplate = $typeTemplates
                                                                ->where('followup_sequence', $seqKey)
                                                                ->first();
                                                            $isSent = $order->payment->whatsappMessageLogs
                                                                ->where('event_type', 'followup_' . $seqKey)
                                                                ->where('status', 'sent')
                                                                ->isNotEmpty();
                                                            $isFailed = $order->payment->whatsappMessageLogs
                                                                ->where('event_type', 'followup_' . $seqKey)
                                                                ->where('status', 'failed')
                                                                ->isNotEmpty();

                                                            $btnClass = 'bg-gray-100 text-gray-500';
                                                            if ($isSent) {
                                                                $btnClass = 'bg-blue-600 text-white';
                                                            } elseif ($isFailed) {
                                                                $btnClass =
                                                                    'bg-red-100 text-red-600 border border-red-200';
                                                            } elseif ($hasTemplate) {
                                                                $btnClass =
                                                                    'bg-green-100 text-green-600 hover:bg-green-600 hover:text-white';
                                                            }
                                                        @endphp

                                                        @if ($hasTemplate)
                                                            @php
                                                                $rawContent = $hasTemplate->content;
                                                                // Common Replacements
                                                                $rawContent = str_replace(
                                                                    '{{nama}}',
                                                                    $order->donor_name ?? 'Hamba Allah',
                                                                    $rawContent,
                                                                );
                                                                $rawContent = str_replace(
                                                                    '{{tanggal}}',
                                                                    $order->created_at->translatedFormat('d F Y'),
                                                                    $rawContent,
                                                                );

                                                                // Specific Replacements for Qurban Order
                                                                $rawContent = str_replace(
                                                                    '{{jenis_hewan}}',
                                                                    $order->animal->name ?? '-',
                                                                    $rawContent,
                                                                );
                                                                $rawContent = str_replace(
                                                                    '{{tipe_qurban}}',
                                                                    $order->animal->type ?? '-',
                                                                    $rawContent,
                                                                );
                                                                $rawContent = str_replace(
                                                                    '{{harga}}',
                                                                    'Rp ' .
                                                                        number_format(
                                                                            $order->amount +
                                                                                ($order->payment?->unique_code ?? 0),
                                                                            0,
                                                                            ',',
                                                                            '.',
                                                                        ),
                                                                    $rawContent,
                                                                );
                                                                $rawContent = str_replace(
                                                                    '{{link_pembayaran}}',
                                                                    route('payment.status', [
                                                                        'id' => $order->payment->external_id ?? '',
                                                                    ]),
                                                                    $rawContent,
                                                                );
                                                                $waLink = $this->getFollowupUrl(
                                                                    $order,
                                                                    $seqKey,
                                                                    'order',
                                                                );
                                                            @endphp

                                                            <a href="{{ $waLink }}" target="_blank"
                                                                class="w-7 h-7 rounded-full flex items-center justify-center transition-all relative group {{ $btnClass }}"
                                                                title="{{ ($isSent ? 'Terkirim - ' : ($isFailed ? 'Gagal - ' : 'Kirim ')) . ($hasTemplate->name ?? 'Follow Up ' . $seqIndex) }}">
                                                                <i class="fa-brands fa-whatsapp text-lg"></i>
                                                                @if ($isSent)
                                                                    <span
                                                                        class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-white border border-blue-600 text-blue-600 rounded-full flex items-center justify-center text-[9px] font-bold">
                                                                        <i class="fa-solid fa-check text-[7px]"></i>
                                                                    </span>
                                                                @elseif($isFailed)
                                                                    <span
                                                                        class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-white border border-red-600 text-red-600 rounded-full flex items-center justify-center text-[9px] font-bold">
                                                                        <i
                                                                            class="fa-solid fa-exclamation text-[7px]"></i>
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-white border border-green-600 text-green-600 rounded-full flex items-center justify-center text-[9px] font-bold group-hover:border-white group-hover:text-green-600">
                                                                        {{ $seqIndex }}
                                                                    </span>
                                                                @endif
                                                            </a>
                                                        @else
                                                            <span
                                                                class="w-7 h-7 rounded-full bg-gray-50 text-gray-300 flex items-center justify-center cursor-not-allowed select-none relative"
                                                                title="Template belum tersedia">
                                                                <i class="fa-brands fa-whatsapp text-lg"></i>
                                                                <span
                                                                    class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-gray-100 border border-gray-300 text-gray-400 rounded-full flex items-center justify-center text-[9px] font-bold">
                                                                    {{ $seqIndex }}
                                                                </span>
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button wire:click="showOrder({{ $order->id }})"
                                                class="text-primary hover:text-primary-hover font-medium flex items-center justify-end gap-1 ml-auto">
                                                Detail <i class="fa-solid fa-chevron-right text-xs"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada
                                            pesanan qurban.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @elseif($activeTab === 'savings')
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Donatur</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Target</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Terkumpul</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Progress</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        FU</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($data as $saving)
                                    <tr class="hover:bg-orange-50/30 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ $saving->donor_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                            {{ str_replace('-', ' ', $saving->target_animal_type) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">Rp
                                            {{ number_format($saving->saved_amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <span
                                                    class="mr-2 w-8 text-right font-medium text-xs">{{ $saving->progress }}%</span>
                                                <div class="w-24 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                                    <div class="bg-gradient-to-r from-primary to-secondary h-1.5 rounded-full"
                                                        style="width: {{ $saving->progress }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $saving->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($saving->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center space-x-1">
                                                @php
                                                    // For Savings, we don't have a single "Payment".
// We will send FU based on the SAVING ACCOUNT context.
// However, sendFollowup requires a paymentId currently.
// To fix this properly, we need to adapt sendFollowup or pass a proxy payment.
// BUT, looking at DonationList, the logic is: Payment -> QurbanSaving.
// So if we find the LATEST payment for this saving, we can use it?
// QurbanSaving hasMany deposits (QurbanSavingsDeposit).
// QurbanSavingsDeposit has belongsTo Payment.

// Let's get the latest successful deposit/payment to use as context.
                                                    // If no deposits yet, we can't really send "transaction based" FU easily without a Payment record.
// But wait, the templates might be generic "Ayo nabung".
// If no payment exists, we can't use 'paymentId'.

                                                    // WORKAROUND: We will try to find the latest deposit's payment.
// If no deposit, maybe we can't send FU yet (as it's usually "Follow Up Pembayaran" or "Progress").
// Actually, let's grab the latest payment from deposits.

                                                    $latestDeposit = $saving->deposits()->latest()->first();
                                                    $latestPayment = $latestDeposit ? $latestDeposit->payment : null;

                                                    $typeTemplates = $followups['tabungan_qurban'] ?? collect();
                                                @endphp

                                                @if ($latestPayment)
                                                    @foreach (['FollowUp1' => 1, 'FollowUp2' => 2, 'FollowUp3' => 3, 'FollowUp4' => 4] as $seqKey => $seqIndex)
                                                        @php
                                                            $hasTemplate = $typeTemplates
                                                                ->where('followup_sequence', $seqKey)
                                                                ->first();
                                                            // Check logs on the latest payment? Or any payment for this saving?
                                                            // Ideally logs should be linked to the Saving Account too, but currently linked to Payment.
                                                            // Let's check logs on the latest payment for now.

$isSent = $latestPayment->whatsappMessageLogs
    ->where('event_type', 'followup_' . $seqKey)
    ->where('status', 'sent')
    ->isNotEmpty();
$isFailed = $latestPayment->whatsappMessageLogs
    ->where('event_type', 'followup_' . $seqKey)
    ->where('status', 'failed')
    ->isNotEmpty();

$btnClass = 'bg-gray-100 text-gray-500';
if ($isSent) {
    $btnClass = 'bg-blue-600 text-white';
} elseif ($isFailed) {
    $btnClass =
        'bg-red-100 text-red-600 border border-red-200';
} elseif ($hasTemplate) {
    $btnClass =
        'bg-green-100 text-green-600 hover:bg-green-600 hover:text-white';
                                                            }
                                                        @endphp

                                                        @if ($hasTemplate)
                                                            @php
                                                                $waLink = $this->getFollowupUrl(
                                                                    $saving,
                                                                    $seqKey,
                                                                    'saving',
                                                                );
                                                            @endphp

                                                            <a href="{{ $waLink }}" target="_blank"
                                                                class="w-7 h-7 rounded-full flex items-center justify-center transition-all relative group {{ $btnClass }}"
                                                                title="{{ ($isSent ? 'Terkirim - ' : ($isFailed ? 'Gagal - ' : 'Kirim ')) . ($hasTemplate->name ?? 'Follow Up ' . $seqIndex) }}">
                                                                <i class="fa-brands fa-whatsapp text-lg"></i>
                                                                @if ($isSent)
                                                                    <span
                                                                        class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-white border border-blue-600 text-blue-600 rounded-full flex items-center justify-center text-[9px] font-bold">
                                                                        <i class="fa-solid fa-check text-[7px]"></i>
                                                                    </span>
                                                                @elseif($isFailed)
                                                                    <span
                                                                        class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-white border border-red-600 text-red-600 rounded-full flex items-center justify-center text-[9px] font-bold">
                                                                        <i
                                                                            class="fa-solid fa-exclamation text-[7px]"></i>
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-white border border-green-600 text-green-600 rounded-full flex items-center justify-center text-[9px] font-bold group-hover:border-white group-hover:text-green-600">
                                                                        {{ $seqIndex }}
                                                                    </span>
                                                                @endif
                                                            </a>
                                                        @else
                                                            <span
                                                                class="w-7 h-7 rounded-full bg-gray-50 text-gray-300 flex items-center justify-center cursor-not-allowed select-none relative"
                                                                title="Template belum tersedia">
                                                                <i class="fa-brands fa-whatsapp text-lg"></i>
                                                                <span
                                                                    class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-gray-100 border border-gray-300 text-gray-400 rounded-full flex items-center justify-center text-[9px] font-bold">
                                                                    {{ $seqIndex }}
                                                                </span>
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Belum ada
                                                        transaksi</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button wire:click="showSaving({{ $saving->id }})"
                                                class="text-primary hover:text-primary-hover font-medium flex items-center justify-end gap-1 ml-auto">
                                                Detail <i class="fa-solid fa-chevron-right text-xs"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada data
                                            tabungan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @elseif($activeTab === 'content')
                        <div class="p-6">
                            <form wire:submit.prevent="saveTabunganContent">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Left Column -->
                                    <div class="space-y-6">
                                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Header & Deskripsi
                                        </h3>

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">Judul
                                                Halaman</label>
                                            <input wire:model="contentTitle" type="text"
                                                class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                            @error('contentTitle')
                                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-gray-700 mb-1">Subtitle</label>
                                            <input wire:model="contentSubtitle" type="text"
                                                class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                            @error('contentSubtitle')
                                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div wire:ignore>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi
                                                Program</label>
                                            <div class="bg-white rounded-xl border border-gray-300 overflow-hidden"
                                                x-data="quillEditor($wire.entangle('contentDescription').live)">
                                                <div x-ref="quillEditor" class="min-h-[200px]"></div>
                                            </div>
                                            @error('contentDescription')
                                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center">
                                                <label class="block text-sm font-semibold text-gray-700">Keunggulan
                                                    Program</label>
                                                <button type="button" wire:click="addBenefit"
                                                    class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded-lg font-medium transition-colors">
                                                    <i class="fa-solid fa-plus mr-1"></i> Tambah
                                                </button>
                                            </div>
                                            @foreach ($contentBenefits as $index => $benefit)
                                                <div class="flex gap-2">
                                                    <input wire:model="contentBenefits.{{ $index }}"
                                                        type="text"
                                                        class="block w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2 px-3 text-sm bg-gray-50 focus:bg-white transition-colors">
                                                    <button type="button"
                                                        wire:click="removeBenefit({{ $index }})"
                                                        class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                            @error('contentBenefits.*')
                                                <span class="text-red-500 text-xs block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="space-y-6">
                                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Informasi Akad &
                                            Syarat</h3>

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">Judul
                                                Akad</label>
                                            <input wire:model="contentAkadTitle" type="text"
                                                class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi
                                                Akad</label>
                                            <textarea wire:model="contentAkadDescription" rows="2"
                                                class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors"></textarea>
                                        </div>

                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center">
                                                <label class="block text-sm font-semibold text-gray-700">Syarat &
                                                    Ketentuan (Modal)</label>
                                                <button type="button" wire:click="addTerm"
                                                    class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded-lg font-medium transition-colors">
                                                    <i class="fa-solid fa-plus mr-1"></i> Tambah
                                                </button>
                                            </div>

                                            <div class="space-y-4">
                                                @foreach ($contentTerms as $index => $term)
                                                    <div
                                                        class="p-3 bg-gray-50 rounded-xl border border-gray-200 relative group">
                                                        <button type="button"
                                                            wire:click="removeTerm({{ $index }})"
                                                            class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors">
                                                            <i class="fa-solid fa-xmark"></i>
                                                        </button>
                                                        <div class="space-y-2">
                                                            <input wire:model="contentTerms.{{ $index }}.title"
                                                                type="text" placeholder="Judul Syarat"
                                                                class="block w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-1.5 px-3 text-sm font-semibold bg-white focus:bg-white transition-colors">
                                                            <textarea wire:model="contentTerms.{{ $index }}.description" rows="2" placeholder="Penjelasan..."
                                                                class="block w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-1.5 px-3 text-xs bg-white focus:bg-white transition-colors"></textarea>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @error('contentTerms.*.title')
                                                <span class="text-red-500 text-xs block">{{ $message }}</span>
                                            @enderror
                                            @error('contentTerms.*.description')
                                                <span class="text-red-500 text-xs block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 flex justify-end">
                                    <button type="submit"
                                        class="bg-primary hover:bg-primary-hover text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all hover:-translate-y-0.5 inline-flex items-center">
                                        <i class="fa-solid fa-save mr-2"></i> Simpan Konten
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pagination -->
            @if ($activeTab !== 'content')
                <div class="mt-6">
                    {{ $data->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Animal Modal -->
    <div x-data="{ show: $wire.entangle('isAnimalModalOpen') }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="show = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <form wire:submit.prevent="saveAnimal">
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $animalId ? 'Edit' : 'Tambah' }}
                                    {{ $type === 'tabungan' ? 'Target Tabungan' : 'Hewan Qurban' }}</h3>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 mt-1 rounded-md text-xs font-medium {{ $type === 'tabungan' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $type === 'tabungan' ? 'Qurban Tabungan' : 'Qurban Langsung' }}
                                </span>
                            </div>
                            <button type="button" wire:click="closeAnimalModal"
                                class="text-gray-400 hover:text-gray-500"><i
                                    class="fa-solid fa-xmark text-xl"></i></button>
                        </div>

                        <input type="hidden" wire:model="type">

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Hewan</label>
                                <input wire:model="name" type="text"
                                    class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                @error('name')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                                    <select wire:model="category"
                                        class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                        <option value="kambing">Kambing</option>
                                        <option value="domba">Domba</option>
                                        <option value="sapi">Sapi</option>
                                        <option value="kerbau">Kerbau</option>
                                    </select>
                                    @error('category')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Berat</label>
                                    <input wire:model="weight" type="text" placeholder="ex: 25-30 kg"
                                        class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('weight')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga (Rp)</label>
                                    <input wire:model="price" type="number"
                                        class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('price')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Stok</label>
                                    <input wire:model="stock" type="number"
                                        class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('stock')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Komisi</label>
                                    <select wire:model="commission_type"
                                        class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                        <option value="none">Tidak Ada</option>
                                        <option value="fixed">Nominal Tetap (Rp)</option>
                                        <option value="percentage">Persentase (%)</option>
                                    </select>
                                    @error('commission_type')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Besaran
                                        Komisi</label>
                                    <input wire:model="commission_amount" type="number" step="0.01"
                                        min="0"
                                        class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base bg-gray-50 focus:bg-white transition-colors">
                                    @error('commission_amount')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar</label>
                                <div class="mt-1 flex items-center space-x-4 p-3 border rounded-xl bg-gray-50">
                                    @if ($image)
                                        <img src="{{ $image->temporaryUrl() }}"
                                            class="h-16 w-16 object-cover rounded-lg shadow-sm">
                                    @elseif($existingImage)
                                        <img src="{{ $existingImage }}"
                                            class="h-16 w-16 object-cover rounded-lg shadow-sm">
                                    @endif
                                    <input wire:model="image" type="file" accept="image/*"
                                        class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                </div>
                                @error('image')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi <span
                                        class="text-gray-400 text-xs">(Opsional)</span></label>
                                <textarea wire:model="description" rows="2" placeholder="Keterangan tambahan..."
                                    class="block w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-base resize-none bg-gray-50 focus:bg-white transition-colors"></textarea>
                                @error('description')
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
                        <button wire:click="closeAnimalModal" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all hover:bg-gray-100">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Order Detail Modal -->
    <div x-data="{ show: $wire.entangle('isOrderModalOpen') }" x-show="show" x-transition:enter="transition ease-out duration-300"
        class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="show = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full border border-gray-100">
                @if ($selectedOrder)
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Detail Pesanan</h3>
                            <button type="button" wire:click="closeOrderModal"
                                class="text-gray-400 hover:text-gray-500"><i
                                    class="fa-solid fa-xmark text-xl"></i></button>
                        </div>

                        <div class="space-y-4">
                            <div
                                class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex justify-between items-center">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-bold">ID Transaksi</p>
                                    <p class="text-lg font-mono font-bold text-gray-800">
                                        {{ $selectedOrder->transaction_id }}</p>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-lg text-sm font-bold {{ $selectedOrder->status == 'paid' ? 'bg-green-100 text-green-700' : ($selectedOrder->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($selectedOrder->status) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500">Donatur</p>
                                    <p class="font-medium">{{ $selectedOrder->donor_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $selectedOrder->whatsapp }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Atas Nama</p>
                                    <p class="font-medium">{{ $selectedOrder->qurban_name }}</p>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-4">
                                <div class="flex items-center mb-2">
                                    <div
                                        class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-3">
                                        <i class="fa-solid fa-cow"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $selectedOrder->animal->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">Tahun {{ $selectedOrder->hijri_year }} H</p>
                                    </div>
                                </div>
                                <div class="ml-11 text-sm text-gray-600 space-y-1">
                                    <p>Sembelih: <span
                                            class="font-medium text-gray-900">{{ ucfirst($selectedOrder->slaughter_method) }}</span>
                                    </p>
                                    <p>Pengiriman: <span
                                            class="font-medium text-gray-900">{{ ucfirst($selectedOrder->delivery_method) }}</span>
                                    </p>
                                    @if ($selectedOrder->address)
                                        <p class="bg-gray-50 p-2 rounded text-xs mt-1">{{ $selectedOrder->address }},
                                            {{ $selectedOrder->city }} {{ $selectedOrder->postal_code }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Info Pembayaran --}}
                            <div class="border-t border-gray-100 pt-4">
                                <h4 class="font-bold text-sm text-gray-900 mb-3">Informasi Pembayaran</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500">Metode Pembayaran</p>
                                        <p class="font-medium capitalize">
                                            {{ str_replace('_', ' ', $selectedOrder->payment_method ?? '-') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Tipe Pembayaran</p>
                                        <p class="font-medium capitalize">
                                            {{ str_replace('_', ' ', $selectedOrder->payment->payment_type ?? '-') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Status Pembayaran</p>
                                        <span
                                            class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($selectedOrder->payment->status ?? '') == 'paid' ? 'bg-green-100 text-green-700' : (($selectedOrder->payment->status ?? '') == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                            {{ ucfirst($selectedOrder->payment->status ?? '-') }}
                                        </span>
                                    </div>
                                    @if ($selectedOrder->payment && $selectedOrder->payment->paid_at)
                                        <div>
                                            <p class="text-xs text-gray-500">Dibayar Pada</p>
                                            <p class="font-medium text-sm">
                                                {{ $selectedOrder->payment->paid_at->format('d M Y H:i') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-4 flex justify-between items-center">
                                <span class="text-sm text-gray-600">Nominal Qurban</span>
                                <span class="font-semibold text-gray-900">Rp
                                    {{ number_format($selectedOrder->amount, 0, ',', '.') }}</span>
                            </div>

                            @if ($selectedOrder->payment && $selectedOrder->payment->unique_code > 0)
                                <div class="mt-2 flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Kode Unik</span>
                                    <span class="font-semibold text-gray-900">Rp
                                        {{ number_format($selectedOrder->payment->unique_code, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div
                                class="border-t border-dashed border-gray-200 mt-3 pt-3 flex justify-between items-center">
                                <span class="font-bold text-gray-700">Total Pembayaran</span>
                                <span class="text-xl font-bold text-primary">Rp
                                    {{ number_format($selectedOrder->amount + ($selectedOrder->payment?->unique_code ?? 0), 0, ',', '.') }}</span>
                            </div>

                            {{-- Dokumentasi Qurban --}}
                            <div class="border-t border-gray-100 pt-4">
                                <h4 class="font-bold text-sm text-gray-900 mb-3">Dokumentasi Qurban</h4>

                                @if ($selectedOrder->documentations->count() > 0)
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
                                        @foreach ($selectedOrder->documentations as $doc)
                                            <div
                                                class="relative group rounded-lg overflow-hidden bg-gray-100 aspect-video border border-gray-200">
                                                @if ($doc->file_type === 'photo')
                                                    <img src="{{ Storage::url($doc->file_path) }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <video src="{{ Storage::url($doc->file_path) }}"
                                                        class="w-full h-full object-cover"></video>
                                                    <div
                                                        class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                                        <i
                                                            class="fa-solid fa-play-circle text-white text-3xl opacity-80"></i>
                                                    </div>
                                                @endif

                                                <div
                                                    class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-10">
                                                    <button
                                                        wire:click="deleteDocumentation({{ $doc->id }}, 'order')"
                                                        wire:confirm="Hapus dokumentasi ini?"
                                                        class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 shadow-sm transform hover:scale-110 transition-all">
                                                        <i class="fa-solid fa-trash text-xs"></i>
                                                    </button>
                                                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                                                        class="ml-2 p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 shadow-sm transform hover:scale-110 transition-all">
                                                        <i class="fa-solid fa-expand text-xs"></i>
                                                    </a>
                                                </div>

                                                @if ($doc->caption)
                                                    <div
                                                        class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[10px] p-1.5 truncate">
                                                        {{ $doc->caption }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 mb-1">Upload
                                                Foto/Video</label>
                                            <input wire:model="docFiles" type="file" multiple
                                                accept="image/*,video/*"
                                                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                            @error('docFiles.*')
                                                <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 mb-1">Caption
                                                (Opsional)</label>
                                            <input wire:model="docCaption" type="text"
                                                class="block w-full rounded-lg border-gray-300 py-2 px-3 text-sm focus:ring-primary focus:border-primary"
                                                placeholder="Keterangan dokumentasi...">
                                            @error('docCaption')
                                                <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <button wire:click="saveOrderDocumentation" wire:loading.attr="disabled"
                                            class="w-full py-2.5 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-primary-hover disabled:opacity-50 transition-colors shadow-sm shadow-primary/30">
                                            <span wire:loading.remove
                                                wire:target="saveOrderDocumentation, docFiles">Upload
                                                Dokumentasi</span>
                                            <span wire:loading wire:target="saveOrderDocumentation, docFiles"><i
                                                    class="fa-solid fa-circle-notch fa-spin mr-1"></i>
                                                Mengupload...</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-100 rounded-b-2xl gap-2">
                        @if (
                            $selectedOrder->payment &&
                                $selectedOrder->payment->payment_type === 'bank_transfer' &&
                                $selectedOrder->status === 'pending')
                            <button wire:click="confirmOrderPayment({{ $selectedOrder->id }})"
                                wire:confirm="Apakah Anda yakin ingin mengkonfirmasi pembayaran ini sebagai PAID? Tindakan ini tidak dapat dibatalkan."
                                type="button"
                                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg shadow-green-500/30 px-5 py-2.5 bg-green-600 text-base font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto sm:text-sm transition-all hover:-translate-y-0.5">
                                <i class="fa-solid fa-check-circle mr-2"></i> Konfirmasi Pembayaran
                            </button>
                        @endif
                        <button wire:click="closeOrderModal" type="button"
                            class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:w-auto sm:text-sm transition-all">
                            Tutup
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Saving Detail Modal -->
    <div x-data="{ show: $wire.entangle('isSavingModalOpen') }" x-show="show" x-transition:enter="transition ease-out duration-300"
        class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="show = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full border border-gray-100">
                @if ($selectedSaving)
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Detail Tabungan</h3>
                            <button type="button" wire:click="closeSavingModal"
                                class="text-gray-400 hover:text-gray-500"><i
                                    class="fa-solid fa-xmark text-xl"></i></button>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-lg font-bold">{{ $selectedSaving->donor_name }}</p>
                                    <p class="text-sm text-gray-500">Target:
                                        {{ ucfirst(str_replace('-', ' ', $selectedSaving->target_animal_type)) }}
                                        ({{ $selectedSaving->target_hijri_year }} H)</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Atas Nama</p>
                                    <p class="font-medium">{{ $selectedSaving->qurban_name }}</p>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Terkumpul</span>
                                    <span class="font-bold text-primary">Rp
                                        {{ number_format($selectedSaving->saved_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                                    <div class="bg-primary h-2.5 rounded-full transition-all duration-500"
                                        style="width: {{ $selectedSaving->progress }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>{{ $selectedSaving->progress }}%</span>
                                    <span>Target: Rp
                                        {{ number_format($selectedSaving->target_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-bold text-sm text-gray-900 mb-3">Riwayat Setoran</h4>
                                <div class="max-h-64 overflow-y-auto border border-gray-100 rounded-xl">
                                    <table class="min-w-full divide-y divide-gray-100">
                                        <thead class="bg-gray-50 sticky top-0">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">
                                                    Tanggal</th>
                                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">
                                                    Jumlah</th>
                                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">
                                                    Metode</th>
                                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">
                                                    Status</th>
                                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach ($selectedSaving->deposits as $deposit)
                                                <tr>
                                                    <td class="px-4 py-2.5 text-xs text-gray-500">
                                                        {{ $deposit->created_at->format('d M Y') }}</td>
                                                    <td class="px-4 py-2.5 text-xs font-medium">Rp
                                                        {{ number_format($deposit->amount + ($deposit->payment?->unique_code ?? 0), 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-4 py-2.5 text-xs text-gray-600 capitalize">
                                                        {{ str_replace('_', ' ', $deposit->payment_method ?? '-') }}
                                                    </td>
                                                    <td class="px-4 py-2.5 text-xs">
                                                        <span
                                                            class="px-1.5 py-0.5 rounded-md text-[10px] uppercase font-bold {{ $deposit->status == 'paid' ? 'bg-green-100 text-green-700' : ($deposit->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">{{ $deposit->status }}</span>
                                                    </td>
                                                    <td class="px-4 py-2.5 text-xs text-right">
                                                        @if ($deposit->status === 'pending' && $deposit->payment && $deposit->payment->payment_type === 'bank_transfer')
                                                            <button
                                                                wire:click="confirmDepositPayment({{ $deposit->id }})"
                                                                wire:confirm="Apakah Anda yakin ingin mengkonfirmasi setoran ini sebagai PAID? Tindakan ini tidak dapat dibatalkan."
                                                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-green-600 text-white hover:bg-green-700 transition-colors shadow-sm">
                                                                <i class="fa-solid fa-check mr-1"></i> Konfirmasi
                                                            </button>
                                                        @elseif($deposit->status === 'paid')
                                                            <span class="text-green-600 text-[11px] font-medium"><i
                                                                    class="fa-solid fa-check-circle mr-1"></i>Terkonfirmasi</span>
                                                        @else
                                                            <span class="text-gray-400 text-[11px]">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="border-t border-gray-100 pt-4">
                                    <h4 class="font-bold text-sm text-gray-900 mb-3">Dokumentasi Qurban</h4>

                                    @if ($selectedSaving->documentations->count() > 0)
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
                                            @foreach ($selectedSaving->documentations as $doc)
                                                <div
                                                    class="relative group rounded-lg overflow-hidden bg-gray-100 aspect-video border border-gray-200">
                                                    @if ($doc->file_type === 'photo')
                                                        <img src="{{ Storage::url($doc->file_path) }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <video src="{{ Storage::url($doc->file_path) }}"
                                                            class="w-full h-full object-cover"></video>
                                                        <div
                                                            class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                                            <i
                                                                class="fa-solid fa-play-circle text-white text-3xl opacity-80"></i>
                                                        </div>
                                                    @endif

                                                    <div
                                                        class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-10">
                                                        <button
                                                            wire:click="deleteDocumentation({{ $doc->id }}, 'saving')"
                                                            wire:confirm="Hapus dokumentasi ini?"
                                                            class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 shadow-sm transform hover:scale-110 transition-all">
                                                            <i class="fa-solid fa-trash text-xs"></i>
                                                        </button>
                                                        <a href="{{ Storage::url($doc->file_path) }}"
                                                            target="_blank"
                                                            class="ml-2 p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 shadow-sm transform hover:scale-110 transition-all">
                                                            <i class="fa-solid fa-expand text-xs"></i>
                                                        </a>
                                                    </div>

                                                    @if ($doc->caption)
                                                        <div
                                                            class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[10px] p-1.5 truncate">
                                                            {{ $doc->caption }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-700 mb-1">Upload
                                                    Foto/Video</label>
                                                <input wire:model="docFiles" type="file" multiple
                                                    class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                                @error('docFiles.*')
                                                    <span
                                                        class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-700 mb-1">Caption
                                                    (Opsional)</label>
                                                <input wire:model="docCaption" type="text"
                                                    class="block w-full rounded-lg border-gray-300 py-2 px-3 text-sm focus:ring-primary focus:border-primary"
                                                    placeholder="Keterangan dokumentasi...">
                                                @error('docCaption')
                                                    <span
                                                        class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <button wire:click="saveSavingDocumentation" wire:loading.attr="disabled"
                                                class="w-full py-2.5 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-primary-hover disabled:opacity-50 transition-colors shadow-sm shadow-primary/30">
                                                <span wire:loading.remove
                                                    wire:target="saveSavingDocumentation, docFiles">Upload
                                                    Dokumentasi</span>
                                                <span wire:loading wire:target="saveSavingDocumentation, docFiles"><i
                                                        class="fa-solid fa-circle-notch fa-spin mr-1"></i>
                                                    Mengupload...</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-100 rounded-b-2xl">
                            <button wire:click="closeSavingModal" type="button"
                                class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm transition-all">
                                Tutup
                            </button>
                        </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div x-data="{ show: $wire.entangle('isExportModalOpen').live }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>

        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                @click="show = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                            <i class="fa-solid fa-file-excel text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Export {{ $activeTab === 'orders' ? 'Pesanan' : 'Tabungan' }} Qurban
                            </h3>
                            <p class="text-sm text-gray-500 mt-1 mb-5">
                                Pilih filter dan rentang tanggal untuk export data.
                            </p>

                            <div class="space-y-4">
                                {{-- Filter Jenis Hewan (Orders) / Target Hewan (Savings) --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $activeTab === 'orders' ? 'Jenis Hewan' : 'Target Hewan' }}
                                    </label>
                                    <select wire:model="exportAnimalTypeFilter"
                                        class="block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring focus:ring-green-500/20 py-2.5 px-3 text-sm">
                                        <option value="">— Semua Jenis —</option>
                                        @if ($activeTab === 'orders')
                                            <option value="langsung">Qurban Langsung</option>
                                            <option value="tabungan">Qurban Tabungan</option>
                                        @else
                                            <option value="kambing">Kambing</option>
                                            <option value="domba">Domba</option>
                                            <option value="sapi">Sapi</option>
                                            <option value="kerbau">Kerbau</option>
                                        @endif
                                    </select>
                                    <p
                                        class="text-xs mt-1 {{ $exportAnimalTypeFilter ? 'text-green-600' : 'text-gray-400' }} flex items-center gap-1">
                                        <i
                                            class="fa-solid {{ $exportAnimalTypeFilter ? 'fa-circle-check' : 'fa-circle-info' }}"></i>
                                        {{ $exportAnimalTypeFilter ? 'Hanya jenis yang dipilih yang akan diexport' : 'Semua jenis akan diexport' }}
                                    </p>
                                </div>

                                {{-- Tanggal Mulai --}}
                                <div>
                                    <label for="startDate"
                                        class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                    <input wire:model="startDate" type="date" id="startDate"
                                        class="block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring focus:ring-green-500/20 py-2.5 px-3 text-sm">
                                    @error('startDate')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Tanggal Selesai --}}
                                <div>
                                    <label for="endDate" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                        Selesai</label>
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
                        class="inline-flex items-center justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-green-600 text-sm font-medium text-white hover:bg-green-700 focus:outline-none transition-all">
                        <i class="fa-solid fa-file-excel mr-2"></i> Export Excel
                    </button>
                    <button wire:click="closeExportModal" type="button"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-all">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('title', 'Donasi Masuk')
@section('header', 'Riwayat Transaksi')

<div class="space-y-6">
    <div class="bg-white overflow-hidden shadow-soft rounded-2xl border border-gray-100">
        <div class="p-6">
            <!-- Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Hari Ini -->
                <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Hari Ini</p>
                            <h3 class="text-lg font-bold text-gray-900">Rp {{ number_format($statToday, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-green-50 text-green-500 flex items-center justify-center">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                    </div>
                </div>

                <!-- Kemarin -->
                <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kemarin</p>
                            <h3 class="text-lg font-bold text-gray-900">Rp
                                {{ number_format($statYesterday, 0, ',', '.') }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                            <i class="fa-solid fa-calendar-minus"></i>
                        </div>
                    </div>
                </div>

                <!-- Bulan Ini -->
                <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Bulan Ini</p>
                            <h3 class="text-lg font-bold text-gray-900">Rp
                                {{ number_format($statThisMonth, 0, ',', '.') }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                            <i class="fa-solid fa-calendar"></i>
                        </div>
                    </div>
                </div>

                <!-- Bulan Lalu -->
                <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Bulan Lalu</p>
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

            <!-- Top Controls -->
            <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 mb-6 space-y-4">
                <!-- Baris Pertama: Filter Utama -->
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-1 group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i
                                class="fa-solid fa-search text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            placeholder="Cari donatur, email, ID..."
                            class="pl-10 w-full rounded-xl border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm transition-all shadow-sm">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        <select wire:model.live="programFilter"
                            class="rounded-xl border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm shadow-sm md:w-64 truncate">
                            <option value="">Semua Program Donasi</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->title }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="statusFilter"
                            class="rounded-xl border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="paid">Berhasil (Paid)</option>
                            <option value="pending">Pending</option>
                            <option value="expired">Expired</option>
                            <option value="failed">Gagal</option>
                        </select>
                    </div>
                </div>

                <!-- Baris Kedua: Tanggal & Export/Aksi -->
                <div
                    class="flex flex-col md:flex-row justify-between items-center gap-4 pt-4 border-t border-gray-200/60">
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                        <span class="text-sm text-gray-500 font-medium hidden sm:block">Rentang:</span>
                        <div class="grid grid-cols-2 sm:flex items-center gap-2 w-full sm:w-auto">
                            <input wire:model.live="dateFrom" type="date"
                                class="w-full sm:w-auto rounded-xl border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 py-2 px-3 text-sm shadow-sm"
                                title="Tanggal Mulai">
                            <span class="text-gray-400 text-sm hidden sm:block">s/d</span>
                            <input wire:model.live="dateTo" type="date"
                                class="w-full sm:w-auto rounded-xl border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 py-2 px-3 text-sm shadow-sm"
                                title="Tanggal Selesai">
                        </div>

                        @if ($search || $statusFilter || $programFilter || $dateFrom || $dateTo)
                            <button wire:click="resetFilters"
                                class="text-xs font-semibold text-gray-500 hover:text-red-500 underline transition-colors w-full sm:w-auto mt-2 sm:mt-0 text-center sm:text-left">
                                Reset Filter
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                        <div class="flex items-center gap-2 text-sm text-gray-500 mr-2">
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
                            class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-xl inline-flex items-center transition-all shadow-sm shadow-green-600/20 text-sm whitespace-nowrap">
                            <i class="fa-solid fa-file-excel mr-2"></i> Export
                        </button>
                    </div>
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
                                    ID Transaksi</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Donatur</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Program</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    FU</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th scope="col"
                                    class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-orange-50/30 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                        {{ $payment->external_id }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $payment->customer_name ?? 'Hamba Allah' }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $payment->customer_email ?? $payment->customer_phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="line-clamp-2"
                                            title="{{ $payment->program->title ?? 'Program Tidak Ditemukan' }}">
                                            {{ $payment->program->title ?? 'Program Tidak Ditemukan' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                                        Rp {{ number_format($payment->amount + $payment->unique_code, 0, ',', '.') }}
                                        @if ($payment->unique_code > 0)
                                            <div class="text-[10px] font-normal text-gray-500 mt-1"
                                                title="Terdapat kode unik: Rp {{ number_format($payment->unique_code, 0, ',', '.') }}">
                                                Inc. Kode Unik
                                            </div>
                                        @endif
                                        @if (
                                            $payment->transaction_type === 'program' &&
                                                $payment->program &&
                                                $payment->program->is_dynamic &&
                                                $payment->donation)
                                            <div
                                                class="text-[11px] font-medium text-primary mt-1 bg-primary/5 border border-primary/10 inline-block px-2 py-0.5 rounded">
                                                {{ $payment->donation->package_quantity }}
                                                {{ $payment->program->package_label }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($payment->status == 'paid')
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                        @elseif($payment->status == 'pending')
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @elseif($payment->status == 'expired')
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Expired</span>
                                        @else
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-1">
                                            @php
                                                // Determine the correct key for $followups array based on transaction_type
                                                $followupType = match ($payment->transaction_type) {
                                                    'program' => 'donasi',
                                                    'qurban_langsung' => 'qurban',
                                                    'qurban_tabungan' => 'tabungan_qurban',
                                                    default => 'donasi',
                                                };

                                                // Get templates for this type from the passed $followups collection
                                                // Note: $followups is grouped by 'type', so we access it via the key
                                                $typeTemplates = $followups[$followupType] ?? collect();
                                            @endphp

                                            @foreach (['FollowUp1' => 1, 'FollowUp2' => 2, 'FollowUp3' => 3, 'FollowUp4' => 4] as $seqKey => $seqIndex)
                                                @php
                                                    $hasTemplate = $typeTemplates
                                                        ->where('followup_sequence', $seqKey)
                                                        ->first();
                                                    // Check if a message log exists for this payment and sequence
                                                    $isSent = $payment->whatsappMessageLogs
                                                        ->where('event_type', 'followup_' . $seqKey)
                                                        ->where('status', 'sent')
                                                        ->isNotEmpty();
                                                    $isFailed = $payment->whatsappMessageLogs
                                                        ->where('event_type', 'followup_' . $seqKey)
                                                        ->where('status', 'failed')
                                                        ->isNotEmpty();

                                                    // Determine button color
                                                    $btnClass = 'bg-gray-100 text-gray-500';
                                                    if ($isSent) {
                                                        $btnClass = 'bg-blue-600 text-white';
                                                    } elseif ($isFailed) {
                                                        $btnClass = 'bg-red-100 text-red-600 border border-red-200';
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
                                                            '{{ nama }}',
                                                            $payment->customer_name ?? 'Hamba Allah',
                                                            $rawContent,
                                                        );
                                                        $rawContent = str_replace(
                                                            '{{ tanggal }}',
                                                            $payment->created_at->translatedFormat('d F Y'),
                                                            $rawContent,
                                                        );

                                                        // Specific Replacements
                                                        if (
                                                            $payment->transaction_type == 'program' &&
                                                            $payment->program
                                                        ) {
                                                            $rawContent = str_replace(
                                                                '{{ program }}',
                                                                $payment->program->title,
                                                                $rawContent,
                                                            );
                                                            $rawContent = str_replace(
                                                                '{{ nilai_donasi }}',
                                                                'Rp ' . number_format($payment->amount, 0, ',', '.'),
                                                                $rawContent,
                                                            );
                                                            $rawContent = str_replace(
                                                                '{{ link_donasi }}',
                                                                route('program.detail', $payment->program->slug),
                                                                $rawContent,
                                                            );
                                                            $rawContent = str_replace(
                                                                '{{ link_pembayaran }}',
                                                                route('payment.status', [
                                                                    'id' => $payment->external_id,
                                                                ]),
                                                                $rawContent,
                                                            );
                                                        } elseif (
                                                            $payment->transaction_type == 'qurban_langsung' &&
                                                            $payment->qurbanOrder
                                                        ) {
                                                            $rawContent = str_replace(
                                                                '{{ jenis_hewan }}',
                                                                $payment->qurbanOrder->animal->name ?? '-',
                                                                $rawContent,
                                                            );
                                                            $rawContent = str_replace(
                                                                '{{ tipe_qurban }}',
                                                                $payment->qurbanOrder->animal->type ?? '-',
                                                                $rawContent,
                                                            );
                                                            $rawContent = str_replace(
                                                                '{{ harga }}',
                                                                'Rp ' . number_format($payment->amount, 0, ',', '.'),
                                                                $rawContent,
                                                            );
                                                            $rawContent = str_replace(
                                                                '{{ link_pembayaran }}',
                                                                route('payment.status', [
                                                                    'id' => $payment->external_id,
                                                                ]),
                                                                $rawContent,
                                                            );
                                                        } elseif (
                                                            $payment->transaction_type == 'qurban_tabungan' &&
                                                            $payment->qurbanSaving
                                                        ) {
                                                            $rawContent = str_replace(
                                                                '{{ target_tabungan }}',
                                                                'Rp ' .
                                                                    number_format(
                                                                        $payment->qurbanSaving->target_amount,
                                                                        0,
                                                                        ',',
                                                                        '.',
                                                                    ),
                                                                $rawContent,
                                                            );
                                                            $rawContent = str_replace(
                                                                '{{ saldo_saat_ini }}',
                                                                'Rp ' .
                                                                    number_format(
                                                                        $payment->qurbanSaving->saved_amount,
                                                                        0,
                                                                        ',',
                                                                        '.',
                                                                    ),
                                                                $rawContent,
                                                            );
                                                            $rawContent = str_replace(
                                                                '{{ sisa_pembayaran }}',
                                                                'Rp ' .
                                                                    number_format(
                                                                        $payment->qurbanSaving->target_amount -
                                                                            $payment->qurbanSaving->saved_amount,
                                                                        0,
                                                                        ',',
                                                                        '.',
                                                                    ),
                                                                $rawContent,
                                                            );
                                                            $rawContent = str_replace(
                                                                '{{ link_topup }}',
                                                                route(
                                                                    'qurban.savings.detail',
                                                                    $payment->qurbanSaving->id,
                                                                ),
                                                                $rawContent,
                                                            );
                                                            $rawContent = str_replace(
                                                                '{{ link_pembayaran }}',
                                                                route('payment.status', [
                                                                    'id' => $payment->external_id,
                                                                ]),
                                                                $rawContent,
                                                            );
                                                        }
                                                        $waLink = $this->getFollowupUrl($payment, $seqKey);
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
                                                                <i class="fa-solid fa-exclamation text-[7px]"></i>
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="showDetail({{ $payment->id }})"
                                            class="text-primary hover:text-primary-hover font-medium flex items-center justify-end gap-1 ml-auto">
                                            Detail <i class="fa-solid fa-chevron-right text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400 text-2xl">
                                                <i class="fa-solid fa-receipt"></i>
                                            </div>
                                            <p class="text-lg font-medium text-gray-900">Belum ada transaksi</p>
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
                {{ $payments->links() }}
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
                            <h3 class="text-lg font-semibold text-gray-900">Export Data Donasi</h3>
                            <p class="text-sm text-gray-500 mt-1 mb-5">
                                Pilih program dan rentang tanggal untuk export data donasi.
                            </p>

                            <div class="space-y-4">
                                {{-- Pilih Program --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Program Donasi
                                    </label>
                                    <select wire:model="exportProgramId"
                                        class="block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring focus:ring-green-500/20 py-2.5 px-3 text-sm">
                                        <option value="">— Semua Program —</option>
                                        @foreach ($programs as $program)
                                            <option value="{{ $program->id }}">{{ $program->title }}</option>
                                        @endforeach
                                    </select>
                                    @if ($exportProgramId)
                                        <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check"></i>
                                            Hanya donasi untuk program ini yang akan diexport
                                        </p>
                                    @else
                                        <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-info"></i>
                                            Semua program akan diexport
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

    <!-- Detail Modal -->
    <div x-data="{ show: $wire.entangle('isOpen') }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true" style="display: none;">

        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                @click="show = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                @if ($selectedPayment)
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900" id="modal-title">
                                Detail Transaksi
                            </h3>
                            <button type="button" wire:click="closeModal"
                                class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>

                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">

                                <div class="bg-gray-50 p-5 rounded-xl mb-6 border border-gray-100">
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div class="text-gray-500">ID Transaksi</div>
                                        <div class="font-medium text-right text-gray-900 font-mono">
                                            {{ $selectedPayment->external_id }}</div>

                                        <div class="text-gray-500">Status</div>
                                        <div class="font-medium text-right capitalize">
                                            <span
                                                class="px-2 py-0.5 rounded text-xs font-semibold {{ $selectedPayment->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-800' }}">
                                                {{ $selectedPayment->status }}
                                            </span>
                                        </div>

                                        <div class="text-gray-500">Metode Bayar</div>
                                        <div class="font-medium text-right uppercase">
                                            {{ str_replace('_', ' ', $selectedPayment->payment_method) }}</div>

                                        <div class="text-gray-500">Tanggal</div>
                                        <div class="font-medium text-right">
                                            {{ $selectedPayment->created_at->format('d M Y H:i') }}</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Data
                                            Donatur</h4>
                                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                            <div class="flex items-center mb-3">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary mr-3">
                                                    <i class="fa-solid fa-user text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">
                                                        {{ $selectedPayment->customer_name }}</p>
                                                    <p class="text-xs text-gray-500">Donatur</p>
                                                </div>
                                            </div>
                                            <div class="space-y-1 pl-11">
                                                <p class="text-sm text-gray-600 flex items-center"><i
                                                        class="fa-regular fa-envelope w-5 text-gray-400"></i>
                                                    {{ $selectedPayment->customer_email ?? '-' }}</p>
                                                <p class="text-sm text-gray-600 flex items-center"><i
                                                        class="fa-solid fa-phone w-5 text-gray-400"></i>
                                                    {{ $selectedPayment->customer_phone ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">
                                            Rincian Pembayaran</h4>
                                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                            <div class="space-y-2 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Nominal</span>
                                                    <span class="font-medium">Rp
                                                        {{ number_format($selectedPayment->amount, 0, ',', '.') }}</span>
                                                </div>
                                                @if ($selectedPayment->admin_fee > 0)
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Biaya Admin</span>
                                                        <span class="font-medium">Rp
                                                            {{ number_format($selectedPayment->admin_fee, 0, ',', '.') }}</span>
                                                    </div>
                                                @endif
                                                @if ($selectedPayment->unique_code > 0)
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Kode Unik</span>
                                                        <span class="font-medium">Rp
                                                            {{ number_format($selectedPayment->unique_code, 0, ',', '.') }}</span>
                                                    </div>
                                                @endif
                                                @if (
                                                    $selectedPayment->transaction_type === 'program' &&
                                                        $selectedPayment->program &&
                                                        $selectedPayment->program->is_dynamic &&
                                                        $selectedPayment->donation)
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Jumlah Paket</span>
                                                        <span class="font-medium text-primary">
                                                            {{ $selectedPayment->donation->package_quantity }}
                                                            {{ $selectedPayment->program->package_label }}
                                                        </span>
                                                    </div>
                                                @endif
                                                <div
                                                    class="border-t border-gray-100 pt-2 mt-2 flex justify-between items-center">
                                                    <span class="text-gray-900 font-bold">Total</span>
                                                    <span class="text-lg font-bold text-primary">Rp
                                                        {{ number_format($selectedPayment->total, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">
                                        Peruntukan Donasi</h4>
                                    @if ($selectedPayment->transaction_type == 'program' && $selectedPayment->program)
                                        <div
                                            class="p-4 border border-blue-100 rounded-xl bg-blue-50/50 flex items-start">
                                            <img src="{{ $selectedPayment->program->image }}"
                                                class="w-12 h-12 rounded-lg object-cover mr-4">
                                            <div>
                                                <p class="text-xs text-blue-600 uppercase font-bold mb-1">Program
                                                    Donasi</p>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $selectedPayment->program->title }}</p>
                                            </div>
                                        </div>
                                    @elseif($selectedPayment->transaction_type == 'qurban_langsung' && $selectedPayment->qurbanOrder)
                                        <div class="p-4 border border-green-100 rounded-xl bg-green-50/50">
                                            <div class="flex items-center mb-2">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-3">
                                                    <i class="fa-solid fa-cow"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-green-600 uppercase font-bold">Qurban</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $selectedPayment->qurbanOrder->animal->name ?? 'Hewan Qurban' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="ml-11 text-sm text-gray-600">
                                                <p>Tahun: <span
                                                        class="font-medium">{{ $selectedPayment->qurbanOrder->hijri_year }}
                                                        H</span></p>
                                                <p>Atas Nama: <span
                                                        class="font-medium">{{ $selectedPayment->qurbanOrder->qurban_name }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    @elseif($selectedPayment->transaction_type == 'qurban_tabungan' && $selectedPayment->qurbanSaving)
                                        <div class="p-4 border border-purple-100 rounded-xl bg-purple-50/50">
                                            <div class="flex items-center mb-2">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mr-3">
                                                    <i class="fa-solid fa-wallet"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-purple-600 uppercase font-bold">Tabungan
                                                        Qurban</p>
                                                    <p class="text-sm font-medium text-gray-900">Setoran Tabungan</p>
                                                </div>
                                            </div>
                                            <div class="ml-11 text-sm text-gray-600">
                                                <p>Target: <span
                                                        class="font-medium capitalize">{{ str_replace('-', ' ', $selectedPayment->qurbanSaving->target_animal_type) }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 italic">Informasi detail tidak tersedia</p>
                                    @endif
                                </div>

                                @if ($selectedPayment->status == 'paid' && $selectedPayment->paid_at)
                                    <div class="mt-6 text-center">
                                        <span
                                            class="text-xs text-green-600 bg-green-50 border border-green-100 px-4 py-2 rounded-full inline-flex items-center">
                                            <i class="fa-solid fa-check-circle mr-2"></i> Dibayar lunas pada
                                            {{ $selectedPayment->paid_at->format('d M Y H:i') }}
                                        </span>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-100 rounded-b-2xl">
                        <button wire:click="closeModal" type="button"
                            class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm transition-all">
                            Tutup
                        </button>

                        @if ($selectedPayment->payment_type == 'bank_transfer' && $selectedPayment->status == 'pending')
                            <button wire:click="confirmPayment({{ $selectedPayment->id }})"
                                wire:confirm="Apakah Anda yakin ingin mengkonfirmasi pembayaran ini sebagai PAID? Dana akan masuk ke program terkait."
                                type="button"
                                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-all mb-3 sm:mb-0">
                                <i class="fa-solid fa-check-circle mr-2 mt-0.5"></i> Konfirmasi Pembayaran
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

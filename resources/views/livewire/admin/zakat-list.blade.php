@section('title', 'Transaksi Zakat')
@section('header', 'Transaksi Zakat')

<div class="space-y-6">
    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-soft flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-moon text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Hari Ini</p>
                <p class="text-lg font-bold text-gray-900">Rp {{ number_format($statToday, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-soft flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-calendar text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Bulan Ini</p>
                <p class="text-lg font-bold text-gray-900">Rp {{ number_format($statThisMonth, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-soft flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-hand-holding-heart text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Terkumpul</p>
                <p class="text-lg font-bold text-gray-900">Rp {{ number_format($statTotal, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
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
        </div>

        @if (session()->has('success'))
            <div
                class="mx-4 mt-4 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3 text-green-700">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mx-4 mt-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 text-red-700">
                <i class="fa-solid fa-circle-xmark text-xl"></i>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">ID Transaksi</th>
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
</div>

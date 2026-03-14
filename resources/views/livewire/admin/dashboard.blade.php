@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

<div wire:poll.10s class="space-y-8">
    <!-- Welcome Banner -->
    <div
        class="bg-gradient-to-r from-primary to-secondary rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative">
            <h2 class="text-2xl font-bold mb-1">Assalamualaikum, {{ Auth::user()->name }}!</h2>
            <p class="text-white/90">Semoga harimu menyenangkan. Berikut adalah ringkasan aktivitas yayasan hari ini.</p>
            <div class="mt-4 inline-flex items-center bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1 text-sm">
                <i class="fa-regular fa-calendar-days mr-2"></i>
                {{ now()->format('l, d F Y') }}
            </div>
        </div>
        <div class="absolute right-0 top-0 h-full w-1/3 bg-white/10 skew-x-12 transform origin-bottom-left"></div>
        <div class="absolute right-10 bottom-0 text-9xl text-white/10">
            <i class="fa-solid fa-mosque"></i>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="space-y-6">
        <!-- Total Overall (Full Width) -->
        <div
            class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Dana Terkumpul (Keseluruhan)</p>
                    <h3 class="text-3xl font-bold text-gray-800 group-hover:text-primary transition-colors">
                        Rp {{ number_format($totalDonations, 0, ',', '.') }}
                    </h3>
                </div>
                <div
                    class="h-16 w-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-2xl group-hover:bg-primary group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-vault"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-400">
                <i class="fa-solid fa-clock-rotate-left text-primary mr-1"></i>
                <span>Akumulasi dari Program, Zakat, dan Qurban</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Program -->
            <div
                class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">Total Dana Program</p>
                        <h3 class="text-xl font-bold text-gray-800 group-hover:text-blue-500 transition-colors">
                            Rp {{ number_format($totalDanaProgram, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div
                        class="h-10 w-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-lg group-hover:bg-blue-500 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                    </div>
                </div>
            </div>

            <!-- Total Zakat -->
            <div
                class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">Total Dana Zakat</p>
                        <h3 class="text-xl font-bold text-gray-800 group-hover:text-emerald-500 transition-colors">
                            Rp {{ number_format($totalDanaZakat, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div
                        class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-lg group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-mosque"></i>
                    </div>
                </div>
            </div>

            <!-- Total Qurban -->
            <div
                class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">Total Dana Qurban</p>
                        <h3 class="text-xl font-bold text-gray-800 group-hover:text-orange-500 transition-colors">
                            Rp {{ number_format($totalDanaQurban, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div
                        class="h-10 w-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center text-lg group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-cow"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bar Chart Section -->
    <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">Transaksi Bulan Ini</h3>
            <span class="text-sm text-gray-500">{{ now()->format('F Y') }}</span>
        </div>

        <div class="relative h-64 w-full">
            @if (count($chartData) > 0)
                <div class="flex items-end justify-between h-full space-x-2">
                    @php
                        $maxTotal = max(array_column($chartData, 'total'));
                        $maxTotal = $maxTotal == 0 ? 1 : $maxTotal; // Prevent division by zero
                    @endphp
                    @foreach ($chartData as $data)
                        <div class="flex flex-col items-center flex-1 group relative h-full justify-end">
                            <!-- Tooltip -->
                            <div class="absolute bottom-full mb-2 hidden group-hover:block z-10 w-full text-center">
                                <div
                                    class="bg-gray-800 text-white text-[10px] rounded py-1 px-1 whitespace-nowrap mx-auto inline-block">
                                    Rp {{ number_format($data['total'], 0, ',', '.') }}
                                </div>
                            </div>

                            <!-- Bar -->
                            <div class="w-full bg-blue-50 rounded-t-sm relative overflow-hidden group-hover:bg-blue-100 transition-colors flex items-end"
                                style="height: 100%;">
                                <div class="w-full bg-primary transition-all duration-500 ease-out rounded-t-sm hover:bg-primary-hover"
                                    style="height: {{ $maxTotal > 0 ? ($data['total'] / $maxTotal) * 100 : 0 }}%;">
                                </div>
                            </div>

                            <!-- Label -->
                            <div class="mt-2 text-[9px] text-gray-400 rotate-0 sm:rotate-0 truncate w-full text-center">
                                {{ \Carbon\Carbon::createFromFormat('d M', $data['date'])->format('d') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex items-center justify-center h-full text-gray-400">
                    No transaction data for this month.
                </div>
            @endif
        </div>
    </div>

    <!-- Today's Transactions List -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">Transaksi Hari Ini</h3>
            <span class="text-sm font-medium text-gray-500">{{ now()->format('d F Y') }}</span>
        </div>
        <div class="p-0">
            @if ($todayTransactions->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach ($todayTransactions as $payment)
                        <div class="p-4 hover:bg-gray-50 transition-colors flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $payment->customer_name ?? 'Hamba Allah' }}
                                </p>
                                <p class="text-xs text-gray-500 truncate flex items-center">
                                    <span class="truncate max-w-[200px]">
                                        @if ($payment->transaction_type == 'program')
                                            {{ $payment->program->title ?? 'Donasi Program' }}
                                        @elseif($payment->transaction_type == 'qurban_langsung')
                                            Qurban: {{ $payment->qurbanOrder->animal->name ?? 'Hewan' }}
                                        @elseif($payment->transaction_type == 'qurban_tabungan')
                                            Tabungan Qurban
                                        @else
                                            {{ ucfirst($payment->transaction_type) }}
                                        @endif
                                    </span>
                                    <span class="mx-1">•</span>
                                    <span>{{ $payment->created_at->format('H:i') }}</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">
                                    Rp {{ number_format($payment->total, 0, ',', '.') }}
                                </p>
                                @php
                                    $status = $payment->status;
                                    $badgeClass = match ($status) {
                                        'paid', 'settled' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'failed', 'canceled' => 'bg-red-100 text-red-800',
                                        'expired' => 'bg-gray-100 text-gray-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                    $label = match ($status) {
                                        'paid', 'settled' => 'Berhasil',
                                        'pending' => 'Menunggu',
                                        'failed' => 'Gagal',
                                        'canceled' => 'Dibatalkan',
                                        'expired' => 'Kedaluwarsa',
                                        default => ucfirst($status),
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $badgeClass }}">
                                    {{ $label }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                        <i class="fa-solid fa-inbox text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-500">Belum ada transaksi hari ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

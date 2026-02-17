<div x-data="{ 
    showDetail: false, 
    selectedDonation: null,
    
    getBadgeColor(status) {
        if(status === 'success') return 'bg-green-50 text-green-600';
        if(status === 'pending') return 'bg-yellow-50 text-yellow-600';
        if(status === 'failed') return 'bg-red-50 text-red-600';
        return 'bg-gray-100 text-gray-600';
    },
    
    getStatusLabel(status) {
        if(status === 'success') return 'Berhasil';
        if(status === 'pending') return 'Pending';
        if(status === 'failed') return 'Gagal';
        return status;
    },

    openDetail(donation) {
        this.selectedDonation = donation;
        this.showDetail = true;
    }
}">
    <header id="header" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="px-4 py-3 flex items-center gap-3">
            <h1 class="text-base font-bold text-dark flex-1">Donasi Saya</h1>
            <button class="w-9 h-9 flex items-center justify-center bg-light rounded-full">
                <i class="fa-regular fa-bell text-dark text-lg"></i>
            </button>
        </div>
    </header>

    <!-- Filter Tabs -->
    <section id="filter-tabs" class="bg-white px-4 py-3 border-b border-gray-100 sticky top-[52px] z-40">
        <div class="flex gap-2 overflow-x-auto hide-scrollbar">
            @foreach(['semua', 'berhasil', 'pending', 'gagal'] as $status)
            <button wire:click="setFilter('{{ $status }}')"
                class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors
                {{ $filter === $status ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ ucfirst($status) }}
            </button>
            @endforeach
        </div>
    </section>

    <main id="main-content" class="pb-24">
        <section id="donation-list" class="px-4 py-4 space-y-3">
            @forelse($donations as $donation)
            <div @click="openDetail({
                    id: '{{ $donation->id }}',
                    title: '{{ $donation->program->title }}',
                    date: '{{ $donation->created_at->format('d F Y, H:i') }}',
                    status: '{{ $donation->status }}',
                    amount: 'Rp {{ number_format($donation->amount, 0, ',', '.') }}',
                    trx: '{{ $donation->transaction_id }}',
                    method: '{{ $donation->payment_method }}'
                 })"
                 class="bg-white rounded-xl border border-gray-100 p-4 cursor-pointer hover:shadow-sm transition-shadow">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm text-dark mb-1">{{ $donation->program->title }}</h4>
                        <p class="text-xs text-gray-500">{{ $donation->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @php
                        $badgeColor = match($donation->status) {
                            'success' => 'bg-green-50 text-green-600',
                            'pending' => 'bg-yellow-50 text-yellow-600',
                            'failed' => 'bg-red-50 text-red-600',
                            default => 'bg-gray-100 text-gray-600'
                        };
                        $statusLabel = match($donation->status) {
                            'success' => 'Berhasil',
                            'pending' => 'Pending',
                            'failed' => 'Gagal',
                            default => $donation->status
                        };
                    @endphp
                    <span class="px-2 py-1 text-xs font-semibold rounded capitalize {{ $badgeColor }}">
                        {{ $statusLabel }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-base font-bold text-dark">Rp {{ number_format($donation->amount, 0, ',', '.') }}</p>
                    <button class="text-primary text-sm font-medium flex items-center gap-1">
                        Lihat Detail <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="text-center py-10">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fa-regular fa-folder-open text-gray-400 text-2xl"></i>
                </div>
                <p class="text-sm text-gray-500">Belum ada donasi {{ $filter !== 'semua' ? $filter : '' }}</p>
            </div>
            @endforelse
        </section>
    </main>

    <x-bottom-nav active="donation" />

    <!-- Detail Modal -->
    <div x-show="showDetail" class="fixed inset-0 z-50 flex items-end justify-center" style="display: none;">
        <!-- Backdrop -->
        <div @click="showDetail = false" x-transition:enter="transition opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition opacity duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

        <!-- Sheet -->
        <div x-transition:enter="transition transform duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition transform duration-300" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="bg-white w-full max-w-[460px] rounded-t-3xl max-h-[90vh] overflow-hidden flex flex-col relative z-10">
            <div class="sticky top-0 bg-white border-b border-gray-100 px-4 py-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-dark">Detail Donasi</h3>
                <button @click="showDetail = false" class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full">
                    <i class="fa-solid fa-xmark text-gray-600 text-sm"></i>
                </button>
            </div>
            
            <div class="overflow-y-auto px-4 py-4 pb-24 flex-1" x-if="selectedDonation">
                <div class="rounded-xl p-4 mb-4 text-center" :class="getBadgeColor(selectedDonation.status).replace('text-', 'bg-').replace('50', '50')">
                    <i class="fa-solid text-4xl mb-2" 
                       :class="selectedDonation.status === 'success' ? 'fa-circle-check text-green-600' : (selectedDonation.status === 'pending' ? 'fa-clock text-yellow-600' : 'fa-circle-xmark text-red-600')"></i>
                    <p class="text-sm font-semibold capitalize" 
                       :class="selectedDonation.status === 'success' ? 'text-green-600' : (selectedDonation.status === 'pending' ? 'text-yellow-600' : 'text-red-600')"
                       x-text="'Donasi ' + getStatusLabel(selectedDonation.status)"></p>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">ID Transaksi</p>
                        <p class="text-sm font-semibold text-dark" x-text="selectedDonation.trx"></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nama Program</p>
                        <p class="text-sm font-semibold text-dark" x-text="selectedDonation.title"></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nominal Donasi</p>
                        <p class="text-xl font-bold text-primary" x-text="selectedDonation.amount"></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Metode Pembayaran</p>
                        <p class="text-sm font-semibold text-dark" x-text="selectedDonation.method"></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal & Waktu</p>
                        <p class="text-sm font-semibold text-dark" x-text="selectedDonation.date"></p>
                    </div>
                </div>
            </div>
            
            <div class="sticky bottom-0 bg-white border-t border-gray-100 px-4 py-3 flex gap-2">
                 <a href="{{ route('home') }}" wire:navigate class="flex-1 py-3 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-primary/90 text-center flex items-center justify-center">Donasi Lagi</a>
                 <button class="flex-1 py-3 bg-gray-100 text-dark rounded-xl text-sm font-semibold hover:bg-gray-200">Bagikan</button>
            </div>
        </div>
    </div>
</div>

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-primary to-secondary rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Donations -->
        <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Donasi Terkumpul</p>
                    <h3 class="text-2xl font-bold text-gray-800 group-hover:text-primary transition-colors">
                        Rp {{ number_format($totalDonations, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="h-12 w-12 rounded-xl bg-green-50 text-green-500 flex items-center justify-center text-xl group-hover:bg-green-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-400">
                <i class="fa-solid fa-arrow-up text-green-500 mr-1"></i>
                <span class="text-green-500 font-medium mr-1">Updated</span>
                <span>Just now</span>
            </div>
        </div>

        <!-- Active Programs -->
        <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Program Aktif</p>
                    <h3 class="text-2xl font-bold text-gray-800 group-hover:text-blue-500 transition-colors">
                        {{ $activePrograms }} <span class="text-sm font-normal text-gray-400">Kampanye</span>
                    </h3>
                </div>
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl group-hover:bg-blue-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-400">
                <span class="bg-blue-100 text-blue-600 px-2 py-0.5 rounded-md">Sedang berjalan</span>
            </div>
        </div>

        <!-- Total Donors -->
        <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Donatur</p>
                    <h3 class="text-2xl font-bold text-gray-800 group-hover:text-orange-500 transition-colors">
                        {{ $totalDonors }} <span class="text-sm font-normal text-gray-400">Orang</span>
                    </h3>
                </div>
                <div class="h-12 w-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center text-xl group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-400">
                <i class="fa-solid fa-user-plus text-orange-500 mr-1"></i>
                <span class="text-orange-500 font-medium mr-1">New</span>
                <span>Users registered</span>
            </div>
        </div>
    </div>

    <!-- Recent Donations & Top Programs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Donations -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">Donasi Terbaru</h3>
                <a href="{{ route('admin.donations') }}" class="text-sm font-medium text-primary hover:text-primary-hover transition-colors">Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i></a>
            </div>
            <div class="p-0">
                @if($recentDonations->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($recentDonations as $payment)
                        <div class="p-4 hover:bg-gray-50 transition-colors flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full border border-gray-200" src="https://ui-avatars.com/api/?name={{ urlencode($payment->customer_name ?? 'Hamba Allah') }}&background=random&color=fff" alt="">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $payment->customer_name ?? 'Hamba Allah' }}
                                </p>
                                <p class="text-xs text-gray-500 truncate flex items-center">
                                    <span class="truncate max-w-[150px]">{{ $payment->program->title ?? 'Program Dihapus' }}</span>
                                    <span class="mx-1">•</span>
                                    <span>{{ $payment->created_at->diffForHumans() }}</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    Paid
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
                        <p class="text-gray-500">Belum ada donasi terbaru.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Programs -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">Program Populer</h3>
                <a href="{{ route('admin.programs') }}" class="text-sm font-medium text-primary hover:text-primary-hover transition-colors">Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i></a>
            </div>
            <div class="p-6">
                @if($topPrograms->count() > 0)
                    <div class="space-y-6">
                        @foreach($topPrograms as $index => $program)
                        <div class="relative">
                            <div class="flex justify-between items-end mb-2">
                                <div class="flex items-center overflow-hidden">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-gray-100 text-gray-500 text-xs font-bold flex items-center justify-center mr-3">
                                        {{ ($topPrograms->currentPage() - 1) * $topPrograms->perPage() + $loop->iteration }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-700 truncate block">{{ $program->title }}</span>
                                </div>
                                <span class="text-sm font-bold text-gray-900 ml-2 whitespace-nowrap">Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="relative pt-1">
                                <div class="flex mb-2 items-center justify-between">
                                    <div></div> <!-- spacer -->
                                    <div class="text-right">
                                        <span class="text-xs font-semibold inline-block text-primary">
                                            {{ $program->target_amount > 0 ? round(($program->collected_amount / $program->target_amount) * 100) : 0 }}%
                                        </span>
                                    </div>
                                </div>
                                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded-full bg-gray-100">
                                    @php
                                        $percentage = $program->target_amount > 0 ? ($program->collected_amount / $program->target_amount) * 100 : 0;
                                    @endphp
                                    <div style="width:{{ min($percentage, 100) }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-primary to-secondary rounded-full transition-all duration-500"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $topPrograms->links() }}
                    </div>
                @else
                    <div class="p-8 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                            <i class="fa-solid fa-chart-bar text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-gray-500">Belum ada data program.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

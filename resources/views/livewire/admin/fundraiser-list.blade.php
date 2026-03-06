@section('title', 'Manajemen Fundriser')
@section('header', 'Data Pendaftar Fundriser')

<div>
<div class="space-y-6">
    <div class="bg-white overflow-hidden shadow-soft rounded-2xl border border-gray-100">
        <!-- Modern Tabs -->
        <div class="border-b border-gray-100">
            <nav class="flex space-x-1 px-4 pt-2" aria-label="Tabs">
                <button wire:click="setTab('fundraisers')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2
                    {{ $activeTab === 'fundraisers' ? 'text-primary border-primary bg-primary/5' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-users mr-2"></i> Pendaftar
                </button>
                <button wire:click="setTab('withdrawals')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2
                    {{ $activeTab === 'withdrawals' ? 'text-primary border-primary bg-primary/5' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-money-bill-transfer mr-2"></i> Pencairan
                </button>
                <button wire:click="setTab('commissions')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2
                    {{ $activeTab === 'commissions' ? 'text-primary border-primary bg-primary/5' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-receipt mr-2"></i> Riwayat Komisi
                </button>
                <button wire:click="setTab('settings')"
                    class="px-6 py-4 text-sm font-medium rounded-t-xl transition-all duration-200 border-b-2
                    {{ $activeTab === 'settings' ? 'text-primary border-primary bg-primary/5' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-cog mr-2"></i> Pengaturan Ujroh
                </button>
            </nav>
        </div>

        <div class="p-6">
            @if($activeTab !== 'settings')
                <!-- Controls -->
                <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 mb-6">
                    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <div class="relative flex-1 sm:w-64">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-search text-gray-400"></i>
                                </div>
                                <input type="text" wire:model.live.debounce.300ms="search" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-primary focus:border-primary bg-white shadow-sm" placeholder="Cari data...">
                            </div>

                            <select wire:model.live="statusFilter" class="block w-32 py-2 pl-3 pr-8 border border-gray-200 bg-white rounded-xl text-sm focus:ring-primary focus:border-primary shadow-sm">
                                <option value="">Semua Status</option>
                                @if($activeTab === 'withdrawals')
                                    <option value="pending">Proses</option>
                                    <option value="approved">Berhasil</option>
                                    <option value="rejected">Ditolak</option>
                                @elseif($activeTab === 'commissions')
                                    <option value="pending">Pending</option>
                                    <option value="success">Success</option>
                                    <option value="cancelled">Cancelled</option>
                                @else
                                    <option value="pending">Menunggu</option>
                                    <option value="approved">Disetujui</option>
                                    <option value="rejected">Ditolak</option>
                                @endif
                            </select>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <span class="text-sm text-gray-500 font-medium">Tampil:</span>
                            <select wire:model.live="perPage" class="block w-full sm:w-20 py-2 pl-3 pr-8 border border-gray-200 bg-white rounded-xl text-sm focus:ring-primary focus:border-primary shadow-sm">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-hidden rounded-xl border border-gray-100">
                    <div class="overflow-x-auto">
                        @if($activeTab === 'fundraisers')
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50/80">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pendaftar</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Saldo Aktif</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse ($data as $fundraiser)
                                        <tr class="hover:bg-orange-50/30 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
                                                            {{ substr($fundraiser->name, 0, 2) }}
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-semibold text-gray-900">{{ $fundraiser->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $fundraiser->domicile }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $fundraiser->whatsapp }}</div>
                                                <div class="text-xs text-gray-500">{{ $fundraiser->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($fundraiser->status === 'approved')
                                                    <div class="text-sm font-bold text-primary">Rp {{ number_format($fundraiser->available_balance, 0, ',', '.') }}</div>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($fundraiser->status === 'pending')
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        <i class="fa-solid fa-hourglass-half mr-1 mt-0.5"></i> Menunggu
                                                    </span>
                                                @elseif ($fundraiser->status === 'approved')
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        <i class="fa-solid fa-check mr-1 mt-0.5"></i> Disetujui
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        <i class="fa-solid fa-xmark mr-1 mt-0.5"></i> Ditolak
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end gap-2">
                                                    @if ($fundraiser->status === 'pending')
                                                        <button wire:click="approve({{ $fundraiser->id }})" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-colors flex items-center justify-center" title="Setujui">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                    @endif

                                                    <button wire:click="showDetail({{ $fundraiser->id }})" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors flex items-center justify-center" title="Detail">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>

                                                    <button wire:click="delete({{ $fundraiser->id }})" onclick="return confirm('Yakin ingin menghapus data pendaftar ini?') || event.stopImmediatePropagation()" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-colors flex items-center justify-center" title="Hapus">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">Tidak ada data pendaftar fundriser.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @elseif($activeTab === 'withdrawals')
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50/80">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fundriser</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nominal</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rekening Tujuan</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse ($data as $wd)
                                        <tr class="hover:bg-orange-50/30 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $wd->created_at->format('d M Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $wd->created_at->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-gray-900">{{ $wd->fundraiser->name ?? '-' }}</div>
                                                <div class="text-xs text-gray-500">{{ $wd->fundraiser->whatsapp ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-primary">Rp {{ number_format($wd->amount, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $wd->bank_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $wd->account_number }} a.n {{ $wd->account_name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($wd->status === 'pending')
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                        <i class="fa-solid fa-clock-rotate-left mr-1 mt-0.5"></i> Proses
                                                    </span>
                                                @elseif ($wd->status === 'approved')
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                                        <i class="fa-solid fa-check mr-1 mt-0.5"></i> Berhasil
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        <i class="fa-solid fa-xmark mr-1 mt-0.5"></i> Ditolak
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button wire:click="showWithdrawalDetail({{ $wd->id }})" class="text-primary hover:text-primary-hover font-medium flex items-center justify-end gap-1 ml-auto">
                                                    Detail <i class="fa-solid fa-chevron-right text-xs"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada riwayat pencairan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @elseif($activeTab === 'commissions')
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50/80">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fundriser</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Komisi Didapat</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Saldo Aktif Saat Ini</th>
                                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse ($data as $fundraiser)
                                        <tr class="hover:bg-orange-50/30 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-gray-900">{{ $fundraiser->name }}</div>
                                                <div class="text-xs text-gray-500">Akun: {{ $fundraiser->user->name ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $fundraiser->whatsapp }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-emerald-600">+Rp {{ number_format($fundraiser->total_commission ?? 0, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-primary">Rp {{ number_format($fundraiser->available_balance, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button wire:click="showCommissionDetail({{ $fundraiser->id }})" class="text-primary hover:text-primary-hover font-medium flex items-center justify-end gap-1 ml-auto bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">
                                                    Lihat Rincian <i class="fa-solid fa-chevron-right text-xs"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">Tidak ada data komisi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                @if ($data && $data->hasPages())
                    <div class="mt-6">
                        {{ $data->links() }}
                    </div>
                @endif
            @endif

            @if($activeTab === 'settings')
                <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                    <form wire:submit.prevent="saveSettings">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Program Ujroh -->
                            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                        <i class="fa-solid fa-hand-holding-heart"></i>
                                    </div>
                                    Ujroh Program Reguler
                                </h3>
                                <p class="text-sm text-gray-500 mb-4">Pengaturan ini akan diterapkan secara global untuk SEMUA program donasi aktif.</p>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Ujroh</label>
                                        <select wire:model.live="program_commission_type" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4">
                                            <option value="none">Tidak Ada Ujroh</option>
                                            <option value="fixed">Nominal Tetap (Rp)</option>
                                            <option value="percentage">Persentase (%)</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Besaran Ujroh</label>
                                        <div class="relative">
                                            @if($program_commission_type === 'fixed')
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                                </div>
                                            @endif
                                            <input wire:model="program_commission_amount" type="number" step="0.01" min="0" 
                                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4 {{ $program_commission_type === 'none' ? 'bg-gray-100 cursor-not-allowed opacity-50' : '' }} {{ $program_commission_type === 'fixed' ? 'pl-10' : '' }}"
                                                {{ $program_commission_type === 'none' ? 'disabled' : '' }}>
                                            @if($program_commission_type === 'percentage')
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">%</span>
                                                </div>
                                            @endif
                                        </div>
                                        @error('program_commission_amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Qurban Ujroh -->
                            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                        <i class="fa-solid fa-cow"></i>
                                    </div>
                                    Ujroh Qurban
                                </h3>
                                <p class="text-sm text-gray-500 mb-4">Pengaturan ini akan diterapkan secara global untuk SEMUA transaksi Qurban.</p>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Ujroh</label>
                                        <select wire:model.live="qurban_commission_type" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4">
                                            <option value="none">Tidak Ada Ujroh</option>
                                            <option value="fixed">Nominal Tetap (Rp)</option>
                                            <option value="percentage">Persentase (%)</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Besaran Ujroh</label>
                                        <div class="relative">
                                            @if($qurban_commission_type === 'fixed')
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                                </div>
                                            @endif
                                            <input wire:model="qurban_commission_amount" type="number" step="0.01" min="0" 
                                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-shadow py-2.5 px-4 {{ $qurban_commission_type === 'none' ? 'bg-gray-100 cursor-not-allowed opacity-50' : '' }} {{ $qurban_commission_type === 'fixed' ? 'pl-10' : '' }}"
                                                {{ $qurban_commission_type === 'none' ? 'disabled' : '' }}>
                                            @if($qurban_commission_type === 'percentage')
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">%</span>
                                                </div>
                                            @endif
                                        </div>
                                        @error('qurban_commission_amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end border-t border-gray-200 pt-6">
                            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-bold rounded-xl shadow-sm hover:bg-primary-hover transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-save"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Fundraiser -->
<div x-data="{ show: @entangle('isOpen') }"
     x-show="show"
     class="fixed inset-0 z-[100] overflow-y-auto"
     style="display: none;"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" aria-hidden="true" @click="show = false"></div>

        <div class="relative inline-block w-full max-w-2xl overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl sm:my-8"
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-user-circle text-primary"></i>
                    Detail Pendaftar Fundriser
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            @if($fundraiserDetail)
            <div class="px-6 py-6 overflow-y-auto max-h-[calc(100vh-200px)]">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">Nama Lengkap</h4>
                            <p class="text-base font-medium text-gray-900">{{ $fundraiserDetail->name }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">Akun Terkait</h4>
                            <p class="text-base font-medium text-gray-900">{{ $fundraiserDetail->user->name ?? '-' }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">Nomor WhatsApp</h4>
                            <p class="text-base font-medium text-gray-900">
                                @php
                                    $waNumber = preg_replace('/[^0-9]/', '', $fundraiserDetail->whatsapp);
                                    if (str_starts_with($waNumber, '0')) { $waNumber = '62' . substr($waNumber, 1); }
                                @endphp
                                <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                                    <i class="fa-brands fa-whatsapp text-green-500"></i> {{ $fundraiserDetail->whatsapp }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">Email</h4>
                            <p class="text-base font-medium text-gray-900">{{ $fundraiserDetail->email }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">Domisili</h4>
                            <p class="text-base font-medium text-gray-900">{{ $fundraiserDetail->domicile }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">Alamat Lengkap</h4>
                            <p class="text-base font-medium text-gray-900">{{ $fundraiserDetail->address }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">Tanggal Daftar</h4>
                            <p class="text-base font-medium text-gray-900">{{ $fundraiserDetail->created_at->format('d F Y H:i') }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">Status</h4>
                            @if ($fundraiserDetail->status === 'pending')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 mt-1">
                                    <i class="fa-solid fa-hourglass-half mr-1 mt-0.5"></i> Menunggu Konfirmasi
                                </span>
                            @elseif ($fundraiserDetail->status === 'approved')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 mt-1">
                                    <i class="fa-solid fa-check mr-1 mt-0.5"></i> Disetujui
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800 mt-1">
                                    <i class="fa-solid fa-xmark mr-1 mt-0.5"></i> Ditolak
                                </span>
                            @endif
                        </div>

                        @if($fundraiserDetail->referral_code)
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">Kode Referral</h4>
                            <p class="text-base font-mono font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded inline-block mt-1">{{ $fundraiserDetail->referral_code }}</p>
                        </div>
                        @endif

                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">Saldo Ujroh</h4>
                            <p class="text-xl font-bold text-primary mt-1">Rp {{ number_format($fundraiserDetail->available_balance, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                @if($fundraiserDetail->status === 'pending' || $fundraiserDetail->status === 'rejected')
                    <div class="border-t border-gray-100 pt-6 mt-6">
                        <h4 class="text-sm font-bold text-gray-900 mb-3">Tindakan</h4>

                        @if($fundraiserDetail->status === 'pending')
                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-4">
                                <p class="text-sm text-blue-800 mb-3">Anda dapat menyetujui pendaftar ini atau menolaknya dengan memberikan alasan.</p>
                                <div class="flex gap-3">
                                    <button wire:click="approve({{ $fundraiserDetail->id }})" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors shadow-sm flex items-center gap-2">
                                        <i class="fa-solid fa-check"></i> Setujui Pendaftar
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-3">
                            <label for="rejectReason" class="block text-sm font-medium text-gray-700">
                                @if($fundraiserDetail->status === 'pending') Alasan Penolakan (Jika ditolak) @else Alasan Penolakan @endif
                            </label>
                            <textarea wire:model="rejectReason" id="rejectReason" rows="3" class="block w-full border border-gray-200 rounded-xl shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-3" placeholder="Masukkan alasan kenapa ditolak..."></textarea>
                            @error('rejectReason') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

                            @if($fundraiserDetail->status === 'pending')
                                <div class="flex justify-end">
                                    <button wire:click="reject" class="bg-white border border-red-300 text-red-700 hover:bg-red-50 font-bold py-2 px-4 rounded-lg text-sm transition-colors shadow-sm flex items-center gap-2">
                                        <i class="fa-solid fa-xmark"></i> Tolak Pendaftar
                                    </button>
                                </div>
                            @elseif($fundraiserDetail->status === 'rejected')
                                <div class="flex justify-end">
                                    <button wire:click="reject" class="bg-primary hover:bg-primary-hover text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors shadow-sm">
                                        Simpan Alasan Penolakan
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            @endif

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
                <button wire:click="closeModal" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Withdrawal -->
<div x-data="{ show: @entangle('isWithdrawalModalOpen') }"
     x-show="show"
     class="fixed inset-0 z-[100] overflow-y-auto"
     style="display: none;"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" aria-hidden="true" @click="show = false"></div>

        <div class="relative inline-block w-full max-w-2xl overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl sm:my-8"
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-money-bill-transfer text-primary"></i>
                    Detail Pencairan Ujroh
                </h3>
                <button wire:click="closeWithdrawalModal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            @if($withdrawalDetail)
            <div class="px-6 py-6 overflow-y-auto max-h-[calc(100vh-200px)]">

                <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nominal Pencairan</p>
                        <h2 class="text-2xl font-bold text-dark mt-1">Rp {{ number_format($withdrawalDetail->amount, 0, ',', '.') }}</h2>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        @if ($withdrawalDetail->status === 'pending')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-orange-100 text-orange-800 mt-1">Proses</span>
                        @elseif ($withdrawalDetail->status === 'approved')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-emerald-100 text-emerald-800 mt-1">Berhasil</span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800 mt-1">Ditolak</span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">Data Fundriser</h4>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $withdrawalDetail->fundraiser->name ?? '-' }}</p>
                            <p class="text-sm text-gray-600">{{ $withdrawalDetail->fundraiser->whatsapp ?? '-' }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">Tanggal Pengajuan</h4>
                            <p class="text-base font-medium text-gray-900 mt-1">{{ $withdrawalDetail->created_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">Rekening Tujuan</h4>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $withdrawalDetail->bank_name }}</p>
                            <p class="text-sm font-mono text-gray-600">{{ $withdrawalDetail->account_number }}</p>
                            <p class="text-sm text-gray-600">a.n {{ $withdrawalDetail->account_name }}</p>
                        </div>
                    </div>
                </div>

                @if($withdrawalDetail->status === 'approved' && $withdrawalDetail->receipt_image)
                    <div class="mt-6 border-t border-gray-100 pt-6">
                        <h4 class="text-sm font-bold text-gray-900 mb-3">Bukti Transfer</h4>
                        <img src="{{ Storage::url($withdrawalDetail->receipt_image) }}" class="rounded-xl border border-gray-200 max-h-64 object-contain">
                    </div>
                @endif

                @if($withdrawalDetail->status === 'rejected' && $withdrawalDetail->rejected_reason)
                    <div class="mt-6 border-t border-gray-100 pt-6">
                        <h4 class="text-sm font-bold text-red-600 mb-2">Alasan Penolakan</h4>
                        <div class="bg-red-50 p-3 rounded-lg border border-red-100">
                            <p class="text-sm text-red-800">{{ $withdrawalDetail->rejected_reason }}</p>
                        </div>
                    </div>
                @endif

                @if($withdrawalDetail->status === 'pending')
                    <div class="border-t border-gray-100 pt-6 mt-6">
                        <h4 class="text-sm font-bold text-gray-900 mb-4">Tindakan Konfirmasi</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Approve Section -->
                            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">
                                <h5 class="text-emerald-800 font-bold text-sm mb-3">Setujui Pencairan</h5>

                                <div class="mb-3">
                                    <label class="block text-xs font-semibold text-emerald-700 mb-1">Bukti Transfer (Opsional)</label>
                                    <input wire:model="receiptImage" type="file" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                                    @error('receiptImage') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                </div>

                                @if ($receiptImage)
                                    <img src="{{ $receiptImage->temporaryUrl() }}" class="mb-3 h-20 rounded-lg object-cover">
                                @endif

                                <button wire:click="approveWithdrawal" wire:loading.attr="disabled" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors shadow-sm">
                                    <span wire:loading.remove wire:target="approveWithdrawal">Setujui & Tandai Berhasil</span>
                                    <span wire:loading wire:target="approveWithdrawal">Memproses...</span>
                                </button>
                            </div>

                            <!-- Reject Section -->
                            <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                                <h5 class="text-red-800 font-bold text-sm mb-3">Tolak Pencairan</h5>

                                <div class="mb-3">
                                    <label class="block text-xs font-semibold text-red-700 mb-1">Alasan Penolakan</label>
                                    <textarea wire:model="withdrawalRejectReason" rows="2" class="block w-full border border-red-200 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 text-sm p-3" placeholder="Masukkan alasan kenapa ditolak..."></textarea>
                                    @error('withdrawalRejectReason') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                </div>

                                <button wire:click="rejectWithdrawal" class="w-full bg-white border border-red-300 text-red-700 hover:bg-red-100 font-bold py-2 px-4 rounded-lg text-sm transition-colors shadow-sm">
                                    Tolak Pencairan
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @endif

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
                <button wire:click="closeWithdrawalModal" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Commission -->
<div x-data="{ show: @entangle('isCommissionModalOpen') }" 
     x-show="show" 
     class="fixed inset-0 z-[100] overflow-y-auto" 
     style="display: none;"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
     
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" aria-hidden="true" @click="show = false"></div>

        <div class="relative inline-block w-full max-w-4xl overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl sm:my-8"
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-primary"></i>
                    Rincian Komisi: {{ $selectedFundraiserName }}
                </h3>
                <button wire:click="closeCommissionModal" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <div class="px-6 py-6 overflow-y-auto max-h-[calc(100vh-200px)]">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sumber Transaksi</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Donatur</th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Nominal Komisi</th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($selectedFundraiserCommissions as $comm)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $comm->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $sourceTitle = 'Transaksi';
                                        $donorName = '-';
                                        if ($comm->commissionable_type === \App\Models\Donation::class) {
                                            $sourceTitle = 'Donasi: ' . ($comm->commissionable->program->title ?? '-');
                                            $donorName = $comm->commissionable->donor_name ?? 'Hamba Allah';
                                        } elseif ($comm->commissionable_type === \App\Models\QurbanOrder::class) {
                                            $sourceTitle = 'Qurban: ' . ($comm->commissionable->animal->name ?? '-');
                                            $donorName = $comm->commissionable->donor_name ?? 'Hamba Allah';
                                        } elseif ($comm->commissionable_type === \App\Models\QurbanSavingsDeposit::class) {
                                            $sourceTitle = 'Tabungan Qurban';
                                            $donorName = $comm->commissionable->qurbanSaving->donor_name ?? 'Hamba Allah';
                                        }
                                    @endphp
                                    <div class="text-sm font-medium text-gray-900 max-w-[200px] truncate" title="{{ $sourceTitle }}">{{ $sourceTitle }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                    {{ $donorName }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <div class="text-sm font-bold text-emerald-600">+Rp {{ number_format($comm->amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    @if ($comm->status === 'success')
                                        <span class="px-2.5 py-1 inline-flex text-[10px] leading-4 font-bold rounded-full bg-emerald-100 text-emerald-800 uppercase">Success</span>
                                    @elseif ($comm->status === 'pending')
                                        <span class="px-2.5 py-1 inline-flex text-[10px] leading-4 font-bold rounded-full bg-yellow-100 text-yellow-800 uppercase">Pending</span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-[10px] leading-4 font-bold rounded-full bg-red-100 text-red-800 uppercase">Cancelled</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada riwayat komisi untuk fundriser ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
                <button wire:click="closeCommissionModal" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

</div>
</div>

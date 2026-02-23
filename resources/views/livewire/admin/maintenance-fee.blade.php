@section('title', 'Maintenance Fee')
@section('header', 'Maintenance Fee')

<div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-white overflow-hidden shadow-soft rounded-2xl border border-gray-100">
        <div class="p-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <label for="year" class="font-medium text-gray-700">Tahun:</label>
                <select wire:model.live="year" id="year" class="rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 cursor-pointer py-2.5 px-4 text-base transition-colors">
                    @foreach($years as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="text-sm text-gray-500">
                Fee Percentage: <span class="font-bold text-gray-900">{{ $systemFee }}%</span>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white overflow-hidden shadow-soft rounded-2xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bulan</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Dana Terkumpul</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Fee Maintenance</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($months as $monthData)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $monthData['month_name'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right font-mono">
                            Rp {{ number_format($monthData['total_collected'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-primary font-bold text-right font-mono">
                            Rp {{ number_format($monthData['fee_maintenance'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($monthData['status'] == 'paid')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Lunas
                                </span>
                                @if(optional($monthData['record'])->paid_at)
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $monthData['record']->paid_at->format('d/m/Y') }}
                                </div>
                                @endif
                            @elseif($monthData['status'] == 'unverified')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    Menunggu Konfirmasi
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-3">
                                <button wire:click="showDetail({{ $monthData['month_num'] }})" class="text-gray-500 hover:text-primary transition-colors">
                                    Detail
                                </button>
                                
                                @if(!in_array($monthData['status'], ['paid', 'unverified']) && $monthData['fee_maintenance'] > 0 && !$monthData['is_current_month'])
                                <button wire:click="showPayment({{ $monthData['month_num'] }})" class="text-white bg-primary hover:bg-primary-hover px-3 py-1 rounded-lg text-xs transition-colors shadow-sm">
                                    Bayar
                                </button>
                                @endif
                                
                                @if(in_array($monthData['status'], ['paid', 'unverified']) && optional($monthData['record'])->proof_of_payment)
                                <a href="{{ Storage::url($monthData['record']->proof_of_payment) }}" target="_blank" class="text-blue-500 hover:text-blue-700 text-xs flex items-center">
                                    <i class="fa-solid fa-receipt mr-1"></i> Bukti
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Modal -->
    <div
        x-data="{ show: false }"
        x-on:open-modal.window="if ($event.detail.name === 'detail-modal') show = true"
        x-on:close-modal.window="if ($event.detail.name === 'detail-modal') show = false"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="show = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100">
                <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900">
                            Detail Transaksi - Bulan {{ Carbon\Carbon::createFromDate($year, $month ?? 1, 1)->translatedFormat('F Y') }}
                        </h3>
                        <button @click="show = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <div class="overflow-y-auto max-h-[500px] border border-gray-100 rounded-xl">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ID Trx</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Title</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tipe</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Amount</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Fee</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($detailTransactions as $trx)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-xs font-mono text-gray-500">{{ $trx['id_trx'] }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-900">{{ Str::limit($trx['title'], 30) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">{{ $trx['type'] }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-right font-medium">Rp {{ number_format($trx['amount'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-right text-primary">Rp {{ number_format($trx['fee'], 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500 text-sm">Tidak ada transaksi berhasil pada bulan ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div
        x-data="{ show: false }"
        x-on:open-modal.window="if ($event.detail.name === 'payment-modal') show = true"
        x-on:close-modal.window="if ($event.detail.name === 'payment-modal') show = false"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="show = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900">
                            Konfirmasi Pembayaran - {{ Carbon\Carbon::createFromDate($year, $month ?? 1, 1)->translatedFormat('F Y') }}
                        </h3>
                        <button @click="show = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 mb-6">
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Transaksi</span>
                                <span class="font-medium">Rp {{ number_format(collect($detailTransactions)->sum('amount'), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Fee Percentage</span>
                                <span class="font-medium">{{ $systemFee }}%</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2 flex justify-between items-center">
                                <span class="font-bold text-gray-900">Total Fee</span>
                                <span class="font-bold text-xl text-primary">Rp {{ number_format($selectedMonthFee, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 mb-6">
                        <h5 class="text-blue-800 font-bold text-sm mb-3 flex items-center">
                            <i class="fa-solid fa-circle-info mr-2 text-blue-600"></i> Silahkan Transfer Ke
                        </h5>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between items-center border-b border-blue-100 pb-2 last:border-0 last:pb-0">
                                <span class="text-blue-600">Bank</span>
                                <span class="font-bold text-blue-900">{{ $bankDetails['bank'] }}</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-blue-100 pb-2 last:border-0 last:pb-0">
                                <span class="text-blue-600">No. Rekening</span>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-blue-900 font-mono text-base select-all" id="account-number">{{ $bankDetails['account_number'] }}</span>
                                    <button @click="navigator.clipboard.writeText('{{ $bankDetails['account_number'] }}'); alert('Nomor rekening disalin!')" class="text-blue-400 hover:text-blue-600 transition-colors" title="Salin" type="button">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-between items-start border-b border-blue-100 pb-2 last:border-0 last:pb-0">
                                <span class="text-blue-600 whitespace-nowrap mr-4">Atas Nama</span>
                                <span class="font-bold text-blue-900 text-right">{{ $bankDetails['account_name'] }}</span>
                            </div>
                        </div>
                    </div>

                    <form wire:submit.prevent="pay">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="proof-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        @if($proofOfPayment)
                                            <p class="text-sm text-green-600 font-medium">{{ $proofOfPayment->getClientOriginalName() }}</p>
                                        @else
                                            <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-2xl mb-2"></i>
                                            <p class="text-xs text-gray-500">Klik untuk upload gambar</p>
                                        @endif
                                    </div>
                                    <input wire:model="proofOfPayment" id="proof-file" type="file" class="hidden" accept="image/*" />
                                </label>
                            </div>
                            @error('proofOfPayment') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-primary/30">
                            <span wire:loading.remove wire:target="pay">Konfirmasi Pembayaran</span>
                            <span wire:loading wire:target="pay"><i class="fa-solid fa-circle-notch fa-spin"></i> Processing...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

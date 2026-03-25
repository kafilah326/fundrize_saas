@section('title', 'WhatsApp Setting')

<div class="px-6 py-6" x-data>
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">WhatsApp Setting</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola koneksi WhatsApp untuk notifikasi otomatis</p>
        </div>
        
        <div class="flex items-center gap-2">
            @if(($waProvider === 'fonnte' && !empty($fonnteToken)) || ($waProvider === 'starsender' && $isConnected))
            <div class="flex items-center bg-white border border-gray-200 rounded-xl px-4 py-2 shadow-sm">
            <div class="flex items-center mr-4">
                <span class="relative flex h-3 w-3 mr-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="text-sm font-bold text-green-700">Terhubung</span>
            </div>
            <div class="h-8 w-px bg-gray-200 mx-2"></div>
            <label class="flex items-center cursor-pointer group ml-2">
                <span class="mr-3 text-sm font-medium text-gray-700">Notifikasi</span>
                <div class="relative flex items-center">
                    <input type="checkbox" wire:click="toggleEnabled" class="sr-only peer" {{ $starsender_enabled ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </div>
            </label>
        </div>
        @endif
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center" role="alert">
            <i class="fa-solid fa-check-circle mr-2"></i>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center" role="alert">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <!-- Left Column: Device Connection Status -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Provider Selection -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-soft p-6">
                <h3 class="font-bold text-gray-900 flex items-center mb-4">
                    <i class="fa-solid fa-server text-primary mr-2"></i>
                    Provider WhatsApp
                </h3>
                
                <div class="mb-2">
                    <select wire:model.live="waProvider" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white transition-colors cursor-pointer">
                        <option value="starsender">StarSender</option>
                        <option value="fonnte">Fonnte</option>
                    </select>
                </div>
            </div>

            @if($waProvider === 'fonnte')
            <!-- Fonnte Token Setting -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-soft p-6">
                <h3 class="font-bold text-gray-900 flex items-center mb-4">
                    <i class="fa-solid fa-key text-primary mr-2"></i>
                    Konfigurasi Fonnte
                </h3>
                
                <form wire:submit.prevent="saveFonnteToken">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">API Token Fonnte</label>
                        <input wire:model="fonnteToken" type="text" placeholder="Masukkan Token Fonnte..." class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white transition-colors">
                        @error('fonnteToken') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-400 mt-2">Dapatkan token di dashboard <a href="https://fonnte.com" target="_blank" class="text-primary hover:underline">Fonnte</a>.</p>
                    </div>
                    <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white text-sm font-bold py-2.5 rounded-xl transition-all shadow-lg hover:shadow-primary/30 hover:-translate-y-0.5">
                        Simpan Token
                    </button>
                </form>
            </div>
            @endif

            @if($waProvider === 'starsender')
            <!-- Connection Card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-soft overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="font-bold text-gray-900 flex items-center">
                        <i class="fa-brands fa-whatsapp text-green-500 text-xl mr-3"></i>
                        Status Koneksi
                    </h3>
                </div>
                
                <div class="p-6 flex flex-col items-center justify-center text-center min-h-[300px]">
                    @if(!$deviceId && !$isPolling)
                        <!-- State 1: Not Connected -->
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                            <i class="fa-brands fa-whatsapp text-4xl text-gray-400"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">Belum Terhubung</h4>
                        <p class="text-sm text-gray-500 mb-8 max-w-xs mx-auto">
                            Hubungkan akun WhatsApp yayasan untuk mengirim notifikasi donasi otomatis.
                        </p>
                        <button wire:click="connectDevice" wire:loading.attr="disabled" class="w-full bg-primary hover:bg-primary-hover text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5 flex items-center justify-center">
                            <span wire:loading.remove wire:target="connectDevice">
                                <i class="fa-solid fa-link mr-2"></i> Koneksikan WhatsApp
                            </span>
                            <span wire:loading wire:target="connectDevice">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i> Memproses...
                            </span>
                        </button>
                    
                    @elseif($isPolling)
                        <!-- State 2: QR Scanning -->
                        <div class="mb-4 relative" wire:poll.5s="checkDeviceStatus">
                            @if($qrUrl)
                                <img src="{{ $qrUrl }}" alt="QR Code" class="w-48 h-48 object-contain border-4 border-white shadow-lg rounded-xl">
                            @elseif($qrCode)
                                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="w-48 h-48 object-contain border-4 border-white shadow-lg rounded-xl">
                            @else
                                <div class="w-48 h-48 bg-gray-100 rounded-xl flex items-center justify-center animate-pulse">
                                    <i class="fa-solid fa-qrcode text-4xl text-gray-300"></i>
                                </div>
                            @endif
                            
                            <div class="absolute -bottom-3 -right-3 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center">
                                <i class="fa-solid fa-spinner fa-spin text-primary"></i>
                            </div>
                        </div>
                        
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Scan QR Code</h4>
                        <p class="text-xs text-gray-500 mb-6">Buka WhatsApp > Perangkat Tertaut > Tautkan Perangkat</p>
                        
                        <div class="text-xs text-primary font-medium bg-primary/5 px-3 py-1 rounded-full animate-pulse">
                            Menunggu koneksi...
                        </div>

                        <button wire:click="disconnectDevice" class="mt-6 text-xs text-red-500 hover:text-red-700 hover:underline">
                            Batal Koneksi
                        </button>

                    @elseif($isConnected)
                        <!-- State 3: Connected -->
                        <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-4 relative">
                            <i class="fa-brands fa-whatsapp text-4xl text-green-500"></i>
                            <div class="absolute bottom-0 right-0 w-6 h-6 bg-white rounded-full shadow border border-white flex items-center justify-center">
                                <i class="fa-solid fa-check-circle text-green-500 text-lg"></i>
                            </div>
                        </div>
                        
                        <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $deviceInfo['name'] ?? 'WhatsApp Yayasan' }}</h4>
                        <p class="text-sm text-gray-500 mb-6 font-mono">{{ $deviceInfo['no_hp'] ?? $deviceInfo['phone'] ?? $deviceInfo['number'] ?? '-' }}</p>
                        
                        <div class="w-full bg-gray-50 rounded-xl p-4 border border-gray-100 mb-6 text-left space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Platform</span>
                                <span class="font-medium text-dark capitalize">{{ $deviceInfo['platform'] ?? 'Android' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Terhubung</span>
                                <span class="font-medium text-dark">{{ \Carbon\Carbon::parse($deviceConnectedAt)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Status</span>
                                <span class="font-bold text-green-600 uppercase text-xs bg-green-100 px-2 py-0.5 rounded">CONNECTED</span>
                            </div>
                        </div>

                        <!-- Expiry Countdown -->
                        @if($this->daysRemaining <= 5)
                            <div class="w-full bg-red-50 border border-red-100 rounded-xl p-3 mb-6 flex items-start gap-3">
                                <i class="fa-solid fa-clock text-red-500 mt-1"></i>
                                <div>
                                    <p class="text-xs font-bold text-red-700">Koneksi Berakhir {{ $this->daysRemaining }} Hari Lagi</p>
                                    <p class="text-[10px] text-red-600">Mohon relog sebelum {{ $this->expiryDate->format('d M Y') }}</p>
                                </div>
                            </div>
                        @else
                            <div class="w-full bg-blue-50 border border-blue-100 rounded-xl p-3 mb-6 flex items-center justify-between">
                                <span class="text-xs text-blue-700 font-medium">Masa Aktif Koneksi</span>
                                <span class="text-xs font-bold text-blue-800">{{ $this->daysRemaining }} Hari</span>
                            </div>
                        @endif

                        <div class="flex w-full gap-2">
                            <button wire:click="relogDevice" class="flex-1 bg-white border border-gray-300 text-gray-700 font-semibold py-2 rounded-xl hover:bg-gray-50 text-sm transition-colors">
                                Relog
                            </button>
                            <button wire:click="disconnectDevice" wire:confirm="Yakin ingin memutus koneksi WhatsApp?" class="flex-1 bg-white border border-red-200 text-red-600 font-semibold py-2 rounded-xl hover:bg-red-50 text-sm transition-colors">
                                Disconnect
                            </button>
                        </div>

                    @else
                        <!-- State 4: Disconnected / Error -->
                        <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6">
                            <i class="fa-solid fa-link-slash text-4xl text-red-500"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">Koneksi Terputus</h4>
                        <p class="text-sm text-gray-500 mb-8 max-w-xs mx-auto">
                            Device tidak merespon atau sesi telah berakhir. Silakan hubungkan ulang.
                        </p>
                        <button wire:click="relogDevice" class="w-full bg-primary hover:bg-primary-hover text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5">
                            <i class="fa-solid fa-rotate mr-2"></i> Hubungkan Ulang
                        </button>
                    @endif
                </div>
            </div>
            @endif

            <!-- Test Message Card (Only when configured) -->
            @if(($waProvider === 'fonnte' && !empty($fonnteToken)) || ($waProvider === 'starsender' && $isConnected))
            <div class="bg-white rounded-2xl border border-gray-100 shadow-soft p-6">
                <h3 class="font-bold text-gray-900 flex items-center mb-4">
                    <i class="fa-solid fa-paper-plane text-primary mr-2"></i>
                    Kirim Pesan Tes
                </h3>
                
                <form wire:submit.prevent="sendTestMessage">
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Nomor Tujuan</label>
                        <input wire:model="testPhone" type="text" placeholder="08123xxx" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white transition-colors">
                        @error('testPhone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Pesan</label>
                        <textarea wire:model="testMessage" rows="2" placeholder="Halo, ini pesan tes..." class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 py-2.5 px-4 text-sm bg-gray-50 focus:bg-white transition-colors"></textarea>
                        @error('testMessage') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white text-sm font-bold py-2.5 rounded-xl transition-all shadow-lg hover:shadow-primary/30 hover:-translate-y-0.5">
                        Kirim
                    </button>
                </form>
                </form>
            </div>
            @endif

        </div>

        <!-- Right Column: Message Logs -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-soft overflow-hidden h-full flex flex-col">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900">Riwayat Pesan</h3>
                    <button wire:click="$refresh" class="text-gray-400 hover:text-primary transition-colors">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
                
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 text-xs text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Waktu</th>
                                <th class="px-6 py-4 font-semibold">Tujuan</th>
                                <th class="px-6 py-4 font-semibold">Event</th>
                                <th class="px-6 py-4 font-semibold">Pesan</th>
                                <th class="px-6 py-4 font-semibold text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($logs as $log)
                            <tr class="hover:bg-orange-50/30 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ $log->created_at->format('d M H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-dark">
                                    {{ $log->phone }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->event_type == 'payment_success')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Success
                                        </span>
                                    @elseif($log->event_type == 'payment_created')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            Created
                                        </span>
                                    @elseif($log->event_type == 'payment_expired')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                            Expired
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($log->event_type) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $log->message }}">
                                    {{ Str::limit($log->message, 50) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if($log->status == 'sent')
                                        <span class="inline-flex items-center text-xs font-bold text-green-600">
                                            <i class="fa-solid fa-check mr-1"></i> Terkirim
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-xs font-bold text-red-500" title="{{ $log->response_data['error'] ?? $log->response_data['message'] ?? 'Error' }}">
                                            <i class="fa-solid fa-triangle-exclamation mr-1"></i> Gagal
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 text-sm">
                                    Belum ada riwayat pesan yang dikirim.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t border-gray-50">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>

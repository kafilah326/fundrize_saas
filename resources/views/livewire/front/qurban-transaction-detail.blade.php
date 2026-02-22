<div>
    <x-page-header title="Detail Transaksi Qurban" :showBack="true" backUrl="{{ route('qurban.history') }}" />

    <main id="main-content" class="pb-24">
        <section id="transaction-status" class="bg-white px-4 py-4 mb-2">
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'paid' => 'bg-green-100 text-green-800',
                    'success' => 'bg-green-100 text-green-800',
                    'settled' => 'bg-green-100 text-green-800',
                    'failed' => 'bg-red-100 text-red-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                    'expired' => 'bg-gray-100 text-gray-800',
                ];
                $colorClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                
                $statusIcons = [
                    'pending' => 'fa-clock text-yellow-600',
                    'paid' => 'fa-check text-green-600',
                    'success' => 'fa-check text-green-600',
                    'settled' => 'fa-check text-green-600',
                    'failed' => 'fa-xmark text-red-600',
                    'cancelled' => 'fa-xmark text-red-600',
                    'expired' => 'fa-minus text-gray-600',
                ];
                $iconClass = $statusIcons[$order->status] ?? 'fa-info text-gray-600';
                
                // Extract bg color for icon container from colorClass (e.g., bg-green-100)
                $bgClass = explode(' ', $colorClass)[0];
            @endphp
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $bgClass }}">
                    <i class="fa-solid text-lg {{ $iconClass }}"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-sm font-bold text-dark">Transaksi {{ ucfirst($order->status) }}</h2>
                    <p class="text-xs text-gray-600">ID: #{{ $order->transaction_id }}</p>
                </div>
                <span class="px-3 py-1 text-xs font-medium rounded-full capitalize {{ $colorClass }}">
                    {{ $order->status }}
                </span>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">Tanggal Transaksi</span>
                    <span class="text-xs font-semibold text-dark">{{ $order->created_at->format('d F Y, H:i') }}</span>
                </div>
            </div>
        </section>

        <section id="qurban-package" class="bg-white px-4 py-4 mb-2">
            <h3 class="text-sm font-bold text-dark mb-3">Paket Qurban</h3>
            <div class="flex gap-3 p-3 bg-orange-50 rounded-xl border border-orange-100">
                <div class="w-16 h-16 flex-shrink-0 overflow-hidden rounded-lg">
                    <img class="w-full h-full object-cover" src="{{ $order->animal->image }}" alt="{{ $order->animal->name }}" />
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-sm text-dark mb-1">{{ $order->animal->name }}</h4>
                    <p class="text-xs text-gray-600 mb-2">{{ $order->animal->weight }}</p>
                    <p class="text-sm font-bold text-primary">Rp {{ number_format($order->amount, 0, ',', '.') }}</p>
                </div>
            </div>
        </section>

        <section id="muqorib-data" class="bg-white px-4 py-4 mb-2">
            <h3 class="text-sm font-bold text-dark mb-3">Data Muqorib</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-start">
                    <span class="text-xs text-gray-600 flex-shrink-0 w-24">Nama Lengkap</span>
                    <span class="text-xs font-medium text-dark text-right">{{ $order->donor_name }}</span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-xs text-gray-600 flex-shrink-0 w-24">WhatsApp</span>
                    <span class="text-xs font-medium text-dark text-right">{{ $order->whatsapp }}</span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-xs text-gray-600 flex-shrink-0 w-24">Atas Nama</span>
                    <span class="text-xs font-medium text-dark text-right">{{ $order->qurban_name }}</span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-xs text-gray-600 flex-shrink-0 w-24">Email</span>
                    <span class="text-xs font-medium text-dark text-right">{{ $order->email ?? '-' }}</span>
                </div>
            </div>
        </section>

        <section id="delivery-info" class="bg-white px-4 py-4 mb-2">
            <h3 class="text-sm font-bold text-dark mb-3">Informasi Pengiriman</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-start">
                    <span class="text-xs text-gray-600 flex-shrink-0 w-24">Alamat</span>
                    <span class="text-xs font-medium text-dark text-right">{{ $order->address }}</span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-xs text-gray-600 flex-shrink-0 w-24">Kota</span>
                    <span class="text-xs font-medium text-dark text-right">{{ $order->city }}</span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-xs text-gray-600 flex-shrink-0 w-24">Kode Pos</span>
                    <span class="text-xs font-medium text-dark text-right">{{ $order->postal_code }}</span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-xs text-gray-600 flex-shrink-0 w-24">Penyembelihan</span>
                    <span class="text-xs font-medium text-dark text-right capitalize">{{ $order->slaughter_method }}</span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-xs text-gray-600 flex-shrink-0 w-24">Pengiriman</span>
                    <span class="text-xs font-medium text-dark text-right capitalize">{{ str_replace('_', ' ', $order->delivery_method) }}</span>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-3">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fa-solid fa-info-circle text-blue-600 text-xs"></i>
                        <span class="text-xs font-medium text-blue-800">Status Pengiriman</span>
                    </div>
                    <p class="text-xs text-blue-700">Estimasi pengiriman akan diinformasikan lebih lanjut.</p>
                </div>
            </div>
        </section>

        <section id="payment-info" class="bg-white px-4 py-4 mb-2">
            <h3 class="text-sm font-bold text-dark mb-3">Informasi Pembayaran</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-600">Metode Pembayaran</span>
                    <span class="text-xs font-medium text-dark">{{ $order->payment_method }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-600">Total Pembayaran</span>
                    <span class="text-sm font-bold text-primary">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-600">Status Pembayaran</span>
                    <span class="px-2 py-1 text-xs font-medium rounded capitalize {{ $colorClass }}">
                        {{ $order->status }}
                    </span>
                </div>
            </div>
        </section>

        @if($order->status === 'success')
        <section id="certificate-section" class="bg-white px-4 py-4 mb-2">
            <h3 class="text-sm font-bold text-dark mb-3">Sertifikat Qurban</h3>
            <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-certificate text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-dark">Sertifikat Digital</p>
                        <p class="text-xs text-gray-600">Tersedia setelah penyembelihan</p>
                    </div>
                    <button class="px-3 py-2 bg-primary text-white text-xs font-medium rounded-lg hover:bg-orange-600 transition-colors">
                        Unduh
                    </button>
                </div>
            </div>
        </section>

        <section id="qurban-documentation" class="bg-white px-4 py-4 mb-6">
            <h3 class="text-sm font-bold text-dark mb-3">Dokumentasi Qurban</h3>
            
            @if($order->documentations->count() > 0)
                <div class="bg-gray-50 rounded-lg p-3 mb-3">
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($order->documentations as $doc)
                            <div class="rounded-lg overflow-hidden bg-gray-200 aspect-video relative group">
                                @if($doc->file_type === 'photo')
                                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank">
                                        <img src="{{ Storage::url($doc->file_path) }}" class="w-full h-full object-cover" alt="Dokumentasi">
                                    </a>
                                @else
                                    <video src="{{ Storage::url($doc->file_path) }}" class="w-full h-full object-cover" controls></video>
                                @endif
                                @if($doc->caption)
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[10px] p-1 truncate">
                                        {{ $doc->caption }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-3 mb-3">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-camera text-gray-600 text-xs"></i>
                        <span class="text-xs font-medium text-dark">Foto & Video Penyembelihan</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-3">Dokumentasi akan tersedia setelah proses penyembelihan selesai.</p>
                    
                    <div class="grid grid-cols-2 gap-2">
                        <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-image text-gray-400 text-xl"></i>
                        </div>
                        <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-video text-gray-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <div class="flex items-start gap-2">
                    <i class="fa-solid fa-bell text-blue-600 text-xs mt-0.5"></i>
                    <div class="flex-1">
                        <p class="text-xs font-medium text-blue-800 mb-1">Notifikasi Otomatis</p>
                        <p class="text-xs text-blue-700">Anda akan menerima notifikasi via WhatsApp ketika dokumentasi sudah tersedia</p>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </main>

    <div id="action-buttons" class="fixed bottom-0 left-0 right-0 max-w-[460px] mx-auto bg-white border-t border-gray-200 p-4 z-50">
        <div class="flex gap-3">
            <button class="flex-1 py-3 border border-primary text-primary font-semibold text-sm rounded-lg hover:bg-orange-50 transition-colors">
                Hubungi CS
            </button>
            @if($order->status === 'success')
            <button class="flex-1 py-3 bg-primary text-white font-semibold text-sm rounded-lg hover:bg-orange-600 transition-colors">
                Unduh Invoice
            </button>
            @endif
        </div>
    </div>
</div>

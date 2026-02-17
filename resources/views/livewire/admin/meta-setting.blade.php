@section('title', 'Meta Setting')
@section('header', 'Integrasi Meta Ads')

<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white overflow-hidden shadow-soft rounded-2xl border border-gray-100 p-6 md:p-8">
                <div class="flex items-center space-x-3 mb-6 border-b border-gray-100 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/20">
                        <i class="fa-brands fa-meta text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Konfigurasi Meta</h3>
                        <p class="text-sm text-gray-500">Atur integrasi Pixel dan Conversions API (CAPI)</p>
                    </div>
                </div>

                <form wire:submit.prevent="save" class="space-y-6">
                    <!-- Toggles -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-gray-900">Meta Pixel</h4>
                                <p class="text-xs text-gray-500">Tracking via Browser (Client-side)</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="pixel_enabled" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between border-t md:border-t-0 md:border-l border-gray-200 pt-4 md:pt-0 md:pl-6">
                            <div>
                                <h4 class="font-semibold text-gray-900">Conversions API</h4>
                                <p class="text-xs text-gray-500">Tracking via Server (Server-side)</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="capi_enabled" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Pixel ID -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Meta Pixel ID</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fa-solid fa-fingerprint"></i>
                            </span>
                            <input wire:model="pixel_id" type="text" placeholder="Contoh: 123456789012345" class="block w-full pl-10 rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 py-2.5 text-base transition-colors bg-gray-50 focus:bg-white">
                        </div>
                        @error('pixel_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Access Token -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Access Token (CAPI)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fa-solid fa-key"></i>
                            </span>
                            <textarea wire:model="access_token" rows="3" placeholder="Paste access token panjang dari Events Manager disini..." class="block w-full pl-10 rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 py-2.5 text-sm font-mono transition-colors bg-gray-50 focus:bg-white"></textarea>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Token ini diperlukan agar server dapat mengirim event Purchase/InitiateCheckout secara langsung ke Meta.</p>
                        @error('access_token') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Test Event Code -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Test Event Code (Opsional)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fa-solid fa-flask"></i>
                            </span>
                            <input wire:model="test_event_code" type="text" placeholder="Contoh: TEST12345" class="block w-full pl-10 rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 py-2.5 text-base transition-colors bg-gray-50 focus:bg-white">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Gunakan kode ini untuk melihat event yang masuk di tab "Test Events" pada Events Manager. Kosongkan jika sudah live.</p>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-blue-600/30 hover:shadow-blue-600/50 transition-all hover:-translate-y-0.5 inline-flex items-center">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Konfigurasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-blue-50/50 rounded-2xl border border-blue-100 p-6">
                <h4 class="font-bold text-blue-900 mb-4 flex items-center">
                    <i class="fa-solid fa-circle-info mr-2"></i> Panduan Singkat
                </h4>
                
                <ol class="space-y-4 text-sm text-blue-800 list-decimal pl-4">
                    <li>
                        <span class="font-semibold block text-blue-900">Dapatkan Pixel ID</span>
                        Buka Meta Events Manager > Data Sources > Settings. Copy Pixel ID Anda.
                    </li>
                    <li>
                        <span class="font-semibold block text-blue-900">Generate Access Token</span>
                        Di halaman yang sama, scroll ke bawah ke bagian "Conversions API". Klik "Generate Access Token".
                    </li>
                    <li>
                        <span class="font-semibold block text-blue-900">Test Event</span>
                        Untuk memastikan data masuk, buka tab "Test Events". Copy kode test (misal: TEST81273) ke kolom Test Event Code.
                    </li>
                </ol>

                <div class="mt-6 pt-4 border-t border-blue-200">
                    <p class="text-xs text-blue-700">
                        <strong>Tips:</strong> Pastikan "Automatic Advanced Matching" aktif di pengaturan Pixel Anda untuk hasil yang lebih akurat.
                    </p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <h4 class="font-bold text-gray-800 mb-3 text-sm">Event yang Ditrack</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start">
                        <span class="w-6 h-6 rounded bg-green-100 text-green-600 flex items-center justify-center text-xs mr-2 flex-shrink-0">IC</span>
                        <div>
                            <span class="font-medium text-gray-900">InitiateCheckout</span>
                            <p class="text-xs text-gray-500">Saat user mengisi form donasi/checkout</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="w-6 h-6 rounded bg-green-100 text-green-600 flex items-center justify-center text-xs mr-2 flex-shrink-0">P</span>
                        <div>
                            <span class="font-medium text-gray-900">Purchase</span>
                            <p class="text-xs text-gray-500">Saat pembayaran terkonfirmasi (sukses)</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

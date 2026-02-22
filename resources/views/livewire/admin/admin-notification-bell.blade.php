<div x-data="notificationBell()" x-init="init()" wire:poll.3s="checkNewNotifications" class="relative">

    {{-- Audio elements for notification sounds --}}
    <audio id="sound-new-transaction" preload="auto">
        <source src="/sounds/1.mp3" type="audio/mpeg">
    </audio>
    <audio id="sound-payment-success" preload="auto">
        <source src="/sounds/2.mp3" type="audio/mpeg">
    </audio>

    {{-- Bell Button --}}
    <button @click="toggleDropdown()" class="p-2 text-gray-400 hover:text-primary transition-colors relative">
        <i class="fa-regular fa-bell text-xl"></i>
        @if ($unreadCount > 0)
            <span
                class="absolute -top-0 -right-0 flex items-center justify-center h-5 w-5 rounded-full bg-red-500 text-white text-[10px] font-bold ring-2 ring-white animate-pulse">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
        class="fixed inset-x-4 top-16 mt-2 sm:absolute sm:inset-auto sm:right-0 sm:mt-3 w-auto sm:w-96 bg-white rounded-2xl shadow-2xl ring-1 ring-black/5 z-[9999] overflow-hidden origin-top-right"
        style="display: none;">

        {{-- Header --}}
        <div class="px-5 py-4 bg-gradient-to-r from-primary to-secondary flex items-center justify-between">
            <div>
                <h3 class="text-white font-bold text-base">Notifikasi</h3>
                @if ($unreadCount > 0)
                    <p class="text-white/80 text-xs mt-0.5">{{ $unreadCount }} belum dibaca</p>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <button
                    onclick="window.subscribePush().then(res => res ? alert('Notifikasi diaktifkan!') : alert('Gagal mengaktifkan / sudah aktif'))"
                    class="text-xs text-white/90 hover:text-white underline mr-2">
                    <i class="fa-solid fa-bell-slash mr-1"></i> Aktifkan Push
                </button>

                @if ($unreadCount > 0)
                    <button wire:click="markAllAsRead"
                        class="text-xs text-white/90 hover:text-white bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg transition-all font-medium">
                        Tandai dibaca
                    </button>
                @endif
            </div>
        </div>

        {{-- Notification List --}}
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
            @forelse($notifications as $notification)
                <div class="px-5 py-3.5 hover:bg-gray-50/80 transition-colors cursor-pointer flex items-start gap-3 {{ !$notification['is_read'] ? 'bg-orange-50/50' : '' }}"
                    wire:click="markAsRead({{ $notification['id'] }})">

                    {{-- Icon --}}
                    <div class="flex-shrink-0 mt-0.5">
                        @if ($notification['type'] === 'new_transaction')
                            <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fa-solid fa-cart-shopping text-blue-600 text-sm"></i>
                            </div>
                        @else
                            <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fa-solid fa-check-circle text-green-600 text-sm"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $notification['title'] }}</p>
                            @if (!$notification['is_read'])
                                <span class="flex-shrink-0 w-2 h-2 rounded-full bg-primary"></span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $notification['message'] }}</p>
                        <p class="text-[11px] text-gray-400 mt-1">
                            {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="px-5 py-10 text-center">
                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                        <i class="fa-regular fa-bell-slash text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-500 font-medium">Belum ada notifikasi</p>
                    <p class="text-xs text-gray-400 mt-1">Notifikasi transaksi akan muncul di sini</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@script
    <script>
        Alpine.data('notificationBell', () => ({
            open: false,

            init() {
                // Listen for Livewire dispatched sound events
                Livewire.on('play-notification-sound', (params) => {
                    const soundType = params[0]?.sound || params.sound;
                    this.playSound(soundType);
                });
            },

            toggleDropdown() {
                this.open = !this.open;
            },

            playSound(type) {
                let audioId = type === '1' ? 'sound-new-transaction' : 'sound-payment-success';
                const audio = document.getElementById(audioId);
                if (audio) {
                    audio.currentTime = 0;
                    audio.play().catch(e => {
                        console.log(
                            'Sound autoplay blocked by browser. User interaction required first.');
                    });
                }
            }
        }));
    </script>
@endscript

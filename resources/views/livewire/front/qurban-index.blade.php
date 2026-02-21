<div x-data="{ 
    filter: 'semua', 
    selected: null, 
    selectedPrice: 0,
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(price);
    }
}">
    <x-page-header title="Qurban" :showBack="true" />

    <main id="main-content" class="pb-32">
        <!-- Banner Section -->
        <section id="qurban-banner" class="bg-white px-4 py-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative aspect-video">
                <img src="{{ $banner && $banner->image ? $banner->image : 'https://storage.googleapis.com/uxpilot-auth.appspot.com/Fi1siCSktTSfwv8Em1pqs4D0Vek2%2Fcff693df-a22b-4f74-b519-bb1fa7cac475.png' }}" alt="{{ $banner->title ?? 'Tabungan Qurban' }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex flex-col justify-end p-4">
                    <h3 class="text-white font-bold text-lg mb-1">{{ $banner->title ?? 'Tabungan Qurban' }}</h3>
                    @if($banner && $banner->description)
                        <div class="text-white/90 text-xs mb-3 line-clamp-2">{!! $banner->description !!}</div>
                    @else
                        <p class="text-white/90 text-xs mb-3">Cicil qurban mulai dari 50rb/bulan</p>
                    @endif
                    <a href="{{ route('qurban.tabungan') }}" wire:navigate class="bg-primary text-white text-xs font-semibold px-4 py-2 rounded-full w-max shadow-lg active:scale-95 transition-transform hover:bg-primary/90">
                        {{ $banner->cta_text ?? 'Info Tabungan' }}
                    </a>
                </div>
            </div>
        </section>

        <!-- Animal Selection Section -->
        <section id="qurban-selection" class="bg-white px-4 pb-4">
            <h3 class="text-sm font-bold text-dark mb-3">Pilih Hewan Qurban</h3>
            
            <!-- Filters -->
            <div class="flex gap-2 overflow-x-auto hide-scrollbar mb-4 pb-1">
                @foreach(['semua', 'kambing', 'sapi', 'domba', 'kerbau'] as $cat)
                <button @click="filter = '{{ $cat }}'" 
                    :class="filter === '{{ $cat }}' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="px-4 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition-colors">
                    {{ ucfirst($cat) }}
                </button>
                @endforeach
            </div>

            <!-- Animal Cards -->
            <div class="space-y-3">
                @foreach($animals as $animal)
                <div x-show="filter === 'semua' || filter === '{{ $animal->category }}'"
                     @click="selected = {{ $animal->id }}; selectedPrice = {{ $animal->price }}"
                     class="qurban-card bg-white border rounded-xl p-3 flex gap-3 cursor-pointer transition-all hover:shadow-md"
                     :class="selected === {{ $animal->id }} ? 'border-primary bg-orange-50 ring-1 ring-primary' : 'border-gray-200 hover:border-primary/50'">
                    
                    <!-- Image -->
                    <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                        <img src="{{ $animal->image }}" alt="{{ $animal->name }}" class="w-full h-full object-cover">
                    </div>

                    <!-- Content -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-dark line-clamp-1">{{ $animal->name }}</h4>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $animal->weight }}</div>
                        </div>
                        <div class="flex items-end justify-between mt-2">
                            <div>
                                <div class="text-xs text-gray-500 mb-0.5">Stok: {{ $animal->stock }} ekor</div>
                                <div class="text-sm font-bold text-primary">Rp {{ number_format($animal->price, 0, ',', '.') }}</div>
                            </div>
                            
                            <!-- Selection Indicator -->
                            <div class="w-5 h-5 rounded-full border flex items-center justify-center transition-colors"
                                 :class="selected === {{ $animal->id }} ? 'bg-primary border-primary' : 'border-gray-300'">
                                <i x-show="selected === {{ $animal->id }}" class="fa-solid fa-check text-white text-[10px]"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </main>

    <!-- Sticky Button -->
    <div id="sticky-button" class="fixed bottom-0 left-0 right-0 max-w-[460px] mx-auto bg-white border-t border-gray-200 p-4 z-50 transition-opacity duration-300"
         :class="selected ? 'opacity-100 pointer-events-auto' : 'opacity-50 pointer-events-none'">
        <button @click="$wire.selectAnimal(selected)" 
                class="w-full bg-primary text-white py-3 rounded-xl text-sm font-bold shadow-lg active:scale-95 transition-transform hover:bg-primary/90 flex items-center justify-center gap-2">
            <span>Tunaikan Qurban</span>
            <span x-show="selected" x-text="'- ' + formatPrice(selectedPrice)"></span>
        </button>
    </div>
</div>

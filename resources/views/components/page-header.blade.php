@props(['title', 'showBack' => true, 'backUrl' => null])

<header id="header" class="bg-white shadow-sm sticky top-0 z-50">
    <div class="px-4 py-3 flex items-center gap-3">
        @if($showBack)
            @if($backUrl)
            <a href="{{ $backUrl }}" wire:navigate class="w-9 h-9 flex items-center justify-center">
                <i class="fa-solid fa-arrow-left text-dark text-lg"></i>
            </a>
            @else
            <button onclick="history.back()" class="w-9 h-9 flex items-center justify-center">
                <i class="fa-solid fa-arrow-left text-dark text-lg"></i>
            </button>
            @endif
        @endif
        
        <h1 class="text-base font-bold text-dark flex-1">{{ $title }}</h1>
        
        @if(isset($actions))
            {{ $actions }}
        @endif
    </div>
</header>

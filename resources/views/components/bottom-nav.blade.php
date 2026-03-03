@props(['active' => ''])

<nav id="bottom-nav" class="fixed bottom-0 left-0 right-0 max-w-[460px] mx-auto bg-white border-t border-gray-200 px-4 py-2 z-50">
    <div class="flex items-center justify-between w-full mx-auto">
        <a href="{{ route('home') }}" wire:navigate class="flex flex-col items-center gap-1 min-w-[60px]">
            <i class="fa-solid fa-house {{ $active === 'home' ? 'text-primary' : 'text-gray-400' }} text-lg"></i>
            <span class="text-xs font-medium {{ $active === 'home' ? 'text-primary' : 'text-gray-400' }}">Home</span>
        </a>
        <a href="{{ route('program.index') }}" wire:navigate class="flex flex-col items-center gap-1 min-w-[60px]">
            <i class="fa-solid fa-list {{ $active === 'program' ? 'text-primary' : 'text-gray-400' }} text-lg"></i>
            <span class="text-xs font-medium {{ $active === 'program' ? 'text-primary' : 'text-gray-400' }}">Program</span>
        </a>
        
        @auth
        <a href="{{ route('my-donation.index') }}" wire:navigate class="flex flex-col items-center gap-1 min-w-[60px]">
            <i class="fa-solid fa-hand-holding-heart {{ $active === 'donation' ? 'text-primary' : 'text-gray-400' }} text-lg"></i>
            <span class="text-xs font-medium {{ $active === 'donation' ? 'text-primary' : 'text-gray-400' }}">Donasi Saya</span>
        </a>
        <a href="{{ route('report.index') }}" wire:navigate class="flex flex-col items-center gap-1 min-w-[60px]">
            <i class="fa-solid fa-chart-line {{ $active === 'report' ? 'text-primary' : 'text-gray-400' }} text-lg"></i>
            <span class="text-xs font-medium {{ $active === 'report' ? 'text-primary' : 'text-gray-400' }}">Laporan</span>
        </a>
        <a href="{{ route('profile.index') }}" wire:navigate class="flex flex-col items-center gap-1 min-w-[60px]">
            <i class="fa-solid fa-user {{ $active === 'profile' ? 'text-primary' : 'text-gray-400' }} text-lg"></i>
            <span class="text-xs font-medium {{ $active === 'profile' ? 'text-primary' : 'text-gray-400' }}">Profil</span>
        </a>
        @else
        <a href="{{ route('login.required', ['tab' => 'donation']) }}" wire:navigate class="flex flex-col items-center gap-1 min-w-[60px]">
            <i class="fa-solid fa-hand-holding-heart {{ $active === 'donation' ? 'text-primary' : 'text-gray-400' }} text-lg"></i>
            <span class="text-xs font-medium {{ $active === 'donation' ? 'text-primary' : 'text-gray-400' }}">Donasi Saya</span>
        </a>
        <a href="{{ route('report.index') }}" wire:navigate class="flex flex-col items-center gap-1 min-w-[60px]">
            <i class="fa-solid fa-chart-line {{ $active === 'report' ? 'text-primary' : 'text-gray-400' }} text-lg"></i>
            <span class="text-xs font-medium {{ $active === 'report' ? 'text-primary' : 'text-gray-400' }}">Laporan</span>
        </a>
        <a href="{{ route('login.required', ['tab' => 'profile']) }}" wire:navigate class="flex flex-col items-center gap-1 min-w-[60px]">
            <i class="fa-solid fa-user {{ $active === 'profile' ? 'text-primary' : 'text-gray-400' }} text-lg"></i>
            <span class="text-xs font-medium {{ $active === 'profile' ? 'text-primary' : 'text-gray-400' }}">Profil</span>
        </a>
        @endauth
    </div>
</nav>

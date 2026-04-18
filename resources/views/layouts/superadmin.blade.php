<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SuperAdmin' }} | Platform</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous">

    <!-- Tailwind Config for Inter -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#4f46e5', // Indigo 600
                        secondary: '#4338ca', // Indigo 700
                    }
                }
            }
        }
    </script>
    
    @livewireStyles
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 flex min-h-screen" x-data="{ sidebarOpen: false }">

    @auth('superadmin')
    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-slate-900/80 backdrop-blur-sm lg:hidden" x-transition.opacity @click="sidebarOpen = false" style="display: none;"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white flex-shrink-0 flex flex-col transition-transform duration-300 lg:static lg:translate-x-0 border-r border-slate-800 shadow-2xl">
        <!-- Brand Logo -->
        <div class="h-20 flex items-center justify-between px-6 bg-slate-950/40 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/10 to-transparent pointer-events-none"></div>
            <div class="flex items-center space-x-3 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 text-white font-bold ring-2 ring-indigo-400/20">
                    <i class="fa-solid fa-bolt text-lg"></i>
                </div>
                <div class="flex flex-col">
                    <span class="font-black text-lg tracking-tight text-white leading-none">FUNDRIZE</span>
                    <span class="text-[10px] font-bold text-indigo-400 tracking-[0.2em] mt-1 uppercase">SaaS Panel</span>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto py-6 flex flex-col gap-1 px-4 scrollbar-thin scrollbar-thumb-slate-800">
            <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] mb-3 mt-2">Utama</p>
            
            <a href="{{ route('superadmin.dashboard') }}" class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('superadmin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-slate-100' }}">
                <div class="w-6 flex justify-center text-lg {{ request()->routeIs('superadmin.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <span class="font-semibold text-sm">Dashboard Overview</span>
            </a>
            
            <a href="{{ route('superadmin.tenants') }}" class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('superadmin.tenants*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-slate-100' }}">
                <div class="w-6 flex justify-center text-lg {{ request()->routeIs('superadmin.tenants*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors">
                    <i class="fa-solid fa-building-ngo"></i>
                </div>
                <span class="font-semibold text-sm">Manajemen Tenant</span>
            </a>

            <a href="{{ route('superadmin.plans') }}" class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('superadmin.plans*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-slate-100' }}">
                <div class="w-6 flex justify-center text-lg {{ request()->routeIs('superadmin.plans*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <span class="font-semibold text-sm">Paket & Harga</span>
            </a>

            <a href="{{ route('superadmin.addons') }}" class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('superadmin.addons*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-slate-100' }}">
                <div class="w-6 flex justify-center text-lg {{ request()->routeIs('superadmin.addons*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors">
                    <i class="fa-solid fa-puzzle-piece"></i>
                </div>
                <span class="font-semibold text-sm">Manajemen Add-on</span>
            </a>

            <div class="mt-8">
                <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] mb-3">Penagihan & Transaksi</p>
                <a href="{{ route('superadmin.transactions') }}" class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('superadmin.transactions*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-slate-100' }}">
                    <div class="w-6 flex justify-center text-lg {{ request()->routeIs('superadmin.transactions*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <span class="font-semibold text-sm">Riwayat Transaksi</span>
                </a>
            </div>

            <div class="mt-8">
                <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] mb-3">Sistem</p>
                <a href="{{ route('superadmin.settings') }}" class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('superadmin.settings*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-slate-100' }}">
                    <div class="w-6 flex justify-center text-lg {{ request()->routeIs('superadmin.settings*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors">
                        <i class="fa-solid fa-gears"></i>
                    </div>
                    <span class="font-semibold text-sm">Pengaturan Situs</span>
                </a>
            </div>
        </div>
        
        <!-- User Footer -->
        <div class="p-6 bg-slate-950/20 border-t border-slate-800/50">
            <div class="flex items-center space-x-3 mb-6">
                <div class="relative">
                    <div class="w-12 h-12 rounded-xl bg-slate-800 flex items-center justify-center text-indigo-400 border border-slate-700 shadow-inner">
                        <i class="fa-solid fa-user-shield text-xl"></i>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-slate-900"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate">{{ auth('superadmin')->user()->name }}</p>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Super Administrator</p>
                </div>
            </div>
            <form action="{{ route('superadmin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-red-500/5 hover:bg-red-500 text-red-500 hover:text-white rounded-xl transition-all duration-200 border border-red-500/10 hover:border-red-500 text-xs font-bold uppercase tracking-widest">
                    <i class="fa-solid fa-power-off"></i>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>
    
    <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">
        <header class="h-20 bg-white/70 backdrop-blur-xl border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-40">
            <div class="flex items-center">
                <button @click="sidebarOpen = true" class="lg:hidden mr-4 w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
                <div class="flex flex-col">
                    <h1 class="font-extrabold text-xl text-slate-800 tracking-tight">{{ $title ?? 'SuperAdmin' }}</h1>
                    <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                        <i class="fa-solid fa-circle-check text-indigo-500 text-[8px]"></i>
                        <span>Central SaaS Panel</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-6">
                <!-- Notifications/Search (Future) -->
                <div class="flex gap-2">
                    <button class="w-10 h-10 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50 hover:bg-white hover:text-indigo-600 transition-all">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <button class="w-10 h-10 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50 hover:bg-white hover:text-indigo-600 transition-all relative">
                        <i class="fa-solid fa-bell"></i>
                        <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                    </button>
                </div>
                
                <div class="h-10 w-[1px] bg-slate-200"></div>
                
                <div class="hidden sm:flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Status Server</p>
                        <p class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Optimal
                        </p>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8 flex-1 overflow-auto custom-scrollbar">
            {{ $slot }}
        </div>
    </main>
    @else
    <main class="flex-1 flex items-center justify-center p-6 bg-slate-100">
        {{ $slot }}
    </main>
    @endauth

    @livewireScripts
</body>
</html>

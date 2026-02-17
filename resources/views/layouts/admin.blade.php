<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - Yayasan Peduli</title>

    @php
        $foundation = \App\Models\FoundationSetting::first();
    @endphp
    @if($foundation && $foundation->favicon)
        <link rel="icon" type="image/png" href="{{ $foundation->favicon }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#FF6B35',
                        secondary: '#FFA07A',
                        dark: '#111827', // Gray 900
                        'dark-lighter': '#1F2937', // Gray 800
                        light: '#F3F4F6',
                        'primary-hover': '#e55a2b',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        ::-webkit-scrollbar-thumb {
            background: #d1d5db; 
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af; 
        }
        
        /* Smooth transitions */
        .transition-all-300 {
            transition: all 0.3s ease-in-out;
        }

        [x-cloak] { display: none !important; }

        /* Rich Text Content Styling (Quill Restore) for Admin Preview */
        .rich-text-content h1 { font-size: 1.875rem; font-weight: 700; margin-top: 1.5em; margin-bottom: 0.75em; line-height: 1.25; }
        .rich-text-content h2 { font-size: 1.5rem; font-weight: 700; margin-top: 1.25em; margin-bottom: 0.75em; line-height: 1.33; }
        .rich-text-content h3 { font-size: 1.25rem; font-weight: 600; margin-top: 1em; margin-bottom: 0.6em; line-height: 1.6; }
        .rich-text-content p { margin-bottom: 1em; line-height: 1.75; }
        .rich-text-content ul { list-style-type: disc; padding-left: 1.625em; margin-top: 1em; margin-bottom: 1em; }
        .rich-text-content ol { list-style-type: decimal; padding-left: 1.625em; margin-top: 1em; margin-bottom: 1em; }
        .rich-text-content li { margin-top: 0.5em; margin-bottom: 0.5em; padding-left: 0.375em; }
        .rich-text-content blockquote { border-left: 4px solid #e5e7eb; padding-left: 1em; color: #4b5563; margin: 1.5em 0; font-style: italic; }
        .rich-text-content a { color: #FF6B35; text-decoration: underline; font-weight: 500; }
        .rich-text-content img { max-width: 100%; height: auto; border-radius: 0.5rem; margin: 1.5em 0; }
        .rich-text-content strong, .rich-text-content b { font-weight: 700; }
        .rich-text-content em, .rich-text-content i { font-style: italic; }
        .rich-text-content u { text-decoration: underline; }
        .rich-text-content s, .rich-text-content strike { text-decoration: line-through; }
        /* Quill Alignment Classes */
        .rich-text-content .ql-align-center { text-align: center; }
        .rich-text-content .ql-align-right { text-align: right; }
        .rich-text-content .ql-align-justify { text-align: justify; }
        .rich-text-content .ql-indent-1 { padding-left: 3em; }
        .rich-text-content .ql-indent-2 { padding-left: 6em; }
        .rich-text-content .ql-indent-3 { padding-left: 9em; }

        /* Quill Editor Customization */
        .ql-toolbar.ql-snow {
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            border-color: #e5e7eb;
            background-color: #f9fafb;
            font-family: 'Inter', sans-serif;
        }
        .ql-container.ql-snow {
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            border-color: #e5e7eb;
            background-color: #ffffff;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
        }
        .ql-editor {
            min-height: 200px;
        }
        /* Custom focus ring for Quill */
        .ql-container.ql-snow:focus-within {
            border-color: #FF6B35;
            box-shadow: 0 0 0 1px #FF6B35;
        }
    </style>

    @livewireStyles
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased selection:bg-primary selection:text-white" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-dark text-white transition-transform duration-300 ease-in-out transform lg:static lg:translate-x-0 shadow-xl"
            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-dark-lighter/50 border-b border-gray-800">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-lg shadow-lg">
                        <i class="fa-solid fa-hand-holding-heart text-sm"></i>
                    </div>
                    <span class="text-lg font-bold tracking-tight text-white">Yayasan<span class="text-primary">Peduli</span></span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-6 px-3 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 
                   @if(request()->routeIs('admin.dashboard')) bg-primary/10 text-primary border-l-4 border-primary @else text-gray-400 hover:bg-dark-lighter hover:text-white hover:translate-x-1 @endif">
                    <i class="fa-solid fa-house w-6 text-center mr-3 text-lg @if(request()->routeIs('admin.dashboard')) text-primary @else text-gray-500 group-hover:text-white transition-colors @endif"></i>
                    Dashboard
                </a>

                <div class="pt-6 pb-2">
                    <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Manajemen</p>
                </div>

                <a href="{{ route('admin.programs') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 
                   @if(request()->routeIs('admin.programs*')) bg-primary/10 text-primary border-l-4 border-primary @else text-gray-400 hover:bg-dark-lighter hover:text-white hover:translate-x-1 @endif">
                    <i class="fa-solid fa-hand-holding-heart w-6 text-center mr-3 text-lg @if(request()->routeIs('admin.programs*')) text-primary @else text-gray-500 group-hover:text-white transition-colors @endif"></i>
                    Program Donasi
                </a>

                <a href="{{ route('admin.categories') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 
                   @if(request()->routeIs('admin.categories*')) bg-primary/10 text-primary border-l-4 border-primary @else text-gray-400 hover:bg-dark-lighter hover:text-white hover:translate-x-1 @endif">
                    <i class="fa-solid fa-tags w-6 text-center mr-3 text-lg @if(request()->routeIs('admin.categories*')) text-primary @else text-gray-500 group-hover:text-white transition-colors @endif"></i>
                    Kategori
                </a>

                <a href="{{ route('admin.akad-types') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 
                   @if(request()->routeIs('admin.akad-types*')) bg-primary/10 text-primary border-l-4 border-primary @else text-gray-400 hover:bg-dark-lighter hover:text-white hover:translate-x-1 @endif">
                    <i class="fa-solid fa-handshake w-6 text-center mr-3 text-lg @if(request()->routeIs('admin.akad-types*')) text-primary @else text-gray-500 group-hover:text-white transition-colors @endif"></i>
                    Tipe Akad
                </a>

                <a href="{{ route('admin.banners') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 
                   @if(request()->routeIs('admin.banners*')) bg-primary/10 text-primary border-l-4 border-primary @else text-gray-400 hover:bg-dark-lighter hover:text-white hover:translate-x-1 @endif">
                    <i class="fa-solid fa-images w-6 text-center mr-3 text-lg @if(request()->routeIs('admin.banners*')) text-primary @else text-gray-500 group-hover:text-white transition-colors @endif"></i>
                    Banner
                </a>

                <div class="pt-6 pb-2">
                    <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Transaksi</p>
                </div>

                <a href="{{ route('admin.donations') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 
                   @if(request()->routeIs('admin.donations*')) bg-primary/10 text-primary border-l-4 border-primary @else text-gray-400 hover:bg-dark-lighter hover:text-white hover:translate-x-1 @endif">
                    <i class="fa-solid fa-receipt w-6 text-center mr-3 text-lg @if(request()->routeIs('admin.donations*')) text-primary @else text-gray-500 group-hover:text-white transition-colors @endif"></i>
                    Donasi Masuk
                </a>
                
                <a href="{{ route('admin.qurban') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 
                   @if(request()->routeIs('admin.qurban*')) bg-primary/10 text-primary border-l-4 border-primary @else text-gray-400 hover:bg-dark-lighter hover:text-white hover:translate-x-1 @endif">
                    <i class="fa-solid fa-cow w-6 text-center mr-3 text-lg @if(request()->routeIs('admin.qurban*')) text-primary @else text-gray-500 group-hover:text-white transition-colors @endif"></i>
                    Qurban
                </a>

                <a href="{{ route('admin.maintenance-fee') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 
                   @if(request()->routeIs('admin.maintenance-fee*')) bg-primary/10 text-primary border-l-4 border-primary @else text-gray-400 hover:bg-dark-lighter hover:text-white hover:translate-x-1 @endif">
                    <i class="fa-solid fa-file-invoice-dollar w-6 text-center mr-3 text-lg @if(request()->routeIs('admin.maintenance-fee*')) text-primary @else text-gray-500 group-hover:text-white transition-colors @endif"></i>
                    Maintenance Fee
                </a>

                <div class="pt-6 pb-2">
                    <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Pengaturan</p>
                </div>

                <a href="{{ route('admin.users') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 
                   @if(request()->routeIs('admin.users*')) bg-primary/10 text-primary border-l-4 border-primary @else text-gray-400 hover:bg-dark-lighter hover:text-white hover:translate-x-1 @endif">
                    <i class="fa-solid fa-users w-6 text-center mr-3 text-lg @if(request()->routeIs('admin.users*')) text-primary @else text-gray-500 group-hover:text-white transition-colors @endif"></i>
                    Pengguna
                </a>

                <a href="{{ route('admin.profile') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 
                   @if(request()->routeIs('admin.profile*')) bg-primary/10 text-primary border-l-4 border-primary @else text-gray-400 hover:bg-dark-lighter hover:text-white hover:translate-x-1 @endif">
                    <i class="fa-solid fa-circle-user w-6 text-center mr-3 text-lg @if(request()->routeIs('admin.profile*')) text-primary @else text-gray-500 group-hover:text-white transition-colors @endif"></i>
                    Profile
                </a>

                <a href="{{ route('admin.settings') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 
                   @if(request()->routeIs('admin.settings*')) bg-primary/10 text-primary border-l-4 border-primary @else text-gray-400 hover:bg-dark-lighter hover:text-white hover:translate-x-1 @endif">
                    <i class="fa-solid fa-gear w-6 text-center mr-3 text-lg @if(request()->routeIs('admin.settings*')) text-primary @else text-gray-500 group-hover:text-white transition-colors @endif"></i>
                    Settings
                </a>
                
                <a href="{{ route('admin.meta-setting') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 
                   @if(request()->routeIs('admin.meta-setting*')) bg-primary/10 text-primary border-l-4 border-primary @else text-gray-400 hover:bg-dark-lighter hover:text-white hover:translate-x-1 @endif">
                    <i class="fa-brands fa-meta w-6 text-center mr-3 text-lg @if(request()->routeIs('admin.meta-setting*')) text-primary @else text-gray-500 group-hover:text-white transition-colors @endif"></i>
                    Meta Setting
                </a>

                <a href="{{ route('admin.whatsapp') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl group transition-all duration-200 
                   @if(request()->routeIs('admin.whatsapp*')) bg-primary/10 text-primary border-l-4 border-primary @else text-gray-400 hover:bg-dark-lighter hover:text-white hover:translate-x-1 @endif">
                    <i class="fa-brands fa-whatsapp w-6 text-center mr-3 text-lg @if(request()->routeIs('admin.whatsapp*')) text-primary @else text-gray-500 group-hover:text-white transition-colors @endif"></i>
                    WhatsApp
                </a>
                
                <!-- Logout -->
                <div class="mt-10 px-4 pb-6">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 text-red-400 hover:bg-red-500/10 hover:text-red-300 hover:translate-x-1 border border-transparent hover:border-red-500/20">
                            <i class="fa-solid fa-right-from-bracket w-6 text-center mr-3 text-lg"></i>
                            Keluar
                        </button>
                    </form>
                </div>

            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden bg-gray-50">
            <!-- Header -->
            <header class="bg-white/80 backdrop-blur-md border-b border-gray-200 z-10 sticky top-0">
                <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden p-2 rounded-md hover:bg-gray-100">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>

                    <!-- Page Title (Desktop) -->
                    <div class="hidden lg:flex lg:flex-col">
                        <h1 class="text-lg font-bold text-gray-800 leading-tight">
                            @yield('header', 'Dashboard')
                        </h1>
                        <p class="text-xs text-gray-500">Selamat datang kembali, {{ Auth::user()->name }}!</p>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications (Placeholder) -->
                        <button class="p-2 text-gray-400 hover:text-primary transition-colors relative">
                            <i class="fa-regular fa-bell text-xl"></i>
                            <span class="absolute top-1 right-1 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                        </button>

                        <div class="h-8 w-px bg-gray-200 mx-2"></div>

                        <!-- User Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none group">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm font-semibold text-gray-700 group-hover:text-primary transition-colors">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">Administrator</p>
                                </div>
                                <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm group-hover:border-primary transition-all" 
                                     src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=FF6B35&color=fff' }}" 
                                     alt="{{ Auth::user()->name }}">
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 group-hover:text-primary transition-colors"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50 origin-top-right"
                                 style="display: none;">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm text-gray-900 font-medium truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fa-solid fa-circle-user mr-2 text-gray-400"></i> Profile
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <button type="submit" @click.stop class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-6 lg:p-8">
                <!-- Flash Messages -->
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="fixed top-20 right-5 z-50">
                    @if (session()->has('success'))
                        <div x-transition:enter="transform ease-out duration-300 transition"
                             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="mb-4 bg-white border-l-4 border-green-500 p-4 rounded-lg shadow-lg flex items-center min-w-[300px]">
                            <div class="flex-shrink-0 bg-green-100 rounded-full p-2">
                                <i class="fa-solid fa-check text-green-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Berhasil!</p>
                                <p class="text-sm text-gray-500">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-xmark"></i>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div x-transition:enter="transform ease-out duration-300 transition"
                             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="mb-4 bg-white border-l-4 border-red-500 p-4 rounded-lg shadow-lg flex items-center min-w-[300px]">
                            <div class="flex-shrink-0 bg-red-100 rounded-full p-2">
                                <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Error!</p>
                                <p class="text-sm text-gray-500">{{ session('error') }}</p>
                            </div>
                            <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-xmark"></i>
                        </div>
                    @endif
                </div>

                {{ $slot }}
            </main>
        </div>

        <!-- Overlay for mobile sidebar -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"
             style="display: none;"></div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('quillEditor', (model) => ({
                value: model,
                editor: null,
                init() {
                    const _this = this;
                    
                    this.editor = new Quill(this.$refs.quillEditor, {
                        theme: 'snow',
                        modules: {
                            toolbar: {
                                container: [
                                    ['bold', 'italic', 'underline', 'strike'],
                                    ['blockquote', 'code-block'],
                                    [{ 'header': 1 }, { 'header': 2 }],
                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                    [{ 'script': 'sub'}, { 'script': 'super' }],
                                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                                    [{ 'size': ['small', false, 'large', 'huge'] }],
                                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                                    [{ 'color': [] }, { 'background': [] }],
                                    [{ 'align': [] }],
                                    ['clean'],
                                    ['link', 'image']
                                ],
                                handlers: {
                                    image: function() {
                                        const input = document.createElement('input');
                                        input.setAttribute('type', 'file');
                                        input.setAttribute('accept', 'image/*');
                                        input.click();

                                        input.onchange = async () => {
                                            const file = input.files[0];
                                            if (/^image\//.test(file.type)) {
                                                const formData = new FormData();
                                                formData.append('image', file);

                                                try {
                                                    const response = await fetch("{{ route('admin.upload-editor-image') }}", {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-Requested-With': 'XMLHttpRequest',
                                                            'Accept': 'application/json',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                        },
                                                        body: formData
                                                    });

                                                    if (!response.ok) throw new Error('Upload failed');

                                                    const data = await response.json();
                                                    
                                                    // Insert image at cursor
                                                    const range = _this.editor.getSelection(true);
                                                    _this.editor.insertEmbed(range.index, 'image', data.url);
                                                    
                                                    // Trigger change event manually to sync with Livewire immediately
                                                    _this.editor.setSelection(range.index + 1);
                                                    _this.value = _this.editor.root.innerHTML;

                                                } catch (error) {
                                                    console.error('Error uploading image:', error);
                                                    alert('Gagal mengupload gambar. Silakan coba lagi.');
                                                }
                                            } else {
                                                alert('Hanya file gambar yang diperbolehkan.');
                                            }
                                        };
                                    }
                                }
                            }
                        }
                    });

                    // Set initial content
                    if (this.value) {
                        this.editor.root.innerHTML = this.value;
                    }

                    // Sync changes to Livewire
                    this.editor.on('text-change', () => {
                        this.value = this.editor.root.innerHTML;
                    });

                    // Sync changes from Livewire
                    this.$watch('value', (newValue) => {
                        if (newValue === null) newValue = '';
                        if (this.editor.root.innerHTML !== newValue) {
                            // Only update if content is different to avoid cursor jumps
                            // But we need to be careful not to overwrite if user is typing
                            // Usually this only happens on reset or initial load
                             const currentContent = this.editor.root.innerHTML;
                             if (currentContent !== newValue) {
                                 this.editor.root.innerHTML = newValue;
                             }
                        }
                    });
                }
            }));
        });
    </script>
    @livewireScripts
</body>
</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Fundrize - Platform Digital untuk Yayasan & Lembaga Sosial' }}</title>
    <meta name="description"
        content="Digitalkan yayasan Anda dengan Fundrize. Kelola donasi, qurban, zakat, dan fundraiser dalam satu platform terintegrasi.">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>

    @livewireStyles
</head>

<body class="bg-slate-50 text-slate-900 selection:bg-primary-100 selection:text-primary-700">

    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 glass" id="main-nav">
        <div class="container mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div
                    class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-200">
                    <i class="fas fa-heart"></i>
                </div>
                <span class="text-xl font-extrabold tracking-tight">Fundrize</span>
            </div>

            <nav class="hidden md:flex items-center gap-8">
                <a href="#features" class="font-medium hover:text-primary-600 transition-colors">Fitur</a>
                <a href="#pricing" class="font-medium hover:text-primary-600 transition-colors">Harga</a>
                <a href="#faq" class="font-medium hover:text-primary-600 transition-colors">FAQ</a>
            </nav>

            <div class="flex items-center gap-4">
                {{-- <a href="{{ route('superadmin.login') }}" class="hidden md:block font-medium hover:text-primary-600 px-4 py-2 transition-colors">Masuk</a> --}}
                <a href="#pricing"
                    class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-2.5 rounded-full shadow-lg shadow-primary-100 transition-all hover:-translate-y-0.5 active:translate-y-0">Mulai
                    Sekarang</a>
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-slate-900 text-slate-400 py-20">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 text-white mb-6">
                        <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center text-white">
                            <i class="fas fa-heart text-sm"></i>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight">Fundrize</span>
                    </div>
                    <p class="max-w-xs mb-8">
                        Solusi digital satu pintu untuk membantu yayasan dan lembaga sosial mengelola donasi dengan
                        lebih modern, transparan, dan efisien.
                    </p>
                    <div class="flex gap-4">
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary-500 hover:text-white transition-all"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary-500 hover:text-white transition-all"><i
                                class="fab fa-instagram"></i></a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary-500 hover:text-white transition-all"><i
                                class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6">Platform</h4>
                    <ul class="space-y-4">
                        <li><a href="#features" class="hover:text-primary-400 transition-colors font-medium">Fitur
                                Utama</a></li>
                        <li><a href="#pricing" class="hover:text-primary-400 transition-colors font-medium">Harga
                                Paket</a></li>
                        <li><a href="#"
                                class="hover:text-primary-400 transition-colors font-medium">Dokumentasi</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6">Perusahaan</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="hover:text-primary-400 transition-colors font-medium">Tentang
                                Kami</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors font-medium">Kontak</a>
                        </li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors font-medium">Kebijakan
                                Privasi</a></li>
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-slate-800 mt-20 pt-10 flex flex-col md:row items-center justify-between text-sm">
                <p>&copy; {{ date('Y') }} Fundrize SaaS. All rights reserved.</p>
                <p class="mt-4 md:mt-0">Dibuat dengan <i class="fas fa-heart text-red-500"></i> untuk Kemanusiaan.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>

</html>

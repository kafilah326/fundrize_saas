<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Yayasan Peduli' }}</title>
    
    @php
        $foundation = \App\Models\FoundationSetting::first();
    @endphp
    @if($foundation && $foundation->favicon)
        <link rel="icon" type="image/png" href="{{ Storage::url($foundation->favicon) }}">
    @endif

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#FF6B35',
                        secondary: '#FFA07A',
                        dark: '#1A1A1A',
                        light: '#F8F9FA'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Base Styles -->
    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Inter', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }

        /* Rich Text Content Styling (Quill Restore) */
        .rich-text-content h1 { font-size: 1.875rem; font-weight: 700; margin-top: 1.5em; margin-bottom: 0.75em; line-height: 1.25; color: #111827; }
        .rich-text-content h2 { font-size: 1.5rem; font-weight: 700; margin-top: 1.25em; margin-bottom: 0.75em; line-height: 1.33; color: #1f2937; }
        .rich-text-content h3 { font-size: 1.25rem; font-weight: 600; margin-top: 1em; margin-bottom: 0.6em; line-height: 1.6; color: #374151; }
        .rich-text-content p { margin-bottom: 1em; line-height: 1.75; color: #374151; }
        .rich-text-content ul { list-style-type: disc; padding-left: 1.625em; margin-top: 1em; margin-bottom: 1em; }
        .rich-text-content ol { list-style-type: decimal; padding-left: 1.625em; margin-top: 1em; margin-bottom: 1em; }
        .rich-text-content li { margin-top: 0.5em; margin-bottom: 0.5em; padding-left: 0.375em; }
        .rich-text-content blockquote { border-left: 4px solid #e5e7eb; padding-left: 1em; color: #4b5563; margin: 1.5em 0; font-style: italic; }
        .rich-text-content a { color: #FF6B35; text-decoration: underline; font-weight: 500; }
        .rich-text-content img { max-width: 100%; height: auto; border-radius: 0.5rem; margin: 1.5em 0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .rich-text-content strong, .rich-text-content b { font-weight: 700; color: #111827; }
        .rich-text-content em, .rich-text-content i { font-style: italic; }
        .rich-text-content u { text-decoration: underline; }
        .rich-text-content s, .rich-text-content strike { text-decoration: line-through; color: #6b7280; }
        .rich-text-content pre { background-color: #f3f4f6; padding: 1em; border-radius: 0.5rem; overflow-x: auto; font-family: monospace; font-size: 0.875em; margin: 1em 0; }
        .rich-text-content code { background-color: #f3f4f6; padding: 0.2em 0.4em; border-radius: 0.25rem; font-family: monospace; font-size: 0.875em; color: #ef4444; }
        
        /* Quill Alignment Classes */
        .rich-text-content .ql-align-center { text-align: center; }
        .rich-text-content .ql-align-right { text-align: right; }
        .rich-text-content .ql-align-justify { text-align: justify; }
        .rich-text-content .ql-indent-1 { padding-left: 3em; }
        .rich-text-content .ql-indent-2 { padding-left: 6em; }
        .rich-text-content .ql-indent-3 { padding-left: 9em; }
    </style>

    <!-- Per-page styles -->
    @stack('styles')

    <!-- Livewire Styles -->
    @livewireStyles

    @php
        $metaPixelId = \App\Models\AppSetting::get('meta_pixel_id');
        $metaPixelEnabled = \App\Models\AppSetting::get('meta_pixel_enabled');
    @endphp

    @if($metaPixelEnabled && $metaPixelId)
        <!-- Meta Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $metaPixelId }}');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ $metaPixelId }}&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Meta Pixel Code -->
    @endif
</head>
<body class="bg-gray-100">

    <div class="max-w-[460px] mx-auto min-h-screen bg-light relative">
        {{ $slot }}
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>

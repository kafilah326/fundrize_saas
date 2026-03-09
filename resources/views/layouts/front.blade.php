@php
    $foundation = \App\Models\FoundationSetting::first();
    $foundationName = $foundation->name ?? 'Yayasan Peduli';
    $foundationAbout = strip_tags($foundation->about ?? '');
    $defaultDescription =
        $foundationAbout ?: 'Platform penggalangan dana online terpercaya untuk membantu sesama yang membutuhkan.';

    // OG Image: ensure always absolute URL, fallback to default-og.jpg
    $ogImage = isset($metaImage) && $metaImage ? $metaImage : asset('images/default-og.jpg');
    if (!str_starts_with($ogImage, 'http')) {
        $ogImage = url($ogImage);
    }
@endphp
<!DOCTYPE html>
<html lang="id" prefix="og: http://ogp.me/ns#">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO & Social Media Meta Tags -->
    <title>{{ $title ?? $foundationName }}</title>
    <meta name="description" content="{{ $metaDescription ?? $defaultDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? $foundationName }}">
    <meta property="og:description" content="{{ $metaDescription ?? $defaultDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:alt" content="{{ $title ?? $foundationName }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="{{ $foundationName }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? $foundationName }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? $defaultDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <meta name="twitter:image:alt" content="{{ $title ?? $foundationName }}">

    <meta itemprop="image" content="{{ $ogImage }}">
    <link rel="image_src" href="{{ $ogImage }}">
    @stack('meta')

    @php
        $primaryColor = \App\Models\AppSetting::get('theme_color', '#FF6B35');
        // Calculate a lighter shade for backgrounds (similar to orange-50/100)
        // If secondary_color is set, use it. Otherwise, calculate a tint.
        $secondaryColor = \App\Models\AppSetting::get('secondary_color');

        if (!$secondaryColor) {
            // Simple tint generation
            $hex = ltrim($primaryColor, '#');
            if (strlen($hex) == 3) {
                $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
            }
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));

            // Mix with white (90% white)
            $r = (int) ($r + (255 - $r) * 0.9);
            $g = (int) ($g + (255 - $g) * 0.9);
            $b = (int) ($b + (255 - $b) * 0.9);

            $secondaryColor = sprintf('#%02x%02x%02x', $r, $g, $b);
        }
    @endphp
    @if ($foundation && $foundation->favicon)
        <link rel="shortcut icon" href="{{ $foundation->favicon }}">
        <link rel="icon" type="image/png" href="{{ $foundation->favicon }}">
        <link rel="apple-touch-icon" href="{{ $foundation->favicon }}">
    @endif

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ $primaryColor }}',
                        secondary: '{{ $secondaryColor }}', // Used as a tint/background color
                        dark: '#1A1A1A',
                        light: '#F8F9FA',
                        // Override orange-50 to use our secondary color (or a very light version of primary)
                        orange: {
                            50: '{{ $secondaryColor }}',
                            100: '{{ $secondaryColor }}', // Fallback for slightly darker tints if needed
                        }
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
        * {
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Rich Text Content Styling (Quill Restore) */
        .rich-text-content {
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
            max-width: 100%;
            overflow: hidden;
        }

        .rich-text-content table {
            display: block;
            max-width: 100%;
            overflow-x: auto;
            border-collapse: collapse;
        }

        .rich-text-content iframe {
            max-width: 100%;
        }

        .rich-text-content h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-top: 1.5em;
            margin-bottom: 0.75em;
            line-height: 1.25;
            color: #111827;
        }

        .rich-text-content h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 1.25em;
            margin-bottom: 0.75em;
            line-height: 1.33;
            color: #1f2937;
        }

        .rich-text-content h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 1em;
            margin-bottom: 0.6em;
            line-height: 1.6;
            color: #374151;
        }

        .rich-text-content h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 0.9em;
            margin-bottom: 0.5em;
            line-height: 1.5;
            color: #374151;
        }

        .rich-text-content h5 {
            font-size: 1rem;
            font-weight: 600;
            margin-top: 0.8em;
            margin-bottom: 0.4em;
            line-height: 1.5;
            color: #374151;
        }

        .rich-text-content h6 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 0.8em;
            margin-bottom: 0.4em;
            line-height: 1.5;
            color: #374151;
        }

        .rich-text-content p {
            margin-bottom: 1em;
            line-height: 1.75;
            color: #374151;
        }

        .rich-text-content ul {
            list-style-type: disc;
            padding-left: 1.625em;
            margin-top: 1em;
            margin-bottom: 1em;
        }

        .rich-text-content ol {
            list-style-type: decimal;
            padding-left: 1.625em;
            margin-top: 1em;
            margin-bottom: 1em;
        }

        .rich-text-content li {
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            padding-left: 0.375em;
            display: list-item;
        }

        .rich-text-content ul ul,
        .rich-text-content ol ul {
            list-style-type: circle;
            padding-left: 1.625em;
        }

        .rich-text-content ul ul ul {
            list-style-type: square;
        }

        .rich-text-content ol ol {
            list-style-type: lower-latin;
            padding-left: 1.625em;
        }

        .rich-text-content blockquote {
            border-left: 4px solid #e5e7eb;
            padding-left: 1em;
            color: #4b5563;
            margin: 1.5em 0;
            font-style: italic;
        }

        .rich-text-content a {
            color: #FF6B35;
            text-decoration: underline;
            font-weight: 500;
        }

        .rich-text-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1.5em 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .rich-text-content strong,
        .rich-text-content b {
            font-weight: 700;
            color: #111827;
        }

        .rich-text-content em,
        .rich-text-content i {
            font-style: italic;
        }

        .rich-text-content u {
            text-decoration: underline;
        }

        .rich-text-content s,
        .rich-text-content strike {
            text-decoration: line-through;
            color: #6b7280;
        }

        .rich-text-content sub {
            vertical-align: sub;
            font-size: 0.75em;
        }

        .rich-text-content sup {
            vertical-align: super;
            font-size: 0.75em;
        }

        .rich-text-content pre {
            background-color: #f3f4f6;
            padding: 1em;
            border-radius: 0.5rem;
            overflow-x: auto;
            font-family: monospace;
            font-size: 0.875em;
            margin: 1em 0;
            white-space: pre-wrap;
        }

        .rich-text-content code {
            background-color: #f3f4f6;
            padding: 0.2em 0.4em;
            border-radius: 0.25rem;
            font-family: monospace;
            font-size: 0.875em;
            color: #ef4444;
        }

        /* Quill Alignment Classes */
        .rich-text-content .ql-align-center {
            text-align: center;
        }

        .rich-text-content .ql-align-right {
            text-align: right;
        }

        .rich-text-content .ql-align-justify {
            text-align: justify;
        }

        /* Quill Indent Classes */
        .rich-text-content .ql-indent-1 {
            padding-left: 3em;
        }

        .rich-text-content .ql-indent-2 {
            padding-left: 6em;
        }

        .rich-text-content .ql-indent-3 {
            padding-left: 9em;
        }

        .rich-text-content .ql-indent-4 {
            padding-left: 12em;
        }

        .rich-text-content .ql-indent-5 {
            padding-left: 15em;
        }

        .rich-text-content .ql-indent-6 {
            padding-left: 18em;
        }

        /* Quill Font Size Classes */
        .rich-text-content .ql-size-small {
            font-size: 0.75em;
        }

        .rich-text-content .ql-size-large {
            font-size: 1.5em;
        }

        .rich-text-content .ql-size-huge {
            font-size: 2.5em;
        }

        /* Quill Code Block */
        .rich-text-content .ql-syntax {
            background-color: #1e293b;
            color: #e2e8f0;
            padding: 1em;
            border-radius: 0.5rem;
            overflow-x: auto;
            font-family: monospace;
            font-size: 0.875em;
            margin: 1em 0;
            white-space: pre-wrap;
            display: block;
        }

        /* Quill Video */
        .rich-text-content .ql-video {
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 0.5rem;
            margin: 1em 0;
        }
    </style>

    <!-- Per-page styles -->
    @stack('styles')

    <!-- Livewire Styles -->
    @livewireStyles

    @php
        $metaPixelId = \App\Models\AppSetting::get('meta_pixel_id');
        $metaPixelEnabled = \App\Models\AppSetting::get('meta_pixel_enabled');
    @endphp

    @if ($metaPixelEnabled && $metaPixelId)
        <!-- Meta Pixel Code -->
        <script>
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $metaPixelId }}');
            fbq('track', 'PageView');

            // Track PageView on Livewire navigation
            document.addEventListener('livewire:navigated', function() {
                fbq('track', 'PageView');
            });
        </script>
        <noscript><img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ $metaPixelId }}&ev=PageView&noscript=1" /></noscript>
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

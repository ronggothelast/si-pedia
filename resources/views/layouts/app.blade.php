@props(['title' => 'SI-Pedia — Ensiklopedia Sistem Informasi', 'active' => '', 'footer' => 'full', 'meta_description' => 'Ensiklopedia Sistem Informasi - Temukan artikel, dosen, dan informasi akademik terlengkap.', 'og_image' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>
    <meta name="description" content="{{ $meta_description }}">
    <meta name="robots" content="index, follow">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $meta_description }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="SI-Pedia">
    @if($og_image)
        <meta property="og:image" content="{{ $og_image }}">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes fadeInDown {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fadeInDown 0.5s ease-out; }
    </style>

    @stack('meta')
</head>
<body class="min-h-screen flex flex-col bg-white text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100 font-sans">
    <x-flash-messages />
    <x-navbar :active="$active" />
    <div class="flex-1">
        {{ $slot }}
    </div>
    @if($footer === 'full')
        <x-footer />
    @endif
</body>
</html>

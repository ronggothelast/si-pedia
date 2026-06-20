@props(['active' => '', 'footer' => 'none', 'title' => 'SI-Pedia'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white font-sans text-gray-900 antialiased">
    <x-navbar :active="$active" />

    {{ $slot }}

    @if ($footer === 'full')
        <x-footer />
    @elseif ($footer === 'min')
        <x-footer-min />
    @endif
</body>
</html>

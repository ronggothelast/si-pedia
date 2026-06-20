{{-- Layout khusus halaman autentikasi: panel navy kiri + slot form di kanan --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SI-Pedia' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans">
    <div class="flex min-h-screen">

        {{-- LEFT: hero panel --}}
        <div class="relative hidden md:flex w-[43%] flex-col items-center justify-center rounded-r-[40px] bg-ink-900 px-10 text-center shadow-2xl">
            <img src="{{ asset('images/auth-illustration.png') }}" alt="Encyclopedia Information System"
                 class="w-[400px] max-w-full">
            <h1 class="mt-2 text-5xl font-extrabold leading-[1.1] text-white">ENCYCLOPEDIA</h1>
            <h2 class="text-5xl font-extrabold leading-[1.1] text-[#c5c8da]">Information System</h2>
            <p class="mt-5 text-lg text-[#9aa0b5]">Explore the digital database.</p>
        </div>

        {{-- RIGHT: form --}}
        <div class="flex flex-1 items-center justify-center px-8 py-12">
            <div class="w-full max-w-[460px]">
                {{ $slot }}
            </div>
        </div>

    </div>
</body>
</html>

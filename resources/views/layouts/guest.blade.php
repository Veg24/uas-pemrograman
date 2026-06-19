<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lumière Dining') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF7F2] font-sans antialiased text-[#1A1A1A]">
    <div class="min-h-screen flex flex-col md:flex-row">
        
        <!-- Left Side: luxury background overlay -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center items-end p-12 relative overflow-hidden border-r border-[#C8882A]/30" style="background-image: url('{{ asset('images/login_food_side.png') }}');">
            <!-- Dark overlay to ensure text contrast -->
            <div class="absolute inset-0 bg-black/40"></div>

            <!-- Fork & Knife Icon top left -->
            <div class="absolute top-12 left-12 z-10 text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <!-- Elegant fork and knife outline -->
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 2v6c0 1.66-1.34 3-3 3s-3-1.34-3-3V2m6 0H8m-4 0h2m13 0v18m0-18c-1.66 0-3 1.34-3 3v4c0 1.66 1.34 3 3 3m0-10h-1m1-4v14" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 11v9" />
                </svg>
            </div>

            <!-- Text bottom left -->
            <div class="relative z-10 text-left max-w-lg mb-12 ml-4">
                <h2 class="font-serif text-white text-5xl font-normal italic tracking-wide leading-tight">Taste the moment.</h2>
            </div>
        </div>

        <!-- Right Side: authentication forms -->
        <div class="flex-1 flex items-center justify-center p-6 md:p-16 bg-[#FAF7F2]">
            <div class="w-full max-w-md space-y-8">
                {{ $slot }}
            </div>
        </div>

    </div>

    <!-- Alert System -->
    <x-alert />
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lumière Dining') }}</title>

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF7F2] font-sans antialiased text-[#1A1A1A]">
    <div class="min-h-screen flex flex-col md:flex-row">
        
        <!-- Left Side: luxury background overlay -->
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-[#1A1A1A] via-[#4A3728] to-[#1A1A1A] items-center justify-center p-12 relative overflow-hidden border-r border-[#C8882A]/30">
            <!-- Subtle Gold Ring Background Pattern -->
            <div class="absolute inset-0 opacity-15">
                <svg class="w-full h-full text-[#C8882A]" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="0.1" />
                    <circle cx="50" cy="50" r="30" fill="none" stroke="currentColor" stroke-width="0.05" />
                    <line x1="50" y1="0" x2="50" y2="100" stroke="currentColor" stroke-width="0.05" />
                    <line x1="0" y1="50" x2="100" y2="50" stroke="currentColor" stroke-width="0.05" />
                </svg>
            </div>
            
            <div class="relative z-10 text-center space-y-6 max-w-lg">
                <div class="w-24 h-[1px] bg-[#C8882A] mx-auto"></div>
                <h2 class="font-serif text-white text-5xl font-bold italic tracking-wide leading-tight">Taste the moment.</h2>
                <div class="w-12 h-[1px] bg-[#C8882A] mx-auto"></div>
                <p class="font-body text-[#D4C9BB] text-sm tracking-widest uppercase mt-4">Lumière Dining Experience</p>
            </div>
            
            <!-- Bottom brand signature -->
            <div class="absolute bottom-10 left-12 right-12 flex justify-between items-center text-[10px] uppercase tracking-widest text-[#AB9BB0]">
                <span>Est. 2026</span>
                <span>Semarang, Indonesia</span>
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

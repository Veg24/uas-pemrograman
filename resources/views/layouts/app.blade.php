<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ mobileSidebarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
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
<body class="bg-[#FAF7F2] dark:bg-[#121212] font-sans antialiased text-[#1A1A1A] dark:text-[#FAF7F2]">
    <div class="min-h-screen flex flex-col md:flex-row">
        
        <!-- Mobile Top Navbar -->
        <div class="md:hidden bg-[#1A1A1A] text-white px-4 py-3 flex items-center justify-between border-b border-[#4A3728]">
            <div class="flex items-center space-x-2">
                <span class="font-serif text-lg font-bold tracking-widest text-[#C8882A]">LUMIÈRE</span>
            </div>
            <div class="flex items-center space-x-2">
                <!-- Dark Mode Toggle Mobile -->
                <button 
                    @click="darkMode = !darkMode" 
                    class="p-2 text-[#D4C9BB] hover:text-white rounded-lg focus:outline-none"
                    title="Toggle Dark Mode"
                >
                    <svg x-show="darkMode" class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.364 17.636l-.707.707M16.243 17.636l.707-.707M7.757 6.364l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                    <svg x-show="!darkMode" class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="text-white focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileSidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileSidebarOpen" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-40 w-64 bg-[#3D2D1E] text-white flex flex-col justify-between transform md:translate-x-0 transition-transform duration-300 ease-in-out border-r border-[#4A3728]/30"
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full md:relative md:translate-x-0'"
        >
            <div>
                <!-- Sidebar Header / Logo -->
                <div class="p-6 flex items-center space-x-3 border-b border-[#4A3728]/50">
                    <div class="w-8 h-8 rounded-full bg-[#C8882A] flex items-center justify-center font-serif text-white font-bold text-sm shadow-inner">
                        L
                    </div>
                    <span class="font-serif text-lg font-bold tracking-wide text-white">Lumière Dining</span>
                </div>

                <!-- User Profile Section -->
                <div class="p-6 flex items-center justify-between border-b border-[#4A3728]/35">
                    <div class="flex items-center space-x-3 overflow-hidden">
                        <div class="relative flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-[#C8882A] flex items-center justify-center font-bold font-serif text-white text-lg">
                                {{ substr(Auth::user()->nama, 0, 1) }}
                            </div>
                            <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-[#4CAF82] rounded-full border-2 border-[#3D2D1E]"></div>
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="text-sm font-semibold truncate text-white">{{ Auth::user()->nama }}</h4>
                            <p class="text-[11px] text-[#AB9BB0] truncate font-medium">Pelanggan</p>
                        </div>
                    </div>
                    
                    <!-- Dark/Light Mode Button -->
                    <button 
                        @click="darkMode = !darkMode" 
                        class="p-2 text-[#D4C9BB] hover:text-white rounded-lg hover:bg-white/5 transition-all focus:outline-none flex-shrink-0"
                        title="Toggle Dark/Light Mode"
                    >
                        <!-- Sun Icon (shown in Dark mode) -->
                        <svg x-show="darkMode" class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.364 17.636l-.707.707M16.243 17.636l.707-.707M7.757 6.364l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                        <!-- Moon Icon (shown in Light mode) -->
                        <svg x-show="!darkMode" class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1.5">
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-150 {{ Request::routeIs('dashboard') ? 'bg-[#C8882A] text-white shadow-md' : 'text-[#D4C9BB] hover:bg-[#FAF7F2]/5 hover:text-white' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Dashboard</span>
                    </a>
                    <a 
                        href="{{ route('reservasi.index') }}" 
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-150 {{ Request::routeIs('reservasi.*') ? 'bg-[#C8882A] text-white shadow-md' : 'text-[#D4C9BB] hover:bg-[#FAF7F2]/5 hover:text-white' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Reservasi Meja</span>
                    </a>
                    <a 
                        href="{{ route('pesan.index') }}" 
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-150 {{ Request::routeIs('pesan.*') ? 'bg-[#C8882A] text-white shadow-md' : 'text-[#D4C9BB] hover:bg-[#FAF7F2]/5 hover:text-white' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span class="flex-1">Pesan Makanan</span>
                        <span id="cart-badge-count" class="bg-[#C8882A] text-white text-[10px] font-bold px-2 py-0.5 rounded-full border border-white/20" style="display: none;">0</span>
                    </a>
                    <a 
                        href="{{ route('riwayat') }}" 
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-150 {{ Request::routeIs('riwayat') ? 'bg-[#C8882A] text-white shadow-md' : 'text-[#D4C9BB] hover:bg-[#FAF7F2]/5 hover:text-white' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Riwayat Aktivitas</span>
                    </a>
                </nav>
            </div>

            <!-- Logout Section -->
            <div class="p-4 border-t border-[#4A3728]/50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold text-[#E95252] hover:bg-[#E95252]/10 transition-all duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay to close mobile sidebar -->
        <div 
            x-show="mobileSidebarOpen" 
            @click="mobileSidebarOpen = false" 
            class="fixed inset-0 z-30 bg-black/50 md:hidden transition-opacity duration-300"
            style="display: none;"
        ></div>

        <!-- Main Content Area -->
        <main class="flex-1 p-6 md:p-10 overflow-y-auto max-w-7xl mx-auto w-full">
            {{ $slot }}
        </main>
    </div>

    <!-- Alert System -->
    <x-alert />
</body>
</html>

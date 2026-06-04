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
<body class="bg-[#FAF7F2] font-sans antialiased text-[#1A1A1A]" x-data="{ mobileSidebarOpen: false }">
    <div class="min-h-screen flex flex-col md:flex-row">
        
        <!-- Mobile Top Navbar -->
        <div class="md:hidden bg-[#1A1A1A] text-white px-4 py-3 flex items-center justify-between border-b border-[#4A3728]">
            <div class="flex items-center space-x-2">
                <span class="font-serif text-lg font-bold tracking-widest text-[#C8882A]">LUMIÈRE</span>
            </div>
            <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="text-white focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!mobileSidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="mobileSidebarOpen" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-40 w-64 bg-[#1A1A1A] text-white flex flex-col justify-between transform md:translate-x-0 transition-transform duration-300 ease-in-out border-r border-[#4A3728]"
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full md:relative md:translate-x-0'"
        >
            <div>
                <!-- Sidebar Header / Logo -->
                <div class="p-6 text-center border-b border-[#4A3728]/50">
                    <h1 class="font-serif text-2xl font-black tracking-widest text-[#C8882A]">LUMIÈRE</h1>
                    <p class="text-[9px] uppercase tracking-widest text-[#AB9BB0] mt-1">Fine Dining & Bistro</p>
                </div>

                <!-- User Profile Section -->
                <div class="p-6 flex items-center space-x-3 border-b border-[#4A3728]/35">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-[#C8882A] flex items-center justify-center font-bold font-serif text-white text-lg">
                            {{ substr(Auth::user()->nama, 0, 1) }}
                        </div>
                        <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-[#4CAF82] rounded-full border-2 border-[#1A1A1A]"></div>
                    </div>
                    <div class="overflow-hidden">
                        <h4 class="text-sm font-semibold truncate">{{ Auth::user()->nama }}</h4>
                        <p class="text-[11px] text-[#AB9BB0] truncate">{{ Auth::user()->email }}</p>
                    </div>
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
                        <span>Logout</span>
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

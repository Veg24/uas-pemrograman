<x-app-layout>
    <!-- Header -->
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-[#E8E0D5]">
        <div>
            <h1 class="font-serif text-3xl font-black text-[#1A1A1A]">Selamat datang, {{ Auth::user()->nama }}</h1>
            <p class="text-sm font-body text-[#7A6A58] mt-1">{{ Carbon\Carbon::today()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
        </div>
        
        <!-- Notification icon -->
        <button class="relative p-2 text-[#7A6A58] hover:text-[#C8882A] hover:bg-white rounded-full transition-all border border-[#E8E0D5] bg-white sm:self-start">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-1.5 right-1.5 block h-2.5 w-2.5 rounded-full bg-[#E95252] ring-2 ring-white"></span>
        </button>
    </header>

    <!-- Content Grid -->
    <div class="mt-8 space-y-8">
        
        <!-- Hero Section: Upcoming Reservasi -->
        @if ($upcomingReservasi)
            <div class="bg-[#1A1A1A] text-white rounded-2xl p-6 md:p-8 relative overflow-hidden border border-[#C8882A]/30 shadow-lg">
                <!-- Background Gold Accent -->
                <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-[#C8882A]/10 to-transparent pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-3">
                        <x-badge variant="warning" class="bg-[#C8882A]/20 text-[#C8882A] border-[#C8882A]/30">
                            Reservasi Mendatang
                        </x-badge>
                        <h2 class="font-serif text-2xl md:text-3xl font-bold tracking-wide">
                            Meja #{{ $upcomingReservasi->meja->nomor_meja }} — {{ ucfirst($upcomingReservasi->meja->area) }}
                        </h2>
                        <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-[#D4C9BB] pt-1">
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4 text-[#C8882A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ $upcomingReservasi->tanggal->format('d M Y') }}</span>
                            </span>
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4 text-[#C8882A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ substr($upcomingReservasi->jam, 0, 5) }} WIB</span>
                            </span>
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4 text-[#C8882A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>{{ $upcomingReservasi->jumlah_tamu }} Tamu</span>
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex flex-row md:flex-col items-start md:items-end justify-between md:justify-center gap-4">
                        <div class="bg-[#C8882A] text-white font-mono px-4 py-2 rounded-xl text-center shadow-md">
                            <span class="block text-xs uppercase tracking-wider text-white/70">Waktu</span>
                            <span class="text-sm font-bold">{{ $countdown }}</span>
                        </div>
                        <a href="{{ route('reservasi.show', $upcomingReservasi->id) }}">
                            <x-button variant="secondary" size="sm">
                                Detail Reservasi
                            </x-button>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Hero CTA: No Reservations -->
            <div class="bg-gradient-to-r from-[#1A1A1A] to-[#4A3728] text-white rounded-2xl p-6 md:p-8 relative overflow-hidden border border-[#C8882A]/30 shadow-lg">
                <div class="relative z-10 space-y-4 max-w-lg">
                    <x-badge variant="info" class="bg-[#3B82F6]/20 text-[#3B82F6] border-[#3B82F6]/30">Lumière Dining</x-badge>
                    <h2 class="font-serif text-2xl md:text-3xl font-bold tracking-wide">Belum ada meja yang dipesan</h2>
                    <p class="text-sm text-[#D4C9BB] leading-relaxed">Nikmati pengalaman kuliner mewah kelas dunia dengan layanan terbaik. Pesan meja Anda di area Indoor ber-AC yang intim atau area Terrace yang asri.</p>
                    <div class="pt-2">
                        <a href="{{ route('reservasi.index') }}">
                            <x-button variant="primary" size="md">
                                Pesan Meja Sekarang →
                            </x-button>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Section -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <x-card class="flex items-center space-x-4">
                <div class="p-3 bg-[#FAF7F2] rounded-xl text-[#C8882A] border border-[#E8E0D5]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-[#7A6A58] uppercase tracking-wider">Total Reservasi</p>
                    <h3 class="text-2xl font-black text-[#1A1A1A] mt-1">{{ $totalReservasi }}</h3>
                </div>
            </x-card>
            
            <x-card class="flex items-center space-x-4">
                <div class="p-3 bg-[#FAF7F2] rounded-xl text-[#3B82F6] border border-[#E8E0D5]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-[#7A6A58] uppercase tracking-wider">Pesanan Aktif</p>
                    <h3 class="text-2xl font-black text-[#1A1A1A] mt-1">{{ $pesananAktif }}</h3>
                </div>
            </x-card>

            <x-card class="flex items-center space-x-4">
                <div class="p-3 bg-[#FAF7F2] rounded-xl text-[#4CAF82] border border-[#E8E0D5]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-[#7A6A58] uppercase tracking-wider">Pesanan Selesai</p>
                    <h3 class="text-2xl font-black text-[#1A1A1A] mt-1">{{ $pesananSelesai }}</h3>
                </div>
            </x-card>
        </div>

        <!-- Lower Grid: Popular Menu & Quick Activity Logs -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Popular Menu Section (2/3 width on large screens) -->
            <div class="lg:col-span-2 space-y-4">
                <h3 class="font-serif text-xl font-bold text-[#1A1A1A]">Menu Populer</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach ($menuPopuler as $menu)
                        <div class="bg-white border border-[#E8E0D5] rounded-2xl overflow-hidden shadow-sm flex flex-col justify-between transition-transform duration-200 hover:-translate-y-1">
                            <!-- Image placeholder with elegant gradient -->
                            <div class="h-32 bg-gradient-to-tr from-[#1A1A1A] to-[#4A3728] relative flex items-center justify-center p-4">
                                <span class="text-center font-serif text-white/90 text-sm font-semibold tracking-wide">{{ $menu->nama_menu }}</span>
                                <span class="absolute top-2 right-2 bg-[#C8882A] text-white text-[9px] font-bold px-2 py-0.5 rounded-full">POPULER</span>
                            </div>
                            
                            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-[#AB9BB0]">{{ str_replace('_', ' ', $menu->kategori) }}</span>
                                    <p class="text-xs text-[#7A6A58] line-clamp-2 mt-1">{{ $menu->deskripsi }}</p>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-[#F5F1EB]">
                                    <span class="font-semibold text-xs font-mono text-[#C8882A]">Rp{{ number_format($menu->harga, 0, ',', '.') }}</span>
                                    <a href="{{ route('pesan.index') }}" class="text-[11px] font-semibold text-[#1A1A1A] hover:text-[#C8882A] underline">Pesan</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Activity Logs Section (1/3 width) -->
            <div class="space-y-4">
                <h3 class="font-serif text-xl font-bold text-[#1A1A1A]">Aktivitas Terakhir</h3>
                
                <x-card class="p-4">
                    @if ($riwayatSingkat->isEmpty())
                        <div class="text-center py-6 text-sm text-[#7A6A58]">
                            Belum ada riwayat aktivitas.
                        </div>
                    @else
                        <ul class="space-y-4">
                            @foreach ($riwayatSingkat as $log)
                                <li class="flex items-start space-x-3 text-xs">
                                    <!-- Activity specific icon indicator -->
                                    <div class="p-1.5 rounded-lg mt-0.5
                                        @if($log->aksi == 'Login') bg-[#ECF3FE] text-[#3B82F6] 
                                        @elseif($log->aksi == 'Registrasi') bg-[#EAF7F1] text-[#4CAF82] 
                                        @elseif($log->aksi == 'Reservasi Meja') bg-[#FEF6EB] text-[#C8882A] 
                                        @elseif($log->aksi == 'Pemesanan Makanan') bg-[#EAF7F1] text-[#4CAF82] 
                                        @else bg-[#FDF1F1] text-[#E95252] @endif">
                                        
                                        @if($log->aksi == 'Login')
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        @elseif($log->aksi == 'Registrasi')
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 overflow-hidden">
                                        <p class="font-bold text-[#1A1A1A] truncate">{{ $log->aksi }}</p>
                                        <p class="text-[#7A6A58] mt-0.5 leading-relaxed">{{ $log->keterangan }}</p>
                                        <span class="block text-[10px] text-[#AB9BB0] font-mono mt-1">{{ $log->timestamp->diffForHumans() }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </x-card>
            </div>

        </div>

    </div>
</x-app-layout>

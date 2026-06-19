<x-app-layout>
    <!-- Header -->
    <header class="flex items-center justify-between pb-6 border-b border-[#E8E0D5]">
        <div>
            <h1 class="font-serif text-2xl font-bold text-[#1A1A1A] tracking-tight">Selamat datang, {{ Auth::user()->nama }} 👋</h1>
        </div>
        
        <div class="flex items-center space-x-4">
            <span class="text-xs uppercase font-bold tracking-widest text-[#7A6A58]">{{ Carbon\Carbon::today()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
            <!-- Notification icon -->
            <button class="relative p-2 text-[#7A6A58] hover:text-[#C8882A] hover:bg-white rounded-full transition-all border border-[#E8E0D5] bg-white">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-[#E95252] ring-2 ring-white"></span>
            </button>
        </div>
    </header>

    <!-- Content Grid -->
    <div class="mt-8 space-y-8">
        
        <!-- Hero Section: Upcoming Reservasi -->
        @if ($upcomingReservasi)
            @php
                $countdownParts = explode(' ', $countdown);
                $number = $countdownParts[0] ?? '';
                $unit = isset($countdownParts[1]) ? implode(' ', array_slice($countdownParts, 1)) : '';
            @endphp
            <div class="bg-cover bg-center text-white rounded-2xl p-6 md:p-8 relative overflow-hidden border border-[#C8882A]/30 shadow-lg" style="background-image: url('{{ asset('images/upcoming_reservasi_bg.png') }}');">
                <!-- Dark Overlay -->
                <div class="absolute inset-0 bg-[#1A1A1A]/70 pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-4">
                        <div class="flex flex-wrap gap-2">
                            <span class="bg-black/40 border border-white/20 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                Reservasi Berikutnya
                            </span>
                            <span class="bg-[#C8882A] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                Dikonfirmasi
                            </span>
                        </div>
                        
                        <h2 class="font-serif text-3xl font-bold tracking-wide">
                            {{ Carbon\Carbon::parse($upcomingReservasi->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                        </h2>
                        
                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-200">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-[#C8882A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ substr($upcomingReservasi->jam, 0, 5) }} WIB</span>
                            </span>
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-[#C8882A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span>Meja {{ $upcomingReservasi->meja->nomor_meja }} · {{ $upcomingReservasi->jumlah_tamu }} orang</span>
                            </span>
                        </div>
                    </div>
                    
                    <div class="text-left md:text-right">
                        <span class="block text-xs uppercase tracking-widest text-gray-300 font-semibold">Menunggu waktu</span>
                        @if(is_numeric($number))
                            <div class="flex items-baseline md:justify-end space-x-1 mt-1">
                                <span class="text-6xl font-extrabold text-[#C8882A] leading-none">{{ $number }}</span>
                                <span class="text-sm font-bold text-white uppercase tracking-wider">{{ $unit }}</span>
                            </div>
                        @else
                            <span class="text-2xl font-bold text-white block mt-1">{{ $countdown }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <!-- Hero CTA: No Reservations -->
            <div class="bg-cover bg-center text-white rounded-2xl p-6 md:p-8 relative overflow-hidden border border-[#C8882A]/30 shadow-lg" style="background-image: url('{{ asset('images/upcoming_reservasi_bg.png') }}');">
                <!-- Dark Overlay -->
                <div class="absolute inset-0 bg-[#1A1A1A]/70 pointer-events-none"></div>
                
                <div class="relative z-10 space-y-4 max-w-lg">
                    <span class="bg-[#C8882A] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Lumière Dining</span>
                    <h2 class="font-serif text-2xl md:text-3xl font-bold tracking-wide">Belum ada meja yang dipesan</h2>
                    <p class="text-sm text-gray-200 leading-relaxed">Nikmati pengalaman kuliner mewah kelas dunia dengan layanan terbaik. Pesan meja Anda di area Indoor ber-AC yang intim atau area Terrace yang asri.</p>
                    <div class="pt-2">
                        <a href="{{ route('reservasi.index') }}">
                            <button class="bg-[#C8882A] hover:bg-[#B67720] text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-150">
                                Pesan Meja Sekarang
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Section -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white border border-[#E8E0D5] rounded-2xl p-6 flex items-center space-x-4 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-[#7A6A58] border border-[#E8E0D5]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-[#AB9BB0] uppercase tracking-wider">Total Reservasi</p>
                    <h3 class="text-3xl font-black text-[#1A1A1A] mt-1">{{ $totalReservasi }}</h3>
                </div>
            </div>
            
            <div class="bg-white border border-[#E8E0D5] rounded-2xl p-6 flex items-center space-x-4 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-[#FEF6EB] flex items-center justify-center text-[#C8882A] border border-[#FEF6EB]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-[#AB9BB0] uppercase tracking-wider">Pesanan Aktif</p>
                    <h3 class="text-3xl font-black text-[#1A1A1A] mt-1">{{ $pesananAktif }}</h3>
                </div>
            </div>

            <div class="bg-white border border-[#E8E0D5] rounded-2xl p-6 flex items-center space-x-4 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-[#EAF7F1] flex items-center justify-center text-[#4CAF82] border border-[#EAF7F1]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-[#AB9BB0] uppercase tracking-wider">Pesanan Selesai</p>
                    <h3 class="text-3xl font-black text-[#1A1A1A] mt-1">{{ $pesananSelesai }}</h3>
                </div>
            </div>
        </div>

        <!-- Lower Grid: Popular Menu & Quick Activity Logs -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Popular Menu Section (2/3 width on large screens) -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-serif text-xl font-bold text-[#1A1A1A]">Menu Populer</h3>
                    <a href="{{ route('pesan.index') }}" class="text-xs font-semibold text-[#C8882A] hover:underline">Lihat Semua</a>
                </div>
                
                <div class="space-y-4">
                    @php
                        $categories = [
                            'makanan_utama' => 'Main Course',
                            'dessert' => 'Dessert',
                            'minuman' => 'Beverage',
                            'appetizer' => 'Appetizer'
                        ];
                    @endphp
                    @foreach ($menuPopuler as $menu)
                        <div class="bg-white border border-[#E8E0D5] rounded-2xl p-4 flex items-center justify-between shadow-sm hover:border-[#C8882A]/30 transition-all duration-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 border border-[#E8E0D5] flex-shrink-0">
                                    @if ($menu->image_url)
                                        <img src="{{ asset($menu->image_url) }}" alt="{{ $menu->nama_menu }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-[#3D2D1E] flex items-center justify-center text-white font-serif font-bold">
                                            {{ substr($menu->nama_menu, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-[#1A1A1A]">{{ $menu->nama_menu }}</h4>
                                    <p class="text-xs text-[#AB9BB0] mt-0.5">{{ $categories[$menu->kategori] ?? $menu->kategori }}</p>
                                </div>
                            </div>
                            <span class="font-bold text-sm text-[#C8882A]">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Activity Logs Section (1/3 width) -->
            <div class="space-y-4">
                <h3 class="font-serif text-xl font-bold text-[#1A1A1A]">Riwayat Singkat</h3>
                
                <div class="bg-white border border-[#E8E0D5] rounded-2xl p-6 shadow-sm">
                    @if ($riwayatSingkat->isEmpty())
                        <div class="text-center py-6 text-sm text-[#7A6A58]">
                            Belum ada riwayat aktivitas.
                        </div>
                    @else
                        <ul class="space-y-6 relative border-l border-[#E8E0D5]/70 pl-4 ml-2">
                            @foreach ($riwayatSingkat as $log)
                                <li class="relative">
                                    <!-- Timeline Dot -->
                                    <div class="absolute -left-[25px] mt-1 bg-white p-0.5 rounded-full z-10">
                                        <div class="w-3.5 h-3.5 rounded-full flex items-center justify-center text-[8px] font-bold 
                                            @if($log->aksi == 'Login') bg-[#ECF3FE] text-[#3B82F6] 
                                            @elseif($log->aksi == 'Registrasi') bg-[#EAF7F1] text-[#4CAF82] 
                                            @elseif($log->aksi == 'Reservasi Meja') bg-[#FEF6EB] text-[#C8882A] 
                                            @elseif($log->aksi == 'Pemesanan Makanan') bg-[#EAF7F1] text-[#4CAF82] 
                                            @else bg-[#FDF1F1] text-[#E95252] @endif">
                                            •
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h4 class="font-bold text-xs text-[#1A1A1A]">{{ $log->aksi }}</h4>
                                        <p class="text-[11px] text-[#7A6A58] mt-0.5 leading-relaxed">{{ $log->keterangan }}</p>
                                        <span class="block text-[9px] text-[#AB9BB0] mt-1">{{ $log->timestamp->diffForHumans() }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>

    </div>
</x-app-layout>

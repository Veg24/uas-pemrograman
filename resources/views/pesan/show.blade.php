<x-app-layout>
    <!-- Header -->
    <div class="flex items-center space-x-3 pb-6 border-b border-[#E8E0D5]">
        <a href="{{ route('riwayat') }}" class="p-2 bg-white border border-[#E8E0D5] rounded-xl text-[#7A6A58] hover:text-[#C8882A] transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="font-serif text-2xl font-black text-[#1A1A1A]">Detail Pesanan #{{ $pesanan->id }}</h1>
            <p class="text-xs font-body text-[#7A6A58] mt-0.5">Informasi pelacakan status hidangan dan rincian transaksi.</p>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left: Status Tracker & Item List (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Status Tracker -->
            <x-card class="p-6">
                <x-slot:headerSlot>
                    <h3 class="text-sm font-bold font-serif text-[#1A1A1A] uppercase tracking-wider">Status Pelacakan Pesanan</h3>
                </x-slot:headerSlot>

                @if ($pesanan->status === 'dibatalkan')
                    <div class="flex items-center space-x-3 bg-[#FDF1F1] text-[#E95252] p-4 rounded-xl border border-[#fbd8d8]">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-semibold font-body">Pesanan ini telah dibatalkan.</span>
                    </div>
                @else
                    <!-- Stepper Progress Tracker -->
                    <div class="relative py-4">
                        <div class="absolute left-4 top-4 bottom-4 w-0.5 bg-[#E8E0D5] md:left-auto md:top-1/2 md:left-6 md:right-6 md:h-0.5 md:w-auto -translate-y-1/2 z-0"></div>
                        
                        <div class="flex flex-col md:flex-row md:justify-between space-y-6 md:space-y-0 relative z-10 text-xs font-semibold font-body text-[#7A6A58]">
                            
                            <!-- Step 1: Diproses -->
                            <div class="flex items-center md:flex-col space-x-3 md:space-x-0 md:space-y-2">
                                <span class="w-8 h-8 rounded-full flex items-center justify-center border-2 transition-all duration-300
                                      bg-[#C8882A] text-white border-[#C8882A]">
                                    1
                                </span>
                                <div class="text-left md:text-center">
                                    <p class="font-bold text-[#1A1A1A]">Diproses</p>
                                    <p class="text-[10px] text-[#AB9BB0] mt-0.5">Dapur menyiapkan makanan</p>
                                </div>
                            </div>
                            
                            <!-- Step 2: Dikirim -->
                            <div class="flex items-center md:flex-col space-x-3 md:space-x-0 md:space-y-2">
                                <span class="w-8 h-8 rounded-full flex items-center justify-center border-2 transition-all duration-300
                                      {{ in_array($pesanan->status, ['dikirim', 'selesai']) 
                                          ? 'bg-[#C8882A] text-white border-[#C8882A]' 
                                          : 'bg-white text-[#7A6A58] border-[#D4C9BB]' }}">
                                    2
                                </span>
                                <div class="text-left md:text-center">
                                    <p class="font-bold" :class="'{{ in_array($pesanan->status, ['dikirim', 'selesai']) }}' ? 'text-[#1A1A1A]' : ''">
                                        {{ $pesanan->tipe === 'delivery' ? 'Pengiriman' : 'Disajikan' }}
                                    </p>
                                    <p class="text-[10px] text-[#AB9BB0] mt-0.5">
                                        {{ $pesanan->tipe === 'delivery' ? 'Kurir mengantar makanan' : 'Pelayan menyajikan makanan' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Step 3: Selesai -->
                            <div class="flex items-center md:flex-col space-x-3 md:space-x-0 md:space-y-2">
                                <span class="w-8 h-8 rounded-full flex items-center justify-center border-2 transition-all duration-300
                                      {{ $pesanan->status === 'selesai' 
                                          ? 'bg-[#4CAF82] text-white border-[#4CAF82]' 
                                          : 'bg-white text-[#7A6A58] border-[#D4C9BB]' }}">
                                    3
                                </span>
                                <div class="text-left md:text-center">
                                    <p class="font-bold" :class="'{{ $pesanan->status === 'selesai' }}' ? 'text-[#4CAF82]' : ''">Selesai</p>
                                    <p class="text-[10px] text-[#AB9BB0] mt-0.5">Pesanan telah diselesaikan</p>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif
            </x-card>

            <!-- Items List -->
            <x-card class="p-6">
                <x-slot:headerSlot>
                    <h3 class="text-sm font-bold font-serif text-[#1A1A1A] uppercase tracking-wider">Item Pesanan</h3>
                </x-slot:headerSlot>

                <div class="divide-y divide-[#F5F1EB] font-body">
                    @foreach ($pesanan->pesananItems as $item)
                        <div class="py-4 flex items-center justify-between text-sm">
                            <div class="space-y-0.5">
                                <p class="font-bold text-[#1A1A1A]">{{ $item->menu->nama_menu }}</p>
                                <p class="text-xs text-[#7A6A58]">
                                    {{ $item->jumlah }} x Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}
                                </p>
                            </div>
                            <span class="font-mono font-bold text-[#1A1A1A]">
                                Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </x-card>

        </div>

        <!-- Right: Transaction & Delivery Details (1/3 width) -->
        <div class="space-y-6">
            <x-card class="shadow-md">
                <x-slot:headerSlot>
                    <h3 class="text-sm font-bold font-serif text-[#1A1A1A] uppercase tracking-wider">Rincian Pembayaran</h3>
                </x-slot:headerSlot>

                <div class="space-y-3 font-body text-xs py-2">
                    <div class="flex justify-between items-center">
                        <span class="text-[#7A6A58]">Metode Pembayaran</span>
                        <span class="font-bold text-[#1A1A1A] uppercase">{{ $pesanan->metode_pembayaran }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[#7A6A58]">Tipe Pesanan</span>
                        <span class="font-bold text-[#1A1A1A]">{{ $pesanan->tipe === 'dine_in' ? 'Dine In' : 'Delivery' }}</span>
                    </div>
                    
                    @if ($pesanan->tipe === 'delivery')
                        <div class="pt-3 border-t border-[#F5F1EB] space-y-2">
                            <span class="block font-bold text-[#7A6A58] text-[10px] uppercase tracking-wider">Detail Pengiriman</span>
                            <div>
                                <span class="block text-[#AB9BB0] text-[9px] uppercase">Penerima</span>
                                <span class="font-bold text-[#1A1A1A] text-xs">{{ $pesanan->nama_penerima }}</span>
                            </div>
                            <div>
                                <span class="block text-[#AB9BB0] text-[9px] uppercase">Alamat</span>
                                <span class="text-[#7A6A58] text-xs leading-relaxed">{{ $pesanan->alamat_penerima }}</span>
                            </div>
                        </div>
                    @endif

                    @if ($pesanan->catatan)
                        <div class="pt-3 border-t border-[#F5F1EB] space-y-1.5">
                            <span class="block font-bold text-[#7A6A58] text-[10px] uppercase tracking-wider">Catatan Khusus</span>
                            <p class="text-[#4A3728] text-xs italic">"{{ $pesanan->catatan }}"</p>
                        </div>
                    @endif

                    <!-- Totals -->
                    <div class="pt-4 border-t border-[#F5F1EB] space-y-2 text-xs">
                        <div class="flex justify-between text-[#7A6A58]">
                            <span>Subtotal</span>
                            <span class="font-mono">Rp{{ number_format($pesanan->pesananItems->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                        
                        @php
                            $subtotal = $pesanan->pesananItems->sum('subtotal');
                            $diskon = $subtotal - $pesanan->total_harga;
                        @endphp
                        
                        @if ($diskon > 0)
                            <div class="flex justify-between text-[#E95252]">
                                <span>Potongan Promo</span>
                                <span class="font-mono">-Rp{{ number_format($diskon, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center text-sm font-bold border-t border-[#F5F1EB] pt-2 text-[#1A1A1A]">
                            <span>Total Bayar</span>
                            <span class="font-mono text-base text-[#C8882A]">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <a href="{{ route('pesan.index') }}" class="block">
                        <x-button variant="secondary" class="w-full">
                            Pesan Menu Lain
                        </x-button>
                    </a>
                </div>
            </x-card>
        </div>

    </div>
</x-app-layout>

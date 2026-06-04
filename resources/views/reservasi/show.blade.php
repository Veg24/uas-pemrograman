<x-app-layout>
    <!-- Header -->
    <div class="flex items-center space-x-3 pb-6 border-b border-[#E8E0D5]">
        <a href="{{ route('riwayat') }}" class="p-2 bg-white border border-[#E8E0D5] rounded-xl text-[#7A6A58] hover:text-[#C8882A] transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="font-serif text-2xl font-black text-[#1A1A1A]">Detail Reservasi #{{ $reservasi->id }}</h1>
            <p class="text-xs font-body text-[#7A6A58] mt-0.5">Informasi lengkap tiket pemesanan meja Anda.</p>
        </div>
    </div>

    <!-- Ticket Card -->
    <div class="mt-8 max-w-2xl mx-auto">
        <div class="bg-white rounded-3xl border border-[#E8E0D5] overflow-hidden shadow-lg relative">
            
            <!-- Top branding banner -->
            <div class="bg-[#1A1A1A] text-white p-6 text-center border-b border-[#C8882A]/30">
                <h2 class="font-serif text-xl font-bold tracking-widest text-[#C8882A]">LUMIÈRE DINING</h2>
                <p class="text-[9px] uppercase tracking-widest text-[#AB9BB0] mt-0.5">Reservation Ticket</p>
            </div>

            <!-- Decorative tickets holes side -->
            <div class="absolute top-[4.5rem] -left-3 w-6 h-6 bg-[#FAF7F2] rounded-full border border-[#E8E0D5]"></div>
            <div class="absolute top-[4.5rem] -right-3 w-6 h-6 bg-[#FAF7F2] rounded-full border border-[#E8E0D5]"></div>

            <div class="p-8 space-y-8 font-body">
                
                <!-- Status Badge -->
                <div class="flex justify-between items-center pb-4 border-b border-[#F5F1EB]">
                    <span class="text-xs uppercase tracking-wider text-[#7A6A58] font-semibold">Status Tiket</span>
                    <x-badge :variant="
                        $reservasi->status === 'dikonfirmasi' ? 'success' : 
                        ($reservasi->status === 'menunggu' ? 'warning' : 
                        ($reservasi->status === 'selesai' ? 'info' : 'error'))
                    ">
                        {{ strtoupper($reservasi->status) }}
                    </x-badge>
                </div>

                <!-- Main ticket grids -->
                <div class="grid grid-cols-2 gap-y-6 gap-x-4">
                    <div>
                        <span class="block text-[11px] uppercase tracking-wider text-[#AB9BB0]">Nama Tamu</span>
                        <span class="text-sm font-bold text-[#1A1A1A] mt-1 block">{{ $reservasi->user->nama }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] uppercase tracking-wider text-[#AB9BB0]">Nomor Kontak</span>
                        <span class="text-sm font-bold text-[#1A1A1A] mt-1 block">{{ $reservasi->user->no_hp }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] uppercase tracking-wider text-[#AB9BB0]">Tanggal Reservasi</span>
                        <span class="text-sm font-bold text-[#1A1A1A] mt-1 block">{{ $reservasi->tanggal->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] uppercase tracking-wider text-[#AB9BB0]">Waktu Kedatangan</span>
                        <span class="text-sm font-bold text-[#1A1A1A] mt-1 block font-mono">{{ substr($reservasi->jam, 0, 5) }} WIB</span>
                    </div>
                    <div>
                        <span class="block text-[11px] uppercase tracking-wider text-[#AB9BB0]">Meja / Area</span>
                        <span class="text-sm font-bold text-[#1A1A1A] mt-1 block">
                            Meja #{{ $reservasi->meja->nomor_meja }} ({{ ucfirst($reservasi->meja->area) }})
                        </span>
                    </div>
                    <div>
                        <span class="block text-[11px] uppercase tracking-wider text-[#AB9BB0]">Jumlah Tamu</span>
                        <span class="text-sm font-bold text-[#1A1A1A] mt-1 block">{{ $reservasi->jumlah_tamu }} Orang</span>
                    </div>
                </div>

                <!-- Textarea / Notes -->
                @if ($reservasi->catatan)
                    <div class="p-4 bg-[#FAF7F2] rounded-2xl border border-[#E8E0D5]">
                        <span class="block text-[10px] uppercase tracking-wider text-[#7A6A58] font-bold">Catatan Kunjungan</span>
                        <p class="text-xs text-[#4A3728] mt-1 leading-relaxed italic">"{{ $reservasi->catatan }}"</p>
                    </div>
                @endif

                <!-- Deposit Detail -->
                <div class="pt-6 border-t border-[#F5F1EB] flex justify-between items-center">
                    <div>
                        <span class="block text-[11px] uppercase tracking-wider text-[#AB9BB0]">Biaya Deposit (Telah Dibayar)</span>
                        <p class="text-[10px] text-[#7A6A58] mt-0.5">Memotong total transaksi saat makan malam.</p>
                    </div>
                    <span class="text-lg font-mono font-black text-[#C8882A]">Rp{{ number_format($reservasi->biaya_deposit, 0, ',', '.') }}</span>
                </div>

                <!-- Actions / Cancel form -->
                @if ($reservasi->status !== 'dibatalkan' && $reservasi->status !== 'selesai')
                    <div class="pt-8 flex flex-col sm:flex-row gap-3">
                        <form action="{{ route('reservasi.destroy', $reservasi->id) }}" method="POST" class="w-full" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi meja ini?');">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" variant="danger" class="w-full">
                                Batalkan Reservasi Meja
                            </x-button>
                        </form>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div x-data="{
        tanggal: '2026-05-10',
        jam: '19:00',
        mejaId: '{{ $mejas->where('nomor_meja', 4)->first()->id ?? '' }}',
        mejaNomor: '4',
        mejaArea: 'indoor',
        mejaKapasitas: 6,
        jumlahTamu: 2,
        catatan: '',
        selectMeja(id, nomor, area, kapasitas) {
            this.mejaId = id;
            this.mejaNomor = nomor;
            this.mejaArea = area;
            this.mejaKapasitas = kapasitas;
        },
        formatDate(dateStr) {
            if (!dateStr) return '';
            const parts = dateStr.split('-');
            if (parts.length !== 3) return dateStr;
            const date = new Date(parts[0], parts[1] - 1, parts[2]);
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return days[date.getDay()] + ', ' + date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
        }
    }" class="space-y-6">
        
        <!-- Page Title -->
        <div class="pb-4">
            <h1 class="font-serif text-3xl font-bold text-[#1A1A1A]">Buat Reservasi</h1>
            <p class="text-sm font-body text-[#7A6A58] mt-1">Pilih waktu, tanggal, dan area tempat duduk yang Anda inginkan.</p>
        </div>

        <form method="POST" action="{{ route('reservasi.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            
            <input type="hidden" name="tanggal" :value="tanggal">
            <input type="hidden" name="jam" :value="jam">
            <input type="hidden" name="meja_id" :value="mejaId">
            <input type="hidden" name="jumlah_tamu" :value="jumlahTamu">

            <!-- Left Form Area (2/3 width) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- 1. Pilih Tanggal & Waktu -->
                <div class="bg-white border border-[#E8E0D5] rounded-2xl p-6 shadow-sm space-y-6">
                    <h3 class="text-base font-bold font-serif text-[#1A1A1A]">Pilih Tanggal & Waktu</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Custom Calendar -->
                        <div class="space-y-4">
                            <!-- Calendar Month Header -->
                            <div class="flex items-center justify-between">
                                <button type="button" class="text-[#7A6A58] hover:text-[#C8882A] font-bold">&lt;</button>
                                <span class="font-serif font-bold text-sm text-[#1A1A1A]">Mei 2026</span>
                                <button type="button" class="text-[#7A6A58] hover:text-[#C8882A] font-bold">&gt;</button>
                            </div>

                            <!-- Calendar Days Header -->
                            <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-[#AB9BB0] uppercase tracking-wider">
                                <span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
                            </div>
                            
                            <!-- Calendar Grid for May 2026 (May 1st is Friday) -->
                            <div class="grid grid-cols-7 gap-1.5">
                                <!-- April Filler Days -->
                                <span class="h-8 flex items-center justify-center text-xs text-gray-300 font-mono">26</span>
                                <span class="h-8 flex items-center justify-center text-xs text-gray-300 font-mono">27</span>
                                <span class="h-8 flex items-center justify-center text-xs text-gray-300 font-mono">28</span>
                                <span class="h-8 flex items-center justify-center text-xs text-gray-300 font-mono">29</span>
                                <span class="h-8 flex items-center justify-center text-xs text-gray-300 font-mono">30</span>
                                
                                <!-- May 1 & 2 -->
                                <button type="button" @click="tanggal = '2026-05-01'" class="h-8 rounded-full text-xs font-mono font-bold flex items-center justify-center transition-all" :class="tanggal === '2026-05-01' ? 'bg-[#1A1A1A] text-white' : 'text-[#1A1A1A] hover:bg-[#FAF7F2]'">1</button>
                                <button type="button" @click="tanggal = '2026-05-02'" class="h-8 rounded-full text-xs font-mono font-bold flex items-center justify-center transition-all" :class="tanggal === '2026-05-02' ? 'bg-[#1A1A1A] text-white' : 'text-[#1A1A1A] hover:bg-[#FAF7F2]'">2</button>
                                
                                <!-- May 3 to 9 -->
                                @for($d = 3; $d <= 9; $d++)
                                    <button type="button" @click="tanggal = '2026-05-0{{ $d }}'" 
                                            class="h-8 rounded-full text-xs font-mono font-bold flex items-center justify-center transition-all" 
                                            :class="tanggal === '2026-05-0{{ $d }}' 
                                                ? 'bg-[#1A1A1A] text-white' 
                                                : ('{{ $d }}' == 7 ? 'border border-[#C8882A] text-[#C8882A]' : 'text-[#1A1A1A] hover:bg-[#FAF7F2]')"
                                    >{{ $d }}</button>
                                @endfor

                                <!-- May 10 to 31 -->
                                @for($d = 10; $d <= 31; $d++)
                                    <button type="button" @click="tanggal = '2026-05-{{ $d }}'" 
                                            class="h-8 rounded-full text-xs font-mono font-bold flex items-center justify-center transition-all" 
                                            :class="tanggal === '2026-05-{{ $d }}' 
                                                ? 'bg-[#1A1A1A] text-white' 
                                                : 'text-[#1A1A1A] hover:bg-[#FAF7F2]'"
                                    >{{ $d }}</button>
                                @endfor
                            </div>
                        </div>

                        <!-- Time Slots -->
                        <div class="space-y-4">
                            <span class="text-[10px] font-bold text-[#AB9BB0] tracking-widest uppercase block">Waktu Tersedia</span>
                            <div class="grid grid-cols-3 gap-2.5">
                                @foreach (['11:00', '12:00', '13:00', '14:00', '17:00', '18:00', '19:00', '20:00', '21:00'] as $time)
                                    @if ($time === '13:00' || $time === '20:00')
                                        <button type="button" disabled class="py-3 rounded-lg bg-[#FAF8F5] text-gray-300 text-xs font-bold font-mono cursor-not-allowed">
                                            {{ $time }}
                                        </button>
                                    @else
                                        <button 
                                            type="button"
                                            @click="jam = '{{ $time }}'"
                                            class="py-3 rounded-lg border text-xs font-bold font-mono flex items-center justify-center transition-all duration-150"
                                            :class="jam === '{{ $time }}'
                                                ? 'bg-[#FEF6EB] text-[#C8882A] border-[#C8882A] shadow-sm'
                                                : 'bg-white text-[#1A1A1A] border-[#E8E0D5] hover:border-[#C8882A]'"
                                        >
                                            {{ $time }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Pilih Meja -->
                <div class="bg-white border border-[#E8E0D5] rounded-2xl p-6 shadow-sm space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold font-serif text-[#1A1A1A]">Pilih Meja</h3>
                        <!-- Legend -->
                        <div class="flex items-center space-x-4 text-[10px] font-bold text-[#7A6A58]">
                            <span class="flex items-center space-x-1">
                                <span class="w-3.5 h-3.5 bg-white border border-[#4CAF82] rounded"></span>
                                <span>TERSEDIA</span>
                            </span>
                            <span class="flex items-center space-x-1">
                                <span class="w-3.5 h-3.5 bg-[#C8882A] rounded"></span>
                                <span>DIPILIH</span>
                            </span>
                            <span class="flex items-center space-x-1">
                                <span class="w-3.5 h-3.5 bg-gray-100 border border-gray-200 rounded"></span>
                                <span>TERISI</span>
                            </span>
                        </div>
                    </div>

                    <!-- Map Outline Grid -->
                    <div class="border border-dashed border-[#D4C9BB]/60 rounded-2xl p-6 grid grid-cols-1 md:grid-cols-2 gap-8 relative bg-[#FAF9F6]">
                        <!-- Area Indoor -->
                        <div class="space-y-4 md:pr-6 md:border-r border-dashed border-[#D4C9BB]/50">
                            <span class="text-[10px] font-bold tracking-widest text-[#AB9BB0] uppercase block">INDOOR</span>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach ($mejas->where('area', 'indoor') as $meja)
                                    @php
                                        // Meja 3 booked visually to match the mockup
                                        $isBooked = ($meja->nomor_meja == 3);
                                    @endphp
                                    @if ($isBooked)
                                        <button type="button" disabled class="h-16 rounded-xl bg-gray-100 border border-gray-200 text-gray-400 flex flex-col items-center justify-center cursor-not-allowed">
                                            <span class="text-sm font-bold font-mono">{{ $meja->nomor_meja }}</span>
                                        </button>
                                    @else
                                        <button 
                                            type="button"
                                            @click="selectMeja('{{ $meja->id }}', '{{ $meja->nomor_meja }}', '{{ $meja->area }}', {{ $meja->kapasitas }})"
                                            class="h-16 rounded-xl flex flex-col items-center justify-center border-2 transition-all duration-150"
                                            :class="mejaId == '{{ $meja->id }}'
                                                ? 'bg-[#C8882A] border-[#C8882A] text-white shadow-sm'
                                                : 'bg-white border-[#4CAF82] text-[#1A1A1A] hover:border-[#C8882A]'"
                                        >
                                            <span class="text-sm font-bold font-mono">{{ $meja->nomor_meja }}</span>
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Area Terrace -->
                        <div class="space-y-4 md:pl-6">
                            <span class="text-[10px] font-bold tracking-widest text-[#AB9BB0] uppercase block">TERRACE</span>
                            <div class="grid grid-cols-2 gap-6 items-center justify-items-center h-full pb-8">
                                @foreach ($mejas->where('area', 'terrace') as $meja)
                                    <button 
                                        type="button"
                                        @click="selectMeja('{{ $meja->id }}', '{{ $meja->nomor_meja }}', '{{ $meja->area }}', {{ $meja->kapasitas }})"
                                        class="w-16 h-16 rounded-full flex items-center justify-center border-2 transition-all duration-150"
                                        :class="mejaId == '{{ $meja->id }}'
                                            ? 'bg-[#C8882A] border-[#C8882A] text-white shadow-sm'
                                            : 'bg-white border-[#4CAF82] text-[#1A1A1A] hover:border-[#C8882A]'"
                                    >
                                        <span class="text-sm font-bold font-mono">{{ $meja->nomor_meja }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Summary Column (1/3 width) -->
            <div class="space-y-6">
                <div class="sticky top-6">
                    <div class="bg-white border border-[#E8E0D5] rounded-2xl p-6 shadow-sm space-y-6">
                        <h3 class="text-base font-bold font-serif text-[#1A1A1A] pb-3 border-b border-[#F5F1EB]">Ringkasan Reservasi</h3>

                        <div class="space-y-5">
                            <!-- Waktu -->
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-[#FAF7F2] rounded-lg text-[#C8882A] mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-[#AB9BB0] uppercase tracking-wider block">WAKTU</span>
                                    <span class="font-bold text-xs text-[#1A1A1A] mt-0.5 block" x-text="formatDate(tanggal) + ' - ' + jam + ' WIB'"></span>
                                </div>
                            </div>

                            <!-- Lokasi -->
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-[#FAF7F2] rounded-lg text-[#C8882A] mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-[#AB9BB0] uppercase tracking-wider block">LOKASI</span>
                                    <span class="font-bold text-xs text-[#1A1A1A] mt-0.5 block" x-text="mejaNomor ? 'Meja ' + mejaNomor + ' — ' + (mejaArea === 'indoor' ? 'Indoor' : 'Terrace') : 'Belum dipilih'"></span>
                                    <span class="text-[10px] text-[#AB9BB0] mt-0.5 block" x-text="mejaNomor ? 'Kapasitas Maks: ' + mejaKapasitas + ' orang' : ''"></span>
                                </div>
                            </div>

                            <!-- Jumlah Tamu Counter -->
                            <div class="space-y-1.5 pt-2">
                                <label class="text-[10px] font-bold text-[#AB9BB0] uppercase tracking-wider block">JUMLAH TAMU</label>
                                <div class="flex items-center justify-between border border-[#E8E0D5] rounded-lg p-1 w-full bg-[#FAF8F5]">
                                    <button type="button" @click="if(jumlahTamu > 1) jumlahTamu--" class="w-8 h-8 rounded bg-white flex items-center justify-center font-bold border border-[#E8E0D5] hover:bg-[#F5F1EB] transition-colors">-</button>
                                    <span class="font-bold text-xs text-[#1A1A1A]" x-text="jumlahTamu + ' Orang'"></span>
                                    <button type="button" @click="jumlahTamu++" class="w-8 h-8 rounded bg-[#C8882A] text-white flex items-center justify-center font-bold hover:bg-[#B67720] transition-colors">+</button>
                                </div>
                            </div>

                            <!-- Catatan -->
                            <div class="space-y-1.5">
                                <label for="catatan" class="text-[10px] font-bold text-[#AB9BB0] uppercase tracking-wider block">CATATAN KHUSUS (OPSIONAL)</label>
                                <textarea 
                                    name="catatan" 
                                    id="catatan" 
                                    x-model="catatan"
                                    rows="2"
                                    placeholder="Contoh: Perayaan ulang tahun, alergi kacang, butuh kursi bayi..."
                                    class="w-full px-3 py-2 bg-[#FAF8F5] border border-[#E8E0D5] rounded-lg font-body text-xs text-[#1A1A1A] placeholder-[#AB9BB0] focus:outline-none focus:border-[#C8882A] focus:ring-1 focus:ring-[#C8882A] resize-none"
                                ></textarea>
                            </div>

                            <!-- Biaya Deposit -->
                            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 flex items-center justify-between">
                                <span class="text-xs font-bold text-[#7A6A58]">Biaya Reservasi (Deposit)</span>
                                <span class="font-bold text-sm text-[#1A1A1A]">Rp 50.000</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-2 space-y-3">
                            <button 
                                type="submit" 
                                class="w-full bg-[#1A1A1A] hover:bg-gray-800 text-white py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                                ::disabled="!mejaId"
                            >
                                Konfirmasi Reservasi →
                            </button>
                            
                            <a href="{{ route('dashboard') }}" class="block text-center text-xs font-bold text-red-500 hover:underline">
                                Batalkan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</x-app-layout>

<x-app-layout>
    <div x-data="reservasiForm()" class="space-y-6">
        
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
                                <button type="button" @click="prevMonth" class="text-[#7A6A58] hover:text-[#C8882A] font-bold">&lt;</button>
                                <span class="font-serif font-bold text-sm text-[#1A1A1A]" x-text="monthNames[currentMonth] + ' ' + currentYear"></span>
                                <button type="button" @click="nextMonth" class="text-[#7A6A58] hover:text-[#C8882A] font-bold">&gt;</button>
                            </div>

                            <!-- Calendar Days Header -->
                            <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-[#AB9BB0] uppercase tracking-wider">
                                <span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
                            </div>
                            
                            <div class="grid grid-cols-7 gap-1.5">
                                <!-- Blank days -->
                                <template x-for="blank in blankdays">
                                    <span class="h-8"></span>
                                </template>
                                
                                <!-- Active month days -->
                                <template x-for="day in days">
                                    <button 
                                        type="button" 
                                        @click="selectDate(day)" 
                                        class="h-8 rounded-full text-xs font-mono font-bold flex items-center justify-center transition-all" 
                                        :class="tanggal === `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}` 
                                            ? 'bg-[#1A1A1A] text-white' 
                                            : (isToday(day) ? 'border border-[#C8882A] text-[#C8882A]' : 'text-[#1A1A1A] hover:bg-[#FAF7F2]')"
                                        x-text="day"
                                    ></button>
                                </template>
                            </div>
                        </div>

                        <!-- Time Slots -->
                        <div class="space-y-4">
                            <span class="text-[10px] font-bold text-[#AB9BB0] tracking-widest uppercase block">Waktu Tersedia</span>
                            <div class="grid grid-cols-3 gap-2.5">
                                @foreach (['11:00', '12:00', '13:00', '14:00', '17:00', '18:00', '19:00', '20:00', '21:00'] as $time)
                                    <button 
                                        type="button"
                                        :disabled="isTimeSlotReserved('{{ $time }}')"
                                        @click="jam = '{{ $time }}'"
                                        class="py-3 rounded-lg border text-xs font-bold font-mono flex items-center justify-center transition-all duration-150"
                                        :class="isTimeSlotReserved('{{ $time }}')
                                            ? 'bg-[#FAF8F5] text-gray-300 border-[#E8E0D5] cursor-not-allowed'
                                            : (jam === '{{ $time }}'
                                                ? 'bg-[#FEF6EB] text-[#C8882A] border-[#C8882A] shadow-sm'
                                                : 'bg-white text-[#1A1A1A] border-[#E8E0D5] hover:border-[#C8882A]')"
                                    >
                                        {{ $time }}
                                    </button>
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
                                    <button 
                                        type="button"
                                        :disabled="isMejaReserved('{{ $meja->id }}')"
                                        @click="selectMeja('{{ $meja->id }}', '{{ $meja->nomor_meja }}', '{{ $meja->area }}', {{ $meja->kapasitas }})"
                                        class="h-16 rounded-xl flex flex-col items-center justify-center border-2 transition-all duration-150"
                                        :class="isMejaReserved('{{ $meja->id }}')
                                            ? 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed'
                                            : (mejaId == '{{ $meja->id }}'
                                                ? 'bg-[#C8882A] border-[#C8882A] text-white shadow-sm'
                                                : 'bg-white border-[#4CAF82] text-[#1A1A1A] hover:border-[#C8882A]')"
                                    >
                                        <span class="text-sm font-bold font-mono">{{ $meja->nomor_meja }}</span>
                                    </button>
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
                                        :disabled="isMejaReserved('{{ $meja->id }}')"
                                        @click="selectMeja('{{ $meja->id }}', '{{ $meja->nomor_meja }}', '{{ $meja->area }}', {{ $meja->kapasitas }})"
                                        class="w-16 h-16 rounded-full flex items-center justify-center border-2 transition-all duration-150"
                                        :class="isMejaReserved('{{ $meja->id }}')
                                            ? 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed'
                                            : (mejaId == '{{ $meja->id }}'
                                                ? 'bg-[#C8882A] border-[#C8882A] text-white shadow-sm'
                                                : 'bg-white border-[#4CAF82] text-[#1A1A1A] hover:border-[#C8882A]')"
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

    <script>
        function reservasiForm() {
            return {
                tanggal: (() => { const d = new Date(); return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`; })(),
                jam: '19:00',
                mejaId: '',
                mejaNomor: '',
                mejaArea: '',
                mejaKapasitas: 0,
                jumlahTamu: 2,
                catatan: '',
                reservasis: @json($reservasis),
                currentMonth: new Date().getMonth(),
                currentYear: new Date().getFullYear(),
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                init() {
                    this.$watch('tanggal', () => this.validateSelection());
                    this.$watch('jam', () => this.validateSelection());
                },
                validateSelection() {
                    if (this.mejaId && this.isMejaReserved(this.mejaId)) {
                        this.mejaId = '';
                        this.mejaNomor = '';
                        this.mejaArea = '';
                        this.mejaKapasitas = 0;
                    }
                },
                get days() {
                    let days = [];
                    let totalDays = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
                    for (let i = 1; i <= totalDays; i++) {
                        days.push(i);
                    }
                    return days;
                },
                get blankdays() {
                    let count = new Date(this.currentYear, this.currentMonth, 1).getDay();
                    let arr = [];
                    for (let i = 0; i < count; i++) {
                        arr.push(i);
                    }
                    return arr;
                },
                prevMonth() {
                    if (this.currentMonth === 0) {
                        this.currentMonth = 11;
                        this.currentYear--;
                    } else {
                        this.currentMonth--;
                    }
                },
                nextMonth() {
                    if (this.currentMonth === 11) {
                        this.currentMonth = 0;
                        this.currentYear++;
                    } else {
                        this.currentMonth++;
                    }
                },
                selectDate(day) {
                    const formattedMonth = String(this.currentMonth + 1).padStart(2, '0');
                    const formattedDay = String(day).padStart(2, '0');
                    this.tanggal = `${this.currentYear}-${formattedMonth}-${formattedDay}`;
                },
                isToday(day) {
                    const today = new Date();
                    return today.getDate() === day && today.getMonth() === this.currentMonth && today.getFullYear() === this.currentYear;
                },
                isMejaReserved(mejaId) {
                    return this.reservasis.some(r => r.meja_id == mejaId && r.tanggal === this.tanggal && r.jam === this.jam);
                },
                isTimeSlotReserved(time) {
                    if (!this.mejaId) return false;
                    return this.reservasis.some(r => r.meja_id == this.mejaId && r.tanggal === this.tanggal && r.jam === time);
                },
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
            };
        }
    </script>
</x-app-layout>

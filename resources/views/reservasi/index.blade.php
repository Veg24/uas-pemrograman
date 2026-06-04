<x-app-layout>
    <div x-data="{
        tanggal: '2026-05-15',
        jam: '18:00',
        mejaId: '',
        mejaNomor: '',
        mejaArea: '',
        mejaKapasitas: 0,
        jumlahTamu: 2,
        catatan: '',
        selectMeja(id, nomor, area, kapasitas) {
            this.mejaId = id;
            this.mejaNomor = nomor;
            this.mejaArea = area;
            this.mejaKapasitas = kapasitas;
        }
    }" class="space-y-6">
        
        <!-- Page Title -->
        <div class="pb-4 border-b border-[#E8E0D5]">
            <h1 class="font-serif text-3xl font-black text-[#1A1A1A]">Reservasi Meja</h1>
            <p class="text-sm font-body text-[#7A6A58] mt-1">Atur waktu kunjungan makan malam Anda di Lumière Dining.</p>
        </div>

        <form method="POST" action="{{ route('reservasi.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            
            <input type="hidden" name="tanggal" :value="tanggal">
            <input type="hidden" name="jam" :value="jam">
            <input type="hidden" name="meja_id" :value="mejaId">
            <input type="hidden" name="jumlah_tamu" :value="jumlahTamu">

            <!-- Left Form Area (2/3 width) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- 1. Pilih Tanggal (Custom Calendar May 2026) -->
                <x-card class="space-y-4">
                    <x-slot:headerSlot>
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold font-serif text-[#1A1A1A]">1. Pilih Tanggal</h3>
                            <span class="text-xs font-mono font-bold text-[#C8882A] bg-[#FEF6EB] px-3 py-1 rounded-full border border-[#fce4c4]">Mei 2026</span>
                        </div>
                    </x-slot:headerSlot>

                    <div class="max-w-md mx-auto">
                        <!-- Calendar Days Header -->
                        <div class="grid grid-cols-7 gap-2 text-center text-xs font-bold text-[#7A6A58] uppercase tracking-wider mb-2">
                            <span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
                        </div>
                        
                        <!-- May 2026 Grid: 1st is Friday. Sun-Thu empty. -->
                        <div class="grid grid-cols-7 gap-2">
                            <!-- Empty days from previous month (Sunday to Thursday = 5 days) -->
                            <template x-for="i in 5">
                                <div class="h-10"></div>
                            </template>
                            
                            <!-- Days 1 to 31 -->
                            <template x-for="day in 31">
                                @php
                                    // Pre-populate some disabled/occupied dates just for flavor or keep it simple
                                @endphp
                                <button 
                                    type="button"
                                    @click="tanggal = '2026-05-' + String(day).padStart(2, '0')"
                                    class="h-10 w-full rounded-xl text-xs font-semibold font-mono flex items-center justify-center border transition-all duration-150"
                                    :class="tanggal === '2026-05-' + String(day).padStart(2, '0') 
                                        ? 'bg-[#C8882A] text-white border-[#C8882A] shadow-md scale-105' 
                                        : 'bg-[#FAF7F2] text-[#1A1A1A] border-[#E8E0D5] hover:border-[#C8882A] hover:bg-white'"
                                >
                                    <span x-text="day"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </x-card>

                <!-- 2. Pilih Jam -->
                <x-card class="space-y-4">
                    <x-slot:headerSlot>
                        <h3 class="text-lg font-bold font-serif text-[#1A1A1A]">2. Pilih Waktu Kunjungan</h3>
                    </x-slot:headerSlot>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach (['11:00', '12:00', '13:00', '14:00', '17:00', '18:00', '19:00', '21:00'] as $time)
                            <button 
                                type="button"
                                @click="jam = '{{ $time }}'"
                                class="py-3 rounded-xl border text-sm font-semibold font-mono flex items-center justify-center transition-all duration-150"
                                :class="jam === '{{ $time }}'
                                    ? 'bg-[#C8882A] text-white border-[#C8882A] shadow-md scale-105'
                                    : 'bg-[#FAF7F2] text-[#1A1A1A] border-[#E8E0D5] hover:border-[#C8882A] hover:bg-white'"
                            >
                                {{ $time }} WIB
                            </button>
                        @endforeach
                    </div>
                </x-card>

                <!-- 3. Pilih Meja (Visual Layout) -->
                <x-card class="space-y-6">
                    <x-slot:headerSlot>
                        <h3 class="text-lg font-bold font-serif text-[#1A1A1A]">3. Pilih Meja</h3>
                    </x-slot:headerSlot>

                    <!-- Legenda -->
                    <div class="flex items-center space-x-6 justify-center text-xs pb-4 border-b border-[#F5F1EB]">
                        <span class="flex items-center space-x-2">
                            <span class="w-4 h-4 bg-white border border-[#D4C9BB] rounded-md"></span>
                            <span class="text-[#7A6A58]">Tersedia</span>
                        </span>
                        <span class="flex items-center space-x-2">
                            <span class="w-4 h-4 bg-[#C8882A] rounded-md"></span>
                            <span class="text-[#7A6A58]">Dipilih</span>
                        </span>
                        <span class="flex items-center space-x-2">
                            <span class="w-4 h-4 bg-[#E95252]/10 border border-[#E95252]/30 rounded-md"></span>
                            <span class="text-[#7A6A58]">Terisi</span>
                        </span>
                    </div>

                    <!-- Layout Map Container -->
                    <div class="space-y-6">
                        
                        <!-- Area Indoor -->
                        <div class="space-y-3">
                            <h4 class="text-xs uppercase tracking-wider font-bold text-[#7A6A58]">Area Indoor</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                @foreach ($mejas->where('area', 'indoor') as $meja)
                                    <button 
                                        type="button"
                                        @if ($meja->status !== 'tersedia') disabled @endif
                                        @click="selectMeja('{{ $meja->id }}', '{{ $meja->nomor_meja }}', '{{ $meja->area }}', {{ $meja->kapasitas }})"
                                        class="relative flex flex-col items-center justify-center p-4 border rounded-2xl transition-all duration-150 h-28"
                                        :class="{
                                            'bg-[#E95252]/5 border-[#E95252]/20 opacity-60 cursor-not-allowed': '{{ $meja->status }}' !== 'tersedia',
                                            'bg-white border-[#D4C9BB] hover:border-[#C8882A]': '{{ $meja->status }}' === 'tersedia' && mejaId != '{{ $meja->id }}',
                                            'bg-[#C8882A] border-[#C8882A] text-white shadow-lg scale-105': mejaId == '{{ $meja->id }}'
                                        }"
                                    >
                                        <!-- Table Number -->
                                        <span class="text-lg font-serif font-black">M{{ $meja->nomor_meja }}</span>
                                        <span class="text-[10px] mt-1 uppercase tracking-wider" :class="mejaId == '{{ $meja->id }}' ? 'text-white/80' : 'text-[#AB9BB0]'">
                                            {{ $meja->kapasitas }} Kursi
                                        </span>

                                        <!-- Chair lines visual decorators -->
                                        <div class="absolute -inset-1 border border-transparent rounded-2xl pointer-events-none flex items-center justify-between px-1">
                                            <div class="w-1.5 h-6 rounded-sm" :class="mejaId == '{{ $meja->id }}' ? 'bg-white/40' : 'bg-[#D4C9BB]'"></div>
                                            <div class="w-1.5 h-6 rounded-sm" :class="mejaId == '{{ $meja->id }}' ? 'bg-white/40' : 'bg-[#D4C9BB]'"></div>
                                        </div>
                                        
                                        @if ($meja->status !== 'tersedia')
                                            <span class="absolute bottom-2 bg-[#E95252] text-white text-[8px] font-bold px-1.5 py-0.5 rounded">TERISI</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Area Terrace -->
                        <div class="space-y-3">
                            <h4 class="text-xs uppercase tracking-wider font-bold text-[#7A6A58]">Area Terrace</h4>
                            <div class="grid grid-cols-2 gap-4 max-w-md">
                                @foreach ($mejas->where('area', 'terrace') as $meja)
                                    <button 
                                        type="button"
                                        @if ($meja->status !== 'tersedia') disabled @endif
                                        @click="selectMeja('{{ $meja->id }}', '{{ $meja->nomor_meja }}', '{{ $meja->area }}', {{ $meja->kapasitas }})"
                                        class="relative flex flex-col items-center justify-center p-4 border border-dashed rounded-2xl transition-all duration-150 h-28"
                                        :class="{
                                            'bg-[#E95252]/5 border-[#E95252]/20 opacity-60 cursor-not-allowed': '{{ $meja->status }}' !== 'tersedia',
                                            'bg-white border-[#D4C9BB] hover:border-[#C8882A]': '{{ $meja->status }}' === 'tersedia' && mejaId != '{{ $meja->id }}',
                                            'bg-[#C8882A] border-[#C8882A] text-white border-solid shadow-lg scale-105': mejaId == '{{ $meja->id }}'
                                        }"
                                    >
                                        <span class="text-lg font-serif font-black">M{{ $meja->nomor_meja }}</span>
                                        <span class="text-[10px] mt-1 uppercase tracking-wider" :class="mejaId == '{{ $meja->id }}' ? 'text-white/80' : 'text-[#AB9BB0]'">
                                            {{ $meja->kapasitas }} Kursi (Outdoor)
                                        </span>
                                        
                                        @if ($meja->status !== 'tersedia')
                                            <span class="absolute bottom-2 bg-[#E95252] text-white text-[8px] font-bold px-1.5 py-0.5 rounded">TERISI</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </x-card>

                <!-- 4. Jumlah Tamu & Catatan -->
                <x-card class="space-y-4">
                    <x-slot:headerSlot>
                        <h3 class="text-lg font-bold font-serif text-[#1A1A1A]">4. Jumlah Tamu & Catatan</h3>
                    </x-slot:headerSlot>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        
                        <!-- Guest Counter -->
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-[#4A3728]">Jumlah Tamu</label>
                            <div class="flex items-center space-x-3 bg-[#FAF7F2] p-1 rounded-xl border border-[#D4C9BB] w-36">
                                <button type="button" @click="if(jumlahTamu > 1) jumlahTamu--" class="w-8 h-8 rounded-lg bg-white flex items-center justify-center font-bold border border-[#E8E0D5] hover:bg-[#F5F1EB] transition-colors">-</button>
                                <span class="flex-1 text-center font-mono font-bold text-sm" x-text="jumlahTamu"></span>
                                <button type="button" @click="jumlahTamu++" class="w-8 h-8 rounded-lg bg-white flex items-center justify-center font-bold border border-[#E8E0D5] hover:bg-[#F5F1EB] transition-colors">+</button>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="sm:col-span-2 space-y-1.5">
                            <label for="catatan" class="block text-sm font-semibold text-[#4A3728]">Catatan Khusus (Opsional)</label>
                            <textarea 
                                name="catatan" 
                                id="catatan" 
                                x-model="catatan"
                                rows="3"
                                placeholder="Alergi makanan, preferensi dekorasi, permintaan kursi bayi, dll."
                                class="w-full px-4 py-2 bg-white border border-[#D4C9BB] rounded-xl font-body text-sm text-[#1A1A1A] placeholder-[#AB9BB0] focus:outline-none focus:ring-2 focus:ring-[#C8882A]/20 focus:border-[#C8882A] resize-none"
                            ></textarea>
                        </div>
                    </div>
                </x-card>

            </div>

            <!-- Right Summary Column (1/3 width) -->
            <div class="space-y-6">
                <div class="sticky top-6">
                    <x-card class="border-t-4 border-t-[#C8882A] shadow-md bg-white">
                        <x-slot:headerSlot>
                            <h3 class="text-base font-bold font-serif text-[#1A1A1A]">Ringkasan Reservasi</h3>
                        </x-slot:headerSlot>

                        <!-- Details List -->
                        <div class="space-y-4 font-body text-sm py-2">
                            <div class="flex justify-between items-center pb-3 border-b border-[#F5F1EB]">
                                <span class="text-[#7A6A58]">Tanggal</span>
                                <span class="font-bold text-[#1A1A1A] font-mono" x-text="tanggal"></span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-[#F5F1EB]">
                                <span class="text-[#7A6A58]">Waktu</span>
                                <span class="font-bold text-[#1A1A1A] font-mono" x-text="jam + ' WIB'"></span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-[#F5F1EB]">
                                <span class="text-[#7A6A58]">Area / Lokasi</span>
                                <span class="font-bold text-[#1A1A1A]" x-text="mejaNomor ? 'Meja #' + mejaNomor + ' (' + (mejaArea === 'indoor' ? 'Indoor' : 'Terrace') + ')' : 'Belum dipilih'"></span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-[#F5F1EB]">
                                <span class="text-[#7A6A58]">Kapasitas Meja</span>
                                <span class="font-bold text-[#1A1A1A]" x-text="mejaNomor ? mejaKapasitas + ' Kursi' : '-'"></span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-[#F5F1EB]">
                                <span class="text-[#7A6A58]">Jumlah Tamu</span>
                                <span class="font-bold text-[#1A1A1A]" x-text="jumlahTamu + ' Orang'"></span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-[#F5F1EB]">
                                <span class="text-[#7A6A58]">Biaya Deposit</span>
                                <span class="font-mono font-bold text-[#C8882A]">Rp50.000</span>
                            </div>
                        </div>

                        <!-- Warnings / Info -->
                        <div class="bg-[#FAF7F2] p-3 rounded-xl border border-[#E8E0D5] text-[11px] text-[#7A6A58] leading-relaxed mt-4">
                            <strong>Kebijakan Deposit:</strong> Deposit sebesar Rp50.000 diperlukan untuk mengamankan meja Anda. Biaya ini akan memotong total transaksi pesanan makan malam Anda secara otomatis.
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-6 space-y-3">
                            <x-button 
                                type="submit" 
                                variant="primary" 
                                class="w-full"
                                ::disabled="!mejaId"
                            >
                                Konfirmasi Reservasi →
                            </x-button>
                            
                            <a href="{{ route('dashboard') }}" class="block">
                                <x-button type="button" variant="ghost" class="w-full">
                                    Batalkan
                                </x-button>
                            </a>
                        </div>
                    </x-card>
                </div>
            </div>

        </form>
    </div>
</x-app-layout>

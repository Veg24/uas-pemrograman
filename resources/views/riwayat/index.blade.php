<x-app-layout>
    <div x-data="{
        showRatingModal: false,
        ratingAktivitasId: '',
        ratingAktivitasJudul: '',
        stars: 5,
        ratingKomentar: '',
        submitRating() {
            this.showRatingModal = false;
            // Dispatch a successful rating message to the toast
            alert('Terima kasih! Ulasan bintang ' + this.stars + ' Anda untuk ' + this.ratingAktivitasJudul + ' telah terkirim.');
            // Reset state
            this.stars = 5;
            this.ratingKomentar = '';
        }
    }" class="space-y-6">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-[#E8E0D5]">
            <div>
                <h1 class="font-serif text-3xl font-black text-[#1A1A1A]">Riwayat Aktivitas</h1>
                <p class="text-sm font-body text-[#7A6A58] mt-1">Daftar reservasi meja dan pemesanan kuliner yang pernah Anda lakukan.</p>
            </div>
            
            <!-- Quick Notification / Action links -->
            <a href="{{ route('reservasi.index') }}" class="sm:self-start">
                <x-button variant="primary" size="sm">
                    Pesan Meja Lagi
                </x-button>
            </a>
        </header>

        <!-- Stats Boxes Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <x-card class="bg-white border border-[#E8E0D5]">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-[#EAF7F1] rounded-xl text-[#4CAF82]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-[#7A6A58] uppercase tracking-wider">Total Reservasi</p>
                        <h3 class="text-2xl font-black text-[#1A1A1A] mt-0.5 font-mono">{{ $totalReservasi }}</h3>
                    </div>
                </div>
            </x-card>

            <x-card class="bg-white border border-[#E8E0D5]">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-[#ECF3FE] rounded-xl text-[#3B82F6]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-[#7A6A58] uppercase tracking-wider">Pesanan Selesai</p>
                        <h3 class="text-2xl font-black text-[#1A1A1A] mt-0.5 font-mono">{{ $pesananSelesai }}</h3>
                    </div>
                </div>
            </x-card>

            <x-card class="bg-white border border-[#E8E0D5]">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-[#FDF1F1] rounded-xl text-[#E95252]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-[#7A6A58] uppercase tracking-wider">Aktivitas Dibatalkan</p>
                        <h3 class="text-2xl font-black text-[#1A1A1A] mt-0.5 font-mono">{{ $totalDibatalkan }}</h3>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Filter Bar -->
        <div class="flex flex-col sm:flex-row gap-4 justify-between items-center bg-white p-4 rounded-2xl border border-[#E8E0D5]">
            <!-- Tab Filters -->
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                @foreach ([
                    'semua' => 'Semua',
                    'reservasi' => 'Reservasi',
                    'pesan' => 'Pesanan Makanan',
                    'dibatalkan' => 'Dibatalkan'
                ] as $tabKey => $tabLabel)
                    <a 
                        href="{{ route('riwayat', array_merge(request()->query(), ['tab' => $tabKey, 'page' => 1])) }}"
                        class="px-4 py-2 rounded-xl text-xs font-semibold font-body border transition-all duration-150"
                        :class="'{{ $tab }}' === '{{ $tabKey }}' 
                            ? 'bg-[#1A1A1A] text-white border-[#1A1A1A] shadow-sm' 
                            : 'bg-[#FAF7F2] text-[#7A6A58] border-[#E8E0D5] hover:border-[#D4C9BB]'"
                    >
                        {{ $tabLabel }}
                    </a>
                @endforeach
            </div>

            <!-- Period Select Dropdown -->
            <div class="w-full sm:w-auto flex items-center space-x-2 text-xs">
                <span class="text-[#7A6A58] font-semibold whitespace-nowrap">Periode:</span>
                <select 
                    onchange="location = this.value;"
                    class="bg-[#FAF7F2] border border-[#D4C9BB] rounded-xl px-3 py-2 text-xs font-semibold text-[#1A1A1A] focus:outline-none focus:ring-1 focus:ring-[#C8882A]"
                >
                    <option value="{{ route('riwayat', array_merge(request()->query(), ['periode' => '30_days', 'page' => 1])) }}" {{ $periode === '30_days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                    <option value="{{ route('riwayat', array_merge(request()->query(), ['periode' => '6_months', 'page' => 1])) }}" {{ $periode === '6_months' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                    <option value="{{ route('riwayat', array_merge(request()->query(), ['periode' => '1_year', 'page' => 1])) }}" {{ $periode === '1_year' ? 'selected' : '' }}>1 Tahun Terakhir</option>
                    <option value="{{ route('riwayat', array_merge(request()->query(), ['periode' => 'semua', 'page' => 1])) }}" {{ $periode === 'semua' ? 'selected' : '' }}>Semua Waktu</option>
                </select>
            </div>
        </div>

        <!-- Activities Timeline -->
        <div class="relative mt-8">
            @if ($paginatedActivities->isEmpty())
                <div class="text-center py-16 bg-white rounded-2xl border border-[#E8E0D5]">
                    <div class="inline-flex p-3 bg-[#FAF7F2] rounded-full text-[#AB9BB0] border border-[#E8E0D5] mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-sm text-[#7A6A58]">Tidak ada aktivitas yang ditemukan untuk periode ini.</p>
                </div>
            @else
                <!-- Central Timeline Line -->
                <div class="absolute left-6 md:left-1/2 top-4 bottom-4 w-0.5 bg-[#E8E0D5] z-0"></div>

                <div class="space-y-12 relative z-10">
                    @foreach ($paginatedActivities as $index => $act)
                        @php
                            $isEven = $index % 2 === 0;
                        @endphp
                        
                        <!-- Timeline Item -->
                        <div class="flex flex-col md:flex-row items-start {{ $isEven ? 'md:flex-row-reverse' : '' }} w-full">
                            <!-- Left/Right Card Spacer -->
                            <div class="w-full md:w-1/2 px-0 md:px-8 pl-12 md:pl-0">
                                <x-card class="shadow-sm border border-[#E8E0D5] bg-white transition-all hover:shadow-md">
                                    <div class="space-y-4">
                                        <!-- Header activity -->
                                        <div class="flex justify-between items-start gap-4">
                                            <div>
                                                <span class="text-[9.5px] font-mono text-[#AB9BB0] block">{{ $act->created_at->format('d M Y, H:i') }}</span>
                                                <h4 class="font-serif text-base font-bold text-[#1A1A1A] mt-1">{{ $act->judul }}</h4>
                                            </div>
                                            <x-badge :variant="
                                                $act->status === 'dikonfirmasi' || $act->status === 'selesai' ? 'success' : 
                                                ($act->status === 'menunggu' || $act->status === 'diproses' || $act->status === 'dikirim' ? 'warning' : 'error')
                                            ">
                                                {{ strtoupper($act->status) }}
                                            </x-badge>
                                        </div>

                                        <!-- Description -->
                                        <p class="text-xs text-[#7A6A58] leading-relaxed">{{ $act->deskripsi }}</p>

                                        <!-- Action buttons -->
                                        <div class="flex flex-wrap gap-2 pt-2 border-t border-[#F5F1EB]">
                                            <a href="{{ $act->url }}">
                                                <x-button variant="secondary" size="sm">
                                                    Lihat Detail
                                                </x-button>
                                            </a>
                                            
                                            @if ($act->status === 'selesai')
                                                <x-button 
                                                    type="button" 
                                                    variant="ghost" 
                                                    size="sm"
                                                    @click="showRatingModal = true; ratingAktivitasId = '{{ $act->tipe . '_' . $act->id }}'; ratingAktivitasJudul = '{{ $act->judul }}'"
                                                    class="text-[#C8882A] hover:bg-[#FEF6EB]"
                                                >
                                                    Beri Rating
                                                </x-button>
                                            @endif
                                            
                                            @if ($act->tipe === 'pesan' && in_array($act->status, ['diproses', 'dikirim']))
                                                <a href="{{ $act->url }}">
                                                    <x-button variant="primary" size="sm">
                                                        Lacak Pesanan
                                                    </x-button>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </x-card>
                            </div>

                            <!-- central bullet connector -->
                            <div class="absolute left-6 md:left-1/2 -translate-x-[9.5px] mt-8 w-5 h-5 rounded-full border-4 border-[#FAF7F2] z-20 flex items-center justify-center
                                 @if($act->tipe === 'reservasi') bg-[#C8882A] @else bg-[#4CAF82] @endif">
                            </div>

                            <!-- spacer for right/left side -->
                            <div class="hidden md:block w-1/2"></div>
                        </div>
                    @endforeach
                </div>

                <!-- Custom Pagination Links -->
                <div class="pt-6">
                    @if ($paginatedActivities->hasPages())
                        <div class="flex items-center justify-between border-t border-[#E8E0D5] px-4 py-3 sm:px-6 mt-8">
                            <div class="flex flex-1 justify-between sm:hidden">
                                @if ($paginatedActivities->onFirstPage())
                                    <span class="relative inline-flex items-center rounded-md border border-[#D4C9BB] bg-white px-4 py-2 text-xs font-medium text-[#7A6A58] opacity-50 cursor-not-allowed">Sebelumnya</span>
                                @else
                                    <a href="{{ $paginatedActivities->previousPageUrl() }}" class="relative inline-flex items-center rounded-md border border-[#D4C9BB] bg-white px-4 py-2 text-xs font-medium text-[#1A1A1A] hover:bg-[#FAF7F2]">Sebelumnya</a>
                                @endif
                                @if ($paginatedActivities->hasMorePages())
                                    <a href="{{ $paginatedActivities->nextPageUrl() }}" class="relative ml-3 inline-flex items-center rounded-md border border-[#D4C9BB] bg-white px-4 py-2 text-xs font-medium text-[#1A1A1A] hover:bg-[#FAF7F2]">Selanjutnya</a>
                                @else
                                    <span class="relative ml-3 inline-flex items-center rounded-md border border-[#D4C9BB] bg-white px-4 py-2 text-xs font-medium text-[#7A6A58] opacity-50 cursor-not-allowed">Selanjutnya</span>
                                @endif
                            </div>
                            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs text-[#7A6A58]">
                                        Menampilkan
                                        <span class="font-medium font-mono">{{ $paginatedActivities->firstItem() ?? 0 }}</span>
                                        sampai
                                        <span class="font-medium font-mono">{{ $paginatedActivities->lastItem() ?? 0 }}</span>
                                        dari
                                        <span class="font-medium font-mono">{{ $paginatedActivities->total() }}</span>
                                        aktivitas
                                    </p>
                                </div>
                                <div>
                                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm bg-white" aria-label="Pagination">
                                        {{-- Previous Page Link --}}
                                        @if ($paginatedActivities->onFirstPage())
                                            <span class="relative inline-flex items-center rounded-l-md px-2.5 py-2 text-gray-400 border border-[#E8E0D5] opacity-50 cursor-not-allowed">
                                                <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @else
                                            <a href="{{ $paginatedActivities->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-md px-2.5 py-2 text-gray-700 border border-[#E8E0D5] hover:bg-gray-50">
                                                <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif

                                        {{-- Page Links --}}
                                        @foreach ($paginatedActivities->getUrlRange(1, $paginatedActivities->lastPage()) as $page => $url)
                                            @if ($page == $paginatedActivities->currentPage())
                                                <span aria-current="page" class="relative z-10 inline-flex items-center bg-[#C8882A] px-3.5 py-2 text-xs font-semibold text-white border border-[#C8882A]">{{ $page }}</span>
                                            @else
                                                <a href="{{ $url }}" class="relative inline-flex items-center px-3.5 py-2 text-xs font-semibold text-[#1A1A1A] border border-[#E8E0D5] hover:bg-gray-50">{{ $page }}</a>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($paginatedActivities->hasMorePages())
                                            <a href="{{ $paginatedActivities->nextPageUrl() }}" class="relative inline-flex items-center rounded-r-md px-2.5 py-2 text-gray-700 border border-[#E8E0D5] hover:bg-gray-50">
                                                <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @else
                                            <span class="relative inline-flex items-center rounded-r-md px-2.5 py-2 text-gray-400 border border-[#E8E0D5] opacity-50 cursor-not-allowed">
                                                <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </nav>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Rating Modal -->
        <div 
            x-show="showRatingModal" 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
            x-transition
            style="display: none;"
        >
            <div @click.away="showRatingModal = false" class="bg-white rounded-3xl border border-[#E8E0D5] max-w-md w-full overflow-hidden shadow-2xl">
                
                <!-- Modal Header -->
                <div class="bg-[#1A1A1A] text-white px-6 py-4 flex items-center justify-between border-b border-[#C8882A]/30">
                    <h3 class="font-serif text-base font-bold text-[#C8882A] tracking-wider">Beri Ulasan Hidangan</h3>
                    <button @click="showRatingModal = false" class="text-[#AB9BB0] hover:text-white text-lg font-bold">×</button>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6 space-y-5 font-body">
                    <p class="text-xs text-[#7A6A58] leading-relaxed">Bagaimana pengalaman rasa dan penyajian menu makanan Anda? Ulasan Anda membantu kami menjaga kualitas cita rasa Lumière Dining.</p>
                    
                    <!-- Stars Select -->
                    <div class="space-y-1.5 text-center">
                        <span class="block text-xs font-semibold text-[#4A3728]">Rating Bintang</span>
                        <div class="flex items-center justify-center space-x-1 pt-1">
                            <template x-for="i in 5">
                                <button 
                                    type="button" 
                                    @click="stars = i"
                                    class="text-2xl transition-colors duration-100 focus:outline-none"
                                    :class="i <= stars ? 'text-[#C8882A]' : 'text-[#E8E0D5]'"
                                >
                                    ★
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Feedback Textarea -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-[#4A3728]">Komentar Ulasan</label>
                        <textarea 
                            x-model="ratingKomentar" 
                            rows="3" 
                            placeholder="Bagikan rasa masakan, kebersihan, atau keramahan pelayan kami..." 
                            class="w-full px-3 py-2 text-xs border border-[#D4C9BB] rounded-xl focus:outline-none focus:ring-1 focus:ring-[#C8882A] focus:border-[#C8882A] resize-none"
                        ></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-3 pt-2">
                        <x-button type="button" @click="showRatingModal = false" variant="secondary" class="w-1/2">
                            Batal
                        </x-button>
                        <x-button type="button" @click="submitRating()" variant="primary" class="w-1/2">
                            Kirim Ulasan
                        </x-button>
                    </div>

                </div>

            </div>
        </div>

    </div>
</x-app-layout>

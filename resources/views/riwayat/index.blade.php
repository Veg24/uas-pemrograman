<x-app-layout>
    <div x-data="{
        showRatingModal: false,
        ratingAktivitasId: '',
        ratingAktivitasJudul: '',
        stars: 5,
        ratingKomentar: '',
        submitRating() {
            this.showRatingModal = false;
            alert('Terima kasih! Ulasan bintang ' + this.stars + ' Anda untuk ' + this.ratingAktivitasJudul + ' telah terkirim.');
            this.stars = 5;
            this.ratingKomentar = '';
        }
    }" class="space-y-6">

        <!-- Header -->
        <header class="flex items-center justify-between pb-6 border-b border-[#E8E0D5]">
            <div>
                <h1 class="font-serif text-3xl font-bold text-[#1A1A1A]">Riwayat Aktivitas</h1>
                <p class="text-sm font-body text-[#7A6A58] mt-1">Lacak semua reservasi dan pesanan Anda.</p>
            </div>
            
            <div class="flex items-center space-x-4">
                <span class="text-xs font-bold text-[#7A6A58]">Welcome, {{ Auth::user()->nama }}</span>
                <!-- Notification icon -->
                <button class="relative p-2 text-[#7A6A58] hover:text-[#C8882A] hover:bg-white rounded-full transition-all border border-[#E8E0D5] bg-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-[#E95252] ring-2 ring-white"></span>
                </button>
            </div>
        </header>

        <!-- Stats Boxes Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white border border-[#E8E0D5] rounded-2xl p-6 flex items-center space-x-4 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-[#FEF6EB] flex items-center justify-center text-[#C8882A]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-[#AB9BB0] uppercase tracking-wider">Total Reservasi</p>
                    <h3 class="text-3xl font-black text-[#1A1A1A] mt-0.5">{{ $totalReservasi }}</h3>
                </div>
            </div>

            <div class="bg-white border border-[#E8E0D5] rounded-2xl p-6 flex items-center space-x-4 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-[#EAF7F1] flex items-center justify-center text-[#4CAF82]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-[#AB9BB0] uppercase tracking-wider">Pesanan Selesai</p>
                    <h3 class="text-3xl font-black text-[#1A1A1A] mt-0.5">{{ $pesananSelesai }}</h3>
                </div>
            </div>

            <div class="bg-white border border-[#E8E0D5] rounded-2xl p-6 flex items-center space-x-4 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-[#FDF1F1] flex items-center justify-center text-[#E95252]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-[#AB9BB0] uppercase tracking-wider">Dibatalkan</p>
                    <h3 class="text-3xl font-black text-[#1A1A1A] mt-0.5">{{ $totalDibatalkan }}</h3>
                </div>
            </div>
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
                        class="px-4 py-2 rounded-full text-xs font-bold font-body border transition-all duration-150"
                        :class="'{{ $tab }}' === '{{ $tabKey }}' 
                            ? 'bg-[#C8882A] text-white border-[#C8882A] shadow-sm' 
                            : 'bg-white text-[#7A6A58] border-[#E8E0D5] hover:border-[#D4C9BB]'"
                    >
                        {{ $tabLabel }}
                    </a>
                @endforeach
            </div>

            <!-- Period Select Dropdown -->
            <div class="w-full sm:w-auto flex items-center space-x-2 text-xs bg-[#FAF7F2] border border-[#D4C9BB] rounded-xl px-3 py-2">
                <!-- Calendar outline icon -->
                <svg class="w-4 h-4 text-[#7A6A58]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <select 
                    onchange="location = this.value;"
                    class="bg-transparent border-0 p-0 text-xs font-bold text-[#1A1A1A] focus:outline-none focus:ring-0 cursor-pointer"
                >
                    <option value="{{ route('riwayat', array_merge(request()->query(), ['periode' => '30_days', 'page' => 1])) }}" {{ $periode === '30_days' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="{{ route('riwayat', array_merge(request()->query(), ['periode' => '6_months', 'page' => 1])) }}" {{ $periode === '6_months' ? 'selected' : '' }}>Last 6 Months</option>
                    <option value="{{ route('riwayat', array_merge(request()->query(), ['periode' => '1_year', 'page' => 1])) }}" {{ $periode === '1_year' ? 'selected' : '' }}>Last 1 Year</option>
                    <option value="{{ route('riwayat', array_merge(request()->query(), ['periode' => 'semua', 'page' => 1])) }}" {{ $periode === 'semua' ? 'selected' : '' }}>Semua Waktu</option>
                </select>
            </div>
        </div>

        <!-- Activities Timeline -->
        <div class="relative mt-8">
            @if ($paginatedActivities->isEmpty())
                <div class="text-center py-16 bg-white rounded-2xl border border-[#E8E0D5]">
                    <div class="inline-flex p-3 bg-[#FAF7F2] rounded-full text-[#AB9BB0] border border-[#E8E0D5] mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                            $statusColor = match($act->status) {
                                'dikonfirmasi', 'selesai' => 'bg-[#EAF7F1] text-[#4CAF82]',
                                'dibatalkan' => 'bg-[#FDF1F1] text-[#E95252]',
                                'menunggu', 'diproses', 'dikirim' => 'bg-[#FEF6EB] text-[#C8882A]',
                                default => 'bg-gray-100 text-gray-500'
                            };
                            $dotColor = match($act->status) {
                                'dikonfirmasi', 'selesai' => 'bg-[#4CAF82]',
                                'dibatalkan' => 'bg-[#E95252]',
                                'menunggu', 'diproses', 'dikirim' => 'bg-[#C8882A]',
                                default => 'bg-gray-400'
                            };
                        @endphp
                        
                        <!-- Timeline Item -->
                        <div class="flex flex-col md:flex-row items-start {{ $isEven ? 'md:flex-row-reverse' : '' }} w-full">
                            <!-- Left/Right Card Spacer -->
                            <div class="w-full md:w-1/2 px-0 md:px-8 pl-12 md:pl-0">
                                <div class="bg-white border border-[#E8E0D5] rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-200 space-y-4">
                                    
                                    <!-- Card Header -->
                                    <div class="flex items-start space-x-3">
                                        <!-- Type Icon box -->
                                        <div class="p-2.5 rounded-xl flex-shrink-0 {{ $act->tipe === 'reservasi' ? 'bg-[#FEF6EB] text-[#C8882A]' : 'bg-gray-100 text-[#7A6A58]' }}">
                                            @if($act->tipe === 'reservasi')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                                </svg>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <span class="text-[10px] font-bold text-[#AB9BB0] uppercase block">
                                                {{ $act->created_at->diffForHumans() }}
                                            </span>
                                            <h4 class="font-bold text-sm text-[#1A1A1A] mt-0.5 truncate">
                                                @if($act->tipe === 'reservasi')
                                                    Reservasi — Meja {{ $act->meja_nomor }} · {{ $act->area === 'indoor' ? 'Indoor' : 'Terrace' }} · {{ $act->jumlah_tamu }} Tamu
                                                @else
                                                    Pesanan Makanan — {{ $act->tipe_pesanan === 'dine_in' ? 'Dine In' : 'Delivery' }}
                                                @endif
                                            </h4>
                                        </div>

                                        @if($act->tipe === 'pesan')
                                            <span class="font-bold text-xs text-[#1A1A1A] text-right font-mono block pt-1">
                                                Rp {{ number_format($act->total_harga, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Description / Notes -->
                                    <div class="flex justify-between items-center pt-2">
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                            {{ $act->status }}
                                        </span>

                                        <div class="flex items-center space-x-2">
                                            @if ($act->status === 'selesai' && $act->tipe === 'reservasi')
                                                <button 
                                                    type="button" 
                                                    @click="showRatingModal = true; ratingAktivitasId = '{{ $act->tipe . '_' . $act->id }}'; ratingAktivitasJudul = '{{ $act->judul }}'"
                                                    class="text-xs font-bold text-[#C8882A] hover:underline"
                                                >
                                                    Beri Rating ★
                                                </button>
                                            @elseif ($act->tipe === 'pesan' && in_array($act->status, ['diproses', 'dikirim']))
                                                <a href="{{ $act->url }}" class="bg-[#C8882A] hover:bg-[#B67720] text-white px-3 py-1 rounded text-[10px] font-bold uppercase tracking-wider transition-colors">
                                                    Lacak Pesanan
                                                </a>
                                            @else
                                                <a href="{{ $act->url }}" class="text-xs font-bold text-[#C8882A] hover:underline">
                                                    Lihat Detail &gt;
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Central Bullet Connector -->
                            <div class="absolute left-6 md:left-1/2 -translate-x-[9.5px] mt-8 w-5 h-5 rounded-full border-4 border-[#FAF7F2] z-20 flex items-center justify-center {{ $dotColor }}">
                            </div>

                            <!-- Spacer for Right/Left Side -->
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
                        <button type="button" @click="showRatingModal = false" class="w-1/2 bg-white hover:bg-gray-50 border border-[#D4C9BB] text-[#7A6A58] py-2.5 rounded-lg text-xs font-bold transition-all">
                            Batal
                        </button>
                        <button type="button" @click="submitRating()" class="w-1/2 bg-[#1A1A1A] hover:bg-gray-800 text-white py-2.5 rounded-lg text-xs font-bold transition-all">
                            Kirim Ulasan
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </div>
</x-app-layout>

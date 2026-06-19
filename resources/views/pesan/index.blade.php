<x-app-layout>
    <div x-data="{
        cart: [],
        tipe: 'dine_in',
        metodePembayaran: 'qris',
        promoCode: '',
        appliedPromo: false,
        catatan: '',
        namaPenerima: '',
        alamatPenerima: '',
        searchQuery: '{{ request('search', '') }}',
        kategoriSelected: '{{ request('kategori', 'semua') }}',

        init() {
            // Load cart from localStorage if exists
            const savedCart = localStorage.getItem('lumiere_cart');
            if (savedCart) {
                try {
                    this.cart = JSON.parse(savedCart);
                } catch(e) {
                    this.cart = [];
                }
            }
            this.$watch('cart', value => {
                localStorage.setItem('lumiere_cart', JSON.stringify(value));
                // Update global cart badge if any
                const badge = document.getElementById('cart-badge-count');
                if (badge) {
                    const count = value.reduce((sum, item) => sum + item.jumlah, 0);
                    badge.innerText = count;
                    badge.style.display = count > 0 ? 'inline-flex' : 'none';
                }
            });
            // Initial badge update
            setTimeout(() => {
                const badge = document.getElementById('cart-badge-count');
                if (badge) {
                    const count = this.cart.reduce((sum, item) => sum + item.jumlah, 0);
                    badge.innerText = count;
                    badge.style.display = count > 0 ? 'inline-flex' : 'none';
                }
            }, 100);
        },

        addToCart(id, nama, harga, stok, imageUrl) {
            const existing = this.cart.find(item => item.menu_id === id);
            if (existing) {
                if (existing.jumlah < stok) {
                    existing.jumlah++;
                } else {
                    alert('Stok menu ini sudah mencapai batas maksimum pembelian.');
                }
            } else {
                this.cart.push({
                    menu_id: id,
                    nama_menu: nama,
                    harga: parseFloat(harga),
                    jumlah: 1,
                    stok: stok,
                    image_url: imageUrl
                });
            }
        },

        updateQty(id, delta) {
            const item = this.cart.find(i => i.menu_id === id);
            if (item) {
                const newQty = item.jumlah + delta;
                if (newQty <= 0) {
                    this.removeItem(id);
                } else if (newQty <= item.stok) {
                    item.jumlah = newQty;
                } else {
                    alert('Stok menu ini tidak mencukupi.');
                }
            }
        },

        removeItem(id) {
            this.cart = this.cart.filter(i => i.menu_id !== id);
        },

        clearCart() {
            this.cart = [];
        },

        get subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
        },

        get diskon() {
            if (this.appliedPromo && this.promoCode.toUpperCase() === 'LUMIERE10') {
                return this.subtotal * 0.10;
            }
            return 0;
        },

        get total() {
            return this.subtotal - this.diskon;
        },

        applyPromo() {
            if (this.promoCode.toUpperCase() === 'LUMIERE10') {
                this.appliedPromo = true;
            } else {
                alert('Kode promo tidak valid.');
                this.appliedPromo = false;
            }
        },

        removePromo() {
            this.promoCode = '';
            this.appliedPromo = false;
        },

        submitCheckout() {
            if (this.cart.length === 0) {
                alert('Keranjang belanja Anda masih kosong.');
                return;
            }
            if (this.tipe === 'delivery' && (!this.namaPenerima || !this.alamatPenerima)) {
                alert('Harap isi Nama dan Alamat Penerima untuk pengiriman.');
                return;
            }
            // Clear cart from localstorage on submit
            localStorage.removeItem('lumiere_cart');
            this.$refs.checkoutForm.submit();
        },

        filterCategory(kat) {
            this.kategoriSelected = kat;
            this.updateFilters();
        },

        updateFilters() {
            const url = new URL(window.location.href);
            url.searchParams.set('search', this.searchQuery);
            url.searchParams.set('kategori', this.kategoriSelected);
            window.location.href = url.toString();
        }
    }" class="space-y-6">

        <!-- Header -->
        <div class="pb-4 border-b border-[#E8E0D5]">
            <h1 class="font-serif text-3xl font-bold text-[#1A1A1A]">Pilihan Menu</h1>
        </div>

        <!-- 3 Columns Layout (Middle Catalog + Right Cart) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Catalog Column (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Search & Filters -->
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-center bg-white p-4 rounded-2xl border border-[#E8E0D5]">
                    <!-- Search Input -->
                    <div class="relative w-full sm:w-72">
                        <input 
                            type="text" 
                            x-model="searchQuery"
                            @keyup.enter="updateFilters()"
                            placeholder="Cari menu..."
                            class="w-full pl-10 pr-4 py-2 border border-[#D4C9BB] rounded-xl font-body text-xs text-[#1A1A1A] focus:outline-none focus:ring-2 focus:ring-[#C8882A]/20 focus:border-[#C8882A]"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#AB9BB0]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Category filter tabs -->
                    <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                        @foreach ([
                            'semua' => 'Semua',
                            'makanan_utama' => 'Makanan Utama',
                            'appetizer' => 'Appetizer',
                            'minuman' => 'Minuman',
                            'dessert' => 'Dessert'
                        ] as $katKey => $katLabel)
                            <button 
                                type="button"
                                @click="filterCategory('{{ $katKey }}')"
                                class="px-4 py-2 rounded-full text-xs font-bold font-body border transition-all duration-150"
                                :class="kategoriSelected === '{{ $katKey }}' 
                                    ? 'bg-[#C8882A] text-white border-[#C8882A] shadow-sm' 
                                    : 'bg-white text-[#7A6A58] border-[#E8E0D5] hover:border-[#D4C9BB]'"
                            >
                                {{ $katLabel }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Menu Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @forelse ($menus as $menu)
                        <div class="bg-white border border-[#E8E0D5] rounded-2xl overflow-hidden shadow-sm flex flex-col justify-between transition-all duration-200 hover:shadow-md">
                            
                            <!-- Menu Image -->
                            <div class="h-44 w-full relative overflow-hidden bg-[#FAF7F2] border-b border-[#E8E0D5]">
                                @if ($menu->image_url)
                                    <img src="{{ asset($menu->image_url) }}" alt="{{ $menu->nama_menu }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-tr from-[#1A1A1A] to-[#4A3728] flex items-center justify-center">
                                        <span class="text-center font-serif text-white/95 text-base font-bold tracking-wide">{{ $menu->nama_menu }}</span>
                                    </div>
                                @endif
                                
                                <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 z-10">
                                    @if ($menu->is_populer)
                                        <span class="bg-[#C8882A] text-white text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">Populer</span>
                                    @endif
                                    @if ($menu->stok <= 0)
                                        <span class="bg-[#E95252] text-white text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">Stok Habis</span>
                                    @endif
                                </div>
                                
                                <div class="absolute top-2.5 right-2.5 z-10">
                                    <span class="bg-black/50 text-white text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                                        @if($menu->kategori == 'makanan_utama') Utama @elseif($menu->kategori == 'minuman') Minuman @elseif($menu->kategori == 'dessert') Dessert @else Appetizer @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Menu Body -->
                            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                                <div class="space-y-1">
                                    <h4 class="font-serif text-base font-bold text-[#1A1A1A]">{{ $menu->nama_menu }}</h4>
                                    <p class="text-xs text-[#7A6A58] leading-relaxed line-clamp-2">{{ $menu->deskripsi }}</p>
                                </div>

                                <div class="flex items-center justify-between pt-2 border-t border-[#F5F1EB]" x-data="{ 
                                    get cartItem() { return cart.find(item => item.menu_id == '{{ $menu->id }}') }
                                }">
                                    <span class="font-bold text-xs font-mono text-[#C8882A]">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                                    
                                    <template x-if="cartItem">
                                        <div class="flex items-center space-x-1.5 bg-[#FAF7F2] border border-[#D4C9BB] rounded-lg p-0.5">
                                            <button type="button" @click="updateQty('{{ $menu->id }}', -1)" class="w-5 h-5 rounded bg-white flex items-center justify-center font-bold text-[10px] hover:bg-gray-100">-</button>
                                            <span class="font-mono font-bold text-[10px] px-1" x-text="cartItem.jumlah"></span>
                                            <button type="button" @click="updateQty('{{ $menu->id }}', 1)" class="w-5 h-5 rounded bg-white flex items-center justify-center font-bold text-[10px] hover:bg-gray-100">+</button>
                                        </div>
                                    </template>
                                    <template x-if="!cartItem">
                                        <button 
                                            type="button"
                                            @click="addToCart('{{ $menu->id }}', '{{ $menu->nama_menu }}', '{{ $menu->harga }}', {{ $menu->stok }}, '{{ $menu->image_url }}')"
                                            @if ($menu->stok <= 0) disabled @endif
                                            class="w-6 h-6 rounded-full border border-[#C8882A] text-[#C8882A] hover:bg-[#C8882A] hover:text-white flex items-center justify-center font-bold transition-all disabled:opacity-30 disabled:cursor-not-allowed text-xs"
                                        >
                                            +
                                        </button>
                                    </template>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-[#E8E0D5]">
                            <p class="text-sm text-[#7A6A58]">Tidak ada hidangan yang cocok dengan kriteria pencarian Anda.</p>
                        </div>
                    @endforelse
                </div>

            </div>

            <!-- Cart Column (1/3 width) -->
            <div class="space-y-6">
                <div class="sticky top-6">
                    <div class="bg-white border border-[#E8E0D5] rounded-2xl p-5 shadow-sm space-y-5">
                        <div class="flex justify-between items-center pb-3 border-b border-[#F5F1EB]">
                            <h3 class="text-base font-bold font-serif text-[#1A1A1A]">Keranjang Pesanan</h3>
                            <button type="button" @click="clearCart()" class="text-xs text-[#E95252] font-semibold hover:underline" x-show="cart.length > 0">Bersihkan</button>
                        </div>

                        <!-- Cart Empty -->
                        <div x-show="cart.length === 0" class="text-center py-12 space-y-3">
                            <div class="inline-flex p-3 bg-[#FAF7F2] rounded-full text-[#AB9BB0] border border-[#E8E0D5]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <p class="text-xs text-[#7A6A58]">Keranjang Anda masih kosong.<br>Pilih hidangan lezat di katalog.</p>
                        </div>

                        <!-- Cart Items -->
                        <div x-show="cart.length > 0" class="space-y-5" style="display: none;">
                            
                            <!-- Toggle Dine In / Delivery -->
                            <div class="flex bg-[#FAF7F2] p-1 rounded-xl border border-[#E8E0D5]">
                                <button 
                                    type="button" 
                                    @click="tipe = 'dine_in'"
                                    class="w-1/2 py-2 text-xs font-semibold rounded-lg transition-all"
                                    :class="tipe === 'dine_in' ? 'bg-white text-[#1A1A1A] shadow-sm border border-[#E8E0D5]/50' : 'text-[#7A6A58] hover:text-[#1A1A1A]'"
                                >
                                    Dine-in
                                </button>
                                <button 
                                    type="button" 
                                    @click="tipe = 'delivery'"
                                    class="w-1/2 py-2 text-xs font-semibold rounded-lg transition-all"
                                    :class="tipe === 'delivery' ? 'bg-white text-[#1A1A1A] shadow-sm border border-[#E8E0D5]/50' : 'text-[#7A6A58] hover:text-[#1A1A1A]'"
                                >
                                    Delivery
                                </button>
                            </div>

                            <!-- Cart Items List -->
                            <div class="max-h-60 overflow-y-auto space-y-4 pr-1">
                                <template x-for="item in cart" :key="item.menu_id">
                                    <div class="flex items-center space-x-3 bg-[#FAF8F5] border border-[#E8E0D5]/60 rounded-xl p-3 relative">
                                        <!-- Item Image -->
                                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0">
                                            <template x-if="item.image_url">
                                                <img :src="'/' + item.image_url" :alt="item.nama_menu" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!item.image_url">
                                                <div class="w-full h-full bg-[#3D2D1E] flex items-center justify-center text-white font-serif font-bold text-xs">L</div>
                                            </template>
                                        </div>

                                        <!-- Item Details -->
                                        <div class="flex-1 min-w-0 pr-4">
                                            <p class="font-bold text-xs text-[#1A1A1A] truncate" x-text="item.nama_menu"></p>
                                            <p class="text-[10px] text-[#AB9BB0] mt-0.5" x-text="'Rp ' + item.harga.toLocaleString('id-ID')"></p>
                                            
                                            <!-- Qty Counter inside Card -->
                                            <div class="flex items-center space-x-1.5 mt-2 bg-white border border-[#E8E0D5] rounded-md p-0.5 w-16 justify-between">
                                                <button type="button" @click="updateQty(item.menu_id, -1)" class="w-4 h-4 rounded flex items-center justify-center text-[10px] hover:bg-gray-100 font-bold">-</button>
                                                <span class="font-mono text-[9px] font-bold text-center" x-text="item.jumlah"></span>
                                                <button type="button" @click="updateQty(item.menu_id, 1)" class="w-4 h-4 rounded flex items-center justify-center text-[10px] hover:bg-gray-100 font-bold">+</button>
                                            </div>
                                        </div>

                                        <!-- Close Button top right -->
                                        <button type="button" @click="removeItem(item.menu_id)" class="absolute top-2 right-2 text-gray-400 hover:text-[#E95252] text-sm">
                                            ×
                                        </button>

                                        <!-- Total Price right -->
                                        <span class="font-bold text-[11px] text-[#C8882A] absolute bottom-3 right-3 font-mono" x-text="'Rp ' + (item.harga * item.jumlah).toLocaleString('id-ID')"></span>
                                    </div>
                                </template>
                            </div>

                            <!-- Delivery Form Inputs (only visible if tipe is delivery) -->
                            <div x-show="tipe === 'delivery'" class="space-y-3 pt-2 border-t border-[#F5F1EB] transition-all" x-transition>
                                <div>
                                    <label class="block text-[10px] font-bold text-[#7A6A58] mb-1">NAMA PENERIMA</label>
                                    <input type="text" x-model="namaPenerima" placeholder="Nama Penerima" class="w-full px-3 py-2 text-xs border border-[#D4C9BB] rounded-xl bg-[#FAF8F5] focus:outline-none focus:border-[#C8882A]">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-[#7A6A58] mb-1">ALAMAT LENGKAP</label>
                                    <textarea x-model="alamatPenerima" rows="2" placeholder="Alamat Lengkap Pengiriman" class="w-full px-3 py-2 text-xs border border-[#D4C9BB] rounded-xl bg-[#FAF8F5] focus:outline-none focus:border-[#C8882A] resize-none"></textarea>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="space-y-1.5 pt-2 border-t border-[#F5F1EB]">
                                <label class="block text-[10px] font-bold text-[#7A6A58]">CATATAN KHUSUS</label>
                                <textarea x-model="catatan" rows="2" placeholder="Tulis permintaan khusus Anda di sini..." class="w-full px-3 py-2 text-xs border border-[#D4C9BB] rounded-xl bg-[#FAF8F5] focus:outline-none focus:border-[#C8882A] resize-none"></textarea>
                            </div>

                            <!-- Promo Code Input -->
                            <div class="pt-2 border-t border-[#F5F1EB] space-y-2">
                                <label class="block text-[10px] font-bold text-[#7A6A58]">KODE PROMO</label>
                                <div class="flex space-x-2">
                                    <input 
                                        type="text" 
                                        x-model="promoCode" 
                                        placeholder="Masukkan kode..." 
                                        :disabled="appliedPromo"
                                        class="flex-1 px-3 py-2 text-xs border border-[#D4C9BB] rounded-xl uppercase bg-[#FAF8F5] focus:outline-none"
                                    >
                                    <button 
                                        type="button" 
                                        @click="appliedPromo ? removePromo() : applyPromo()"
                                        class="px-3 py-2 text-xs font-semibold rounded-xl border transition-colors"
                                        :class="appliedPromo ? 'bg-[#E95252] text-white border-[#E95252]' : 'bg-[#FAF7F2] text-[#7A6A58] border-[#D4C9BB] hover:bg-[#E8E0D5]'"
                                        x-text="appliedPromo ? 'Hapus' : 'Pakai'"
                                    ></button>
                                </div>
                                <p class="text-[9px] text-[#AB9BB0]">Gunakan kode <span class="font-bold text-[#C8882A]">LUMIERE10</span> untuk potongan 10%.</p>
                            </div>

                            <!-- Totals -->
                            <div class="pt-3 border-t border-[#F5F1EB] space-y-2 text-xs">
                                <div class="flex justify-between text-[#7A6A58]">
                                    <span>Subtotal</span>
                                    <span class="font-mono font-bold" x-text="'Rp ' + subtotal.toLocaleString('id-ID')"></span>
                                </div>
                                <div class="flex justify-between text-[#E95252]" x-show="appliedPromo">
                                    <span>Discount Promo</span>
                                    <span class="font-mono font-bold" x-text="'-Rp ' + diskon.toLocaleString('id-ID')"></span>
                                </div>
                                <div class="flex justify-between items-center text-xs font-bold border-t border-[#F5F1EB] pt-2 text-[#1A1A1A]">
                                    <span>Total Keseluruhan</span>
                                    <span class="font-mono text-sm text-[#C8882A]" x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                                </div>
                            </div>

                            <!-- Payment Method Selection -->
                            <div class="pt-3 border-t border-[#F5F1EB] space-y-2 text-xs">
                                <span class="block font-bold text-[10px] text-[#7A6A58] uppercase">Metode Pembayaran</span>
                                <div class="space-y-2">
                                    <!-- QRIS -->
                                    <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer hover:border-[#C8882A] transition-colors"
                                           :class="metodePembayaran === 'qris' ? 'border-[#C8882A] bg-[#FEF6EB]' : 'border-[#D4C9BB] bg-white'">
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" x-model="metodePembayaran" value="qris" class="hidden">
                                            <span class="font-bold text-xs">QRIS</span>
                                        </div>
                                        <!-- QR code simple outline icon -->
                                        <svg class="w-4 h-4 text-[#C8882A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m-6 4v1m0 4v1m0 4v1M4 12h1m15 0h1m-5 4v1m-4 4v1M6 6h2v2H6V6zm0 10h2v2H6v-2zm10-10h2v2h-2V6zm-3 7h1v1h-1v-1zm2 2h1v1h-1v-1z"/>
                                        </svg>
                                    </label>
                                    
                                    <!-- Bank Transfer -->
                                    <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer hover:border-[#C8882A] transition-colors"
                                           :class="metodePembayaran === 'transfer' ? 'border-[#C8882A] bg-[#FEF6EB]' : 'border-[#D4C9BB] bg-white'">
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" x-model="metodePembayaran" value="transfer" class="hidden">
                                            <span class="font-bold text-xs">Bank Transfer</span>
                                        </div>
                                        <!-- Bank temple simple outline icon -->
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </label>
                                    
                                    <!-- Cash -->
                                    <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer hover:border-[#C8882A] transition-colors"
                                           :class="metodePembayaran === 'cash' ? 'border-[#C8882A] bg-[#FEF6EB]' : 'border-[#D4C9BB] bg-white'"
                                           x-show="tipe === 'dine_in'">
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" x-model="metodePembayaran" value="cash" class="hidden">
                                            <span class="font-bold text-xs">Cash (Dine-in only)</span>
                                        </div>
                                        <!-- Cash bill simple outline icon -->
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </label>
                                </div>
                            </div>

                            <!-- Hidden Checkout Submission Form -->
                            <form method="POST" action="{{ route('pesan.store') }}" x-ref="checkoutForm" style="display: none;">
                                @csrf
                                <input type="hidden" name="tipe" :value="tipe">
                                <input type="hidden" name="metode_pembayaran" :value="metodePembayaran">
                                <input type="hidden" name="catatan" :value="catatan">
                                <input type="hidden" name="nama_penerima" :value="namaPenerima">
                                <input type="hidden" name="alamat_penerima" :value="alamatPenerima">
                                <input type="hidden" name="promo_code" :value="promoCode">
                                
                                <template x-for="(item, index) in cart" :key="item.menu_id">
                                    <div>
                                        <input type="hidden" :name="'items['+index+'][menu_id]'" :value="item.menu_id">
                                        <input type="hidden" :name="'items['+index+'][jumlah]'" :value="item.jumlah">
                                    </div>
                                </template>
                            </form>

                            <!-- Submit button -->
                            <div class="pt-2">
                                <button type="button" @click="submitCheckout()" class="w-full bg-[#C8882A] hover:bg-[#B67720] text-white py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-150">
                                    Checkout Sekarang
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>

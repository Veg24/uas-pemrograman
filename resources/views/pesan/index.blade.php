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

        addToCart(id, nama, harga, stok) {
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
                    stok: stok
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
            <h1 class="font-serif text-3xl font-black text-[#1A1A1A]">Pesan Makanan</h1>
            <p class="text-sm font-body text-[#7A6A58] mt-1">Pesan hidangan premium Lumière Dining langsung ke meja Anda atau kirim ke rumah.</p>
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
                            placeholder="Cari hidangan..."
                            class="w-full pl-10 pr-4 py-2 border border-[#D4C9BB] rounded-xl font-body text-sm text-[#1A1A1A] focus:outline-none focus:ring-2 focus:ring-[#C8882A]/20 focus:border-[#C8882A]"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#AB9BB0]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                                class="px-4 py-2 rounded-xl text-xs font-semibold font-body border transition-all duration-150"
                                :class="kategoriSelected === '{{ $katKey }}' 
                                    ? 'bg-[#1A1A1A] text-white border-[#1A1A1A] shadow-sm' 
                                    : 'bg-[#FAF7F2] text-[#7A6A58] border-[#E8E0D5] hover:border-[#D4C9BB]'"
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
                            
                            <!-- Menu Image Placeholder -->
                            <div class="h-44 bg-gradient-to-tr from-[#1A1A1A] to-[#4A3728] relative flex items-center justify-center p-4">
                                <span class="text-center font-serif text-white/95 text-base font-bold tracking-wide">{{ $menu->nama_menu }}</span>
                                
                                <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5">
                                    @if ($menu->is_populer)
                                        <x-badge variant="warning" class="bg-[#C8882A] text-white border-none text-[9px]">Populer</x-badge>
                                    @endif
                                    @if ($menu->stok <= 0)
                                        <x-badge variant="error" class="bg-[#E95252] text-white border-none text-[9px]">Stok Habis</x-badge>
                                    @endif
                                </div>
                            </div>

                            <!-- Menu Body -->
                            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                <div class="space-y-1">
                                    <span class="text-[10px] uppercase font-bold text-[#AB9BB0]">{{ str_replace('_', ' ', $menu->kategori) }}</span>
                                    <h4 class="font-serif text-base font-bold text-[#1A1A1A]">{{ $menu->nama_menu }}</h4>
                                    <p class="text-xs text-[#7A6A58] leading-relaxed line-clamp-3">{{ $menu->deskripsi }}</p>
                                </div>

                                <div class="flex items-center justify-between pt-3 border-t border-[#F5F1EB]">
                                    <span class="font-bold text-sm font-mono text-[#C8882A]">Rp{{ number_format($menu->harga, 0, ',', '.') }}</span>
                                    
                                    <button 
                                        type="button"
                                        @click="addToCart('{{ $menu->id }}', '{{ $menu->nama_menu }}', '{{ $menu->harga }}', {{ $menu->stok }})"
                                        @if ($menu->stok <= 0) disabled @endif
                                        class="w-8 h-8 rounded-lg bg-[#FAF7F2] text-[#1A1A1A] border border-[#D4C9BB] flex items-center justify-center font-bold hover:bg-[#C8882A] hover:text-white hover:border-[#C8882A] transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        +
                                    </button>
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
                    <x-card class="shadow-lg border border-[#E8E0D5] p-5">
                        <x-slot:headerSlot>
                            <div class="flex justify-between items-center">
                                <h3 class="text-base font-bold font-serif text-[#1A1A1A]">Keranjang Saya</h3>
                                <button type="button" @click="clearCart()" class="text-xs text-[#E95252] font-semibold hover:underline" x-show="cart.length > 0">Bersihkan</button>
                            </div>
                        </x-slot:headerSlot>

                        <!-- Cart Empty -->
                        <div x-show="cart.length === 0" class="text-center py-12 space-y-3">
                            <div class="inline-flex p-3 bg-[#FAF7F2] rounded-full text-[#AB9BB0] border border-[#E8E0D5]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <p class="text-xs text-[#7A6A58]">Keranjang Anda masih kosong.<br>Pilih hidangan lezat di katalog.</p>
                        </div>

                        <!-- Cart Items -->
                        <div x-show="cart.length > 0" class="space-y-6" style="display: none;">
                            
                            <!-- Toggle Dine In / Delivery -->
                            <div class="flex bg-[#FAF7F2] p-1 rounded-xl border border-[#E8E0D5]">
                                <button 
                                    type="button" 
                                    @click="tipe = 'dine_in'"
                                    class="w-1/2 py-2 text-xs font-semibold rounded-lg transition-all"
                                    :class="tipe === 'dine_in' ? 'bg-[#1A1A1A] text-white shadow-sm' : 'text-[#7A6A58] hover:text-[#1A1A1A]'"
                                >
                                    Dine In
                                </button>
                                <button 
                                    type="button" 
                                    @click="tipe = 'delivery'"
                                    class="w-1/2 py-2 text-xs font-semibold rounded-lg transition-all"
                                    :class="tipe === 'delivery' ? 'bg-[#1A1A1A] text-white shadow-sm' : 'text-[#7A6A58] hover:text-[#1A1A1A]'"
                                >
                                    Delivery
                                </button>
                            </div>

                            <!-- Cart Items List -->
                            <div class="max-h-60 overflow-y-auto space-y-3.5 pr-1">
                                <template x-for="item in cart" :key="item.menu_id">
                                    <div class="flex items-center justify-between text-xs pb-3 border-b border-[#F5F1EB] last:border-0 last:pb-0">
                                        <div class="flex-1 pr-3">
                                            <p class="font-bold text-[#1A1A1A]" x-text="item.nama_menu"></p>
                                            <p class="text-[#C8882A] font-mono mt-0.5" x-text="'Rp' + (item.harga * item.jumlah).toLocaleString('id-ID')"></p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button type="button" @click="updateQty(item.menu_id, -1)" class="w-6 h-6 rounded bg-[#FAF7F2] border border-[#D4C9BB] flex items-center justify-center font-bold text-xs">-</button>
                                            <span class="font-mono font-bold w-6 text-center" x-text="item.jumlah"></span>
                                            <button type="button" @click="updateQty(item.menu_id, 1)" class="w-6 h-6 rounded bg-[#FAF7F2] border border-[#D4C9BB] flex items-center justify-center font-bold text-xs">+</button>
                                            <button type="button" @click="removeItem(item.menu_id)" class="text-[#E95252] hover:text-[#c93f3f] pl-1 text-sm font-semibold">×</button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Delivery Form Inputs (only visible if tipe is delivery) -->
                            <div x-show="tipe === 'delivery'" class="space-y-3.5 pt-4 border-t border-[#F5F1EB] transition-all" x-transition>
                                <div>
                                    <label class="block text-[11px] font-semibold text-[#7A6A58] mb-1">Nama Penerima</label>
                                    <input type="text" x-model="namaPenerima" placeholder="Nama Penerima" class="w-full px-3 py-2 text-xs border border-[#D4C9BB] rounded-xl focus:outline-none focus:ring-1 focus:ring-[#C8882A] focus:border-[#C8882A]">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-[#7A6A58] mb-1">Alamat Lengkap</label>
                                    <textarea x-model="alamatPenerima" rows="2" placeholder="Alamat Lengkap Pengiriman" class="w-full px-3 py-2 text-xs border border-[#D4C9BB] rounded-xl focus:outline-none focus:ring-1 focus:ring-[#C8882A] focus:border-[#C8882A] resize-none"></textarea>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="space-y-1.5 pt-4 border-t border-[#F5F1EB]">
                                <label class="block text-[11px] font-semibold text-[#7A6A58]">Catatan Khusus Pesanan</label>
                                <textarea x-model="catatan" rows="2" placeholder="Contoh: Saus dipisah, kurangi pedas..." class="w-full px-3 py-2 text-xs border border-[#D4C9BB] rounded-xl focus:outline-none focus:ring-1 focus:ring-[#C8882A] focus:border-[#C8882A] resize-none"></textarea>
                            </div>

                            <!-- Promo Code Input -->
                            <div class="pt-4 border-t border-[#F5F1EB] space-y-2">
                                <label class="block text-[11px] font-semibold text-[#7A6A58]">Kode Promo</label>
                                <div class="flex space-x-2">
                                    <input 
                                        type="text" 
                                        x-model="promoCode" 
                                        placeholder="Masukkan kode..." 
                                        :disabled="appliedPromo"
                                        class="flex-1 px-3 py-2 text-xs border border-[#D4C9BB] rounded-xl uppercase focus:outline-none focus:ring-1 focus:ring-[#C8882A]"
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
                            <div class="pt-4 border-t border-[#F5F1EB] space-y-2 text-xs">
                                <div class="flex justify-between text-[#7A6A58]">
                                    <span>Subtotal</span>
                                    <span class="font-mono" x-text="'Rp' + subtotal.toLocaleString('id-ID')"></span>
                                </div>
                                <div class="flex justify-between text-[#E95252]" x-show="appliedPromo">
                                    <span>Diskon (10%)</span>
                                    <span class="font-mono" x-text="'-Rp' + diskon.toLocaleString('id-ID')"></span>
                                </div>
                                <div class="flex justify-between items-center text-sm font-bold border-t border-[#F5F1EB] pt-2 text-[#1A1A1A]">
                                    <span>Total Keseluruhan</span>
                                    <span class="font-mono text-base text-[#C8882A]" x-text="'Rp' + total.toLocaleString('id-ID')"></span>
                                </div>
                            </div>

                            <!-- Payment Method Selection -->
                            <div class="pt-4 border-t border-[#F5F1EB] space-y-2 text-xs">
                                <span class="block font-semibold text-[#7A6A58]">Metode Pembayaran</span>
                                <div class="grid grid-cols-3 gap-2">
                                    <label class="flex flex-col items-center justify-center p-2 border rounded-xl cursor-pointer text-center hover:border-[#C8882A] transition-colors"
                                           :class="metodePembayaran === 'qris' ? 'border-[#C8882A] bg-[#FEF6EB]' : 'border-[#D4C9BB] bg-white'">
                                        <input type="radio" x-model="metodePembayaran" value="qris" class="hidden">
                                        <span class="font-bold block text-[10px]">QRIS</span>
                                    </label>
                                    
                                    <label class="flex flex-col items-center justify-center p-2 border rounded-xl cursor-pointer text-center hover:border-[#C8882A] transition-colors"
                                           :class="metodePembayaran === 'transfer' ? 'border-[#C8882A] bg-[#FEF6EB]' : 'border-[#D4C9BB] bg-white'">
                                        <input type="radio" x-model="metodePembayaran" value="transfer" class="hidden">
                                        <span class="font-bold block text-[10px]">Transfer</span>
                                    </label>
                                    
                                    <label class="flex flex-col items-center justify-center p-2 border rounded-xl cursor-pointer text-center hover:border-[#C8882A] transition-colors"
                                           :class="metodePembayaran === 'cash' ? 'border-[#C8882A] bg-[#FEF6EB]' : 'border-[#D4C9BB] bg-white'"
                                           x-show="tipe === 'dine_in'">
                                        <input type="radio" x-model="metodePembayaran" value="cash" class="hidden">
                                        <span class="font-bold block text-[10px]">Tunai</span>
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
                            <div class="pt-4">
                                <x-button type="button" @click="submitCheckout()" variant="primary" class="w-full">
                                    Checkout Sekarang
                                </x-button>
                            </div>

                        </div>
                    </x-card>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>

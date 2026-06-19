<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Reservasi;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\AuditLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed a default User
        User::create([
            'nama' => 'Lumiere Guest',
            'email' => 'guest@lumiere.com',
            'no_hp' => '081234567890',
            'password' => Hash::make('password'),
        ]);

        // 2. Seed Mejas
        $mejas = [
            ['nomor_meja' => 1, 'kapasitas' => 2, 'area' => 'indoor', 'status' => 'tersedia'],
            ['nomor_meja' => 2, 'kapasitas' => 4, 'area' => 'indoor', 'status' => 'tersedia'],
            ['nomor_meja' => 3, 'kapasitas' => 4, 'area' => 'indoor', 'status' => 'tersedia'],
            ['nomor_meja' => 4, 'kapasitas' => 6, 'area' => 'indoor', 'status' => 'tersedia'],
            ['nomor_meja' => 5, 'kapasitas' => 4, 'area' => 'terrace', 'status' => 'tersedia'],
            ['nomor_meja' => 6, 'kapasitas' => 8, 'area' => 'terrace', 'status' => 'tersedia'],
            ['nomor_meja' => 7, 'kapasitas' => 2, 'area' => 'indoor', 'status' => 'tersedia'],
            ['nomor_meja' => 8, 'kapasitas' => 2, 'area' => 'indoor', 'status' => 'tersedia'],
            ['nomor_meja' => 9, 'kapasitas' => 6, 'area' => 'indoor', 'status' => 'tersedia'],
            ['nomor_meja' => 10, 'kapasitas' => 4, 'area' => 'terrace', 'status' => 'tersedia'],
            ['nomor_meja' => 11, 'kapasitas' => 2, 'area' => 'terrace', 'status' => 'tersedia'],
            ['nomor_meja' => 12, 'kapasitas' => 6, 'area' => 'terrace', 'status' => 'tersedia'],
        ];

        foreach ($mejas as $meja) {
            Meja::create($meja);
        }

        // 3. Seed Menus
        $menus = [
            [
                'nama_menu' => 'Seared Scallops',
                'harga' => 125000.00,
                'deskripsi' => 'Pan-seared scallops with pea purée and microgreens.',
                'kategori' => 'appetizer',
                'stok' => 20,
                'is_populer' => false,
                'image_url' => 'images/seared_scallops.png',
            ],
            [
                'nama_menu' => 'Truffle Tenderloin',
                'harga' => 350000.00,
                'deskripsi' => 'Premium beef tenderloin with shaved black truffles.',
                'kategori' => 'makanan_utama',
                'stok' => 15,
                'is_populer' => true,
                'image_url' => 'images/truffle_tenderloin.png',
            ],
            [
                'nama_menu' => 'Truffle Pasta',
                'harga' => 185000.00,
                'deskripsi' => 'Handmade tagliatelle with rich truffle cream.',
                'kategori' => 'makanan_utama',
                'stok' => 25,
                'is_populer' => false,
                'image_url' => 'images/truffle_pasta.png',
            ],
            [
                'nama_menu' => 'Valhona Dome',
                'harga' => 95000.00,
                'deskripsi' => 'Dark chocolate dome with raspberry filling.',
                'kategori' => 'dessert',
                'stok' => 10,
                'is_populer' => false,
                'image_url' => 'images/valhona_dome.png',
            ],
            [
                'nama_menu' => 'Garden Salad',
                'harga' => 75000.00,
                'deskripsi' => 'Fresh seasonal greens with citrus dressing.',
                'kategori' => 'appetizer',
                'stok' => 30,
                'is_populer' => false,
                'image_url' => 'images/garden_salad.png',
            ],
            [
                'nama_menu' => 'Amber Sunset',
                'harga' => 110000.00,
                'deskripsi' => 'Signature cocktail with bourbon and orange.',
                'kategori' => 'minuman',
                'stok' => 40,
                'is_populer' => false,
                'image_url' => 'images/amber_sunset.png',
            ],
            [
                'nama_menu' => 'Wagyu Tenderloin MB7',
                'harga' => 380000.00,
                'deskripsi' => 'Highly marbled Wagyu tenderloin, grilled to perfection, roasted root vegetables.',
                'kategori' => 'makanan_utama',
                'stok' => 12,
                'is_populer' => true,
                'image_url' => 'images/wagyu_tenderloin.png',
            ],
            [
                'nama_menu' => 'Chocolate Entremet',
                'harga' => 85000.00,
                'deskripsi' => 'Multi-layered mousse cake with glossy chocolate glaze.',
                'kategori' => 'dessert',
                'stok' => 15,
                'is_populer' => false,
                'image_url' => 'images/chocolate_entremet.png',
            ],
            [
                'nama_menu' => 'Lumière Signature',
                'harga' => 120000.00,
                'deskripsi' => 'Our house special luxury cocktail blend of aged spirits and gold flakes.',
                'kategori' => 'minuman',
                'stok' => 50,
                'is_populer' => true,
                'image_url' => 'images/lumiere_signature.png',
            ],
            [
                'nama_menu' => 'Escargot Bourguignonne',
                'harga' => 95000.00,
                'deskripsi' => 'Escargots baked in garlic, parsley, and French butter.',
                'kategori' => 'appetizer',
                'stok' => 18,
                'is_populer' => false,
                'image_url' => 'images/escargot_bourguignonne.png',
            ],
            [
                'nama_menu' => 'Pan-Seared Salmon',
                'harga' => 210000.00,
                'deskripsi' => 'Atlantic salmon fillet with crispy skin, buttered asparagus, and lemon-dill sauce.',
                'kategori' => 'makanan_utama',
                'stok' => 20,
                'is_populer' => false,
                'image_url' => 'images/pan_seared_salmon.png',
            ],
            [
                'nama_menu' => 'Classic Mojito',
                'harga' => 80000.00,
                'deskripsi' => 'Refreshing cocktail with muddled mint leaves, lime juice, white rum, and sparkling water.',
                'kategori' => 'minuman',
                'stok' => 45,
                'is_populer' => false,
                'image_url' => 'images/classic_mojito.png',
            ],
            [
                'nama_menu' => 'Lobster Thermidor',
                'harga' => 380000.00,
                'deskripsi' => 'Luxury Lobster Thermidor baked in its shell with a creamy cognac cheese sauce and golden browned crust.',
                'kategori' => 'makanan_utama',
                'stok' => 8,
                'is_populer' => true,
                'image_url' => 'images/lobster_thermidor.png',
            ],
            [
                'nama_menu' => 'Crème Brûlée',
                'harga' => 75000.00,
                'deskripsi' => 'Classic French dessert with vanilla custard and a perfectly glossy caramelized amber sugar crust.',
                'kategori' => 'dessert',
                'stok' => 20,
                'is_populer' => false,
                'image_url' => 'images/creme_brulee.png',
            ],
            [
                'nama_menu' => 'Foie Gras Poêlé',
                'harga' => 180000.00,
                'deskripsi' => 'Seared foie gras on toasted brioche bread, drizzled with a rich blackberry reduction.',
                'kategori' => 'appetizer',
                'stok' => 10,
                'is_populer' => false,
                'image_url' => 'images/foie_gras_poele.png',
            ],
            [
                'nama_menu' => 'Duck Confit',
                'harga' => 240000.00,
                'deskripsi' => 'Crispy French Duck Confit leg served over smooth potato purée and red wine reduction.',
                'kategori' => 'makanan_utama',
                'stok' => 14,
                'is_populer' => false,
                'image_url' => 'images/duck_confit.png',
            ],
            [
                'nama_menu' => 'Mushroom Risotto',
                'harga' => 165000.00,
                'deskripsi' => 'Creamy wild mushroom risotto topped with shaved parmesan cheese and aromatic black truffle oil.',
                'kategori' => 'makanan_utama',
                'stok' => 25,
                'is_populer' => false,
                'image_url' => 'images/truffle_pasta.png',
            ],
            [
                'nama_menu' => 'Espresso Martini',
                'harga' => 95000.00,
                'deskripsi' => 'Sophisticated coffee cocktail made with freshly brewed espresso, coffee liqueur, and premium vodka.',
                'kategori' => 'minuman',
                'stok' => 30,
                'is_populer' => false,
                'image_url' => 'images/amber_sunset.png',
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }

        // 4. Seed Mock Reservations
        Reservasi::create([
            'user_id' => 1,
            'meja_id' => 1,
            'tanggal' => Carbon::now()->subDays(2)->toDateString(),
            'jam' => '18:00:00',
            'jumlah_tamu' => 2,
            'catatan' => 'Rayakan ulang tahun pernikahan.',
            'status' => 'selesai',
            'biaya_deposit' => 50000.00,
            'created_at' => Carbon::now()->subDays(2),
        ]);

        Reservasi::create([
            'user_id' => 1,
            'meja_id' => 4,
            'tanggal' => Carbon::now()->addDays(3)->toDateString(),
            'jam' => '19:00:00',
            'jumlah_tamu' => 5,
            'catatan' => 'Minta meja dekat jendela.',
            'status' => 'dikonfirmasi',
            'biaya_deposit' => 50000.00,
            'created_at' => Carbon::now(),
        ]);

        // 5. Seed Mock Orders (Pesanans)
        $pesanan1 = Pesanan::create([
            'user_id' => 1,
            'total_harga' => 475000.00,
            'status' => 'selesai',
            'metode_pembayaran' => 'qris',
            'tipe' => 'dine_in',
            'catatan' => 'Sajikan setelah hidangan pembuka.',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        PesananItem::create([
            'pesanan_id' => $pesanan1->id,
            'menu_id' => 2, // Truffle Tenderloin
            'jumlah' => 1,
            'harga_satuan' => 350000.00,
            'subtotal' => 350000.00,
        ]);

        PesananItem::create([
            'pesanan_id' => $pesanan1->id,
            'menu_id' => 1, // Seared Scallops
            'jumlah' => 1,
            'harga_satuan' => 125000.00,
            'subtotal' => 125000.00,
        ]);

        $pesanan2 = Pesanan::create([
            'user_id' => 1,
            'total_harga' => 205000.00,
            'status' => 'diproses',
            'metode_pembayaran' => 'transfer',
            'tipe' => 'dine_in',
            'catatan' => 'Es batu dipisah.',
            'created_at' => Carbon::now(),
        ]);

        PesananItem::create([
            'pesanan_id' => $pesanan2->id,
            'menu_id' => 6, // Amber Sunset
            'jumlah' => 1,
            'harga_satuan' => 110000.00,
            'subtotal' => 110000.00,
        ]);

        PesananItem::create([
            'pesanan_id' => $pesanan2->id,
            'menu_id' => 4, // Valhona Dome
            'jumlah' => 1,
            'harga_satuan' => 95000.00,
            'subtotal' => 95000.00,
        ]);

        // 6. Seed Mock Audit Logs
        AuditLog::create([
            'user_id' => 1,
            'aksi' => 'Registrasi',
            'keterangan' => 'User baru berhasil mendaftar',
            'timestamp' => Carbon::now()->subDays(5),
        ]);

        AuditLog::create([
            'user_id' => 1,
            'aksi' => 'Login',
            'keterangan' => 'User berhasil login ke sistem',
            'timestamp' => Carbon::now()->subDays(2),
        ]);

        AuditLog::create([
            'user_id' => 1,
            'aksi' => 'Reservasi',
            'keterangan' => 'Melakukan reservasi Meja #1 (Indoor) untuk tanggal ' . Carbon::now()->subDays(2)->format('d M Y'),
            'timestamp' => Carbon::now()->subDays(2),
        ]);

        AuditLog::create([
            'user_id' => 1,
            'aksi' => 'Pemesanan Makanan',
            'keterangan' => 'Membuat pesanan makanan #' . $pesanan1->id . ' sebesar Rp475.000',
            'timestamp' => Carbon::now()->subDays(2),
        ]);

        AuditLog::create([
            'user_id' => 1,
            'aksi' => 'Reservasi',
            'keterangan' => 'Melakukan reservasi Meja #4 (Indoor) untuk tanggal ' . Carbon::now()->addDays(3)->format('d M Y'),
            'timestamp' => Carbon::now(),
        ]);

        AuditLog::create([
            'user_id' => 1,
            'aksi' => 'Pemesanan Makanan',
            'keterangan' => 'Membuat pesanan makanan #' . $pesanan2->id . ' sebesar Rp205.000',
            'timestamp' => Carbon::now(),
        ]);
    }
}

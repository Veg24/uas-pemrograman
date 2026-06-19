# 🍽️ Lumière Dining - Premium Restaurant Management System

Lumière Dining adalah sistem manajemen pemesanan meja (*table reservation*) dan pemesanan menu hidangan premium (*food ordering*) berbasis web. Proyek ini dikembangkan dengan antarmuka yang mewah, premium, dan responsif menggunakan **Laravel 11**, **Tailwind CSS**, dan **Alpine.js**.

Sistem ini dirancang khusus untuk memberikan pengalaman pengguna kelas atas, mulai dari visualisasi estetika *warm gold & brown*, pendaftaran bertahap, pemilihan zona meja, transaksi makanan secara interaktif, hingga pelacakan riwayat pesanan yang komprehensif.

---

## ✨ Fitur Utama

### 1. Sistem Autentikasi Ganda
* **Registrasi & Login Terintegrasi**: Pendaftaran bertahap (*multi-step stepper form*) yang menawan secara visual.
* **Autentikasi Fleksibel**: Pengguna dapat masuk menggunakan **Email** atau **Nomor Handphone (`no_hp`)**.
* **Log Audit Keamanan**: Setiap aktivitas pendaftaran, masuk (*login*), dan keluar (*logout*) dicatat secara otomatis dalam tabel `audit_logs` di database.
* **Email Sambutan Otomatis**: Pengiriman HTML Welcome Email yang didesain eksklusif langsung setelah pengguna berhasil mendaftar.

### 2. Reservasi Meja Eksklusif
* **Zona Area Meja**: Pilihan area makan yaitu **Indoor** (mewah, tenang) atau **Terrace** (terbuka, pemandangan luar).
* **Grid Kalender & Slot Waktu**: Memilih tanggal reservasi dan waktu kedatangan secara visual yang responsif.
* **Manajemen Meja**: Sistem otomatis mengunci nomor meja yang dipilih berdasarkan kapasitas tamu dan area.

### 3. Pemesanan Makanan Premium
* **Kategori Menu**: Filtrasi hidangan yang interaktif (Makanan Utama, Appetizer, Dessert, dan Minuman).
* **Keranjang Belanja Real-Time**: Penambahan, pengurangan, dan penghapusan item hidangan secara instan menggunakan micro-interactions.
* **Metode Pembayaran**: Dukungan pilihan pembayaran modern seperti **QRIS**, **Transfer Bank**, atau **Tunai (Cash)**.

### 4. Dasbor & Riwayat Aktivitas
* **Informasi Dasbor Dinamis**: Menampilkan hitung mundur (*countdown*) reservasi aktif mendatang, kartu statistik pesanan, dan menu-menu terpopuler.
* **Timeline Aktivitas**: Catatan transaksi reservasi, pemesanan hidangan, serta log aktivitas pengguna yang disajikan dalam format *vertical timeline*.

---

## 🛠️ Teknologi & Arsitektur

Sistem dibangun menggunakan kombinasi teknologi modern berstandar industri:
* **Backend**: Laravel 11.x & PHP 8.5+
* **Database**: MySQL (Development) & SQLite In-Memory (Automated testing)
* **Frontend**: Tailwind CSS & Alpine.js (untuk reaktivitas tanpa *bloat*)
* **Build Tool**: Vite Asset Bundler
* **Pengujian**: PHPUnit (TDD Ready)

---

## 🚀 Panduan Instalasi & Penggunaan

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di lingkungan lokal Anda (seperti Laragon, XAMPP, atau PHP CLI):

### 1. Kloning Repositori
```bash
git clone https://github.com/username/uas_pemrograman.git
cd uas_pemrograman
```

### 2. Instalasi Dependensi
Instal dependensi backend PHP (Composer) dan frontend JavaScript (NPM):
```bash
composer install
npm install
```

### 3. Konfigurasi Lingkungan (.env)
Salin file konfigurasi `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
*Pastikan konfigurasi database di file `.env` Anda sudah diarahkan ke MySQL lokal Anda.*
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lumiere_dining
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key & Jalankan Migrasi + Seeder
Buat database baru dengan nama `lumiere_dining` di MySQL server Anda, kemudian jalankan:
```bash
php artisan key:generate
php artisan migrate:fresh --seed
```
*Perintah di atas akan membuat semua struktur tabel yang dibutuhkan dan mengisi database dengan default meja, menu hidangan premium, serta akun uji coba.*

### 5. Jalankan Server Pengembangan
Jalankan server Laravel dan compiler aset Vite:
```bash
# Terminal 1: Menjalankan Laravel Server
php artisan serve

# Terminal 2: Menjalankan Vite Development Server
npm run dev
```
Buka peramban (browser) Anda dan akses **`http://localhost:8000`**.

---

## 🧪 Akun Uji Coba (Default Seeded Users)
Untuk mencoba fitur aplikasi tanpa registrasi ulang, gunakan kredensial berikut:
* **Email / No. HP**: `guest@lumiere.com` atau `081234567890`
* **Password**: `password`

---

## 🎯 Menjalankan Unit & Integration Test
Aplikasi telah dilengkapi dengan pengujian terotomatisasi (*automated tests*) untuk menguji validasi registrasi, autentikasi ganda, pengiriman email welcome, dan session persistence.

Jalankan perintah berikut:
```bash
php artisan test
```

---

## 📄 Lisensi
Proyek ini dilisensikan di bawah [MIT License](LICENSE).

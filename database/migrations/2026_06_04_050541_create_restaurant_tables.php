<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. mejas
        Schema::create('mejas', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_meja');
            $table->integer('kapasitas');
            $table->enum('area', ['indoor', 'terrace']);
            $table->enum('status', ['tersedia', 'terisi', 'maintenance'])->default('tersedia');
            $table->timestamps();
        });

        // 2. menus
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu');
            $table->decimal('harga', 12, 2);
            $table->text('deskripsi');
            $table->enum('kategori', ['makanan_utama', 'appetizer', 'minuman', 'dessert']);
            $table->integer('stok');
            $table->boolean('is_populer')->default(false);
            $table->string('image_url')->nullable();
            $table->timestamps();
        });

        // 3. reservasis
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('meja_id')->constrained('mejas')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam');
            $table->integer('jumlah_tamu');
            $table->text('catatan')->nullable();
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'dibatalkan', 'selesai'])->default('menunggu');
            $table->decimal('biaya_deposit', 10, 2)->default(50000.00);
            $table->timestamps();
        });

        // 4. pesanans
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_harga', 12, 2);
            $table->enum('status', ['diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('diproses');
            $table->enum('metode_pembayaran', ['qris', 'transfer', 'cash']);
            $table->enum('tipe', ['dine_in', 'delivery']);
            $table->string('nama_penerima')->nullable();
            $table->text('alamat_penerima')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 5. pesanan_items
        Schema::create('pesanan_items', function (Blueprint $table) { // using table name pesanan_items as requested
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // 6. audit_logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('aksi');
            $table->text('keterangan');
            $table->dateTime('timestamp')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('pesanan_items');
        Schema::dropIfExists('pesanans');
        Schema::dropIfExists('reservasis');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('mejas');
    }
};

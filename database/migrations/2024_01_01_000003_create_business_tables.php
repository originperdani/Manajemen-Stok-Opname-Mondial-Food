<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kategori Produk
        Schema::create('kategori_produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Produk (Kue/Roti)
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_produk')->onDelete('cascade');
            $table->string('nama_produk');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(5);
            $table->string('gambar')->nullable();
            $table->string('satuan')->default('pcs');
            $table->decimal('berat', 8, 2)->nullable()->comment('dalam gram');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // Bahan Baku
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan');
            $table->string('satuan'); // kg, liter, pcs, gram
            $table->decimal('stok', 12, 2)->default(0);
            $table->decimal('stok_minimum', 12, 2)->default(10);
            $table->decimal('harga_per_satuan', 12, 2)->default(0);
            $table->string('supplier')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Resep
        Schema::create('resep', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->string('nama_resep');
            $table->text('instruksi')->nullable();
            $table->integer('hasil_produksi')->default(1)->comment('jumlah produk yg dihasilkan');
            $table->integer('waktu_produksi')->nullable()->comment('dalam menit');
            $table->timestamps();
        });

        // Detail Resep (bahan baku yang dibutuhkan)
        Schema::create('resep_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resep_id')->constrained('resep')->onDelete('cascade');
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->onDelete('cascade');
            $table->decimal('jumlah', 12, 2);
            $table->string('satuan');
            $table->timestamps();
        });

        // Produksi
        Schema::create('produksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->foreignId('resep_id')->constrained('resep')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('jumlah_produksi');
            $table->date('tanggal_produksi');
            $table->enum('status', ['proses', 'selesai', 'gagal'])->default('proses');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Transaksi
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('kasir_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('tipe', ['online', 'pos'])->default('online');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('ongkir', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('status', ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('pending');
            $table->text('catatan')->nullable();
            $table->string('nama_pelanggan')->nullable();
            $table->string('phone_pelanggan')->nullable();
            $table->text('alamat_pengiriman')->nullable();
            $table->timestamps();
        });

        // Detail Transaksi
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // Pembayaran
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            $table->enum('metode', ['qris', 'e_wallet', 'm_banking', 'bayar_ditempat'])->default('bayar_ditempat');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->decimal('kembalian', 15, 2)->default(0);
            $table->enum('status', ['pending', 'berhasil', 'gagal'])->default('pending');
            $table->string('referensi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamps();
        });

        // Pengiriman
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            $table->enum('metode_kirim', ['ambil_sendiri', 'kurir_toko', 'grabfood', 'gofood'])->default('ambil_sendiri');
            $table->text('alamat_tujuan')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->string('phone_penerima')->nullable();
            $table->enum('status', ['menunggu', 'diproses', 'dikirim', 'diterima'])->default('menunggu');
            $table->string('no_resi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_kirim')->nullable();
            $table->timestamp('tanggal_terima')->nullable();
            $table->timestamps();
        });

        // Keranjang
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->timestamps();
        });

        // Log Stok Bahan Baku
        Schema::create('stok_log', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['bahan_baku', 'produk']);
            $table->unsignedBigInteger('referensi_id');
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->decimal('jumlah', 12, 2);
            $table->decimal('stok_sebelum', 12, 2);
            $table->decimal('stok_sesudah', 12, 2);
            $table->string('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_log');
        Schema::dropIfExists('keranjang');
        Schema::dropIfExists('pengiriman');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('detail_transaksi');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('produksi');
        Schema::dropIfExists('resep_detail');
        Schema::dropIfExists('resep');
        Schema::dropIfExists('bahan_baku');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('kategori_produk');
    }
};

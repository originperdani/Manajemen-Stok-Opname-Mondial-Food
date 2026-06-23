<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN status ENUM('belum_bayar', 'pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan') DEFAULT 'belum_bayar'");
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status ENUM('belum_bayar', 'pending', 'berhasil', 'gagal') DEFAULT 'belum_bayar'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN status ENUM('pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan') DEFAULT 'pending'");
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status ENUM('pending', 'berhasil', 'gagal') DEFAULT 'pending'");
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update data lama
        DB::table('pembayaran')->where('metode', 'bayar_ditempat')->update(['metode' => 'cash']);
        
        // Ubah enum
        DB::statement("ALTER TABLE `pembayaran` MODIFY COLUMN `metode` ENUM('cash','qris','mandiri','bca','bri','bni','e_wallet','m_banking','midtrans') DEFAULT 'cash'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `pembayaran` MODIFY COLUMN `metode` ENUM('cash','qris','mandiri','bca','bri','bni','e_wallet','m_banking') DEFAULT 'cash'");
    }
};

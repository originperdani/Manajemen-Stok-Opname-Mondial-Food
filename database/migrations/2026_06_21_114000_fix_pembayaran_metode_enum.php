<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update data lama yang menggunakan metode bayar_ditempat ke cash
        DB::table('pembayaran')->where('metode', 'bayar_ditempat')->update(['metode' => 'cash']);
        
        // Sekarang ubah ENUM
        DB::statement("ALTER TABLE `pembayaran` MODIFY COLUMN `metode` ENUM('cash', 'qris', 'mandiri', 'bca', 'bri', 'bni', 'e_wallet', 'm_banking') DEFAULT 'cash'");
    }

    public function down(): void
    {
        // Kembalikan data ke bayar_ditempat
        DB::table('pembayaran')->where('metode', 'cash')->update(['metode' => 'bayar_ditempat']);
        
        DB::statement("ALTER TABLE `pembayaran` MODIFY COLUMN `metode` ENUM('qris', 'e_wallet', 'm_banking', 'bayar_ditempat') DEFAULT 'bayar_ditempat'");
    }
};

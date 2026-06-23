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
        Schema::table('pengiriman', function (Blueprint $table) {
            $table->enum('metode_kirim', ['ambil_sendiri', 'kurir_toko', 'kurir_ojol', 'grabfood', 'gofood'])->default('ambil_sendiri')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            $table->enum('metode_kirim', ['ambil_sendiri', 'kurir_toko', 'grabfood', 'gofood'])->default('ambil_sendiri')->change();
        });
    }
};

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
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->enum('metode', ['qris', 'e_wallet', 'm_banking', 'bayar_ditempat', 'mandiri', 'bca', 'bri', 'bni', 'cash'])->default('bayar_ditempat')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->enum('metode', ['qris', 'e_wallet', 'm_banking', 'bayar_ditempat'])->default('bayar_ditempat')->change();
        });
    }
};

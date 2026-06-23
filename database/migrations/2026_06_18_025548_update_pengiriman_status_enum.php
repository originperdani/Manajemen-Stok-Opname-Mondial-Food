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
            $table->enum('status', ['menunggu', 'diproses', 'dikirim', 'siap_diambil', 'diterima'])->default('menunggu')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            $table->enum('status', ['menunggu', 'diproses', 'dikirim', 'diterima'])->default('menunggu')->change();
        });
    }
};

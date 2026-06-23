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
        Schema::dropIfExists('produk_opsi_produk');
        Schema::dropIfExists('resep_opsi_produk');
        Schema::dropIfExists('produk_opsi_nilai');
        Schema::dropIfExists('produk_opsi');
        
        Schema::table('detail_transaksi', function (Blueprint $table) {
            if (Schema::hasColumn('detail_transaksi', 'opsi')) {
                $table->dropColumn('opsi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

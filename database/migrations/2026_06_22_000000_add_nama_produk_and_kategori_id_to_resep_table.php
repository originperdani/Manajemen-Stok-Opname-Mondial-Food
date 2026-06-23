<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resep', function (Blueprint $table) {
            $table->string('nama_produk')->nullable()->after('id');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_produk')->onDelete('set null')->after('nama_produk');
            $table->foreignId('produk_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('resep', function (Blueprint $table) {
            $table->dropColumn(['nama_produk', 'kategori_id']);
            // We can't easily revert the nullable change for produk_id without original migration, so skip that
        });
    }
};

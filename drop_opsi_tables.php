<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Menghapus tabel-tabel produk_opsi...\n";

try {
    // Drop tables in reverse order of creation to avoid foreign key issues
    Schema::dropIfExists('produk_opsi_produk');
    echo "✓ produk_opsi_produk dihapus\n";
    
    Schema::dropIfExists('resep_opsi_produk');
    echo "✓ resep_opsi_produk dihapus\n";
    
    Schema::dropIfExists('produk_opsi_nilai');
    echo "✓ produk_opsi_nilai dihapus\n";
    
    Schema::dropIfExists('produk_opsi');
    echo "✓ produk_opsi dihapus\n";
    
    // Drop column from detail_transaksi
    if (Schema::hasColumn('detail_transaksi', 'opsi')) {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->dropColumn('opsi');
        });
        echo "✓ Kolom opsi dihapus dari detail_transaksi\n";
    }
    
    echo "\n✅ Semua tabel dan kolom produk_opsi berhasil dihapus!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

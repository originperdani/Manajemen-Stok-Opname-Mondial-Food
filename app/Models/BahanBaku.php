<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';
    protected $fillable = ['nama_bahan', 'satuan', 'stok', 'stok_minimum', 'harga_per_satuan', 'supplier', 'keterangan'];
    protected $casts = ['stok' => 'decimal:2', 'stok_minimum' => 'decimal:2', 'harga_per_satuan' => 'decimal:2'];

    public function resepDetail() { return $this->hasMany(ResepDetail::class, 'bahan_baku_id'); }

    public function isStokMenipis(): bool { return $this->stok <= $this->stok_minimum; }
}

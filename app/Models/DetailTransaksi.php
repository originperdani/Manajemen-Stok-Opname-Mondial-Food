<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';
    protected $fillable = ['transaksi_id', 'produk_id', 'jumlah', 'harga_satuan', 'subtotal'];
    protected $casts = ['harga_satuan' => 'decimal:2', 'subtotal' => 'decimal:2'];

    public function transaksi() { return $this->belongsTo(Transaksi::class, 'transaksi_id'); }
    public function produk() { return $this->belongsTo(Produk::class, 'produk_id'); }
}

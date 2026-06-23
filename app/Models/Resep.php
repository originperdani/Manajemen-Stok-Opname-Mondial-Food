<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table = 'resep';
    protected $fillable = ['produk_id', 'nama_resep', 'instruksi', 'hasil_produksi', 'waktu_produksi', 'nama_produk', 'kategori_id'];

    public function produk() { return $this->belongsTo(Produk::class, 'produk_id'); }
    public function kategori() { return $this->belongsTo(KategoriProduk::class, 'kategori_id'); }
    public function detail() { return $this->hasMany(ResepDetail::class, 'resep_id'); }
    public function produksi() { return $this->hasMany(Produksi::class, 'resep_id'); }
}

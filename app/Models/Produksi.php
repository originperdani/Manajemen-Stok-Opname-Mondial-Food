<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $table = 'produksi';
    protected $fillable = ['produk_id', 'resep_id', 'user_id', 'jumlah_produksi', 'tanggal_produksi', 'status', 'catatan'];
    protected $casts = ['tanggal_produksi' => 'date'];

    public function produk() { return $this->belongsTo(Produk::class, 'produk_id'); }
    public function resep() { return $this->belongsTo(Resep::class, 'resep_id'); }
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
}

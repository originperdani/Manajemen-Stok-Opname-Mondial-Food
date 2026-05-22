<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokLog extends Model
{
    protected $table = 'stok_log';
    protected $fillable = ['tipe', 'referensi_id', 'jenis', 'jumlah', 'stok_sebelum', 'stok_sesudah', 'keterangan', 'user_id'];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
}

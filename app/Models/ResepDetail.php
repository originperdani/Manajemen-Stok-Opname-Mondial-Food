<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResepDetail extends Model
{
    protected $table = 'resep_detail';
    protected $fillable = ['resep_id', 'bahan_baku_id', 'jumlah', 'satuan'];

    public function resep() { return $this->belongsTo(Resep::class, 'resep_id'); }
    public function bahanBaku() { return $this->belongsTo(BahanBaku::class, 'bahan_baku_id'); }
}

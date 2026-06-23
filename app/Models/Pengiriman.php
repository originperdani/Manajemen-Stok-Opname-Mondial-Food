<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';
    protected $fillable = [
        'transaksi_id', 'metode_kirim', 'alamat_tujuan', 'nama_penerima',
        'phone_penerima', 'status', 'no_resi', 'catatan', 'tanggal_kirim', 'tanggal_terima',
    ];
    protected $casts = ['tanggal_kirim' => 'datetime', 'tanggal_terima' => 'datetime'];

    public function transaksi() { return $this->belongsTo(Transaksi::class, 'transaksi_id'); }

    public function getMetodeKirimLabelAttribute(): string
    {
        return match($this->metode_kirim) {
            'ambil_sendiri' => 'Ambil Sendiri',
            'kurir_toko' => 'Kurir Toko',
            'kurir_ojol' => 'Kurir Toko / Ojek Online',
            'grabfood' => 'GrabFood',
            'gofood' => 'GoFood',
            default => $this->metode_kirim,
        };
    }
}

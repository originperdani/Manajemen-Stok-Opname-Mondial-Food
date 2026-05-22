<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $fillable = ['transaksi_id', 'metode', 'jumlah_bayar', 'kembalian', 'status', 'referensi', 'keterangan', 'tanggal_bayar'];
    protected $casts = ['jumlah_bayar' => 'decimal:2', 'kembalian' => 'decimal:2', 'tanggal_bayar' => 'datetime'];

    public function transaksi() { return $this->belongsTo(Transaksi::class, 'transaksi_id'); }

    public function getMetodeLabelAttribute(): string
    {
        return match($this->metode) {
            'qris' => 'QRIS',
            'e_wallet' => 'E-Wallet',
            'm_banking' => 'M-Banking',
            'bayar_ditempat' => 'Bayar di Tempat',
            default => $this->metode,
        };
    }
}

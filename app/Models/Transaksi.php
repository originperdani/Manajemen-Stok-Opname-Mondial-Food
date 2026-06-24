<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = [
        'kode_transaksi', 'user_id', 'kasir_id', 'tipe', 'subtotal', 'diskon',
        'ongkir', 'total', 'status', 'catatan', 'nama_pelanggan',
        'phone_pelanggan', 'email_pelanggan', 'alamat_pengiriman',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'kasir_id' => 'integer',
        'subtotal' => 'decimal:2',
        'diskon' => 'decimal:2',
        'ongkir' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function kasir() { return $this->belongsTo(User::class, 'kasir_id'); }
    public function detail() { return $this->hasMany(DetailTransaksi::class, 'transaksi_id'); }
    public function pembayaran() { return $this->hasOne(Pembayaran::class, 'transaksi_id'); }
    public function pengiriman() { return $this->hasOne(Pengiriman::class, 'transaksi_id'); }

    public static function generateKode(): string
    {
        $prefix = 'TRX-' . date('Ymd');
        $last = static::where('kode_transaksi', 'like', $prefix . '%')->orderBy('id', 'desc')->first();
        $number = $last ? ((int) substr($last->kode_transaksi, -4)) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}

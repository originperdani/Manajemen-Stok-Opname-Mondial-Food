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
            'cash' => 'CASH',
            'qris' => 'QRIS',
            'mandiri' => 'Mandiri',
            'bca' => 'BCA',
            'bri' => 'BRI',
            'bni' => 'BNI',
            'permata' => 'Permata',
            'cimb' => 'CIMB',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'dana' => 'DANA',
            'ovo' => 'OVO',
            'e_wallet' => 'E-Wallet',
            'm_banking' => 'M-Banking',
            'alfamart' => 'Alfamart',
            'indomaret' => 'Indomaret',
            'akulaku' => 'Akulaku',
            'kredivo' => 'Kredivo',
            'midtrans' => 'Midtrans',
            default => ucfirst(str_replace('_', ' ', $this->metode)),
        };
    }

    /**
     * Extract the actual payment method from a Midtrans notification/status response.
     * Maps payment_type + bank/issuer info into our internal metode values.
     */
    public static function extractMidtransPaymentMethod($response): string
    {
        $paymentType = $response->payment_type ?? null;

        switch ($paymentType) {
            case 'bank_transfer':
                // VA payment — check va_numbers array or permata
                if (!empty($response->va_numbers) && is_array($response->va_numbers)) {
                    return strtolower($response->va_numbers[0]->bank ?? 'midtrans');
                }
                if (!empty($response->permata_va_number)) {
                    return 'permata';
                }
                return 'midtrans';

            case 'echannel':
                // Mandiri Bill Payment
                return 'mandiri';

            case 'gopay':
                return 'gopay';

            case 'shopeepay':
                return 'shopeepay';

            case 'qris':
                // Could be GoPay QRIS, ShopeePay QRIS, etc.
                $acquirer = $response->acquirer ?? null;
                if ($acquirer === 'gopay') return 'gopay';
                if ($acquirer === 'airpay shopee') return 'shopeepay';
                return 'qris';

            case 'cstore':
                // Convenience store (Alfamart, Indomaret)
                $store = strtolower($response->store ?? '');
                if ($store === 'alfamart') return 'alfamart';
                if ($store === 'indomaret') return 'indomaret';
                return 'midtrans';

            case 'akulaku':
                return 'akulaku';

            case 'kredivo':
                return 'kredivo';

            case 'credit_card':
                return 'credit_card';

            default:
                return 'midtrans';
        }
    }
}

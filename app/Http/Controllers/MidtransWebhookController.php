<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Set Midtrans config
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.env') === 'production';
        
        try {
            $notif = new Notification();
            
            $transactionStatus = $notif->transaction_status;
            $orderId = $notif->order_id;
            $fraudStatus = $notif->fraud_status;
            
            // Strip retry suffix (e.g. -R1, -R2) to find original transaction
            $kodeTransaksi = preg_replace('/-R\d+$/', '', $orderId);
            
            // Find transaction by kode_transaksi
            $transaksi = Transaksi::where('kode_transaksi', $kodeTransaksi)->first();
            
            if (!$transaksi) {
                return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
            }
            
            // Update pembayaran status
            $pembayaran = $transaksi->pembayaran;
            
            if ($pembayaran) {
                if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'accept') {
                        $actualMetode = Pembayaran::extractMidtransPaymentMethod($notif);
                        $pembayaran->update([
                            'status' => 'berhasil',
                            'metode' => $actualMetode,
                            'tanggal_bayar' => now()
                        ]);
                        $transaksi->update(['status' => 'pending']);
                        
                        // Kurangi stok
                        foreach ($transaksi->detail as $item) {
                            $produk = $item->produk;
                            if ($produk) {
                                $stokSebelum = $produk->stok;
                                $produk->decrement('stok', $item->jumlah);
                                
                                \App\Models\StokLog::create([
                                    'tipe' => 'produk',
                                    'referensi_id' => $produk->id,
                                    'jenis' => 'keluar',
                                    'jumlah' => $item->jumlah,
                                    'stok_sebelum' => $stokSebelum,
                                    'stok_sesudah' => $produk->stok,
                                    'keterangan' => 'Pesanan Online #' . $transaksi->kode_transaksi,
                                    'user_id' => $transaksi->user_id,
                                ]);
                            }
                        }
                    }
                } elseif ($transactionStatus == 'settlement') {
                    $actualMetode = Pembayaran::extractMidtransPaymentMethod($notif);
                    $pembayaran->update([
                        'status' => 'berhasil',
                        'metode' => $actualMetode,
                        'tanggal_bayar' => now()
                    ]);
                    $transaksi->update(['status' => 'pending']);
                    
                    // Kurangi stok
                    foreach ($transaksi->detail as $item) {
                        $produk = $item->produk;
                        if ($produk) {
                            $stokSebelum = $produk->stok;
                            $produk->decrement('stok', $item->jumlah);
                            
                            \App\Models\StokLog::create([
                                'tipe' => 'produk',
                                'referensi_id' => $produk->id,
                                'jenis' => 'keluar',
                                'jumlah' => $item->jumlah,
                                'stok_sebelum' => $stokSebelum,
                                'stok_sesudah' => $produk->stok,
                                'keterangan' => 'Pesanan Online #' . $transaksi->kode_transaksi,
                                'user_id' => $transaksi->user_id,
                            ]);
                        }
                    }
                } elseif ($transactionStatus == 'deny') {
                    $pembayaran->update([
                        'status' => 'gagal',
                        'tanggal_bayar' => null
                    ]);
                    $transaksi->update(['status' => 'dibatalkan']);
                } elseif ($transactionStatus == 'expire') {
                    $pembayaran->update([
                        'status' => 'gagal',
                        'tanggal_bayar' => null
                    ]);
                    $transaksi->update(['status' => 'dibatalkan']);
                } elseif ($transactionStatus == 'cancel') {
                    $pembayaran->update([
                        'status' => 'gagal',
                        'tanggal_bayar' => null
                    ]);
                    $transaksi->update(['status' => 'dibatalkan']);
                }
            }
            
            return response()->json(['message' => 'Webhook diproses']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}

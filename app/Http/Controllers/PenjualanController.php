<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\StokLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function dashboard()
    {
        $transaksiHariIni = Transaksi::whereDate('created_at', today())->count();
        $pendapatanHariIni = Transaksi::whereDate('created_at', today())->where('status', 'selesai')->sum('total');
        $transaksiPending = Transaksi::where('status', 'pending')->count();
        $transaksiTerbaru = Transaksi::with('user', 'pembayaran')->latest()->take(5)->get();
        return view('penjualan.dashboard', compact('transaksiHariIni', 'pendapatanHariIni', 'transaksiPending', 'transaksiTerbaru'));
    }

    // POS - Point of Sale
    public function pos()
    {
        $produk = Produk::where('is_active', true)->where('stok', '>', 0)->with('kategori')->get();
        return view('penjualan.pos', compact('produk'));
    }

    public function prosesPos(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'metode_bayar' => 'required|in:qris,e_wallet,m_banking,bayar_ditempat',
            'jumlah_bayar' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $items = [];

            foreach ($request->items as $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                if ($produk->stok < $item['jumlah']) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak cukup!");
                }
                $itemSubtotal = $produk->harga * $item['jumlah'];
                $subtotal += $itemSubtotal;
                $items[] = [
                    'produk' => $produk,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $produk->harga,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $transaksi = Transaksi::create([
                'kode_transaksi' => Transaksi::generateKode(),
                'kasir_id' => auth()->id(),
                'tipe' => 'pos',
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'status' => 'selesai',
                'nama_pelanggan' => $request->nama_pelanggan,
            ]);

            foreach ($items as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['produk']->id,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $item['subtotal'],
                ]);

                $stokSebelum = $item['produk']->stok;
                $item['produk']->decrement('stok', $item['jumlah']);

                StokLog::create([
                    'tipe' => 'produk',
                    'referensi_id' => $item['produk']->id,
                    'jenis' => 'keluar',
                    'jumlah' => $item['jumlah'],
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $item['produk']->stok,
                    'keterangan' => 'Penjualan POS #' . $transaksi->kode_transaksi,
                    'user_id' => auth()->id(),
                ]);
            }

            $kembalian = max(0, $request->jumlah_bayar - $subtotal);
            Pembayaran::create([
                'transaksi_id' => $transaksi->id,
                'metode' => $request->metode_bayar,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian' => $kembalian,
                'status' => 'berhasil',
                'tanggal_bayar' => now(),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil!', 'kode' => $transaksi->kode_transaksi, 'kembalian' => $kembalian]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function transaksi(Request $request)
    {
        $query = Transaksi::with('user', 'pembayaran', 'detail.produk');
        if ($request->status) { $query->where('status', $request->status); }
        if ($request->search) { $query->where('kode_transaksi', 'like', "%{$request->search}%"); }
        $transaksi = $query->latest()->paginate(15);
        return view('penjualan.transaksi', compact('transaksi'));
    }

    public function detailTransaksi(Transaksi $transaksi)
    {
        $transaksi->load('user', 'pembayaran', 'pengiriman', 'detail.produk');
        return view('penjualan.detail-transaksi', compact('transaksi'));
    }

    public function updateStatus(Request $request, Transaksi $transaksi)
    {
        $request->validate(['status' => 'required|in:pending,diproses,dikirim,selesai,dibatalkan']);
        $transaksi->update(['status' => $request->status]);

        if ($request->status === 'dikirim' && $transaksi->pengiriman) {
            $transaksi->pengiriman->update(['status' => 'dikirim', 'tanggal_kirim' => now()]);
        }
        if ($request->status === 'selesai') {
            if ($transaksi->pembayaran) {
                $transaksi->pembayaran->update(['status' => 'berhasil', 'tanggal_bayar' => now()]);
            }
            if ($transaksi->pengiriman) {
                $transaksi->pengiriman->update(['status' => 'diterima', 'tanggal_terima' => now()]);
            }
        }

        return back()->with('success', 'Status transaksi diupdate!');
    }

    public function laporan(Request $request)
    {
        $bulan = $request->bulan ?: date('m');
        $tahun = $request->tahun ?: date('Y');

        $laporanHarian = Transaksi::where('status', 'selesai')
            ->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah_transaksi, SUM(total) as total_pendapatan')
            ->groupBy('tanggal')->orderBy('tanggal')->get();

        $totalBulanIni = Transaksi::where('status', 'selesai')
            ->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->sum('total');

        return view('penjualan.laporan', compact('laporanHarian', 'totalBulanIni', 'bulan', 'tahun'));
    }

    public function kirimPesanan(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'metode_kirim' => 'required|in:ambil_sendiri,kurir_toko,grabfood,gofood',
        ]);

        Pengiriman::updateOrCreate(
            ['transaksi_id' => $transaksi->id],
            [
                'metode_kirim' => $request->metode_kirim,
                'alamat_tujuan' => $transaksi->alamat_pengiriman,
                'nama_penerima' => $transaksi->nama_pelanggan,
                'phone_penerima' => $transaksi->phone_pelanggan,
                'status' => 'diproses',
            ]
        );

        $transaksi->update(['status' => 'diproses']);
        return back()->with('success', 'Pesanan sedang diproses!');
    }
}

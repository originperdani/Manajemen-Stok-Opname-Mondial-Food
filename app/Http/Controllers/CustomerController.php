<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\Keranjang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\StokLog;
use App\Helpers\CacheHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Midtrans\Snap;
use Midtrans\Config;

class CustomerController extends Controller
{
    public function home()
    {
        $produkVersion = $this->cacheVersion('produk');
        $kategoriVersion = $this->cacheVersion('kategori_produk');

        $featured = Cache::remember(
            "customer.home.featured.v{$produkVersion}",
            now()->addMinutes(10),
            fn() => Produk::where('is_active', true)
                ->where('is_featured', true)
                ->where('stok', '>', 0)
                ->with('kategori')
                ->take(8)
                ->get()
        );

        $kategori = Cache::remember(
            "customer.home.kategori.v{$produkVersion}.{$kategoriVersion}",
            now()->addMinutes(30),
            fn() => KategoriProduk::where('is_active', true)->withCount('produk')->get()
        );

        $produkBaru = Cache::remember(
            "customer.home.produk-baru.v{$produkVersion}",
            now()->addMinutes(10),
            fn() => Produk::where('is_active', true)
                ->where('stok', '>', 0)
                ->with('kategori')
                ->latest()
                ->take(8)
                ->get()
        );

        return view('customer.home', compact('featured', 'kategori', 'produkBaru'));
    }

    public function about()
    {
        return view('customer.about');
    }

    public function contact()
    {
        return view('customer.contact');
    }

    public function katalog(Request $request)
    {
        $filters = [
            'kategori' => (string) $request->query('kategori', ''),
            'search' => trim((string) $request->query('search', '')),
            'sort' => in_array($request->query('sort'), ['harga_asc', 'harga_desc'], true)
                ? $request->query('sort')
                : 'latest',
        ];

        $produkVersion = $this->cacheVersion('produk');
        $kategoriVersion = $this->cacheVersion('kategori_produk');
        $filterHash = md5(http_build_query($filters));

        $produk = Cache::remember(
            "customer.katalog.produk.v{$produkVersion}.{$kategoriVersion}.{$filterHash}",
            now()->addMinutes(10),
            function () use ($filters) {
                $query = Produk::where('is_active', true)->where('stok', '>', 0)->with('kategori');

                if ($filters['kategori'] !== '') {
                    $query->whereHas('kategori', fn($q) => $q->where('slug', $filters['kategori']));
                }

                if ($filters['search'] !== '') {
                    $query->where('nama_produk', 'like', '%' . $filters['search'] . '%');
                }

                if ($filters['sort'] === 'harga_asc') {
                    $query->orderBy('harga', 'asc');
                } elseif ($filters['sort'] === 'harga_desc') {
                    $query->orderBy('harga', 'desc');
                } else {
                    $query->latest();
                }

                return $query->get();
            }
        );

        $kategori = Cache::remember(
            "customer.katalog.kategori.v{$kategoriVersion}",
            now()->addMinutes(30),
            fn() => KategoriProduk::where('is_active', true)->get()
        );

        return view('customer.katalog', compact('produk', 'kategori'));
    }

    public function detailProduk(Produk $produk)
    {
        $produkVersion = $this->cacheVersion('produk');
        $kategoriVersion = $this->cacheVersion('kategori_produk');

        ['produk' => $produk, 'related' => $related] = Cache::remember(
            "customer.detail.{$produk->id}.v{$produkVersion}.{$kategoriVersion}",
            now()->addMinutes(10),
            function () use ($produk) {
                $cachedProduk = Produk::with('kategori')->findOrFail($produk->id);
                $related = Produk::where('kategori_id', $cachedProduk->kategori_id)
                    ->where('id', '!=', $cachedProduk->id)
                    ->where('is_active', true)
                    ->take(4)
                    ->get();

                return [
                    'produk' => $cachedProduk,
                    'related' => $related,
                ];
            }
        );

        return view('customer.detail-produk', compact('produk', 'related'));
    }

    public function keranjang()
    {
        $items = Keranjang::where('user_id', auth()->id())->with('produk.kategori')->get();
        $total = $items->sum(fn($item) => $item->jumlah * $item->produk->harga);
        return view('customer.keranjang', compact('items', 'total'));
    }

    public function tambahKeranjang(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $existing = Keranjang::where('user_id', auth()->id())->where('produk_id', $request->produk_id)->first();
        if ($existing) {
            $existing->increment('jumlah', $request->jumlah);
        } else {
            Keranjang::create([
                'user_id' => auth()->id(),
                'produk_id' => $request->produk_id,
                'jumlah' => $request->jumlah,
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function updateKeranjang(Request $request, Keranjang $keranjang)
    {
        $request->validate(['jumlah' => 'required|integer|min:1']);
        $keranjang->update(['jumlah' => $request->jumlah]);
        return back()->with('success', 'Keranjang diupdate!');
    }

    public function hapusKeranjang(Keranjang $keranjang)
    {
        $keranjang->delete();
        return back()->with('success', 'Item dihapus dari keranjang!');
    }

    public function checkout()
    {
        $items = Keranjang::where('user_id', auth()->id())->with('produk')->get();
        if ($items->isEmpty()) {
            return redirect()->route('customer.keranjang')->with('error', 'Keranjang kosong!');
        }
        $total = $items->sum(fn($item) => $item->jumlah * $item->produk->harga);
        return view('customer.checkout', compact('items', 'total'));
    }

    public function prosesCheckout(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string',
            'phone_pelanggan' => 'required|string',
            'email_pelanggan' => 'required|email',
            'metode_kirim' => 'required|in:ambil_sendiri,kurir_ojol',
        ], [
            'nama_pelanggan.required' => 'Nama lengkap harus diisi',
            'phone_pelanggan.required' => 'Nomor telepon/WhatsApp harus diisi',
            'email_pelanggan.required' => 'Email harus diisi',
            'email_pelanggan.email' => 'Email harus valid',
        ]);

        $items = Keranjang::where('user_id', auth()->id())->with('produk')->get();
        if ($items->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong!'], 400);
        }

        DB::beginTransaction();
        try {
            $subtotal = $items->sum(fn($item) => $item->jumlah * $item->produk->harga);
            $ongkir = 0;

            $transaksi = Transaksi::create([
                'kode_transaksi' => Transaksi::generateKode(),
                'user_id' => auth()->id(),
                'tipe' => 'online',
                'subtotal' => $subtotal,
                'ongkir' => $ongkir,
                'total' => $subtotal + $ongkir,
                'status' => 'belum_bayar',
                'nama_pelanggan' => $request->nama_pelanggan,
                'phone_pelanggan' => $request->phone_pelanggan,
                'email_pelanggan' => $request->email_pelanggan,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'catatan' => $request->catatan,
            ]);

            foreach ($items as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item->produk_id,
                    'jumlah' => $item->jumlah,
                    'harga_satuan' => $item->produk->harga,
                    'subtotal' => $item->jumlah * $item->produk->harga,
                ]);
            }

            Pembayaran::create([
                'transaksi_id' => $transaksi->id,
                'metode' => 'midtrans',
                'jumlah_bayar' => $subtotal + $ongkir,
                'status' => 'belum_bayar',
            ]);

            Pengiriman::create([
                'transaksi_id' => $transaksi->id,
                'metode_kirim' => $request->metode_kirim,
                'alamat_tujuan' => $request->alamat_pengiriman,
                'nama_penerima' => $request->nama_pelanggan,
                'phone_penerima' => $request->phone_pelanggan,
                'status' => 'menunggu',
            ]);

            Keranjang::where('user_id', auth()->id())->delete();

            CacheHelper::bumpVersion('produk');
            DB::commit();
            
            // Generate Snap Token Midtrans
            $snapToken = $this->generateSnapToken($transaksi);
            
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'transaksi_id' => $transaksi->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memproses pesanan: ' . $e->getMessage()], 500);
        }
    }
    
    private function generateSnapToken(Transaksi $transaksi)
    {
        try {
            // Konfigurasi Midtrans
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.env') === 'production';
            Config::$isSanitized = true;
            Config::$is3ds = true;
            
            // Use kode_transaksi with retry suffix to avoid Midtrans duplicate order_id rejection
            $orderId = $transaksi->kode_transaksi;
            $pembayaran = $transaksi->pembayaran;
            
            // If there's an existing referensi with retry suffix, increment it
            if ($pembayaran && $pembayaran->referensi && preg_match('/-R(\d+)$/', $pembayaran->referensi, $matches)) {
                $retryCount = (int) $matches[1] + 1;
                $orderId = $transaksi->kode_transaksi . '-R' . $retryCount;
            } elseif ($pembayaran && $pembayaran->status !== 'berhasil') {
                // First retry — check if original order_id was already used by trying with suffix
                // This handles the case where the first checkout created an order in Midtrans
                // but the customer didn't complete payment and is now retrying
                $orderId = $transaksi->kode_transaksi . '-R1';
            }
            
            // Prepare transaction details
            $transaction_details = [
                'order_id' => $orderId,
                'gross_amount' => (int) $transaksi->total,
            ];
            
            // Prepare customer details
            $customer_details = [
                'first_name' => $transaksi->nama_pelanggan,
                'email' => $transaksi->email_pelanggan,
                'phone' => $transaksi->phone_pelanggan,
            ];
            
            // Prepare item details
            $item_details = [];
            foreach ($transaksi->detail as $item) {
                $item_details[] = [
                    'id' => (string) $item->produk->id,
                    'price' => (int) $item->harga_satuan,
                    'quantity' => $item->jumlah,
                    'name' => $item->produk->nama_produk,
                ];
            }
            
            // Combine all parameters
            $params = [
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details,
                'item_details' => $item_details,
            ];
            
            \Log::info('Midtrans Params:', $params);
            
            $snapToken = Snap::getSnapToken($params);
            
            \Log::info('Midtrans Snap Token:', ['token' => $snapToken]);
            
            // Save the order_id used so webhook can map back to transaction
            if ($pembayaran) {
                $pembayaran->update(['referensi' => $orderId]);
            }
            
            return $snapToken;
        } catch (\Exception $e) {
            \Log::error('Midtrans Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    public function getSnapToken(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            $snapToken = $this->generateSnapToken($transaksi);
            return response()->json([
                'snap_token' => $snapToken
            ]);
        } catch (\Exception $e) {
            \Log::error('Midtrans Snap Token Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function pesanan()
    {
        $pesananQuery = Transaksi::where('user_id', auth()->id())
            ->with('pembayaran', 'pengiriman')
            ->latest();
        
        $pesanan = $pesananQuery->paginate(10);
        
        // Check and update status for recent orders (last 24 hours)
        foreach ($pesanan as $transaksi) {
            if ($transaksi->pembayaran && 
                $transaksi->pembayaran->metode === 'midtrans' && 
                in_array($transaksi->pembayaran->status, ['belum_bayar', 'pending']) &&
                $transaksi->created_at->gt(now()->subHours(24))) {
                $this->checkAndUpdateMidtransStatus($transaksi);
            }
        }
        
        // Refresh the data after updates
        $pesanan = $pesananQuery->paginate(10);
        
        return view('customer.pesanan', compact('pesanan'));
    }

    public function pesananDetail(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== auth()->id()) { abort(403); }
        
        // Check and update status from Midtrans if needed
        if ($transaksi->pembayaran && 
            $transaksi->pembayaran->metode === 'midtrans' && 
            in_array($transaksi->pembayaran->status, ['belum_bayar', 'pending'])) {
            $this->checkAndUpdateMidtransStatus($transaksi);
        }
        
        $transaksi->load('detail.produk', 'pembayaran', 'pengiriman');
        return view('customer.pesanan-detail', compact('transaksi'));
    }
    
    private function checkAndUpdateMidtransStatus(Transaksi $transaksi): void
    {
        try {
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.env') === 'production';
            
            // Use the stored referensi (which includes retry suffix) or fallback to kode_transaksi
            $midtransOrderId = $transaksi->pembayaran->referensi ?? $transaksi->kode_transaksi;
            
            // Get transaction status from Midtrans
            $status = \Midtrans\Transaction::status($midtransOrderId);
            
            $transactionStatus = $status->transaction_status;
            $fraudStatus = $status->fraud_status ?? null;
            $pembayaran = $transaksi->pembayaran;
            
            if (!$pembayaran) return;
            
            if ($transactionStatus == 'capture' && $fraudStatus == 'accept') {
                $this->updatePaymentSuccess($transaksi, $pembayaran, $status);
            } elseif ($transactionStatus == 'settlement') {
                $this->updatePaymentSuccess($transaksi, $pembayaran, $status);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $pembayaran->update(['status' => 'gagal', 'tanggal_bayar' => null]);
                $transaksi->update(['status' => 'dibatalkan']);
            }
        } catch (\Exception $e) {
            // Ignore errors, just continue
        }
    }
    
    private function updatePaymentSuccess(Transaksi $transaksi, $pembayaran, $midtransResponse = null): void
    {
        $updateData = ['status' => 'berhasil', 'tanggal_bayar' => now()];
        
        // Extract actual payment method from Midtrans response
        if ($midtransResponse) {
            $actualMetode = Pembayaran::extractMidtransPaymentMethod($midtransResponse);
            $updateData['metode'] = $actualMetode;
        }
        
        $pembayaran->update($updateData);
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

        // Bump cache version so product stock updates show on katalog/home
        \App\Helpers\CacheHelper::bumpVersion('produk');
    }

    public function lihatStruk(Transaksi $transaksi)
    {
        return $this->renderStrukPesanan($transaksi, false);
    }

    public function downloadStruk(Transaksi $transaksi)
    {
        return $this->renderStrukPesanan($transaksi, true);
    }

    private function renderStrukPesanan(Transaksi $transaksi, bool $download)
    {
        if ($transaksi->user_id !== auth()->id()) { abort(403); }

        $transaksi->loadMissing('detail.produk', 'pembayaran', 'pengiriman', 'user');

        abort_unless($transaksi->pembayaran, 404, 'Data pembayaran pesanan tidak ditemukan.');

        $store = [
            'name' => 'Mondial Bakery',
            'address' => 'Jl. Mesjid Al-Akhyar No.34, Gandul, Kec. Cinere, Kota Depok, Jawa Barat 16512',
            'phone' => '0857-9393-0723',
            'email' => 'mondialfood.co@gmail.com',
        ];

        $filename = 'struk-' . $transaksi->kode_transaksi . '.pdf';
        $pdf = Pdf::loadView('customer.struk-pesanan', [
            'transaksi' => $transaksi,
            'store' => $store,
        ])->setPaper([0, 0, 226.77, 620], 'portrait');

        $response = $download
            ? $pdf->download($filename)
            : $pdf->stream($filename);

        return $response
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function cacheVersion(string $namespace): int
    {
        return (int) Cache::get("cache_versions.{$namespace}", 1);
    }
}

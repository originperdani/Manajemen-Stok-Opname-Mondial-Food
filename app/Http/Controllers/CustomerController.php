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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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
            'metode_bayar' => 'required|in:qris,e_wallet,m_banking,bayar_ditempat',
            'metode_kirim' => 'required|in:ambil_sendiri,kurir_toko,grabfood,gofood',
        ]);

        $items = Keranjang::where('user_id', auth()->id())->with('produk')->get();
        if ($items->isEmpty()) {
            return back()->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();
        try {
            $subtotal = $items->sum(fn($item) => $item->jumlah * $item->produk->harga);
            $ongkir = in_array($request->metode_kirim, ['kurir_toko', 'grabfood', 'gofood']) ? 10000 : 0;

            $transaksi = Transaksi::create([
                'kode_transaksi' => Transaksi::generateKode(),
                'user_id' => auth()->id(),
                'tipe' => 'online',
                'subtotal' => $subtotal,
                'ongkir' => $ongkir,
                'total' => $subtotal + $ongkir,
                'status' => 'pending',
                'nama_pelanggan' => $request->nama_pelanggan,
                'phone_pelanggan' => $request->phone_pelanggan,
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

                $stokSebelum = $item->produk->stok;
                $item->produk->decrement('stok', $item->jumlah);

                StokLog::create([
                    'tipe' => 'produk',
                    'referensi_id' => $item->produk->id,
                    'jenis' => 'keluar',
                    'jumlah' => $item->jumlah,
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $item->produk->stok,
                    'keterangan' => 'Pesanan Online #' . $transaksi->kode_transaksi,
                    'user_id' => auth()->id(),
                ]);
            }

            Pembayaran::create([
                'transaksi_id' => $transaksi->id,
                'metode' => $request->metode_bayar,
                'jumlah_bayar' => $subtotal + $ongkir,
                'status' => $request->metode_bayar === 'bayar_ditempat' ? 'pending' : 'berhasil',
                'tanggal_bayar' => $request->metode_bayar !== 'bayar_ditempat' ? now() : null,
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

            DB::commit();
            return redirect()->route('customer.pesanan.detail', $transaksi)->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    public function pesanan()
    {
        $pesanan = Transaksi::where('user_id', auth()->id())->with('pembayaran', 'pengiriman')->latest()->paginate(10);
        return view('customer.pesanan', compact('pesanan'));
    }

    public function pesananDetail(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== auth()->id()) { abort(403); }
        $transaksi->load('detail.produk', 'pembayaran', 'pengiriman');
        return view('customer.pesanan-detail', compact('transaksi'));
    }

    private function cacheVersion(string $namespace): int
    {
        return (int) Cache::get("cache_versions.{$namespace}", 1);
    }
}

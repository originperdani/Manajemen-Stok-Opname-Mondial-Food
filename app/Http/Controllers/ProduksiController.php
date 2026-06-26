<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Resep;
use App\Models\ResepDetail;
use App\Models\BahanBaku;
use App\Models\Produksi;
use App\Models\StokLog;
use App\Models\KategoriProduk;
use App\Helpers\CacheHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProduksiController extends Controller
{
    public function dashboard()
    {
        $totalProduk = Produk::count();
        $totalResep = Resep::count();
        $produksiHariIni = Produksi::whereDate('tanggal_produksi', today())->count();
        $produkDiproduksiHariIni = Produksi::whereDate('tanggal_produksi', today())->distinct()->count('produk_id');
        $produkMenipis = Produk::whereColumn('stok', '<=', 'stok_minimum')->get();
        $produksiTerbaru = Produksi::with('produk', 'user')->latest()->take(5)->get();
        return view('produksi.dashboard', compact('totalProduk', 'totalResep', 'produksiHariIni', 'produkDiproduksiHariIni', 'produkMenipis', 'produksiTerbaru'));
    }

    // PRODUK
    public function produk(Request $request)
    {
        $query = Produk::with('kategori');
        if ($request->search) { $query->where('nama_produk', 'like', "%{$request->search}%"); }
        if ($request->status) {
            if ($request->status == 'menipis') {
                $query->whereColumn('stok', '<=', 'stok_minimum');
            } elseif ($request->status == 'aman') {
                $query->whereColumn('stok', '>', 'stok_minimum');
            }
        }
        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }
        $produk = $query->latest()->paginate(15);
        $kategori = KategoriProduk::all();
        return view('produksi.produk', compact('produk', 'kategori'));
    }

    public function createProduk()
    {
        $kategori = KategoriProduk::all();
        return view('produksi.produk-form', compact('kategori'));
    }

    public function storeProduk(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_produk,id',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|max:5120',
        ]);

        $data = $request->except('gambar');
        $data['slug'] = Str::slug($request->nama_produk) . '-' . Str::random(5);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);
        CacheHelper::bumpVersion('produk');
        CacheHelper::bumpVersion('kategori_produk');

        return redirect()->route('produksi.produk')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function editProduk(Produk $produk)
    {
        $kategori = KategoriProduk::all();
        return view('produksi.produk-form', compact('produk', 'kategori'));
    }

    public function updateProduk(Request $request, Produk $produk)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_produk,id',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|max:5120',
        ]);

        $data = $request->except('gambar');
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('gambar')) {
            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }

            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);
        CacheHelper::bumpVersion('produk');
        CacheHelper::bumpVersion('kategori_produk');

        return redirect()->route('produksi.produk')->with('success', 'Produk berhasil diupdate!');
    }

    public function deleteProduk(Produk $produk)
    {
        if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();
        CacheHelper::bumpVersion('produk');
        CacheHelper::bumpVersion('kategori_produk');

        return back()->with('success', 'Produk berhasil dihapus!');
    }

    // KATEGORI
    public function kategori()
    {
        $kategori = KategoriProduk::withCount('produk')->get();
        return view('produksi.kategori', compact('kategori'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        KategoriProduk::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
            'deskripsi' => $request->deskripsi,
        ]);
        CacheHelper::bumpVersion('kategori_produk');

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function deleteKategori(KategoriProduk $kategori)
    {
        $kategori->delete();
        CacheHelper::bumpVersion('kategori_produk');
        CacheHelper::bumpVersion('produk');

        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    // RESEP
    public function resep(Request $request)
    {
        $query = Resep::with('produk', 'kategori', 'detail.bahanBaku')->latest();
        if ($request->filter) {
            $query->where('id', $request->filter);
        }
        $resep = $query->paginate(15);
        return view('produksi.resep', compact('resep'));
    }

    public function createResep()
    {
        $kategori = KategoriProduk::all();
        $bahanBaku = BahanBaku::all();
        return view('produksi.resep-form', compact('kategori', 'bahanBaku'));
    }

    public function storeResep(Request $request)
    {
        $request->validate([
            'nama_resep' => 'required|string|max:255',
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_produk,id',
            'bahan' => 'required|array|min:1',
            'bahan.*.bahan_baku_id' => 'required|exists:bahan_baku,id',
            'bahan.*.jumlah' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            // Cari atau buat produk baru
            $produk = Produk::firstOrCreate(
                ['nama_produk' => $request->nama_produk],
                [
                    'kategori_id' => $request->kategori_id,
                    'harga' => 0, // Default harga, bisa diupdate nanti
                    'stok' => 0,
                    'stok_minimum' => 5,
                ]
            );

            $resep = Resep::create([
                'produk_id' => $produk->id,
                'nama_resep' => $request->nama_resep,
                'nama_produk' => $request->nama_produk,
                'kategori_id' => $request->kategori_id,
                'instruksi' => $request->instruksi,
                'hasil_produksi' => $request->hasil_produksi ?? 1,
                'waktu_produksi' => $request->waktu_produksi,
            ]);

            foreach ($request->bahan as $b) {
                $bahan = BahanBaku::find($b['bahan_baku_id']);
                ResepDetail::create([
                    'resep_id' => $resep->id,
                    'bahan_baku_id' => $b['bahan_baku_id'],
                    'jumlah' => $b['jumlah'],
                    'satuan' => $bahan->satuan,
                ]);
            }

            DB::commit();
            return redirect()->route('produksi.resep')->with('success', 'Resep berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan resep: ' . $e->getMessage());
        }
    }

    public function deleteResep(Resep $resep)
    {
        $resep->delete();
        return back()->with('success', 'Resep berhasil dihapus!');
    }

    // PRODUKSI
    public function inputProduksi()
    {
        $resep = Resep::with('produk')->get();
        return view('produksi.input-produksi', compact('resep'));
    }

    public function riwayatProduksi(Request $request)
    {
        $periode = $request->get('periode', 'bulanan');
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('n'));
        $tanggal = $request->get('tanggal', date('Y-m-d'));

        if ($periode === 'tahunan') {
            $start = \Carbon\Carbon::create($tahun, 1, 1)->startOfDay();
            $end = \Carbon\Carbon::create($tahun, 12, 31)->endOfDay();
        } elseif ($periode === 'all') {
            $start = \Carbon\Carbon::create($tahun, 1, 1)->startOfDay();
            $end = \Carbon\Carbon::create($tahun, 12, 31)->endOfDay();
        } elseif ($periode === 'harian') {
            $start = \Carbon\Carbon::parse($tanggal)->startOfDay();
            $end = \Carbon\Carbon::parse($tanggal)->endOfDay();
        } else {
            $bulan = min(max((int)$bulan, 1), 12);
            $start = \Carbon\Carbon::create($tahun, $bulan, 1)->startOfDay();
            $end = (clone $start)->endOfMonth();
        }
        
        $query = Produksi::with('produk', 'user');
        $query->whereBetween('tanggal_produksi', [$start, $end]);
        $totalProduksi = (clone $query)->sum('jumlah_produksi');
        $totalJenisProduk = (clone $query)->distinct()->count('produk_id');
        $totalAktivitas = (clone $query)->count();
        $produksi = $query->latest('tanggal_produksi')->latest('id')->paginate(15);
        
        return view('produksi.riwayat', compact('produksi', 'totalProduksi', 'totalJenisProduk', 'totalAktivitas', 'periode', 'tahun', 'bulan', 'tanggal'));
    }

    public function prosesProduksi(Request $request)
    {
        $request->validate([
            'resep_id' => 'required|exists:resep,id',
            'jumlah_produksi' => 'required|integer|min:1',
        ]);

        $resep = Resep::with('detail.bahanBaku', 'produk')->findOrFail($request->resep_id);

        DB::beginTransaction();
        try {
            // Kurangi bahan baku
            foreach ($resep->detail as $detail) {
                $kebutuhan = $detail->jumlah * $request->jumlah_produksi;
                $bahan = $detail->bahanBaku;

                if ($bahan->stok < $kebutuhan) {
                    throw new \Exception("Stok {$bahan->nama_bahan} tidak cukup! Butuh: {$kebutuhan} {$bahan->satuan}, Tersedia: {$bahan->stok} {$bahan->satuan}");
                }

                $stokSebelum = $bahan->stok;
                $bahan->decrement('stok', $kebutuhan);

                StokLog::create([
                    'tipe' => 'bahan_baku',
                    'referensi_id' => $bahan->id,
                    'jenis' => 'keluar',
                    'jumlah' => $kebutuhan,
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $bahan->stok,
                    'keterangan' => 'Produksi ' . $resep->nama_resep,
                    'user_id' => auth()->id(),
                ]);
            }

            // Tambah stok produk
            $hasilTotal = $resep->hasil_produksi * $request->jumlah_produksi;
            $stokSebelum = $resep->produk->stok;
            $resep->produk->increment('stok', $hasilTotal);

            StokLog::create([
                'tipe' => 'produk',
                'referensi_id' => $resep->produk->id,
                'jenis' => 'masuk',
                'jumlah' => $hasilTotal,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $resep->produk->stok,
                'keterangan' => 'Hasil produksi ' . $resep->nama_resep,
                'user_id' => auth()->id(),
            ]);

            Produksi::create([
                'produk_id' => $resep->produk->id,
                'resep_id' => $resep->id,
                'user_id' => auth()->id(),
                'jumlah_produksi' => $hasilTotal,
                'tanggal_produksi' => today(),
                'status' => 'selesai',
                'catatan' => $request->catatan,
            ]);

            CacheHelper::bumpVersion('produk');
            DB::commit();
            return redirect()->route('produksi.dashboard')->with('success', "Produksi berhasil! {$hasilTotal} {$resep->produk->nama_produk} telah ditambahkan ke stok.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}

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
use Illuminate\Support\Str;

class ProduksiController extends Controller
{
    public function dashboard()
    {
        $totalProduk = Produk::count();
        $totalResep = Resep::count();
        $produksiHariIni = Produksi::whereDate('tanggal_produksi', today())->count();
        $produkMenipis = Produk::whereColumn('stok', '<=', 'stok_minimum')->get();
        $produksiTerbaru = Produksi::with('produk', 'user')->latest()->take(5)->get();
        return view('produksi.dashboard', compact('totalProduk', 'totalResep', 'produksiHariIni', 'produkMenipis', 'produksiTerbaru'));
    }

    // PRODUK
    public function produk(Request $request)
    {
        $query = Produk::with('kategori');
        if ($request->search) { $query->where('nama_produk', 'like', "%{$request->search}%"); }
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
            'gambar' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('gambar');
        $data['slug'] = Str::slug($request->nama_produk) . '-' . Str::random(5);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);
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
            'gambar' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('gambar');
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);
        return redirect()->route('produksi.produk')->with('success', 'Produk berhasil diupdate!');
    }

    public function deleteProduk(Produk $produk)
    {
        $produk->delete();
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
        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function deleteKategori(KategoriProduk $kategori)
    {
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    // RESEP
    public function resep()
    {
        $resep = Resep::with('produk', 'kategori', 'detail.bahanBaku')->latest()->paginate(15);
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

    public function riwayatProduksi()
    {
        $produksi = Produksi::with('produk', 'user')->latest()->paginate(15);
        return view('produksi.riwayat', compact('produksi'));
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

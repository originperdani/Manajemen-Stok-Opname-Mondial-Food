<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\StokLog;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function dashboard()
    {
        $totalBahan = BahanBaku::count();
        $bahanMenipis = BahanBaku::whereColumn('stok', '<=', 'stok_minimum')->get();
        $recentLogs = StokLog::where('tipe', 'bahan_baku')->with('user')->latest()->take(10)->get();
        return view('gudang.dashboard', compact('totalBahan', 'bahanMenipis', 'recentLogs'));
    }

    public function index(Request $request)
    {
        $query = BahanBaku::query();
        if ($request->search) {
            $query->where('nama_bahan', 'like', "%{$request->search}%");
        }
        if ($request->filter === 'menipis') {
            $query->whereColumn('stok', '<=', 'stok_minimum');
        }
        $bahan = $query->latest()->paginate(15);
        return view('gudang.index', compact('bahan'));
    }

    public function create()
    {
        return view('gudang.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_per_satuan' => 'required|numeric|min:0',
        ]);

        $bahan = BahanBaku::create($request->all());

        StokLog::create([
            'tipe' => 'bahan_baku',
            'referensi_id' => $bahan->id,
            'jenis' => 'masuk',
            'jumlah' => $bahan->stok,
            'stok_sebelum' => 0,
            'stok_sesudah' => $bahan->stok,
            'keterangan' => 'Stok awal',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('gudang.index')->with('success', 'Bahan baku berhasil ditambahkan!');
    }

    public function edit(BahanBaku $bahan)
    {
        return view('gudang.form', compact('bahan'));
    }

    public function update(Request $request, BahanBaku $bahan)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_per_satuan' => 'required|numeric|min:0',
        ]);

        $bahan->update($request->except('stok'));
        return redirect()->route('gudang.index')->with('success', 'Bahan baku berhasil diupdate!');
    }

    public function destroy(BahanBaku $bahan)
    {
        $bahan->delete();
        return back()->with('success', 'Bahan baku berhasil dihapus!');
    }

    public function tambahStok(Request $request, BahanBaku $bahan)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string',
        ]);

        $stokSebelum = $bahan->stok;
        $bahan->increment('stok', $request->jumlah);

        StokLog::create([
            'tipe' => 'bahan_baku',
            'referensi_id' => $bahan->id,
            'jenis' => 'masuk',
            'jumlah' => $request->jumlah,
            'stok_sebelum' => $stokSebelum,
            'stok_sesudah' => $bahan->stok,
            'keterangan' => $request->keterangan ?? 'Penambahan stok',
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Stok berhasil ditambahkan!');
    }
}

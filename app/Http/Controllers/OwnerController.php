<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produk;
use App\Models\BahanBaku;
use App\Models\Transaksi;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalProduk = Produk::count();
        $totalBahanBaku = BahanBaku::count();
        $totalUser = User::count();

        $produkStokMenipis = Produk::whereColumn('stok', '<=', 'stok_minimum')->count();
        $bahanStokMenipis = BahanBaku::whereColumn('stok', '<=', 'stok_minimum')->count();

        // Handle filter
        $filterType = $request->filter_type ?? 'harian';
        $tanggal = $request->tanggal ?? now()->toDateString();
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // Query untuk transaksi dan pendapatan sesuai filter
        $transaksiQuery = Transaksi::query();
        $pendapatanQuery = Transaksi::where('status', 'selesai');

        $periodeLabel = '';
        if ($filterType === 'harian') {
            $transaksiQuery->whereDate('created_at', $tanggal);
            $pendapatanQuery->whereDate('created_at', $tanggal);
            $periodeLabel = now()->parse($tanggal)->translatedFormat('d F Y');
        } elseif ($filterType === 'bulanan') {
            $transaksiQuery->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
            $pendapatanQuery->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
            $periodeLabel = now()->parse($tahun.'-'.$bulan)->translatedFormat('F Y');
        } elseif ($filterType === 'tahunan') {
            $transaksiQuery->whereYear('created_at', $tahun);
            $pendapatanQuery->whereYear('created_at', $tahun);
            $periodeLabel = $tahun;
        }

        $totalTransaksi = $transaksiQuery->count();
        $totalPendapatan = $pendapatanQuery->sum('total');

        $transaksiHariIni = Transaksi::whereDate('created_at', today())->count();
        $pendapatanHariIni = Transaksi::whereDate('created_at', today())->where('status', 'selesai')->sum('total');

        // Chart: Penjualan 7 hari terakhir
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[] = Transaksi::whereDate('created_at', $date)->where('status', 'selesai')->sum('total');
        }

        // Transaksi terbaru
        $transaksiTerbaru = Transaksi::with('user', 'pembayaran')->latest()->take(10)->get();

        return view('owner.dashboard', compact(
            'totalProduk', 'totalBahanBaku', 'totalTransaksi', 'totalPendapatan',
            'totalUser', 'transaksiHariIni', 'pendapatanHariIni',
            'produkStokMenipis', 'bahanStokMenipis',
            'chartLabels', 'chartData', 'transaksiTerbaru',
            'filterType', 'tanggal', 'bulan', 'tahun', 'periodeLabel'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }
        if ($request->role) {
            $query->where('role', $request->role);
        }
        $users = $query->latest()->paginate(15);
        return view('owner.users', compact('users'));
    }

    public function createUser()
    {
        return view('owner.user-form');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:owner,admin_gudang,admin_penjualan,admin_produksi,customer',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('owner.users')->with('success', 'User berhasil ditambahkan!');
    }

    public function editUser(User $user)
    {
        return view('owner.user-form', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:owner,admin_gudang,admin_penjualan,admin_produksi,customer',
        ]);

        $data = $request->only('name', 'email', 'role', 'phone', 'alamat');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        return redirect()->route('owner.users')->with('success', 'User berhasil diupdate!');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }

    public function transaksi(Request $request)
    {
        $query = Transaksi::with('user', 'pembayaran', 'detail');
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }
        $transaksi = $query->latest()->get();
        return view('owner.transaksi', compact('transaksi'));
    }

    public function laporan(Request $request)
    {
        $bulan = $request->bulan ?: date('m');
        $tahun = $request->tahun ?: date('Y');

        $laporanHarian = Transaksi::where('status', 'selesai')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah_transaksi, SUM(total) as total_pendapatan')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $totalBulanIni = Transaksi::where('status', 'selesai')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->sum('total');

        $produkTerlaris = DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id')
            ->join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
            ->where('transaksi.status', 'selesai')
            ->whereMonth('transaksi.created_at', $bulan)
            ->whereYear('transaksi.created_at', $tahun)
            ->select('produk.nama_produk', DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'))
            ->groupBy('produk.nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(10)
            ->get();

        return view('owner.laporan', compact('laporanHarian', 'totalBulanIni', 'produkTerlaris', 'bulan', 'tahun'));
    }

    public function stokProduk(Request $request)
    {
        $query = Produk::with('kategori');
        if ($request->search) {
            $query->where('nama_produk', 'like', "%{$request->search}%");
        }
        if ($request->filter === 'menipis') {
            $query->whereColumn('stok', '<=', 'stok_minimum');
        }
        $produk = $query->get();
        return view('owner.stok-produk', compact('produk'));
    }

    public function stokBahan(Request $request)
    {
        $query = BahanBaku::query();
        if ($request->search) {
            $query->where('nama_bahan', 'like', "%{$request->search}%");
        }
        if ($request->filter === 'menipis') {
            $query->whereColumn('stok', '<=', 'stok_minimum');
        }
        $bahan = $query->get();
        return view('owner.stok-bahan', compact('bahan'));
    }
}

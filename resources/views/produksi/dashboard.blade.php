@extends('layouts.admin')
@section('title', 'Dashboard Produksi')
@section('page-title', 'Dashboard Produksi')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Produksi</div>
<li><a href="{{ route('produksi.dashboard') }}" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('produksi.produk') }}"><i class="fas fa-birthday-cake"></i> Produk</a></li>
<li><a href="{{ route('produksi.kategori') }}"><i class="fas fa-tags"></i> Kategori</a></li>
<li><a href="{{ route('produksi.resep') }}"><i class="fas fa-book-open"></i> Resep</a></li>
<li><a href="{{ route('produksi.input') }}"><i class="fas fa-plus-circle"></i> Input Produksi</a></li>
<li><a href="{{ route('produksi.riwayat') }}"><i class="fas fa-history"></i> Detail Riwayat Produksi</a></li>
<li><a href="{{ route('produksi.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="stat-grid">
    <a href="{{ route('produksi.produk') }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4>Total Produk</h4>
            <p>{{ $totalProduk }}</p>
        </div>
    </a>
    <a href="{{ route('produksi.resep') }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4>Total Resep</h4>
            <p>{{ $totalResep }}</p>
        </div>
    </a>
    <a href="{{ route('produksi.riwayat', ['periode' => 'harian', 'tanggal' => date('Y-m-d')]) }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4>Aktivitas Produksi Hari Ini</h4>
            <p>{{ $produksiHariIni }}</p>
        </div>
    </a>
    <a href="{{ route('produksi.riwayat', ['periode' => 'harian', 'tanggal' => date('Y-m-d')]) }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4>Produk Diproduksi Hari Ini</h4>
            <p>{{ $produkDiproduksiHariIni }}</p>
        </div>
    </a>
    <a href="{{ route('produksi.produk', ['status' => 'menipis']) }}" class="stat-card" style="border-left: 5px solid #dc3545; text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="color: #dc3545;">Stok Produk Menipis</h4>
            <p style="color: #dc3545;">{{ $produkMenipis->count() }}</p>
        </div>
    </a>
</div>

@if($produkMenipis->count() > 0)
<div class="card mb-4" style="border-color: var(--accent); background: var(--bg-warm); border-left: 5px solid var(--primary);">
    <div class="card-header" style="background: var(--secondary); border-bottom-color: var(--accent);">
        <h3 style="color: var(--primary-dark);">Stok Produk Menipis - Segera Produksi!</h3>
    </div>
    <div class="card-body" style="padding:0">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Stok Saat Ini</th>
                    <th>Stok Minimum</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produkMenipis as $p)
                <tr>
                    <td><strong>{{ $p->nama_produk }}</strong></td>
                    <td><span class="badge badge-danger">{{ $p->stok }}</span></td>
                    <td>{{ $p->stok_minimum }}</td>
                    <td>
                        <a href="{{ route('produksi.input', ['produk_id' => $p->id]) }}" class="btn btn-primary btn-sm">
                            Produksi Sekarang
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="card" style="border-left: 5px solid var(--primary);">
    <div class="card-header">
        <h3>Riwayat Produksi Terbaru</h3>
        <a href="{{ route('produksi.riwayat') }}" class="btn btn-primary btn-sm" style="background: var(--gradient-gold); border: none; color: white;">Detail Riwayat Produksi</a>
    </div>
    <div class="card-body" style="padding:0">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Penanggung Jawab</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produksiTerbaru as $p)
                <tr>
                    <td><strong>{{ $p->produk->nama_produk ?? '-' }}</strong></td>
                    <td style="font-weight: 700; color: var(--primary);">{{ $p->jumlah_produksi }}</td>
                    <td style="font-size: 0.9rem;">{{ $p->user->penanggung_jawab ?? '-' }}</td>
                    <td>
                        <span class="badge badge-{{ $p->status=='selesai'?'success':'warning' }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td style="font-size: 0.85rem; color: var(--text-light);">{{ $p->tanggal_produksi->format('d/m/Y') }}</td>
                </tr>
                @endforeach
                @if($produksiTerbaru->isEmpty())
                <tr>
                    <td colspan="5" class="text-center" style="padding: 3rem; color: var(--text-light);">Belum ada riwayat aktivitas produksi.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

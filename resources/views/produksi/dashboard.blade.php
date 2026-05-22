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
<li><a href="{{ route('produksi.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-birthday-cake"></i></div>
        <div class="stat-info">
            <h4>Total Produk</h4>
            <p>{{ $totalProduk }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-book-open"></i></div>
        <div class="stat-info">
            <h4>Total Resep</h4>
            <p>{{ $totalResep }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-industry"></i></div>
        <div class="stat-info">
            <h4>Produksi Hari Ini</h4>
            <p>{{ $produksiHariIni }}</p>
        </div>
    </div>
</div>

@if($produkMenipis->count() > 0)
<div class="card mb-4" style="border-color: var(--accent); background: var(--bg-warm);">
    <div class="card-header" style="background: var(--secondary); border-bottom-color: var(--accent);">
        <h3 style="color: var(--primary-dark);"><i class="fas fa-triangle-exclamation" style="margin-right: 0.5rem; color: var(--warning);"></i> Stok Produk Menipis - Segera Produksi!</h3>
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
                            <i class="fas fa-plus"></i> Produksi Sekarang
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-history" style="color: var(--accent); margin-right: 0.5rem;"></i> Riwayat Produksi Terbaru</h3>
        <a href="{{ route('produksi.input') }}" class="btn btn-secondary btn-sm">Input Produksi</a>
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
                    <td style="font-size: 0.9rem;">{{ $p->user->name ?? '-' }}</td>
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

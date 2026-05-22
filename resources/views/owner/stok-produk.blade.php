@extends('layouts.admin')
@section('title', 'Dashboard Stok Produk')
@section('page-title', 'Dashboard Stok Produk')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Utama</div>
<li><a href="{{ route('owner.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('owner.users') }}"><i class="fas fa-users"></i> Kelola User</a></li>
<li><a href="{{ route('owner.transaksi') }}"><i class="fas fa-receipt"></i> Transaksi</a></li>
<li><a href="{{ route('owner.reports.index') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
<div class="sidebar-divider">Monitoring Stok</div>
<li><a href="{{ route('owner.stok-produk') }}" class="active"><i class="fas fa-birthday-cake"></i> Stok Produk</a></li>
<li><a href="{{ route('owner.stok-bahan') }}"><i class="fas fa-boxes"></i> Stok Bahan Baku</a></li>
@endsection

@section('content')
<div class="action-header">
    <form method="GET" class="d-flex gap-1" style="flex: 1; flex-wrap: wrap;">
        <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}" style="width:250px">
        <button class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
    </form>
</div>

<div class="card">
    <div class="card-header"><h3>🍰 Stok Produk / Kue</h3></div>
    <div class="table-responsive"><table>
        <thead><tr><th>Produk</th><th>Kategori</th><th>Stok</th><th>Min</th><th>Harga</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($produk as $p)
            <tr>
                <td><strong>{{ $p->nama_produk }}</strong></td>
                <td>{{ $p->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $p->stok }} {{ $p->satuan }}</td>
                <td>{{ $p->stok_minimum }}</td>
                <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                <td>
                    @if($p->stok <= 0)<span class="badge badge-danger">Habis</span>
                    @elseif($p->stok <= $p->stok_minimum)<span class="badge badge-warning">⚠ Menipis</span>
                    @else<span class="badge badge-success">Aman</span>@endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table></div>
</div>
@endsection

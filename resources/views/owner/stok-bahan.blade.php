@extends('layouts.admin')
@section('title', 'Dashboard Stok Bahan Baku')
@section('page-title', 'Dashboard Stok Bahan Baku')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Utama</div>
<li><a href="{{ route('owner.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('owner.users') }}"><i class="fas fa-users"></i> Kelola User</a></li>
<li><a href="{{ route('owner.transaksi') }}"><i class="fas fa-receipt"></i> Transaksi</a></li>
<li><a href="{{ route('owner.reports.index') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
<div class="sidebar-divider">Monitoring Stok</div>
<li><a href="{{ route('owner.stok-produk') }}"><i class="fas fa-birthday-cake"></i> Stok Produk</a></li>
<li><a href="{{ route('owner.stok-bahan') }}" class="active"><i class="fas fa-boxes"></i> Stok Bahan Baku</a></li>
@endsection

@section('content')
<div class="action-header" style="border-left: 5px solid var(--primary);">
    <form method="GET" class="d-flex gap-1" style="flex: 1; flex-wrap: wrap;">
        <input type="text" name="search" class="form-control" placeholder="Cari bahan..." value="{{ request('search') }}" style="width:250px">
        <button class="btn btn-primary">Cari</button>
    </form>
</div>

<div class="card" style="border-left: 5px solid var(--primary);">
    <div class="card-header"><h3>Stok Bahan Baku</h3></div>
    <div class="table-responsive"><table>
        <thead><tr><th>Nama Bahan</th><th>Stok</th><th>Min</th><th>Satuan</th><th>Supplier</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($bahan as $b)
            <tr>
                <td><strong>{{ $b->nama_bahan }}</strong></td>
                <td>{{ $b->stok }}</td>
                <td>{{ $b->stok_minimum }}</td>
                <td>{{ $b->satuan }}</td>
                <td>{{ $b->supplier ?? '-' }}</td>
                <td>
                    @if($b->stok <= 0)<span class="badge badge-danger">Habis</span>
                    @elseif($b->isStokMenipis())<span class="badge badge-warning">Menipis</span>
                    @else<span class="badge badge-success">Aman</span>@endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table></div>
</div>
@endsection

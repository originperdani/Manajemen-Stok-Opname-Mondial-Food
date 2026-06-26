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
<div class="stats-grid mb-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
    <a href="{{ route('owner.stok-bahan') }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer; background: #fff; padding: 1.5rem; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); align-items: center;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Total Bahan Baku</h4>
            <p style="font-size: 1.8rem; font-weight: 700; color: var(--text-dark); margin: 0;">{{ $totalBahan }}</p>
        </div>
    </a>
    <a href="{{ route('owner.stok-bahan', ['filter' => 'menipis']) }}" class="stat-card" style="border-left: 5px solid #dc3545; text-decoration: none; color: inherit; display: flex; cursor: pointer; background: #fff; padding: 1.5rem; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); align-items: center;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="color: #dc3545; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Bahan Baku Stok Menipis</h4>
            <p style="font-size: 1.8rem; font-weight: 700; color: #dc3545; margin: 0;">{{ $bahanMenipis }}</p>
        </div>
    </a>
</div>

<div class="action-header" style="border-left: 5px solid var(--primary);">
    <form method="GET" class="d-flex gap-1" style="flex: 1; flex-wrap: wrap;">
        <input type="text" name="search" class="form-control" placeholder="Cari bahan..." value="{{ request('search') }}" style="width:250px">
        <select name="filter" class="form-control" style="width:150px" onchange="this.form.submit()">
            <option value="">Semua</option>
            <option value="menipis" {{ request('filter')=='menipis'?'selected':'' }}>Menipis</option>
        </select>
        <button class="btn btn-primary" style="white-space: nowrap;">Cari</button>
        @if(request('search') || request('filter'))
            <a href="{{ route('owner.stok-bahan') }}" class="btn btn-danger"><i class="fas fa-undo"></i> Reset</a>
        @endif
    </form>
</div>

<div class="card" style="border-left: 5px solid var(--primary);"><div class="table-responsive"><table>
    <thead><tr><th>Nama Bahan</th><th>Stok</th><th>Min</th><th>Satuan</th><th>Harga/Satuan</th><th>Supplier</th><th>Status</th></tr></thead>
    <tbody>
        @foreach($bahan as $b)
        <tr>
            <td><strong>{{ $b->nama_bahan }}</strong></td>
            <td style="font-weight:600;{{ $b->isStokMenipis()?'color:#dc3545':'' }}">{{ $b->stok }}</td>
            <td>{{ $b->stok_minimum }}</td>
            <td>{{ $b->satuan }}</td>
            <td>Rp {{ number_format($b->harga_per_satuan, 0, ',', '.') }}</td>
            <td>{{ $b->supplier ?? '-' }}</td>
            <td>@if($b->isStokMenipis())<span class="badge" style="background: #f5c6cb; color: #721c24;">Menipis</span>@else<span class="badge" style="background: var(--secondary); color: var(--primary-dark);">Aman</span>@endif</td>
        </tr>
        @endforeach
    </tbody>
</table></div></div>

@endsection

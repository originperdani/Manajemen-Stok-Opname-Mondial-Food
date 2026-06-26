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
<div class="sidebar-divider">Akses Admin</div>
<li><a href="{{ route('gudang.dashboard') }}"><i class="fas fa-warehouse"></i> Admin Gudang</a></li>
<li><a href="{{ route('penjualan.dashboard') }}"><i class="fas fa-cash-register"></i> Admin Penjualan</a></li>
<li><a href="{{ route('produksi.dashboard') }}"><i class="fas fa-industry"></i> Admin Produksi</a></li>
@endsection

@section('content')
<div class="stat-grid mb-4" style="margin-bottom: 1.5rem;">
    <div class="stat-card" style="border-left: 5px solid var(--primary);">
        <div class="stat-info">
            <h4 style="color: var(--primary-dark);">Total Produk</h4>
            <p>{{ \App\Models\Produk::count() }}</p>
        </div>
    </div>
    <a href="{{ route('owner.stok-produk', ['status' => 'menipis']) }}" class="stat-card" style="border-left: 5px solid #dc3545; text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="color: #dc3545;">Stok Produk Menipis</h4>
            <p style="color: #dc3545;">{{ \App\Models\Produk::whereColumn('stok', '<=', 'stok_minimum')->count() }}</p>
        </div>
    </a>
</div>

<div class="action-header" style="border-left: 5px solid var(--primary);">
    <form method="GET" class="d-flex gap-2 align-items-center" style="flex: 1;">
        <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}" style="width:250px">
        <select name="kategori_id" class="form-control" style="width:200px" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
        <select name="status" class="form-control" style="width:200px" onchange="this.form.submit()">
            <option value="">Semua Status Stok</option>
            <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Stok Aman</option>
            <option value="menipis" {{ request('status') == 'menipis' ? 'selected' : '' }}>Stok Menipis / Habis</option>
        </select>
        <button class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
        @if(request('search') || request('status') || request('kategori_id'))
            <a href="{{ route('owner.stok-produk') }}" class="btn btn-danger"><i class="fas fa-undo"></i> Reset</a>
        @endif
    </form>
</div>

<div class="card" style="border-left: 5px solid var(--primary);"><div class="table-responsive"><table>
    <thead><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Min</th><th>Status</th></tr></thead>
    <tbody>
        @foreach($produk as $p)
        <tr>
            <td><strong>{{ $p->nama_produk }}</strong></td>
            <td>{{ $p->kategori->nama_kategori ?? '-' }}</td>
            <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
            <td style="{{ $p->isStokMenipis()?'color:var(--danger);font-weight:700':'' }}">{{ $p->stok }} {{ $p->satuan }}</td>
            <td>{{ $p->stok_minimum }}</td>
            <td>@if($p->stok <= 0)<span class="badge badge-danger">Habis</span>@elseif($p->isStokMenipis())<span class="badge badge-warning">Menipis</span>@else<span class="badge badge-success">Aman</span>@endif</td>
        </tr>
        @endforeach
    </tbody>
</table></div></div>
{{ $produk->withQueryString()->links() }}
@endsection

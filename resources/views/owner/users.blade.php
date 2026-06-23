@extends('layouts.admin')
@section('title', 'Kelola User')
@section('page-title', 'Kelola User')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Utama</div>
<li><a href="{{ route('owner.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('owner.users') }}" class="active"><i class="fas fa-users"></i> Kelola User</a></li>
<li><a href="{{ route('owner.transaksi') }}"><i class="fas fa-receipt"></i> Transaksi</a></li>
<li><a href="{{ route('owner.reports.index') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
<div class="sidebar-divider">Monitoring Stok</div>
<li><a href="{{ route('owner.stok-produk') }}"><i class="fas fa-birthday-cake"></i> Stok Produk</a></li>
<li><a href="{{ route('owner.stok-bahan') }}"><i class="fas fa-boxes"></i> Stok Bahan Baku</a></li>
@endsection

@section('content')
<div class="action-header" style="border-left: 5px solid var(--primary);">
    <form method="GET" style="display:flex;gap:0.75rem;flex:1;flex-wrap:wrap">
        <input type="text" name="search" class="form-control" placeholder="Cari user..." value="{{ request('search') }}" style="width:250px">
        <select name="role" class="form-control" style="width:180px" onchange="this.form.submit()">
            <option value="">Semua Role</option>
            @foreach(['owner','admin_gudang','admin_penjualan','admin_produksi','customer'] as $r)
                <option value="{{ $r }}" {{ request('role')==$r?'selected':'' }}>{{ ucwords(str_replace('_',' ',$r)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
    <a href="{{ route('owner.users.create') }}" class="btn btn-primary">Tambah User</a>
</div>

<div class="card" style="border-left: 5px solid var(--primary);">
    <div class="table-responsive">
        <table>
            <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Telepon</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td><strong>{{ $u->name }}</strong></td>
                    <td>{{ $u->email }}</td>
                    <td><span class="badge badge-primary">{{ ucwords(str_replace('_',' ',$u->role)) }}</span></td>
                    <td>{{ $u->phone ?? '-' }}</td>
                    <td><span class="badge {{ $u->is_active ? 'badge-success' : 'badge-danger' }}">{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('owner.users.edit', $u) }}" class="btn btn-warning btn-sm">Edit</a>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('owner.users.delete', $u) }}" onsubmit="return confirm('Hapus user ini?')">@csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
{{ $users->withQueryString()->links() }}
@endsection

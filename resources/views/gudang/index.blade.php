@extends('layouts.admin')
@section('title', 'Bahan Baku')
@section('page-title', 'Manajemen Bahan Baku')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Gudang</div>
<li><a href="{{ route('gudang.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('gudang.index') }}" class="active"><i class="fas fa-boxes"></i> Bahan Baku</a></li>
<li><a href="{{ route('gudang.create') }}"><i class="fas fa-plus"></i> Tambah Bahan</a></li>
<li><a href="{{ route('gudang.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="action-header">
    <form method="GET" class="d-flex gap-1" style="flex: 1; flex-wrap: wrap;">
        <input type="text" name="search" class="form-control" placeholder="Cari bahan..." value="{{ request('search') }}" style="width:250px">
        <select name="filter" class="form-control" style="width:150px" onchange="this.form.submit()">
            <option value="">Semua</option>
            <option value="menipis" {{ request('filter')=='menipis'?'selected':'' }}>⚠ Menipis</option>
        </select>
        <button class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
    </form>
    <a href="{{ route('gudang.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Bahan</a>
</div>

<div class="card"><div class="table-responsive"><table>
    <thead><tr><th>Nama Bahan</th><th>Stok</th><th>Min</th><th>Satuan</th><th>Harga/Satuan</th><th>Supplier</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
        @foreach($bahan as $b)
        <tr>
            <td><strong>{{ $b->nama_bahan }}</strong></td>
            <td style="font-weight:600;{{ $b->isStokMenipis()?'color:var(--danger)':'' }}">{{ $b->stok }}</td>
            <td>{{ $b->stok_minimum }}</td>
            <td>{{ $b->satuan }}</td>
            <td>Rp {{ number_format($b->harga_per_satuan, 0, ',', '.') }}</td>
            <td>{{ $b->supplier ?? '-' }}</td>
            <td>@if($b->isStokMenipis())<span class="badge badge-warning">⚠ Menipis</span>@else<span class="badge badge-success">Aman</span>@endif</td>
            <td>
                <div class="d-flex gap-1">
                    <button class="btn btn-success btn-sm" onclick="document.getElementById('stok-{{ $b->id }}').style.display='flex'"><i class="fas fa-plus"></i></button>
                    <a href="{{ route('gudang.edit', $b) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="{{ route('gudang.destroy', $b) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
                </div>
                <div id="stok-{{ $b->id }}" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center">
                    <div style="background:white;border-radius:16px;padding:2rem;max-width:400px;width:90%">
                        <h3 style="margin-bottom:1rem">Tambah Stok: {{ $b->nama_bahan }}</h3>
                        <form method="POST" action="{{ route('gudang.tambah-stok', $b) }}">@csrf
                            <div class="form-group"><label class="form-label">Jumlah ({{ $b->satuan }})</label><input type="number" name="jumlah" class="form-control" step="0.01" min="0.01" required></div>
                            <div class="form-group"><label class="form-label">Keterangan</label><input type="text" name="keterangan" class="form-control"></div>
                            <div class="d-flex gap-1 mt-4"><button class="btn btn-success"><i class="fas fa-plus"></i> Tambah</button><button type="button" class="btn btn-secondary" onclick="this.closest('[id^=stok]').style.display='none'">Batal</button></div>
                        </form>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table></div></div>
{{ $bahan->withQueryString()->links() }}
@endsection

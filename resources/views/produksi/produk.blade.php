@extends('layouts.admin')
@section('title', 'Produk')
@section('page-title', 'Manajemen Produk')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Produksi</div>
<li><a href="{{ route('produksi.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('produksi.produk') }}" class="active"><i class="fas fa-birthday-cake"></i> Produk</a></li>
<li><a href="{{ route('produksi.kategori') }}"><i class="fas fa-tags"></i> Kategori</a></li>
<li><a href="{{ route('produksi.resep') }}"><i class="fas fa-book-open"></i> Resep</a></li>
<li><a href="{{ route('produksi.input') }}"><i class="fas fa-plus-circle"></i> Input Produksi</a></li>
<li><a href="{{ route('produksi.riwayat') }}"><i class="fas fa-history"></i> Detail Riwayat Produksi</a></li>
<li><a href="{{ route('produksi.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="action-header" style="border-left: 5px solid var(--primary);">
    <form method="GET" class="d-flex gap-1" style="flex: 1;"><input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}" style="width:250px"><button class="btn btn-primary"><i class="fas fa-search"></i> Cari</button></form>
    <a href="{{ route('produksi.produk.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Produk</a>
</div>

<div class="card" style="border-left: 5px solid var(--primary);"><div class="table-responsive"><table>
    <thead><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
        @foreach($produk as $p)
        <tr>
            <td><strong>{{ $p->nama_produk }}</strong></td>
            <td>{{ $p->kategori->nama_kategori ?? '-' }}</td>
            <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
            <td style="{{ $p->isStokMenipis()?'color:var(--danger);font-weight:700':'' }}">{{ $p->stok }} {{ $p->satuan }}</td>
            <td>@if($p->stok <= 0)<span class="badge badge-danger">Habis</span>@elseif($p->isStokMenipis())<span class="badge badge-warning">Menipis</span>@else<span class="badge badge-success">Aman</span>@endif</td>
            <td>
                <div class="d-flex gap-1">
                    <a href="{{ route('produksi.produk.edit', $p) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="{{ route('produksi.produk.delete', $p) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table></div></div>
{{ $produk->withQueryString()->links() }}
@endsection

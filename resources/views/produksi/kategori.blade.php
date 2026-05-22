@extends('layouts.admin')
@section('title', 'Kategori')
@section('page-title', 'Kategori Produk')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Produksi</div>
<li><a href="{{ route('produksi.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('produksi.produk') }}"><i class="fas fa-birthday-cake"></i> Produk</a></li>
<li><a href="{{ route('produksi.kategori') }}" class="active"><i class="fas fa-tags"></i> Kategori</a></li>
<li><a href="{{ route('produksi.resep') }}"><i class="fas fa-book-open"></i> Resep</a></li>
<li><a href="{{ route('produksi.input') }}"><i class="fas fa-plus-circle"></i> Input Produksi</a></li>
<li><a href="{{ route('produksi.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="grid-2">
    <div class="card">
        <div class="card-header"><h3>➕ Tambah Kategori</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('produksi.kategori.store') }}">@csrf
                <div class="form-group"><label class="form-label">Nama Kategori *</label><input type="text" name="nama_kategori" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control"></textarea></div>
                <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>📋 Daftar Kategori</h3></div>
        <div class="card-body" style="padding:0"><table>
            <thead><tr><th>Kategori</th><th>Slug</th><th>Jumlah Produk</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($kategori as $kat)
                <tr>
                    <td><strong>{{ $kat->nama_kategori }}</strong></td>
                    <td>{{ $kat->slug }}</td>
                    <td>{{ $kat->produk_count }}</td>
                    <td>
                        <form method="POST" action="{{ route('produksi.kategori.delete', $kat) }}" onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table></div>
    </div>
</div>
@endsection

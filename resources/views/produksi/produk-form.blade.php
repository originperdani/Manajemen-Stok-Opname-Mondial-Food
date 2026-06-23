@extends('layouts.admin')
@section('title', isset($produk) ? 'Edit Produk' : 'Tambah Produk')
@section('page-title', isset($produk) ? 'Edit Produk' : 'Tambah Produk Baru')
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
<div class="card" style="max-width:800px; margin:0 auto; border-left:5px solid var(--primary);">
    <div class="card-header">
        <h3>
            {{ isset($produk) ? 'Edit' : 'Tambah' }} Produk
        </h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($produk) ? route('produksi.produk.update', $produk) : route('produksi.produk.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($produk)) @method('PUT') @endif
            
            <div class="form-group">
                <label class="form-label">Nama Produk *</label>
                <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk', $produk->nama_produk ?? '') }}" required>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="kategori_id" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id', $produk->kategori_id ?? '')==$kat->id?'selected':'' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga *</label>
                    <input type="number" name="harga" class="form-control" value="{{ old('harga', $produk->harga ?? '') }}" required>
                </div>
            </div>
            
            <div class="grid-2">
                @if(!isset($produk))
                    <div class="form-group">
                        <label class="form-label">Stok Awal *</label>
                        <input type="number" name="stok" class="form-control" value="{{ old('stok',0) }}" required>
                    </div>
                @endif
                <div class="form-group">
                    <label class="form-label">Stok Minimum</label>
                    <input type="number" name="stok_minimum" class="form-control" value="{{ old('stok_minimum', $produk->stok_minimum ?? 5) }}">
                </div>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Satuan</label>
                    <select name="satuan" class="form-control">
                        @foreach(['pcs','box','toples','loyang'] as $s)
                            <option value="{{ $s }}" {{ old('satuan', $produk->satuan ?? 'pcs')==$s?'selected':'' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Berat (gram)</label>
                    <input type="number" name="berat" class="form-control" step="0.01" value="{{ old('berat', $produk->berat ?? '') }}">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $produk->deskripsi ?? '') }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Gambar</label>
                <input type="file" name="gambar" class="form-control" accept="image/*">
                @if(isset($produk) && $produk->gambar)
                    <div style="margin-top:1rem;">
                        <label class="form-label">Gambar Saat Ini:</label>
                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}" style="max-width:200px; border-radius:12px; margin-top:.5rem;">
                    </div>
                @endif
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label style="display:flex; align-items:center; gap:.75rem; cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $produk->is_active ?? true) ? 'checked' : '' }}> 
                        Aktif
                    </label>
                </div>
                <div class="form-group">
                    <label style="display:flex; align-items:center; gap:.75rem; cursor:pointer;">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $produk->is_featured ?? false) ? 'checked' : '' }}> 
                        Produk Unggulan
                    </label>
                </div>
            </div>
            
            <div class="d-flex gap-2" style="margin-top:2rem;">
                <button class="btn btn-primary">
                    Simpan
                </button>
                <a href="{{ route('produksi.produk') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.admin')
@section('title', isset($bahan) ? 'Edit Bahan Baku' : 'Tambah Bahan Baku')
@section('page-title', isset($bahan) ? 'Edit Bahan Baku' : 'Tambah Bahan Baku')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Gudang</div>
<li><a href="{{ route('gudang.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('gudang.index') }}"><i class="fas fa-boxes"></i> Bahan Baku</a></li>
<li><a href="{{ route('gudang.create') }}" class="active"><i class="fas fa-plus"></i> Tambah Bahan</a></li>
<li><a href="{{ route('gudang.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><h3>{{ isset($bahan) ? '✏️ Edit' : '➕ Tambah' }} Bahan Baku</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ isset($bahan) ? route('gudang.update', $bahan) : route('gudang.store') }}">
            @csrf
            @if(isset($bahan)) @method('PUT') @endif
            <div class="form-group"><label class="form-label">Nama Bahan *</label><input type="text" name="nama_bahan" class="form-control" value="{{ old('nama_bahan', $bahan->nama_bahan ?? '') }}" required></div>
            <div class="grid-2">
                <div class="form-group"><label class="form-label">Satuan *</label>
                    <select name="satuan" class="form-control" required>
                        @foreach(['kg','gram','liter','ml','pcs','bungkus'] as $s)
                            <option value="{{ $s }}" {{ old('satuan', $bahan->satuan ?? '')==$s?'selected':'' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Harga/Satuan *</label><input type="number" name="harga_per_satuan" class="form-control" value="{{ old('harga_per_satuan', $bahan->harga_per_satuan ?? 0) }}" required></div>
            </div>
            @if(!isset($bahan))
            <div class="form-group"><label class="form-label">Stok Awal *</label><input type="number" name="stok" class="form-control" step="0.01" value="{{ old('stok', 0) }}" required></div>
            @endif
            <div class="form-group"><label class="form-label">Stok Minimum *</label><input type="number" name="stok_minimum" class="form-control" step="0.01" value="{{ old('stok_minimum', $bahan->stok_minimum ?? 10) }}" required></div>
            <div class="form-group"><label class="form-label">Supplier</label><input type="text" name="supplier" class="form-control" value="{{ old('supplier', $bahan->supplier ?? '') }}"></div>
            <div class="form-group"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control">{{ old('keterangan', $bahan->keterangan ?? '') }}</textarea></div>
            <div class="d-flex gap-1 mt-4"><button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button><a href="{{ route('gudang.index') }}" class="btn btn-secondary">Batal</a></div>
        </form>
    </div>
</div>
@endsection

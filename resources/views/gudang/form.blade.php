@extends('layouts.admin')
@section('title', isset($bahan) ? 'Edit Bahan Baku' : 'Tambah Bahan Baku')
@section('page-title', isset($bahan) ? 'Edit Bahan Baku' : 'Tambah Bahan Baku')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Gudang</div>
<li><a href="{{ route('gudang.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('gudang.index') }}"><i class="fas fa-boxes"></i> Bahan Baku</a></li>
<li><a href="{{ route('gudang.create') }}" class="active"><i class="fas fa-plus"></i> Tambah Bahan</a></li>
<li><a href="{{ route('gudang.riwayat') }}"><i class="fas fa-history"></i> Riwayat Stok</a></li>
<li><a href="{{ route('gudang.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="card" style="max-width:700px; margin:0 auto; border-left:5px solid var(--primary);">
    <div class="card-header" style="padding:0.75rem 1.25rem;">
        <h3 style="font-size:1rem;">
            {{ isset($bahan) ? 'Edit' : 'Tambah' }} Bahan Baku
        </h3>
    </div>
    <div class="card-body" style="padding:0.75rem 1.25rem;">
        <form method="POST" action="{{ isset($bahan) ? route('gudang.update', $bahan) : route('gudang.store') }}">
            @csrf
            @if(isset($bahan)) @method('PUT') @endif
            
            <div class="form-group" style="margin-bottom:0.5rem;">
                <label class="form-label" style="margin-bottom:0.25rem;">Nama Bahan *</label>
                <input type="text" name="nama_bahan" id="nama-bahan" class="form-control" style="padding:0.4rem 0.8rem;" value="{{ old('nama_bahan', $bahan->nama_bahan ?? '') }}" required>
            </div>
            
            <div class="grid-2" style="gap:0.75rem;">
                <div class="form-group" style="margin-bottom:0.5rem;">
                    <label class="form-label" style="margin-bottom:0.25rem;">Satuan *</label>
                    <select name="satuan" class="form-control" style="padding:0.4rem 0.8rem;" required>
                        @foreach(['kg','gram','liter','ml','pcs','bungkus'] as $s)
                            <option value="{{ $s }}" {{ old('satuan', $bahan->satuan ?? '')==$s?'selected':'' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0.5rem;">
                    <label class="form-label" style="margin-bottom:0.25rem;">Harga/Satuan *</label>
                    <input type="number" name="harga_per_satuan" class="form-control" style="padding:0.4rem 0.8rem;" value="{{ old('harga_per_satuan', $bahan->harga_per_satuan ?? 0) }}" required>
                </div>
            </div>
            
            @if(!isset($bahan))
            <div class="form-group" style="margin-bottom:0.5rem;">
                <label class="form-label" style="margin-bottom:0.25rem;">Stok Awal *</label>
                <input type="number" name="stok" class="form-control" style="padding:0.4rem 0.8rem;" step="0.01" value="{{ old('stok', 0) }}" required>
            </div>
            <div class="form-group" style="margin-bottom:0.5rem;">
                <label class="form-label" style="margin-bottom:0.25rem;">Keterangan Log Stok (Auto-Generate)</label>
                <input type="text" class="form-control" style="padding:0.4rem 0.8rem;" id="auto-keterangan" readonly value="Tambah Bahan Baku Baru Stok Awal ">
                <small style="color: var(--text-light); margin-top: .15rem; display: block; font-size:0.8rem;">
                    Keterangan ini akan otomatis terisi saat menyimpan bahan baru.
                </small>
            </div>
            @endif
            
            <div class="form-group" style="margin-bottom:0.5rem;">
                <label class="form-label" style="margin-bottom:0.25rem;">Stok Minimum *</label>
                <input type="number" name="stok_minimum" class="form-control" style="padding:0.4rem 0.8rem;" step="0.01" value="{{ old('stok_minimum', $bahan->stok_minimum ?? 10) }}" required>
            </div>
            
            <div class="form-group" style="margin-bottom:0.75rem;">
                <label class="form-label" style="margin-bottom:0.25rem;">Supplier</label>
                <input type="text" name="supplier" class="form-control" style="padding:0.4rem 0.8rem;" value="{{ old('supplier', $bahan->supplier ?? '') }}">
            </div>
            
            <div class="d-flex gap-1" style="justify-content: flex-end;">
                <a href="{{ route('gudang.index') }}" class="btn btn-secondary" style="padding:0.4rem 0.9rem; font-size:0.9rem;">
                    Batal
                </a>
                <button class="btn btn-primary" style="padding:0.4rem 0.9rem; font-size:0.9rem;">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const namaBahanInput = document.getElementById('nama-bahan');
    const autoKeteranganInput = document.getElementById('auto-keterangan');
    
    if (namaBahanInput && autoKeteranganInput) {
        namaBahanInput.addEventListener('input', function() {
            autoKeteranganInput.value = 'Tambah Bahan Baku Baru Stok Awal ' + this.value;
        });
    }
</script>
@endsection
@extends('layouts.admin')
@section('title', 'Tambah Resep')
@section('page-title', 'Tambah Resep Baru')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Produksi</div>
<li><a href="{{ route('produksi.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('produksi.produk') }}"><i class="fas fa-birthday-cake"></i> Produk</a></li>
<li><a href="{{ route('produksi.kategori') }}"><i class="fas fa-tags"></i> Kategori</a></li>
<li><a href="{{ route('produksi.resep') }}" class="active"><i class="fas fa-book-open"></i> Resep</a></li>
<li><a href="{{ route('produksi.input') }}"><i class="fas fa-plus-circle"></i> Input Produksi</a></li>
<li><a href="{{ route('produksi.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="card" style="max-width:800px">
    <div class="card-header"><h3>📖 Resep Baru</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('produksi.resep.store') }}">@csrf
            <div class="grid-2">
                <div class="form-group"><label class="form-label">Nama Resep *</label><input type="text" name="nama_resep" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Produk *</label>
                    <select name="produk_id" class="form-control" required><option value="">Pilih Produk</option>@foreach($produk as $p)<option value="{{ $p->id }}">{{ $p->nama_produk }}</option>@endforeach</select>
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group"><label class="form-label">Hasil Produksi (pcs)</label><input type="number" name="hasil_produksi" class="form-control" value="1" min="1"></div>
                <div class="form-group"><label class="form-label">Waktu Produksi (menit)</label><input type="number" name="waktu_produksi" class="form-control"></div>
            </div>
            <div class="form-group"><label class="form-label">Instruksi</label><textarea name="instruksi" class="form-control" rows="3"></textarea></div>

            <h4 style="margin:1.5rem 0 1rem">📦 Bahan yang Dibutuhkan</h4>
            <div id="bahanContainer">
                <div class="bahan-row d-flex gap-1 mb-2 align-center">
                    <select name="bahan[0][bahan_baku_id]" class="form-control" required style="flex:2">
                        <option value="">Pilih Bahan</option>
                        @foreach($bahanBaku as $b)<option value="{{ $b->id }}">{{ $b->nama_bahan }} ({{ $b->satuan }})</option>@endforeach
                    </select>
                    <input type="number" name="bahan[0][jumlah]" class="form-control" placeholder="Jumlah" step="0.01" min="0.01" required style="flex:1">
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.bahan-row').remove()"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <button type="button" class="btn btn-secondary mb-3" onclick="addBahan()"><i class="fas fa-plus"></i> Tambah Baris Bahan</button>

            <div class="d-flex gap-1 mt-4"><button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Resep</button><a href="{{ route('produksi.resep') }}" class="btn btn-secondary">Batal</a></div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let bahanIdx = 1;
function addBahan() {
    const html = `<div class="bahan-row d-flex gap-1 mb-2 align-center">
        <select name="bahan[${bahanIdx}][bahan_baku_id]" class="form-control" required style="flex:2">
            <option value="">Pilih Bahan</option>
            @foreach($bahanBaku as $b)<option value="{{ $b->id }}">{{ $b->nama_bahan }} ({{ $b->satuan }})</option>@endforeach
        </select>
        <input type="number" name="bahan[${bahanIdx}][jumlah]" class="form-control" placeholder="Jumlah" step="0.01" min="0.01" required style="flex:1">
        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.bahan-row').remove()"><i class="fas fa-times"></i></button>
    </div>`;
    document.getElementById('bahanContainer').insertAdjacentHTML('beforeend', html);
    bahanIdx++;
}
</script>
@endsection

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
<li><a href="{{ route('produksi.riwayat') }}"><i class="fas fa-history"></i> Detail Riwayat Produksi</a></li>
<li><a href="{{ route('produksi.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="card" style="max-width: 850px; margin: 0 auto; border-left: 5px solid var(--primary);">
    <div class="card-header" style="padding: 1rem 1.5rem;">
        <h3 style="font-size: 1.2rem;">Resep Baru</h3>
    </div>
    <div class="card-body" style="padding: 1.5rem;">
        <form method="POST" action="{{ route('produksi.resep.store') }}">@csrf
            <div class="grid-2" style="gap: 0.75rem;">
                <div class="form-group" style="margin-bottom: 0.75rem;">
                    <label class="form-label" style="margin-bottom: 0.4rem; font-size: 0.95rem;">Nama Resep *</label>
                    <input type="text" name="nama_resep" class="form-control" style="padding: 0.5rem 0.9rem;" required>
                </div>
                <div class="form-group" style="margin-bottom: 0.75rem;">
                    <label class="form-label" style="margin-bottom: 0.4rem; font-size: 0.95rem;">Nama Produk *</label>
                    <input type="text" name="nama_produk" class="form-control" style="padding: 0.5rem 0.9rem;" required>
                </div>
            </div>
            <div class="grid-2" style="gap: 0.75rem;">
                <div class="form-group" style="margin-bottom: 0.75rem;">
                    <label class="form-label" style="margin-bottom: 0.4rem; font-size: 0.95rem;">Kategori *</label>
                    <select name="kategori_id" class="form-control" style="padding: 0.5rem 0.9rem;" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0.75rem;">
                    <label class="form-label" style="margin-bottom: 0.4rem; font-size: 0.95rem;">Hasil Produksi (pcs)</label>
                    <input type="number" name="hasil_produksi" class="form-control" style="padding: 0.5rem 0.9rem;" value="1" min="1">
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 0.75rem;">
                <label class="form-label" style="margin-bottom: 0.4rem; font-size: 0.95rem;">Waktu Produksi (menit)</label>
                <input type="number" name="waktu_produksi" class="form-control" style="padding: 0.5rem 0.9rem;">
            </div>
            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label" style="margin-bottom: 0.4rem; font-size: 0.95rem;">Instruksi</label>
                <textarea name="instruksi" class="form-control" style="padding: 0.5rem 0.9rem;" rows="2"></textarea>
            </div>

            <h4 style="margin: 1rem 0 0.75rem; font-family: 'Raleway', sans-serif; font-weight: 800; color: var(--text-dark); font-size: 1.05rem;">
                Bahan yang Dibutuhkan
            </h4>
            <div id="bahanContainer">
                <div class="bahan-row d-flex gap-1 mb-2 align-center">
                    <select name="bahan[0][bahan_baku_id]" class="form-control" required style="flex: 2; padding: 0.5rem 0.9rem;">
                        <option value="">Pilih Bahan</option>
                        @foreach($bahanBaku as $b)
                            <option value="{{ $b->id }}">{{ $b->nama_bahan }} ({{ $b->satuan }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="bahan[0][jumlah]" class="form-control" placeholder="Jumlah" step="0.01" min="0.01" required style="flex: 1; padding: 0.5rem 0.9rem;">
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.bahan-row').remove()" style="padding: 0.5rem 0.75rem; font-size: 0.9rem;">Hapus</button>
                </div>
            </div>
            <button type="button" class="btn btn-secondary mb-2" onclick="addBahan()" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Tambah Baris Bahan</button>

            <div class="d-flex gap-1 mt-2" style="justify-content: flex-end;">
                <a href="{{ route('produksi.resep') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Batal</a>
                <button class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Simpan Resep</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let bahanIdx = 1;
function addBahan() {
    const html = `<div class="bahan-row d-flex gap-1 mb-2 align-center">
        <select name="bahan[${bahanIdx}][bahan_baku_id]" class="form-control" required style="flex:2; padding: 0.5rem 0.9rem;">
            <option value="">Pilih Bahan</option>
            @foreach($bahanBaku as $b)<option value="{{ $b->id }}">{{ $b->nama_bahan }} ({{ $b->satuan }})</option>@endforeach
        </select>
        <input type="number" name="bahan[${bahanIdx}][jumlah]" class="form-control" placeholder="Jumlah" step="0.01" min="0.01" required style="flex:1; padding: 0.5rem 0.9rem;">
        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.bahan-row').remove()" style="padding: 0.5rem 0.75rem; font-size: 0.9rem;">Hapus</button>
    </div>`;
    document.getElementById('bahanContainer').insertAdjacentHTML('beforeend', html);
    bahanIdx++;
}
</script>
@endsection
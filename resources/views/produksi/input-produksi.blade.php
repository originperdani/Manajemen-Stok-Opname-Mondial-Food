@extends('layouts.admin')
@section('title', 'Input Produksi')
@section('page-title', 'Input Produksi')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Produksi</div>
<li><a href="{{ route('produksi.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('produksi.produk') }}"><i class="fas fa-birthday-cake"></i> Produk</a></li>
<li><a href="{{ route('produksi.kategori') }}"><i class="fas fa-tags"></i> Kategori</a></li>
<li><a href="{{ route('produksi.resep') }}"><i class="fas fa-book-open"></i> Resep</a></li>
<li><a href="{{ route('produksi.input') }}" class="active"><i class="fas fa-plus-circle"></i> Input Produksi</a></li>
<li><a href="{{ route('produksi.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><h3>🏭 Proses Produksi</h3></div>
    <div class="card-body">
        @if($resep->count() > 0)
        <form method="POST" action="{{ route('produksi.input.proses') }}">@csrf
            <div class="form-group">
                <label class="form-label">Pilih Resep *</label>
                <select name="resep_id" class="form-control" required id="resepSelect" onchange="showResepInfo()">
                    <option value="">-- Pilih Resep --</option>
                    @foreach($resep as $r)
                        <option value="{{ $r->id }}" data-produk="{{ $r->produk->nama_produk }}" data-hasil="{{ $r->hasil_produksi }}">{{ $r->nama_resep }} ({{ $r->produk->nama_produk }})</option>
                    @endforeach
                </select>
            </div>
            <div id="resepInfo" style="display:none;background:var(--bg-warm);padding:1rem;border-radius:12px;margin-bottom:1rem">
                <p><strong>Produk:</strong> <span id="infoProduk"></span></p>
                <p><strong>Hasil per batch:</strong> <span id="infoHasil"></span> pcs</p>
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Batch *</label>
                <input type="number" name="jumlah_produksi" class="form-control" value="1" min="1" required id="jumlahBatch" oninput="updateTotal()">
                <small class="text-muted" id="totalHasil"></small>
            </div>
            <div class="form-group"><label class="form-label">Catatan</label><textarea name="catatan" class="form-control" placeholder="Catatan produksi (opsional)"></textarea></div>
            <button class="btn btn-primary btn-lg" style="width: auto; padding: 0.85rem 2.5rem;"><i class="fas fa-play"></i> Mulai Produksi</button>
        </form>
        @else
            <div class="text-center" style="padding:2rem;color:var(--text-light)">
                <i class="fas fa-book-open" style="font-size:3rem;margin-bottom:1rem;color:var(--border)"></i>
                <h3>Belum Ada Resep</h3>
                <p>Buat resep terlebih dahulu sebelum memulai produksi</p>
                <a href="{{ route('produksi.resep.create') }}" class="btn btn-primary mt-2"><i class="fas fa-plus"></i> Buat Resep</a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function showResepInfo() {
    const sel = document.getElementById('resepSelect');
    const opt = sel.options[sel.selectedIndex];
    const info = document.getElementById('resepInfo');
    if (sel.value) {
        info.style.display = 'block';
        document.getElementById('infoProduk').textContent = opt.dataset.produk;
        document.getElementById('infoHasil').textContent = opt.dataset.hasil;
        updateTotal();
    } else { info.style.display = 'none'; }
}
function updateTotal() {
    const sel = document.getElementById('resepSelect');
    const opt = sel.options[sel.selectedIndex];
    const batch = parseInt(document.getElementById('jumlahBatch').value) || 0;
    const hasil = parseInt(opt?.dataset?.hasil) || 0;
    document.getElementById('totalHasil').textContent = `Total hasil: ${batch * hasil} pcs`;
}
</script>
@endsection

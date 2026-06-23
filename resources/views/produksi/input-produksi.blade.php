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
<li><a href="{{ route('produksi.riwayat') }}"><i class="fas fa-history"></i> Detail Riwayat Produksi</a></li>
<li><a href="{{ route('produksi.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="card" style="max-width: 750px; margin: 0 auto; border-left: 5px solid var(--primary);">
    <div class="card-header" style="padding: 1rem 1.5rem;">
        <h3 style="font-size: 1.2rem;">Proses Produksi</h3>
    </div>
    <div class="card-body" style="padding: 1.5rem;">
        @if($resep->count() > 0)
        <form method="POST" action="{{ route('produksi.input.proses') }}">
            @csrf

            {{-- Pilih Resep --}}
            <div class="form-group" style="margin-bottom: 0.75rem;">
                <label class="form-label" style="margin-bottom: 0.4rem; font-size: 0.95rem;">Pilih Resep <span style="color: #c0392b;">*</span></label>
                <select name="resep_id" class="form-control" style="padding: 0.5rem 0.9rem;" required id="resepSelect" onchange="showResepInfo()">
                    <option value="">-- Pilih Resep --</option>
                    @foreach($resep as $r)
                        <option value="{{ $r->id }}" data-produk="{{ $r->produk->nama_produk }}" data-hasil="{{ $r->hasil_produksi }}">{{ $r->nama_resep }} ({{ $r->produk->nama_produk }})</option>
                    @endforeach
                </select>

                {{-- Info Resep --}}
                <div id="resepInfo" style="display: none; background: #fff9f2; border: 1px solid rgba(122, 75, 34, 0.1); border-radius: 12px; padding: 0.75rem 1rem; margin-top: 0.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: var(--text-medium); padding: 0.15rem 0;">
                        <strong style="color: var(--text-dark);">Produk:</strong>
                        <span id="infoProduk"></span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: var(--text-medium); padding: 0.15rem 0;">
                        <strong style="color: var(--text-dark);">Hasil per batch:</strong>
                        <span id="infoHasil"></span> pcs
                    </div>
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid var(--border); margin: 1rem 0;">

            {{-- Jumlah Batch --}}
            <div class="form-group" style="margin-bottom: 0.75rem;">
                <label class="form-label" style="margin-bottom: 0.4rem; font-size: 0.95rem;">Jumlah Batch <span style="color: #c0392b;">*</span></label>
                <input type="number" name="jumlah_produksi" class="form-control" style="padding: 0.5rem 0.9rem;" value="1" min="1" required id="jumlahBatch" oninput="updateTotal()">
                <div id="totalHasil" style="display: inline-flex; align-items: center; gap: 0.4rem; margin-top: 0.4rem; font-size: 0.85rem; font-weight: 600; color: var(--primary); opacity: 0; transition: opacity 0.3s ease;">
                    <span id="totalHasilText"></span>
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid var(--border); margin: 1rem 0;">

            {{-- Catatan --}}
            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label" style="margin-bottom: 0.4rem; font-size: 0.95rem;">Catatan</label>
                <textarea name="catatan" class="form-control" style="padding: 0.5rem 0.9rem;" placeholder="Catatan produksi (opsional)" rows="2"></textarea>
            </div>

            {{-- Submit & Batal --}}
            <div class="d-flex gap-1 mt-2" style="justify-content: flex-end;">
                <a href="{{ route('produksi.dashboard') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                    Mulai Produksi
                </button>
            </div>
        </form>
        @else
        <div style="text-align: center; padding: 2.5rem 1.5rem;">
            <h3 style="font-family: 'Raleway', sans-serif; font-size: 1.1rem; color: var(--text-medium); margin-bottom: 0.5rem;">Belum Ada Resep</h3>
            <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 1rem;">Buat resep terlebih dahulu sebelum memulai produksi.</p>
            <a href="{{ route('produksi.resep.create') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                Buat Resep
            </a>
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
    const totalHasilEl = document.getElementById('totalHasil');
    
    if (sel.value) {
        info.style.display = 'block';
        document.getElementById('infoProduk').textContent = opt.dataset.produk;
        document.getElementById('infoHasil').textContent = opt.dataset.hasil;
        updateTotal();
    } else {
        info.style.display = 'none';
        totalHasilEl.style.opacity = '0';
    }
}

function updateTotal() {
    const sel = document.getElementById('resepSelect');
    const opt = sel.options[sel.selectedIndex];
    const batch = parseInt(document.getElementById('jumlahBatch').value) || 0;
    const hasil = parseInt(opt?.dataset?.hasil) || 0;
    const total = batch * hasil;
    const el = document.getElementById('totalHasil');
    const textEl = document.getElementById('totalHasilText');

    if (sel.value && total > 0) {
        textEl.textContent = `Total hasil: ${total} pcs`;
        el.style.opacity = '1';
    } else {
        el.style.opacity = '0';
    }
}
</script>
@endsection
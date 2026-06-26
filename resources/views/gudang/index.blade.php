@extends('layouts.admin')
@section('title', 'Bahan Baku')
@section('page-title', 'Manajemen Bahan Baku')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Gudang</div>
<li><a href="{{ route('gudang.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('gudang.index') }}" class="active"><i class="fas fa-boxes"></i> Bahan Baku</a></li>
<li><a href="{{ route('gudang.create') }}"><i class="fas fa-plus"></i> Tambah Bahan</a></li>
<li><a href="{{ route('gudang.riwayat') }}"><i class="fas fa-history"></i> Riwayat Stok</a></li>
<li><a href="{{ route('gudang.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="stats-grid mb-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
    <a href="{{ route('gudang.index') }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer; background: #fff; padding: 1.5rem; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); align-items: center;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Total Bahan Baku</h4>
            <p style="font-size: 1.8rem; font-weight: 700; color: var(--text-dark); margin: 0;">{{ $totalBahan }}</p>
        </div>
    </a>
    <a href="{{ route('gudang.index', ['filter' => 'menipis']) }}" class="stat-card" style="border-left: 5px solid #dc3545; text-decoration: none; color: inherit; display: flex; cursor: pointer; background: #fff; padding: 1.5rem; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); align-items: center;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="color: #dc3545; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Bahan Baku Stok Menipis</h4>
            <p style="font-size: 1.8rem; font-weight: 700; color: #dc3545; margin: 0;">{{ $bahanMenipis }}</p>
        </div>
    </a>
</div>

<div class="action-header" style="border-left: 5px solid var(--primary);">
    <form method="GET" class="d-flex gap-1" style="flex: 1; flex-wrap: wrap;">
        <input type="text" name="search" class="form-control" placeholder="Cari bahan..." value="{{ request('search') }}" style="width:250px">
        <select name="filter" class="form-control" style="width:150px" onchange="this.form.submit()">
            <option value="">Semua</option>
            <option value="menipis" {{ request('filter')=='menipis'?'selected':'' }}>Menipis</option>
        </select>
        <button class="btn btn-primary" style="white-space: nowrap;">Cari</button>
    </form>
    <a href="{{ route('gudang.create') }}" class="btn btn-primary" style="white-space: nowrap;">Tambah Bahan</a>
</div>

<div class="card" style="border-left: 5px solid var(--primary);"><div class="table-responsive"><table>
    <thead><tr><th>Nama Bahan</th><th>Stok</th><th>Min</th><th>Satuan</th><th>Harga/Satuan</th><th>Supplier</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
        @foreach($bahan as $b)
        <tr>
            <td><strong>{{ $b->nama_bahan }}</strong></td>
            <td style="font-weight:600;{{ $b->isStokMenipis()?'color:#dc3545':'' }}">{{ $b->stok }}</td>
            <td>{{ $b->stok_minimum }}</td>
            <td>{{ $b->satuan }}</td>
            <td>Rp {{ number_format($b->harga_per_satuan, 0, ',', '.') }}</td>
            <td>{{ $b->supplier ?? '-' }}</td>
            <td>@if($b->isStokMenipis())<span class="badge" style="background: #f5c6cb; color: #721c24;">Menipis</span>@else<span class="badge" style="background: var(--secondary); color: var(--primary-dark);">Aman</span>@endif</td>
            <td>
                <div class="d-flex gap-1" style="flex-wrap: nowrap;">
                    <button class="btn btn-primary btn-sm" data-open-modal="stok-{{ $b->id }}"><i class="fas fa-plus"></i></button>
                    <a href="{{ route('gudang.edit', $b) }}" class="btn btn-secondary btn-sm" style="background: var(--accent);"><i class="fas fa-edit" style="color: var(--primary-dark);"></i></a>
                    <form method="POST" action="{{ route('gudang.destroy', $b) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-secondary btn-sm" style="background: var(--accent-soft);"><i class="fas fa-trash" style="color: var(--primary-dark);"></i></button></form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table></div></div>
@push('modals')
@foreach($bahan as $b)
<!-- Modal {{ $b->id }} -->
<div id="stok-{{ $b->id }}" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);z-index:999999;align-items:center;justify-content:center;overflow:hidden">
    <div style="background:white;border-radius:20px;padding:2.5rem;max-width:450px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.3);position:relative;max-height:90vh;overflow-y:auto;border-left:5px solid var(--primary)">
        <button type="button" data-close-modal="stok-{{ $b->id }}" style="position:absolute;top:1rem;right:1rem;background:none;border:none;font-size:1.8rem;cursor:pointer;color:#999;z-index:1">&times;</button>
        <h3 style="margin-bottom:1.5rem;font-family:'Playfair Display', serif;color:var(--primary-dark);font-size:1.5rem">Tambah Stok: {{ $b->nama_bahan }}</h3>
        <form method="POST" action="{{ route('gudang.tambah-stok', $b) }}">@csrf
            <div class="form-group"><label class="form-label">Jumlah ({{ $b->satuan }})</label><input type="number" name="jumlah" class="form-control" step="0.01" min="0.01" required style="padding:1rem;font-size:1rem"></div>
            <div class="form-group">
                <label class="form-label">Keterangan (Auto-Generate)</label>
                <input type="text" name="keterangan" class="form-control" value="Update Penambahan Stok {{ $b->nama_bahan }}" readonly style="background:#f8f9fa">
                <small style="display:block; margin-top: 0.75rem; font-size: 0.85rem; color: var(--text-light);">Keterangan otomatis sesuai nama bahan</small>
            </div>
            <div class="d-flex gap-2 mt-5"><button class="btn btn-primary" style="padding:0.75rem 2rem">Tambah Stok</button><button type="button" data-close-modal="stok-{{ $b->id }}" class="btn btn-secondary" style="padding:0.75rem 2rem">Batal</button></div>
        </form>
    </div>
</div>
@endforeach
@endpush
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Event delegation untuk open modal
    document.addEventListener('click', function(e) {
        const openBtn = e.target.closest('[data-open-modal]');
        if (openBtn) {
            const modalId = openBtn.getAttribute('data-open-modal');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }
    });

    // Event delegation untuk close modal
    document.addEventListener('click', function(e) {
        const closeBtn = e.target.closest('[data-close-modal]');
        if (closeBtn) {
            const modalId = closeBtn.getAttribute('data-close-modal');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }
    });

    // Close when clicking outside modal (event delegation)
    document.addEventListener('click', function(e) {
        if (e.target.id && (e.target.id.startsWith('modal-') || e.target.id.startsWith('stok-'))) {
            e.target.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
});
</script>
@endsection

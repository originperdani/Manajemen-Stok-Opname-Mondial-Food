@extends('layouts.admin')
@section('title', 'Dashboard Gudang')
@section('page-title', 'Dashboard Gudang')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Gudang</div>
<li><a href="{{ route('gudang.dashboard') }}" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('gudang.index') }}"><i class="fas fa-boxes"></i> Bahan Baku</a></li>
<li><a href="{{ route('gudang.create') }}"><i class="fas fa-plus"></i> Tambah Bahan</a></li>
<li><a href="{{ route('gudang.riwayat') }}"><i class="fas fa-history"></i> Riwayat Stok</a></li>
<li><a href="{{ route('gudang.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="stat-grid">
        <div class="stat-card" style="border-left: 5px solid var(--primary);">
            <div class="stat-info" style="flex: 1;">
                <h4>Total Bahan Baku</h4>
                <p>{{ $totalBahan }}</p>
            </div>
        </div>
        <div class="stat-card" style="border-left: 5px solid var(--primary);">
            <div class="stat-info" style="flex: 1;">
                <h4>Stok Menipis</h4>
                <p>{{ $bahanMenipis->count() }}</p>
            </div>
        </div>
    </div>

    @if($bahanMenipis->count() > 0)
    <div class="card mb-4" style="border-color: #dc3545; border-left: 5px solid var(--primary);">
        <div class="card-header" style="background: #f8d7da; border-bottom-color: #dc3545;">
            <h3 style="color: #721c24;">Perhatian: Stok Menipis!</h3>
        </div>
    <div class="card-body" style="padding:0">
        <table>
            <thead style="background: #f8d7da;">
                <tr>
                    <th style="color: #721c24;">Nama Bahan</th>
                    <th style="color: #721c24;">Stok Saat Ini</th>
                    <th style="color: #721c24;">Minimum</th>
                    <th style="color: #721c24;">Satuan</th>
                    <th style="color: #721c24;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bahanMenipis as $b)
                <tr style="background: #fff5f5;">
                    <td><strong>{{ $b->nama_bahan }}</strong></td>
                    <td><span class="badge" style="background: #f5c6cb; color: #721c24;">{{ $b->stok }}</span></td>
                    <td>{{ $b->stok_minimum }}</td>
                    <td>{{ $b->satuan }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" data-open-modal="modal-{{ $b->id }}">
                            <i class="fas fa-plus"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="card" style="border-left: 5px solid var(--primary);">
    <div class="card-header">
        <h3>Log Stok Terbaru</h3>
        <a href="{{ route('gudang.riwayat') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body" style="padding:0">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Stok Sebelum</th>
                    <th>Stok Sesudah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentLogs as $log)
                <tr>
                    <td style="font-size: 0.85rem; color: var(--text-light);">{{ $log->created_at->format('d/m/y H:i') }}</td>
                    <td>
                        <span class="badge" style="background: {{ $log->jenis=='masuk' ? '#d4edda' : '#fff3cd' }}; color: {{ $log->jenis=='masuk' ? '#155724' : '#856404' }};">
                            {{ ucfirst($log->jenis) }}
                        </span>
                    </td>
                    <td style="font-weight: 600;">{{ $log->jumlah }}</td>
                    <td>{{ $log->stok_sebelum }}</td>
                    <td style="font-weight: 700;">{{ $log->stok_sesudah }}</td>
                    <td style="font-size: 0.85rem;">{{ $log->keterangan }}</td>
                </tr>
                @endforeach
                @if($recentLogs->isEmpty())
                <tr>
                    <td colspan="6" class="text-center" style="padding: 3rem; color: var(--text-light);">Belum ada riwayat aktivitas stok.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@push('modals')
@foreach($bahanMenipis as $b)
<!-- Modal {{ $b->id }} -->
<div id="modal-{{ $b->id }}" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);backdrop-filter:blur(5px);z-index:999999;align-items:center;justify-content:center;overflow:hidden">
    <div style="background:white;border-radius:20px;padding:2.5rem;max-width:450px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.3);position:relative;max-height:90vh;overflow-y:auto;border-left:5px solid var(--primary)">
        <button type="button" data-close-modal="modal-{{ $b->id }}" style="position:absolute;top:1rem;right:1rem;background:none;border:none;font-size:1.8rem;cursor:pointer;color:#999;z-index:1">&times;</button>
        <h3 style="margin-bottom:1.5rem;font-family:'Playfair Display', serif;color:var(--primary-dark);font-size:1.5rem">Tambah Stok: {{ $b->nama_bahan }}</h3>
        <form method="POST" action="{{ route('gudang.tambah-stok', $b) }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Jumlah ({{ $b->satuan }})</label>
                <input type="number" name="jumlah" class="form-control" step="0.01" min="0.01" required style="padding:1rem;font-size:1rem">
            </div>
            <div class="form-group">
                <label class="form-label">Keterangan (Auto-Generate)</label>
                <input type="text" name="keterangan" class="form-control" value="Update Penambahan Stok {{ $b->nama_bahan }}" readonly style="background:#f8f9fa">
                <small style="display:block; margin-top: 0.75rem; font-size: 0.85rem; color: var(--text-light);">Keterangan otomatis sesuai nama bahan</small>
            </div>
            <div class="d-flex gap-2 mt-5">
                <button class="btn btn-primary" style="padding:0.75rem 2rem">Tambah Stok</button>
                <button type="button" data-close-modal="modal-{{ $b->id }}" class="btn btn-secondary" style="padding:0.75rem 2rem">Batal</button>
            </div>
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

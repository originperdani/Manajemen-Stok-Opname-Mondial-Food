@extends('layouts.admin')
@section('title', 'Dashboard Gudang')
@section('page-title', 'Dashboard Gudang')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Gudang</div>
<li><a href="{{ route('gudang.dashboard') }}" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('gudang.index') }}"><i class="fas fa-boxes"></i> Bahan Baku</a></li>
<li><a href="{{ route('gudang.create') }}"><i class="fas fa-plus"></i> Tambah Bahan</a></li>
<li><a href="{{ route('gudang.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-boxes"></i></div>
        <div class="stat-info">
            <h4>Total Bahan Baku</h4>
            <p>{{ $totalBahan }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon {{ $bahanMenipis->count() > 0 ? 'red' : 'green' }}"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-info">
            <h4>Stok Menipis</h4>
            <p>{{ $bahanMenipis->count() }}</p>
        </div>
    </div>
</div>

@if($bahanMenipis->count() > 0)
<div class="card mb-4" style="border-color: var(--accent); background: var(--bg-warm);">
    <div class="card-header" style="background: var(--secondary); border-bottom-color: var(--accent);">
        <h3 style="color: var(--primary-dark);"><i class="fas fa-triangle-exclamation" style="margin-right: 0.5rem; color: var(--warning);"></i> Perhatian: Stok Menipis!</h3>
    </div>
    <div class="card-body" style="padding:0">
        <table>
            <thead>
                <tr>
                    <th>Nama Bahan</th>
                    <th>Stok Saat Ini</th>
                    <th>Minimum</th>
                    <th>Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bahanMenipis as $b)
                <tr style="background: #FFFDFD;">
                    <td><strong>{{ $b->nama_bahan }}</strong></td>
                    <td><span class="badge badge-danger">{{ $b->stok }}</span></td>
                    <td>{{ $b->stok_minimum }}</td>
                    <td>{{ $b->satuan }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="document.getElementById('modal-{{ $b->id }}').style.display='flex'">
                            <i class="fas fa-plus"></i> Tambah Stok
                        </button>
                        <!-- Modal -->
                        <div id="modal-{{ $b->id }}" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.6);backdrop-filter:blur(5px);z-index:9999;align-items:center;justify-content:center">
                            <div style="background:white;border-radius:24px;padding:2.5rem;max-width:450px;width:90%;box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
                                <h3 style="margin-bottom:1.5rem; font-family: 'Playfair Display', serif;">Tambah Stok: {{ $b->nama_bahan }}</h3>
                                <form method="POST" action="{{ route('gudang.tambah-stok', $b) }}">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label">Jumlah ({{ $b->satuan }})</label>
                                        <input type="number" name="jumlah" class="form-control" step="0.01" min="0.01" required placeholder="0.00">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Keterangan</label>
                                        <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Pembelian dari supplier">
                                    </div>
                                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                                        <button class="btn btn-primary" style="flex: 1;">Simpan Perubahan</button>
                                        <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="this.closest('[id^=modal]').style.display='none'">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-history" style="color: var(--accent); margin-right: 0.5rem;"></i> Log Stok Terbaru</h3>
        <a href="{{ route('gudang.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
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
                        <span class="badge badge-{{ $log->jenis=='masuk'?'success':'danger' }}">
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
@endsection

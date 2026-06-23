@extends('layouts.admin')
@section('title', 'Riwayat Stok Bahan Baku')
@section('page-title', 'Riwayat Stok Bahan Baku')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Gudang</div>
<li><a href="{{ route('gudang.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('gudang.index') }}"><i class="fas fa-boxes"></i> Bahan Baku</a></li>
<li><a href="{{ route('gudang.create') }}"><i class="fas fa-plus"></i> Tambah Bahan</a></li>
<li><a href="{{ route('gudang.riwayat') }}" class="active"><i class="fas fa-history"></i> Riwayat Stok</a></li>
<li><a href="{{ route('gudang.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="action-header">
    <form method="GET" action="{{ route('gudang.riwayat') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end; width: 100%;">
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <label class="form-label" style="margin-bottom: 0; font-size: 0.9rem;">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
        </div>
        <input type="hidden" name="sort" value="{{ $sort }}">
        <button type="submit" class="btn btn-primary">
            Filter
        </button>
        <a href="{{ route('gudang.riwayat') }}" class="btn btn-secondary">
            Reset
        </a>
    </form>
</div>

<div class="action-header" style="margin-top: 1rem;">
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ route('gudang.riwayat', ['sort' => 'terbaru', 'tanggal' => $tanggal]) }}" class="btn {{ $sort === 'terbaru' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
            Semua
        </a>
        <a href="{{ route('gudang.riwayat', ['sort' => 'terbaru', 'tanggal' => $tanggal]) }}" class="btn {{ $sort === 'terbaru' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
            Terbaru
        </a>
        <a href="{{ route('gudang.riwayat', ['sort' => 'terlama', 'tanggal' => $tanggal]) }}" class="btn {{ $sort === 'terlama' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
            Terlama
        </a>
    </div>
</div>

<div style="display: flex; flex-direction: column; gap: 2rem;">
    <!-- Tabel Stok Masuk -->
    <div class="card" style="border-left: 5px solid var(--primary);">
        <div class="card-header" style="background: #d4edda; border-bottom: 2px solid #28a745;">
            <h3 style="color: #155724;">Stok Masuk</h3>
        </div>
        <div class="card-body" style="padding:0">
            <table>
                <thead style="background: #d4edda;">
                    <tr>
                        <th style="color: #155724;">Waktu</th>
                        <th style="color: #155724;">Bahan Baku</th>
                        <th style="color: #155724;">Jumlah</th>
                        <th style="color: #155724;">Stok Sesudah</th>
                        <th style="color: #155724;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @if($logsMasuk->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-light);">Belum ada stok masuk.</td>
                    </tr>
                    @else
                    @foreach($logsMasuk as $log)
                    <tr style="background: #f8fff9;">
                        <td style="font-size: 0.8rem; color: var(--text-light);">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td><strong>{{ $log->bahanBaku->nama_bahan ?? '-' }}</strong></td>
                        <td style="font-weight: 600;">{{ $log->jumlah }}</td>
                        <td style="font-weight: 700;">{{ $log->stok_sesudah }}</td>
                        <td style="font-size: 0.85rem;">{{ $log->keterangan ?? '-' }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Stok Keluar -->
    <div class="card" style="border-left: 5px solid var(--primary);">
        <div class="card-header" style="background: #fff3cd; border-bottom: 2px solid #ffc107;">
            <h3 style="color: #856404;">Stok Keluar</h3>
        </div>
        <div class="card-body" style="padding:0">
            <table>
                <thead style="background: #fff3cd;">
                    <tr>
                        <th style="color: #856404;">Waktu</th>
                        <th style="color: #856404;">Bahan Baku</th>
                        <th style="color: #856404;">Jumlah</th>
                        <th style="color: #856404;">Stok Sesudah</th>
                        <th style="color: #856404;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @if($logsKeluar->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-light);">Belum ada stok keluar.</td>
                    </tr>
                    @else
                    @foreach($logsKeluar as $log)
                    <tr style="background: #fffef5;">
                        <td style="font-size: 0.8rem; color: var(--text-light);">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td><strong>{{ $log->bahanBaku->nama_bahan ?? '-' }}</strong></td>
                        <td style="font-weight: 600;">{{ $log->jumlah }}</td>
                        <td style="font-weight: 700;">{{ $log->stok_sesudah }}</td>
                        <td style="font-size: 0.85rem;">{{ $log->keterangan ?? '-' }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

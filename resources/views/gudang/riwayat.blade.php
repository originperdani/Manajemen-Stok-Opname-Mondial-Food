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
<div class="card mb-4" style="border-radius: 16px; border: 1px solid var(--border); background: #fff; border-left: 5px solid var(--primary);">
    <div class="card-body" style="padding: 1.5rem;">
        <form method="GET" action="{{ route('gudang.riwayat') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
            <div class="form-group mb-0">
                <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem;">Periode</label>
                <select name="periode" class="form-control" onchange="this.form.submit()" style="min-width: 150px; padding: 0.5rem 0.75rem;">
                    <option value="harian" {{ $periode == 'harian' ? 'selected' : '' }}>Harian</option>
                    <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="all" {{ $periode == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                    <option value="tahunan" {{ $periode == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>

            @if($periode == 'harian')
                <div class="form-group mb-0">
                    <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem;">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" style="min-width: 180px; padding: 0.5rem 0.75rem;">
                </div>
            @elseif($periode == 'bulanan')
                <div class="form-group mb-0">
                    <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem;">Bulan</label>
                    <select name="bulan" class="form-control" style="min-width: 150px; padding: 0.5rem 0.75rem;">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(2024, $i, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
            @endif

            @if($periode != 'harian')
                <div class="form-group mb-0">
                    <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem;">Tahun</label>
                    <select name="tahun" class="form-control" style="min-width: 120px; padding: 0.5rem 0.75rem;">
                        @for($i = 2024; $i <= date('Y'); $i++)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            @endif

            <input type="hidden" name="sort" value="{{ $sort }}">
            
            <div class="form-group mb-0">
                <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem; visibility: hidden;">Aksi</label>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary" style="background: var(--gradient-gold); border: none; padding: 0.5rem 1rem; font-size: 0.875rem; height: 42px;">
                        Filter
                    </button>
                    <a href="{{ route('gudang.riwayat') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem; height: 42px; display: flex; align-items: center;">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@php
    $sumMasuk = $logsMasuk->sum('jumlah');
    $sumKeluar = $logsKeluar->sum('jumlah');
    $fmtMasuk = floor($sumMasuk) == $sumMasuk ? number_format($sumMasuk, 0, ',', '.') : rtrim(rtrim(number_format($sumMasuk, 2, ',', '.'), '0'), ',');
    $fmtKeluar = floor($sumKeluar) == $sumKeluar ? number_format($sumKeluar, 0, ',', '.') : rtrim(rtrim(number_format($sumKeluar, 2, ',', '.'), '0'), ',');
@endphp
<div class="stats-grid mb-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
    <div class="stat-card" style="border-left: 5px solid var(--primary); background: #fff; padding: 1.5rem; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; align-items: center;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Total Aktivitas Stok</h4>
            <p style="font-size: 1.8rem; font-weight: 700; color: var(--text-dark); margin: 0;">{{ $logs->count() }}</p>
        </div>
    </div>
    <div class="stat-card" style="border-left: 5px solid var(--primary); background: #fff; padding: 1.5rem; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; align-items: center;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Total Bahan Masuk</h4>
            <p style="font-size: 1.8rem; font-weight: 700; color: var(--text-dark); margin: 0;">{{ $fmtMasuk }}</p>
        </div>
    </div>
    <div class="stat-card" style="border-left: 5px solid var(--primary); background: #fff; padding: 1.5rem; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; align-items: center;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Total Bahan Keluar</h4>
            <p style="font-size: 1.8rem; font-weight: 700; color: var(--text-dark); margin: 0;">{{ $fmtKeluar }}</p>
        </div>
    </div>
</div>

<div class="action-header" style="margin-top: 1rem; margin-bottom: 1rem;">
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ route('gudang.riwayat', ['sort' => 'terbaru', 'periode' => $periode, 'tahun' => $tahun, 'bulan' => $bulan, 'tanggal' => $tanggal]) }}" class="btn {{ $sort === 'terbaru' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
            Terbaru
        </a>
        <a href="{{ route('gudang.riwayat', ['sort' => 'terlama', 'periode' => $periode, 'tahun' => $tahun, 'bulan' => $bulan, 'tanggal' => $tanggal]) }}" class="btn {{ $sort === 'terlama' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
            Terlama
        </a>
    </div>
</div>

@php
    $totalMasuk = $logsMasuk->sum('jumlah');
    $totalKeluar = $logsKeluar->sum('jumlah');
    $fmtMasuk = floor($totalMasuk) == $totalMasuk ? number_format($totalMasuk, 0, ',', '.') : number_format($totalMasuk, 2, ',', '.');
    $fmtKeluar = floor($totalKeluar) == $totalKeluar ? number_format($totalKeluar, 0, ',', '.') : number_format($totalKeluar, 2, ',', '.');
@endphp



<div style="display: flex; flex-direction: column; gap: 2rem;">
    <!-- Tabel Stok Masuk -->
    <div data-target-id="stok-masuk" class="card" style="border-left: 5px solid var(--primary); background: #d4edda !important;">
        <div class="card-header" style="background: #d4edda; border-bottom: 2px solid #28a745; border-radius: 0;">
            <h3 style="color: #155724; margin: 0;">Stok Masuk</h3>
        </div>
        <div class="card-body" style="padding:0; background: #ffffff;">
            <table>
                <thead style="background: #f8fff9; border-bottom: 1px solid #c3e6cb;">
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
                <tfoot style="background: #d4edda; border-top: 2px solid #c3e6cb;">
                    <tr>
                        <td colspan="2" style="text-align: right; font-weight: bold; color: #155724;">Total Bahan Masuk:</td>
                        <td style="font-weight: bold; color: #155724; font-size: 1.1rem;">{{ $fmtMasuk }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Tabel Stok Keluar -->
    <div data-target-id="stok-keluar" class="card" style="border-left: 5px solid var(--primary); background: #fff3cd !important;">
        <div class="card-header" style="background: #fff3cd; border-bottom: 2px solid #ffc107; border-radius: 0;">
            <h3 style="color: #856404; margin: 0;">Stok Keluar</h3>
        </div>
        <div class="card-body" style="padding:0; background: #ffffff;">
            <table>
                <thead style="background: #fffef5; border-bottom: 1px solid #ffe8a1;">
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
                <tfoot style="background: #fff3cd; border-top: 2px solid #ffe8a1;">
                    <tr>
                        <td colspan="2" style="text-align: right; font-weight: bold; color: #856404;">Total Bahan Keluar:</td>
                        <td style="font-weight: bold; color: #856404; font-size: 1.1rem;">{{ $fmtKeluar }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash) {
            const hash = window.location.hash.substring(1);
            const target = document.querySelector('[data-target-id="' + hash + '"]');
            if (target) {
                const container = document.querySelector('.content-area');
                if (container) {
                    setTimeout(() => {
                        container.scrollTo({
                            top: target.offsetTop - 20,
                            behavior: 'smooth'
                        });
                    }, 100);
                }
            }
        }
    });
</script>
@endsection

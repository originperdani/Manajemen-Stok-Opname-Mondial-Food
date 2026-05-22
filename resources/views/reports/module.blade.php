@extends('layouts.admin')

@php
    $role = auth()->user()->role;
    if ($viewer === 'owner') {
        $exportRoute = 'owner.reports.' . $module . '.export';
    } else {
        $exportRoute = $module . '.laporan.export';
    }
@endphp

@section('title', $report['title'])

@section('sidebar-menu')
@php $role = auth()->user()->role; @endphp
@if($role === 'owner')
    <div class="sidebar-divider">Menu Utama</div>
    <li><a href="{{ route('owner.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    <li><a href="{{ route('owner.users') }}"><i class="fas fa-users"></i> Kelola User</a></li>
    <li><a href="{{ route('owner.transaksi') }}"><i class="fas fa-receipt"></i> Transaksi</a></li>
    <li><a href="{{ route('owner.reports.index') }}" class="active"><i class="fas fa-chart-bar"></i> Laporan</a></li>
    <div class="sidebar-divider">Monitoring Stok</div>
    <li><a href="{{ route('owner.stok-produk') }}"><i class="fas fa-birthday-cake"></i> Stok Produk</a></li>
    <li><a href="{{ route('owner.stok-bahan') }}"><i class="fas fa-boxes"></i> Stok Bahan Baku</a></li>
@elseif($role === 'admin_gudang')
    <div class="sidebar-divider">Menu Gudang</div>
    <li><a href="{{ route('gudang.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    <li><a href="{{ route('gudang.index') }}"><i class="fas fa-boxes"></i> Bahan Baku</a></li>
    <li><a href="{{ route('gudang.create') }}"><i class="fas fa-plus"></i> Tambah Bahan</a></li>
    <li><a href="{{ route('gudang.laporan') }}" class="active"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@elseif($role === 'admin_produksi')
    <div class="sidebar-divider">Menu Produksi</div>
    <li><a href="{{ route('produksi.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    <li><a href="{{ route('produksi.produk') }}"><i class="fas fa-birthday-cake"></i> Produk</a></li>
    <li><a href="{{ route('produksi.kategori') }}"><i class="fas fa-tags"></i> Kategori</a></li>
    <li><a href="{{ route('produksi.resep') }}"><i class="fas fa-book-open"></i> Resep</a></li>
    <li><a href="{{ route('produksi.input') }}"><i class="fas fa-plus-circle"></i> Input Produksi</a></li>
    <li><a href="{{ route('produksi.laporan') }}" class="active"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@elseif($role === 'admin_penjualan')
    <div class="sidebar-divider">Menu Penjualan</div>
    <li><a href="{{ route('penjualan.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    <li><a href="{{ route('penjualan.pos') }}"><i class="fas fa-cash-register"></i> POS / Kasir</a></li>
    <li><a href="{{ route('penjualan.transaksi') }}"><i class="fas fa-history"></i> Riwayat Transaksi</a></li>
    <li><a href="{{ route('penjualan.laporan') }}" class="active"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endif
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0" style="font-family: 'Playfair Display', serif; font-weight: 800; background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            {{ $report['title'] }}
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route($exportRoute, ['format' => 'pdf', 'periode' => $report['period_type'], 'bulan' => $report['month'], 'tahun' => $report['year']]) }}" target="_blank" class="btn btn-sm" style="background: #ef4444; color: white; border-radius: 12px; padding: 0.6rem 1.25rem;">
                <i class="fas fa-file-pdf fa-sm"></i> Download PDF
            </a>
            <a href="{{ route($exportRoute, ['format' => 'xls', 'periode' => $report['period_type'], 'bulan' => $report['month'], 'tahun' => $report['year']]) }}" class="btn btn-sm" style="background: #10b981; color: white; border-radius: 12px; padding: 0.6rem 1.25rem;">
                <i class="fas fa-file-excel fa-sm"></i> Download Excel
            </a>
        </div>
    </div>

    <!-- Filter Periode -->
    <div class="card shadow-sm mb-5" style="border-radius: 16px; border: 1px solid var(--border); background: #fff;">
        <div class="card-body p-4">
            <form action="" method="GET">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-auto mb-3 mb-lg-0">
                        <div style="color: var(--primary); font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; gap: 0.6rem; padding-right: 1rem;">
                            <i class="fas fa-calendar-alt"></i> Filter Laporan:
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-3 col-lg-2 mb-3 mb-lg-0">
                        <select name="periode" class="form-control" onchange="this.form.submit()" style="border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 0.9rem; height: 45px; box-shadow: none;">
                            <option value="bulanan" {{ $report['period_type'] == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="tahunan" {{ $report['period_type'] == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                        </select>
                    </div>

                    @if($report['period_type'] == 'bulanan')
                        <div class="col-12 col-md-3 col-lg-2 mb-3 mb-lg-0">
                            <select name="bulan" class="form-control" style="border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 0.9rem; height: 45px; box-shadow: none;">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $report['month'] == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create(2024, $i, 1)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    @endif

                    <div class="col-12 col-md-3 col-lg-2 mb-3 mb-lg-0">
                        <select name="tahun" class="form-control" style="border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 0.9rem; height: 45px; box-shadow: none;">
                            @for($i = 2024; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ $report['year'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-12 col-md-auto ml-lg-auto mt-2 mt-lg-0">
                        <button type="submit" class="btn btn-primary" style="height: 45px; border-radius: 10px; padding: 0 2rem; margin: 0; display: flex; align-items: center; gap: 0.75rem; font-weight: 700; width: auto; min-width: 150px; justify-content: center;">
                            <i class="fas fa-search"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="row mb-5">
        @foreach($report['summary'] as $summary)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm" style="border-radius: 12px; border-left: 4px solid var(--primary); border-top: none; border-right: none; border-bottom: none;">
                    <div class="card-body py-3">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--text-light); letter-spacing: 0.05em; font-size: 0.7rem;">
                            {{ $summary['label'] }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold" style="color: var(--text-dark); font-size: 1.1rem;">
                            {{ $summary['value'] }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Sections -->
    @foreach($report['sections'] as $section)
        <div class="card shadow-sm mb-4" style="border-radius: 20px; border: 1px solid var(--border);">
            <div class="card-header py-3 bg-white" style="border-bottom: 1px solid var(--border); border-radius: 20px 20px 0 0;">
                <h6 class="m-0 font-weight-bold" style="color: var(--primary); letter-spacing: 0.05em; text-transform: uppercase; font-size: 0.85rem;">
                    {{ $section['title'] }}
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                @foreach($section['headers'] as $header)
                                    <th>{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($section['rows'] as $row)
                                <tr>
                                    @foreach($row as $cell)
                                        <td>{{ $cell }}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($section['headers']) }}" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3 d-block opacity-20"></i>
                                        Tidak ada data untuk periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

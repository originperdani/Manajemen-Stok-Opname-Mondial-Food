@extends('layouts.admin')

@php
    $role = auth()->user()->role;
    if ($viewer === 'owner') {
        $exportRoute = 'owner.reports.' . $module . '.export';
    } else {
        $exportRoute = $module . '.laporan.export';
    }

    // Build export parameters
    $exportParams = ['format' => '%s', 'periode' => $report['period_type'], 'tahun' => $report['year']];
    if ($report['period_type'] === 'bulanan') {
        $exportParams['bulan'] = $report['month'];
    }
    if ($report['period_type'] === 'harian') {
        $exportParams['tanggal'] = $report['date'];
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
    <li><a href="{{ route('gudang.riwayat') }}"><i class="fas fa-history"></i> Riwayat Stok</a></li>
    <li><a href="{{ route('gudang.laporan') }}" class="active"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@elseif($role === 'admin_produksi')
    <div class="sidebar-divider">Menu Produksi</div>
    <li><a href="{{ route('produksi.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    <li><a href="{{ route('produksi.produk') }}"><i class="fas fa-birthday-cake"></i> Produk</a></li>
    <li><a href="{{ route('produksi.kategori') }}"><i class="fas fa-tags"></i> Kategori</a></li>
    <li><a href="{{ route('produksi.resep') }}"><i class="fas fa-book-open"></i> Resep</a></li>
    <li><a href="{{ route('produksi.input') }}"><i class="fas fa-plus-circle"></i> Input Produksi</a></li>
    <li><a href="{{ route('produksi.riwayat') }}"><i class="fas fa-history"></i> Detail Riwayat Produksi</a></li>
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
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="font-family: 'Raleway', sans-serif; font-weight: 800; background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            {{ $report['title'] }}
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route($exportRoute, array_merge($exportParams, ['format' => 'pdf'])) }}" class="btn btn-sm" style="background: #ef4444; color: white; border-radius: 12px; padding: 0.4rem 1rem;">
                Download PDF
            </a>
            <a href="{{ route($exportRoute, array_merge($exportParams, ['format' => 'xls'])) }}" class="btn btn-sm" style="background: #10b981; color: white; border-radius: 12px; padding: 0.4rem 1rem;">
                Download Excel
            </a>
        </div>
    </div>

    <!-- Filter Periode -->
    <div class="card mb-4" style="border-radius: 16px; border: 1px solid var(--border); background: #fff; border-left: 5px solid var(--primary);">
        <div class="card-header">
            <h3 style="font-size: 1.2rem;">Filter Laporan</h3>
        </div>
        <div class="card-body" style="padding: 1.5rem;">
            <form action="" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
                <div class="form-group mb-0">
                    <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem;">Periode</label>
                    <select name="periode" class="form-control" onchange="this.form.submit()" style="min-width: 150px; padding: 0.5rem 0.75rem;">
                        <option value="harian" {{ $report['period_type'] == 'harian' ? 'selected' : '' }}>Harian</option>
                        <option value="bulanan" {{ $report['period_type'] == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="all" {{ $report['period_type'] == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                        <option value="tahunan" {{ $report['period_type'] == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>

                @if($report['period_type'] == 'harian')
                    <div class="form-group mb-0">
                        <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem;">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ $report['date'] }}" style="min-width: 180px; padding: 0.5rem 0.75rem;">
                    </div>
                @elseif($report['period_type'] == 'bulanan')
                    <div class="form-group mb-0">
                        <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem;">Bulan</label>
                        <select name="bulan" class="form-control" style="min-width: 150px; padding: 0.5rem 0.75rem;">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $report['month'] == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(2024, $i, 1)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                @endif

                @if($report['period_type'] != 'harian')
                    <div class="form-group mb-0">
                        <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem;">Tahun</label>
                        <select name="tahun" class="form-control" style="min-width: 120px; padding: 0.5rem 0.75rem;">
                            @for($i = 2024; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ $report['year'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary" style="background: var(--gradient-gold); border: none; padding: 0.5rem 1rem; font-size: 0.875rem; height: 42px;">
                    Tampilkan
                </button>
            </form>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="stat-grid" style="margin-bottom: 2rem;">
        @foreach($report['summary'] as $summary)
            <div class="stat-card" style="border-left: 5px solid var(--primary);">
                <div class="stat-info" style="flex: 1;">
                    <h4>{{ $summary['label'] }}</h4>
                    <p>{{ $summary['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Sections -->
    @foreach($report['sections'] as $section)
        @if($report['module'] === 'gudang' && isset($section['headers_masuk']))
            <div class="card mb-4" style="border-radius: 16px; border: 1px solid var(--border); border-left: 5px solid var(--primary);">
                <div class="card-header">
                    <h3>{{ $section['title'] }}</h3>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <!-- Stok Masuk -->
                    <div class="mb-4">
                        <h6 class="font-weight-bold mb-3" style="color: #155724;">
                            Stok Masuk
                        </h6>
                        <div class="table-container" style="border-radius: 12px; border: 1px solid var(--border); overflow: hidden;">
                            <table class="table mb-0">
                                <thead style="background: var(--table-header);">
                                    <tr>
                                        @foreach($section['headers_masuk'] as $header)
                                            <th>{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($section['rows_masuk'] as $row)
                                        <tr style="background: #fff;">
                                            @foreach($row as $cell)
                                                <td>{{ $cell }}</td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ count($section['headers_masuk']) }}" class="text-center py-5 text-muted">
                                                Tidak ada stok masuk untuk periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Stok Keluar -->
                    <div>
                        <h6 class="font-weight-bold mb-3" style="color: #856404;">
                            Stok Keluar
                        </h6>
                        <div class="table-container" style="border-radius: 12px; border: 1px solid var(--border); overflow: hidden;">
                            <table class="table mb-0">
                                <thead style="background: var(--table-header);">
                                    <tr>
                                        @foreach($section['headers_keluar'] as $header)
                                            <th>{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($section['rows_keluar'] as $row)
                                        <tr style="background: #fff;">
                                            @foreach($row as $cell)
                                                <td>{{ $cell }}</td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ count($section['headers_keluar']) }}" class="text-center py-5 text-muted">
                                                Tidak ada stok keluar untuk periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card mb-4" style="border-radius: 16px; border: 1px solid var(--border); border-left: 5px solid var(--primary);">
                <div class="card-header">
                    <h3>{{ $section['title'] }}</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table mb-0">
                            <thead style="background: var(--table-header);">
                                <tr>
                                    @foreach($section['headers'] as $header)
                                        <th>{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($section['rows'] as $row)
                                    <tr style="background: #fff;">
                                        @foreach($row as $cell)
                                            <td>{{ $cell }}</td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($section['headers']) }}" class="text-center py-5 text-muted">
                                            Tidak ada data untuk periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                                @if($report['module'] === 'penjualan' && strtoupper($section['title']) === 'PRODUK TERJUAL')
                                    @php
                                        $totalProdukTerjual = 0;
                                        foreach($section['rows'] as $row) {
                                            // Remove any non-numeric characters (like dots used as thousand separators)
                                            $cleanNumber = preg_replace('/[^0-9]/', '', $row[1]);
                                            $totalProdukTerjual += (int) $cleanNumber;
                                        }
                                    @endphp
                                    <tr style="background: #fef3c7; font-weight: bold;">
                                        <td style="border-top: 2px solid #d97706;">Keseluruhan Produk Terjual</td>
                                        <td style="border-top: 2px solid #d97706;">{{ number_format($totalProdukTerjual, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection

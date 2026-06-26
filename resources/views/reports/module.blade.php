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
@section('page-title', $report['title'])

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
    <div class="sidebar-divider">Akses Admin</div>
    <li><a href="{{ route('gudang.dashboard') }}"><i class="fas fa-warehouse"></i> Admin Gudang</a></li>
    <li><a href="{{ route('penjualan.dashboard') }}"><i class="fas fa-cash-register"></i> Admin Penjualan</a></li>
    <li><a href="{{ route('produksi.dashboard') }}"><i class="fas fa-industry"></i> Admin Produksi</a></li>
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
    <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
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

                <div class="form-group mb-0">
                    <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem; visibility: hidden;">Aksi</label>
                    <div style="display: flex;">
                        <button type="submit" class="btn btn-primary" style="background: var(--gradient-gold); border: none; padding: 0.5rem 1rem; font-size: 0.875rem; height: 42px;">
                            Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="stat-grid" style="margin-bottom: 2rem;">
        @foreach($report['summary'] as $summary)
            @php
                $isMenipis = stripos($summary['label'], 'menipis') !== false;
                $borderColor = $isMenipis ? '#dc3545' : 'var(--primary)';
                $textColor = $isMenipis ? '#dc3545' : 'inherit';
            @endphp
            @if(isset($summary['link']))
                <a href="{{ $summary['link'] }}" class="stat-card" style="border-left: 5px solid {{ $borderColor }}; text-decoration: none; color: inherit; display: flex; cursor: pointer;">
            @else
                <div class="stat-card" style="border-left: 5px solid {{ $borderColor }};">
            @endif
                <div class="stat-info" style="flex: 1;">
                    <h4 style="color: {{ $textColor }};">{{ $summary['label'] }}</h4>
                    <p style="color: {{ $textColor }};">{{ $summary['value'] }}</p>
                </div>
            @if(isset($summary['link']))
                </a>
            @else
                </div>
            @endif
        @endforeach
    </div>

    <!-- Sections -->
    @foreach($report['sections'] as $section)
        @if($report['module'] === 'gudang' && isset($section['headers_masuk']))
            <div data-target-id="{{ \Illuminate\Support\Str::slug($section['title']) }}" class="card mb-4" style="border-radius: 16px; border: 1px solid var(--border); border-left: 5px solid var(--primary);">
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
            <div data-target-id="{{ \Illuminate\Support\Str::slug($section['title']) }}" class="card mb-4" style="border-radius: 16px; border: 1px solid var(--border); border-left: 5px solid var(--primary);">
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
                                @if($report['module'] === 'penjualan' && strtoupper($section['title']) === 'DETAIL TRANSAKSI')
                                    <tr style="background: #fef3c7; font-weight: bold;">
                                        <td colspan="7" style="border-top: 2px solid #d97706; text-align: right;">Total Pendapatan (Transaksi Selesai)</td>
                                        <td style="border-top: 2px solid #d97706;">Rp {{ number_format($report['total_pendapatan'] ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if($report['module'] === 'gudang' && strtoupper($section['title']) === 'RINGKASAN STOK BAHAN AKHIR PERIODE')
                                    @php
                                        $totalNilai = 0;
                                        foreach($section['rows'] as $row) {
                                            // Row 4 is Nilai Stok (index 4)
                                            if (isset($row[4])) {
                                                $cleanNumber = preg_replace('/[^0-9]/', '', $row[4]);
                                                $totalNilai += (int) $cleanNumber;
                                            }
                                        }
                                    @endphp
                                    <tr style="background: #fdf5e6; font-weight: bold; color: var(--primary);">
                                        <td colspan="4" style="text-align: right; border-top: 2px solid var(--primary); padding-right: 1.5rem;">TOTAL NILAI PERSEDIAAN</td>
                                        <td style="border-top: 2px solid var(--primary);">Rp {{ number_format($totalNilai, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle internal summary card links
        const internalLinks = document.querySelectorAll('a[href^="#"]');
        internalLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const hash = this.getAttribute('href').substring(1);
                const target = document.querySelector('[data-target-id="' + hash + '"]');
                if (target) {
                    const container = document.querySelector('.content-area');
                    if (container) {
                        container.scrollTo({
                            top: target.offsetTop - 20,
                            behavior: 'smooth'
                        });
                        // optionally update url without scrolling native
                        history.pushState(null, null, '#' + hash);
                    }
                }
            });
        });
        
        // Handle page load hash
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

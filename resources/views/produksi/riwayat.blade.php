@extends('layouts.admin')
@section('title', 'Detail Riwayat Produksi')
@section('page-title', 'Detail Riwayat Produksi')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Produksi</div>
<li><a href="{{ route('produksi.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('produksi.produk') }}"><i class="fas fa-birthday-cake"></i> Produk</a></li>
<li><a href="{{ route('produksi.kategori') }}"><i class="fas fa-tags"></i> Kategori</a></li>
<li><a href="{{ route('produksi.resep') }}"><i class="fas fa-book-open"></i> Resep</a></li>
<li><a href="{{ route('produksi.input') }}"><i class="fas fa-plus-circle"></i> Input Produksi</a></li>
<li><a href="{{ route('produksi.riwayat') }}" class="active"><i class="fas fa-history"></i> Detail Riwayat Produksi</a></li>
<li><a href="{{ route('produksi.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')

@php
    $periodeLabel = '';
    if ($periode == 'harian') {
        $periodeLabel = 'Tanggal: ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y');
    } elseif ($periode == 'bulanan') {
        $periodeLabel = 'Bulan: ' . \Carbon\Carbon::create($tahun, $bulan, 1)->translatedFormat('F Y');
    } elseif ($periode == 'tahunan') {
        $periodeLabel = 'Tahun: ' . $tahun;
    } else {
        $periodeLabel = 'Semua Waktu';
    }
@endphp

<div class="card mb-4" style="border-radius: 16px; border: 1px solid var(--border); background: #fff; border-left: 5px solid var(--primary);">
    <div class="card-body" style="padding: 1.5rem; display: flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('produksi.riwayat') }}" style="flex: 1; display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
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

            <div class="form-group mb-0">
                <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem; visibility: hidden;">Aksi</label>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;"><i class="fas fa-filter"></i> Filter</button>
                    @if(request()->has('periode'))
                        <a href="{{ route('produksi.riwayat') }}" class="btn btn-outline-secondary" style="padding: 0.5rem 1rem;"><i class="fas fa-undo"></i> Reset</a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Summary Cards -->
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <div style="min-width: 200px; background: linear-gradient(135deg, #fffaf5 0%, #fff4e7 100%); padding: 1.25rem; border-radius: 12px; border: 1px solid rgba(122, 75, 34, 0.1);">
                <h5 style="color: var(--primary-dark); font-size: 0.9rem; font-weight: 700; margin-bottom: 0.25rem; text-transform: uppercase;">AKTIVITAS PRODUKSI</h5>
                <p style="font-size: 2rem; font-weight: 800; color: var(--text-dark); margin-bottom: 0.5rem; line-height: 1;">{{ number_format($totalAktivitas, 0, ',', '.') }} <span style="font-size: 1rem; font-weight: 600; color: var(--text-light);">kali</span></p>
                <span style="display: inline-block; padding: 0.35rem 0.85rem; background: #fff; border-radius: 100px; font-size: 0.85rem; font-weight: 600; color: var(--primary); border: 1px solid rgba(122, 75, 34, 0.15);">
                    <i class="fas fa-history" style="margin-right: 0.3rem;"></i> {{ $periodeLabel }}
                </span>
            </div>
            
            <div style="min-width: 200px; background: linear-gradient(135deg, #fffaf5 0%, #fff4e7 100%); padding: 1.25rem; border-radius: 12px; border: 1px solid rgba(122, 75, 34, 0.1);">
                <h5 style="color: var(--primary-dark); font-size: 0.9rem; font-weight: 700; margin-bottom: 0.25rem; text-transform: uppercase;">TOTAL PRODUKSI</h5>
                <p style="font-size: 2rem; font-weight: 800; color: var(--text-dark); margin-bottom: 0.5rem; line-height: 1;">{{ number_format($totalProduksi, 0, ',', '.') }} <span style="font-size: 1rem; font-weight: 600; color: var(--text-light);">pcs</span></p>
                <span style="display: inline-block; padding: 0.35rem 0.85rem; background: #fff; border-radius: 100px; font-size: 0.85rem; font-weight: 600; color: var(--primary); border: 1px solid rgba(122, 75, 34, 0.15);">
                    <i class="fas fa-calendar-alt" style="margin-right: 0.3rem;"></i> {{ $periodeLabel }}
                </span>
            </div>
            
            <div style="min-width: 200px; background: linear-gradient(135deg, #fffaf5 0%, #fff4e7 100%); padding: 1.25rem; border-radius: 12px; border: 1px solid rgba(122, 75, 34, 0.1);">
                <h5 style="color: var(--primary-dark); font-size: 0.9rem; font-weight: 700; margin-bottom: 0.25rem; text-transform: uppercase;">PRODUK DIPRODUKSI</h5>
                <p style="font-size: 2rem; font-weight: 800; color: var(--text-dark); margin-bottom: 0.5rem; line-height: 1;">{{ number_format($totalJenisProduk, 0, ',', '.') }} <span style="font-size: 1rem; font-weight: 600; color: var(--text-light);">jenis</span></p>
                <span style="display: inline-block; padding: 0.35rem 0.85rem; background: #fff; border-radius: 100px; font-size: 0.85rem; font-weight: 600; color: var(--primary); border: 1px solid rgba(122, 75, 34, 0.15);">
                    <i class="fas fa-birthday-cake" style="margin-right: 0.3rem;"></i> {{ $periodeLabel }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="card" style="border-left: 5px solid var(--primary);">
    <div class="card-header">
        <h3 style="font-size: 1.2rem;">Riwayat Produksi Lengkap</h3>
    </div>
    <div class="card-body" style="padding:0">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah Produksi</th>
                    <th>Penanggung Jawab</th>
                    <th>Status</th>
                    <th>Tanggal Produksi</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produksi as $p)
                <tr>
                    <td><strong>{{ $p->produk->nama_produk ?? '-' }}</strong></td>
                    <td style="font-weight: 700; color: var(--primary);">{{ $p->jumlah_produksi }}</td>
                    <td style="font-size: 0.95rem;">{{ $p->user->penanggung_jawab ?? '-' }}</td>
                    <td>
                        <span class="badge badge-{{ $p->status=='selesai'?'success':'warning' }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td style="font-size: 0.9rem; color: var(--text-light);">{{ $p->tanggal_produksi->format('d/m/Y') }}</td>
                    <td style="font-size: 0.9rem; color: var(--text-medium);">{{ $p->catatan ?? '-' }}</td>
                </tr>
                @endforeach
                @if($produksi->isEmpty())
                <tr>
                    <td colspan="6" class="text-center" style="padding: 3rem; color: var(--text-light);">Belum ada riwayat aktivitas produksi.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
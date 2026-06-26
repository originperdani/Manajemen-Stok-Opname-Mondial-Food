@extends('layouts.admin')
@section('title', 'Dashboard Owner')
@section('page-title', 'Dashboard Owner')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Utama</div>
<li><a href="{{ route('owner.dashboard') }}" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('owner.users') }}"><i class="fas fa-users"></i> Kelola User</a></li>
<li><a href="{{ route('owner.transaksi') }}"><i class="fas fa-receipt"></i> Transaksi</a></li>
<li><a href="{{ route('owner.reports.index') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
<div class="sidebar-divider">Monitoring Stok</div>
<li><a href="{{ route('owner.stok-produk') }}"><i class="fas fa-birthday-cake"></i> Stok Produk</a></li>
<li><a href="{{ route('owner.stok-bahan') }}"><i class="fas fa-boxes"></i> Stok Bahan Baku</a></li>
@endsection

@section('content')
<div class="stat-grid">
    <a href="{{ route('owner.transaksi', ['periode' => 'harian', 'tanggal' => date('Y-m-d'), 'status' => 'selesai']) }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4>Pendapatan Hari Ini</h4>
            <p>Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
        </div>
    </a>
    <a href="{{ route('owner.transaksi', ['periode' => 'harian', 'tanggal' => date('Y-m-d')]) }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4>Transaksi Hari Ini</h4>
            <p>{{ $transaksiHariIni }}</p>
        </div>
    </a>
    <a href="{{ route('owner.stok-produk') }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4>Total Produk</h4>
            <p>{{ $totalProduk }}</p>
        </div>
    </a>
    <a href="{{ route('owner.users') }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4>Total User</h4>
            <p>{{ $totalUser }}</p>
        </div>
    </a>
    <a href="{{ route('owner.stok-produk', ['filter' => 'menipis']) }}" class="stat-card" style="border-left: 5px solid #dc3545; text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="color: #dc3545;">Produk Stok Menipis</h4>
            <p style="color: #dc3545;">{{ $produkStokMenipis }}</p>
        </div>
    </a>
    <a href="{{ route('owner.stok-bahan', ['filter' => 'menipis']) }}" class="stat-card" style="border-left: 5px solid #dc3545; text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="color: #dc3545;">Bahan Baku Stok Menipis</h4>
            <p style="color: #dc3545;">{{ $bahanStokMenipis }}</p>
        </div>
    </a>
</div>

<div class="grid-2">
    <div class="card" style="border-left: 5px solid var(--primary);">
        <div class="card-header">
            <h3>Penjualan 7 Hari Terakhir</h3>
        </div>
        <div class="card-body">
            <canvas id="salesChart" height="250"></canvas>
        </div>
    </div>
    <div class="card" style="border-left: 5px solid var(--primary);">
        <div class="card-header">
            <h3>Transaksi Terbaru</h3>
            <a href="{{ route('owner.transaksi') }}" class="btn btn-primary btn-sm" style="background: var(--gradient-gold); border: none; color: #fff;">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding:0;max-height:400px;overflow-y:auto">
            <table>
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksiTerbaru as $t)
                    @php
                        $isPickup = $t->pengiriman && in_array($t->pengiriman->metode_kirim, ['ambil_sendiri']);
                        $displayStatus = $t->status;
                        if ($isPickup && $t->status === 'dikirim') {
                            $displayStatus = 'siap diambil';
                        }
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight: 600;">{{ $t->kode_transaksi }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-light);">{{ $t->created_at->format('d M Y, H:i') }}</div>
                        </td>
                        <td style="font-weight: 700; color: var(--primary);">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge badge-{{ match($t->status) { 
                                'selesai'=>'success',
                                'pending'=>'warning',
                                'dibatalkan'=>'danger',
                                'proses'=>'info',
                                'dikirim'=>'info',
                                default=>'secondary' 
                            } }}">
                                {{ ucfirst($displayStatus) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Filter Section - diatas total pendapatan -->
<div class="card mb-4" style="margin-top: 2.5rem; border-left: 5px solid var(--primary);">
    <div class="card-header">
        <h3>Filter Periode</h3>
    </div>
    <div class="card-body" style="padding: 1.5rem;">
        <form method="GET" action="{{ route('owner.dashboard') }}" class="d-flex align-items-end" style="gap: 2rem; flex-wrap: wrap;">
            <div class="form-group mb-0" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Tipe Filter</label>
                <select name="filter_type" class="form-control" onchange="updateFilterOptions(this.value)" style="min-width: 150px; padding: 0.5rem 0.75rem;">
                    <option value="harian" {{ $filterType === 'harian' ? 'selected' : '' }}>Harian</option>
                    <option value="bulanan" {{ $filterType === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="tahunan" {{ $filterType === 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>
            
            <div class="form-group mb-0" id="tanggal-filter" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" style="min-width: 180px; padding: 0.5rem 0.75rem;">
            </div>
            
            <div class="form-group mb-0" id="bulan-filter" style="display: none; margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Bulan</label>
                <select name="bulan" class="form-control" style="min-width: 150px; padding: 0.5rem 0.75rem;">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ now()->parse('2025-'.$m.'-01')->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>
            
            <div class="form-group mb-0" id="tahun-filter" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Tahun</label>
                <select name="tahun" class="form-control" style="min-width: 120px; padding: 0.5rem 0.75rem;">
                    @for($y = date('Y') - 5; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary btn-sm" style="background: var(--gradient-gold); border: none; padding: 0.375rem 0.75rem; font-size: 0.875rem; white-space: nowrap;">
                Terapkan
            </button>
            <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary btn-sm" style="padding: 0.375rem 0.75rem; font-size: 0.875rem; white-space: nowrap;">
                Reset
            </a>
        </form>
    </div>
</div>

<div class="stat-grid" style="margin-top: 2.5rem;">
    <a href="{{ route('owner.transaksi', ['periode' => $filterType, 'tanggal' => $tanggal, 'bulan' => $bulan, 'tahun' => $tahun, 'status' => 'selesai']) }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4>Total Pendapatan <span style="font-size: 0.8rem; color: var(--text-light); display: block;">({{ $periodeLabel }})</span></h4>
            <p>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
    </a>
    <a href="{{ route('owner.transaksi', ['periode' => $filterType, 'tanggal' => $tanggal, 'bulan' => $bulan, 'tahun' => $tahun]) }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4>Total Transaksi <span style="font-size: 0.8rem; color: var(--text-light); display: block;">({{ $periodeLabel }})</span></h4>
            <p>{{ $totalTransaksi }}</p>
        </div>
    </a>
</div>
@endsection

@section('scripts')
<script>
function updateFilterOptions(type) {
    const tanggalFilter = document.getElementById('tanggal-filter');
    const bulanFilter = document.getElementById('bulan-filter');
    const tahunFilter = document.getElementById('tahun-filter');
    
    tanggalFilter.style.display = type === 'harian' ? 'block' : 'none';
    bulanFilter.style.display = type === 'bulanan' ? 'block' : 'none';
    tahunFilter.style.display = (type === 'bulanan' || type === 'tahunan') ? 'block' : 'none';
}

// Initialize on load
document.addEventListener('DOMContentLoaded', () => {
    updateFilterOptions('{{ $filterType }}');
});

new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Pendapatan',
            data: {!! json_encode($chartData) !!},
            borderColor: '#D4A853',
            backgroundColor: 'rgba(212,168,83,0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#8B6914',
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v/1000) + 'K' } } }
    }
});
</script>
@endsection
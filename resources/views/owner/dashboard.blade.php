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
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-info">
            <h4>Pendapatan Hari Ini</h4>
            <p>Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-shopping-cart"></i></div>
        <div class="stat-info">
            <h4>Transaksi Hari Ini</h4>
            <p>{{ $transaksiHariIni }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-birthday-cake"></i></div>
        <div class="stat-info">
            <h4>Total Produk</h4>
            <p>{{ $totalProduk }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h4>Total User</h4>
            <p>{{ $totalUser }}</p>
        </div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-line" style="color: var(--accent); margin-right: 0.5rem;"></i> Penjualan 7 Hari Terakhir</h3>
        </div>
        <div class="card-body">
            <canvas id="salesChart" height="250"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-clock" style="color: var(--accent); margin-right: 0.5rem;"></i> Transaksi Terbaru</h3>
            <a href="{{ route('owner.transaksi') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
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
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="stat-grid" style="margin-top: 2.5rem;">
    <div class="stat-card" style="border-left: 5px solid var(--success);">
        <div class="stat-icon green"><i class="fas fa-coins"></i></div>
        <div class="stat-info">
            <h4>Total Pendapatan</h4>
            <p>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-receipt"></i></div>
        <div class="stat-info">
            <h4>Total Transaksi</h4>
            <p>{{ $totalTransaksi }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon {{ $produkStokMenipis > 0 ? 'red' : 'green' }}"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-info">
            <h4>Produk Stok Menipis</h4>
            <p>{{ $produkStokMenipis }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon {{ $bahanStokMenipis > 0 ? 'red' : 'green' }}"><i class="fas fa-boxes"></i></div>
        <div class="stat-info">
            <h4>Bahan Stok Menipis</h4>
            <p>{{ $bahanStokMenipis }}</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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

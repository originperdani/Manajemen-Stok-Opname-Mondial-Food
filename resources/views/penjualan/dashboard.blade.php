@extends('layouts.admin')
@section('title', 'Dashboard Penjualan')
@section('page-title', 'Dashboard Penjualan')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Penjualan</div>
<li><a href="{{ route('penjualan.dashboard') }}" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('penjualan.pos') }}"><i class="fas fa-cash-register"></i> POS / Kasir</a></li>
<li><a href="{{ route('penjualan.transaksi') }}"><i class="fas fa-history"></i> Riwayat Transaksi</a></li>
<li><a href="{{ route('penjualan.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="stat-grid">
    <a href="{{ route('penjualan.laporan', ['periode' => 'harian', 'tanggal' => date('Y-m-d')]) }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4>Pendapatan Hari Ini</h4>
            <p>Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
        </div>
    </a>
    <a href="{{ route('penjualan.transaksi', ['periode' => 'harian', 'tanggal' => date('Y-m-d')]) }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4>Transaksi Hari Ini</h4>
            <p>{{ $transaksiHariIni }}</p>
        </div>
    </a>
    <a href="{{ route('penjualan.transaksi', ['status' => 'pending']) }}" class="stat-card" style="border-left: 5px solid var(--primary); text-decoration: none; color: inherit; display: flex; cursor: pointer;">
        <div class="stat-info" style="flex: 1;">
            <h4>Pesanan Pending</h4>
            <p>{{ $transaksiPending }}</p>
        </div>
    </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <a href="{{ route('penjualan.pos') }}" class="btn btn-primary btn-lg" style="padding: 1.5rem;">
        <div style="text-align: left;">
            <div style="font-size: 1.1rem;">Buka Kasir POS</div>
            <div style="font-size: 0.75rem; opacity: 0.8; font-weight: 400;">Input penjualan langsung di toko</div>
        </div>
    </a>
    <a href="{{ route('penjualan.transaksi') }}" class="btn btn-secondary btn-lg" style="padding: 1.5rem;">
        <div style="text-align: left;">
            <div style="font-size: 1.1rem;">Kelola Transaksi</div>
            <div style="font-size: 0.75rem; opacity: 0.8; font-weight: 400;">Lihat riwayat dan status pesanan</div>
        </div>
    </a>
</div>

<div class="card" style="border-left: 5px solid var(--primary);">
    <div class="card-header">
        <h3>Transaksi Terbaru</h3>
    </div>
    <div class="card-body" style="padding:0">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tipe</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
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
                    <td><strong>{{ $t->kode_transaksi }}</strong></td>
                    <td>
                        <span class="badge badge-{{ $t->tipe=='pos'?'info':'primary' }}">
                            {{ strtoupper($t->tipe) }}
                        </span>
                    </td>
                    <td style="font-weight:700; color: var(--primary);">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge badge-{{ match($t->status) { 
                            'selesai'=>'success',
                            'pending'=>'warning',
                            'proses'=>'info',
                            'dikirim'=>'info',
                            'belum_bayar'=>'danger',
                            'dibatalkan'=>'danger',
                            default=>'secondary' 
                        } }}">
                            {{ ucfirst(str_replace('_', ' ', $displayStatus)) }}
                        </span>
                    </td>
                    <td style="font-size: 0.85rem; color: var(--text-light);">{{ $t->created_at->format('d/m H:i') }}</td>
                    <td>
                        <a href="{{ route('penjualan.transaksi.detail', $t) }}" class="btn btn-sm btn-secondary">
                            Lihat
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

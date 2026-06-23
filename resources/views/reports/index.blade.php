@extends('layouts.admin')

@section('title', 'Laporan Keseluruhan')
@section('page-title', 'Dashboard Laporan')

@section('sidebar-menu')
<div class="sidebar-divider">Menu Utama</div>
<li><a href="{{ route('owner.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('owner.users') }}"><i class="fas fa-users"></i> Kelola User</a></li>
<li><a href="{{ route('owner.transaksi') }}"><i class="fas fa-receipt"></i> Transaksi</a></li>
<li><a href="{{ route('owner.reports.index') }}" class="active"><i class="fas fa-chart-bar"></i> Laporan</a></li>
<div class="sidebar-divider">Monitoring Stok</div>
<li><a href="{{ route('owner.stok-produk') }}"><i class="fas fa-birthday-cake"></i> Stok Produk</a></li>
<li><a href="{{ route('owner.stok-bahan') }}"><i class="fas fa-boxes"></i> Stok Bahan Baku</a></li>
@endsection

@section('content')
    <h2 style="font-family: 'Raleway', sans-serif; font-weight: 800; margin-bottom: 2rem;">Laporan Keseluruhan</h2>

    <div class="stat-grid">
        <!-- Laporan Gudang -->
        <div class="stat-card" style="border-left: 5px solid var(--primary);">
            <div class="stat-info" style="flex: 1;">
                <h4>Gudang</h4>
                <p>Bahan Baku</p>
                <div style="margin-top: 0.5rem;">
                    <a href="{{ route('owner.reports.gudang') }}" class="btn btn-primary btn-sm" style="background: var(--gradient-gold); border: none;">
                        Buka Laporan
                    </a>
                </div>
            </div>
        </div>

        <!-- Laporan Produksi -->
        <div class="stat-card" style="border-left: 5px solid var(--primary);">
            <div class="stat-info" style="flex: 1;">
                <h4>Produksi</h4>
                <p>Bahan Jadi</p>
                <div style="margin-top: 0.5rem;">
                    <a href="{{ route('owner.reports.produksi') }}" class="btn btn-primary btn-sm" style="background: var(--gradient-gold); border: none;">
                        Buka Laporan
                    </a>
                </div>
            </div>
        </div>

        <!-- Laporan Penjualan -->
        <div class="stat-card" style="border-left: 5px solid var(--primary);">
            <div class="stat-info" style="flex: 1;">
                <h4>Penjualan</h4>
                <p>Transaksi</p>
                <div style="margin-top: 0.5rem;">
                    <a href="{{ route('owner.reports.penjualan') }}" class="btn btn-primary btn-sm" style="background: var(--gradient-gold); border: none;">
                        Buka Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
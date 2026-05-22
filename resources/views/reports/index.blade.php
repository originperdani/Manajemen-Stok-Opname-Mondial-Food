@extends('layouts.admin')

@section('title', 'Laporan Keseluruhan')

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
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Keseluruhan</h1>
    </div>

    <div class="row">
        <!-- Laporan Gudang -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Gudang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Bahan Baku</div>
                            <p class="text-muted small mt-2">Stok dan pergerakan bahan baku.</p>
                            <a href="{{ route('owner.reports.gudang') }}" class="btn btn-primary btn-sm mt-2">Buka Laporan</a>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laporan Produksi -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Produksi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Bahan Jadi</div>
                            <p class="text-muted small mt-2">Hasil produksi dan status pembuatan roti.</p>
                            <a href="{{ route('owner.reports.produksi') }}" class="btn btn-success btn-sm mt-2">Buka Laporan</a>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cookie-bite fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laporan Penjualan -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Penjualan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Transaksi</div>
                            <p class="text-muted small mt-2">Data transaksi online dan kasir/POS.</p>
                            <a href="{{ route('owner.reports.penjualan') }}" class="btn btn-info btn-sm mt-2">Buka Laporan</a>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')
@section('title', isset($user) ? 'Edit User' : 'Tambah User')
@section('page-title', isset($user) ? 'Edit User' : 'Tambah User Baru')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Utama</div>
<li><a href="{{ route('owner.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('owner.users') }}" class="active"><i class="fas fa-users"></i> Kelola User</a></li>
<li><a href="{{ route('owner.transaksi') }}"><i class="fas fa-receipt"></i> Transaksi</a></li>
<li><a href="{{ route('owner.reports.index') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
<div class="sidebar-divider">Monitoring Stok</div>
<li><a href="{{ route('owner.stok-produk') }}"><i class="fas fa-birthday-cake"></i> Stok Produk</a></li>
<li><a href="{{ route('owner.stok-bahan') }}"><i class="fas fa-boxes"></i> Stok Bahan Baku</a></li>
<div class="sidebar-divider">Akses Admin</div>
<li><a href="{{ route('gudang.dashboard') }}"><i class="fas fa-warehouse"></i> Admin Gudang</a></li>
<li><a href="{{ route('penjualan.dashboard') }}"><i class="fas fa-cash-register"></i> Admin Penjualan</a></li>
<li><a href="{{ route('produksi.dashboard') }}"><i class="fas fa-industry"></i> Admin Produksi</a></li>
@endsection

@section('content')
<div class="card" style="max-width:600px; margin:0 auto; border-left:5px solid var(--primary);">
    <div class="card-header" style="padding:0.75rem 1.25rem;">
        <h3 style="font-size:1rem;">@if(isset($user)) Edit User @else User Baru @endif</h3>
    </div>
    <div class="card-body" style="padding:0.75rem 1.25rem;">
        <form method="POST" action="{{ isset($user) ? route('owner.users.update', $user) : route('owner.users.store') }}">
            @csrf
            @if(isset($user)) @method('PUT') @endif
            <div class="form-group" style="margin-bottom:0.5rem;">
                <label class="form-label" style="margin-bottom:0.25rem;">Nama *</label>
                <input type="text" name="name" class="form-control" style="padding:0.4rem 0.8rem;" value="{{ old('name', $user->name ?? '') }}" required>
            </div>
            <div class="form-group" style="margin-bottom:0.5rem;">
                <label class="form-label" style="margin-bottom:0.25rem;">Email *</label>
                <input type="email" name="email" class="form-control" style="padding:0.4rem 0.8rem;" value="{{ old('email', $user->email ?? '') }}" required>
            </div>
            <div class="form-group" style="margin-bottom:0.5rem;">
                <label class="form-label" style="margin-bottom:0.25rem;">Password {{ isset($user) ? '(kosongkan jika tidak diubah)' : '*' }}</label>
                <input type="password" name="password" class="form-control" style="padding:0.4rem 0.8rem;" {{ isset($user) ? '' : 'required' }}>
            </div>
            <div class="form-group" style="margin-bottom:0.5rem;">
                <label class="form-label" style="margin-bottom:0.25rem;">Role *</label>
                <select name="role" class="form-control" style="padding:0.4rem 0.8rem;" required>
                    @foreach(['owner'=>'Owner','admin_gudang'=>'Admin Gudang','admin_penjualan'=>'Admin Penjualan','admin_produksi'=>'Admin Produksi','customer'=>'Customer'] as $val => $label)
                        <option value="{{ $val }}" {{ old('role', $user->role ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0.5rem;">
                <label class="form-label" style="margin-bottom:0.25rem;">Telepon</label>
                <input type="text" name="phone" class="form-control" style="padding:0.4rem 0.8rem;" value="{{ old('phone', $user->phone ?? '') }}">
            </div>
            <div class="form-group" style="margin-bottom:0.75rem;">
                <label class="form-label" style="margin-bottom:0.25rem;">Alamat</label>
                <textarea name="alamat" class="form-control" style="padding:0.4rem 0.8rem; rows:2;">{{ old('alamat', $user->alamat ?? '') }}</textarea>
            </div>
            <div class="d-flex gap-1 mt-2">
                <button type="submit" class="btn btn-primary" style="padding:0.4rem 0.9rem; font-size:0.9rem;">Simpan</button>
                <a href="{{ route('owner.users') }}" class="btn btn-secondary" style="padding:0.4rem 0.9rem; font-size:0.9rem;">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

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
@endsection

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><h3>{{ isset($user) ? 'Edit User' : '➕ User Baru' }}</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ isset($user) ? route('owner.users.update', $user) : route('owner.users.store') }}">
            @csrf
            @if(isset($user)) @method('PUT') @endif
            <div class="form-group"><label class="form-label">Nama *</label><input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required></div>
            <div class="form-group"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required></div>
            <div class="form-group"><label class="form-label">Password {{ isset($user) ? '(kosongkan jika tidak diubah)' : '*' }}</label><input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}></div>
            <div class="form-group">
                <label class="form-label">Role *</label>
                <select name="role" class="form-control" required>
                    @foreach(['owner'=>'Owner','admin_gudang'=>'Admin Gudang','admin_penjualan'=>'Admin Penjualan','admin_produksi'=>'Admin Produksi','customer'=>'Customer'] as $val => $label)
                        <option value="{{ $val }}" {{ old('role', $user->role ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group"><label class="form-label">Telepon</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}"></div>
            <div class="form-group"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control">{{ old('alamat', $user->alamat ?? '') }}</textarea></div>
            <div class="d-flex gap-1 mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('owner.users') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

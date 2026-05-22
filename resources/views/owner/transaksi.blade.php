@extends('layouts.admin')
@section('title', 'Transaksi')
@section('page-title', 'Semua Transaksi')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Utama</div>
<li><a href="{{ route('owner.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('owner.users') }}"><i class="fas fa-users"></i> Kelola User</a></li>
<li><a href="{{ route('owner.transaksi') }}" class="active"><i class="fas fa-receipt"></i> Transaksi</a></li>
<li><a href="{{ route('owner.reports.index') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
<div class="sidebar-divider">Monitoring Stok</div>
<li><a href="{{ route('owner.stok-produk') }}"><i class="fas fa-birthday-cake"></i> Stok Produk</a></li>
<li><a href="{{ route('owner.stok-bahan') }}"><i class="fas fa-boxes"></i> Stok Bahan Baku</a></li>
@endsection

@section('content')
<form method="GET" class="d-flex gap-1 mb-3">
    <select name="status" class="form-control" style="width:150px" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        @foreach(['pending','diproses','dikirim','selesai','dibatalkan'] as $s)
            <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <input type="date" name="dari" class="form-control" style="width:160px" value="{{ request('dari') }}">
    <input type="date" name="sampai" class="form-control" style="width:160px" value="{{ request('sampai') }}">
    <button class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
</form>

<div class="card">
    <div class="table-responsive"><table>
        <thead><tr><th>Kode</th><th>Customer</th><th>Tipe</th><th>Total</th><th>Pembayaran</th><th>Status</th><th>Tanggal</th></tr></thead>
        <tbody>
            @foreach($transaksi as $t)
            <tr>
                <td><strong>{{ $t->kode_transaksi }}</strong></td>
                <td>{{ $t->user->name ?? $t->nama_pelanggan ?? 'Walk-in' }}</td>
                <td><span class="badge badge-{{ $t->tipe=='pos'?'info':'primary' }}">{{ strtoupper($t->tipe) }}</span></td>
                <td style="font-weight:600">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                <td>{{ $t->pembayaran->metode_label ?? '-' }}</td>
                <td><span class="badge badge-{{ match($t->status) { 'selesai'=>'success','pending'=>'warning','dibatalkan'=>'danger',default=>'info' } }}">{{ ucfirst($t->status) }}</span></td>
                <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table></div>
</div>
{{ $transaksi->withQueryString()->links() }}
@endsection

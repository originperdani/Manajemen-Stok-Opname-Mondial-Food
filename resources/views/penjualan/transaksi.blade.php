@extends('layouts.admin')
@section('title', 'Transaksi')
@section('page-title', 'Riwayat Transaksi')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Penjualan</div>
<li><a href="{{ route('penjualan.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('penjualan.pos') }}"><i class="fas fa-cash-register"></i> POS / Kasir</a></li>
<li><a href="{{ route('penjualan.transaksi') }}" class="active"><i class="fas fa-history"></i> Riwayat Transaksi</a></li>
<li><a href="{{ route('penjualan.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="action-header">
    <form method="GET" class="d-flex gap-1" style="flex: 1; flex-wrap: wrap">
        <input type="text" name="search" class="form-control" placeholder="Cari kode transaksi..." value="{{ request('search') }}" style="width:250px">
        <select name="status" class="form-control" style="width:150px" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach(['pending','diproses','dikirim','selesai','dibatalkan'] as $s)
                <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
    </form>
</div>

<div class="card"><div class="table-responsive"><table>
    <thead><tr><th>Kode</th><th>Customer</th><th>Tipe</th><th>Total</th><th>Bayar</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
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
            <td><a href="{{ route('penjualan.transaksi.detail', $t) }}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table></div></div>
{{ $transaksi->withQueryString()->links() }}
@endsection

@extends('layouts.admin')
@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi: ' . $transaksi->kode_transaksi)
@section('sidebar-menu')
<div class="sidebar-divider">Menu Penjualan</div>
<li><a href="{{ route('penjualan.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('penjualan.pos') }}"><i class="fas fa-cash-register"></i> POS / Kasir</a></li>
<li><a href="{{ route('penjualan.transaksi') }}" class="active"><i class="fas fa-receipt"></i> Transaksi</a></li>
<li><a href="{{ route('penjualan.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="grid-2 mb-3">
    <div class="card"><div class="card-body">
        <h4 style="margin-bottom:0.8rem">📋 Info Transaksi</h4>
        <p><strong>Kode:</strong> {{ $transaksi->kode_transaksi }}</p>
        <p><strong>Tipe:</strong> <span class="badge badge-{{ $transaksi->tipe=='pos'?'info':'primary' }}">{{ strtoupper($transaksi->tipe) }}</span></p>
        <p><strong>Tanggal:</strong> {{ $transaksi->created_at->format('d M Y, H:i') }}</p>
        <p><strong>Customer:</strong> {{ $transaksi->user->name ?? $transaksi->nama_pelanggan ?? 'Walk-in' }}</p>
        <p><strong>Status:</strong> <span class="badge badge-{{ match($transaksi->status) { 'selesai'=>'success','pending'=>'warning','dibatalkan'=>'danger',default=>'info' } }}">{{ ucfirst($transaksi->status) }}</span></p>
    </div></div>
    <div class="card"><div class="card-body">
        <h4 style="margin-bottom:0.8rem">⚙️ Aksi</h4>
        <form method="POST" action="{{ route('penjualan.transaksi.status', $transaksi) }}" class="mb-2">@csrf @method('PUT')
            <div class="d-flex gap-1">
                <select name="status" class="form-control">
                    @foreach(['pending','diproses','dikirim','selesai','dibatalkan'] as $s)
                        <option value="{{ $s }}" {{ $transaksi->status==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
        @if($transaksi->tipe == 'online' && !$transaksi->pengiriman)
        <form method="POST" action="{{ route('penjualan.transaksi.kirim', $transaksi) }}">@csrf
            <div class="d-flex gap-1">
                <select name="metode_kirim" class="form-control">
                    <option value="kurir_toko">Kurir Toko</option>
                    <option value="grabfood">GrabFood</option>
                    <option value="gofood">GoFood</option>
                </select>
                <button class="btn btn-success"><i class="fas fa-truck"></i> Kirim</button>
            </div>
        </form>
        @endif
    </div></div>
</div>

<div class="card mb-3">
    <div class="card-header"><h3>📦 Produk</h3></div>
    <div class="card-body" style="padding:0"><table>
        <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th style="text-align:right">Subtotal</th></tr></thead>
        <tbody>
            @foreach($transaksi->detail as $d)
            <tr><td>{{ $d->produk->nama_produk ?? '-' }}</td><td>Rp {{ number_format($d->harga_satuan,0,',','.') }}</td><td>{{ $d->jumlah }}</td><td style="text-align:right">Rp {{ number_format($d->subtotal,0,',','.') }}</td></tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr><td colspan="3" style="text-align:right"><strong>Subtotal</strong></td><td style="text-align:right">Rp {{ number_format($transaksi->subtotal,0,',','.') }}</td></tr>
            @if($transaksi->ongkir > 0)<tr><td colspan="3" style="text-align:right">Ongkir</td><td style="text-align:right">Rp {{ number_format($transaksi->ongkir,0,',','.') }}</td></tr>@endif
            <tr style="font-size:1.1rem"><td colspan="3" style="text-align:right"><strong>TOTAL</strong></td><td style="text-align:right;color:var(--primary)"><strong>Rp {{ number_format($transaksi->total,0,',','.') }}</strong></td></tr>
        </tfoot>
    </table></div>
</div>

@if($transaksi->pembayaran)
<div class="card mb-3"><div class="card-body">
    <h4>💳 Pembayaran</h4>
    <p><strong>Metode:</strong> {{ $transaksi->pembayaran->metode_label }}</p>
    <p><strong>Jumlah Bayar:</strong> Rp {{ number_format($transaksi->pembayaran->jumlah_bayar,0,',','.') }}</p>
    <p><strong>Kembalian:</strong> Rp {{ number_format($transaksi->pembayaran->kembalian,0,',','.') }}</p>
    <p><strong>Status:</strong> <span class="badge badge-{{ $transaksi->pembayaran->status=='berhasil'?'success':'warning' }}">{{ ucfirst($transaksi->pembayaran->status) }}</span></p>
</div></div>
@endif

@if($transaksi->pengiriman)
<div class="card"><div class="card-body">
    <h4>🚚 Pengiriman</h4>
    <p><strong>Metode:</strong> {{ $transaksi->pengiriman->metode_kirim_label }}</p>
    <p><strong>Status:</strong> <span class="badge badge-{{ match($transaksi->pengiriman->status) { 'diterima'=>'success','dikirim'=>'info',default=>'warning' } }}">{{ ucfirst($transaksi->pengiriman->status) }}</span></p>
    <p><strong>Penerima:</strong> {{ $transaksi->pengiriman->nama_penerima }}</p>
    @if($transaksi->pengiriman->alamat_tujuan)<p><strong>Alamat:</strong> {{ $transaksi->pengiriman->alamat_tujuan }}</p>@endif
</div></div>
@endif
@endsection

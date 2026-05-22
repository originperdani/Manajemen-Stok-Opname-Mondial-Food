@extends('layouts.app')
@section('title', 'Detail Pesanan - Mondial Bakery')
@section('content')
<div class="container" style="margin-top:2rem;margin-bottom:3rem;max-width:900px">
    <a href="{{ route('customer.pesanan') }}" style="color:var(--primary);text-decoration:none;font-size:0.9rem"><i class="fas fa-arrow-left"></i> Kembali ke Pesanan</a>
    <h1 style="margin:1rem 0"><i class="fas fa-receipt" style="color:var(--accent)"></i> {{ $transaksi->kode_transaksi }}</h1>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
        <div class="card"><div class="card-body">
            <h4 style="font-size:0.9rem;color:var(--text-light);margin-bottom:0.5rem">Status Pesanan</h4>
            <span class="badge badge-{{ match($transaksi->status) { 'pending'=>'warning','diproses'=>'info','dikirim'=>'primary','selesai'=>'success','dibatalkan'=>'danger',default=>'primary' } }}" style="font-size:0.9rem;padding:0.5rem 1.2rem">{{ ucfirst($transaksi->status) }}</span>
            <p style="font-size:0.85rem;color:var(--text-light);margin-top:0.5rem">{{ $transaksi->created_at->format('d M Y, H:i') }}</p>
        </div></div>
        <div class="card"><div class="card-body">
            <h4 style="font-size:0.9rem;color:var(--text-light);margin-bottom:0.5rem">Pembayaran</h4>
            @if($transaksi->pembayaran)
                <p style="font-weight:600">{{ $transaksi->pembayaran->metode_label }}</p>
                <span class="badge badge-{{ $transaksi->pembayaran->status === 'berhasil' ? 'success' : 'warning' }}">{{ ucfirst($transaksi->pembayaran->status) }}</span>
            @endif
        </div></div>
    </div>

    @if($transaksi->pengiriman)
        <div class="card mb-3"><div class="card-body">
            <h4 style="margin-bottom:0.8rem"><i class="fas fa-truck"></i> Info Pengiriman</h4>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;font-size:0.9rem">
                <div><strong>Metode:</strong> {{ $transaksi->pengiriman->metode_kirim_label }}</div>
                <div><strong>Status:</strong> <span class="badge badge-{{ match($transaksi->pengiriman->status) { 'diterima'=>'success','dikirim'=>'info',default=>'warning' } }}">{{ ucfirst($transaksi->pengiriman->status) }}</span></div>
                <div><strong>Penerima:</strong> {{ $transaksi->pengiriman->nama_penerima }}</div>
                <div><strong>Telepon:</strong> {{ $transaksi->pengiriman->phone_penerima }}</div>
            </div>
            @if($transaksi->pengiriman->alamat_tujuan)
                <p style="margin-top:0.5rem;font-size:0.9rem"><strong>Alamat:</strong> {{ $transaksi->pengiriman->alamat_tujuan }}</p>
            @endif
        </div></div>
    @endif

    <div class="card mb-3">
        <div class="card-header"><h3>📦 Detail Produk</h3></div>
        <div class="card-body" style="padding:0">
            <table>
                <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th style="text-align:right">Subtotal</th></tr></thead>
                <tbody>
                        @foreach($transaksi->detail as $d)
                            <tr>
                                <td>{{ $d->produk->nama_produk ?? 'Produk dihapus' }}</td>
                                <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                                <td>{{ $d->jumlah }}</td>
                                <td style="text-align:right">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr><td colspan="3" style="text-align:right;font-weight:500">Subtotal</td><td style="text-align:right">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</td></tr>
                        @if($transaksi->ongkir > 0)<tr><td colspan="3" style="text-align:right;font-weight:500">Ongkir</td><td style="text-align:right">Rp {{ number_format($transaksi->ongkir, 0, ',', '.') }}</td></tr>@endif
                        <tr style="font-weight:700;font-size:1.1rem"><td colspan="3" style="text-align:right">Total</td><td style="text-align:right;color:var(--primary)">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

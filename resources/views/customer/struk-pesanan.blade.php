<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pesanan {{ $transaksi->kode_transaksi }}</title>
    <style>
        * {
            margin: 0 !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }
        
        html, body {
            height: auto !important;
            min-height: 0 !important;
            background: #fff !important;
        }
        
        @page {
            margin: 0 !important;
            size: 80mm auto !important;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #1f140d;
            width: 80mm;
            margin: 0 auto;
        }
        
        .receipt {
            width: 72mm;
            margin: 0 auto;
            padding: 8px !important;
        }
        
        .center {
            text-align: center;
        }
        
        .logo {
            max-width: 55px;
            max-height: 40px;
            margin: 0 auto 4px !important;
            display: block;
        }
        
        .store-name {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 3px !important;
        }
        
        .store-info {
            color: #4f3b2d;
            font-size: 7.5px;
            margin: 0 auto !important;
            max-width: 200px;
            line-height: 1.5;
        }
        
        .divider {
            border-top: 1px dashed #8a6a52;
            margin: 6px 0 !important;
            height: 0;
        }
        
        .title {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 5px !important;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        td {
            vertical-align: top;
            padding: 1.5px 0 !important;
            line-height: 1.5;
        }
        
        /* ── Unified meta rows (info pesanan, pengiriman, dll) ── */
        .meta td.lbl {
            width: 80px;
            color: #5f4938;
        }
        
        .meta td.sep {
            width: 6px;
            color: #5f4938;
        }
        
        .meta td.val {
            text-align: left;
            font-weight: 600;
            word-break: break-word;
        }
        
        /* ── Item produk ── */
        .item {
            margin-bottom: 4px !important;
            page-break-inside: avoid;
        }
        
        .item-name {
            font-weight: 700;
            font-size: 10px;
            line-height: 1.5;
        }
        
        .item-detail td {
            color: #5f4938;
            font-size: 9.5px;
        }
        
        .item-detail td:first-child {
            width: 60%;
        }
        
        .item-detail td:last-child {
            color: #1f140d;
            font-weight: 700;
            text-align: right;
        }
        
        /* ── Summary rows (subtotal, diskon, total) ── */
        .summary td.lbl {
            width: 80px;
            color: #5f4938;
        }

        .summary td.sep {
            width: 6px;
            color: #5f4938;
        }

        .summary td.val {
            text-align: right;
            white-space: nowrap;
            font-weight: 600;
        }
        
        .summary .discount td {
            color: #6b4c3a;
        }
        
        .summary .grand-total td {
            border-top: 1px dashed #8a6a52;
            padding-top: 4px !important;
            font-size: 11px;
            font-weight: 800;
        }

        .summary .grand-total td.val {
            color: #1f140d;
        }
        
        /* ── Payment rows ── */
        .payment td.lbl {
            width: 80px;
            color: #5f4938;
        }

        .payment td.sep {
            width: 6px;
            color: #5f4938;
        }

        .payment td.val {
            text-align: right;
            font-weight: 700;
        }
        
        .notes {
            color: #4f3b2d;
            font-size: 8px;
            word-break: break-word;
            margin-top: 4px !important;
            line-height: 1.5;
        }
        
        .footer {
            font-size: 8px;
            color: #4f3b2d;
            line-height: 1.5;
            margin-top: 4px !important;
        }
    </style>
</head>
<body>
@php
    $logoJpgPath = public_path('images/Logo-receipt.jpg');
    $logoPngPath = public_path('images/Logo.png');
    $logoPath = file_exists($logoJpgPath) ? $logoJpgPath : $logoPngPath;
    $logoMime = str_ends_with(strtolower($logoPath), '.jpg') || str_ends_with(strtolower($logoPath), '.jpeg') ? 'image/jpeg' : 'image/png';
    $logoData = file_exists($logoPath) ? 'data:' . $logoMime . ';base64,' . base64_encode(file_get_contents($logoPath)) : null;
    $rupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $tanggalTransaksi = $transaksi->pembayaran?->tanggal_bayar ?: $transaksi->created_at;
    $tanggalStruk = $tanggalTransaksi ? $tanggalTransaksi->format('d/m/Y H:i') : now()->format('d/m/Y H:i');
    $statusPembayaran = match ($transaksi->pembayaran?->status) {
        'berhasil' => 'Berhasil',
        'pending' => 'Menunggu',
        'gagal' => 'Gagal',
        default => '-',
    };
@endphp

<div class="receipt">
    <div class="center">
        @if($logoData)
            <img src="{{ $logoData }}" class="logo" alt="Mondial Bakery">
        @endif
        <div class="store-name">{{ $store['name'] }}</div>
        <div class="store-info">{{ $store['address'] }}</div>
    </div>

    <div class="divider"></div>

    <div class="center title">Struk Pesanan</div>
    <table class="meta">
        <tr>
            <td class="lbl">Tanggal</td>
            <td class="sep">:</td>
            <td class="val">{{ $tanggalStruk }}</td>
        </tr>
        <tr>
            <td class="lbl">No. Pesanan</td>
            <td class="sep">:</td>
            <td class="val">{{ $transaksi->kode_transaksi }}</td>
        </tr>
        <tr>
            <td class="lbl">Jenis Pesanan</td>
            <td class="sep">:</td>
            <td class="val">{{ $transaksi->tipe === 'online' ? 'Online' : 'POS' }}</td>
        </tr>
        <tr>
            <td class="lbl">Pelanggan</td>
            <td class="sep">:</td>
            <td class="val">{{ $transaksi->nama_pelanggan ?: ($transaksi->user->name ?? '-') }}</td>
        </tr>
        @if($transaksi->phone_pelanggan)
            <tr>
                <td class="lbl">Telepon</td>
                <td class="sep">:</td>
                <td class="val">{{ $transaksi->phone_pelanggan }}</td>
            </tr>
        @endif
    </table>

    <div class="divider"></div>

    @foreach($transaksi->detail as $item)
        <div class="item">
            <div class="item-name">{{ $item->produk->nama_produk ?? 'Produk dihapus' }}</div>
            <table class="item-detail">
                <tr>
                    <td>{{ $item->jumlah }} x {{ $rupiah($item->harga_satuan) }}</td>
                    <td>{{ $rupiah($item->subtotal) }}</td>
                </tr>
            </table>
        </div>
    @endforeach

    <div class="divider"></div>

    <table class="summary">
        <tr>
            <td class="lbl">Total Item</td>
            <td class="sep"></td>
            <td class="val">{{ number_format((float) $transaksi->detail->sum('jumlah'), 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="lbl">Subtotal Produk</td>
            <td class="sep"></td>
            <td class="val">{{ $rupiah($transaksi->subtotal) }}</td>
        </tr>
        @if((float) $transaksi->diskon > 0)
            <tr class="discount">
                <td class="lbl">Diskon</td>
                <td class="sep"></td>
                <td class="val">-{{ $rupiah($transaksi->diskon ?? 0) }}</td>
            </tr>
        @endif
        @if((float) $transaksi->ongkir > 0)
            <tr>
                <td class="lbl">Biaya Pengiriman</td>
                <td class="sep"></td>
                <td class="val">{{ $rupiah($transaksi->ongkir) }}</td>
            </tr>
        @endif
        <tr class="grand-total">
            <td class="lbl">Total</td>
            <td class="sep"></td>
            <td class="val">{{ $rupiah($transaksi->total) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="payment">
        <tr>
            <td class="lbl">Metode Bayar</td>
            <td class="sep">:</td>
            <td class="val">{{ $transaksi->pembayaran->metode_label }}</td>
        </tr>
        <tr>
            <td class="lbl">Status Bayar</td>
            <td class="sep">:</td>
            <td class="val">{{ $statusPembayaran }}</td>
        </tr>
        <tr>
            <td class="lbl">Dibayar</td>
            <td class="sep">:</td>
            <td class="val">{{ $rupiah($transaksi->pembayaran->jumlah_bayar) }}</td>
        </tr>
        @if((float) $transaksi->pembayaran->kembalian > 0)
            <tr>
                <td class="lbl">Kembalian</td>
                <td class="sep">:</td>
                <td class="val">{{ $rupiah($transaksi->pembayaran->kembalian) }}</td>
            </tr>
        @endif
    </table>

    @if($transaksi->pengiriman)
        <div class="divider"></div>
        <table class="meta">
            <tr>
                <td class="lbl">Pengiriman</td>
                <td class="sep">:</td>
                <td class="val">{{ $transaksi->pengiriman->metode_kirim_label ?? '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Penerima</td>
                <td class="sep">:</td>
                <td class="val">{{ $transaksi->pengiriman->nama_penerima ?? $transaksi->nama_pelanggan ?? '-' }}</td>
            </tr>
            @if($transaksi->email_pelanggan)
                <tr>
                    <td class="lbl">Email</td>
                    <td class="sep">:</td>
                    <td class="val">{{ $transaksi->email_pelanggan }}</td>
                </tr>
            @endif
        </table>
    @endif

    @if($transaksi->pengiriman?->alamat_tujuan)
        <div class="notes">
            <strong>Alamat:</strong><br>
            {{ $transaksi->pengiriman->alamat_tujuan }}
        </div>
    @endif

    @if($transaksi->catatan)
        <div class="notes">
            <strong>Catatan:</strong><br>
            {{ $transaksi->catatan }}
        </div>
    @endif

    <div class="divider"></div>

    <div class="center footer">
        Terima kasih telah berbelanja di Mondial Bakery.<br>
        Simpan struk ini sebagai bukti pesanan Anda.
    </div>
</div>
</body>
</html>

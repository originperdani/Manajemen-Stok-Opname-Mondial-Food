@extends('layouts.admin')
@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi: ' . $transaksi->kode_transaksi)
@section('sidebar-menu')
<div class="sidebar-divider">Menu Utama</div>
<li><a href="{{ route('owner.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('owner.users') }}"><i class="fas fa-users"></i> Kelola User</a></li>
<li><a href="{{ route('owner.transaksi') }}" class="active"><i class="fas fa-receipt"></i> Transaksi</a></li>
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
<style>
    .detail-card {
        background: linear-gradient(135deg, #FFF9F5 0%, #FFFFFF 100%);
        border-radius: 24px;
        padding: 2rem;
        border: 1px solid rgba(122, 75, 34, 0.1);
        border-left: 5px solid var(--primary);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
    }
    .detail-card h4 {
        color: var(--text-dark);
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .detail-card p {
        margin-bottom: 1.2rem;
        font-size: 1rem;
        line-height: 1.7;
        color: var(--text-secondary);
    }
    .detail-card strong {
        color: var(--text-dark);
        font-weight: 600;
        min-width: 100px;
        display: inline-block;
    }
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .detail-product-card {
        background: linear-gradient(135deg, #FFF9F5 0%, #FFFFFF 100%);
        border-radius: 24px;
        padding: 2rem;
        border: 1px solid rgba(122, 75, 34, 0.1);
        border-left: 5px solid var(--primary);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
    }
    .detail-product-card h3 {
        color: var(--text-dark);
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .product-item {
        padding: 1rem 0;
        border-bottom: 1px dashed rgba(122, 75, 34, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: start;
    }
    .product-item:last-child {
        border-bottom: none;
    }
    .product-info h5 {
        margin: 0;
        color: var(--text-dark);
        font-size: 1.1rem;
        font-weight: 600;
    }
    .product-info small {
        color: var(--text-secondary);
        font-size: 0.95rem;
    }
    .product-subtotal {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary);
    }
    .total-section {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 2px solid var(--primary);
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 1rem;
    }
    .total-row strong {
        color: var(--text-dark);
    }
    .grand-total {
        font-size: 1.4rem;
        font-weight: 800;
        margin-top: 1rem;
    }
    .grand-total .amount {
        color: var(--primary);
    }
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .status-pending { background: rgba(234, 179, 8, 0.15); color: #854d0e; }
    .status-selesai { background: rgba(34, 197, 94, 0.15); color: #166534; }
    .status-dibatalkan { background: rgba(239, 68, 68, 0.15); color: #991b1b; }
    .status-default { background: rgba(122, 75, 34, 0.15); color: var(--primary); }
    .action-form {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .action-form .form-control {
        flex: 1;
        padding: 1rem 1.25rem;
        border-radius: 14px;
        border: 2px solid rgba(122, 75, 34, 0.2);
        font-size: 1rem;
        background: white;
    }
    .action-form .btn {
        padding: 1rem 2rem;
        border-radius: 14px;
        font-weight: 700;
        font-size: 1rem;
        background: linear-gradient(135deg, var(--primary) 0%, #8B6914 100%);
        border: none;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .action-form .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(122, 75, 34, 0.3);
    }
    .action-form .btn:disabled,
    .action-form .form-control:disabled {
        cursor: not-allowed;
        opacity: 0.65;
    }
    .action-note {
        margin-top: -0.75rem;
        margin-bottom: 1.25rem;
        color: var(--text-light);
        font-size: 0.88rem;
        font-weight: 600;
    }
    .receipt-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .receipt-actions .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.9rem 1.25rem;
        border-radius: 14px;
        font-weight: 700;
        text-decoration: none;
    }
    .receipt-actions .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, #8B6914 100%);
        color: white;
    }
    .receipt-actions .btn-secondary {
        background: #f4f4f5;
        color: var(--text-dark);
    }
    .contact-link {
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: color 0.2s ease;
        word-break: break-word;
    }
    .contact-link:hover {
        text-decoration: underline;
    }
    .contact-link.whatsapp { color: #25D366; }
    .contact-link.email { color: #1a73e8; }
    @media (max-width: 900px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="detail-grid">
    <div class="detail-card">
        <h4>Status Pesanan</h4>
        <p>
            <span class="status-badge status-{{ $transaksi->status }}">{{ ucfirst($transaksi->status) }}</span>
        </p>
        <p><strong>Tanggal:</strong> {{ $transaksi->created_at->format('d M Y, H:i') }}</p>
        <p><strong>Kode:</strong> {{ $transaksi->kode_transaksi }}</p>
    </div>

    <div class="detail-card">
        <h4>Pembayaran</h4>
        @if($transaksi->pembayaran)
            <p><strong>Metode:</strong> {{ $transaksi->pembayaran->metode_label }}</p>
            <p><strong>Jumlah Bayar:</strong> Rp {{ number_format($transaksi->pembayaran->jumlah_bayar,0,',','.') }}</p>
            <p><strong>Status:</strong> <span class="status-badge status-{{ $transaksi->pembayaran->status=='berhasil'?'selesai':'pending' }}">{{ ucfirst($transaksi->pembayaran->status) }}</span></p>
        @else
            <p style="color: var(--text-secondary);"><em>Belum ada pembayaran</em></p>
        @endif
    </div>

    <div class="detail-card">
        <h4>Info Pengiriman</h4>
        <p><strong>Metode:</strong> {{ $transaksi->pengiriman->metode_kirim_label ?? ($transaksi->tipe == 'pos' ? 'Ambil di Toko' : 'Belum ditentukan') }}</p>
        @if($transaksi->pengiriman)
                            @php
                                $isPickup = in_array($transaksi->pengiriman->metode_kirim, ['ambil_sendiri']);
                                $pengirimanStatus = $transaksi->pengiriman->status;
                                $displayStatus = $pengirimanStatus;
                                if ($isPickup && $pengirimanStatus === 'dikirim') {
                                    $displayStatus = 'siap_diambil';
                                }
                                $statusBadgeClass = match($displayStatus) {
                                    'siap_diambil' => 'status-default',
                                    'diterima' => 'status-selesai',
                                    'dikirim' => 'status-default',
                                    default => 'status-pending'
                                };
                                $statusText = match($displayStatus) {
                                    'siap_diambil' => 'Siap Diambil',
                                    'diterima' => 'Diterima',
                                    'dikirim' => 'Dikirim',
                                    default => ucfirst($displayStatus)
                                };
                            @endphp
                            <p><strong>Status:</strong> <span class="status-badge {{ $statusBadgeClass }}">{{ $statusText }}</span></p>
                            <p><strong>Penerima:</strong> {{ $transaksi->pengiriman->nama_penerima }}</p>
        @php
            $phone = $transaksi->pengiriman->phone_penerima ?? $transaksi->phone_pelanggan;
            if ($phone) {
                // Normalize phone number
                $whatsappPhone = preg_replace('/[^0-9]/', '', $phone);
                if (substr($whatsappPhone, 0, 1) === '0') {
                    $whatsappPhone = '62' . substr($whatsappPhone, 1);
                }
                
                // Create product list string
                $productList = '';
                foreach ($transaksi->detail as $item) {
                    $productList .= "- " . ($item->produk->nama_produk ?? 'Produk') . " x " . $item->jumlah . "\n";
                }
                
                // Status-specific messages
                $isPickup = $transaksi->pengiriman && in_array($transaksi->pengiriman->metode_kirim, ['ambil_sendiri']);
                $emailPart = $transaksi->email_pelanggan ? "\nEmail: " . $transaksi->email_pelanggan : '';
                $statusMessages = [
                    'pending' => "Halo " . ($transaksi->pengiriman->nama_penerima ?? $transaksi->nama_pelanggan) . "!\n\nKami ingin menginformasikan bahwa pesanan Anda dengan kode " . $transaksi->kode_transaksi . " telah kami terima dan sedang menunggu konfirmasi.\n\nDetail Pesanan:\n" . $productList . $emailPart . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.') . "\n\nTerima kasih telah berbelanja di Mondial Bakery!",
                    'diproses' => "Halo " . ($transaksi->pengiriman->nama_penerima ?? $transaksi->nama_pelanggan) . "!\n\nKabar baik! Pesanan Anda dengan kode " . $transaksi->kode_transaksi . " sedang kami proses.\n\nDetail Pesanan:\n" . $productList . $emailPart . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.') . "\n\n" . ($isPickup ? "Kami akan memberitahu Anda segera setelah pesanan siap diambil di toko." : "Kami akan memberitahu Anda segera setelah pesanan siap dikirim atau diambil."),
                    'dikirim' => $isPickup 
                        ? "Halo " . ($transaksi->pengiriman->nama_penerima ?? $transaksi->nama_pelanggan) . "!\n\nKabar baik! Pesanan Anda dengan kode " . $transaksi->kode_transaksi . " sudah SIAP DIAMBIL di toko kami!\n\nDetail Pesanan:\n" . $productList . $emailPart . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.') . "\n\nSilakan datang ke toko untuk mengambil pesanan Anda. Terima kasih!"
                        : "Halo " . ($transaksi->pengiriman->nama_penerima ?? $transaksi->nama_pelanggan) . "!\n\nPesanan Anda dengan kode " . $transaksi->kode_transaksi . " telah dikirim!\n\nDetail Pesanan:\n" . $productList . $emailPart . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.') . "\n\nMetode Pengiriman: " . ($transaksi->pengiriman->metode_kirim_label ?? 'Belum ditentukan') . "\n\nSilakan tunggu kurir kami. Terima kasih!",
                    'selesai' => "Halo " . ($transaksi->pengiriman->nama_penerima ?? $transaksi->nama_pelanggan) . "!\n\nTerima kasih! Pesanan Anda dengan kode " . $transaksi->kode_transaksi . " telah " . ($isPickup ? "diambil dan selesai" : "selesai") . ".\n\nDetail Pesanan:\n" . $productList . $emailPart . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.') . "\n\nKami harap Anda puas dengan produk kami. Sampai jumpa di pesanan berikutnya! 💕",
                    'dibatalkan' => "Halo " . ($transaksi->pengiriman->nama_penerima ?? $transaksi->nama_pelanggan) . ".\n\nMohon maaf, pesanan Anda dengan kode " . $transaksi->kode_transaksi . " telah dibatalkan.\n\nDetail Pesanan:\n" . $productList . $emailPart . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.') . "\n\nJika Anda memiliki pertanyaan, silakan hubungi kami kembali.",
                ];
                
                $selectedMessage = $statusMessages[$transaksi->status] ?? $statusMessages['pending'];
                $whatsappUrl = "https://wa.me/" . $whatsappPhone . "?text=" . urlencode($selectedMessage);
            }
        @endphp
        @if($phone)
            <p><strong>Telepon:</strong> <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="contact-link whatsapp">
                <i class="fab fa-whatsapp"></i> {{ $phone }}
            </a></p>
        @endif
        @if($transaksi->email_pelanggan)
            @php
                $emailClean = trim($transaksi->email_pelanggan);
                $emailProductList = '';
                foreach ($transaksi->detail as $item) {
                    $emailProductList .= "- " . ($item->produk->nama_produk ?? 'Produk') . " x " . $item->jumlah . "\n";
                }

                $emailRecipientName = $transaksi->pengiriman->nama_penerima ?? $transaksi->nama_pelanggan ?? $transaksi->user->name ?? 'Customer';
                $emailIsPickup = $transaksi->pengiriman && in_array($transaksi->pengiriman->metode_kirim, ['ambil_sendiri']);
                $emailOrderDetail = trim($emailProductList . "Email: " . $emailClean . "\n");
                $emailMessages = [
                    'pending' => "Halo " . $emailRecipientName . "!\n\nKami ingin menginformasikan bahwa pesanan Anda dengan kode " . $transaksi->kode_transaksi . " telah kami terima dan sedang menunggu konfirmasi.\n\nDetail Pesanan:\n" . $emailOrderDetail . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.') . "\n\nTerima kasih telah berbelanja di Mondial Bakery!",
                    'diproses' => "Halo " . $emailRecipientName . "!\n\nKabar baik! Pesanan Anda dengan kode " . $transaksi->kode_transaksi . " sedang kami proses.\n\nDetail Pesanan:\n" . $emailOrderDetail . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.') . "\n\n" . ($emailIsPickup ? "Kami akan memberitahu Anda segera setelah pesanan siap diambil di toko." : "Kami akan memberitahu Anda segera setelah pesanan siap dikirim atau diambil."),
                    'dikirim' => $emailIsPickup
                        ? "Halo " . $emailRecipientName . "!\n\nKabar baik! Pesanan Anda dengan kode " . $transaksi->kode_transaksi . " sudah SIAP DIAMBIL di toko kami!\n\nDetail Pesanan:\n" . $emailOrderDetail . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.') . "\n\nSilakan datang ke toko untuk mengambil pesanan Anda. Terima kasih!"
                        : "Halo " . $emailRecipientName . "!\n\nPesanan Anda dengan kode " . $transaksi->kode_transaksi . " telah dikirim!\n\nDetail Pesanan:\n" . $emailOrderDetail . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.') . "\n\nMetode Pengiriman: " . ($transaksi->pengiriman->metode_kirim_label ?? 'Belum ditentukan') . "\n\nSilakan tunggu kurir kami. Terima kasih!",
                    'selesai' => "Halo " . $emailRecipientName . "!\n\nTerima kasih! Pesanan Anda dengan kode " . $transaksi->kode_transaksi . " telah " . ($emailIsPickup ? "diambil dan selesai" : "selesai") . ".\n\nDetail Pesanan:\n" . $emailOrderDetail . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.') . "\n\nKami harap Anda puas dengan produk kami. Sampai jumpa di pesanan berikutnya!",
                    'dibatalkan' => "Halo " . $emailRecipientName . ".\n\nMohon maaf, pesanan Anda dengan kode " . $transaksi->kode_transaksi . " telah dibatalkan.\n\nDetail Pesanan:\n" . $emailOrderDetail . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.') . "\n\nJika Anda memiliki pertanyaan, silakan hubungi kami kembali.",
                ];
                $emailMessage = $emailMessages[$transaksi->status] ?? $emailMessages['pending'];
                $emailUrl = "https://mail.google.com/mail/?" . http_build_query([
                    'authuser' => config('mail.from.address'),
                    'view' => 'cm',
                    'fs' => '1',
                    'to' => $emailClean,
                    'su' => "Update Pesanan " . $transaksi->kode_transaksi . " - Mondial Bakery",
                    'body' => $emailMessage,
                ], '', '&', PHP_QUERY_RFC3986);
            @endphp
            <p><strong>Email:</strong> <a href="{{ $emailUrl }}" target="_blank" rel="noopener noreferrer" class="contact-link email" title="Kirim email ke {{ $emailClean }}">
                <i class="fas fa-envelope"></i> {{ $emailClean }}
            </a></p>
        @endif
                            @if($transaksi->pengiriman->alamat_tujuan)<p><strong>Alamat:</strong> {{ $transaksi->pengiriman->alamat_tujuan }}</p>@endif
                            @if($transaksi->catatan)<p><strong>Catatan Pesanan:</strong> {{ $transaksi->catatan }}</p>@endif
                            @endif
        <p><strong>Customer:</strong> {{ $transaksi->user->name ?? $transaksi->nama_pelanggan ?? 'Walk-in' }}</p>
    </div>

    <div class="detail-product-card" style="padding: 1.5rem;">
            <h4>Aksi</h4>
            @if($transaksi->pembayaran)
                <div class="receipt-actions">
                    <a href="{{ route('owner.transaksi.struk', $transaksi) }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                        <i class="fas fa-receipt"></i> Lihat Struk
                    </a>
                    <a href="{{ route('owner.transaksi.struk.download', $transaksi) }}" class="btn btn-secondary">
                        <i class="fas fa-download"></i> Download Struk PDF
                    </a>
                </div>
            @endif
        </div>
</div>

<div class="detail-product-card">
    <h3>Detail Produk</h3>
    @foreach($transaksi->detail as $d)
    <div class="product-item">
        <div class="product-info">
            <h5>{{ $d->produk->nama_produk ?? '-' }}</h5>
            <small>Rp {{ number_format($d->harga_satuan,0,',','.') }} × {{ $d->jumlah }}</small>
        </div>
        <div class="product-subtotal">
            Rp {{ number_format($d->subtotal,0,',','.') }}
        </div>
    </div>
    @endforeach

    <div class="total-section">
        <div class="total-row">
            <strong>Subtotal</strong>
            <span>Rp {{ number_format($transaksi->subtotal,0,',','.') }}</span>
        </div>
        @if($transaksi->ongkir > 0)
        <div class="total-row">
            <span>Ongkir</span>
            <span>Rp {{ number_format($transaksi->ongkir,0,',','.') }}</span>
        </div>
        @endif
        <div class="total-row grand-total">
            <strong>Total</strong>
            <span class="amount">Rp {{ number_format($transaksi->total,0,',','.') }}</span>
        </div>
    </div>
</div>

@endsection

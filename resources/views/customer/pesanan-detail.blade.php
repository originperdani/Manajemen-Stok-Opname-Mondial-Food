@extends('layouts.app')
@section('title', 'Detail Pesanan - Mondial Bakery')
@section('styles')
<style>
    /* Pastikan popup Midtrans selalu di atas semua elemen */
    #snap-midtrans {
        z-index: 99999 !important;
    }
    
    .pesanan-detail-header {
        position: relative;
        background: #ffffff;
        padding: 5rem 0;
        width: 100%;
        border-bottom: none;
        text-align: center;
        overflow: hidden;
    }

    .pesanan-detail-header::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: -10px;
        right: -10px;
        background-image: 
            linear-gradient(30deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(150deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(30deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(150deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(60deg, rgba(199, 131, 77, 0.01) 25%, transparent 25.5%, transparent 75%, rgba(199, 131, 77, 0.01) 75%, rgba(199, 131, 77, 0.01)),
            linear-gradient(60deg, rgba(199, 131, 77, 0.01) 25%, transparent 25.5%, transparent 75%, rgba(199, 131, 77, 0.01) 75%, rgba(199, 131, 77, 0.01));
        background-size: 40px 70px;
        background-position: center;
        background-repeat: repeat;
        opacity: 0.8;
        pointer-events: none;
        z-index: 0;
    }

    .pesanan-detail-section {
        position: relative;
        padding: 2rem 0 5rem;
        background: #fff;
        overflow: hidden;
    }

    .pesanan-detail-section::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: -10px;
        right: -10px;
        background-image: 
            linear-gradient(30deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(150deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(60deg, rgba(199, 131, 77, 0.01) 25%, transparent 25.5%, transparent 75%, rgba(199, 131, 77, 0.01) 75%, rgba(199, 131, 77, 0.01));
        background-size: 40px 70px;
        opacity: 0.5;
        z-index: 0;
        pointer-events: none;
    }

    .pesanan-detail-header h1, .pesanan-detail-header p {
        position: relative;
        z-index: 1;
    }

    .pesanan-detail-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        background: linear-gradient(135deg, var(--text-dark) 0%, var(--text-dark) 30%, var(--primary) 50%, var(--accent) 70%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .pesanan-detail-header p {
        color: var(--text-light);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .pesanan-detail-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 1.5rem;
        position: relative;
        z-index: 1;
    }

    .pesanan-detail-container .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -1.75rem;
    }

    .pesanan-detail-container .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 1.75rem;
        margin-bottom: 2rem;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(122, 75, 34, 0.1);
        border-radius: 8px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .back-btn:hover {
        background: var(--primary);
        color: white;
        transform: translateX(-4px);
    }

    .receipt-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .receipt-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-height: 42px;
        padding: 0.65rem 1.1rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.25s ease;
        border: 1px solid rgba(122, 75, 34, 0.16);
    }

    .receipt-btn-primary {
        background: var(--primary);
        color: #fff;
        box-shadow: 0 10px 24px rgba(122, 75, 34, 0.2);
    }

    .receipt-btn-secondary {
        background: #fff;
        color: var(--primary);
    }

    .receipt-btn:hover {
        transform: translateY(-2px);
    }

    .receipt-btn-primary:hover {
        background: var(--primary-dark);
        color: #fff;
    }

    .receipt-btn-secondary:hover {
        background: rgba(122, 75, 34, 0.08);
        color: var(--primary-dark);
    }

    .detail-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: var(--text-dark);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .info-card {
        padding: 1.25rem;
        border-radius: 20px;
        border: 1px solid rgba(122, 75, 34, 0.12);
        background: linear-gradient(135deg, #ffffff 0%, #fffaf5 100%);
        box-shadow: 0 8px 30px rgba(122, 75, 34, 0.1);
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-gold);
    }

    .info-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 80px;
        height: 80px;
        background: radial-gradient(circle, rgba(243, 187, 103, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30px, 30px);
        transition: all 0.4s ease;
    }

    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 40px rgba(122, 75, 34, 0.18);
    }

    .info-card:hover::after {
        transform: translate(0, 0);
        width: 120px;
        height: 120px;
    }

    .info-card-title {
        color: var(--text-light);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
    }

    .info-card-content {
        font-size: 1rem;
        color: var(--text-dark);
        font-weight: 600;
    }

    .status-badge {
        padding: 0.35rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
        display: inline-block;
        width: fit-content;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-diproses {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-dikirim {
        background: #cce5ff;
        color: #004085;
    }

    .status-selesai {
        background: #d4edda;
        color: #155724;
    }

    .status-dibatalkan {
        background: #f8d7da;
        color: #721c24;
    }
    
    .status-belum_bayar {
        background: #dc2626;
        color: white;
        text-align: center;
    }

    .detail-table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 16px;
        overflow: hidden;
    }

    .detail-table thead {
        background: linear-gradient(135deg, rgba(122, 75, 34, 0.95) 0%, rgba(96, 59, 27, 1) 100%);
    }

    .detail-table th {
        padding: 1.25rem;
        text-align: left;
        color: white;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .detail-table tbody tr {
        transition: background 0.3s ease;
    }

    .detail-table tbody tr:hover {
        background: rgba(243, 187, 103, 0.08);
    }

    .detail-table td {
        padding: 1.25rem;
        border-bottom: 1px solid rgba(122, 75, 34, 0.08);
        font-size: 0.95rem;
    }

    .detail-table tfoot td {
        border-bottom: none;
        font-weight: 600;
        font-size: 1rem;
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
        background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(255, 250, 245, 1) 100%);
    }

    .detail-table .total-row {
        font-weight: 800;
        font-size: 1.2rem;
        color: var(--primary);
    }
    
    @media (max-width: 768px) {
        .pesanan-detail-header {
            padding: 3rem 0 2rem;
        }
        
        .pesanan-detail-header h1 {
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }
        
        .pesanan-detail-header p {
            font-size: 0.9rem;
            padding: 0 1rem;
        }
        
        .pesanan-detail-container {
            padding: 0 1rem;
        }
        
        .pesanan-detail-section {
            padding: 1.5rem 0 3rem;
        }
        
        .pesanan-detail-container .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
            padding: 0 1rem;
            margin-bottom: 1.5rem;
        }
        
        .pesanan-detail-container .row {
            margin: 0 -1rem;
        }
        
        .back-btn {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
        }

        .receipt-actions {
            flex-direction: column;
        }

        .receipt-btn {
            width: 100%;
            font-size: 0.85rem;
        }
        
        .detail-title {
            font-size: 1.5rem;
        }
        
        .info-card {
            padding: 1rem;
        }
        
        .info-card-title {
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }
        
        .info-card-content {
            font-size: 0.9rem;
        }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 0.3rem 0.8rem;
        }
        
        .detail-table th,
        .detail-table td {
            padding: 1rem;
            font-size: 0.85rem;
        }
        
        .detail-table .total-row {
            font-size: 1rem;
        }
        
        .detail-produk-item {
            padding: 0.75rem 0 !important;
        }
        
        .detail-produk-name {
            font-size: 0.9rem !important;
        }
        
        .detail-produk-price {
            font-size: 0.75rem !important;
        }
        
        .detail-produk-subtotal {
            font-size: 0.85rem !important;
        }
        
        .detail-produk-summary {
            padding-top: 1rem !important;
        }
        
        .detail-produk-total span {
            font-size: 1rem !important;
        }
        
        .info-pengiriman-content {
            gap: 0.5rem !important;
            font-size: 0.85rem !important;
        }
    }
</style>
@endsection
@section('content')
<div class="pesanan-detail-header">
    <div class="pesanan-detail-container">
        @if($transaksi->pembayaran && $transaksi->pembayaran->status === 'berhasil')
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; background: linear-gradient(135deg, #155724 0%, #155724 30%, #28a745 50%, #4cc9f0 70%, #4cc9f0 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; margin-bottom: 0.5rem;">Pembayaran Berhasil!</h1>
            <p style="color: var(--text-light); font-size: 1.1rem;">Terima kasih sudah berbelanja, pesanan Anda akan segera diproses.</p>
        @else
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; background: linear-gradient(135deg, var(--text-dark) 0%, var(--text-dark) 30%, var(--primary) 50%, var(--accent) 70%, var(--accent) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; margin-bottom: 0.5rem;">Detail Pesanan</h1>
            <p style="color: var(--text-light); font-size: 1.1rem;">{{ $transaksi->kode_transaksi }}</p>
        @endif
    </div>
</div>

<section class="pesanan-detail-section">
    <div class="pesanan-detail-container">
        <a href="{{ $transaksi->pembayaran && $transaksi->pembayaran->status !== 'berhasil' ? route('customer.checkout') : route('customer.pesanan') }}" class="back-btn reveal fade-bottom">
            <i class="fas fa-arrow-left"></i> {{ $transaksi->pembayaran && $transaksi->pembayaran->status !== 'berhasil' ? 'Kembali ke Checkout' : 'Kembali ke Pesanan' }}
        </a>

        @if($transaksi->pembayaran)
            <div class="receipt-actions reveal fade-bottom">
                @if(in_array($transaksi->pembayaran->status, ['belum_bayar', 'pending']) && $transaksi->pembayaran->metode === 'midtrans' && $transaksi->status !== 'dibatalkan')
                    <button id="pay-button" class="receipt-btn receipt-btn-primary">
                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                    </button>
                @endif
                @if($transaksi->pembayaran->status === 'berhasil')
                    <a href="{{ route('customer.pesanan.struk', $transaksi) }}" target="_blank" rel="noopener noreferrer" class="receipt-btn receipt-btn-primary">
                        <i class="fas fa-receipt"></i> Lihat Struk
                    </a>
                    <a href="{{ route('customer.pesanan.struk.download', $transaksi) }}" class="receipt-btn receipt-btn-secondary">
                        <i class="fas fa-download"></i> Download Struk PDF
                    </a>
                @endif
            </div>
        @endif

        <div class="row g-5 mb-5 reveal fade-bottom delay-100">
            <div class="col-md-6">
                <div class="info-card">
                    <h4 class="info-card-title">Status Pesanan</h4>
                    @php
                        $isPickup = $transaksi->pengiriman && in_array($transaksi->pengiriman->metode_kirim, ['ambil_sendiri']);
                        $displayStatus = $transaksi->status;
                        if ($isPickup && $transaksi->status === 'dikirim') {
                            $displayStatus = 'siap diambil';
                        }
                        $statusClass = $isPickup && $displayStatus === 'siap diambil' ? 'dikirim' : $transaksi->status;
                    @endphp
                    <div class="status-badge status-{{ $statusClass }} mb-2">{{ ucfirst($displayStatus) }}</div>
                    <p class="text-muted" style="font-size: 0.85rem; margin: 0;">{{ $transaksi->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-card">
                    <h4 class="info-card-title">Pembayaran</h4>
                    @if($transaksi->pembayaran)
                        <p class="info-card-content mb-1">{{ $transaksi->pembayaran->metode_label }}</p>
                        <span class="status-badge status-{{ $transaksi->pembayaran->status === 'berhasil' ? 'selesai' : ($transaksi->pembayaran->status === 'belum_bayar' ? 'belum_bayar' : 'pending') }}" style="text-align:center">{{ ucfirst(str_replace('_', ' ', $transaksi->pembayaran->status)) }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-5 reveal fade-bottom delay-200">
            <div class="col-md-6">
                <div class="info-card">
                    <h4 class="info-card-title"><i class="fas fa-truck"></i> Info Pengiriman</h4>
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
                                    'dikirim' => 'status-dikirim',
                                    default => 'status-pending'
                                };
                                $statusText = match($displayStatus) {
                                    'siap_diambil' => 'Siap Diambil',
                                    'diterima' => 'Diterima',
                                    'dikirim' => 'Dikirim',
                                    default => ucfirst($displayStatus)
                                };
                            @endphp
                            <div class="info-pengiriman-content" style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.9rem; margin-top: 0.5rem;">
                                <p style="margin: 0;"><strong>Metode:</strong> {{ $transaksi->pengiriman->metode_kirim_label }}</p>
                                <p style="margin: 0;"><strong>Status:</strong> <span class="status-badge {{ $statusBadgeClass }}">{{ $statusText }}</span></p>
                                <p style="margin: 0;"><strong>Penerima:</strong> {{ $transaksi->pengiriman->nama_penerima }}</p>
                                @if($transaksi->catatan)
                                    <p style="margin: 0;"><strong>Catatan Pesanan:</strong> {{ $transaksi->catatan }}</p>
                                @endif
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
                                    
                                    // Status-specific messages for customer
                                    $emailPart = $transaksi->email_pelanggan ? "\nEmail: " . $transaksi->email_pelanggan : '';
                                    $statusMessages = [
                                        'pending' => "Halo admin Mondial Bakery!\n\nSaya ingin menanyakan status pesanan saya dengan kode " . $transaksi->kode_transaksi . ".\n\nDetail Pesanan:\n" . $productList . $emailPart . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.') . "\n\nTerima kasih.",
                                        'diproses' => "Halo admin Mondial Bakery!\n\nTerima kasih sudah memproses pesanan saya dengan kode " . $transaksi->kode_transaksi . ". " . ($isPickup ? "Mohon info ketika pesanan saya siap diambil ya!" : "Mohon info lebih lanjut ya!") . "\n\nDetail Pesanan:\n" . $productList . $emailPart . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.'),
                                        'dikirim' => $isPickup 
                                            ? "Halo admin Mondial Bakery!\n\nTerima kasih! Saya melihat pesanan saya dengan kode " . $transaksi->kode_transaksi . " sudah siap diambil. Saya akan segera ke toko!\n\nDetail Pesanan:\n" . $productList . $emailPart . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.')
                                            : "Halo admin Mondial Bakery!\n\nSaya melihat pesanan saya dengan kode " . $transaksi->kode_transaksi . " sudah dikirim. Bisa infokan estimasi sampai kapan ya?\n\nDetail Pesanan:\n" . $productList . $emailPart . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.'),
                                        'selesai' => "Halo admin Mondial Bakery!\n\nTerima kasih, pesanan saya dengan kode " . $transaksi->kode_transaksi . " sudah " . ($isPickup ? "diambil dan" : "") . " selesai dan saya terima dengan baik! Produknya enak 😊\n\nDetail Pesanan:\n" . $productList . $emailPart . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.'),
                                        'dibatalkan' => "Halo admin Mondial Bakery!\n\nMohon maaf, saya ingin bertanya tentang pesanan saya dengan kode " . $transaksi->kode_transaksi . " yang dibatalkan. Ada yang bisa saya bantu?\n\nDetail Pesanan:\n" . $productList . $emailPart . "\nTotal: Rp " . number_format($transaksi->total, 0, ',', '.'),
                                    ];
                                    
                                    $selectedMessage = $statusMessages[$transaksi->status] ?? $statusMessages['pending'];
                                    $whatsappUrl = "https://wa.me/" . $whatsappPhone . "?text=" . urlencode($selectedMessage);
                                }
                            @endphp
                            @if($phone)
                            <p style="margin: 0;"><strong>Telepon:</strong> <a href="{{ $whatsappUrl }}" target="_blank" style="color: #25D366; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                <i class="fab fa-whatsapp"></i> {{ $phone }}
                            </a></p>
                            @endif
                            @if($transaksi->email_pelanggan)
                            <p style="margin: 0;"><strong>Email:</strong> <a href="mailto:{{ $transaksi->email_pelanggan }}" style="color: #1a73e8; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-envelope"></i> {{ $transaksi->email_pelanggan }}
                            </a></p>
                            @endif
                            @if($transaksi->pengiriman->alamat_tujuan)
                                <p style="margin: 0;"><strong>Alamat:</strong> {{ $transaksi->pengiriman->alamat_tujuan }}</p>
                            @endif
                        </div>
                    @else
                        <p class="text-muted" style="font-size: 0.9rem;">Tidak ada info pengiriman</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-card">
                    <h4 class="info-card-title" style="font-size: 1.1rem; color: var(--text-dark); text-transform: none; letter-spacing: normal;"><i class="fas fa-box-open" style="color: var(--accent); margin-right: 0.5rem;"></i> Detail Produk</h4>
                    <div class="detail-produk-list" style="max-height: 400px; overflow-y: auto;">
                        @foreach($transaksi->detail as $d)
                            <div class="detail-produk-item" style="padding: 1rem 0; border-bottom: 1px dashed rgba(122, 75, 34, 0.15); display: flex; justify-content: space-between; gap: 1rem;">
                                <div>
                                    <p class="detail-produk-name" style="font-weight: 600; color: var(--text-dark); margin: 0;">{{ $d->produk->nama_produk ?? 'Produk dihapus' }}</p>
                                    <p class="detail-produk-price" style="font-size: 0.85rem; color: var(--text-light); margin: 0.25rem 0 0;">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }} x {{ $d->jumlah }}</p>
                                </div>
                                <p class="detail-produk-subtotal" style="font-weight: 700; color: var(--primary); margin: 0; white-space: nowrap;">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                        <div class="detail-produk-summary" style="padding-top: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span style="font-weight: 600;">Subtotal</span>
                                <span style="font-weight: 600;">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if($transaksi->ongkir > 0)
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span style="font-weight: 600;">Ongkir</span>
                                    <span style="font-weight: 600;">Rp {{ number_format($transaksi->ongkir, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="detail-produk-total" style="display: flex; justify-content: space-between; padding-top: 1rem; border-top: 2px solid var(--primary-light); margin-top: 0.5rem;">
                                <span style="font-weight: 800; font-size: 1.2rem; color: var(--text-dark);">Total</span>
                                <span style="font-weight: 800; font-size: 1.2rem; color: var(--primary);">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
@if($transaksi->pembayaran && in_array($transaksi->pembayaran->status, ['belum_bayar', 'pending']) && $transaksi->pembayaran->metode === 'midtrans' && $transaksi->status !== 'dibatalkan')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        // Generate snap token via AJAX
        fetch('{{ route('customer.pesanan.snap-token', $transaksi) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.snap_token) {
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        // Refresh to check updated status
                        location.reload();
                    },
                    onPending: function(result) {
                        location.href = '{{ route('customer.pesanan') }}';
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal!");
                    },
                    onClose: function() {
                        location.href = '{{ route('customer.pesanan') }}';
                    }
                });
            } else {
                alert('Gagal membuat token pembayaran');
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan: ' + (error.error || error.message));
        });
    };
</script>
@endif
@endsection

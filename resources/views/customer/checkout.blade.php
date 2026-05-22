@extends('layouts.app')
@section('title', 'Checkout - Mondial Bakery')
@section('styles')
<style>
    /* ===== PAGE HEADER ===== */
    .checkout-header {
        background: #FDFDFC;
        padding: 4rem 0;
        border-bottom: 1px solid var(--border);
        text-align: center;
        margin-bottom: 4rem;
    }
    .checkout-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.75rem;
        background: linear-gradient(135deg, var(--text-dark) 0%, var(--text-dark) 30%, var(--primary) 50%, var(--accent) 70%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    /* ===== PREMIUM CHECKOUT BUTTON ===== */
    .btn-checkout-premium {
        width: 100%;
        height: 56px;
        background: var(--gradient-gold);
        color: white;
        border: none;
        border-radius: 16px;
        font-family: 'Poppins', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 25px rgba(122, 75, 34, 0.2);
        position: relative;
        overflow: hidden;
        margin-top: 2rem;
    }

    .btn-checkout-premium:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(122, 75, 34, 0.3);
        background: var(--gradient-dark);
        color: white;
    }

    .btn-checkout-premium:active {
        transform: translateY(-1px) scale(0.98);
    }

    .btn-checkout-premium i {
        font-size: 1.2rem;
        transition: transform 0.4s ease;
    }

    .btn-checkout-premium:hover i {
        transform: scale(1.2) rotate(10deg);
    }

    .btn-checkout-premium::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
        transform: rotate(45deg);
        transition: 0.6s;
    }

    .btn-checkout-premium:hover::after {
        left: 100%;
    }
    .checkout-header p {
        color: var(--text-light);
        font-size: 1.1rem;
    }

    /* ===== CHECKOUT GRID ===== */
    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 3rem;
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem 6rem;
    }

    /* ===== SECTION CARDS ===== */
    .checkout-section {
        background: white;
        border-radius: 24px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border);
    }
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 2rem;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .section-title i { color: var(--primary); }

    /* ===== FORM STYLES ===== */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    .form-group-full { grid-column: span 2; }
    
    .checkout-input, .checkout-textarea {
        width: 100%;
        padding: 0.875rem 1.25rem;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        font-family: 'Poppins';
        font-size: 0.95rem;
        transition: all 0.3s;
        background: #F9FAFB;
    }
    .checkout-input:focus, .checkout-textarea:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(139, 105, 20, 0.1);
    }
    .checkout-label {
        display: block;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    /* ===== OPTION CARDS ===== */
    .options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .option-card {
        position: relative;
        cursor: pointer;
    }
    .option-card input {
        position: absolute;
        opacity: 0;
    }
    .option-content {
        padding: 1.25rem;
        border: 1.5px solid var(--border);
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        transition: all 0.3s ease;
        background: #F9FAFB;
    }
    .option-card input:checked + .option-content {
        border-color: var(--primary);
        background: var(--bg-cream);
        box-shadow: 0 4px 15px rgba(139, 105, 20, 0.08);
    }
    .option-title { font-weight: 700; color: var(--text-dark); font-size: 0.95rem; }
    .option-desc { font-size: 0.8rem; color: var(--text-light); }

    /* ===== ORDER SUMMARY ===== */
    .order-summary {
        background: white;
        border-radius: 24px;
        padding: 2.5rem;
        border: 1px solid var(--border);
        position: sticky;
        top: 120px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }
    .order-item {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #F3F4F6;
    }
    .order-item:last-child { border-bottom: none; }
    .item-name { font-weight: 600; font-size: 0.95rem; color: var(--text-dark); margin-bottom: 0.25rem; }
    .item-meta { font-size: 0.85rem; color: var(--text-light); }
    .item-price { font-weight: 700; color: var(--text-dark); }

    .summary-totals {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 2px dashed var(--border);
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        color: var(--text-medium);
    }
    .grand-total {
        margin-top: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .grand-total-label { font-weight: 800; font-size: 1.125rem; color: var(--text-dark); }
    .grand-total-value { font-weight: 800; font-size: 1.75rem; color: var(--primary); }

    @media (max-width: 991px) {
        .checkout-grid { grid-template-columns: 1fr; }
        .order-summary { position: static; }
        .form-grid { grid-template-columns: 1fr; }
        .form-group-full { grid-column: auto; }
        .options-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<header class="checkout-header">
    <div class="container">
        <h1>Checkout</h1>
        <p>Selesaikan pesanan Anda dengan mengisi detail di bawah ini.</p>
    </div>
</header>

<div class="container">
    <form method="POST" action="{{ route('customer.checkout.proses') }}">
        @csrf
        <div class="checkout-grid">
            <main>
                <!-- Data Pemesan -->
                <div class="checkout-section">
                    <h3 class="section-title"><i class="fa-solid fa-user-check"></i> Informasi Pengiriman</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="checkout-label">Nama Lengkap *</label>
                            <input type="text" name="nama_pelanggan" class="checkout-input" value="{{ auth()->user()->name }}" required placeholder="Masukkan nama penerima">
                        </div>
                        <div class="form-group">
                            <label class="checkout-label">No. Telepon / WhatsApp *</label>
                            <input type="text" name="phone_pelanggan" class="checkout-input" value="{{ auth()->user()->phone }}" required placeholder="Contoh: 08123456789">
                        </div>
                        <div class="form-group-full">
                            <label class="checkout-label">Alamat Lengkap Pengiriman *</label>
                            <textarea name="alamat_pengiriman" class="checkout-textarea" rows="3" required placeholder="Nama jalan, nomor rumah, kelurahan, kecamatan, kota">{{ auth()->user()->alamat }}</textarea>
                        </div>
                        <div class="form-group-full">
                            <label class="checkout-label">Catatan Pesanan (Opsional)</label>
                            <textarea name="catatan" class="checkout-textarea" rows="2" placeholder="Contoh: Titip di satpam, jangan terlalu manis, dll"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Metode Pengiriman -->
                <div class="checkout-section">
                    <h3 class="section-title"><i class="fa-solid fa-truck-fast"></i> Metode Pengiriman</h3>
                    <div class="options-grid">
                        @foreach([
                            'ambil_sendiri' => ['🏪', 'Ambil Sendiri', 'Ambil pesanan langsung di toko'],
                            'kurir_toko' => ['🚗', 'Kurir Toko', 'Pengiriman aman oleh kurir kami (+Rp10.000)'],
                            'grabfood' => ['🟢', 'GrabFood', 'Pengiriman via layanan GrabFood (+Rp10.000)'],
                            'gofood' => ['🔴', 'GoFood', 'Pengiriman via layanan GoFood (+Rp10.000)']
                        ] as $val => $info)
                            <label class="option-card">
                                <input type="radio" name="metode_kirim" value="{{ $val }}" {{ $loop->first ? 'checked' : '' }}>
                                <div class="option-content">
                                    <div style="font-size: 1.5rem;">{{ $info[0] }}</div>
                                    <div class="option-title">{{ $info[1] }}</div>
                                    <div class="option-desc">{{ $info[2] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="checkout-section">
                    <h3 class="section-title"><i class="fa-solid fa-wallet"></i> Metode Pembayaran</h3>
                    <div class="options-grid">
                        @foreach([
                            'qris' => ['📱', 'QRIS', 'Scan kode QR untuk pembayaran instan'],
                            'e_wallet' => ['💰', 'E-Wallet', 'OVO, GoPay, Dana, LinkAja'],
                            'm_banking' => ['🏦', 'Transfer Bank', 'BCA, Mandiri, BNI, BRI'],
                            'bayar_ditempat' => ['💵', 'Bayar di Tempat', 'Bayar saat pesanan sampai (COD)']
                        ] as $val => $info)
                            <label class="option-card">
                                <input type="radio" name="metode_bayar" value="{{ $val }}" {{ $loop->first ? 'checked' : '' }}>
                                <div class="option-content">
                                    <div style="font-size: 1.5rem;">{{ $info[0] }}</div>
                                    <div class="option-title">{{ $info[1] }}</div>
                                    <div class="option-desc">{{ $info[2] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </main>

            <aside>
                <div class="order-summary">
                    <h3 class="section-title" style="margin-bottom: 1.5rem;"><i class="fa-solid fa-basket-shopping"></i> Ringkasan Pesanan</h3>
                    
                    <div style="max-height: 300px; overflow-y: auto; padding-right: 0.5rem;">
                        @foreach($items as $item)
                            <div class="order-item">
                                <div>
                                    <div class="item-name">{{ $item->produk->nama_produk }}</div>
                                    <div class="item-meta">{{ $item->jumlah }} x Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</div>
                                </div>
                                <div class="item-price">Rp {{ number_format($item->jumlah * $item->produk->harga, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="summary-totals">
                        <div class="summary-row">
                            <span>Subtotal Produk</span>
                            <span style="font-weight: 600;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Biaya Pengiriman</span>
                            <span style="font-weight: 600;" id="ongkir-display">Rp 0</span>
                        </div>
                        
                        <div class="grand-total">
                            <span class="grand-total-label">Total Pembayaran</span>
                            <span class="grand-total-value" id="total-display">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-checkout-premium">
                        Konfirmasi Pesanan <i class="fa-solid fa-circle-check"></i>
                    </button>
                    
                    <div style="margin-top: 1.5rem; text-align: center; font-size: 0.8rem; color: var(--text-light); line-height: 1.5;">
                        <i class="fa-solid fa-shield-halved"></i> Pembayaran Anda aman dan terenkripsi.
                    </div>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
const subtotal = {{ $total }};
document.querySelectorAll('input[name="metode_kirim"]').forEach(r => {
    r.addEventListener('change', function() {
        const ongkir = this.value === 'ambil_sendiri' ? 0 : 10000;
        document.getElementById('ongkir-display').textContent = 'Rp ' + ongkir.toLocaleString('id-ID');
        document.getElementById('total-display').textContent = 'Rp ' + (subtotal + ongkir).toLocaleString('id-ID');
    });
});
</script>
@endsection

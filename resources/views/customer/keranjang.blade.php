@extends('layouts.app')
@section('title', 'Keranjang - Mondial Bakery')
@section('styles')
<style>
    /* ===== PAGE HEADER ===== */
    .cart-header {
        background: #FDFDFC;
        padding: 4rem 0;
        border-bottom: 1px solid var(--border);
        text-align: center;
        margin-bottom: 4rem;
    }
    .cart-header h1 {
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
    .cart-header p {
        color: var(--text-light);
        font-size: 1.1rem;
    }

    /* ===== CART CONTENT ===== */
    .cart-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 3rem;
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem 6rem;
    }

    /* ===== ITEM CARD ===== */
    .cart-item {
        background: white;
        border-radius: 24px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 2rem;
        transition: all 0.3s ease;
    }
    .cart-item:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border-color: var(--primary-light);
    }
    .item-media {
        width: 100px; height: 100px;
        background: #F9FAFB;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem;
        overflow: hidden;
        flex-shrink: 0;
    }
    .item-media img {
        width: 100%; height: 100%; object-fit: cover;
    }
    .item-info { flex-grow: 1; }
    .item-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
        text-decoration: none;
    }
    .item-title:hover { color: var(--primary); }
    .item-price-unit {
        font-size: 0.875rem;
        color: var(--text-light);
    }
    
    .item-qty {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .qty-input-sm {
        width: 60px; height: 40px;
        text-align: center;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-family: 'Poppins';
        font-weight: 600;
        font-size: 0.95rem;
    }

    .item-total {
        text-align: right;
        min-width: 140px;
    }
    .item-total-price {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary);
    }

    .btn-remove {
        color: #991B1B;
        background: #FEF2F2;
        border: none;
        width: 40px; height: 40px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex; align-items: center; justify-content: center;
    }
    .btn-remove:hover { background: #FEE2E2; transform: scale(1.05); }

    /* ===== SUMMARY CARD ===== */
    .summary-card {
        background: white;
        border-radius: 24px;
        padding: 2.5rem;
        border: 1px solid var(--border);
        position: sticky;
        top: 120px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }
    .summary-card h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 2rem;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        color: var(--text-medium);
        font-size: 0.95rem;
    }
    .summary-total {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .total-label { font-weight: 700; font-size: 1.125rem; color: var(--text-dark); }
    .total-value { font-weight: 800; font-size: 1.5rem; color: var(--primary); }

    /* ===== EMPTY STATE ===== */
    .empty-cart {
        text-align: center;
        padding: 6rem 2rem;
        background: white;
        border-radius: 32px;
        border: 1px solid var(--border);
        max-width: 600px;
        margin: 4rem auto 8rem;
    }
    .empty-icon {
        font-size: 5rem;
        color: var(--border);
        margin-bottom: 2rem;
    }

    @media (max-width: 991px) {
        .cart-grid { grid-template-columns: 1fr; }
        .summary-card { position: static; }
        .cart-item { flex-wrap: wrap; gap: 1rem; }
        .item-total { order: 4; width: 100%; text-align: left; min-width: 0; }
        .btn-remove { position: absolute; top: 1.5rem; right: 1.5rem; }
    }
</style>
@endsection

@section('content')
<header class="cart-header">
    <div class="container">
        <h1>Keranjang Belanja</h1>
        <p>Tinjau item pilihan Anda sebelum melakukan pemesanan.</p>
    </div>
</header>

<div class="container">
    @if($items->count() > 0)
        <div class="cart-grid">
            <main>
                @foreach($items as $item)
                    <div class="cart-item">
                        <div class="item-media">
                            @if($item->produk->gambar)
                                <img src="{{ str_starts_with($item->produk->gambar, 'http') ? $item->produk->gambar : asset('storage/' . $item->produk->gambar) }}" 
                                     alt="{{ $item->produk->nama_produk }}"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="fallback-image" style="display:none; width:100%; height:100%; align-items:center; justify-content:center; font-size:2.5rem; background: var(--bg-alt);">
                                    {{ ['🍞','🍰','🥐','🍪','🎂'][($item->produk->kategori_id - 1) % 5] }}
                                </div>
                            @else
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:2.5rem; background: var(--bg-alt);">
                                    {{ ['🍞','🍰','🥐','🍪','🎂'][($item->produk->kategori_id - 1) % 5] }}
                                </div>
                            @endif
                        </div>
                        <div class="item-info">
                            <a href="{{ route('produk.detail', $item->produk) }}" class="item-title">{{ $item->produk->nama_produk }}</a>
                            <p class="item-price-unit">Rp {{ number_format($item->produk->harga, 0, ',', '.') }} / {{ $item->produk->satuan }}</p>
                        </div>
                        <div class="item-qty">
                            <form method="POST" action="{{ route('customer.keranjang.update', $item) }}">
                                @csrf @method('PUT')
                                <input type="number" name="jumlah" value="{{ $item->jumlah }}" 
                                       min="1" max="{{ $item->produk->stok }}" class="qty-input-sm" 
                                       onchange="this.form.submit()">
                            </form>
                        </div>
                        <div class="item-total">
                            <span class="item-total-price">Rp {{ number_format($item->jumlah * $item->produk->harga, 0, ',', '.') }}</span>
                        </div>
                        <form method="POST" action="{{ route('customer.keranjang.hapus', $item) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-remove" onclick="return confirm('Hapus item dari keranjang?')">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                @endforeach
            </main>

            <aside>
                <div class="summary-card">
                    <h3><i class="fa-solid fa-receipt"></i> Ringkasan Belanja</h3>
                    <div class="summary-row">
                        <span>Total Item</span>
                        <span style="font-weight: 600;">{{ $items->sum('jumlah') }} Item</span>
                    </div>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span style="font-weight: 600;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="summary-total">
                        <span class="total-label">Total Harga</span>
                        <span class="total-value">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <a href="{{ route('customer.checkout') }}" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 2rem;">
                        Proses Checkout <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i>
                    </a>
                    <a href="{{ route('katalog') }}" class="btn btn-secondary" style="width: 100%; margin-top: 1rem; border: none;">
                        Lanjut Belanja
                    </a>
                </div>
            </aside>
        </div>
    @else
        <div class="empty-cart">
            <div class="empty-icon">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <h2 style="font-size: 1.75rem; margin-bottom: 1rem; color: var(--text-dark);">Keranjang Anda Kosong</h2>
            <p style="color: var(--text-light); margin-bottom: 2.5rem;">Sepertinya Anda belum memilih roti atau kue favorit. Yuk, jelajahi katalog kami!</p>
            <a href="{{ route('katalog') }}" class="btn btn-primary btn-lg">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection

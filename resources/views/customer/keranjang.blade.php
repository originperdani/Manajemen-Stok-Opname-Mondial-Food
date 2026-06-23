@extends('layouts.app')
@section('title', 'Keranjang - Mondial Bakery')
@section('styles')
<style>
    /* ===== PAGE HEADER ===== */
    .cart-header {
        background: #FDFDFC;
        padding: 5rem 0;
        border-bottom: 1px solid var(--border);
        text-align: center;
        margin-bottom: 4rem;
    }
    .cart-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
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

    /* ===== CART BUTTONS ===== */
    .cart-btn {
        width: 100%;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
    }

    .cart-btn-primary {
        background: var(--gradient-gold);
        color: white;
        box-shadow: 0 4px 15px rgba(122, 75, 34, 0.2);
        margin-top: 2rem;
    }

    .cart-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(122, 75, 34, 0.3);
        background: var(--gradient-dark);
    }

    .cart-btn-secondary {
        background: var(--bg-alt);
        color: var(--text-dark);
        margin-top: 1rem;
    }

    .cart-btn-secondary:hover {
        background: var(--surface-soft);
        transform: translateY(-2px);
    }

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
        padding: 4rem 2rem;
        background: white;
        border-radius: 32px;
        border: 1px solid var(--border);
        max-width: 600px;
        margin: 4rem auto 8rem;
    }
    .empty-icon {
        font-size: 3.5rem;
        color: var(--border);
        margin-bottom: 1.5rem;
    }
    .empty-cart h2 {
        font-size: 1.5rem;
        margin-bottom: 0.75rem;
        color: var(--text-dark);
    }
    .empty-cart p {
        font-size: 0.95rem;
        color: var(--text-light);
        margin-bottom: 2rem;
    }

    @media (max-width: 1024px) {
        .cart-grid {
            grid-template-columns: 1fr;
            max-width: 820px;
            gap: 2rem;
        }

        .summary-card {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .cart-header {
            padding: 3rem 0 2rem;
            margin-bottom: 2rem;
        }
        
        .cart-header h1 {
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }
        
        .cart-header p {
            font-size: 0.9rem;
            padding: 0 1rem;
        }
        
        .cart-grid {
            grid-template-columns: 1fr;
            padding: 0 1.25rem 3rem;
            gap: 2rem;
        }
        
        .cart-item {
            padding: 1.25rem;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .item-media {
            width: 80px;
            height: 80px;
            font-size: 2.5rem;
        }
        
        .item-title {
            font-size: 1rem;
        }
        
        .summary-card {
            padding: 1.5rem;
            position: static;
        }
        
        .summary-card h3 {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .total-value {
            font-size: 1.25rem;
        }
        
        .empty-cart {
            padding: 2.5rem 1.25rem;
            margin: 2rem auto 4rem;
        }
        
        .empty-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .empty-cart h2 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        
        .empty-cart p {
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }
    }

    @media (max-width: 640px) {
        .cart-item {
            display: grid;
            grid-template-columns: 72px 1fr auto;
            align-items: center;
            gap: 0.75rem;
        }

        .item-media {
            grid-row: span 2;
            width: 72px;
            height: 72px;
            font-size: 2rem;
        }

        .item-info {
            min-width: 0;
        }

        .item-title {
            display: block;
            overflow-wrap: anywhere;
        }

        .item-qty {
            grid-column: 2;
        }

        .item-total {
            grid-column: 2 / 4;
            min-width: 0;
            text-align: left;
        }

        .cart-item > form {
            grid-column: 3;
            grid-row: 1 / span 2;
        }

        .btn-remove {
            width: 36px;
            height: 36px;
        }
    }
</style>
@endsection

@section('content')
<header class="cart-header">
    <div class="container">
        <h1 class="reveal fade-bottom">Keranjang Belanja</h1>
        <p class="reveal fade-bottom delay-100">Tinjau item pilihan Anda sebelum melakukan pemesanan.</p>
    </div>
</header>

<div class="container">
    @if($items->count() > 0)
        <div class="cart-grid">
            <main>
                @foreach($items as $item)
                    <div class="cart-item reveal reveal-slower fade-bottom delay-{{ ($loop->index % 3 + 1) * 100 }}">
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
                <div class="summary-card reveal fade-left">
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
                    
                    <form action="{{ route('customer.checkout') }}" method="GET" style="width: 100%;">
                        <button type="submit" class="cart-btn cart-btn-primary">
                            Proses Checkout <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                    <form action="{{ route('katalog') }}" method="GET" style="width: 100%;">
                        <button type="submit" class="cart-btn cart-btn-secondary">
                            Lanjut Belanja
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    @else
        <div class="empty-cart reveal fade-bottom">
            <div class="empty-icon">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <h2>Keranjang Anda Kosong</h2>
            <p>Sepertinya Anda belum memilih roti atau kue favorit. Yuk, jelajahi katalog kami!</p>
            <form action="{{ route('katalog') }}" method="GET" style="display: inline-block;">
                <button type="submit" class="cart-btn cart-btn-primary" style="display: inline-flex; width: auto; margin-top: 0;">
                    Mulai Belanja
                </button>
            </form>
        </div>
    @endif
</div>
@endsection

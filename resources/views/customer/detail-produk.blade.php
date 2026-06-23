@extends('layouts.app')
@section('title', $produk->nama_produk . ' - Mondial Bakery')
@section('styles')
<style>
    /* ===== DETAIL CONTAINER (MB1 Placement + MB Styles) ===== */
    .detail-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 0 1.5rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        align-items: start;
    }

    /* ===== IMAGE GALLERY ===== */
    .detail-media {
        background: rgba(255,255,255,0.82);
        border-radius: 24px;
        aspect-ratio: 4/3;
        max-width: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 6rem;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.88);
        box-shadow: var(--shadow-md);
        position: relative;
        background-image: linear-gradient(135deg, #fff7ef, #fffdf9);
    }

    .detail-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.45s ease;
    }

    .detail-media:hover img {
        transform: scale(1.05);
    }

    .detail-badge {
        position: absolute;
        top: 1.25rem;
        left: 1.25rem;
        padding: 0.55rem 0.95rem;
        border-radius: 999px;
        background: rgba(31, 20, 13, 0.78);
        color: white;
        font-size: 0.78rem;
        font-weight: 700;
        z-index: 10;
    }

    /* ===== PRODUCT INFO ===== */
    .detail-info { 
        padding: 1rem 0;
        display: grid;
        gap: 1.4rem;
    }

    .detail-cat {
        font-size: 0.875rem;
        color: var(--primary);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 1rem;
        display: block;
    }

    .detail-title {
        font-size: clamp(1.5rem, 3vw, 2rem);
        color: var(--text-dark);
        margin-bottom: 1rem;
        line-height: 1.2;
        font-weight: 700;
        font-family: 'Playfair Display', serif;
    }

    .detail-price {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .detail-desc {
        color: var(--text-medium);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    /* ===== META GRID ===== */
    .detail-meta-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,244,231,0.82));
        border-radius: 16px;
        border: 1px solid rgba(122, 75, 34, 0.08);
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .meta-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .meta-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    /* ===== ACTIONS ===== */
    .action-group {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .qty-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .qty-btn {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        border: 1.5px solid rgba(122, 75, 34, 0.12);
        background: rgba(255,255,255,0.92);
        color: var(--text-dark);
        font-size: 1.25rem;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qty-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--bg-cream);
        transform: translateY(-2px);
    }

    .qty-input {
        width: 70px;
        height: 48px;
        text-align: center;
        border: 1.5px solid rgba(122, 75, 34, 0.12);
        border-radius: 12px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 1.1rem;
        background: white;
    }

    /* ===== BUTTON TAMBAH KERANJANG PREMIUM ===== */
    .btn-cart-premium {
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
    }

    .btn-cart-premium:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(122, 75, 34, 0.3);
        background: var(--gradient-dark);
    }

    .btn-cart-premium:active {
        transform: translateY(-1px) scale(0.98);
    }

    .btn-cart-premium i {
        font-size: 1.2rem;
        transition: transform 0.4s ease;
    }

    .btn-cart-premium:hover i {
        transform: scale(1.2) rotate(-10deg);
    }

    /* Efek kilau pada tombol */
    .btn-cart-premium::after {
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

    .btn-cart-premium:hover::after {
        left: 100%;
    }

    /* ===== RELATED PRODUCTS ===== */
    .related-section {
        max-width: 1280px;
        margin: 6rem auto;
        padding: 0 1.5rem;
    }

    .related-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        margin-bottom: 2.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .related-title::after {
        content: '';
        flex-grow: 1;
        height: 1px;
        background: rgba(122, 75, 34, 0.12);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
    }

    .product-card {
        background: rgba(255,255,255,0.84);
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.9);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        text-decoration: none;
        box-shadow: var(--shadow-sm);
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
    }

    .product-media-sm {
        aspect-ratio: 1/1;
        overflow: hidden;
        background: linear-gradient(135deg, #fff7ef, #fffdf9);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
    }

    .product-media-sm img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-body-sm {
        padding: 1.5rem;
    }

    .product-title-sm {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .product-price-sm {
        color: var(--primary);
        font-weight: 700;
        font-size: 1.1rem;
    }

    @media (max-width: 991px) {
        .detail-container {
            grid-template-columns: 1fr;
            gap: 3rem;
            margin: 2rem auto;
        }

        .detail-media {
            max-height: 400px;
        }
    }

    @media (max-width: 768px) {
        .detail-container {
            padding: 0 1.25rem;
            gap: 2rem;
        }
        
        .detail-media {
            max-width: 100%;
            aspect-ratio: 1/1;
        }
        
        .detail-title {
            font-size: 1.5rem !important;
        }
        
        .detail-price {
            font-size: 1.4rem;
            margin-bottom: 1rem;
        }
        
        .detail-desc {
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }
        
        .detail-meta-grid {
            padding: 1.25rem;
            gap: 0.75rem;
        }
        
        .meta-value {
            font-size: 0.9rem;
        }
        
        .qty-btn {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }
        
        .qty-input {
            width: 60px;
            height: 40px;
            font-size: 1rem;
        }
        
        .btn-cart-premium {
            height: 50px;
            font-size: 0.95rem;
        }
        
        .related-section {
            padding: 0 1.25rem;
            margin: 3rem auto;
        }
        
        .related-title {
            font-size: 1.35rem;
            margin-bottom: 1.5rem;
        }
        
        .products-grid {
            gap: 1.25rem;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        }
        
        .product-body-sm {
            padding: 1.25rem;
        }
        
        .product-title-sm {
            font-size: 0.95rem;
        }
        
        .product-price-sm {
            font-size: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="detail-container">
    <div class="detail-media" data-reveal>
        <span class="detail-badge">{{ $produk->kategori->nama_kategori ?? 'Bakery' }}</span>
        @if($produk->gambar)
            <img src="{{ str_starts_with($produk->gambar, 'http') ? $produk->gambar : asset('storage/' . $produk->gambar) }}" 
                 alt="{{ $produk->nama_produk }}"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="product-placeholder" style="display:none; width:100%; height:100%; align-items:center; justify-content:center; font-size:8rem;">
                {{ ['🍞','🍰','🥐','🍪','🎂'][($produk->kategori_id - 1) % 5] }}
            </div>
        @else
            <div class="product-placeholder">
                {{ ['🍞','🍰','🥐','🍪','🎂'][($produk->kategori_id - 1) % 5] }}
            </div>
        @endif
    </div>

    <div class="detail-info">
        <div data-reveal>
            <span class="detail-cat">{{ $produk->kategori->nama_kategori ?? 'Bakery' }}</span>
            <h1 class="detail-title">{{ $produk->nama_produk }}</h1>
            <div class="detail-price">
                <span>Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                <span class="detail-stock-chip">
                    <i class="fa-solid {{ $produk->stok > 0 ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                    {{ $produk->stok > 0 ? 'Ready Stock' : 'Out of Stock' }}
                </span>
            </div>
            <p class="detail-desc">{{ $produk->deskripsi ?? 'Nikmati produk berkualitas premium dari Mondial Bakery yang dibuat dengan bahan-bahan pilihan dan penuh cinta.' }}</p>

            <div class="detail-meta-grid">
                <div class="meta-item">
                    <span class="meta-label">Stok Tersedia</span>
                    <span class="meta-value">{{ $produk->stok }} {{ $produk->satuan }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Berat Bersih</span>
                    <span class="meta-value">{{ $produk->berat ? $produk->berat . ' g' : '-' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Kategori</span>
                    <span class="meta-value">{{ $produk->kategori->nama_kategori ?? '-' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Status Produk</span>
                    <span class="meta-value" style="color: {{ $produk->stok > 0 ? '#166534' : '#991B1B' }}">
                        {{ $produk->stok > 0 ? '✓ Siap dipesan' : '✕ Sedang habis' }}
                    </span>
                </div>
            </div>

            @auth
                @if(auth()->user()->isCustomer() && $produk->stok > 0)
                    <form method="POST" action="{{ route('customer.keranjang.tambah') }}" class="action-group">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                        <div class="qty-selector">
                            <button type="button" class="qty-btn" onclick="changeQty(-1)">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <input type="number" name="jumlah" value="1" min="1" max="{{ $produk->stok }}" class="qty-input" id="qty">
                            <button type="button" class="qty-btn" onclick="changeQty(1)">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <button type="submit" class="btn-cart-premium">
                            <i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-cart-premium" style="text-decoration: none;">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk untuk Belanja
                </a>
            @endauth
        </div>
    </div>
</div>

@if($related->count() > 0)
    <section class="related-section">
        <h2 class="related-title" data-reveal>Produk Terkait</h2>
        <div class="products-grid">
            @foreach($related as $r)
                <a href="{{ route('produk.detail', $r) }}" class="product-card" data-reveal>
                    <div class="product-media-sm">
                        @if($r->gambar)
                            <img src="{{ str_starts_with($r->gambar, 'http') ? $r->gambar : asset('storage/' . $r->gambar) }}" 
                                 alt="{{ $r->nama_produk }}"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <span style="display:none">{{ ['🍞','🍰','🥐','🍪','🎂'][($r->kategori_id - 1) % 5] }}</span>
                        @else
                            {{ ['🍞','🍰','🥐','🍪','🎂'][($r->kategori_id - 1) % 5] }}
                        @endif
                    </div>
                    <div class="product-body-sm">
                        <h4 class="product-title-sm">{{ $r->nama_produk }}</h4>
                        <p class="product-price-sm">Rp {{ number_format($r->harga, 0, ',', '.') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endif
@endsection

@section('scripts')
<script>
function changeQty(d) {
    const q = document.getElementById('qty');
    let v = parseInt(q.value) + d;
    if (v < 1) v = 1;
    if (v > {{ $produk->stok }}) v = {{ $produk->stok }};
    q.value = v;
}
</script>
@endsection

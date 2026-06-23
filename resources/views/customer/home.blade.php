@extends('layouts.app')
@section('title', 'Mondial Bakery - Toko Kue & Roti Terbaik')

@section('styles')
<style>
    /* ===== HERO SECTION (MB1 Placement + MB Styles) ===== */
    .hero-slider {
        position: relative;
        background: #ffffff;
        padding: 4rem 0 7rem;
        width: 100%;
        overflow: hidden;
        z-index: 1;
    }

    .hero-slider::before,
    .category-icons-section::before,
    .promo-banners::before,
    .trending-section::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: -10px;
        right: -10px;
        background-image: 
            /* Motif Batik Geometris Modern (Truntum Style) */
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

    .hero-slider::after {
        content: '';
        position: absolute;
        left: -10px;
        right: -10px;
        bottom: 0;
        height: 150px;
        background: linear-gradient(180deg, transparent 0%, #ffffff 100%);
        z-index: 0;
    }

    .hero-slide {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 4rem;
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        align-items: center;
        gap: 2rem;
        position: relative;
        z-index: 1;
    }

    .hero-kicker {
        display: block;
        color: var(--primary);
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .hero-text h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        line-height: 1.1;
        margin-bottom: 1.25rem;
        max-width: 640px;
        background: linear-gradient(135deg, var(--text-dark) 0%, var(--text-dark) 30%, var(--primary) 50%, var(--accent) 70%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
    }

    .hero-word {
        background: var(--gradient-gold);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .hero-word.alt {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-light) 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .hero-text p {
        max-width: 560px;
        color: var(--text-medium);
        font-size: 1rem;
        margin-bottom: 2rem;
    }

    .btn-shop-v1 {
        background: var(--gradient-gold);
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        transition: all 0.3s ease;
        display: inline-block;
        box-shadow: 0 4px 10px rgba(122, 75, 34, 0.2);
        border: none;
    }

    .btn-shop-v1:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(122, 75, 34, 0.3);
        color: white;
    }

    .hero-image {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        margin-top: -0.5rem;
    }

    .hero-image::before {
        content: '';
        position: absolute;
        width: min(92%, 520px);
        aspect-ratio: 1 / 1;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(122, 75, 34, 0.08) 0%, rgba(122, 75, 34, 0.02) 58%, transparent 76%);
        bottom: 3%;
        left: 50%;
        transform: translateX(-50%);
        filter: blur(8px);
        z-index: 0;
    }

    .hero-image img {
        position: relative;
        z-index: 1;
        width: min(85%, 420px);
        aspect-ratio: 1 / 1;
        height: auto;
        object-fit: contain;
        object-position: center;
        mix-blend-mode: multiply;
        box-shadow: none;
        animation: heroFloat 8s ease-in-out infinite;
    }

    /* ===== CATEGORY ICONS (MB1 Placement + MB Styles) ===== */
    .category-icons-section {
        position: relative;
        padding: 4rem 0 5rem;
        background: #ffffff;
        width: 100%;
        overflow: hidden;
    }

    .category-icons-grid {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .category-icon-card {
        position: relative;
        text-align: center;
        padding: 0;
        border: none;
        text-decoration: none;
        color: var(--text-dark);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        background: transparent;
        overflow: visible;
        flex: 0 0 auto;
        min-width: 140px;
    }

    .category-icon-card::before {
        content: none;
    }

    .category-icon-card:hover {
        transform: translateY(-5px);
    }

    .category-image-container {
        width: 100px;
        height: 100px;
        margin: 0 auto 0.75rem;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid var(--primary-light);
        transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .category-icon-card:hover .category-image-container {
        transform: scale(1.1);
        border-color: var(--primary);
        box-shadow: 0 8px 20px rgba(122, 75, 34, 0.2);
    }

    .category-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .category-icon-card span {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-medium);
        position: relative;
        z-index: 1;
        transition: color 0.3s ease;
    }

    .category-icon-card:hover span {
        color: var(--primary);
    }

    /* ===== PROMO BANNERS (MB1 Placement + MB Styles) ===== */
    .promo-banners {
        position: relative;
        padding: 5rem 0;
        background: #ffffff;
        width: 100%;
        overflow: hidden;
    }

    .promo-grid {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        position: relative;
        z-index: 1;
    }

    .promo-card {
        position: relative;
        height: 300px;
        background-size: cover;
        background-position: center;
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        overflow: hidden;
        border-radius: 4px;
    }

    .promo-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(28, 18, 13, 0) 0%, rgba(28, 18, 13, 0.4) 100%);
        transition: all 0.6s ease;
        z-index: 1;
    }

    .promo-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }

    .promo-card:hover::before {
        background: linear-gradient(180deg, rgba(28, 18, 13, 0.1) 0%, rgba(28, 18, 13, 0.6) 100%);
    }

    .promo-content {
        position: relative;
        z-index: 2;
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .promo-card:hover .promo-content {
        transform: translateY(-5px);
    }

    .promo-content span {
        font-size: 0.8rem;
        color: var(--accent);
        font-weight: 700;
        text-transform: uppercase;
        display: block;
        margin-bottom: 0.5rem;
        letter-spacing: 0.1em;
    }

    .promo-content h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        margin: 0 0 1.5rem;
        max-width: 220px;
        line-height: 1.2;
        color: var(--white);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .promo-content a {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--white);
        text-decoration: none;
        border-bottom: 2px solid var(--accent);
        padding-bottom: 4px;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .promo-content a:hover {
        color: var(--accent);
        transform: translateX(5px);
    }

    /* ===== TRENDING PRODUCTS (MB1 Placement + MB Styles) ===== */
    .trending-section {
        position: relative;
        padding: 6rem 0;
        background: #ffffff;
        width: 100%;
        overflow: hidden;
    }

    .section-title-v2 {
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
        z-index: 1;
    }

    .section-title-v2 h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        position: relative;
        display: inline-block;
        padding-bottom: 1rem;
    }

    .section-title-v2 h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background: var(--primary);
    }

    .products-grid-v2 {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        position: relative;
        z-index: 1;
    }

    .product-card-v2 {
        position: relative;
        text-align: center;
        transition: all 0.35s ease;
        padding: 1.2rem 1.2rem 1.6rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.95) 0%, rgba(247, 239, 227, 0.9) 100%);
        border: 1px solid rgba(122, 75, 34, 0.08);
        box-shadow: 0 12px 30px rgba(71, 37, 16, 0.08);
        overflow: hidden;
    }

    .product-card-v2:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 38px rgba(71, 37, 16, 0.12);
    }

    .product-card-v2::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(243, 187, 103, 0.12) 0%, transparent 48%, rgba(122, 75, 34, 0.06) 100%);
        pointer-events: none;
    }

    .product-img-v2 {
        position: relative;
        aspect-ratio: 1/1;
        background: linear-gradient(180deg, rgba(255, 250, 245, 0.95) 0%, rgba(255, 238, 217, 0.82) 100%);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }

    .product-img-v2::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(243, 187, 103, 0.16) 0%, transparent 50%, rgba(255, 248, 239, 0.12) 100%);
        pointer-events: none;
    }

    .product-img-v2 img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.5s;
    }

    .product-card-v2:hover .product-img-v2 img {
        transform: scale(1.1);
    }

    .product-badge-v2 {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: #EF4444;
        color: var(--white);
        padding: 0.2rem 0.6rem;
        font-size: 0.7rem;
        font-weight: 700;
        border-radius: 2px;
        text-transform: uppercase;
    }

    .product-info-v2 {
        text-align: center;
    }

    .product-info-v2 h4 {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-medium);
        margin-bottom: 0.5rem;
    }

    .product-info-v2 .price {
        font-weight: 700;
        color: var(--text-dark);
        font-size: 1.1rem;
    }

    .product-info-v2 .rating {
        color: #FFB800;
        font-size: 0.75rem;
        margin-top: 0.5rem;
    }



    /* ===== ANIMATIONS ===== */
    @keyframes heroFloat {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-20px) scale(1.02); }
    }

    @media (max-width: 1024px) {
        .promo-grid { grid-template-columns: 1fr; }
        .products-grid-v2 { grid-template-columns: repeat(2, 1fr); }
        .hero-slide { grid-template-columns: 1fr; text-align: center; }
        .hero-text h1 { font-size: 2.5rem; margin: 0 auto 2rem; }
        .category-icons-section,
        .promo-banners,
        .trending-section {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            margin-top: 0;
        }
    }

    @media (max-width: 640px) {
        .category-icon-card { min-width: 140px; }
        .products-grid-v2 { grid-template-columns: 1fr; }
    }
    
    @media (max-width: 768px) {
        .hero-slider {
            padding: 2.5rem 0 3.5rem;
        }
        
        .hero-slide {
            padding: 0 1.25rem;
            gap: 1.25rem;
        }
        
        .hero-kicker {
            font-size: 0.7rem;
            margin-bottom: 0.5rem;
        }
        
        .hero-text h1 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }
        
        .hero-text p {
            font-size: 0.7rem;
            margin-bottom: 1rem;
        }
        
        .btn-shop-v1 {
            font-size: 0.8rem;
            padding: 0.8rem 1.8rem;
        }
        
        .category-icons-section {
            padding: 3rem 0 4rem;
        }
        
        .category-icons-grid {
            padding: 0 1.25rem;
            gap: 1rem;
        }
        
        .category-image-container {
            width: 80px;
            height: 80px;
        }
        
        .category-icon-card span {
            font-size: 0.75rem;
        }
        
        .promo-banners {
            padding: 3rem 0;
        }
        
        .promo-grid {
            padding: 0 1.25rem;
            gap: 1.5rem;
        }
        
        .promo-card {
            height: 220px;
            padding: 1.5rem;
        }
        
        .promo-content span {
            font-size: 0.7rem;
            margin-bottom: 0.3rem;
        }
        
        .promo-content h3 {
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }
        
        .promo-content a {
            font-size: 0.75rem;
        }
        
        .trending-section {
            padding: 3rem 0;
        }
        
        .section-title-v2 {
            margin-bottom: 2rem;
        }
        
        .section-title-v2 h2 {
            font-size: 1.2rem;
        }
        
        .products-grid-v2 {
            padding: 0 1.25rem;
            gap: 1.5rem;
        }
        
        .product-card-v2 {
            padding: 1rem;
        }
        
        .product-info-v2 h4 {
            font-size: 0.85rem;
        }
        
        .product-info-v2 .price {
            font-size: 0.95rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Slider -->
<section class="hero-slider">
    <div class="hero-slide">
        <div class="hero-text reveal fade-left">
            <span class="hero-kicker">Cita rasa bakery premium setiap hari</span>
            <h1>
                Nikmati <span class="hero-word">roti hangat</span> dan
                <span class="hero-word alt">cake spesial</span> untuk setiap momen istimewa.
            </h1>
            <p>
                Hadir dengan pilihan rasa yang lembut, fresh, dan dibuat dari bahan berkualitas
                untuk menemani sarapan, hadiah, maupun acara keluarga Anda.
            </p>
            <a href="{{ route('katalog') }}" class="btn-shop-v1">Jelajahi Produk</a>
        </div>
        <div class="hero-image reveal fade-right">
            <img src="{{ asset('images/Logo.png') }}" alt="Mondial Bakery Signature">
        </div>
    </div>
</section>

<!-- Category Icons -->
<section class="category-icons-section">
    <div class="category-icons-grid">
        @php
            $placeholderImages = [
                'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=200&h=200&fit=crop',
                'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=200&h=200&fit=crop',
                'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=200&h=200&fit=crop',
                'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?w=200&h=200&fit=crop',
                'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=200&h=200&fit=crop',
                'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=200&h=200&fit=crop',
            ];
        @endphp
        @foreach($kategori as $kat)
        <a href="{{ route('katalog', ['kategori' => $kat->slug]) }}" class="category-icon-card reveal fade-bottom delay-{{ ($loop->index % 6 + 1) * 100 }}">
            <div class="category-image-container">
                @if($kat->gambar)
                    <img src="{{ str_starts_with($kat->gambar, 'http') ? $kat->gambar : asset('storage/' . $kat->gambar) }}" 
                         alt="{{ $kat->nama_kategori }}"
                         class="category-image">
                @else
                    <img src="{{ $placeholderImages[$loop->index % count($placeholderImages)] }}" 
                         alt="{{ $kat->nama_kategori }}"
                         class="category-image">
                @endif
            </div>
            <span>{{ $kat->nama_kategori }}</span>
        </a>
        @endforeach
    </div>
</section>

<!-- Promo Banners -->
<section class="promo-banners">
    <div class="promo-grid">
        <div class="promo-card reveal reveal-slower fade-bottom" style="background-image: url('https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800');">
            <div class="promo-content">
                <span>Roti Segar</span>
                <h3>Premium Artisan Bread</h3>
                <a href="{{ route('katalog') }}">LIHAT PRODUK ></a>
            </div>
        </div>
        <div class="promo-card reveal reveal-slower fade-bottom delay-200" style="background-image: url('https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=800');">
            <div class="promo-content">
                <span>Hanya Hari Ini</span>
                <h3>100% Segar & Buatan Tangan</h3>
                <a href="{{ route('katalog') }}">LIHAT PRODUK ></a>
            </div>
        </div>
        <div class="promo-card reveal reveal-slower fade-bottom delay-400" style="background-image: url('https://images.unsplash.com/photo-1551024506-0bccd828d307?w=800');">
            <div class="promo-content">
                <span>Kualitas Premium</span>
                <h3>Kue & Dessert Manis</h3>
                <a href="{{ route('katalog') }}">LIHAT PRODUK ></a>
            </div>
        </div>
    </div>
</section>

<!-- Trending Products -->
<section class="trending-section" id="produk-unggulan">
    <div class="section-title-v2 reveal fade-bottom">
        <h2>Produk Unggulan Kami</h2>
    </div>
    <div class="products-grid-v2">
        @foreach($featured->take(4) as $prod)
        <div class="product-card-v2 reveal reveal-slower fade-bottom delay-{{ ($loop->index % 4 + 1) * 100 }}" >
            <div class="product-img-v2">
                @if($prod->gambar)
                    <img src="{{ str_starts_with($prod->gambar, 'http') ? $prod->gambar : asset('storage/' . $prod->gambar) }}" 
                         alt="{{ $prod->nama_produk }}"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="fallback-image" style="display:none; width:100%; height:100%; align-items:center; justify-content:center; font-size:4rem; background: var(--bg-alt);">
                        {{ ['🍞','🍰','🥐','🍪','🎂'][($prod->kategori_id - 1) % 5] }}
                    </div>
                @else
                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:4rem; background: var(--bg-alt);">
                        {{ ['🍞','🍰','🥐','🍪','🎂'][($prod->kategori_id - 1) % 5] }}
                    </div>
                @endif
                <span class="product-badge-v2">Best Seller</span>
            </div>
            <div class="product-info-v2">
                <h4>{{ $prod->nama_produk }}</h4>
                <div class="price">Rp {{ number_format($prod->harga, 0, ',', '.') }}</div>
                <div class="rating">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>


@endsection

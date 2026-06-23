@extends('layouts.app')
@section('title', 'Katalog Produk - Mondial Bakery')

@section('styles')
<style>
    .page-header {
        position: relative;
        padding: 5rem 0;
        background: #ffffff;
        text-align: center;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(30deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(150deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(60deg, rgba(199, 131, 77, 0.01) 25%, transparent 25.5%, transparent 75%, rgba(199, 131, 77, 0.01) 75%, rgba(199, 131, 77, 0.01));
        background-size: 40px 70px;
        opacity: 0.8;
        z-index: 0;
    }

    .page-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        background: linear-gradient(135deg, var(--text-dark) 0%, var(--text-dark) 30%, var(--primary) 50%, var(--accent) 70%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .page-header p {
        font-size: 1.1rem;
        color: var(--text-light);
        max-width: 600px;
        margin: 0 auto;
    }

    /* ===== KATALOG CONTAINER (MB1 Placement + MB Styles) ===== */
    .katalog-section {
        position: relative;
        padding: 2rem 0 5rem;
        background: #fff;
        overflow: hidden;
    }

    .katalog-section::before {
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

    .katalog-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 2rem;
        position: relative;
        z-index: 1;
    }

    /* ===== FILTER HORIZONTAL (NAVIGASI) ===== */
    .filter-nav {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(247, 239, 227, 0.9) 100%);
        border-radius: 28px;
        padding: 2rem;
        border: 1px solid var(--border);
        box-shadow: 0 10px 40px rgba(71, 37, 16, 0.08);
        margin-bottom: 2rem;
        position: relative;
        z-index: 10;
    }

    .filter-nav::before {
        content: '';
        position: absolute;
        inset: 0;
        background: 
            radial-gradient(circle at 20% 20%, rgba(243, 187, 103, 0.08) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(122, 75, 34, 0.06) 0%, transparent 50%);
        border-radius: 28px;
        pointer-events: none;
    }

    .filter-nav form {
        display: grid;
        grid-template-columns: 1fr auto auto auto;
        gap: 1.5rem;
        align-items: end;
        position: relative;
        z-index: 1;
    }

    .filter-group {
        margin-bottom: 0;
    }

    .cat-pills {
        display: flex;
        flex-direction: row;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .cat-pill {
        padding: 0.6rem 1.5rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.85);
        border: 1.5px solid var(--border);
        color: var(--text-medium);
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.9rem;
    }

    .cat-pill:hover {
        background: rgba(247, 239, 227, 0.95);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(71, 37, 16, 0.1);
    }

    .cat-pill.active {
        background: var(--gradient-gold);
        color: white;
        border-color: transparent;
        box-shadow: 0 6px 18px rgba(71, 37, 16, 0.25);
        transform: translateY(-1px);
    }

    .sort-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%237a4b22' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.5rem;
        padding-right: 3.5rem !important;
    }

    /* ===== PRODUCT GRID (3 KESAMPING) ===== */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    @media (max-width: 1024px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .filter-nav form {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .product-grid {
            grid-template-columns: 1fr;
        }
    }

    .product-card {
        background: var(--surface-soft);
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid var(--border);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .product-footer button {
        padding: 0.5rem 1.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        background: var(--gradient-gold);
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(122, 75, 34, 0.2);
    }

    .product-footer button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(122, 75, 34, 0.3);
        background: var(--gradient-dark);
    }

    .product-footer button:active {
        transform: translateY(0);
        box-shadow: 0 2px 5px rgba(122, 75, 34, 0.2);
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
        border-color: transparent;
    }

    .product-media {
        position: relative;
        aspect-ratio: 4/3;
        overflow: hidden;
        background: var(--bg-alt);
    }

    .product-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .product-card:hover .product-media img {
        transform: scale(1.1);
    }

    .product-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-cat {
        font-size: 0.75rem;
        color: var(--primary);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
        display: block;
    }

    .product-title {
        font-size: 1.125rem;
        margin-bottom: 0.75rem;
        font-weight: 600;
        color: var(--text-dark);
        line-height: 1.4;
    }

    .product-desc {
        color: var(--text-light);
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid var(--border);
    }

    .product-price-info {
        display: flex;
        flex-direction: column;
    }

    .product-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
    }

    .stok-info {
        font-size: 0.75rem;
        color: var(--text-light);
    }

    @media (max-width: 991px) {
        .katalog-container {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }
        .filter-sidebar {
            position: static;
            width: 100%;
        }
        .cat-pills {
            flex-direction: row;
            flex-wrap: wrap;
        }
    }
    
    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 0 1.5rem;
        }
        
        .page-header h1 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            font-size: 0.75rem;
            padding: 0 1rem;
        }
        
        .katalog-section {
            padding: 1.5rem 0 3rem;
        }
        
        .katalog-container {
            padding: 0 1.25rem;
            gap: 1.5rem;
        }
        
        .filter-nav {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .filter-nav h3 {
            font-size: 1rem;
        }
        
        .cat-pill {
            font-size: 0.8rem;
            padding: 0.5rem 1.2rem;
        }
        
        .filter-nav label {
            font-size: 0.7rem;
        }
        
        .filter-nav input,
        .filter-nav select {
            font-size: 0.85rem;
            padding: 0.7rem 1.2rem;
        }
        
        .filter-nav form button {
            font-size: 0.85rem;
            padding: 0.7rem 1.5rem;
        }
        
        .product-grid {
            gap: 1.5rem;
        }
        
        .product-card {
            border-radius: 18px;
        }
        
        .product-body {
            padding: 1.2rem;
        }
        
        .product-title {
            font-size: 1rem;
        }
        
        .product-desc {
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }
        
        .product-price {
            font-size: 1.1rem;
        }
        
        .product-footer button {
            font-size: 0.8rem;
            padding: 0.4rem 1.2rem;
        }
        
        .product-card-not-found h3 {
            font-size: 1.3rem;
        }
        
        .product-card-not-found p {
            font-size: 0.9rem;
        }
        
        .product-card-not-found a {
            font-size: 0.9rem;
            padding: 0.75rem 1.75rem;
        }
    }

    @media (max-width: 480px) {
        .filter-nav {
            border-radius: 20px;
            padding: 1.25rem;
        }

        .cat-pills {
            gap: 0.5rem;
        }

        .cat-pill {
            flex: 1 1 calc(50% - 0.5rem);
            text-align: center;
        }

        .product-footer {
            align-items: stretch;
            flex-direction: column;
            gap: 0.9rem;
        }

        .product-price,
        .stok-info {
            overflow-wrap: anywhere;
        }

        .product-footer form,
        .product-footer button,
        .product-footer a {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endsection

@section('content')
<header class="page-header">
    <div class="container">
        <h1 class="reveal fade-bottom">Katalog Produk</h1>
        <p class="reveal fade-bottom delay-100">Temukan kelezatan autentik dalam setiap pilihan roti dan kue kami yang dibuat dengan bahan premium.</p>
    </div>
</header>

<section class="katalog-section">
    <div class="katalog-container">
    <!-- Filter Nav (Horizontal) -->
    <div class="filter-nav reveal fade-bottom">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; position: relative; z-index: 1;">
            <i class="fas fa-sliders-h" style="font-size: 1.5rem; color: var(--primary);"></i>
            <h3 style="margin: 0; font-size: 1.2rem; font-weight: 700; color: var(--text-dark); font-family: 'Poppins', sans-serif;">Filter Produk</h3>
        </div>
        <form action="{{ route('katalog') }}" method="GET" id="filterForm">
            <div class="filter-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; font-size: 0.8rem; color: var(--text-dark); margin-bottom: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <i class="fas fa-search" style="font-size: 0.9rem;"></i>
                    Cari Produk
                </label>
                <input type="text" name="search" class="search-input" style="width: 100%; padding: 0.85rem 1.5rem; border: 1.5px solid var(--border); border-radius: 999px; font-family: 'Poppins'; font-size: 0.95rem; background: rgba(255, 255, 255, 0.9); transition: all 0.3s ease; box-shadow: inset 0 2px 8px rgba(0,0,0,0.02);" placeholder="Nama roti atau kue..." value="{{ request('search') }}" onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(122, 75, 34, 0.08)'; this.style.outline='none';" onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='inset 0 2px 8px rgba(0,0,0,0.02)';">
            </div>

            <div class="filter-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; font-size: 0.8rem; color: var(--text-dark); margin-bottom: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <i class="fas fa-tags" style="font-size: 0.9rem;"></i>
                    Kategori
                </label>
                <div class="cat-pills">
                    <a href="{{ route('katalog') }}" 
                       class="cat-pill {{ !request('kategori') ? 'active' : '' }}" style="text-decoration: none;">
                        Semua
                    </a>
                    @foreach($kategori as $kat)
                        <a href="{{ route('katalog', ['kategori' => $kat->slug, 'search' => request('search')]) }}" 
                           class="cat-pill {{ request('kategori') == $kat->slug ? 'active' : '' }}" style="text-decoration: none;">
                            {{ $kat->nama_kategori }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="filter-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; font-size: 0.8rem; color: var(--text-dark); margin-bottom: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <i class="fas fa-sort" style="font-size: 0.9rem;"></i>
                    Urutan
                </label>
                <select name="sort" class="sort-select" style="padding: 0.85rem 1.5rem; border: 1.5px solid var(--border); border-radius: 999px; font-family: 'Poppins'; font-size: 0.95rem; background-color: rgba(255, 255, 255, 0.9); cursor: pointer; transition: all 0.3s ease; box-shadow: inset 0 2px 8px rgba(0,0,0,0.02); width: 100%;" onchange="this.form.submit()" onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(122, 75, 34, 0.08)'; this.style.outline='none';" onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='inset 0 2px 8px rgba(0,0,0,0.02)';">
                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga ↑</option>
                    <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga ↓</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 0.85rem 2rem; border-radius: 999px; white-space: nowrap; background: var(--gradient-gold); color: white; font-weight: 700; font-size: 0.95rem; border: none; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 6px 20px rgba(122, 75, 34, 0.35);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 30px rgba(122, 75, 34, 0.45)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(122, 75, 34, 0.35)';">Cari</button>
        </form>
    </div>

    <!-- Product Grid -->
    <div class="catalog-main">
        @if($produk->count() > 0)
            <div class="product-grid">
                @foreach($produk as $prod)
                    <div class="product-card reveal reveal-slower fade-bottom delay-{{ ($loop->index % 3 + 1) * 100 }}">
                        <a href="{{ route('produk.detail', $prod) }}" class="product-media">
                            @if($prod->gambar)
                                <img src="{{ str_starts_with($prod->gambar, 'http') ? $prod->gambar : asset('storage/' . $prod->gambar) }}" 
                                     alt="{{ $prod->nama_produk }}"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="fallback-image" style="display:none; width:100%; height:100%; align-items:center; justify-content:center; font-size:5rem; background: var(--bg-alt);">
                                    {{ ['🍞','🍰','🥐','🍪','🎂'][($prod->kategori_id - 1) % 5] }}
                                </div>
                            @else
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:5rem; background: var(--bg-alt);">
                                    {{ ['🍞','🍰','🥐','🍪','🎂'][($prod->kategori_id - 1) % 5] }}
                                </div>
                            @endif
                        </a>
                        <div class="product-body">
                            <span class="product-cat">{{ $prod->kategori->nama_kategori ?? 'Bakery' }}</span>
                            <h4 class="product-title">{{ $prod->nama_produk }}</h4>
                            <p class="product-desc">{{ $prod->deskripsi }}</p>
                            <div class="product-footer">
                                <div class="product-price-info">
                                    <span class="product-price">Rp {{ number_format($prod->harga, 0, ',', '.') }}</span>
                                    <span class="stok-info">Tersedia: {{ $prod->stok }} {{ $prod->satuan }}</span>
                                </div>
                                @auth
                                    <form action="{{ route('produk.detail', $prod) }}" method="GET" style="margin: 0;">
                                        <button type="submit">
                                            Beli
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" style="padding: 0.5rem 1.5rem; border-radius: 4px; font-size: 0.875rem; font-weight: 700; text-transform: uppercase; background: var(--gradient-gold); color: white; border: none; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(122, 75, 34, 0.2); text-decoration: none; display: inline-block;">
                                        Beli
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination removed as per user request to show all products --}}
            {{-- <div style="margin-top: 4rem;">
                {{ $produk->withQueryString()->links() }}
            </div> --}}
        @else
                <div class="reveal fade-bottom" style="text-align:center;padding: 6rem 2rem;background: var(--surface-soft);border-radius: 24px;border: 1px solid var(--border);">
                    <div style="font-size: 5rem;color: var(--primary-light);margin-bottom: 2rem;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <h3 style="font-size: 1.7rem;margin-bottom: 1rem;color: var(--text-dark);font-weight: 800;">Produk Tidak Ditemukan</h3>
                    <p style="color: var(--text-light);max-width: 450px;margin: 0 auto 2.5rem;font-size: 1.05rem;">Maaf, kami tidak dapat menemukan produk yang Anda cari. Coba gunakan kata kunci lain atau reset filter.</p>
                    <a href="{{ route('katalog') }}" style="display:inline-flex;align-items:center;justify-content:center;gap: 0.6rem;padding: 0.9rem 2rem;border-radius: 999px;background: var(--gradient-gold);color: white;text-decoration: none;font-weight: 700;font-size: 1rem;transition: all 0.3s ease;box-shadow: 0 6px 20px rgba(122, 75, 34, 0.35);" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 30px rgba(122, 75, 34, 0.45)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 6px 20px rgba(122, 75, 34, 0.35)';">
                        <i class="fas fa-arrow-left"></i>
                        Lihat Semua Produk
                    </a>
                </div>
            @endif
    </div>
    </div>
</section>
@endsection

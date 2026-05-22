@extends('layouts.app')
@section('title', 'Katalog Produk - Mondial Bakery')

@section('styles')
<style>
    /* ===== KATALOG HEADER (MB1 Placement + MB Styles) ===== */
    .katalog-header {
        position: relative;
        background: #ffffff;
        padding: 5rem 0;
        width: 100%;
        border-bottom: none;
        text-align: center;
        overflow: hidden;
    }

    .katalog-header::before {
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

    .katalog-header h1, .katalog-header p {
        position: relative;
        z-index: 1;
    }

    .katalog-header h1 {
        position: relative;
        z-index: 1;
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        background: linear-gradient(135deg, var(--text-dark) 0%, var(--text-dark) 30%, var(--primary) 50%, var(--accent) 70%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .katalog-header p {
        color: var(--text-light);
        font-size: 1.1rem;
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
        background: var(--white);
        border-radius: 20px;
        padding: 1.5rem;
        border: 1px solid var(--border);
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        margin-bottom: 2rem;
        position: relative;
        z-index: 10;
    }

    .filter-nav form {
        display: grid;
        grid-template-columns: 1fr auto auto auto;
        gap: 1.5rem;
        align-items: end;
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
        padding: 0.5rem 1.25rem;
        border-radius: 999px;
        background: var(--bg-alt);
        border: 1.5px solid var(--border);
        color: var(--text-medium);
        font-weight: 500;
    }

    .cat-pill.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
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
        background: var(--white);
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid var(--border);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
        border-color: transparent;
    }

    .product-media {
        position: relative;
        aspect-ratio: 1/1;
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
</style>
@endsection

@section('content')
<div class="katalog-header">
    <h1>Katalog Produk</h1>
    <p>Temukan kelezatan autentik dalam setiap pilihan roti dan kue kami yang dibuat dengan bahan premium.</p>
</div>

<section class="katalog-section">
    <div class="katalog-container">
    <!-- Filter Nav (Horizontal) -->
    <div class="filter-nav" data-reveal>
        <form action="{{ route('katalog') }}" method="GET" id="filterForm">
            <div class="filter-group">
                <label style="display: block; font-weight: 600; font-size: 0.8rem; color: var(--text-dark); margin-bottom: 0.5rem; text-transform: uppercase;">Cari Produk</label>
                <input type="text" name="search" class="search-input" style="width: 100%; padding: 0.6rem 1rem; border: 1.5px solid var(--border); border-radius: 999px; font-family: 'Poppins'; font-size: 0.9rem; background: var(--bg-alt);" placeholder="Nama roti atau kue..." value="{{ request('search') }}">
            </div>

            <div class="filter-group">
                <label style="display: block; font-weight: 600; font-size: 0.8rem; color: var(--text-dark); margin-bottom: 0.5rem; text-transform: uppercase;">Kategori</label>
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
                <label style="display: block; font-weight: 600; font-size: 0.8rem; color: var(--text-dark); margin-bottom: 0.5rem; text-transform: uppercase;">Urutan</label>
                <select name="sort" class="sort-select" style="padding: 0.6rem 1rem; border: 1.5px solid var(--border); border-radius: 999px; font-family: 'Poppins'; font-size: 0.9rem; background: var(--bg-alt); cursor: pointer;" onchange="this.form.submit()">
                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga ↑</option>
                    <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga ↓</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.5rem; border-radius: 999px; white-space: nowrap;">Cari</button>
        </form>
    </div>

    <!-- Product Grid -->
    <div class="catalog-main">
        @if($produk->count() > 0)
            <div class="product-grid">
                @foreach($produk as $prod)
                    <div class="product-card reveal fade-bottom" data-reveal>
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
                                <a href="{{ route('produk.detail', $prod) }}" class="btn btn-secondary btn-sm" title="Lihat Detail" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
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
            <div style="text-align:center; padding: 6rem 2rem; background: var(--white); border-radius: 24px; border: 1px solid var(--border);" data-reveal>
                <div style="font-size: 4rem; color: var(--border); margin-bottom: 2rem;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: var(--text-dark);">Produk Tidak Ditemukan</h3>
                <p style="color: var(--text-light); max-width: 400px; margin: 0 auto 2rem;">Maaf, kami tidak dapat menemukan produk yang Anda cari. Coba gunakan kata kunci lain atau reset filter.</p>
                <a href="{{ route('katalog') }}" class="btn btn-primary" style="border-radius: 12px;">Lihat Semua Produk</a>
            </div>
        @endif
    </div>
    </div>
</section>
@endsection

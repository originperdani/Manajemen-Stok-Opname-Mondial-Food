<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mondial Bakery')</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #7a4b22;
            --primary-light: #c7834d;
            --primary-dark: #512c16;
            --secondary: #fff4e7;
            --accent: #f3bb67;
            --accent-soft: #ffe1bf;
            --bg: #fffaf5;
            --bg-alt: #fffdf9;
            --surface-plain: #fbf6ef;
            --surface-soft: #f7efe3;
            --surface-warm: #f3e8d8;
            --section-cream: linear-gradient(180deg, rgba(255, 250, 245, 0.96) 0%, rgba(255, 244, 231, 0.92) 100%);
            --section-warm: linear-gradient(135deg, rgba(255, 248, 239, 0.98) 0%, rgba(255, 240, 222, 0.94) 50%, rgba(255, 250, 245, 0.98) 100%);
            --section-soft-gold: linear-gradient(135deg, rgba(255, 247, 235, 0.96) 0%, rgba(255, 233, 205, 0.92) 100%);
            --section-light: linear-gradient(180deg, rgba(255, 255, 255, 0.96) 0%, rgba(255, 248, 239, 0.94) 100%);
            --text-dark: #1f140d;
            --text-medium: #6c5243;
            --text-light: #9a816f;
            --white: #ffffff;
            --border: rgba(122, 75, 34, 0.12);
            --shadow-sm: 0 8px 30px rgba(71, 37, 16, 0.08);
            --shadow-md: 0 20px 50px rgba(71, 37, 16, 0.14);
            --shadow-lg: 0 28px 70px rgba(71, 37, 16, 0.18);
            --radius-sm: 14px;
            --radius: 24px;
            --radius-lg: 36px;
            --gradient-gold: linear-gradient(135deg, #7a4b22 0%, #c7834d 50%, #f3bb67 100%);
            --gradient-dark: linear-gradient(135deg, #1c120d 0%, #392116 55%, #6c3e1c 100%);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; outline: none !important; -webkit-tap-highlight-color: transparent; }
        body {
            caret-color: transparent;
        }
        a, button, img,
        h1, h2, h3, h4, h5, h6,
        p, span, small, strong, em,
        div, section, article, aside,
        label, li, ul, ol {
            user-select: none;
            -webkit-user-select: none;
            -webkit-user-drag: none;
        }
        input, textarea, select, option {
            user-select: text;
            -webkit-user-select: text;
            caret-color: auto;
        }
        a:focus, button:focus, img:focus, a:active, button:active, img:active { 
            outline: none !important; 
            box-shadow: none !important;
            background-color: transparent !important;
        }
        html { 
            scroll-behavior: smooth; 
            overflow-x: hidden;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        main {
            position: relative;
            overflow: hidden;
            background: #ffffff;
        }

        main::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 24px 24px, rgba(122, 75, 34, 0.03) 0 2px, transparent 2.5px),
                radial-gradient(circle at 84px 84px, rgba(199, 131, 77, 0.025) 0 2px, transparent 2.5px),
                linear-gradient(45deg, transparent 46%, rgba(122, 75, 34, 0.02) 48%, transparent 52%),
                linear-gradient(-45deg, transparent 46%, rgba(122, 75, 34, 0.018) 48%, transparent 52%);
            background-size: 120px 120px, 120px 120px, 120px 120px, 120px 120px;
            background-position: 0 0, 40px 40px, 0 0, 0 0;
            opacity: 0.5;
            pointer-events: none;
            z-index: 0;
        }

        main > * {
            position: relative;
            z-index: 1;
        }

        /* ===== TOP BAR ===== */
        .top-bar {
            background:
                linear-gradient(180deg, rgba(243, 187, 103, 0.08) 0%, transparent 42%),
                var(--gradient-dark);
            color: rgba(255, 255, 255, 0.92);
            padding: 0.6rem 0;
            font-size: 0.75rem;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        .top-bar::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08), transparent);
            animation: shimmer 6s linear infinite;
        }
        .top-bar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .top-bar-links { display: flex; gap: 1.5rem; list-style: none; }
        .top-bar-links a { color: rgba(255,255,255,0.8); text-decoration: none; transition: 0.3s; }
        .top-bar-links a:hover { color: var(--accent); }

        /* ===== MAIN HEADER ===== */
        .header-main {
            padding: 1rem 0 0.8rem;
            background: #ffffff;
            border-bottom: none;
        }
        .header-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 3rem;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 2rem;
        }
        .header-left { display: none; }
        
        .header-center .logo {
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            line-height: 1;
            transition: transform 0.3s ease;
            width: auto;
            height: 80px;
        }
        .header-center .logo:hover { transform: scale(1.05); }
        .header-center .logo img {
            height: 100%;
            width: auto;
            object-fit: contain;
        }

        .header-nav {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2.5rem;
        }

        .header-nav a {
            text-decoration: none;
            color: var(--text-medium);
            font-size: 0.82rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .header-nav a:hover,
        .header-nav a.active {
            color: var(--text-dark);
        }
        
        .header-right { display: flex; justify-content: flex-end; align-items: center; gap: 0.85rem; }
        .header-action-btn {
            color: var(--text-dark);
            text-decoration: none;
            font-size: 1rem;
            position: relative;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: transparent;
        }
        .header-action-btn:hover { color: var(--primary); transform: translateY(-2px); }
        .header-action-btn .badge {
            position: absolute;
            top: -2px; right: -2px;
            background: var(--gradient-gold);
            color: white;
            font-size: 0.58rem;
            min-width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 1.5px solid var(--surface-plain);
        }

        /* ===== NAVBAR ===== */
        .navbar {
            display: none;
        }
        .navbar.scrolled { padding: 0.2rem 0; box-shadow: var(--shadow-md); }
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-links { display: flex; list-style: none; gap: 2.5rem; }
        .nav-links li { position: relative; }
        .nav-links a {
            display: block;
            padding: 1rem 0;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: 0.3s;
        }
        .nav-links a:hover, .nav-links a.active { color: var(--primary); }
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 0; height: 3px;
            background: var(--gradient-gold);
            transition: 0.3s;
        }
        .nav-links a:hover::after, .nav-links a.active::after { width: 100%; }

        .nav-search {
            display: flex;
            align-items: center;
            background: var(--bg-alt);
            border-radius: 12px;
            padding: 0.6rem 1.2rem;
            width: 320px;
            border: 1px solid var(--border);
            transition: 0.3s;
        }
        .nav-search:focus-within { background: white; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(122, 75, 34, 0.05); }
        .nav-search input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 0.85rem;
            font-family: 'Poppins';
            color: var(--text-dark);
        }
        .nav-search i { color: var(--text-light); font-size: 0.9rem; }

        /* ===== ALERTS ===== */
        .alert-container {
            max-width: 1400px;
            margin: 1rem auto 0;
            padding: 0 2rem;
        }
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 16px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: var(--shadow-sm);
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }
        .alert-success { background: #f0fdf4; color: #166534; border-color: #dcfce7; }
        .alert-danger { background: #fef2f2; color: #991b1b; border-color: #fee2e2; }

        /* ===== FOOTER ===== */
        .footer {
            background: linear-gradient(135deg, #1c120d 0%, #392116 45%, #6c3e1c 75%, #8f5a2f 100%);
            color: #ede4db;
            padding: 2rem 0 1rem;
            margin-top: 3rem;
            position: relative;
            overflow: hidden;
            border-top: 1px solid rgba(243, 187, 103, 0.1);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: flex-start;
            gap: 2.5rem;
            position: relative;
            z-index: 1;
        }

        .footer-left-group {
            display: flex;
            gap: 2.5rem;
            align-items: flex-start;
        }

        .footer h5 {
            color: var(--white);
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 0.4rem; }
        .footer-links a {
            color: rgba(237, 228, 219, 0.8);
            text-decoration: none;
            transition: 0.3s;
            font-size: 0.8rem;
        }
        .footer-links a:hover { color: var(--accent); }

        .footer-admin-links { list-style: none; }
        .footer-admin-links li { margin-bottom: 0.4rem; }
        .footer-admin-links a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(237, 228, 219, 0.8);
            text-decoration: none;
            transition: 0.3s;
            font-size: 0.8rem;
        }
        .footer-admin-links i {
            font-size: 1rem;
            color: var(--white);
        }
        .footer-admin-links a:hover { color: var(--accent); }

        .footer-brand-center {
            text-align: center;
            padding: 0 1rem;
        }
        .footer-logo img {
             height: 60px;
             width: auto;
             object-fit: contain;
             margin-bottom: 0.8rem;
             /* Menampilkan detail logo (mata) dan menambahkan outline putih agar tulisan gelap tetap terlihat di bg gelap */
             filter: drop-shadow(0 0 1px rgba(255,255,255,0.7))
                     drop-shadow(0 0 1px rgba(255,255,255,0.7));
             opacity: 0.9;
         }
         .footer-brand-center h4 {
             font-family: 'Playfair Display', serif;
             font-size: 1.5rem;
             color: var(--white);
             margin-bottom: 0.4rem;
             font-weight: 700;
             letter-spacing: 0.02em;
             text-shadow: 0 2px 4px rgba(0,0,0,0.3);
         }
         .footer-brand-center p {
             color: rgba(255, 255, 255, 0.8);
             line-height: 1.5;
             font-size: 0.8rem;
             max-width: 280px;
             margin: 0 auto 1rem;
             font-style: italic;
         }

         .social-links { display: flex; gap: 0.8rem; justify-content: center; }
         .social-links a {
             width: 32px; height: 32px; border-radius: 50%;
             background: rgba(255, 255, 255, 0.1);
             display: flex; align-items: center; justify-content: center;
             color: var(--white) !important;
             text-decoration: none;
             transition: all 0.3s ease;
             border: 1px solid rgba(255, 255, 255, 0.1);
             font-size: 0.8rem;
         }
         .social-links a:hover {
             background: var(--accent);
             color: var(--primary-dark) !important;
             transform: translateY(-3px);
             border-color: var(--accent);
         }

        .footer-address-section {
            text-align: right;
        }
        .footer-address {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.6rem;
        }
        .footer-address p {
            color: rgba(237, 228, 219, 0.8);
            font-size: 0.8rem;
            line-height: 1.4;
            max-width: 180px;
        }
        .footer-address i {
            font-size: 1rem;
            color: var(--white);
        }

        .footer-bottom {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            color: rgba(237, 228, 219, 0.5);
            font-size: 0.75rem;
            position: relative;
            z-index: 1;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @media (max-width: 1024px) {
            .footer-container { grid-template-columns: 1fr 1fr; }
            .header-container, .nav-container { padding: 0 1.5rem; }
            .header-nav { gap: 1.5rem; }
        }
        @media (max-width: 768px) {
            .header-container { grid-template-columns: auto 1fr; }
            .header-left { display: none; }
            .header-nav { display: none; }
            .nav-links, .nav-search { display: none; }
            .footer-container { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; gap: 1rem; text-align: center; }
            .footer { border-top-left-radius: 32px; border-top-right-radius: 32px; }
        }

        /* Common Page Components */
        .card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 1.5rem; }
        .card-body { padding: 1.75rem; }
        .card-header { padding: 1.25rem 1.75rem; background: rgba(122, 75, 34, 0.03); border-bottom: 1px solid var(--border); }
        .card-header h3 { font-size: 1.15rem; font-weight: 700; color: var(--text-dark); margin: 0; }
        .btn-shop { 
            display: inline-flex; align-items: center; justify-content: center; gap: 0.6rem;
            padding: 0.9rem 2rem; border-radius: 999px; background: var(--gradient-gold);
            color: white; text-decoration: none; font-weight: 600; transition: 0.3s;
            box-shadow: 0 10px 20px rgba(122, 75, 34, 0.2);
        }
        .btn-shop:hover { background: var(--gradient-dark); color: var(--white); transform: translateY(-3px); box-shadow: var(--shadow-md); }

        /* ===== SCROLL ANIMATIONS (ULTRA SMOOTH & DREAMY) ===== */
        .reveal {
            position: relative;
            opacity: 0;
            transition: transform 3.5s cubic-bezier(0.19, 1, 0.22, 1), 
                        opacity 3.5s cubic-bezier(0.19, 1, 0.22, 1),
                        filter 3.5s cubic-bezier(0.19, 1, 0.22, 1);
            will-change: transform, opacity, filter;
            filter: blur(40px) brightness(0.65);
        }

        .reveal.active {
            opacity: 1;
            filter: blur(0) brightness(1);
        }

        .fade-bottom, .fade-left, .fade-right, .fade-top {
            transform: translateX(-150px) scale(0.92);
        }

        .fade-bottom.active, .fade-left.active, .fade-right.active, .fade-top.active {
            transform: translateX(0) scale(1);
        }

        .zoom-in {
            transform: scale(0.75) translateX(-80px);
        }

        .zoom-in.active {
            transform: scale(1) translateX(0);
        }

        .delay-100 { transition-delay: 400ms; }
        .delay-200 { transition-delay: 800ms; }
        .delay-300 { transition-delay: 1200ms; }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Main Header -->
    <header class="header-main">
        <div class="header-container">
            <div class="header-left">
                <!-- Info Kontak dihapus -->
            </div>
            <div class="header-center">
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('images/Logo.png') }}" alt="Mondial Bakery">
                </a>
            </div>
            <div class="header-nav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('katalog') }}" class="{{ request()->routeIs('katalog') ? 'active' : '' }}">Semua Produk</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Tentang Kami</a>
                @if(!auth()->check() || auth()->user()->isCustomer())
                    <a href="{{ route('customer.pesanan') }}" class="{{ request()->routeIs('customer.pesanan*') ? 'active' : '' }}">Pesanan</a>
                @endif
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Hubungi Kami</a>
            </div>
            <div class="header-right">
                @auth
                    <div style="display: flex; align-items: center; gap: 1.5rem;">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ match(auth()->user()->role) {
                                'owner' => route('owner.dashboard'),
                                'admin_gudang' => route('gudang.dashboard'),
                                'admin_penjualan' => route('penjualan.dashboard'),
                                'admin_produksi' => route('produksi.dashboard'),
                            } }}" class="header-action-btn" title="Dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                            </a>
                        @endif
                        
                        <a href="{{ route('katalog') }}" class="header-action-btn" title="Cari">
                            <i class="fas fa-search"></i>
                        </a>
                        <a href="#" class="header-action-btn" title="{{ auth()->user()->name }}">
                            <i class="far fa-user"></i>
                        </a>

                        <a href="{{ route('customer.keranjang') }}" class="header-action-btn" title="Keranjang Belanja">
                            <i class="fas fa-shopping-basket"></i>
                            @php $cartCount = \App\Models\Keranjang::where('user_id', auth()->id())->count(); @endphp
                            <span class="badge">{{ $cartCount ?? 0 }}</span>
                        </a>

                        <form action="{{ route('logout') }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="header-action-btn" style="border:none; background:none; cursor:pointer; padding:0;">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <div style="display: flex; align-items: center; gap: 1.5rem;">
                        <a href="{{ route('katalog') }}" class="header-action-btn" title="Cari">
                            <i class="fas fa-search"></i>
                        </a>
                        <a href="{{ route('login') }}" class="header-action-btn" title="Masuk">
                            <i class="far fa-user"></i>
                        </a>
                        <a href="{{ route('login') }}" class="header-action-btn" title="Keranjang">
                            <i class="fas fa-shopping-basket"></i>
                            <span class="badge">0</span>
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('katalog') }}" class="{{ request()->routeIs('katalog') ? 'active' : '' }}">Katalog</a></li>
                @if(!auth()->check() || auth()->user()->isCustomer())
                    <li><a href="{{ route('customer.pesanan') }}" class="{{ request()->routeIs('customer.pesanan*') ? 'active' : '' }}">Pesanan Saya</a></li>
                @endif
            </ul>
            <div class="nav-search">
                <input type="text" placeholder="Cari roti favorit Anda...">
                <i class="fas fa-search"></i>
            </div>
        </div>
    </nav>

    <!-- Alerts -->
    <div class="container" style="max-width: 1400px; margin-left: auto; margin-right: auto; padding: 0 2rem;">
        @if(session('success'))
            <div class="alert alert-success" style="margin-top: 1rem;">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <!-- Sisi Kiri: Gabungan Toko Kami & Kontak Admin -->
            <div class="footer-left-group">
                <div>
                    <h5>Toko Kami</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('katalog') }}">Katalog Produk</a></li>
                        <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                        <li><a href="{{ route('contact') }}">Hubungi Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h5>Kontak Admin</h5>
                    <ul class="footer-admin-links">
                        <li><a href="https://wa.me/085793930723" target="_blank"><i class="fab fa-whatsapp"></i> Admin 1</a></li>
                        <li><a href="https://wa.me/085793930723" target="_blank"><i class="fab fa-whatsapp"></i> Admin 2</a></li>
                        <li><a href="https://wa.me/085793930723" target="_blank"><i class="fab fa-whatsapp"></i> Admin 3</a></li>
                    </ul>
                </div>
            </div>

            <!-- Sisi Tengah: Branding (Center Aligned like Copyright) -->
            <div class="footer-brand-center">
                <div class="footer-logo">
                    <img src="{{ asset('images/Logo.png') }}" alt="Mondial Bakery">
                </div>
                <h4>Mondial Bakery</h4>
                <p>"Menghadirkan kebahagiaan melalui setiap gigitan roti dan kue premium yang dibuat dengan penuh cinta."</p>
                <div class="social-links" style="justify-content: center;">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>

            <!-- Sisi Kanan: Alamat Kami -->
            <div class="footer-address-section">
                <h5>Alamat Kami</h5>
                <div class="footer-address">
                    <p>Jl. Mesjid Al-Akhyar No.34, Gandul, Cinere, Depok</p>
                    <i class="fas fa-location-dot"></i>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>Copyright &copy; {{ date('Y') }} Mondial Bakery</p>
        </div>
    </footer>

    <script>
        const editableSelector = 'input, textarea, select, [contenteditable="true"], [contenteditable=""], [role="textbox"]';

        function isEditableTarget(target) {
            return !!(target && (target.closest?.(editableSelector) || target.isContentEditable));
        }

        function clearSelection() {
            const selection = window.getSelection ? window.getSelection() : null;
            if (selection && selection.rangeCount > 0) {
                selection.removeAllRanges();
            }
        }

        document.addEventListener('mousedown', event => {
            if (!isEditableTarget(event.target)) {
                clearSelection();
            }
        });

        document.addEventListener('mouseup', event => {
            if (!isEditableTarget(event.target)) {
                setTimeout(clearSelection, 0);
            }
        });

        document.addEventListener('click', event => {
            if (!isEditableTarget(event.target) && document.activeElement && !isEditableTarget(document.activeElement)) {
                document.activeElement.blur?.();
                clearSelection();
            }
        });

        document.addEventListener('selectionchange', () => {
            const selection = window.getSelection ? window.getSelection() : null;
            const anchorElement = selection?.anchorNode?.parentElement;

            if (selection && !selection.isCollapsed && !isEditableTarget(anchorElement)) {
                clearSelection();
            }
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'F7') {
                event.preventDefault();
            }
        });

        // Smooth Navbar Transition
         window.addEventListener('scroll', () => {
             const navbar = document.getElementById('navbar');
             if (window.scrollY > 50) {
                 navbar.classList.add('scrolled');
                 navbar.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)';
             } else {
                 navbar.classList.remove('scrolled');
                 navbar.style.boxShadow = 'none';
             }
         });

         // Auto-hide alerts
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });

        // Scroll Reveal Animation with Intersection Observer
        const revealOptions = {
            threshold: 0.15,
            rootMargin: "0px 0px -50px 0px"
        };

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("active");
                    revealObserver.unobserve(entry.target);
                }
            });
        }, revealOptions);

        document.querySelectorAll(".reveal").forEach(el => {
            revealObserver.observe(el);
        });
    </script>
    @yield('scripts')
</body>
</html>

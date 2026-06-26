<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mondial Bakery')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo.png') }}">
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
            padding-top: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            animation: pageEntrance 1.45s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        main {
            position: relative;
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
            z-index: -1;
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
            padding: 1.25rem 0 1rem;
            background: #ffffff;
            border-bottom: none;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 0.5rem 0 0;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            box-sizing: border-box;
        }

        .header-left { 
            display: none; 
            z-index: 9999999999;
            justify-self: start;
        }

        .header-center {
            justify-self: start;
            margin-left: -1rem;
        }

        .header-nav {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1.5rem;
        }

        .header-right {
            justify-self: end;
        }
        
        .header-center .logo {
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            line-height: 1;
            transition: transform 0.3s ease;
            width: auto;
            height: 85px;
            z-index: 5;
        }
        .header-center .logo:hover { transform: scale(1.05); }
        .header-center .logo img {
            height: 100%;
            width: auto;
            object-fit: contain;
        }

        .header-nav a {
            text-decoration: none;
            color: var(--text-medium);
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding-bottom: 4px;
        }

        .header-nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: var(--gradient-gold);
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .header-nav a:hover,
        .header-nav a.active {
            color: var(--primary);
        }

        .header-nav a:hover::after,
        .header-nav a.active::after {
            width: 100%;
        }
        
        .header-right { 
            display: flex; 
            justify-content: flex-end; 
            align-items: center; 
            gap: 0.5rem; 
            flex-shrink: 0;
            overflow: visible;
            z-index: 20;
        }
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

        .notification-dropdown {
            position: relative;
        }

        .notification-btn {
            position: relative;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1px solid rgba(122, 75, 34, 0.1);
            background: transparent;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-btn:hover,
        .notification-dropdown.active .notification-btn {
            color: var(--primary);
            background: rgba(122, 75, 34, 0.06);
            transform: translateY(-2px);
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -5px;
            min-width: 17px;
            height: 17px;
            padding: 0 4px;
            border-radius: 999px;
            background: #ef4444;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.58rem;
            font-weight: 800;
            border: 1.5px solid #fff;
        }

        .notification-menu {
            position: absolute;
            top: calc(100% + 0.6rem);
            right: 0;
            width: min(350px, calc(100vw - 1rem));
            max-height: 420px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 18px 50px rgba(71, 37, 16, 0.18);
            display: none;
            z-index: 999999;
        }

        .notification-dropdown.active .notification-menu {
            display: block;
            animation: slideDown 0.2s ease;
        }

        .notification-menu-header {
            padding: 1rem 1.1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            background: var(--secondary);
        }

        .notification-menu-header strong {
            display: block;
            font-size: 0.92rem;
            color: var(--text-dark);
        }

        .notification-menu-header span {
            display: block;
            font-size: 0.72rem;
            color: var(--text-light);
            font-weight: 700;
        }

        .notification-list {
            padding: 0.35rem;
        }

        .notification-item {
            display: flex;
            gap: 0.75rem;
            padding: 0.8rem;
            border-radius: 12px;
            color: var(--text-dark);
            text-decoration: none;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .notification-item:hover {
            background: rgba(122, 75, 34, 0.06);
            transform: translateX(2px);
        }

        .notification-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 32px;
            color: #fff;
            background: var(--primary);
        }

        .notification-icon.warning { background: #f59e0b; }
        .notification-icon.info { background: #2563eb; }
        .notification-icon.success { background: #16a34a; }
        .notification-icon.danger { background: #dc2626; }

        .notification-copy {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 0.12rem;
        }

        .notification-copy strong {
            font-size: 0.82rem;
            line-height: 1.25;
        }

        .notification-copy small {
            color: var(--text-medium);
            font-size: 0.72rem;
            line-height: 1.35;
        }

        .notification-copy em {
            color: var(--text-light);
            font-size: 0.68rem;
            font-style: normal;
            font-weight: 700;
        }

        .notification-empty {
            padding: 1.4rem 1rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            color: var(--text-light);
        }

        .notification-empty i {
            color: #16a34a;
            font-size: 1.35rem;
            margin-bottom: 0.25rem;
        }

        .notification-empty strong {
            color: var(--text-dark);
            font-size: 0.92rem;
        }

        /* ===== MOBILE MENU ===== */
        .mobile-menu-btn {
            display: none;
            background: rgba(243, 187, 103, 0.2);
            border: 1px solid var(--accent);
            font-size: 1.3rem;
            color: var(--text-dark);
            cursor: pointer;
            z-index: 9999999999999;
            position: relative;
            padding: 0.6rem 0.8rem;
            border-radius: 8px;
        }

        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            width: 50%;
            max-width: 220px;
            height: 100vh;
            background: var(--gradient-dark);
            z-index: 999999999999;
            transition: left 0.3s ease;
            overflow-y: auto;
        }

        .mobile-sidebar.active {
            left: 0 !important;
        }

        .mobile-sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999999999998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-sidebar-overlay.active {
            opacity: 1 !important;
            visibility: visible !important;
        }

        .mobile-sidebar-header {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .mobile-sidebar-header img {
            height: 45px;
            margin-bottom: 0.75rem;
        }
        .mobile-sidebar-header h3 {
            font-size: 1rem;
        }

        .mobile-sidebar-menu {
            list-style: none;
            padding: 1.5rem 0;
        }

        .mobile-sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.6rem 1.25rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.75rem;
            transition: all 0.3s;
        }

        .mobile-sidebar-menu li a:hover,
        .mobile-sidebar-menu li a.active {
            background: rgba(255,255,255,0.1);
            color: var(--accent);
        }

        .mobile-sidebar-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
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
            padding: 3rem 0 1.5rem;
            margin-top: 3rem;
            position: relative;
            overflow: hidden;
            border-top: 1px solid rgba(243, 187, 103, 0.1);
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: flex-start;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .footer-left-group {
            display: flex;
            gap: 3rem;
            align-items: flex-start;
            padding-left: 1rem;
        }

        .footer h5 {
            color: var(--white);
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-family: 'Poppins', sans-serif;
        }

        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 0.1rem; }
        .footer-links a {
            color: rgba(237, 228, 219, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 400;
            line-height: 1.3;
        }
        .footer-links a:hover { 
            color: var(--accent);
            padding-left: 3px;
        }

        .footer-admin-links { list-style: none; }
        .footer-admin-links li { margin-bottom: 0.1rem; }
        .footer-admin-links a {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            color: rgba(237, 228, 219, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 400;
        }
        .footer-admin-links i {
            font-size: 0.9rem;
            color: var(--accent);
        }
        .footer-admin-links a:hover { color: var(--accent); }

        .footer-brand-center {
            text-align: center;
            padding: 0 1rem;
            margin-top: 0.5rem;
        }
        .footer-logo img {
             height: 85px;
             width: auto;
             object-fit: contain;
             margin-bottom: 0.9rem;
             filter: drop-shadow(0 0 1px rgba(255,255,255,0.7))
                     drop-shadow(0 0 1px rgba(255,255,255,0.7));
             opacity: 0.95;
         }
         .footer-brand-center h4 {
             font-family: 'Playfair Display', serif;
             font-size: 1.4rem;
             color: var(--white);
             margin-bottom: 0.6rem;
             font-weight: 700;
             letter-spacing: 0.03em;
             text-shadow: 0 3px 6px rgba(0,0,0,0.4);
         }
         .footer-brand-center p {
             color: rgba(255, 255, 255, 0.85);
             line-height: 1.5;
             font-size: 0.95rem;
             max-width: 300px;
             margin: 0 auto 0.7rem;
             font-style: italic;
             font-weight: 300;
         }

         .social-links { display: flex; gap: 0.5rem; justify-content: center; }
         .social-links a {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            color: var(--primary-dark) !important;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(243, 187, 103, 0.3);
        }
         .social-links a:hover {
            background: #fff;
            color: var(--primary) !important;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.25);
        }

        .footer-address-section {
            text-align: right;
            padding-right: 1.5rem;
        }
        .footer-address {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.4rem;
        }
        .footer-address p {
            color: rgba(237, 228, 219, 0.9);
            font-size: 0.9rem;
            line-height: 1.5;
            max-width: 200px;
            font-weight: 400;
            text-align: right;
        }
        .footer-address i {
            font-size: 0.95rem;
            color: var(--accent);
            margin-top: 3px;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 1rem;
            padding-top: 0.7rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(237, 228, 219, 0.6);
            font-size: 0.85rem;
            position: relative;
            z-index: 1;
            letter-spacing: 0.02em;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @media (max-width: 1024px) {
            .footer-container { grid-template-columns: 1fr 1fr 1fr; }
            .header-container, .nav-container { padding: 0 1.5rem; }
            .header-nav { gap: 1.25rem; }
            
            /* Tampilkan mobile menu di 1024px juga */
            .header-left { display: flex; align-items: center; }
            .mobile-menu-btn { display: flex; align-items: center; justify-content: center; }
            .header-nav { display: none; }
            .nav-links, .nav-search { display: none; }
            
            /* Perkecil teks profil di 1024px */
            .profile-dropdown-btn .profile-text span:first-child { font-size: 0.55rem !important; }
            .profile-dropdown-btn .profile-text span:last-child { font-size: 0.65rem !important; }
            .profile-dropdown-btn img, .profile-dropdown-btn .profile-initial { width: 30px; height: 30px; }
            .profile-dropdown-btn { padding: 0.4rem 0.6rem; gap: 0.3rem; }
            
            /* Perkecil tombol aksi */
            .header-action-btn { width: 32px; height: 32px; font-size: 0.9rem; }
            .notification-btn { width: 32px; height: 32px; font-size: 0.9rem; }
            .notification-menu {
                position: fixed;
                top: 7.75rem;
                right: 1.5rem;
                left: auto;
                width: min(360px, calc(100vw - 3rem));
                max-height: min(420px, calc(100vh - 9rem));
            }
            .header-right { gap: 0.35rem; }
        }
        @media (max-width: 768px) {
            .header-container { grid-template-columns: auto 1fr auto; padding: 0 1rem; max-width: 100%; }
            .header-left { display: flex; align-items: center; }
            .mobile-menu-btn { display: flex; align-items: center; justify-content: center; }
            .header-nav { display: none; }
            .nav-links, .nav-search { display: none; }
            .footer-container { grid-template-columns: 1fr 1fr 1fr; gap: 0.5rem; padding: 0 1rem; }
            .footer-bottom { flex-direction: column; gap: 0.8rem; text-align: center; }
            .footer { border-top-left-radius: 32px; border-top-right-radius: 32px; padding: 1.5rem 0 1rem; }
            .footer-left-group { flex-direction: column; gap: 0.8rem; }
            .footer-brand-center { padding: 0; order: 0; margin-top: 0.5rem; }
            .footer-address-section { text-align: right; margin-top: 0; padding-right: 1rem; }
            .footer-address { justify-content: flex-end; align-items: center; }
            .footer-address p { font-size: 0.65rem; max-width: 120px; text-align: right; line-height: 1.3; }
            .footer-logo img { height: 40px; margin-bottom: 0.3rem; }
            .footer-brand-center h4 { font-size: 1rem; margin-bottom: 0.15rem; }
            .footer-brand-center p { font-size: 0.65rem; margin-bottom: 0.3rem; line-height: 1.2; }
            .footer h5 { text-align: left; font-size: 0.65rem; margin-bottom: 0.3rem; }
            .footer-links, .footer-admin-links { text-align: left; }
            .footer-links li, .footer-admin-links li { margin-bottom: 0.15rem; }
            .footer-links a, .footer-admin-links a { font-size: 0.65rem; }
            .social-links { gap: 0.4rem; margin-bottom: 0.3rem; }
            .social-links a { width: 28px; height: 28px; font-size: 0.75rem; }
            .footer-bottom { margin-top: 1rem; padding-top: 0.7rem; }
            .header-center { margin-left: 0.5rem; }
            .header-center .logo { height: 50px; }
            .profile-dropdown-btn .profile-text { display: flex; flex-direction: column; gap: 0.05rem; }
            .profile-dropdown-btn .profile-text span:first-child { font-size: 0.5rem !important; }
            .profile-dropdown-btn .profile-text span:last-child { font-size: 0.6rem !important; }
            .profile-dropdown-btn { padding: 0.4rem 0.5rem; gap: 0.25rem; }
            .profile-dropdown-btn img, .profile-dropdown-btn .profile-initial { width: 28px; height: 28px; }
            .header-action-btn { width: 30px; height: 30px; font-size: 0.85rem; }
            .notification-btn { width: 30px; height: 30px; font-size: 0.85rem; }
            .notification-menu {
                position: fixed;
                top: 5.75rem;
                right: 1rem;
                left: 1rem;
                width: auto;
                max-height: min(420px, calc(100vh - 7rem));
            }
            .header-right { gap: 0.25rem; }

            /* Mobile sidebar even smaller */
            .mobile-sidebar { width: 50% !important; max-width: 220px !important; }
            .mobile-sidebar-menu li a { font-size: 0.7rem; padding: 0.5rem 1rem; gap: 0.5rem; }
            .mobile-sidebar-header { padding: 1rem; }
            .mobile-sidebar-header img { height: 35px; margin-bottom: 0.5rem; }
            .mobile-sidebar-header h3 { font-size: 0.85rem !important; margin-top: 0.5rem !important; }
        }

        /* Tablet & mobile only: responsive safety layer */
        @media (max-width: 1024px) {
            body { font-size: 0.9rem; }
            .container {
                width: 100%;
                max-width: 100% !important;
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            main,
            section,
            article,
            .card,
            .card-body {
                max-width: 100%;
            }
            
            .card { margin-bottom: 1rem !important; border-radius: 12px !important; }
            .card-body { padding: 1rem !important; }
            .card-header { padding: 1rem !important; }
            .page-header { padding: 1.5rem 0 !important; }

            img,
            video,
            canvas,
            svg {
                max-width: 100%;
            }

            .mobile-sidebar {
                width: min(55vw, 220px) !important;
                max-width: 220px !important;
            }
        }

        @media (max-width: 640px) {
            .header-main {
                padding: 0.8rem 0;
            }

            .header-container {
                padding: 0 0.75rem;
                gap: 0.4rem;
            }

            .mobile-menu-btn {
                min-width: 38px;
                min-height: 38px;
                padding: 0.45rem 0.6rem;
                font-size: 1rem;
            }

            .header-center {
                justify-self: center;
                margin-left: 0;
            }

            .header-center .logo {
                height: 44px;
            }

            .header-right > div {
                gap: 0.25rem !important;
            }

            .profile-dropdown-btn .profile-text,
            .profile-dropdown-btn .profile-arrow {
                display: none;
            }

            .profile-dropdown-btn {
                padding: 0.25rem;
            }

            .profile-dropdown-menu {
                right: -0.5rem;
                min-width: 170px;
            }

            .header-action-btn,
            .notification-btn {
                width: 30px;
                height: 30px;
                flex: 0 0 30px;
            }

            .notification-menu {
                top: 4.75rem;
                right: 0.75rem;
                left: 0.75rem;
                width: auto;
                max-height: calc(100vh - 5.75rem);
                border-radius: 14px;
            }

            .notification-menu-header {
                padding: 0.85rem 1rem;
            }

            .notification-list {
                padding: 0.25rem;
            }

            .notification-item {
                gap: 0.65rem;
                padding: 0.7rem;
            }

            .notification-copy strong {
                font-size: 0.78rem;
            }

            .notification-copy small {
                font-size: 0.68rem;
            }

            .mobile-sidebar {
                width: min(60vw, 240px) !important;
                max-width: 240px !important;
            }

            .container {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .footer {
                padding: 2rem 0 1rem;
                border-top-left-radius: 24px;
                border-top-right-radius: 24px;
            }

            .footer-container {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 0 1.25rem;
                text-align: center;
            }

            .footer-left-group {
                flex-direction: row;
                justify-content: center;
                gap: 2rem;
                padding-left: 0;
            }

            .footer h5,
            .footer-links,
            .footer-admin-links,
            .footer-address-section,
            .footer-address p {
                text-align: center;
            }

            .footer-admin-links a {
                justify-content: center;
            }

            .footer-brand-center {
                order: -1;
                margin-top: 0;
            }

            .footer-logo img {
                height: 58px;
                margin-left: auto;
                margin-right: auto;
            }

            .footer-brand-center p {
                max-width: 320px;
                font-size: 0.78rem;
            }

            .footer-address-section {
                padding-right: 0;
            }

            .footer-address {
                justify-content: center;
            }
        }

        @media (max-width: 420px) {
            .footer-left-group {
                flex-direction: column;
                gap: 1rem;
            }

            .notification-menu {
                top: 4.6rem;
                right: 0.65rem;
                left: 0.65rem;
            }
        }

        /* Common Page Components */
        .card { background: var(--surface-soft); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 1.5rem; }
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

        /* ===== SCROLL ANIMATIONS (SMOOTH BLUR) ===== */
        @keyframes pageEntrance {
            from {
                opacity: 0;
                filter: blur(20px);
                transform: translateY(20px) scale(0.99);
            }
            to {
                opacity: 1;
                filter: blur(0);
                transform: translateY(0) scale(1);
            }
        }

        .reveal {
            position: relative;
            opacity: 0;
            transition: transform 1.35s cubic-bezier(0.16, 1, 0.3, 1),
                        opacity 1.35s cubic-bezier(0.16, 1, 0.3, 1),
                        filter 1.35s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, opacity, filter;
            filter: blur(20px);
        }

        .reveal.active {
            opacity: 1;
            filter: blur(0);
            transform: translate3d(0, 0, 0) scale(1);
        }

        .reveal.reveal-slower {
            transition: transform 1.9s cubic-bezier(0.16, 1, 0.3, 1),
                        opacity 1.9s cubic-bezier(0.16, 1, 0.3, 1);
            filter: none;
        }

        .fade-bottom {
            transform: translateY(28px) scale(0.99);
        }

        .fade-top {
            transform: translateY(-28px) scale(0.99);
        }

        .fade-left {
            transform: translateX(-28px) scale(0.99);
        }

        .fade-right {
            transform: translateX(28px) scale(0.99);
        }

        .zoom-in {
            transform: scale(0.96);
        }

        .zoom-in.active {
            transform: translate3d(0, 0, 0) scale(1);
        }

        .reveal.active.delay-100 { transition-delay: 180ms; }
        .reveal.active.delay-200 { transition-delay: 320ms; }
        .reveal.active.delay-300 { transition-delay: 460ms; }
        .reveal.active.delay-400 { transition-delay: 600ms; }
        .reveal.active.delay-500 { transition-delay: 740ms; }
        .reveal.active.delay-600 { transition-delay: 880ms; }
        
        /* Dropdown Styles */
        .profile-dropdown {
            position: relative;
        }
        
        .profile-dropdown-btn {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            cursor: pointer;
            padding: 0.4rem 0.6rem;
            border-radius: 12px;
            transition: 0.3s;
            background: transparent;
            border: none;
            text-decoration: none;
        }
        
        .profile-dropdown-btn:hover {
            background: rgba(122, 75, 34, 0.05);
        }
        
        .profile-dropdown-btn img {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }
        
        .profile-dropdown-btn .profile-initial {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--gradient-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.85rem;
        }
        
        .profile-dropdown-btn .profile-text {
            display: flex;
            flex-direction: column;
            text-align: left;
        }
        
        .profile-dropdown-btn .profile-text span:first-child {
            font-size: 0.55rem;
            color: var(--text-light);
            font-weight: 600;
        }
        
        .profile-dropdown-btn .profile-text span:last-child {
            font-size: 0.65rem;
            color: var(--text-dark);
            font-weight: 700;
        }
        
        .profile-dropdown-btn .profile-arrow {
            color: var(--text-light);
            transition: 0.3s;
            font-size: 0.75rem;
        }
        
        .profile-dropdown.active .profile-arrow {
            transform: rotate(180deg);
        }
        
        .profile-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            min-width: 180px;
            display: none;
            z-index: 99999;
            overflow: hidden;
        }
        
        .profile-dropdown.active .profile-dropdown-menu {
            display: block;
            animation: slideDown 0.2s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .profile-dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            border-bottom: 1px solid rgba(122, 75, 34, 0.1);
        }
        
        .profile-dropdown-menu a:last-child {
            border-bottom: none;
        }
        
        .profile-dropdown-menu a:hover {
            background: rgba(122, 75, 34, 0.05);
        }
        
        .profile-dropdown-menu a i {
            color: var(--primary);
        }
        
        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            body {
                animation: none;
            }

            .reveal {
                opacity: 1;
                filter: none;
                transform: none;
                transition: none;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Main Header -->
    <header id="sticky-header" class="header-main">
        <div class="header-container">
            <div class="header-left">
                <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="header-center">
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('images/Logo.png') }}" alt="Mondial Bakery">
                </a>
            </div>
            <div class="header-nav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('katalog') }}" class="{{ request()->routeIs('katalog') ? 'active' : '' }}">Katalog Produk</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Tentang Kami</a>
                @if(!auth()->check() || auth()->user()->isCustomer())
                    <a href="{{ route('customer.pesanan') }}" class="{{ request()->routeIs('customer.pesanan*') ? 'active' : '' }}">Pesanan</a>
                @endif
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Hubungi Kami</a>
            </div>
            <div class="header-right">
                @auth
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
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

                        @include('partials.header-notifications', ['notificationDropdownId' => 'customerHeaderNotifications'])
                        
                        <a href="{{ route('katalog') }}" class="header-action-btn" title="Cari">
                            <i class="fas fa-search"></i>
                        </a>
                        
                        @if(auth()->user()->isCustomer())
                            <a href="{{ route('customer.keranjang') }}" class="header-action-btn" title="Keranjang Belanja">
                                <i class="fas fa-shopping-basket"></i>
                                @php $cartCount = \App\Models\Keranjang::where('user_id', auth()->id())->count(); @endphp
                                <span class="badge">{{ $cartCount ?? 0 }}</span>
                            </a>
                        @endif
                        
                        <div class="profile-dropdown" id="profileDropdownCustomer">
                            <button class="profile-dropdown-btn" onclick="toggleDropdownCustomer()">
                                @if(auth()->user()->foto)
                                    <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Profile">
                                @else
                                    <div class="profile-initial">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="profile-text">
                                    <span>{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</span>
                                    <span>{{ auth()->user()->name }}</span>
                                </div>
                                <i class="fas fa-chevron-down profile-arrow"></i>
                            </button>
                            
                            <div class="profile-dropdown-menu">
                                <a href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit"></i>
                                    Edit Profil
                                </a>
                                <form action="{{ route('logout') }}" method="POST" style="display: none;" id="logoutFormCustomer">
                                    @csrf
                                </form>
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logoutFormCustomer').submit();">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <a href="{{ route('login') }}" class="header-action-btn" title="Masuk / Daftar" style="display: flex; align-items: center; gap: 0.3rem; text-decoration: none; width: auto; padding: 0.4rem 0.9rem; border-radius: 12px; background: var(--gradient-gold); color: white; font-size: 0.78rem; font-weight: 600;">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Masuk</span>
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Mobile Sidebar -->
    <div class="mobile-sidebar-overlay" id="mobileSidebarOverlay" onclick="toggleMobileMenu()"></div>
    <div class="mobile-sidebar" id="mobileSidebar">
        <button class="mobile-sidebar-close" onclick="toggleMobileMenu()">
            <i class="fas fa-times"></i>
        </button>
        <div class="mobile-sidebar-header">
            <img src="{{ asset('images/Logo.png') }}" alt="Mondial Bakery">
            <h3 style="color: #f3bb67; margin-top: 10px;">Mondial Bakery</h3>
        </div>
        <ul class="mobile-sidebar-menu">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fas fa-home"></i> Beranda</a></li>
            <li><a href="{{ route('katalog') }}" class="{{ request()->routeIs('katalog') ? 'active' : '' }}"><i class="fas fa-box"></i> Katalog Produk</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}"><i class="fas fa-info-circle"></i> Tentang Kami</a></li>
            @if(!auth()->check() || auth()->user()->isCustomer())
                <li><a href="{{ route('customer.pesanan') }}" class="{{ request()->routeIs('customer.pesanan*') ? 'active' : '' }}"><i class="fas fa-shopping-cart"></i> Pesanan</a></li>
            @endif
            <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}"><i class="fas fa-envelope"></i> Hubungi Kami</a></li>
            
            @auth
                @if(auth()->user()->isAdmin())
                    <li style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 10px; padding-top: 10px;">
                        <a href="{{ match(auth()->user()->role) {
                            'owner' => route('owner.dashboard'),
                            'admin_gudang' => route('gudang.dashboard'),
                            'admin_penjualan' => route('penjualan.dashboard'),
                            'admin_produksi' => route('produksi.dashboard'),
                        } }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                        </a>
                    </li>
                @endif
                
                <li><a href="{{ route('profile.edit') }}"><i class="fas fa-user"></i> Edit Profil</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: none;" id="logoutFormMobile">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logoutFormMobile').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            @else
                <li style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 10px; padding-top: 10px;">
                    <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Masuk / Daftar</a>
                </li>
            @endauth
        </ul>
    </div>

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('katalog') }}" class="{{ request()->routeIs('katalog') ? 'active' : '' }}">Katalog Produk</a></li>
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
                    <a href="https://www.instagram.com/mondialbakery?igsh=aWhpMXA4djFwdDNh" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
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
        function toggleMobileMenu() {
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('mobileSidebarOverlay');
            
            if (sidebar) {
                sidebar.classList.toggle('active');
            }
            
            if (overlay) {
                overlay.classList.toggle('active');
            }
        }

        function toggleDropdownCustomer() {
            const dropdown = document.getElementById('profileDropdownCustomer');
            dropdown.classList.toggle('active');
        }

        function toggleHeaderNotifications(id) {
            const dropdown = document.getElementById(id);
            if (!dropdown) return;

            document.querySelectorAll('.notification-dropdown.active').forEach(openDropdown => {
                if (openDropdown !== dropdown) {
                    openDropdown.classList.remove('active');
                }
            });

            dropdown.classList.toggle('active');
            
            if (dropdown.classList.contains('active')) {
                markHeaderNotificationsAsRead(dropdown);
            }
        }

        function markHeaderNotificationsAsRead(dropdown) {
            const badge = dropdown.querySelector('.notification-badge');
            if (!badge) return;

            badge.remove();

            const headerInfo = dropdown.querySelector('.notification-menu-header span');
            if (headerInfo) {
                headerInfo.textContent = 'Semua sudah dibaca';
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const readUrl = dropdown.dataset.readUrl;

            if (!token || !readUrl) return;

            fetch(readUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).catch(() => {});
        }
        
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdownCustomer');
            if (dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }

            document.querySelectorAll('.notification-dropdown.active').forEach(openDropdown => {
                if (!openDropdown.contains(event.target)) {
                    openDropdown.classList.remove('active');
                }
            });
        });
        
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
                } else {
                    entry.target.classList.remove("active");
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin Mondial Bakery</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        :root {
            --primary: #7a4b22;
            --primary-light: #c7834d;
            --primary-dark: #512c16;
            --secondary: #fff4e7;
            --accent: #f3bb67;
            --bg-body: #fdfbfc;
            --text-dark: #1B1B18;
            --text-medium: #5C4033;
            --text-light: #8B7355;
            --white: #FFFFFF;
            --border: rgba(122, 75, 34, 0.1);
            --radius-lg: 24px;
            --sidebar-w: 260px;
            --gradient-gold: linear-gradient(135deg, #7a4b22 0%, #c7834d 50%, #f3bb67 100%);
            --gradient-sidebar: linear-gradient(180deg, #2c1810 0%, #1a0f0a 100%);
        }
        
        /* Dropdown Styles */
        .profile-dropdown {
            position: relative;
        }
        
        .profile-dropdown-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.5rem 0.75rem;
            border-radius: 12px;
            transition: 0.3s;
            background: transparent;
            border: none;
        }
        
        .profile-dropdown-btn:hover {
            background: rgba(122, 75, 34, 0.05);
        }
        
        .profile-dropdown-btn img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }
        
        .profile-dropdown-btn .profile-initial {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
        }
        
        .profile-dropdown-btn .profile-text {
            display: flex;
            flex-direction: column;
            text-align: left;
        }
        
        .profile-dropdown-btn .profile-text span:first-child {
            font-size: 0.8rem;
            color: var(--text-light);
            font-weight: 600;
        }
        
        .profile-dropdown-btn .profile-text span:last-child {
            font-size: 0.9rem;
            color: var(--text-dark);
            font-weight: 700;
        }
        
        .profile-dropdown-btn .profile-arrow {
            color: var(--text-light);
            transition: 0.3s;
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
            min-width: 200px;
            display: none;
            z-index: 1000;
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
            padding: 0.875rem 1.25rem;
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            border-bottom: 1px solid var(--border);
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

        .notification-dropdown {
            position: relative;
        }

        .notification-btn {
            position: relative;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: #fff;
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
            background: var(--secondary);
            transform: translateY(-2px);
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            border-radius: 999px;
            background: #ef4444;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.68rem;
            font-weight: 800;
            border: 2px solid #fff;
        }

        .notification-menu {
            position: absolute;
            top: calc(100% + 0.65rem);
            right: 0;
            width: min(360px, calc(100vw - 2rem));
            max-height: 430px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 18px 50px rgba(0,0,0,0.16);
            display: none;
            z-index: 1200;
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
            background: #fff4e7;
        }

        .notification-menu-header strong {
            display: block;
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .notification-menu-header span {
            display: block;
            font-size: 0.78rem;
            color: var(--text-light);
            font-weight: 700;
        }

        .notification-list {
            padding: 0.35rem;
        }

        .notification-item {
            display: flex;
            gap: 0.8rem;
            padding: 0.85rem;
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
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 34px;
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
            font-size: 0.88rem;
            line-height: 1.25;
        }

        .notification-copy small {
            color: var(--text-medium);
            font-size: 0.78rem;
            line-height: 1.35;
        }

        .notification-copy em {
            color: var(--text-light);
            font-size: 0.72rem;
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
            font-size: 0.95rem;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Nunito', sans-serif; 
            background: var(--bg-body); 
            color: var(--text-dark); 
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-size: 15px;
            line-height: 1.6;
        }

        .admin-wrapper {
            transform: scale(0.9);
            transform-origin: top left;
            width: calc(100% / 0.9);
            height: calc(100vh / 0.9);
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--gradient-sidebar);
            color: #fff;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 10px 0 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .sidebar-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            display: none;
        }

        .sidebar-header {
            padding: 3rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-header img { 
            height: 75px; 
            margin-bottom: 1rem;
            filter: drop-shadow(0 0 1px rgba(255,255,255,0.8)) 
                    drop-shadow(0 0 1px rgba(255,255,255,0.8))
                    drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        }

        .sidebar-header h3 {
            color: #f3bb67;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
            font-family: 'Raleway', sans-serif;
        }

        .sidebar-header small { 
            color: #f3bb67;
            font-size: 0.75rem; 
            text-transform: uppercase;
            letter-spacing: 0.15em;
            font-weight: 700;
            opacity: 0.8;
        }

        .sidebar-menu { list-style: none; padding: 2rem 0; }
        
        .sidebar-divider {
            padding: 1.5rem 2rem 0.5rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: #f3bb67;
            font-weight: 800;
            opacity: 0.4;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1rem 2rem;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .sidebar-menu li a:hover {
            color: #fff;
            background: rgba(255,255,255,0.05);
        }

        .sidebar-menu li a.active {
            background: linear-gradient(90deg, #c7834d 0%, #f3bb67 100%);
            color: #fff;
            margin: 0.2rem 1rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(199, 131, 77, 0.3);
        }

        .sidebar-menu li a i { width: 24px; text-align: center; }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            width: calc(100vw - var(--sidebar-w));
            display: flex;
            flex-direction: column;
            background: #fff;
            overflow-x: hidden;
            position: relative;
        }

        .main-content::before {
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

        .topbar {
            height: 70px;
            background: #fff;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .topbar h2 { 
            font-size: 1.55rem; 
            font-family: 'Raleway', sans-serif;
            font-weight: 800;
        }

        .topbar-actions { display: flex; align-items: center; gap: 1.5rem; }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1.25rem;
            background: #fff4e7;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            border: 1px solid rgba(122, 75, 34, 0.1);
        }

        .btn-logout {
            background: #fff;
            color: #ef4444;
            border: 1px solid #fee2e2;
            padding: 0.6rem 1.25rem;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-logout:hover { background: #fee2e2; }

        .content-area { 
            padding: 0.5rem 1rem; 
            flex: 1;
            width: 100%;
            overflow-y: auto;
            overflow-x: hidden;
            position: relative;
            z-index: 1;
        }

        /* Compact Stat Cards */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            border: 1px solid var(--border);
            display: flex; 
            align-items: center;
            gap: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.02);
            transition: transform 0.3s;
        }

        .stat-card:hover { transform: translateY(-5px); }

        .stat-icon {
            width: 50px; height: 50px;
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            color: #fff;
        }

        .stat-icon.blue { background: var(--primary); }
        .stat-icon.green { background: var(--primary-light); }
        .stat-icon.orange { background: var(--accent); }
        .stat-icon.purple { background: var(--primary-dark); }
        .stat-icon.red { background: #c0392b; }

        .stat-info h4 { 
            font-size: 0.9rem; 
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.2rem;
        }

        .stat-info p { font-size: 1.5rem; font-weight: 800; }

        /* Generic Card */
        .card {
            background: #ffffff !important;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 2.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.02);
            position: relative;
            z-index: 2;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            background: #fafafa;
            border-radius: calc(var(--radius-lg) - 1px) calc(var(--radius-lg) - 1px) 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h3 { font-size: 1.4rem; font-weight: 800; font-family: 'Raleway', sans-serif; }

        .card-body { padding: 2.5rem; }

        /* Buttons Small & Spaced */
        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.75rem 1.5rem; border-radius: 12px;
            font-weight: 700; text-decoration: none; border: none;
            cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.9rem;
            margin: 0.5rem 0.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Khusus untuk tombol aksi utama di atas tabel */
        .action-header {
            margin-bottom: 2rem;
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.25rem;
            background: #fff;
            padding: 1.25rem;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .action-header .btn {
            margin: 0; /* Reset margin karena sudah diatur oleh gap di action-header */
        }

        /* Table Responsive */
        .table-responsive,
        .table-container,
        .admin-table-scroll {
            width: 100%;
            max-width: 100%;
            display: block;
            overflow-x: auto !important;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            overscroll-behavior-x: contain;
            background: #fff;
            border-radius: var(--radius-lg);
        }

        .table-responsive > table,
        .table-container > table,
        .admin-table-scroll > table {
            width: max-content;
            min-width: max(760px, 100%);
            max-width: none;
        }

        table { width: 100%; border-collapse: collapse; table-layout: auto; }
        table th {
            background: #fff4e7;
            padding: 1rem 1rem;
            text-align: left;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--primary-dark);
            border-bottom: 2px solid var(--border);
            white-space: nowrap;
        }
        table td { 
            padding: 1rem 1rem; 
            border-bottom: 1px solid var(--border); 
            font-size: 0.95rem;
        }
        .table-responsive th,
        .table-responsive td,
        .table-container th,
        .table-container td,
        .admin-table-scroll th,
        .admin-table-scroll td {
            white-space: nowrap;
        }
        table tr:last-child td { border-bottom: none; }
        .btn-primary { background: var(--gradient-gold); color: #fff; }
        .btn-secondary { background: #f3f4f6; color: var(--text-dark); }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.9rem; }

        .badge {
            padding: 0.45rem 1.1rem;
            border-radius: 100px;
            font-size: 0.875rem;
            font-weight: 700;
            display: inline-block;
            text-align: center;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        .badge-primary { background: #fff4e7; color: var(--primary); }
        .badge-info { background: #eff6ff; color: #1e40af; }
        .text-center { text-align: center; }

        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }

        .align-center { align-items: center !important; }
        .mb-2 { margin-bottom: 1rem !important; }
        .mb-1 { margin-bottom: 0.5rem !important; }

        .gap-1 { gap: 1rem !important; }
        .gap-2 { gap: 1.5rem !important; }
        .d-flex { display: flex !important; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .sidebar-close { display: block; }
            .main-content { margin-left: 0; width: 100vw; }
            .stat-grid { grid-template-columns: 1fr 1fr; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            .mobile-menu-btn { display: block !important; }
        }
        
        .form-label { display: block; margin-bottom: 0.75rem; font-weight: 600; color: var(--text-dark); font-size: 1rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-control {
            width: 100%; padding: 0.85rem 1.25rem; border: 1.5px solid var(--border);
            border-radius: 12px; font-family: 'Nunito', sans-serif; font-size: 0.95rem;
            background: #F9FAFB; transition: all 0.3s;
        }
        .form-control:focus { 
            outline: none; 
            border-color: var(--primary); 
            background: white;
            box-shadow: 0 0 0 4px rgba(139,105,20,0.1); 
        }

        .alert { 
            padding: 1.25rem 1.75rem; 
            border-radius: 16px; 
            margin-bottom: 2rem; 
            display: flex; 
            align-items: center; 
            gap: 1rem; 
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid transparent;
            animation: slideInDown 0.4s ease-out;
        }
        @keyframes slideInDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .alert-success { background: #ECFDF5; color: #065F46; border-color: #A7F3D0; }
        .alert-danger { background: #FEF2F2; color: #991B1B; border-color: #FEE2E2; }
        .alert-warning { background: #FFFBEB; color: #92400E; border-color: #FEF3C7; }

        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; }

        .mt-4 { margin-top: 2rem !important; }
        .mt-3 { margin-top: 1.5rem !important; }
        .mb-4 { margin-bottom: 2rem !important; }
        .mb-3 { margin-bottom: 1.5rem !important; }

        @media (max-width: 1024px) {
            .sidebar { width: 0; overflow: hidden; box-shadow: none; }
            .main-content { margin-left: 0; }
            .stat-grid { grid-template-columns: 1fr 1fr; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
        }

        /* Tablet & mobile only: keep desktop layout untouched */
        @media (max-width: 1024px) {
            body {
                overflow: visible;
                font-size: 0.85rem;
            }

            .admin-wrapper {
                transform: none;
                width: 100vw;
                height: auto;
                min-height: 100vh;
            }

            .sidebar {
                width: min(82vw, 300px);
                overflow-y: auto;
                box-shadow: 10px 0 30px rgba(0,0,0,0.18);
                transform: translateX(-100%);
                z-index: 1001;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                width: 100vw;
                min-width: 0;
                height: auto;
                min-height: 100vh;
                overflow: visible;
            }

            .topbar {
                height: auto;
                min-height: 56px;
                padding: 0.5rem 0.75rem;
                gap: 0.5rem;
            }

            .topbar h2 {
                min-width: 0;
                font-size: clamp(0.95rem, 3.2vw, 1.15rem);
                line-height: 1.2;
                overflow-wrap: anywhere;
            }

            .topbar-actions {
                gap: 0.5rem;
                flex-shrink: 0;
            }

            .content-area {
                padding: 0.5rem;
                overflow-x: hidden;
                overflow-y: visible;
                -webkit-overflow-scrolling: touch;
            }

            .card {
                border-radius: 12px;
                margin-bottom: 1rem;
            }

            .card-header {
                padding: 0.75rem 1rem;
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            .card-header h3 {
                font-size: 1rem;
            }

            .card-body {
                padding: 0.75rem 1rem;
            }

            .action-header,
            .d-flex {
                min-width: 0;
            }

            .action-header {
                padding: 0.75rem 0.5rem;
                gap: 0.5rem;
            }

            .table-responsive,
            .table-container,
            .admin-table-scroll {
                width: 100%;
                max-width: 100%;
                display: block;
                overflow-x: auto !important;
                overflow-y: hidden;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
                overscroll-behavior-x: contain;
            }

            .table-responsive > table,
            .table-container > table,
            .admin-table-scroll > table {
                width: max-content;
                min-width: max(760px, 100%);
                max-width: none;
            }

            .table-responsive th,
            .table-responsive td,
            .table-container th,
            .table-container td,
            .admin-table-scroll th,
            .admin-table-scroll td {
                white-space: nowrap;
            }

            .table-responsive .btn,
            .table-responsive .btn-sm,
            .table-container .btn,
            .table-container .btn-sm,
            .admin-table-scroll .btn,
            .admin-table-scroll .btn-sm {
                width: auto;
                white-space: nowrap;
                margin: 0.15rem;
            }

            table {
                min-width: 760px;
            }

            canvas,
            img,
            video,
            svg {
                max-width: 100%;
            }
        }

        @media (max-width: 640px) {
            .topbar {
                align-items: flex-start;
            }

            .topbar-actions {
                gap: 0.5rem;
            }

            .profile-dropdown-btn {
                padding: 0.35rem;
            }

            .profile-dropdown-btn .profile-text,
            .profile-dropdown-btn .profile-arrow,
            .topbar-user {
                display: none;
            }

            .notification-menu {
                right: -3.25rem;
                width: min(320px, calc(100vw - 1rem));
            }

            .content-area {
                padding: 0.75rem;
            }

            .stat-grid {
                grid-template-columns: 1fr;
                gap: 0.85rem;
            }

            .stat-card {
                padding: 1rem;
                border-radius: 16px;
            }

            .stat-info h4 {
                font-size: 0.78rem;
                line-height: 1.35;
            }

            .stat-info p {
                font-size: 1.15rem;
                line-height: 1.25;
                overflow-wrap: anywhere;
            }

            .card-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .card-body {
                padding: 1rem;
            }

            .btn,
            .btn-sm {
                width: 100%;
                justify-content: center;
                margin: 0.25rem 0;
                white-space: normal;
            }

            .action-header,
            .d-flex {
                flex-direction: column;
                align-items: stretch !important;
            }

            [style*="grid-template-columns: 1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }

            .form-group,
            .form-control,
            input.form-control,
            select.form-control,
            textarea.form-control {
                width: 100% !important;
                min-width: 0 !important;
            }

            table {
                min-width: 620px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="admin-wrapper">
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
        <aside class="sidebar" id="adminSidebar">
            <button class="sidebar-close" onclick="toggleSidebar()">
                <i class="fas fa-times"></i>
            </button>
            <div class="sidebar-header">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo" onerror="this.style.display='none'">
                <h3>Mondial Bakery</h3>
                <small>{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</small>
            </div>
            <ul class="sidebar-menu">
                @yield('sidebar-menu')
                @if(auth()->user()->role === 'owner' && !request()->is('owner*'))
                    <div class="sidebar-divider">Akses Owner</div>
                    <li><a href="{{ route('owner.dashboard') }}"><i class="fas fa-arrow-left"></i> Kembali ke Owner</a></li>
                @endif
                <div class="sidebar-divider">Akun</div>
                <li>
                    <a href="{{ route('home') }}"><i class="fas fa-store"></i> Lihat Toko</a>
                </li>
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logoutFormSidebar').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
            <form id="logoutFormSidebar" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
        </aside>

        <div class="main-content">
            <div class="topbar">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button class="mobile-menu-btn" onclick="toggleSidebar()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-dark); display: none;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2>@yield('page-title', 'Dashboard')</h2>
                </div>
                <div class="topbar-actions">
                    @include('partials.header-notifications', ['notificationDropdownId' => 'adminHeaderNotifications'])

                    <div class="profile-dropdown" id="profileDropdown">
                        <button class="profile-dropdown-btn" onclick="toggleDropdown()">
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
                            <a href="{{ route('home') }}">
                                <i class="fas fa-store"></i>
                                Lihat Toko
                            </a>
                            <form action="{{ route('logout') }}" method="POST" style="display: none;" id="logoutFormAdmin">
                                @csrf
                            </form>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutFormAdmin').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-area">
                @if(session('success'))
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script>
        function ensureAdminTableScroll() {
            document.querySelectorAll('.content-area table').forEach(table => {
                if (table.closest('.table-responsive, .table-container, .admin-table-scroll')) {
                    return;
                }

                const wrapper = document.createElement('div');
                wrapper.className = 'admin-table-scroll';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            });
        }

        document.addEventListener('DOMContentLoaded', ensureAdminTableScroll);

        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
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
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }

            document.querySelectorAll('.notification-dropdown.active').forEach(openDropdown => {
                if (!openDropdown.contains(event.target)) {
                    openDropdown.classList.remove('active');
                }
            });
        });
        
        const editableSelector = 'a[href], button, label, summary, [role="button"], [role="link"], input, textarea, select, [contenteditable="true"], [contenteditable=""], [role="textbox"]';

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
                // Cegah fokus pada elemen yang tidak bisa diedit
                event.preventDefault();
            }
        });

        document.addEventListener('mouseup', event => {
            if (!isEditableTarget(event.target)) {
                setTimeout(clearSelection, 0);
            }
        });

        document.addEventListener('click', event => {
            if (!isEditableTarget(event.target)) {
                // Hapus fokus dari elemen yang tidak bisa diedit
                if (document.activeElement && !isEditableTarget(document.activeElement)) {
                    document.activeElement.blur?.();
                }
                clearSelection();
            }
        });

        document.addEventListener('touchstart', event => {
            if (!isEditableTarget(event.target)) {
                clearSelection();
            }
        }, { passive: true });

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

        document.querySelectorAll('.alert').forEach(a => {
            setTimeout(() => { a.style.opacity='0'; setTimeout(()=>a.remove(), 300); }, 5000);
        });

        // Nonaktifkan contenteditable secara global kecuali memang dibutuhkan
        document.querySelectorAll('[contenteditable="true"], [contenteditable=""]').forEach(el => {
            if (!el.dataset.enableEditable) {
                el.contentEditable = 'false';
            }
        });
    </script>
    @stack('modals')
    @yield('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin Mondial Bakery</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            --sidebar-w: 280px;
            --gradient-gold: linear-gradient(135deg, #7a4b22 0%, #c7834d 50%, #f3bb67 100%);
            --gradient-sidebar: linear-gradient(180deg, #2c1810 0%, #1a0f0a 100%);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Poppins', sans-serif; 
            background: var(--bg-body); 
            color: var(--text-dark); 
            min-height: 100vh;
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
        }

        .sidebar-header {
            padding: 3rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-header img { 
            height: 75px; 
            margin-bottom: 1rem;
            /* Mengembalikan detail logo (mata) dan menambahkan outline putih agar tulisan gelap tetap terlihat di bg gelap */
            filter: drop-shadow(0 0 1px rgba(255,255,255,0.8)) 
                    drop-shadow(0 0 1px rgba(255,255,255,0.8))
                    drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        }

        .sidebar-header h3 {
            color: #f3bb67;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
            font-family: 'Playfair Display', serif;
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
            font-size: 0.7rem;
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
            font-size: 0.95rem;
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
            font-size: 1.6rem; 
            font-family: 'Playfair Display', serif;
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
            padding: 2rem; 
            flex: 1;
            width: 100%;
            background-image: radial-gradient(#7a4b22 0.5px, transparent 0.5px);
            background-size: 30px 30px;
            background-color: #fff;
            overflow-x: hidden;
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

        .stat-icon.blue { background: #3b82f6; }
        .stat-icon.green { background: #10b981; }
        .stat-icon.orange { background: #f59e0b; }
        .stat-icon.purple { background: #8b5cf6; }
        .stat-icon.red { background: #ef4444; }

        .stat-info h4 { 
            font-size: 0.85rem; 
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.2rem;
        }

        .stat-info p { font-size: 1.4rem; font-weight: 800; }

        /* Generic Card */
        .card {
            background: #fff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 2.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.02);
        }

        .card-header {
            padding: 1.5rem 2.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
        }

        .card-header h3 { font-size: 1.3rem; font-weight: 800; font-family: 'Playfair Display', serif; }

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

        /* Table Responsive without scroll */
        .table-container {
            width: 100%;
            overflow-x: auto;
            background: #fff;
            border-radius: var(--radius-lg);
        }

        table { width: 100%; border-collapse: collapse; table-layout: auto; }
        table th {
            background: #fff4e7;
            padding: 1rem 0.75rem;
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--primary-dark);
            border-bottom: 2px solid var(--border);
            white-space: nowrap;
        }
        table td { 
            padding: 0.85rem 0.75rem; 
            border-bottom: 1px solid var(--border); 
            font-size: 0.85rem;
        }
        table tr:last-child td { border-bottom: none; }
        .btn-primary { background: var(--gradient-gold); color: #fff; }
        .btn-secondary { background: #f3f4f6; color: var(--text-dark); }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.85rem; }

        .badge {
            padding: 0.4rem 1rem;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }

        .gap-1 { gap: 1rem !important; }
        .gap-2 { gap: 1.5rem !important; }
        .d-flex { display: flex !important; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
        }
        
        .form-label { display: block; margin-bottom: 0.75rem; font-weight: 600; color: var(--text-dark); font-size: 0.95rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-control {
            width: 100%; padding: 0.85rem 1.25rem; border: 1.5px solid var(--border);
            border-radius: 12px; font-family: 'Poppins'; font-size: 0.95rem;
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
    </style>
    @yield('styles')
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/Logo.png') }}" alt="Logo" onerror="this.style.display='none'">
            <h3>Mondial Bakery</h3>
            <small>{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</small>
        </div>
        <ul class="sidebar-menu">
            @yield('sidebar-menu')
            <div class="sidebar-divider">Akun</div>
            <li>
                <a href="{{ route('home') }}"><i class="fas fa-store"></i> Lihat Toko</a>
            </li>
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
    </aside>

    <div class="main-content">
        <div class="topbar">
            <h2>@yield('page-title', 'Dashboard')</h2>
            <div class="topbar-actions">
                <div class="topbar-user">
                    <i class="fas fa-user-circle" style="font-size:1.3rem;color:var(--accent)"></i>
                    <span>{{ auth()->user()->name }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Keluar</span>
                    </button>
                </form>
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

        document.querySelectorAll('.alert').forEach(a => {
            setTimeout(() => { a.style.opacity='0'; setTimeout(()=>a.remove(), 300); }, 5000);
        });
    </script>
    @yield('scripts')
</body>
</html>

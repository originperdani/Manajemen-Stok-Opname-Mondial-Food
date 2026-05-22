<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Mondial Bakery</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #8B6914;
            --primary-dark: #6B4F0E;
            --accent: #D4A853;
            --text-dark: #1B1B18;
            --text-medium: #5C4033;
            --text-light: #8B7355;
            --border: #E3E3E0;
        }
        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            background:
                radial-gradient(circle at top left, rgba(243, 187, 103, 0.16), transparent 30%),
                radial-gradient(circle at bottom right, rgba(139, 105, 20, 0.12), transparent 28%),
                linear-gradient(135deg, #fffaf5 0%, #fffdf9 100%);
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
            overflow: hidden;
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
        .register-container {
            display: flex;
            width: 100%;
            height: 100vh;
        }
        .register-left {
            flex: 1.2;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: linear-gradient(135deg, #1b120d 0%, #3d2416 50%, #7a4b22 100%);
            color: white;
            position: relative;
            z-index: 2;
        }
        .register-left::after {
            content: "";
            position: absolute;
            top: 0;
            right: -100px;
            width: 100px;
            height: 100%;
            background: linear-gradient(to right, #7a4b22, transparent);
            z-index: 1;
            pointer-events: none;
        }
        .register-left::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at center, rgba(243, 187, 103, 0.18) 0%, transparent 70%),
                linear-gradient(135deg, rgba(255,255,255,0.03), transparent);
            z-index: 1;
        }
        .register-left::after {
            content: "";
            position: absolute;
            width: 340px;
            height: 340px;
            top: -120px;
            right: -120px;
            border-radius: 50%;
            background: rgba(243, 187, 103, 0.12);
            filter: blur(10px);
            animation: pulseGlow 8s ease-in-out infinite;
        }
        .register-left-content {
            position: relative;
            z-index: 2;
            max-width: 500px;
            text-align: center;
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .register-left-logo {
            margin-bottom: 2rem;
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); opacity: 0.7; }
            50% { transform: scale(1.08); opacity: 1; }
        }
        .register-left-logo img {
            height: 120px;
            width: auto;
            filter: drop-shadow(0 0 2px rgba(255,255,255,0.8)) drop-shadow(0 0 15px rgba(255,255,255,0.3));
        }
        .register-left-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.75rem;
            color: var(--accent);
            margin-bottom: 1.25rem;
            line-height: 1.2;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        .register-left-content p {
            font-size: 0.95rem;
            line-height: 1.7;
            color: #D1D1CF;
            margin-bottom: 2.5rem;
            font-weight: 300;
        }
        .features {
            display: grid;
            gap: 1rem;
            text-align: left;
            max-width: 360px;
            margin: 0 auto;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            font-size: 0.85rem;
            color: #EDEDEC;
            padding: 0.65rem 1rem;
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(5px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .feature-item:hover {
            background: rgba(212, 168, 83, 0.15);
            border-color: rgba(212, 168, 83, 0.3);
            transform: translateX(8px) scale(1.02);
        }
        .feature-item i {
            color: var(--accent);
            font-size: 1rem;
        }

        .register-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: transparent;
        }
        .register-box {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(20px);
            border-radius: 32px;
            padding: 1.75rem 2rem;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 28px 60px -18px rgba(71, 37, 16, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.78);
            animation: fadeInRight 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }
        .register-box::before {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .register-box h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-dark);
            margin-bottom: 0.2rem;
            text-align: center;
        }
        .subtitle {
            color: var(--text-light);
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            text-align: center;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.85rem;
        }
        .form-group-full { grid-column: span 2; }
        
        .form-group { margin-bottom: 0.6rem; }
        .form-label {
            display: block;
            margin-bottom: 0.35rem;
            font-weight: 500;
            color: var(--text-dark);
            font-size: 0.75rem;
        }
        .input-wrapper { position: relative; }
        .input-wrapper i {
            position: absolute;
            left: 1.15rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .form-control {
            width: 100%;
            padding: 0.65rem 1rem 0.65rem 2.75rem;
            border: 1.5px solid #EDEDEC;
            border-radius: 12px;
            font-family: 'Poppins';
            font-size: 0.85rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #F9FAFB;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(139, 105, 20, 0.15), 0 4px 12px rgba(139, 105, 20, 0.08);
            transform: translateY(-1px);
        }
        .form-control:focus + i {
            color: var(--primary);
            transform: translateY(-50%) scale(1.1);
        }

        .btn-register {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-family: 'Poppins';
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            box-shadow: 0 10px 20px -5px rgba(139, 105, 20, 0.4);
            margin-top: 0.75rem;
            position: relative;
            overflow: hidden;
        }
        .btn-register::after {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            transform: scale(0);
            transition: transform 0.6s ease-out;
        }
        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(139, 105, 20, 0.5);
        }
        .btn-register:hover::after {
            transform: scale(1);
        }
        .btn-register i {
            transition: transform 0.3s;
        }
        .btn-register:hover i {
            transform: translateX(4px);
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--text-light);
        }
        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }

        .error-msg {
            background: #FEF2F2;
            color: #991B1B;
            padding: 1rem 1.25rem;
            border-radius: 14px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: 1px solid #FEE2E2;
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        @media (max-width: 1200px) {
            .register-left-content h1 { font-size: 3rem; }
        }
        @media (max-width: 1024px) {
            .register-left { flex: 1; padding: 2rem; }
            .register-left-content h1 { font-size: 2.5rem; }
            .features { max-width: 100%; }
        }
        @media (max-width: 768px) {
            body { overflow-y: auto; height: auto; }
            .register-container { height: auto; }
            .register-left { display: none; }
            .register-right { padding: 1rem; min-height: 100vh; }
            .register-box { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-left">
            <div class="register-left-content">
                <div class="register-left-logo">
                    <img src="{{ asset('images/Logo.png') }}" alt="Mondial Bakery">
                </div>
                <h1>Join the Sweetness.</h1>
                <p>Jadilah bagian dari komunitas Mondial Bakery dan nikmati kemudahan memesan roti premium langsung dari genggaman Anda.</p>
                <div class="features">
                    <div class="feature-item">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Pemesanan Online yang Mudah & Cepat</span>
                    </div>
                    <div class="feature-item">
                        <i class="fa-solid fa-gem"></i>
                        <span>Akses Produk Premium & Promo Eksklusif</span>
                    </div>
                    <div class="feature-item">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Pantau Status Pesanan secara Real-time</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="register-right">
            <div class="register-box">
                <h2>Buat Akun Baru</h2>
                <p class="subtitle">Mulai nikmati kelezatan roti premium kami.</p>

                @if($errors->any())
                    <div class="error-msg">
                        @foreach($errors->all() as $e)
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                                <i class="fas fa-circle-exclamation"></i>
                                <span>{{ $e }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group form-group-full">
                            <label class="form-label">Nama Lengkap</label>
                            <div class="input-wrapper">
                                <i class="fa-regular fa-user"></i>
                                <input type="text" name="name" class="form-control" placeholder="Nama lengkap Anda" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat Email</label>
                            <div class="input-wrapper">
                                <i class="fa-regular fa-envelope"></i>
                                <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Telepon / WA</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-phone"></i>
                                <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kata Sandi</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ulangi Sandi</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-shield-check"></i>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi sandi" required>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-register">
                        <span>Daftar Sekarang</span>
                        <i class="fa-solid fa-user-plus"></i>
                    </button>
                </form>

                <p class="login-link">Sudah memiliki akun? <a href="{{ route('login') }}">Masuk Sekarang</a></p>
            </div>
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
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Mondial Bakery</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --primary: #7a4b22;
            --primary-light: #c7834d;
            --accent: #f3bb67;
            --text-dark: #1B1B18;
            --text-light: #8B7355;
            --gradient-gold: linear-gradient(135deg, #7a4b22 0%, #c7834d 50%, #f3bb67 100%);
            --bg-cream: #fdf6ee;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background: var(--bg-cream);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .container {
            max-width: 500px;
            width: 100%;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 1.5rem;
            transition: 0.3s;
        }
        
        .back-btn:hover {
            color: var(--primary-light);
        }
        
        .profile-section {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .profile-img-wrapper {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 1rem;
        }
        
        .profile-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--accent);
        }
        
        .profile-img-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--gradient-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3.5rem;
            font-weight: 800;
        }
        
        .upload-btn {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--primary);
            border: 3px solid white;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
            font-size: 1.1rem;
        }
        
        .upload-btn:hover {
            background: var(--primary-light);
        }
        
        .upload-btn input {
            display: none;
        }
        
        .profile-name {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-dark);
        }
        
        .profile-role {
            font-size: 1rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }
        
        .profile-hint {
            font-size: 0.875rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid rgba(122, 75, 34, 0.2);
            border-radius: 16px;
            font-family: 'Nunito', sans-serif;
            font-size: 1rem;
            transition: 0.3s;
            background: white;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(122, 75, 34, 0.1);
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
        }
        
        .btn {
            flex: 1;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            font-family: 'Nunito', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            border: none;
            text-decoration: none;
            text-align: center;
        }
        
        .btn-secondary {
            background: #f3f4f6;
            color: var(--text-dark);
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
        }
        
        .btn-primary {
            background: var(--gradient-gold);
            color: white;
            box-shadow: 0 4px 12px rgba(122, 75, 34, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(122, 75, 34, 0.4);
        }
        
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }
        
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="container">
        @php
            $backUrl = match(auth()->user()->role) {
                'owner' => route('owner.dashboard'),
                'admin_gudang' => route('gudang.dashboard'),
                'admin_penjualan' => route('penjualan.dashboard'),
                'admin_produksi' => route('produksi.dashboard'),
                'customer' => route('home'),
                default => route('home'),
            };
        @endphp
        
        <a href="{{ $backUrl }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
        
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif
        
        <form method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="profile-section">
                <div class="profile-img-wrapper">
                    @if(auth()->user()->foto)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Foto Profil" class="profile-img" id="profilePreview">
                    @else
                        <div class="profile-img-placeholder" id="profilePreview">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <label class="upload-btn" title="Ubah Foto">
                        <i class="fas fa-camera"></i>
                        <input type="file" name="foto" accept="image/*" onchange="previewImage(event)">
                    </label>
                </div>
                
                <p class="profile-role">{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }} / {{ auth()->user()->name }}</p>
                <p class="profile-hint">Klik ikon kamera untuk mengubah foto profil.</p>
            </div>
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', auth()->user()->name) }}" required>
                @error('name')
                    <small style="color: #991b1b; display: block; margin-top: 0.5rem;">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="btn-group">
            <a href="{{ $backUrl }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
        </form>
    </div>
    
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profilePreview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Foto Profil';
                    img.className = 'profile-img';
                    img.id = 'profilePreview';
                    preview.parentNode.replaceChild(img, preview);
                }
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
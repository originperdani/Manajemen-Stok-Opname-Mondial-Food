@extends('layouts.app')
@section('title', 'Tentang Kami - Mondial Bakery')

@section('styles')
<style>
    .about-header {
        position: relative;
        background: #ffffff;
        padding: 5rem 0;
        width: 100%;
        border-bottom: none;
        text-align: center;
        overflow: hidden;
    }

    .about-header::before {
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

    .about-header h1, .about-header p {
        position: relative;
        z-index: 1;
    }

    .about-header h1 {
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

    .about-header p {
        color: var(--text-light);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .about-section {
        position: relative;
        padding: 5rem 0;
        background: #fff;
    }

    .about-section::before {
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
    }

    .about-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 4rem;
    }

    .about-content {
        display: grid;
        grid-template-columns: 0.8fr 1.2fr;
        gap: 3rem;
        align-items: start;
    }

    .about-text h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, var(--text-dark) 0%, var(--text-dark) 30%, var(--primary) 50%, var(--accent) 70%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
    }

    .about-text p {
        color: var(--text-medium);
        line-height: 1.8;
        margin-bottom: 1.2rem;
        text-align: justify;
        max-width: 100%;
        font-size: 1.05rem;
    }

    .about-image {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }

    .about-image img {
        width: 100%;
        max-width: 420px;
        height: auto;
        mix-blend-mode: multiply;
        filter: contrast(1.1);
    }

    .vision-mission {
        margin-top: 5rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2.5rem;
    }

    .vm-card {
        background: white;
        padding: 3rem 2.5rem;
        border-radius: 28px;
        border: none;
        box-shadow: 0 8px 35px rgba(122, 75, 34, 0.12);
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
    }

    .vm-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: var(--gradient-gold);
    }

    .vm-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(243, 187, 103, 0.12) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(40px, 40px);
        transition: all 0.4s ease;
    }

    .vm-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(122, 75, 34, 0.18);
    }

    .vm-card:hover::after {
        transform: translate(0, 0);
        width: 150px;
        height: 150px;
    }

    .vm-icon {
        width: 85px;
        height: 85px;
        background: linear-gradient(135deg, rgba(122, 75, 34, 0.1) 0%, rgba(243, 187, 103, 0.2) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.75rem;
        position: relative;
        z-index: 1;
    }

    .vm-icon img {
        width: 55px !important;
        height: 55px !important;
        object-fit: contain;
    }

    .vm-card h3 {
        color: var(--text-dark);
        margin-bottom: 1rem;
        font-family: 'Playfair Display', serif;
        font-size: 1.9rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }

    .vm-card p {
        color: var(--text-medium);
        line-height: 1.8;
        font-size: 1.05rem;
        position: relative;
        z-index: 1;
    }

    /* ===== NILAI KAMI ===== */
    .values-section {
        margin-top: 5rem;
        text-align: center;
    }

    .values-section h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        margin-bottom: 3rem;
        color: var(--text-dark);
        font-weight: 700;
    }

    .values-grid {
        display: flex;
        justify-content: center;
        gap: 4rem;
        flex-wrap: wrap;
    }

    .value-item {
        text-align: center;
        transition: all 0.3s ease;
    }

    .value-item:hover {
        transform: translateY(-5px);
    }

    .value-icon {
        width: 70px;
        height: 70px;
        background: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        font-size: 1.75rem;
        color: white;
        transition: all 0.3s ease;
    }

    .value-item:hover .value-icon {
        background: var(--gradient-gold);
        transform: scale(1.1);
    }

    .value-item h4 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }

    @media (max-width: 1024px) {
        .about-header {
            padding: 4.5rem 1.5rem;
        }

        .about-header h1 {
            font-size: 2.35rem;
            line-height: 1.15;
        }

        .about-header p {
            max-width: 520px;
            line-height: 1.6;
            padding: 0 1rem;
        }

        .about-container {
            padding: 0 2rem;
        }

        .about-content {
            gap: 2.5rem;
        }

        .vision-mission {
            gap: 1.5rem;
        }

        .vm-card {
            padding: 2.4rem 2rem;
            border-radius: 22px;
        }

        .values-grid {
            gap: 3rem;
        }
    }

    @media (max-width: 768px) {
        .about-header {
            padding: 3.5rem 1rem;
        }

        .about-header h1 {
            font-size: 2.1rem;
            margin-bottom: 0.85rem;
        }

        .about-header p {
            max-width: 24rem;
            padding: 0;
            font-size: 0.98rem;
            line-height: 1.55;
        }

        .about-section {
            padding: 3rem 0;
        }

        .about-content, .vision-mission {
            grid-template-columns: 1fr;
        }

        .about-content {
            gap: 1.75rem;
        }

        .about-image {
            padding: 0.5rem 2rem;
        }

        .about-image img {
            max-width: 280px;
        }

        .about-text h2 {
            font-size: 2rem;
            text-align: center;
        }

        .about-text p {
            text-align: left;
            font-size: 0.96rem;
            line-height: 1.7;
        }

        .vision-mission {
            margin-top: 3rem;
            gap: 1.25rem;
        }

        .vm-card {
            padding: 2rem 1.5rem;
            border-radius: 20px;
        }

        .vm-icon {
            width: 72px;
            height: 72px;
            margin-bottom: 1.25rem;
        }

        .vm-icon img {
            width: 48px !important;
            height: 48px !important;
        }

        .vm-card h3 {
            font-size: 1.55rem;
        }

        .vm-card p {
            font-size: 0.95rem;
            line-height: 1.65;
        }

        .values-section {
            margin-top: 3rem;
        }

        .values-section h2 {
            font-size: 1.7rem;
            margin-bottom: 2rem;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.5rem;
        }

        .about-container {
            padding: 0 1.5rem;
        }

        .value-icon {
            width: 60px;
            height: 60px;
            font-size: 1.45rem;
        }

        .value-item h4 {
            font-size: 0.95rem;
            line-height: 1.35;
        }
    }

    @media (max-width: 480px) {
        .about-header {
            padding: 3rem 0.9rem;
        }

        .about-header h1 {
            font-size: 2rem;
        }

        .about-header p {
            max-width: 19rem;
            font-size: 0.92rem;
        }

        .about-container {
            padding: 0 1rem;
        }

        .about-image {
            padding: 0.25rem 1.5rem;
        }

        .about-text h2 {
            font-size: 1.75rem;
        }

        .vm-card {
            padding: 1.75rem 1.25rem;
        }
    }
</style>
@endsection

@section('content')
<div class="about-header">
    <h1 class="reveal fade-bottom">Tentang Kami</h1>
    <p class="reveal fade-bottom delay-100">Mengenal lebih dekat perjalanan dan nilai-nilai Mondial Bakery</p>
</div>

<section class="about-section">
    <div class="about-container">
        <div class="about-content">
            <div class="about-image reveal fade-left">
                <img src="{{ asset('images/Logo.png') }}" alt="Mondial Bakery">
            </div>
            <div class="about-text reveal fade-right">
                <h2 class="reveal fade-bottom">Cerita di Balik Setiap Gigitan</h2>
                <p>Mondial Bakery lahir dari gairah untuk menghadirkan roti dan kue berkualitas tinggi dengan sentuhan cinta di setiap gigitannya. Kami percaya bahwa setiap butir tepung dan setiap tetes ragi memiliki cerita untuk diceritakan.</p>
                <p>Dengan bahan-bahan pilihan terbaik dan teknik tradisional yang dipadukan inovasi modern, kami berkomitmen memberikan pengalaman rasa yang tak terlupakan. Setiap produk yang keluar dari dapur kami adalah representasi dedikasi dan passion kami terhadap seni pembuatan roti dan kue.</p>
            </div>
        </div>

        <div class="vision-mission">
            <div class="vm-card reveal fade-bottom delay-100">
                <div class="vm-icon">
                    <img src="{{ asset('images/Logo.png') }}" alt="Logo" style="width: 45px; height: 45px; object-fit: contain; mix-blend-mode: multiply;">
                </div>
                <h3>Visi Kami</h3>
                <p>Menjadi toko roti dan kue pilihan utama keluarga Indonesia yang dikenal karena kualitas, kebersihan, dan keaslian rasa yang konsisten serta pelayanan yang hangat dari hati.</p>
            </div>
            <div class="vm-card reveal fade-bottom delay-200">
                <div class="vm-icon">
                    <img src="{{ asset('images/Logo.png') }}" alt="Logo" style="width: 45px; height: 45px; object-fit: contain; mix-blend-mode: multiply;">
                </div>
                <h3>Misi Kami</h3>
                <p>Memberikan produk roti dan kue terbaik dengan bahan berkualitas premium, pelayanan yang ramah dan hangat, serta terus berinovasi mengikuti selera pelanggan tanpa meninggalkan akar tradisi dan keaslian rasa.</p>
            </div>
        </div>

        <!-- Nilai Kami Section -->
        <div class="values-section">
            <h2 class="reveal fade-bottom">Nilai-Nilai Kami</h2>
            <div class="values-grid">
                <div class="value-item reveal fade-bottom delay-100">
                    <div class="value-icon">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h4>Kualitas Premium</h4>
                </div>
                <div class="value-item reveal fade-bottom delay-200">
                    <div class="value-icon">
                        <i class="fa-solid fa-heart"></i>
                    </div>
                    <h4>Dibuat dengan Cinta</h4>
                </div>
                <div class="value-item reveal fade-bottom delay-300">
                    <div class="value-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h4>Pelayanan Hangat</h4>
                </div>
                <div class="value-item reveal fade-bottom delay-400">
                    <div class="value-icon">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h4>Inovasi Berkelanjutan</h4>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

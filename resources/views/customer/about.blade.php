@extends('layouts.app')
@section('title', 'Tentang Kami - Mondial Bakery')

@section('styles')
<style>
    .page-header {
        position: relative;
        background: #ffffff;
        padding: 4rem 0;
        width: 100%;
        overflow: hidden;
        z-index: 1;
        text-align: center;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: -10px;
        right: -10px;
        background-image: 
            /* Motif Batik Geometris Modern (Truntum Style) - Sama dengan Beranda */
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
        gap: 2rem;
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
        color: #666;
        line-height: 1.6;
        margin-bottom: 1.2rem;
        text-align: justify;
        max-width: 100%;
    }

    .about-image {
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        padding-top: 1rem;
    }

    .about-image img {
        width: 100%;
        max-width: 380px;
        height: auto;
        mix-blend-mode: multiply;
        filter: contrast(1.1);
    }

    .vision-mission {
        margin-top: 5rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .vm-card {
        background: linear-gradient(135deg, #ffffff 0%, #fffbf2 100%);
        padding: 3rem;
        border-radius: 24px;
        border: 1px solid rgba(243, 187, 103, 0.2);
        box-shadow: 0 10px 30px rgba(122, 75, 34, 0.05);
        transition: transform 0.3s ease;
    }

    .vm-card:hover {
        transform: translateY(-5px);
        border-color: var(--accent);
    }

    .vm-card h3 {
        background: var(--gradient-gold);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1rem;
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .about-content, .vision-mission {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<section class="page-header">
    <div class="container">
        <h1 style="position: relative; z-index: 1; font-family: 'Playfair Display', serif; font-size: 2.5rem; background: linear-gradient(135deg, var(--text-dark) 0%, var(--text-dark) 30%, var(--primary) 50%, var(--accent) 70%, var(--accent) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; margin: 0;">
            Tentang Kami
        </h1>
        <p class="text-muted" style="position: relative; z-index: 1; margin-top: 0.5rem;">Mengenal lebih dekat perjalanan Mondial Bakery</p>
    </div>
</section>

<section class="about-section">
    <div class="about-container">
        <div class="about-content">
            <div class="about-image">
                <img src="{{ asset('images/logo.png') }}" alt="Mondial Bakery">
            </div>
            <div class="about-text">
                <h2>Kelezatan Autentik Sejak Hari Pertama</h2>
                <p>Mondial Bakery lahir dari gairah untuk menghadirkan roti dan kue berkualitas tinggi dengan sentuhan cinta di setiap gigitannya. Kami percaya bahwa setiap butir tepung dan setiap tetes ragi memiliki cerita untuk diceritakan.</p>
                <p>Menggunakan bahan-bahan pilihan terbaik dan teknik pembuatan tradisional yang dipadukan dengan inovasi modern, kami berkomitmen untuk memberikan pengalaman rasa yang tak terlupakan bagi setiap pelanggan kami.</p>
            </div>
        </div>

        <div class="vision-mission">
            <div class="vm-card">
                <h3>Visi Kami</h3>
                <p>Menjadi toko roti pilihan utama keluarga yang dikenal karena kualitas, kebersihan, dan keaslian rasa yang konsisten.</p>
            </div>
            <div class="vm-card">
                <h3>Misi Kami</h3>
                <p>Memberikan produk roti dan kue terbaik dengan bahan berkualitas, pelayanan yang hangat, serta terus berinovasi mengikuti selera pelanggan tanpa meninggalkan akar tradisi.</p>
            </div>
        </div>
    </div>
</section>
@endsection

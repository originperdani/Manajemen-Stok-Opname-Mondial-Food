@extends('layouts.app')
@section('title', 'Tentang Kami - Mondial Bakery')

@section('styles')
<style>
    .page-header {
        position: relative;
        padding: 6rem 0;
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
        font-size: 3.5rem;
        color: var(--text-dark);
        position: relative;
        z-index: 1;
    }

    .about-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 4rem 2rem;
    }

    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        margin-bottom: 6rem;
    }

    .about-image {
        position: relative;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .about-image img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.5s;
    }

    .about-image:hover img {
        transform: scale(1.05);
    }

    .about-text h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
    }

    .about-text p {
        color: var(--text-medium);
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 1.5rem;
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin-top: 4rem;
    }

    .value-card {
        padding: 3rem 2rem;
        background: white;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        text-align: center;
        transition: all 0.3s;
    }

    .value-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
    }

    .value-card i {
        font-size: 3rem;
        color: var(--primary);
        margin-bottom: 1.5rem;
    }

    .value-card h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    @media (max-width: 991px) {
        .about-grid { grid-template-columns: 1fr; gap: 3rem; }
        .values-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<header class="page-header">
    <div class="container">
        <h1 class="reveal fade-bottom">Tentang Kami</h1>
        <p class="reveal fade-bottom delay-100">Mengenal lebih dekat perjalanan Mondial Bakery dalam menghadirkan kebahagiaan.</p>
    </div>
</header>

<div class="about-content">
    <div class="about-grid">
        <div class="about-image reveal fade-left">
            <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=800" alt="Dapur Mondial Bakery">
        </div>
        <div class="about-text reveal fade-right">
            <h2>Warisan Rasa & Kualitas</h2>
            <p>Mondial Bakery lahir dari hasrat mendalam untuk menghadirkan roti dan kue berkualitas premium yang dibuat dengan cara tradisional namun tetap relevan dengan selera modern.</p>
            <p>Setiap produk kami dipanggang setiap hari (Freshly Baked Every Day) menggunakan bahan-bahan pilihan terbaik, tanpa pengawet buatan, untuk memastikan Anda mendapatkan cita rasa yang otentik dan tekstur yang sempurna.</p>
            <p>Kami percaya bahwa sepotong roti atau kue bukan sekadar makanan, melainkan sebuah bentuk perhatian dan cinta yang bisa dibagikan kepada orang-orang tersayang.</p>
        </div>
    </div>

    <div class="section-title-v2 reveal fade-bottom">
        <h2>Visi & Misi Kami</h2>
    </div>

    <div class="values-grid">
        <div class="value-card reveal fade-bottom delay-100">
            <i class="fas fa-heart"></i>
            <h3>Kualitas Premium</h3>
            <p>Hanya menggunakan bahan-bahan terbaik untuk menjaga standar rasa yang tinggi.</p>
        </div>
        <div class="value-card reveal fade-bottom delay-200">
            <i class="fas fa-seedling"></i>
            <h3>Kesegaran Terjamin</h3>
            <p>Produk kami selalu segar dari panggangan setiap hari untuk Anda.</p>
        </div>
        <div class="value-card reveal fade-bottom delay-300">
            <i class="fas fa-smile"></i>
            <h3>Kepuasan Pelanggan</h3>
            <p>Memberikan pelayanan yang hangat dan ramah bagi setiap pecinta roti.</p>
        </div>
    </div>
</div>
@endsection

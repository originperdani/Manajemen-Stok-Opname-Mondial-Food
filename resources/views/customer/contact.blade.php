@extends('layouts.app')
@section('title', 'Hubungi Kami - Mondial Bakery')

@section('styles')
<style>
    .page-header {
        position: relative;
        padding: 5rem 0;
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
        font-size: 2.5rem;
        background: linear-gradient(135deg, var(--text-dark) 0%, var(--text-dark) 30%, var(--primary) 50%, var(--accent) 70%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .page-header p {
        font-size: 1.1rem;
        color: var(--text-light);
        max-width: 600px;
        margin: 0 auto;
    }

    .contact-section {
        position: relative;
        padding: 5rem 0;
        background: #fff;
    }

    .contact-section::before {
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
        pointer-events: none;
        z-index: 0;
    }

    .contact-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
    }

    .info-card {
        background: #fdfaf7;
        padding: 2.5rem;
        border-radius: 20px;
        border: 1px solid rgba(122, 75, 34, 0.1);
        margin-bottom: 2rem;
    }

    .info-card h3 {
        background: linear-gradient(135deg, var(--text-dark) 0%, var(--primary) 50%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Override icon color inside gradient text */
    .info-card h3 i {
        -webkit-text-fill-color: var(--primary);
        background: none;
    }

    .admin-list {
        display: grid;
        gap: 1rem;
    }

    .admin-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        border: 1px solid rgba(122, 75, 34, 0.05);
        transition: all 0.3s ease;
    }

    .admin-item:hover {
        transform: translateX(8px);
        border-color: var(--primary-light);
    }

    .admin-item span {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.95rem;
    }

    .wa-btn {
        background: #25D366;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .wa-btn:hover {
        background: #128C7E;
        color: white;
        transform: translateY(-2px);
    }

    .map-container {
        width: 100%;
        height: 400px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-list-item {
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        padding: 1.25rem;
        background: #fff;
        border-radius: 16px;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(122, 75, 34, 0.03);
        border: 1px solid rgba(122, 75, 34, 0.05);
        transition: all 0.3s ease;
    }

    .info-list-item:hover {
        transform: translateX(8px);
        border-color: var(--primary-light);
        box-shadow: 0 8px 25px rgba(122, 75, 34, 0.08);
    }

    .info-list-item i {
        font-size: 1.25rem;
        color: var(--primary);
        width: 24px;
        text-align: center;
        margin-top: 0.2rem;
    }

    .info-content-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .info-content-value {
        display: block;
        font-size: 0.95rem;
        color: var(--text-dark);
        font-weight: 500;
    }

    .email-contact-link {
        display: inline-flex;
        align-items: center;
        color: var(--text-dark);
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 700;
        word-break: break-word;
        transition: color 0.25s ease;
    }

    .email-contact-link:hover {
        color: var(--primary);
        text-decoration: underline;
    }

    .opening-hours-detail {
        margin-top: 0.5rem;
        display: grid;
        gap: 0.5rem;
    }

    .hour-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: var(--text-medium);
        padding: 0.4rem 0.8rem;
        background: var(--bg-alt);
        border-radius: 8px;
    }

    .hour-row .day { font-weight: 600; }

    .social-links {
        display: flex;
        gap: 1.25rem;
        margin-top: 2rem;
        justify-content: center;
        padding-top: 1.5rem;
        border-top: 1px dashed rgba(122, 75, 34, 0.15);
    }

    .social-icon {
        width: 56px;
        height: 56px;
        background: var(--primary);
        color: #fff !important;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        font-size: 1.5rem;
        box-shadow: 0 8px 20px rgba(122, 75, 34, 0.35);
    }

    .social-icon:hover {
        transform: translateY(-8px) rotate(8deg);
        background: var(--primary-dark);
        color: #fff !important;
        box-shadow: 0 14px 30px rgba(122, 75, 34, 0.45);
    }

    @media (max-width: 992px) {
        .contact-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }
    
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem 0 1.25rem;
        }
        
        .page-header h1 {
            font-size: 1.3rem;
            margin-bottom: 0.35rem;
        }
        
        .page-header p {
            font-size: 0.75rem;
            padding: 0 1rem;
        }
        
        .contact-section {
            padding: 2rem 0;
        }
        
        .contact-container {
            padding: 0 1.25rem;
        }
        
        .info-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .info-card h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
        
        .admin-item {
            padding: 1rem;
            flex-direction: column;
            gap: 0.75rem;
            align-items: flex-start;
        }
        
        .wa-btn {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            width: 100%;
            justify-content: center;
        }
        
        .info-list-item {
            padding: 1rem;
        }
        
        .info-content-label {
            font-size: 0.7rem;
        }
        
        .info-content-value {
            font-size: 0.85rem;
        }
        
        .hour-row {
            font-size: 0.75rem;
            padding: 0.3rem 0.6rem;
        }
        
        .map-container {
            height: 280px;
        }
        
        .social-links {
            gap: 1rem;
            padding-top: 1rem;
        }
        
        .social-icon {
            width: 44px;
            height: 44px;
            font-size: 1.2rem;
        }
    }
</style>
@endsection

@section('content')
<header class="page-header">
    <div class="container">
        <h1 class="reveal fade-bottom">Hubungi Kami</h1>
        <p class="reveal fade-bottom delay-100">Kami siap melayani Anda dengan sepenuh hati</p>
    </div>
</header>

<section class="contact-section">
    <div class="contact-container">
        @php
            $contactEmail = 'mondialfood.co@gmail.com';
            $contactEmailUrl = 'https://mail.google.com/mail/?' . http_build_query([
                'view' => 'cm',
                'fs' => '1',
                'to' => $contactEmail,
                'su' => 'Pertanyaan Seputar Mondial Bakery',
                'body' => "Halo Admin Mondial Bakery,\n\nSaya ingin bertanya mengenai produk/pemesanan Mondial Bakery.\n\nNama:\nNo. WhatsApp:\nPertanyaan:\n\nTerima kasih.",
            ], '', '&', PHP_QUERY_RFC3986);
        @endphp
        <div class="contact-grid">
            <!-- Left Side: Contact Info -->
            <div class="contact-info">
                <div class="info-card reveal fade-bottom">
                    <h3><i class="fab fa-whatsapp"></i> Customer Service (Admin)</h3>
                    <div class="admin-list">
                        <div class="admin-item">
                            <span>Admin 1 (Pemesanan)</span>
                            <a href="https://wa.me/6285793930723" target="_blank" class="wa-btn">
                                <i class="fab fa-whatsapp"></i> Chat WA
                            </a>
                        </div>
                        <div class="admin-item">
                            <span>Admin 2 (Komplain)</span>
                            <a href="https://wa.me/6285793930723" target="_blank" class="wa-btn">
                                <i class="fab fa-whatsapp"></i> Chat WA
                            </a>
                        </div>
                        <div class="admin-item">
                            <span>Admin 3 (Informasi)</span>
                            <a href="https://wa.me/6285793930723" target="_blank" class="wa-btn">
                                <i class="fab fa-whatsapp"></i> Chat WA
                            </a>
                        </div>
                    </div>
                </div>

                <div class="info-card reveal fade-bottom delay-100">
                    <h3><i class="fas fa-info-circle"></i> Informasi Lainnya</h3>
                    <div class="info-list">
                        <div class="info-list-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <span class="info-content-label">Email Kami</span>
                                <a href="{{ $contactEmailUrl }}" target="_blank" rel="noopener noreferrer" class="email-contact-link" title="Kirim email ke {{ $contactEmail }}">
                                    {{ $contactEmail }}
                                </a>
                            </div>
                        </div>
                        <div class="info-list-item">
                            <i class="fab fa-instagram"></i>
                            <div>
                                <span class="info-content-label">Instagram</span>
                                <a href="https://www.instagram.com/mondialbakery?igsh=aWhpMXA4djFwdDNh" target="_blank" rel="noopener noreferrer" style="color: var(--text-dark); text-decoration: none;">
                                    <span class="info-content-value">@mondialbakery</span>
                                </a>
                            </div>
                        </div>
                        <div class="info-list-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <span class="info-content-label">Jam Operasional</span>
                                <div class="opening-hours-detail">
                                    <div class="hour-row">
                                        <span class="day">Senin - Jumat</span>
                                        <span class="time">08:00 - 20:00</span>
                                    </div>
                                    <div class="hour-row">
                                        <span class="day">Sabtu - Minggu</span>
                                        <span class="time">08:00 - 21:00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <a href="https://www.instagram.com/mondialbakery?igsh=aWhpMXA4djFwdDNh" target="_blank" rel="noopener noreferrer" class="social-icon" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon" title="TikTok"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>

            <!-- Right Side: Location & Map -->
            <div class="contact-location">
                <div class="info-card reveal fade-bottom">
                    <h3><i class="fas fa-map-marker-alt"></i> Lokasi Kami</h3>
                    <p style="margin-bottom: 1.5rem;">Jl. Mesjid Al-Akhyar No.34, Gandul, Kec. Cinere, Kota Depok, Jawa Barat 16512</p>
                    <div class="map-container">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.7335272846873!2d106.7865766!3d-6.324844!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ef273e93655b%3A0xc023c7c42704f76d!2sJl.%20Mesjid%20Al-Akhyar%20No.34%2C%20Gandul%2C%20Kec.%20Cinere%2C%20Kota%20Depok%2C%20Jawa%20Barat%2016512!5e0!3m2!1sid!2sid!4v1714800000000!5m2!1sid!2sid" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@extends('layouts.app')
@section('title', 'Checkout - Mondial Bakery')
@section('styles')
<style>
    /* ===== PAGE HEADER ===== */
    .checkout-header {
        background: #FDFDFC;
        padding: 5rem 0;
        border-bottom: 1px solid var(--border);
        text-align: center;
        margin-bottom: 4rem;
    }
    .checkout-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        background: linear-gradient(135deg, var(--text-dark) 0%, var(--text-dark) 30%, var(--primary) 50%, var(--accent) 70%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }
    .checkout-header p {
        color: var(--text-light);
        font-size: 1.1rem;
    }

    /* ===== PREMIUM CHECKOUT BUTTON ===== */
    .btn-checkout-premium {
        width: 100%;
        height: 56px;
        background: var(--gradient-gold);
        color: white;
        border: none;
        border-radius: 16px;
        font-family: 'Poppins', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 25px rgba(122, 75, 34, 0.2);
        position: relative;
        overflow: hidden;
        margin-top: 2rem;
    }

    .btn-checkout-premium:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(122, 75, 34, 0.3);
        background: var(--gradient-dark);
        color: white;
    }

    .btn-checkout-premium:active {
        transform: translateY(-1px) scale(0.98);
    }

    .btn-checkout-premium i {
        font-size: 1.2rem;
        transition: transform 0.4s ease;
    }

    .btn-checkout-premium:hover i {
        transform: scale(1.2) rotate(10deg);
    }

    .btn-checkout-premium::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
        transform: rotate(45deg);
        transition: 0.6s;
    }

    .btn-checkout-premium:hover::after {
        left: 100%;
    }
    .checkout-header p {
        color: var(--text-light);
        font-size: 1.1rem;
    }

    /* ===== CHECKOUT GRID ===== */
    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 3rem;
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem 6rem;
    }

    /* ===== SECTION CARDS ===== */
    .checkout-section {
        background: white;
        border-radius: 24px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border);
    }
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 2rem;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .section-title i { color: var(--primary); }

    /* ===== FORM STYLES ===== */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    .form-group-full { grid-column: span 2; }
    
    .checkout-input, .checkout-textarea {
        width: 100%;
        padding: 0.875rem 1.25rem;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        font-family: 'Poppins';
        font-size: 0.95rem;
        transition: all 0.3s;
        background: #F9FAFB;
    }
    .checkout-input:focus, .checkout-textarea:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(139, 105, 20, 0.1);
    }
    .checkout-input:invalid, .checkout-textarea:invalid {
        border-color: #dc2626;
    }
    .checkout-input:invalid:focus, .checkout-textarea:invalid:focus {
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
    }
    .form-group, .form-group-full {
        position: relative;
    }
    .error-message {
        color: #dc2626;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: none;
        font-weight: 500;
    }
    .form-group .error-message.show, .form-group-full .error-message.show {
        display: block;
    }
    .checkout-label {
        display: block;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    /* ===== OPTION CARDS ===== */
    .options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .option-card {
        position: relative;
        cursor: pointer;
    }
    .option-card input {
        position: absolute;
        opacity: 0;
    }
    .option-content {
        padding: 1.5rem;
        border: 2px solid var(--border);
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        transition: all 0.3s ease;
        background: var(--surface-soft);
    }
    .option-card input:checked + .option-content {
        border-color: var(--primary);
        background: var(--surface-soft);
        box-shadow: 0 6px 20px rgba(122, 75, 34, 0.15);
    }
    .option-title { font-weight: 700; color: var(--text-dark); font-size: 0.95rem; }
    .option-desc { font-size: 0.8rem; color: var(--text-light); }

    /* ===== ORDER SUMMARY ===== */
    .order-summary {
        background: white;
        border-radius: 24px;
        padding: 2.5rem;
        border: 1px solid var(--border);
        position: sticky;
        top: 120px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }
    .order-item {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #F3F4F6;
    }
    .order-item:last-child { border-bottom: none; }
    .item-name { font-weight: 600; font-size: 0.95rem; color: var(--text-dark); margin-bottom: 0.25rem; }
    .item-meta { font-size: 0.85rem; color: var(--text-light); }
    .item-price { font-weight: 700; color: var(--text-dark); }

    .summary-totals {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 2px dashed var(--border);
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        color: var(--text-medium);
    }
    .grand-total {
        margin-top: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .grand-total-label { font-weight: 800; font-size: 1.125rem; color: var(--text-dark); }
    .grand-total-value { font-weight: 800; font-size: 1.75rem; color: var(--primary); }

    @media (max-width: 1024px) {
        .checkout-grid {
            grid-template-columns: 1fr;
            max-width: 820px;
            gap: 2rem;
        }

        .order-summary {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .checkout-header {
            padding: 3rem 0 2rem;
            margin-bottom: 2rem;
        }
        
        .checkout-header h1 {
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }
        
        .checkout-header p {
            font-size: 0.9rem;
            padding: 0 1rem;
        }
        
        .checkout-grid {
            grid-template-columns: 1fr;
            padding: 0 1.25rem 3rem;
            gap: 2rem;
        }
        
        .checkout-section {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .section-title {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .form-group-full {
            grid-column: auto;
        }
        
        .options-grid {
            grid-template-columns: 1fr;
        }
        
        .order-summary {
            padding: 1.5rem;
            position: static;
        }
        
        .grand-total-value {
            font-size: 1.4rem;
        }
        
        .btn-checkout-premium {
            height: 50px;
            font-size: 0.95rem;
            margin-top: 1.5rem;
        }
    }

    @media (max-width: 640px) {
        .checkout-header h1 {
            flex-wrap: wrap;
            gap: 0.5rem;
            font-size: 1.6rem;
        }

        .checkout-section,
        .order-summary {
            border-radius: 18px;
            padding: 1.25rem;
        }

        .section-title {
            align-items: flex-start;
            line-height: 1.35;
        }

        .option-content {
            min-height: auto !important;
            padding: 1.2rem;
        }

        .order-item {
            gap: 0.75rem;
        }

        .item-name,
        .item-meta {
            overflow-wrap: anywhere;
        }

        .item-price {
            white-space: nowrap;
        }

        .grand-total {
            align-items: flex-start;
            flex-direction: column;
            gap: 0.35rem;
        }

        .grand-total-value {
            font-size: 1.25rem;
            overflow-wrap: anywhere;
        }
    }
</style>
@endsection

@section('content')
<header class="checkout-header">
    <div class="container">
        <h1>Checkout</h1>
        <p>Selesaikan pesanan Anda dengan mengisi detail di bawah ini.</p>
    </div>
</header>

<div class="container">
    <form id="checkout-form" method="POST" action="{{ route('customer.checkout.proses') }}">
        @csrf
        @if ($errors->any())
            <div class="checkout-section">
                <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <i class="fa-solid fa-circle-exclamation" style="color: #dc2626; font-size: 1.2rem;"></i>
                        <h4 style="margin: 0; color: #991b1b; font-size: 1rem;">Harap lengkapi data berikut:</h4>
                    </div>
                    <ul style="margin: 0; padding-left: 1.25rem; color: #991b1b;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="checkout-grid">
            <main>
                <!-- Data Pemesan -->
                <div class="checkout-section">
                    <h3 class="section-title"><i class="fa-solid fa-user-check"></i> Informasi Pengiriman</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="checkout-label">Nama Lengkap *</label>
                            <input type="text" name="nama_pelanggan" class="checkout-input" value="{{ auth()->user()->name }}" required placeholder="Masukkan nama penerima" oninvalid="this.setCustomValidity('Harap masukkan nama lengkap Anda')" oninput="this.setCustomValidity('')">
                            <div class="error-message" id="error-nama_pelanggan">Harap masukkan nama lengkap Anda</div>
                        </div>
                        <div class="form-group">
                            <label class="checkout-label">No. Telepon / WhatsApp *</label>
                            <input type="text" name="phone_pelanggan" class="checkout-input" value="{{ auth()->user()->phone }}" required placeholder="Contoh: 08123456789" oninvalid="this.setCustomValidity('Harap masukkan nomor telepon/WhatsApp Anda')" oninput="this.setCustomValidity('')">
                            <div class="error-message" id="error-phone_pelanggan">Harap masukkan nomor telepon/WhatsApp Anda</div>
                        </div>
                        <div class="form-group-full">
                            <label class="checkout-label">Email *</label>
                            <input type="email" name="email_pelanggan" class="checkout-input" value="{{ auth()->user()->email }}" required placeholder="Masukkan email Anda" oninvalid="this.setCustomValidity('Harap masukkan email Anda yang valid')" oninput="this.setCustomValidity('')">
                            <div class="error-message" id="error-email_pelanggan">Harap masukkan email Anda yang valid</div>
                        </div>
                        <div class="form-group-full">
                            <label class="checkout-label" for="alamat_pengiriman">Alamat Lengkap Pengiriman *</label>
                            <textarea id="alamat_pengiriman" name="alamat_pengiriman" class="checkout-textarea" rows="3" required placeholder="Nama jalan, nomor rumah, kelurahan, kecamatan, kota" oninvalid="this.setCustomValidity('Harap masukkan alamat lengkap Anda')" oninput="this.setCustomValidity('')">{{ auth()->user()->alamat }}</textarea>
                            <div class="error-message" id="error-alamat_pengiriman">Harap masukkan alamat lengkap Anda</div>
                        </div>
                        <div class="form-group-full">
                            <label class="checkout-label">Catatan Pesanan (Opsional)</label>
                            <textarea name="catatan" class="checkout-textarea" rows="2" placeholder="Contoh: Titip di satpam, jangan terlalu manis, dll"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Metode Pengiriman -->
                <div class="checkout-section">
                    <h3 class="section-title"><i class="fa-solid fa-truck-fast"></i> Metode Pengiriman</h3>
                    <div class="options-grid">
                        @foreach([
                            'ambil_sendiri' => ['fa-store', 'Ambil Sendiri', 'Ambil pesanan langsung di toko'],
                            'kurir_ojol' => ['fa-motorcycle', 'Kurir Toko / Ojek Online', 'Pengiriman oleh kurir kami atau ojek online']
                        ] as $val => $info)
                            <label class="option-card">
                                <input type="radio" name="metode_kirim" value="{{ $val }}" {{ $loop->first ? 'checked' : '' }} onchange="updateOngkir()">
                                <div class="option-content" style="position: relative; min-height: 180px; display: flex; flex-direction: column; justify-content: center;">
                                    @if($val === 'kurir_ojol')
                                        <span style="display: inline-block; margin-bottom: 0.75rem; background: rgba(122, 75, 34, 0.1); color: var(--primary); padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; width: fit-content;">Bayar Ongkir COD</span>
                                    @endif
                                    <div style="font-size: 2rem; color: var(--primary);"><i class="fas {{ $info[0] }}"></i></div>
                                    <div class="option-title" style="font-size: 1.05rem;">{{ $info[1] }}</div>
                                    <div class="option-desc">{{ $info[2] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="checkout-section">
                    <h3 class="section-title"><i class="fa-solid fa-wallet"></i> Metode Pembayaran</h3>
                    <div class="options-grid" id="metode-bayar-container">
                        <label class="option-card metode-bayar-option">
                            <input type="hidden" name="metode_bayar" value="midtrans">
                            <div class="option-content">
                                <div style="font-size: 1.8rem; color: var(--primary);"><i class="fas fa-credit-card"></i></div>
                                <div class="option-title">Pembayaran Online</div>
                                <div class="option-desc">QRIS, Transfer Bank, E-Wallet (diproses oleh Midtrans)</div>
                            </div>
                        </label>
                    </div>
                </div>
            </main>

            <aside>
                <div class="order-summary">
                    <h3 class="section-title" style="margin-bottom: 1.5rem;"><i class="fa-solid fa-basket-shopping"></i> Ringkasan Pesanan</h3>
                    
                    <div style="max-height: 300px; overflow-y: auto; padding-right: 0.5rem;">
                        @foreach($items as $item)
                            <div class="order-item">
                                <div>
                                    <div class="item-name">{{ $item->produk->nama_produk }}</div>
                                    <div class="item-meta">{{ $item->jumlah }} x Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</div>
                                </div>
                                <div class="item-price">Rp {{ number_format($item->jumlah * $item->produk->harga, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="summary-totals">
                        <div class="summary-row">
                            <span>Subtotal Produk</span>
                            <span style="font-weight: 600;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Biaya Pengiriman</span>
                            <span style="font-weight: 600;" id="ongkir-display">Rp 0</span>
                        </div>
                        
                        <div class="grand-total">
                            <span class="grand-total-label">Total Pembayaran</span>
                            <span class="grand-total-value" id="total-display">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-checkout-premium">
                        Konfirmasi Pesanan <i class="fa-solid fa-circle-check"></i>
                    </button>
                    
                    <div style="margin-top: 1.5rem; text-align: center; font-size: 0.8rem; color: var(--text-light); line-height: 1.5;">
                        <i class="fa-solid fa-shield-halved"></i> Pembayaran Anda aman dan terenkripsi.
                    </div>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
const subtotal = {{ $total }};
const alamatToko = 'Mondial Bakery, Jl. Mesjid Al-Akhyar No.34, Gandul, Cinere, Depok City, West Java 16512';

function updateOngkir() {
    const ongkir = 0;
    document.getElementById('ongkir-display').textContent = 'Rp ' + ongkir.toLocaleString('id-ID');
    document.getElementById('total-display').textContent = 'Rp ' + (subtotal + ongkir).toLocaleString('id-ID');
}

function updateAlamat() {
    const metodeKirim = document.querySelector('input[name="metode_kirim"]:checked').value;
    const alamatTextarea = document.querySelector('textarea[name="alamat_pengiriman"]');
    const alamatLabel = document.querySelector('label[for="alamat_pengiriman"]') || document.querySelector('textarea[name="alamat_pengiriman"]').previousElementSibling;
    
    if (metodeKirim === 'ambil_sendiri') {
        alamatLabel.textContent = 'Alamat Pengambilan';
        alamatTextarea.value = alamatToko;
        alamatTextarea.disabled = true;
        alamatTextarea.style.backgroundColor = '#F3F4F6';
        alamatTextarea.style.cursor = 'not-allowed';
    } else {
        alamatLabel.textContent = 'Alamat Lengkap Pengiriman *';
        alamatTextarea.value = '';
        alamatTextarea.disabled = false;
        alamatTextarea.style.backgroundColor = '#F9FAFB';
        alamatTextarea.style.cursor = 'text';
    }
}

function validateForm() {
    let isValid = true;
    const fields = ['nama_pelanggan', 'phone_pelanggan', 'email_pelanggan', 'alamat_pengiriman'];
    
    fields.forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        const errorMsg = document.getElementById(`error-${field}`);
        
        if (!input.value.trim()) {
            input.classList.add('invalid');
            errorMsg.classList.add('show');
            isValid = false;
        } else {
            input.classList.remove('invalid');
            errorMsg.classList.remove('show');
        }
        
        if (field === 'email_pelanggan' && input.value.trim()) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value.trim())) {
                input.classList.add('invalid');
                errorMsg.textContent = 'Harap masukkan email yang valid';
                errorMsg.classList.add('show');
                isValid = false;
            }
        }
    });
    
    return isValid;
}

document.addEventListener('DOMContentLoaded', function() {
    updateOngkir();
    updateAlamat();
    
    document.querySelectorAll('input[name="metode_kirim"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updateAlamat();
        });
    });
    
    const fields = ['nama_pelanggan', 'phone_pelanggan', 'email_pelanggan', 'alamat_pengiriman'];
    fields.forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        input.addEventListener('input', function() {
            const errorMsg = document.getElementById(`error-${field}`);
            if (this.value.trim()) {
                this.classList.remove('invalid');
                errorMsg.classList.remove('show');
            }
        });
    });
    
    document.getElementById('checkout-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        document.getElementById('alamat_pengiriman').disabled = false;
        
        if (!validateForm()) {
            const firstError = document.querySelector('.error-message.show');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }
        
        const submitBtn = document.querySelector('.btn-checkout-premium');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        
        try {
            const formData = new FormData(this);
            const response = await fetch('{{ route('customer.checkout.proses') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.snap_token) {
                snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                // Refresh pesanan list to show updated status
                                window.location.href = '{{ route('customer.pesanan') }}?payment=success';
                            },
                            onPending: function(result) {
                                window.location.href = '{{ route('customer.pesanan') }}?payment=pending';
                            },
                            onError: function(result) {
                                window.location.href = '{{ route('customer.pesanan') }}?payment=error';
                            },
                            onClose: function() {
                                window.location.href = '{{ route('customer.pesanan') }}';
                            }
                        });
            } else {
                alert(data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Konfirmasi Pesanan <i class="fa-solid fa-circle-check"></i>';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Konfirmasi Pesanan <i class="fa-solid fa-circle-check"></i>';
        }
    });
});
</script>
@endsection

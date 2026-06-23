@extends('layouts.admin')
@section('title', 'POS Kasir')
@section('page-title', 'Point of Sale (Kasir)')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Penjualan</div>
<li><a href="{{ route('penjualan.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('penjualan.pos') }}" class="active"><i class="fas fa-cash-register"></i> POS / Kasir</a></li>
<li><a href="{{ route('penjualan.transaksi') }}"><i class="fas fa-history"></i> Riwayat Transaksi</a></li>
<li><a href="{{ route('penjualan.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('styles')
<style>
    /* ═══ LAYOUT ═══ */
    .pos-container{display:grid;grid-template-columns:1fr 420px;gap:1.25rem;height:calc(100vh - 220px);min-height:600px;max-height:calc(100vh - 220px)}

    /* ═══ PRODUCT GRID ═══ */
    .pos-products{overflow-y:auto;padding-right:0.5rem;scroll-behavior:smooth;-webkit-overflow-scrolling:touch}
    .pos-search{margin-bottom:1rem;position:sticky;top:0;z-index:5;background:var(--bg-cream,#fdf6ee);padding-bottom:0.5rem}
    .pos-search input{border-radius:12px;border:1.5px solid var(--border);padding:0.7rem 1rem 0.7rem 2.5rem;font-size:0.9rem;background:white;width:100%;transition:border-color 0.3s}
    .pos-search input:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(122,75,34,0.08)}
    .pos-search-icon{position:absolute;left:1rem;top:50%;transform:translateY(-50%);background:var(--gradient-gold);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-size:0.85rem;pointer-events:none}
    .pos-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:0.75rem}
    .pos-item{background:white;border:2px solid transparent;border-radius:14px;overflow:hidden;cursor:pointer;transition:all 0.25s ease;display:flex;flex-direction:column;box-shadow:0 2px 8px rgba(0,0,0,0.06)}
    .pos-item:hover{border-color:var(--accent);transform:translateY(-3px);box-shadow:0 8px 24px rgba(122,75,34,0.15)}
    .pos-item:active{transform:translateY(0);box-shadow:0 2px 8px rgba(0,0,0,0.06)}
    .pos-item .item-img{width:100%;aspect-ratio:1/1;object-fit:cover;display:block;background:#f5efe8}
    .pos-item .item-img-fallback{width:100%;aspect-ratio:1/1;display:flex;align-items:center;justify-content:center;font-size:2.8rem;background:linear-gradient(135deg,#fdf6ee,#f5efe8)}
    .pos-item .item-info{padding:0.55rem 0.65rem 0.65rem;text-align:center;border-top:1px solid rgba(122,75,34,0.06)}
    .pos-item h5{font-size:0.78rem;margin:0 0 0.15rem;color:var(--text-dark);line-height:1.3;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .pos-item .price{color:var(--primary);font-weight:700;font-size:0.82rem}
    .pos-item .stock{font-size:0.65rem;color:var(--text-light);margin-top:0.1rem}

    /* ═══ CART PANEL ═══ */
    .pos-cart{background:white;border-radius:16px;border:1px solid var(--border);display:flex;flex-direction:column;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.06);min-height:600px}
    .pos-cart-header{padding:0.85rem 1.25rem;background:linear-gradient(135deg,var(--primary),#8B6914);border-bottom:none;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
    .pos-cart-header h3{font-size:0.95rem;color:white;margin:0;display:flex;align-items:center;gap:0.5rem}
    .pos-cart-header .cart-count{background:rgba(255,255,255,0.25);color:white;font-size:0.7rem;font-weight:700;padding:0.15rem 0.55rem;border-radius:99px;min-width:20px;text-align:center}

    /* ─── Cart Items ─── */
    .pos-cart-items{flex:1;overflow-y:auto;padding:0.5rem 0.75rem;min-height:100px;background:#fafaf8;scroll-behavior:smooth;-webkit-overflow-scrolling:touch;max-height:calc(100% - 320px)}
    .pos-cart-empty{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem 1rem;color:var(--text-light);text-align:center;gap:0.5rem;min-height:140px}
    .pos-cart-empty i{font-size:2rem;opacity:0.3}
    .pos-cart-empty span{font-size:0.82rem}
    .pos-cart-item{display:flex;align-items:center;gap:0.5rem;padding:0.55rem 0.5rem;background:white;border-radius:10px;margin-bottom:0.4rem;border:1px solid rgba(122,75,34,0.06);transition:all 0.2s}
    .pos-cart-item:hover{border-color:rgba(122,75,34,0.12);box-shadow:0 2px 6px rgba(0,0,0,0.04)}
    .pos-cart-item .cart-num{width:20px;height:20px;border-radius:50%;background:var(--primary);color:white;font-size:0.6rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .pos-cart-item .name{flex:1;font-size:0.8rem;font-weight:600;color:var(--text-dark);line-height:1.3;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .pos-cart-item .qty-ctrl{display:flex;align-items:center;gap:0.2rem;flex-shrink:0}
    .pos-cart-item .qty-ctrl button{width:22px;height:22px;border:1.5px solid var(--border);border-radius:50%;background:white;cursor:pointer;font-size:0.75rem;font-weight:700;color:var(--text-dark);display:flex;align-items:center;justify-content:center;transition:all 0.2s}
    .pos-cart-item .qty-ctrl button:hover{background:var(--primary);color:white;border-color:var(--primary)}
    .pos-cart-item .qty-ctrl input{width:28px;height:22px;text-align:center;border:none;background:transparent;font-size:0.8rem;font-weight:700;color:var(--primary)}
    .pos-cart-item .sub{font-weight:700;font-size:0.78rem;color:var(--primary);min-width:70px;text-align:right;flex-shrink:0}
    .pos-cart-item .remove{width:22px;height:22px;border-radius:50%;color:white;cursor:pointer;border:none;background:var(--gradient-gold);font-size:0.6rem;display:flex;align-items:center;justify-content:center;transition:all 0.2s;flex-shrink:0}
    .pos-cart-item .remove:hover{background:var(--primary);transform:scale(1.15)}

    /* ─── Cart Footer ─── */
    .pos-cart-footer{border-top:2px solid var(--border);background:linear-gradient(180deg,#fffcf8,#fdf6ee);padding:1rem 1.25rem;flex-shrink:0;display:flex;flex-direction:column;gap:0.75rem}
    .pos-total-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem}
    .pos-total-bar .label{font-size:0.85rem;font-weight:600;color:var(--text-light)}
    .pos-total-bar .amount{font-size:1.35rem;font-weight:800;color:var(--primary)}

    /* ─── Form Grid ─── */
    .pos-form-row{display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;margin-bottom:0.6rem}
    .pos-form-row .form-control{font-size:0.82rem;padding:0.55rem 0.75rem;border-radius:10px;border:1.5px solid var(--border)}
    .pos-form-row .form-control:focus{border-color:var(--primary);box-shadow:0 0 0 2px rgba(122,75,34,0.08);outline:none}

    /* ─── Pembayaran Compact ─── */
    .payment-compact{background:white;border-radius:12px;padding:0.75rem;margin-bottom:0.7rem;border:1.5px solid rgba(122,75,34,0.1)}
    .payment-compact .pay-label{display:flex;align-items:center;gap:0.4rem;font-size:0.72rem;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.45rem}
    .payment-compact .pay-label i{font-size:0.75rem;color:var(--accent)}
    .uang-input-wrapper{position:relative}
    .uang-input-wrapper .currency-prefix{position:absolute;left:0.85rem;top:50%;transform:translateY(-50%);font-weight:700;color:var(--primary);font-size:0.85rem;pointer-events:none}
    .uang-input-wrapper input{padding-left:2.2rem !important;font-size:0.95rem !important;font-weight:700 !important;border-radius:10px !important;border:1.5px solid var(--border) !important}
    .uang-input-wrapper input:focus{border-color:var(--primary) !important;box-shadow:0 0 0 2px rgba(122,75,34,0.08) !important;outline:none}
    .quick-cash{display:flex;gap:0.3rem;margin-top:0.4rem;flex-wrap:wrap}
    .quick-cash button{flex:1;min-width:60px;padding:0.3rem 0.4rem;border:1.5px solid rgba(122,75,34,0.1);border-radius:8px;background:white;font-size:0.7rem;font-weight:700;color:var(--primary);cursor:pointer;transition:all 0.2s;white-space:nowrap}
    .quick-cash button:hover{background:var(--secondary,#f7efe3);border-color:var(--accent);transform:translateY(-1px)}
    .kembalian-box{display:flex;justify-content:space-between;align-items:center;padding:0.6rem 0.85rem;border-radius:10px;margin-top:0.45rem;transition:all 0.3s ease;border:1.5px solid transparent}
    .kembalian-box.sufficient{background:#ecfdf5;border-color:#a7f3d0}
    .kembalian-box.insufficient{background:#fef2f2;border-color:#fecaca}
    .kembalian-box.neutral{background:#f9fafb;border-color:#e5e7eb}
    .kembalian-box .kembalian-label{font-size:0.78rem;font-weight:600;display:flex;align-items:center;gap:0.35rem}
    .kembalian-box.sufficient .kembalian-label{color:#065f46}
    .kembalian-box.insufficient .kembalian-label{color:#991b1b}
    .kembalian-box.neutral .kembalian-label{color:#6b7280}
    .kembalian-box .kembalian-value{font-size:0.95rem;font-weight:800}
    .kembalian-box.sufficient .kembalian-value{color:#059669}
    .kembalian-box.insufficient .kembalian-value{color:#dc2626}
    .kembalian-box.neutral .kembalian-value{color:#9ca3af}

    /* ─── Action Buttons ─── */
    .pos-btn-group{display:flex;gap:0.5rem;margin-top:0.5rem}
    .pos-btn-group .btn{flex:1;font-size:0.82rem;padding:0.75rem 0.85rem;border-radius:12px;font-weight:700;display:flex;align-items:center;justify-content:center;gap:0.4rem;transition:all 0.25s;cursor:pointer;white-space:nowrap;overflow:visible}
    .btn-cancel{background:white !important;color:var(--primary) !important;border:2px solid var(--primary) !important}
    .btn-cancel:hover{background:var(--secondary,#f7efe3) !important;border-color:var(--primary) !important}
    .btn-process{background:linear-gradient(135deg,var(--primary),#8B6914) !important;color:white !important;border:none !important;box-shadow:0 4px 12px rgba(122,75,34,0.3)}
    .btn-process:hover{box-shadow:0 6px 20px rgba(122,75,34,0.4);transform:translateY(-1px)}

    @media (max-width:1024px){
        .pos-container{grid-template-columns:1fr;height:auto;min-height:0;max-height:none;gap:1rem}
        .pos-products{overflow:visible;padding-right:0}
        .pos-search{position:relative;top:auto;background:transparent}
        .pos-grid{grid-template-columns:repeat(auto-fill,minmax(150px,1fr))}
        .pos-cart{min-height:0}
        .pos-cart-items{max-height:45vh}
    }

    @media (max-width:640px){
        .pos-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:0.65rem}
        .pos-item h5{white-space:normal;min-height:2.6em}
        .pos-cart-header{padding:0.8rem 1rem}
        .pos-cart-footer{padding:0.9rem 1rem}
        .pos-cart-item{flex-wrap:wrap;align-items:flex-start;gap:0.45rem}
        .pos-cart-item .name{flex:1 1 calc(100% - 30px);white-space:normal}
        .pos-cart-item .qty-ctrl{order:3}
        .pos-cart-item .sub{order:4;min-width:auto;margin-left:auto}
        .pos-cart-item .remove{order:5}
        .pos-form-row{grid-template-columns:1fr}
        .pos-total-bar .amount{font-size:1.1rem;text-align:right}
        .quick-cash button{flex:1 1 calc(50% - 0.3rem)}
        .pos-btn-group{flex-direction:column}
        .pos-btn-group .btn{width:100%;white-space:normal}
    }
</style>
@endsection

@section('content')
<div class="pos-container">
    {{-- ═══ PRODUCT GRID ═══ --}}
    <div class="pos-products">
        <div class="pos-search" style="position:relative">
            <i class="fas fa-search pos-search-icon" style="background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
            <input type="text" id="searchProduct" placeholder="Cari produk...">
        </div>
        <div class="pos-grid" id="productGrid">
            @foreach($produk as $p)
            <div class="pos-item" onclick="addToCart({{ $p->id }}, '{{ addslashes($p->nama_produk) }}', {{ $p->harga }}, {{ $p->stok }})" data-name="{{ strtolower($p->nama_produk) }}">
                @if($p->gambar)
                    <img class="item-img" src="{{ str_starts_with($p->gambar, 'http') ? $p->gambar : asset('storage/' . $p->gambar) }}" alt="{{ $p->nama_produk }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="item-img-fallback" style="display:none">{{ ['🍞','🍰','🥐','🍪','🎂'][($p->kategori_id - 1) % 5] }}</div>
                @else
                    <div class="item-img-fallback">{{ ['🍞','🍰','🥐','🍪','🎂'][($p->kategori_id - 1) % 5] }}</div>
                @endif
                <div class="item-info">
                    <h5>{{ $p->nama_produk }}</h5>
                    <div class="price">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                    <div class="stock">Stok: {{ $p->stok }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ═══ CART PANEL ═══ --}}
    <div class="pos-cart">
        <div class="pos-cart-header">
            <h3><i class="fas fa-shopping-cart" style="background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i> Keranjang</h3>
            <span class="cart-count" id="cartCount">0</span>
        </div>

        <div class="pos-cart-items" id="cartItems">
            <div class="pos-cart-empty">
                <i class="fas fa-shopping-basket" style="background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                <span>Klik produk untuk menambahkan</span>
            </div>
        </div>

        <div class="pos-cart-footer">
            <div class="pos-total-bar">
                <span class="label">Total Pesanan</span>
                <span class="amount" id="grandTotal">Rp 0</span>
            </div>

            <div class="pos-form-row">
                <div style="position:relative;">
                    <i class="fas fa-user" style="position:absolute; left: 10px; top: 50%; transform: translateY(-50%); background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; z-index:10;"></i>
                    <input type="text" id="namaPelanggan" class="form-control" placeholder="Nama Pelanggan" style="padding-left: 35px;">
                </div>
                <div style="position:relative;">
                    <i class="fas fa-money-bill-wave-alt" id="metodeBayarIcon" style="position:absolute; left: 10px; top: 50%; transform: translateY(-50%); background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; z-index:10;"></i>
                    <select id="metodeBayar" class="form-control" style="padding-left: 35px;">
                        <option value="cash">CASH</option>
                        <option value="qris">QRIS</option>
                        <option value="mandiri">Mandiri</option>
                        <option value="bca">BCA</option>
                        <option value="bri">BRI</option>
                        <option value="bni">BNI</option>
                        <option value="e_wallet">E-Wallet</option>
                        <option value="m_banking">M-Banking</option>
                    </select>
                </div>
            </div>

            <div class="payment-compact">
                <div class="pay-label"><i class="fas fa-money-bill-wave" style="background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i> Uang Pelanggan</div>
                <div class="uang-input-wrapper">
                    <span class="currency-prefix">Rp</span>
                    <input type="text" id="uangPelanggan" class="form-control" placeholder="0" min="0" oninput="formatUang(this); hitungKembalian();">
                </div>
                <div class="quick-cash" id="quickCash"></div>
                <div class="kembalian-box neutral" id="kembalianBox">
                    <span class="kembalian-label"><i class="fas fa-coins" style="background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i> Kembalian</span>
                    <span class="kembalian-value" id="kembalianValue">Rp 0</span>
                </div>
            </div>

            <input type="hidden" id="jumlahBayar" value="0">
            <div class="pos-btn-group">
                <button class="btn btn-cancel" onclick="batalPesanan()" style="background: white; color: var(--primary); border: 2px solid var(--primary);">
                    <i class="fas fa-times" style="background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i> Batal
                </button>
                <button class="btn btn-process" onclick="prosesTransaksi()"><i class="fas fa-check-circle"></i> Proses Transaksi</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let cart = {};
let cartIndex = 0;

// Function to format number with thousand separators (dots)
function formatUang(input) {
    let value = input.value.replace(/\D/g, '');
    input.value = value ? parseFloat(value).toLocaleString('id-ID') : '';
}

// Function to get numeric value from formatted string
function getNumericValue(str) {
    return parseFloat(str.replace(/\D/g, '')) || 0;
}

// Function to update payment method icon
function updateMetodeBayarIcon() {
    const metode = document.getElementById('metodeBayar').value;
    const icon = document.getElementById('metodeBayarIcon');
    switch(metode) {
        case 'cash':
            icon.className = 'fas fa-coins';
            break;
        case 'qris':
            icon.className = 'fas fa-qrcode';
            break;
        case 'mandiri':
            icon.className = 'fas fa-building-columns';
            break;
        case 'bca':
            icon.className = 'fas fa-building-columns';
            break;
        case 'bri':
            icon.className = 'fas fa-building-columns';
            break;
        case 'bni':
            icon.className = 'fas fa-building-columns';
            break;
        case 'e_wallet':
            icon.className = 'fas fa-wallet';
            break;
        case 'm_banking':
            icon.className = 'fas fa-university';
            break;
        case 'bayar_ditempat':
            icon.className = 'fas fa-money-bill-wave-alt';
            break;
        default:
            icon.className = 'fas fa-money-bill-wave-alt';
    }
    icon.style.cssText = 'position:absolute; left: 10px; top: 50%; transform: translateY(-50%); background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; z-index:10;';
}

// Initialize on load and change
document.getElementById('metodeBayar').addEventListener('change', updateMetodeBayarIcon);
document.addEventListener('DOMContentLoaded', updateMetodeBayarIcon);

function addToCart(id, name, price, stock) {
    if (cart[id]) {
        if (cart[id].qty >= stock) { alert('Stok tidak cukup!'); return; }
        cart[id].qty++;
    } else {
        cart[id] = { id, name, price, stock, qty: 1 };
    }
    renderCart();
}

function getCartTotal() {
    let total = 0;
    Object.values(cart).forEach(i => total += i.price * i.qty);
    return total;
}

function renderCart() {
    const el = document.getElementById('cartItems');
    const countEl = document.getElementById('cartCount');
    const totalItems = Object.values(cart).reduce((sum, i) => sum + i.qty, 0);
    countEl.textContent = totalItems;

    if (Object.keys(cart).length === 0) {
        el.innerHTML = '<div class="pos-cart-empty"><i class="fas fa-shopping-basket" style="background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i><span>Klik produk untuk menambahkan</span></div>';
        document.getElementById('grandTotal').textContent = 'Rp 0';
        document.getElementById('jumlahBayar').value = 0;
        generateQuickCash(0);
        hitungKembalian();
        return;
    }
    let html = '', total = 0, num = 1;
    for (const [id, item] of Object.entries(cart)) {
        const sub = item.price * item.qty;
        total += sub;
        html += `<div class="pos-cart-item">
            <span class="cart-num">${num}</span>
            <div class="name" title="${item.name}">${item.name}</div>
            <div class="qty-ctrl">
                <button onclick="changeCartQty(${id},-1)">−</button>
                <input value="${item.qty}" readonly>
                <button onclick="changeCartQty(${id},1)">+</button>
            </div>
            <div class="sub">Rp ${sub.toLocaleString('id-ID')}</div>
            <button class="remove" onclick="removeFromCart(${id})" title="Hapus">
                <i class="fas fa-times"></i>
            </button>
        </div>`;
        num++;
    }
    el.innerHTML = html;
    document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('jumlahBayar').value = total;
    generateQuickCash(total);
    hitungKembalian();
}

function generateQuickCash(total) {
    const container = document.getElementById('quickCash');
    if (total <= 0) { container.innerHTML = ''; return; }
    const denominations = [1000, 2000, 5000, 10000, 20000, 50000, 100000];
    let suggestions = [total];
    denominations.forEach(d => {
        const rounded = Math.ceil(total / d) * d;
        if (rounded >= total && !suggestions.includes(rounded)) suggestions.push(rounded);
    });
    suggestions.sort((a, b) => a - b);
    suggestions = suggestions.slice(0, 4);
    let html = '';
    suggestions.forEach(s => {
        const label = s >= 1000 ? (s >= 1000000 ? (s/1000000) + 'jt' : (s/1000) + 'rb') : s;
        html += `<button type="button" onclick="setUangPelanggan(${s})">${s === total ? 'Uang Pas' : 'Rp ' + label}</button>`;
    });
    container.innerHTML = html;
}

function setUangPelanggan(amount) {
    const input = document.getElementById('uangPelanggan');
    input.value = amount.toLocaleString('id-ID');
    hitungKembalian();
}

function hitungKembalian() {
    const total = getCartTotal();
    const uang = getNumericValue(document.getElementById('uangPelanggan').value) || 0;
    const kembalian = uang - total;
    const box = document.getElementById('kembalianBox');
    const valueEl = document.getElementById('kembalianValue');
    const bayarEl = document.getElementById('jumlahBayar');

    bayarEl.value = uang;

    if (uang <= 0 || total <= 0) {
        box.className = 'kembalian-box neutral';
        valueEl.textContent = 'Rp 0';
        box.querySelector('.kembalian-label i').className = 'fas fa-coins';
        box.querySelector('.kembalian-label i').style.cssText = 'background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;';
    } else if (kembalian >= 0) {
        box.className = 'kembalian-box sufficient';
        valueEl.textContent = 'Rp ' + kembalian.toLocaleString('id-ID');
        box.querySelector('.kembalian-label i').className = 'fas fa-check-circle';
        box.querySelector('.kembalian-label i').style.cssText = 'background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;';
    } else {
        box.className = 'kembalian-box insufficient';
        valueEl.textContent = 'Kurang Rp ' + Math.abs(kembalian).toLocaleString('id-ID');
        box.querySelector('.kembalian-label i').className = 'fas fa-exclamation-circle';
        box.querySelector('.kembalian-label i').style.cssText = 'background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;';
    }
}

function changeCartQty(id, d) {
    if (!cart[id]) return;
    cart[id].qty += d;
    if (cart[id].qty <= 0) delete cart[id];
    else if (cart[id].qty > cart[id].stock) { cart[id].qty = cart[id].stock; alert('Stok maks!'); }
    renderCart();
}

function removeFromCart(id) { delete cart[id]; renderCart(); }

function batalPesanan() {
    if (Object.keys(cart).length === 0) { alert('Keranjang sudah kosong!'); return; }
    if (!confirm('Yakin ingin membatalkan pesanan ini?')) return;
    cart = {};
    renderCart();
    document.getElementById('namaPelanggan').value = '';
    document.getElementById('metodeBayar').selectedIndex = 0;
    document.getElementById('uangPelanggan').value = '';
    hitungKembalian();
}

function prosesTransaksi() {
    if (Object.keys(cart).length === 0) { alert('Keranjang kosong!'); return; }
    const items = Object.values(cart).map(i => ({ produk_id: i.id, jumlah: i.qty }));
    const uangPelanggan = getNumericValue(document.getElementById('uangPelanggan').value) || 0;
    const total = getCartTotal();
    if (uangPelanggan < total) { alert('Uang pelanggan kurang! Masih kurang Rp ' + (total - uangPelanggan).toLocaleString('id-ID')); return; }
    const kembalian = uangPelanggan - total;

    fetch('{{ route("penjualan.pos.proses") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({
            items, metode_bayar: document.getElementById('metodeBayar').value,
            jumlah_bayar: uangPelanggan, nama_pelanggan: document.getElementById('namaPelanggan').value
        })
    }).then(r => r.json()).then(data => {
        if (data.success) {
            alert(`✅ ${data.message}\nKode: ${data.kode}\nUang Pelanggan: Rp ${uangPelanggan.toLocaleString('id-ID')}\nKembalian: Rp ${kembalian.toLocaleString('id-ID')}`);
            cart = {}; renderCart();
            document.getElementById('namaPelanggan').value = '';
            document.getElementById('uangPelanggan').value = '';
            hitungKembalian();
        } else { alert('❌ ' + data.message); }
    }).catch(e => alert('Error: ' + e.message));
}

document.getElementById('searchProduct').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.pos-item').forEach(el => {
        el.style.display = el.dataset.name.includes(q) ? '' : 'none';
    });
});
</script>
@endsection

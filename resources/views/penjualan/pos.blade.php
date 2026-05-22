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
    .pos-container{display:grid;grid-template-columns:1fr 380px;gap:1.5rem;height:calc(100vh - 140px)}
    .pos-products{overflow-y:auto;padding-right:0.5rem}
    .pos-search{margin-bottom:1rem}
    .pos-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:0.8rem}
    .pos-item{background:white;border:2px solid var(--border);border-radius:12px;padding:1rem;text-align:center;cursor:pointer;transition:all 0.3s}
    .pos-item:hover{border-color:var(--accent);transform:scale(1.03)}
    .pos-item .emoji{font-size:2rem;margin-bottom:0.3rem}
    .pos-item h5{font-size:0.82rem;margin-bottom:0.2rem;color:var(--text-dark)}
    .pos-item .price{color:var(--primary);font-weight:600;font-size:0.85rem}
    .pos-item .stock{font-size:0.7rem;color:var(--text-light)}
    .pos-cart{background:white;border-radius:16px;border:1px solid var(--border);display:flex;flex-direction:column;overflow:hidden}
    .pos-cart-header{padding:1rem 1.5rem;background:linear-gradient(135deg,var(--secondary),var(--bg-warm));border-bottom:1px solid var(--border)}
    .pos-cart-items{flex:1;overflow-y:auto;padding:0.5rem}
    .pos-cart-item{display:flex;align-items:center;gap:0.5rem;padding:0.6rem;border-bottom:1px solid var(--border)}
    .pos-cart-item .name{flex:1;font-size:0.82rem;font-weight:500}
    .pos-cart-item .qty-ctrl{display:flex;align-items:center;gap:0.3rem}
    .pos-cart-item .qty-ctrl button{width:24px;height:24px;border:1px solid var(--border);border-radius:6px;background:white;cursor:pointer;font-size:0.8rem}
    .pos-cart-item .qty-ctrl input{width:35px;height:24px;text-align:center;border:1px solid var(--border);border-radius:6px;font-size:0.8rem}
    .pos-cart-item .sub{font-weight:600;font-size:0.82rem;color:var(--primary);min-width:80px;text-align:right}
    .pos-cart-item .remove{color:var(--danger);cursor:pointer;border:none;background:none;font-size:0.9rem}
    .pos-cart-footer{padding:1rem 1.5rem;border-top:1px solid var(--border);background:var(--bg-cream)}
    .pos-total{font-size:1.4rem;font-weight:700;color:var(--primary);text-align:right;margin-bottom:0.8rem}
</style>
@endsection

@section('content')
<div class="pos-container">
    <div class="pos-products">
        <div class="pos-search"><input type="text" class="form-control" id="searchProduct" placeholder="🔍 Cari produk..."></div>
        <div class="pos-grid" id="productGrid">
            @foreach($produk as $p)
            <div class="pos-item" onclick="addToCart({{ $p->id }}, '{{ addslashes($p->nama_produk) }}', {{ $p->harga }}, {{ $p->stok }})" data-name="{{ strtolower($p->nama_produk) }}">
                <div class="emoji">{{ ['🍞','🍰','🥐','🍪','🎂'][($p->kategori_id - 1) % 5] }}</div>
                <h5>{{ $p->nama_produk }}</h5>
                <div class="price">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                <div class="stock">Stok: {{ $p->stok }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="pos-cart">
        <div class="pos-cart-header"><h3 style="font-size:1rem"><i class="fas fa-shopping-cart"></i> Keranjang</h3></div>
        <div class="pos-cart-items" id="cartItems"><p class="text-center text-muted" style="padding:2rem">Klik produk untuk menambahkan</p></div>
        <div class="pos-cart-footer">
            <div class="pos-total">Total: <span id="grandTotal">Rp 0</span></div>
            <div class="form-group" style="margin-bottom:0.8rem">
                <input type="text" id="namaPelanggan" class="form-control" placeholder="Nama Pelanggan (Opsional)">
            </div>
            <div class="form-group" style="margin-bottom:0.8rem">
                <select id="metodeBayar" class="form-control">
                    <option value="bayar_ditempat">💵 Bayar di Tempat</option>
                    <option value="qris">📱 QRIS</option>
                    <option value="e_wallet">💰 E-Wallet</option>
                    <option value="m_banking">🏦 M-Banking</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0.8rem">
                <input type="number" id="jumlahBayar" class="form-control" placeholder="Jumlah Bayar">
            </div>
            <button class="btn btn-primary" style="width:100%" onclick="prosesTransaksi()"><i class="fas fa-check-circle"></i> Proses Transaksi</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let cart = {};
function addToCart(id, name, price, stock) {
    if (cart[id]) {
        if (cart[id].qty >= stock) { alert('Stok tidak cukup!'); return; }
        cart[id].qty++;
    } else {
        cart[id] = { id, name, price, stock, qty: 1 };
    }
    renderCart();
}

function renderCart() {
    const el = document.getElementById('cartItems');
    if (Object.keys(cart).length === 0) {
        el.innerHTML = '<p class="text-center text-muted" style="padding:2rem">Klik produk untuk menambahkan</p>';
        document.getElementById('grandTotal').textContent = 'Rp 0';
        return;
    }
    let html = '', total = 0;
    for (const [id, item] of Object.entries(cart)) {
        const sub = item.price * item.qty;
        total += sub;
        html += `<div class="pos-cart-item">
            <div class="name">${item.name}</div>
            <div class="qty-ctrl">
                <button onclick="changeCartQty(${id},-1)">-</button>
                <input value="${item.qty}" readonly>
                <button onclick="changeCartQty(${id},1)">+</button>
            </div>
            <div class="sub">Rp ${sub.toLocaleString('id-ID')}</div>
            <button class="remove" onclick="removeFromCart(${id})"><i class="fas fa-times"></i></button>
        </div>`;
    }
    el.innerHTML = html;
    document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('jumlahBayar').value = total;
}

function changeCartQty(id, d) {
    if (!cart[id]) return;
    cart[id].qty += d;
    if (cart[id].qty <= 0) delete cart[id];
    else if (cart[id].qty > cart[id].stock) { cart[id].qty = cart[id].stock; alert('Stok maks!'); }
    renderCart();
}

function removeFromCart(id) { delete cart[id]; renderCart(); }

function prosesTransaksi() {
    if (Object.keys(cart).length === 0) { alert('Keranjang kosong!'); return; }
    const items = Object.values(cart).map(i => ({ produk_id: i.id, jumlah: i.qty }));
    const jumlahBayar = parseFloat(document.getElementById('jumlahBayar').value) || 0;
    let total = 0;
    Object.values(cart).forEach(i => total += i.price * i.qty);
    if (jumlahBayar < total) { alert('Jumlah bayar kurang!'); return; }

    fetch('{{ route("penjualan.pos.proses") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({
            items, metode_bayar: document.getElementById('metodeBayar').value,
            jumlah_bayar: jumlahBayar, nama_pelanggan: document.getElementById('namaPelanggan').value
        })
    }).then(r => r.json()).then(data => {
        if (data.success) {
            alert(`✅ ${data.message}\nKode: ${data.kode}\nKembalian: Rp ${data.kembalian.toLocaleString('id-ID')}`);
            cart = {}; renderCart();
            document.getElementById('namaPelanggan').value = '';
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

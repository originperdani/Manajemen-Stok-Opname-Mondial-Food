<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProduksiController;

// Route Tes Midtrans
Route::get('/test-midtrans', function () {
    try {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'TEST-' . time(),
                'gross_amount' => 10000,
            ],
            'customer_details' => [
                'first_name' => 'Test',
                'email' => 'test@example.com',
                'phone' => '081234567890',
            ],
            'item_details' => [
                [
                    'id' => '1',
                    'price' => 10000,
                    'quantity' => 1,
                    'name' => 'Produk Test',
                ]
            ]
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('customer.test-midtrans', compact('snapToken'));
    } catch (\Exception $e) {
        dd('Midtrans Error: ' . $e->getMessage(), $e->getTraceAsString());
    }
})->name('test.midtrans');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $data = $request->validate(['email' => 'required|email']);
    $data['email'] = strtolower(trim($data['email']));

    $status = Password::sendResetLink($data);
    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => 'Link reset sandi sudah dikirim ke email Anda. Silakan cek kotak masuk atau folder spam.'])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (Request $request, string $token) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->query('email', ''),
    ]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $data = $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);
    $data['email'] = strtolower(trim($data['email']));

    $status = Password::reset(
        $data,
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );
    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', 'Sandi berhasil direset. Silakan masuk dengan sandi baru.')
        : back()->withInput($request->only('email'))->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

// Profile Routes (harus login)
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
});

Route::post('/notifications/read', [NotificationController::class, 'markRead'])
    ->middleware('auth')
    ->name('notifications.read');

// Landing Page
Route::get('/', [CustomerController::class, 'home'])->name('home');
Route::get('/tentang-kami', [CustomerController::class, 'about'])->name('about');
Route::get('/hubungi-kami', [CustomerController::class, 'contact'])->name('contact');

// Customer Routes (harus login sebagai customer)
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/', [CustomerController::class, 'home'])->name('home');
    Route::get('/keranjang', [CustomerController::class, 'keranjang'])->name('keranjang');
    Route::post('/keranjang/tambah', [CustomerController::class, 'tambahKeranjang'])->name('keranjang.tambah');
    Route::put('/keranjang/{keranjang}', [CustomerController::class, 'updateKeranjang'])->name('keranjang.update');
    Route::delete('/keranjang/{keranjang}', [CustomerController::class, 'hapusKeranjang'])->name('keranjang.hapus');
    Route::get('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CustomerController::class, 'prosesCheckout'])->name('checkout.proses');
    Route::get('/pesanan', [CustomerController::class, 'pesanan'])->name('pesanan');
    Route::get('/pesanan/{transaksi}', [CustomerController::class, 'pesananDetail'])->name('pesanan.detail');
    Route::get('/pesanan/{transaksi}/struk', [CustomerController::class, 'lihatStruk'])->name('pesanan.struk');
    Route::get('/pesanan/{transaksi}/struk/download', [CustomerController::class, 'downloadStruk'])->name('pesanan.struk.download');
    Route::post('/pesanan/{transaksi}/snap-token', [CustomerController::class, 'getSnapToken'])->name('pesanan.snap-token');
});

// Midtrans Webhook (no auth needed)
Route::post('/midtrans/webhook', [App\Http\Controllers\MidtransWebhookController::class, 'handleWebhook'])->name('midtrans.webhook');

// Katalog (accessible by all)
Route::get('/katalog', [CustomerController::class, 'katalog'])->name('katalog');
Route::get('/produk/{produk}', [CustomerController::class, 'detailProduk'])->name('produk.detail');

// Owner Routes
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [OwnerController::class, 'users'])->name('users');
    Route::get('/users/create', [OwnerController::class, 'createUser'])->name('users.create');
    Route::post('/users', [OwnerController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [OwnerController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [OwnerController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [OwnerController::class, 'deleteUser'])->name('users.delete');
    Route::get('/transaksi', [OwnerController::class, 'transaksi'])->name('transaksi');
    Route::get('/transaksi/{transaksi}', [OwnerController::class, 'detailTransaksi'])->name('transaksi.detail');
    Route::get('/transaksi/{transaksi}/struk', [OwnerController::class, 'lihatStruk'])->name('transaksi.struk');
    Route::get('/transaksi/{transaksi}/struk/download', [OwnerController::class, 'downloadStruk'])->name('transaksi.struk.download');
    Route::get('/laporan', [OwnerController::class, 'laporan'])->name('laporan');
    Route::get('/stok-produk', [OwnerController::class, 'stokProduk'])->name('stok-produk');
    Route::get('/stok-bahan', [OwnerController::class, 'stokBahan'])->name('stok-bahan');

    // Laporan Terpusat untuk Owner
    Route::get('/reports', [ReportController::class, 'ownerIndex'])->name('reports.index');
    Route::get('/reports/gudang', [ReportController::class, 'gudang'])->name('reports.gudang');
    Route::get('/reports/produksi', [ReportController::class, 'produksi'])->name('reports.produksi');
    Route::get('/reports/penjualan', [ReportController::class, 'penjualan'])->name('reports.penjualan');
    Route::get('/reports/gudang/export/{format}', [ReportController::class, 'exportGudang'])->name('reports.gudang.export');
    Route::get('/reports/produksi/export/{format}', [ReportController::class, 'exportProduksi'])->name('reports.produksi.export');
    Route::get('/reports/penjualan/export/{format}', [ReportController::class, 'exportPenjualan'])->name('reports.penjualan.export');
});

// Admin Gudang Routes
Route::middleware(['auth', 'role:admin_gudang,owner'])->prefix('gudang')->name('gudang.')->group(function () {
    Route::get('/dashboard', [GudangController::class, 'dashboard'])->name('dashboard');
    Route::get('/bahan', [GudangController::class, 'index'])->name('index');
    Route::get('/bahan/create', [GudangController::class, 'create'])->name('create');
    Route::post('/bahan', [GudangController::class, 'store'])->name('store');
    Route::get('/bahan/{bahan}/edit', [GudangController::class, 'edit'])->name('edit');
    Route::put('/bahan/{bahan}', [GudangController::class, 'update'])->name('update');
    Route::delete('/bahan/{bahan}', [GudangController::class, 'destroy'])->name('destroy');
    Route::post('/bahan/{bahan}/tambah-stok', [GudangController::class, 'tambahStok'])->name('tambah-stok');
    Route::get('/riwayat', [GudangController::class, 'riwayat'])->name('riwayat');

    // Laporan Gudang
    Route::get('/laporan', [ReportController::class, 'gudang'])->name('laporan');
    Route::get('/laporan/export/{format}', [ReportController::class, 'exportGudang'])->name('laporan.export');
});

// Admin Penjualan Routes
Route::middleware(['auth', 'role:admin_penjualan,owner'])->prefix('penjualan')->name('penjualan.')->group(function () {
    Route::get('/dashboard', [PenjualanController::class, 'dashboard'])->name('dashboard');
    Route::get('/pos', [PenjualanController::class, 'pos'])->name('pos');
    Route::post('/pos/proses', [PenjualanController::class, 'prosesPos'])->name('pos.proses');
    Route::get('/transaksi', [PenjualanController::class, 'transaksi'])->name('transaksi');
    Route::get('/transaksi/{transaksi}', [PenjualanController::class, 'detailTransaksi'])->name('transaksi.detail');
    Route::get('/transaksi/{transaksi}/struk', [PenjualanController::class, 'lihatStruk'])->name('transaksi.struk');
    Route::get('/transaksi/{transaksi}/struk/download', [PenjualanController::class, 'downloadStruk'])->name('transaksi.struk.download');
    Route::put('/transaksi/{transaksi}/status', [PenjualanController::class, 'updateStatus'])->name('transaksi.status');
    Route::post('/transaksi/{transaksi}/kirim', [PenjualanController::class, 'kirimPesanan'])->name('transaksi.kirim');

    // Laporan Penjualan
    Route::get('/laporan', [ReportController::class, 'penjualan'])->name('laporan');
    Route::get('/laporan/export/{format}', [ReportController::class, 'exportPenjualan'])->name('laporan.export');
});

// Admin Produksi Routes
Route::middleware(['auth', 'role:admin_produksi,owner'])->prefix('produksi')->name('produksi.')->group(function () {
    Route::get('/dashboard', [ProduksiController::class, 'dashboard'])->name('dashboard');
    Route::get('/produk', [ProduksiController::class, 'produk'])->name('produk');
    Route::get('/produk/create', [ProduksiController::class, 'createProduk'])->name('produk.create');
    Route::post('/produk', [ProduksiController::class, 'storeProduk'])->name('produk.store');
    Route::get('/produk/{produk}/edit', [ProduksiController::class, 'editProduk'])->name('produk.edit');
    Route::put('/produk/{produk}', [ProduksiController::class, 'updateProduk'])->name('produk.update');
    Route::delete('/produk/{produk}', [ProduksiController::class, 'deleteProduk'])->name('produk.delete');
    Route::get('/kategori', [ProduksiController::class, 'kategori'])->name('kategori');
    Route::post('/kategori', [ProduksiController::class, 'storeKategori'])->name('kategori.store');
    Route::delete('/kategori/{kategori}', [ProduksiController::class, 'deleteKategori'])->name('kategori.delete');
    Route::get('/resep', [ProduksiController::class, 'resep'])->name('resep');
    Route::get('/resep/create', [ProduksiController::class, 'createResep'])->name('resep.create');
    Route::post('/resep', [ProduksiController::class, 'storeResep'])->name('resep.store');
    Route::delete('/resep/{resep}', [ProduksiController::class, 'deleteResep'])->name('resep.delete');
    Route::get('/input', [ProduksiController::class, 'inputProduksi'])->name('input');
    Route::post('/input', [ProduksiController::class, 'prosesProduksi'])->name('input.proses');
    Route::get('/riwayat', [ProduksiController::class, 'riwayatProduksi'])->name('riwayat');

    // Laporan Produksi
    Route::get('/laporan', [ReportController::class, 'produksi'])->name('laporan');
    Route::get('/laporan/export/{format}', [ReportController::class, 'exportProduksi'])->name('laporan.export');
});

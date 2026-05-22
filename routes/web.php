<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\CustomerController;

use App\Http\Controllers\ReportController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
});

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
Route::middleware(['auth', 'role:admin_gudang'])->prefix('gudang')->name('gudang.')->group(function () {
    Route::get('/dashboard', [GudangController::class, 'dashboard'])->name('dashboard');
    Route::get('/bahan', [GudangController::class, 'index'])->name('index');
    Route::get('/bahan/create', [GudangController::class, 'create'])->name('create');
    Route::post('/bahan', [GudangController::class, 'store'])->name('store');
    Route::get('/bahan/{bahan}/edit', [GudangController::class, 'edit'])->name('edit');
    Route::put('/bahan/{bahan}', [GudangController::class, 'update'])->name('update');
    Route::delete('/bahan/{bahan}', [GudangController::class, 'destroy'])->name('destroy');
    Route::post('/bahan/{bahan}/tambah-stok', [GudangController::class, 'tambahStok'])->name('tambah-stok');

    // Laporan Gudang
    Route::get('/laporan', [ReportController::class, 'gudang'])->name('laporan');
    Route::get('/laporan/export/{format}', [ReportController::class, 'exportGudang'])->name('laporan.export');
});

// Admin Penjualan Routes
Route::middleware(['auth', 'role:admin_penjualan'])->prefix('penjualan')->name('penjualan.')->group(function () {
    Route::get('/dashboard', [PenjualanController::class, 'dashboard'])->name('dashboard');
    Route::get('/pos', [PenjualanController::class, 'pos'])->name('pos');
    Route::post('/pos/proses', [PenjualanController::class, 'prosesPos'])->name('pos.proses');
    Route::get('/transaksi', [PenjualanController::class, 'transaksi'])->name('transaksi');
    Route::get('/transaksi/{transaksi}', [PenjualanController::class, 'detailTransaksi'])->name('transaksi.detail');
    Route::put('/transaksi/{transaksi}/status', [PenjualanController::class, 'updateStatus'])->name('transaksi.status');
    Route::post('/transaksi/{transaksi}/kirim', [PenjualanController::class, 'kirimPesanan'])->name('transaksi.kirim');

    // Laporan Penjualan
    Route::get('/laporan', [ReportController::class, 'penjualan'])->name('laporan');
    Route::get('/laporan/export/{format}', [ReportController::class, 'exportPenjualan'])->name('laporan.export');
});

// Admin Produksi Routes
Route::middleware(['auth', 'role:admin_produksi'])->prefix('produksi')->name('produksi.')->group(function () {
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

    // Laporan Produksi
    Route::get('/laporan', [ReportController::class, 'produksi'])->name('laporan');
    Route::get('/laporan/export/{format}', [ReportController::class, 'exportProduksi'])->name('laporan.export');
});

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\KategoriProduk;
use App\Models\Produk;
use App\Models\BahanBaku;
use App\Models\Resep;
use App\Models\ResepDetail;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === USERS ===
        User::create(['name' => 'Owner Mondial', 'email' => 'owner@mondial.com', 'password' => Hash::make('password'), 'role' => 'owner', 'phone' => '081234567890']);
        User::create(['name' => 'Admin Gudang', 'email' => 'gudang@mondial.com', 'password' => Hash::make('password'), 'role' => 'admin_gudang', 'phone' => '081234567891']);
        User::create(['name' => 'Admin Penjualan', 'email' => 'penjualan@mondial.com', 'password' => Hash::make('password'), 'role' => 'admin_penjualan', 'phone' => '081234567892']);
        User::create(['name' => 'Admin Produksi', 'email' => 'produksi@mondial.com', 'password' => Hash::make('password'), 'role' => 'admin_produksi', 'phone' => '081234567893']);
        User::create(['name' => 'Budi Customer', 'email' => 'customer@mondial.com', 'password' => Hash::make('password'), 'role' => 'customer', 'phone' => '081234567894', 'alamat' => 'Jl. Merdeka No. 10, Jakarta']);

        // === KATEGORI ===
        $roti = KategoriProduk::create(['nama_kategori' => 'Roti', 'slug' => 'roti', 'deskripsi' => 'Aneka roti segar panggang']);
        $kue = KategoriProduk::create(['nama_kategori' => 'Kue', 'slug' => 'kue', 'deskripsi' => 'Kue tradisional dan modern']);
        $pastry = KategoriProduk::create(['nama_kategori' => 'Pastry', 'slug' => 'pastry', 'deskripsi' => 'Pastry premium']);
        $cookies = KategoriProduk::create(['nama_kategori' => 'Cookies', 'slug' => 'cookies', 'deskripsi' => 'Kue kering dan cookies']);
        $cake = KategoriProduk::create(['nama_kategori' => 'Cake', 'slug' => 'cake', 'deskripsi' => 'Birthday cake dan custom cake']);

        // === PRODUK ===
        $produkData = [
            ['kategori_id' => $roti->id, 'nama_produk' => 'Roti Tawar Gandum', 'slug' => 'roti-tawar-gandum', 'harga' => 25000, 'stok' => 50, 'stok_minimum' => 10, 'satuan' => 'pcs', 'berat' => 400, 'is_featured' => true, 'gambar' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&q=80', 'deskripsi' => 'Roti tawar gandum utuh, lembut dan sehat. Cocok untuk sarapan sehari-hari.'],
            ['kategori_id' => $roti->id, 'nama_produk' => 'Roti Sobek Cokelat', 'slug' => 'roti-sobek-cokelat', 'harga' => 30000, 'stok' => 35, 'stok_minimum' => 8, 'satuan' => 'pcs', 'berat' => 350, 'is_featured' => true, 'gambar' => 'https://images.unsplash.com/photo-1549931319-a545dcf3bc73?w=800&q=80', 'deskripsi' => 'Roti sobek dengan isian cokelat lumer yang menggoda.'],
            ['kategori_id' => $roti->id, 'nama_produk' => 'Roti Manis Kasur', 'slug' => 'roti-manis-kasur', 'harga' => 20000, 'stok' => 40, 'stok_minimum' => 10, 'satuan' => 'pcs', 'berat' => 300, 'gambar' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=800&q=80', 'deskripsi' => 'Roti manis klasik yang empuk dan wangi mentega.'],
            ['kategori_id' => $roti->id, 'nama_produk' => 'Croissant Butter', 'slug' => 'croissant-butter', 'harga' => 18000, 'stok' => 25, 'stok_minimum' => 5, 'satuan' => 'pcs', 'berat' => 80, 'is_featured' => true, 'gambar' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=800&q=80', 'deskripsi' => 'Croissant renyah berlapis dengan butter premium Perancis.'],
            ['kategori_id' => $kue->id, 'nama_produk' => 'Bolu Pandan', 'slug' => 'bolu-pandan', 'harga' => 45000, 'stok' => 20, 'stok_minimum' => 5, 'satuan' => 'pcs', 'berat' => 500, 'is_featured' => true, 'gambar' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=800&q=80', 'deskripsi' => 'Bolu pandan lembut dengan aroma pandan asli yang memikat.'],
            ['kategori_id' => $kue->id, 'nama_produk' => 'Lapis Legit', 'slug' => 'lapis-legit', 'harga' => 120000, 'stok' => 10, 'stok_minimum' => 3, 'satuan' => 'pcs', 'berat' => 800, 'is_featured' => true, 'gambar' => 'https://images.unsplash.com/photo-1582760926402-8e7c8d4da3bc?w=800&q=80', 'deskripsi' => 'Kue lapis legit premium, dibuat dengan rempah pilihan.'],
            ['kategori_id' => $kue->id, 'nama_produk' => 'Brownies Panggang', 'slug' => 'brownies-panggang', 'harga' => 55000, 'stok' => 15, 'stok_minimum' => 5, 'satuan' => 'pcs', 'berat' => 400, 'gambar' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=800&q=80', 'deskripsi' => 'Brownies panggang dengan dark chocolate Belgium berkualitas.'],
            ['kategori_id' => $pastry->id, 'nama_produk' => 'Danish Pastry', 'slug' => 'danish-pastry', 'harga' => 22000, 'stok' => 30, 'stok_minimum' => 5, 'satuan' => 'pcs', 'berat' => 100, 'is_featured' => true, 'gambar' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&q=80', 'deskripsi' => 'Danish pastry dengan topping buah segar dan krim custard.'],
            ['kategori_id' => $pastry->id, 'nama_produk' => 'Eclair Cokelat', 'slug' => 'eclair-cokelat', 'harga' => 28000, 'stok' => 20, 'stok_minimum' => 5, 'satuan' => 'pcs', 'berat' => 120, 'gambar' => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?w=800&q=80', 'deskripsi' => 'Eclair isi krim cokelat Belgia dengan glaze mengkilap.'],
            ['kategori_id' => $cookies->id, 'nama_produk' => 'Choco Chip Cookies', 'slug' => 'choco-chip-cookies', 'harga' => 35000, 'stok' => 25, 'stok_minimum' => 5, 'satuan' => 'toples', 'berat' => 250, 'gambar' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?w=800&q=80', 'deskripsi' => 'Cookies dengan chocolate chip premium, renyah di luar lembut di dalam.'],
            ['kategori_id' => $cookies->id, 'nama_produk' => 'Nastar Premium', 'slug' => 'nastar-premium', 'harga' => 85000, 'stok' => 15, 'stok_minimum' => 3, 'satuan' => 'toples', 'berat' => 500, 'is_featured' => true, 'gambar' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=800&q=80', 'deskripsi' => 'Nastar isi selai nanas asli, lembut and lumer di mulut.'],
            ['kategori_id' => $cake->id, 'nama_produk' => 'Black Forest Cake', 'slug' => 'black-forest-cake', 'harga' => 180000, 'stok' => 8, 'stok_minimum' => 2, 'satuan' => 'pcs', 'berat' => 1200, 'is_featured' => true, 'gambar' => 'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=800&q=80', 'deskripsi' => 'Black forest cake klasik dengan cherry dan cokelat serut.'],
            ['kategori_id' => $cake->id, 'nama_produk' => 'Red Velvet Cake', 'slug' => 'red-velvet-cake', 'harga' => 200000, 'stok' => 5, 'stok_minimum' => 2, 'satuan' => 'pcs', 'berat' => 1000, 'gambar' => 'https://images.unsplash.com/photo-1586788680434-30d324671ff6?w=800&q=80', 'deskripsi' => 'Red velvet cake premium dengan cream cheese frosting.'],
            ['kategori_id' => $cake->id, 'nama_produk' => 'Cheese Cake Original', 'slug' => 'cheese-cake-original', 'harga' => 165000, 'stok' => 6, 'stok_minimum' => 2, 'satuan' => 'pcs', 'berat' => 900, 'gambar' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=800&q=80', 'deskripsi' => 'Cheesecake New York style dengan tekstur creamy yang lembut.'],
            ['kategori_id' => $roti->id, 'nama_produk' => 'Cinnamon Roll', 'slug' => 'cinnamon-roll', 'harga' => 15000, 'stok' => 30, 'stok_minimum' => 8, 'satuan' => 'pcs', 'berat' => 100, 'is_featured' => true, 'gambar' => 'https://images.unsplash.com/photo-1509365465985-25d11c17e812?w=800&q=80', 'deskripsi' => 'Cinnamon roll hangat dengan glaze manis.'],
        ];

        foreach ($produkData as $p) {
            Produk::create($p);
        }

        // === BAHAN BAKU ===
        $tepung = BahanBaku::create(['nama_bahan' => 'Tepung Terigu Protein Tinggi', 'satuan' => 'kg', 'stok' => 100, 'stok_minimum' => 20, 'harga_per_satuan' => 12000, 'supplier' => 'PT Bogasari']);
        $gula = BahanBaku::create(['nama_bahan' => 'Gula Pasir', 'satuan' => 'kg', 'stok' => 50, 'stok_minimum' => 10, 'harga_per_satuan' => 14000, 'supplier' => 'PT Gulaku']);
        $telur = BahanBaku::create(['nama_bahan' => 'Telur Ayam', 'satuan' => 'kg', 'stok' => 30, 'stok_minimum' => 10, 'harga_per_satuan' => 28000, 'supplier' => 'Supplier Telur Jaya']);
        $mentega = BahanBaku::create(['nama_bahan' => 'Mentega / Butter', 'satuan' => 'kg', 'stok' => 20, 'stok_minimum' => 5, 'harga_per_satuan' => 45000, 'supplier' => 'PT Wijsman']);
        $susu = BahanBaku::create(['nama_bahan' => 'Susu Cair UHT', 'satuan' => 'liter', 'stok' => 40, 'stok_minimum' => 10, 'harga_per_satuan' => 16000, 'supplier' => 'PT Frisian Flag']);
        $cokelat = BahanBaku::create(['nama_bahan' => 'Cokelat Batang (Dark)', 'satuan' => 'kg', 'stok' => 15, 'stok_minimum' => 5, 'harga_per_satuan' => 85000, 'supplier' => 'PT Van Houten']);
        $ragi = BahanBaku::create(['nama_bahan' => 'Ragi Instant', 'satuan' => 'gram', 'stok' => 2000, 'stok_minimum' => 500, 'harga_per_satuan' => 50, 'supplier' => 'Fermipan']);
        BahanBaku::create(['nama_bahan' => 'Keju Cheddar', 'satuan' => 'kg', 'stok' => 10, 'stok_minimum' => 3, 'harga_per_satuan' => 95000, 'supplier' => 'PT Kraft']);
        BahanBaku::create(['nama_bahan' => 'Cream Cheese', 'satuan' => 'kg', 'stok' => 8, 'stok_minimum' => 3, 'harga_per_satuan' => 120000, 'supplier' => 'Philadelphia']);
        BahanBaku::create(['nama_bahan' => 'Pandan Paste', 'satuan' => 'liter', 'stok' => 5, 'stok_minimum' => 2, 'harga_per_satuan' => 35000, 'supplier' => 'Koepoe Koepoe']);
        BahanBaku::create(['nama_bahan' => 'Whipping Cream', 'satuan' => 'liter', 'stok' => 12, 'stok_minimum' => 4, 'harga_per_satuan' => 55000, 'supplier' => 'Anchor']);
        BahanBaku::create(['nama_bahan' => 'Selai Nanas', 'satuan' => 'kg', 'stok' => 8, 'stok_minimum' => 3, 'harga_per_satuan' => 40000, 'supplier' => 'Homemade']);

        // === RESEP ===
        $resepRotiTawar = Resep::create([
            'produk_id' => 1, 'nama_resep' => 'Resep Roti Tawar Gandum', 'hasil_produksi' => 10, 'waktu_produksi' => 120,
            'instruksi' => '1. Campur tepung, gula, ragi, dan garam. 2. Tambahkan telur, susu, dan mentega. 3. Uleni hingga kalis. 4. Diamkan 1 jam. 5. Bentuk dan masukkan loyang. 6. Diamkan 30 menit lagi. 7. Panggang 180°C selama 30 menit.'
        ]);
        ResepDetail::create(['resep_id' => $resepRotiTawar->id, 'bahan_baku_id' => $tepung->id, 'jumlah' => 2, 'satuan' => 'kg']);
        ResepDetail::create(['resep_id' => $resepRotiTawar->id, 'bahan_baku_id' => $gula->id, 'jumlah' => 0.3, 'satuan' => 'kg']);
        ResepDetail::create(['resep_id' => $resepRotiTawar->id, 'bahan_baku_id' => $telur->id, 'jumlah' => 0.5, 'satuan' => 'kg']);
        ResepDetail::create(['resep_id' => $resepRotiTawar->id, 'bahan_baku_id' => $mentega->id, 'jumlah' => 0.3, 'satuan' => 'kg']);
        ResepDetail::create(['resep_id' => $resepRotiTawar->id, 'bahan_baku_id' => $susu->id, 'jumlah' => 0.5, 'satuan' => 'liter']);
        ResepDetail::create(['resep_id' => $resepRotiTawar->id, 'bahan_baku_id' => $ragi->id, 'jumlah' => 20, 'satuan' => 'gram']);

        $resepBrownies = Resep::create([
            'produk_id' => 7, 'nama_resep' => 'Resep Brownies Panggang', 'hasil_produksi' => 5, 'waktu_produksi' => 90,
            'instruksi' => '1. Lelehkan cokelat dan mentega. 2. Kocok telur dan gula. 3. Campur semua bahan. 4. Tuang ke loyang. 5. Panggang 175°C selama 25 menit.'
        ]);
        ResepDetail::create(['resep_id' => $resepBrownies->id, 'bahan_baku_id' => $tepung->id, 'jumlah' => 0.5, 'satuan' => 'kg']);
        ResepDetail::create(['resep_id' => $resepBrownies->id, 'bahan_baku_id' => $gula->id, 'jumlah' => 0.4, 'satuan' => 'kg']);
        ResepDetail::create(['resep_id' => $resepBrownies->id, 'bahan_baku_id' => $telur->id, 'jumlah' => 0.6, 'satuan' => 'kg']);
        ResepDetail::create(['resep_id' => $resepBrownies->id, 'bahan_baku_id' => $mentega->id, 'jumlah' => 0.3, 'satuan' => 'kg']);
        ResepDetail::create(['resep_id' => $resepBrownies->id, 'bahan_baku_id' => $cokelat->id, 'jumlah' => 0.5, 'satuan' => 'kg']);
    }
}

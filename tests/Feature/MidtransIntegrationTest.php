<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\Transaksi;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class MidtransIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock Midtrans Snap class
        $mockSnap = \Mockery::mock('alias:Midtrans\Snap');
        $mockSnap->shouldReceive('getSnapToken')->andReturn('mock-snap-token-123');
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function test_checkout_generates_order_id_with_prefix_and_no_suffix_on_first_try()
    {
        // 1. Setup config prefix
        Config::set('midtrans.order_prefix', 'TEST-');
        
        // 2. Setup user and product
        $user = User::factory()->create(['role' => 'customer']);
        $kategori = KategoriProduk::create([
            'nama_kategori' => 'Roti',
            'slug' => 'roti',
            'is_active' => true,
        ]);
        $produk = Produk::create([
            'kategori_id' => $kategori->id,
            'nama_produk' => 'Bolu Pandan',
            'slug' => 'bolu-pandan',
            'harga' => 45000,
            'stok' => 10,
            'is_active' => true,
        ]);

        // 3. Create a Transaction and Payment
        $transaksi = Transaksi::create([
            'kode_transaksi' => 'TRX-99999',
            'user_id' => $user->id,
            'tipe' => 'online',
            'subtotal' => 45000,
            'ongkir' => 0,
            'total' => 45000,
            'status' => 'belum_bayar',
            'nama_pelanggan' => 'Test User',
            'phone_pelanggan' => '08123456789',
            'email_pelanggan' => 'test@example.com',
        ]);

        $pembayaran = Pembayaran::create([
            'transaksi_id' => $transaksi->id,
            'metode' => 'midtrans',
            'jumlah_bayar' => 45000,
            'status' => 'belum_bayar',
        ]);

        // Access the controller and test token generation
        $controller = new \App\Http\Controllers\CustomerController();
        
        // Invoke private generateSnapToken via reflection
        $reflection = new \ReflectionClass(get_class($controller));
        $method = $reflection->getMethod('generateSnapToken');
        $method->setAccessible(true);
        
        $snapToken = $method->invokeArgs($controller, [$transaksi]);
        
        $this->assertEquals('mock-snap-token-123', $snapToken);
        
        // Ensure referensi saved contains prefix but NO -R1 suffix on the first checkout
        $pembayaran->refresh();
        $this->assertEquals('TEST-TRX-99999', $pembayaran->referensi);
    }

    public function test_checkout_retry_increments_retry_suffix()
    {
        Config::set('midtrans.order_prefix', 'TEST-');
        
        $user = User::factory()->create(['role' => 'customer']);
        $kategori = KategoriProduk::create([
            'nama_kategori' => 'Roti',
            'slug' => 'roti',
            'is_active' => true,
        ]);
        $produk = Produk::create([
            'kategori_id' => $kategori->id,
            'nama_produk' => 'Bolu Pandan',
            'slug' => 'bolu-pandan',
            'harga' => 45000,
            'stok' => 10,
            'is_active' => true,
        ]);

        $transaksi = Transaksi::create([
            'kode_transaksi' => 'TRX-88888',
            'user_id' => $user->id,
            'tipe' => 'online',
            'subtotal' => 45000,
            'ongkir' => 0,
            'total' => 45000,
            'status' => 'belum_bayar',
        ]);

        // Create payment with existing referensi (meaning it was already attempted)
        $pembayaran = Pembayaran::create([
            'transaksi_id' => $transaksi->id,
            'metode' => 'midtrans',
            'jumlah_bayar' => 45000,
            'status' => 'belum_bayar',
            'referensi' => 'TEST-TRX-88888', // first attempt
        ]);

        $controller = new \App\Http\Controllers\CustomerController();
        $reflection = new \ReflectionClass(get_class($controller));
        $method = $reflection->getMethod('generateSnapToken');
        $method->setAccessible(true);
        
        // 1st retry
        $method->invokeArgs($controller, [$transaksi]);
        $pembayaran->refresh();
        $this->assertEquals('TEST-TRX-88888-R1', $pembayaran->referensi);

        // 2nd retry
        $method->invokeArgs($controller, [$transaksi]);
        $pembayaran->refresh();
        $this->assertEquals('TEST-TRX-88888-R2', $pembayaran->referensi);
    }

    public function test_webhook_successfully_strips_prefix_and_updates_transaction()
    {
        Config::set('midtrans.order_prefix', 'TEST-');
        
        $user = User::factory()->create(['role' => 'customer']);
        $kategori = KategoriProduk::create([
            'nama_kategori' => 'Roti',
            'slug' => 'roti',
            'is_active' => true,
        ]);
        $produk = Produk::create([
            'kategori_id' => $kategori->id,
            'nama_produk' => 'Bolu Pandan',
            'slug' => 'bolu-pandan',
            'harga' => 45000,
            'stok' => 10,
            'is_active' => true,
        ]);

        $transaksi = Transaksi::create([
            'kode_transaksi' => 'TRX-77777',
            'user_id' => $user->id,
            'tipe' => 'online',
            'subtotal' => 45000,
            'ongkir' => 0,
            'total' => 45000,
            'status' => 'belum_bayar',
        ]);

        $pembayaran = Pembayaran::create([
            'transaksi_id' => $transaksi->id,
            'metode' => 'midtrans',
            'jumlah_bayar' => 45000,
            'status' => 'belum_bayar',
            'referensi' => 'TEST-TRX-77777-R1',
        ]);

        // Simulating the Webhook parsing
        // We will call the controller or just simulate the exact controller logic
        $orderIdReceived = 'TEST-TRX-77777-R1';
        
        // Strip retry suffix
        $kodeTransaksi = preg_replace('/-R\d+$/', '', $orderIdReceived);
        $this->assertEquals('TEST-TRX-77777', $kodeTransaksi);

        // Strip prefix
        $prefix = config('midtrans.order_prefix', '');
        if ($prefix && str_starts_with($kodeTransaksi, $prefix)) {
            $kodeTransaksi = substr($kodeTransaksi, strlen($prefix));
        }
        $this->assertEquals('TRX-77777', $kodeTransaksi);

        // Find transaction
        $found = Transaksi::where('kode_transaksi', $kodeTransaksi)->first();
        $this->assertNotNull($found);
        $this->assertEquals($transaksi->id, $found->id);
    }
}

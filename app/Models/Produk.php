<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Produk extends Model
{
    protected $table = 'produk';
    protected $fillable = [
        'kategori_id', 'nama_produk', 'slug', 'deskripsi', 'harga', 'stok',
        'stok_minimum', 'gambar', 'satuan', 'berat', 'is_active', 'is_featured',
    ];
    protected $casts = ['harga' => 'decimal:2', 'berat' => 'decimal:2', 'is_active' => 'boolean', 'is_featured' => 'boolean'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nama_produk);
            }
        });

        static::saved(function () {
            self::bumpCacheVersion();
        });

        static::deleted(function () {
            self::bumpCacheVersion();
        });
    }

    public function kategori() { return $this->belongsTo(KategoriProduk::class, 'kategori_id'); }
    public function resep() { return $this->hasMany(Resep::class, 'produk_id'); }
    public function detailTransaksi() { return $this->hasMany(DetailTransaksi::class, 'produk_id'); }
    public function keranjang() { return $this->hasMany(Keranjang::class, 'produk_id'); }
    public function produksi() { return $this->hasMany(Produksi::class, 'produk_id'); }

    public function isStokMenipis(): bool { return $this->stok <= $this->stok_minimum; }

    public function getHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    private static function bumpCacheVersion(): void
    {
        $key = 'cache_versions.produk';
        Cache::forever($key, ((int) Cache::get($key, 1)) + 1);
    }
}

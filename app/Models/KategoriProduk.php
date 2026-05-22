<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class KategoriProduk extends Model
{
    protected $table = 'kategori_produk';
    protected $fillable = ['nama_kategori', 'slug', 'deskripsi', 'gambar', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nama_kategori);
            }
        });

        static::saved(function () {
            self::bumpCacheVersion();
        });

        static::deleted(function () {
            self::bumpCacheVersion();
        });
    }

    public function produk() { return $this->hasMany(Produk::class, 'kategori_id'); }

    private static function bumpCacheVersion(): void
    {
        $key = 'cache_versions.kategori_produk';
        Cache::forever($key, ((int) Cache::get($key, 1)) + 1);
    }
}

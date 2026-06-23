<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements CanResetPasswordContract
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, CanResetPassword;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'alamat', 'foto', 'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isOwner(): bool { return $this->role === 'owner'; }
    public function isAdminGudang(): bool { return $this->role === 'admin_gudang'; }
    public function isAdminPenjualan(): bool { return $this->role === 'admin_penjualan'; }
    public function isAdminProduksi(): bool { return $this->role === 'admin_produksi'; }
    public function isCustomer(): bool { return $this->role === 'customer'; }
    public function isAdmin(): bool { return in_array($this->role, ['owner', 'admin_gudang', 'admin_penjualan', 'admin_produksi']); }

    // Format nama role (contoh: Admin Produksi)
    public function getRoleNameAttribute(): string {
        return ucwords(str_replace('_', ' ', $this->role));
    }

    // Format penanggung jawab (contoh: Admin Produksi/Dewi Persik)
    public function getPenanggungJawabAttribute(): string {
        return $this->role_name . '/' . $this->name;
    }

    public function transaksi() { return $this->hasMany(Transaksi::class); }
    public function keranjang() { return $this->hasMany(Keranjang::class); }
    public function produksi() { return $this->hasMany(Produksi::class); }
}

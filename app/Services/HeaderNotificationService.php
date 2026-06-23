<?php

namespace App\Services;

use App\Models\BahanBaku;
use App\Models\Produksi;
use App\Models\Produk;
use App\Models\StokLog;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Throwable;

class HeaderNotificationService
{
    public function forUser(User $user): array
    {
        try {
            $readAt = $this->readTimestamp($user);
            $items = match ($user->role) {
                'owner' => $this->ownerNotifications(),
                'admin_penjualan' => $this->salesNotifications(),
                'admin_gudang' => $this->warehouseNotifications(),
                'admin_produksi' => $this->productionNotifications(),
                'customer' => $this->customerNotifications($user),
                default => [],
            };

            $visibleItems = collect($items)
                ->sortByDesc('_sort')
                ->take(8)
                ->values();

            $unreadCount = $visibleItems
                ->filter(fn (array $item) => (int) ($item['_sort'] ?? 0) > $readAt)
                ->count();

            $displayItems = $visibleItems
                ->map(function (array $item) {
                    unset($item['_sort']);
                    return $item;
                })
                ->values();

            return [
                'count' => $unreadCount,
                'items' => $displayItems->all(),
            ];
        } catch (Throwable) {
            return ['count' => 0, 'items' => []];
        }
    }

    public function markRead(User $user): void
    {
        Cache::forever($this->readCacheKey($user), now()->getTimestamp());
    }

    private function ownerNotifications(): array
    {
        return [
            ...$this->transactionItems(3, $this->routeUrl('owner.transaksi')),
            ...$this->lowIngredientItems(2, $this->routeUrl('owner.stok-bahan')),
            ...$this->lowProductItems(2, $this->routeUrl('owner.stok-produk')),
            ...$this->productionItems(2, $this->routeUrl('owner.reports.produksi', fallback: $this->routeUrl('owner.dashboard'))),
        ];
    }

    private function salesNotifications(): array
    {
        return [
            ...$this->pendingOnlineOrderItems(4),
            ...$this->transactionItems(4, null, ['diproses', 'dikirim', 'selesai']),
        ];
    }

    private function warehouseNotifications(): array
    {
        return [
            ...$this->lowIngredientItems(4, $this->routeUrl('gudang.index')),
            ...$this->stockLogItems(4, $this->routeUrl('gudang.riwayat'), 'bahan_baku'),
        ];
    }

    private function productionNotifications(): array
    {
        return [
            ...$this->lowProductItems(4, $this->routeUrl('produksi.produk')),
            ...$this->productionItems(4, $this->routeUrl('produksi.riwayat')),
            ...$this->pendingOnlineOrderItems(2, $this->routeUrl('produksi.dashboard')),
        ];
    }

    private function customerNotifications(User $user): array
    {
        if (! $this->tableReady('transaksi')) {
            return [];
        }

        return Transaksi::with(['pembayaran', 'pengiriman'])
            ->where('user_id', $user->id)
            ->latest('updated_at')
            ->take(6)
            ->get()
            ->map(fn (Transaksi $transaksi) => $this->customerTransactionItem($transaksi))
            ->all();
    }

    private function pendingOnlineOrderItems(int $limit, ?string $url = null): array
    {
        if (! $this->tableReady('transaksi')) {
            return [];
        }

        return Transaksi::with('user')
            ->where('tipe', 'online')
            ->where('status', 'pending')
            ->latest()
            ->take($limit)
            ->get()
            ->map(fn (Transaksi $transaksi) => $this->adminTransactionItem($transaksi, $url))
            ->all();
    }

    private function transactionItems(int $limit, ?string $url = null, array $statuses = []): array
    {
        if (! $this->tableReady('transaksi')) {
            return [];
        }

        $query = Transaksi::with(['user', 'pembayaran', 'pengiriman'])->latest('updated_at');

        if ($statuses !== []) {
            $query->whereIn('status', $statuses);
        }

        return $query->take($limit)
            ->get()
            ->map(fn (Transaksi $transaksi) => $this->adminTransactionItem($transaksi, $url))
            ->all();
    }

    private function lowIngredientItems(int $limit, string $url): array
    {
        if (! $this->tableReady('bahan_baku')) {
            return [];
        }

        return BahanBaku::whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')
            ->take($limit)
            ->get()
            ->map(function (BahanBaku $bahan) use ($url) {
                return $this->item(
                    icon: 'fa-box-open',
                    title: "Stok {$bahan->nama_bahan} menipis",
                    description: 'Sisa ' . $this->number($bahan->stok) . " {$bahan->satuan}, minimum " . $this->number($bahan->stok_minimum),
                    url: $url,
                    time: $bahan->updated_at,
                    tone: 'warning'
                );
            })
            ->all();
    }

    private function lowProductItems(int $limit, string $url): array
    {
        if (! $this->tableReady('produk')) {
            return [];
        }

        return Produk::whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')
            ->take($limit)
            ->get()
            ->map(function (Produk $produk) use ($url) {
                return $this->item(
                    icon: 'fa-bread-slice',
                    title: "Stok {$produk->nama_produk} rendah",
                    description: 'Sisa ' . $this->number($produk->stok) . " {$produk->satuan}, minimum " . $this->number($produk->stok_minimum),
                    url: $url,
                    time: $produk->updated_at,
                    tone: 'warning'
                );
            })
            ->all();
    }

    private function productionItems(int $limit, string $url): array
    {
        if (! $this->tableReady('produksi')) {
            return [];
        }

        return Produksi::with('produk')
            ->latest('updated_at')
            ->take($limit)
            ->get()
            ->map(function (Produksi $produksi) use ($url) {
                $status = $this->statusLabel($produksi->status);

                return $this->item(
                    icon: 'fa-industry',
                    title: "Produksi {$status}",
                    description: ($produksi->produk?->nama_produk ?? 'Produk') . ' - ' . $produksi->jumlah_produksi . ' pcs',
                    url: $url,
                    time: $produksi->updated_at,
                    tone: $produksi->status === 'selesai' ? 'success' : 'info'
                );
            })
            ->all();
    }

    private function stockLogItems(int $limit, string $url, ?string $tipe = null): array
    {
        if (! $this->tableReady('stok_log')) {
            return [];
        }

        $query = StokLog::with('user')->latest();
        
        if ($tipe) {
            $query->where('tipe', $tipe);
        }

        return $query
            ->take($limit)
            ->get()
            ->map(function (StokLog $log) use ($url) {
                $jenis = $log->jenis === 'masuk' ? 'Stok masuk' : 'Stok keluar';

                return $this->item(
                    icon: $log->jenis === 'masuk' ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down',
                    title: $jenis,
                    description: ($log->keterangan ?: ucfirst(str_replace('_', ' ', $log->tipe))) . ' - ' . $this->number($log->jumlah),
                    url: $url,
                    time: $log->created_at,
                    tone: $log->jenis === 'masuk' ? 'success' : 'danger'
                );
            })
            ->all();
    }

    private function adminTransactionItem(Transaksi $transaksi, ?string $url = null): array
    {
        $customer = $transaksi->nama_pelanggan ?: ($transaksi->user?->name ?: 'Pelanggan');
        $href = $url ?: $this->routeUrl('penjualan.transaksi.detail', $transaksi);

        return $this->item(
            icon: $this->transactionIcon($transaksi->status),
            title: $this->adminTransactionTitle($transaksi),
            description: "{$customer} - {$transaksi->kode_transaksi} - " . $this->money($transaksi->total),
            url: $href,
            time: $transaksi->updated_at,
            tone: $this->transactionTone($transaksi->status)
        );
    }

    private function customerTransactionItem(Transaksi $transaksi): array
    {
        return $this->item(
            icon: $this->transactionIcon($transaksi->status),
            title: $this->customerTransactionTitle($transaksi),
            description: "{$transaksi->kode_transaksi} - " . $this->money($transaksi->total),
            url: $this->routeUrl('customer.pesanan.detail', $transaksi),
            time: $transaksi->updated_at,
            tone: $this->transactionTone($transaksi->status)
        );
    }

    private function readTimestamp(User $user): int
    {
        return (int) Cache::get($this->readCacheKey($user), 0);
    }

    private function readCacheKey(User $user): string
    {
        return "header_notifications.read_at.{$user->id}";
    }

    private function adminTransactionTitle(Transaksi $transaksi): string
    {
        $status = $transaksi->status;
        if ($status === 'dikirim' && $this->isPickup($transaksi)) {
            return 'Pesanan siap diambil';
        }
        
        return match ($status) {
            'belum_bayar' => 'Pesanan belum dibayar',
            'pending' => 'Pesanan baru perlu diproses',
            'diproses' => 'Pesanan sedang diproses',
            'dikirim' => 'Pesanan sedang dikirim',
            'selesai' => 'Pesanan selesai',
            'dibatalkan' => 'Pesanan dibatalkan',
            default => 'Update transaksi',
        };
    }

    private function customerTransactionTitle(Transaksi $transaksi): string
    {
        return match ($transaksi->status) {
            'belum_bayar' => 'Pesanan belum dibayar',
            'pending' => 'Pesanan menunggu konfirmasi',
            'diproses' => 'Pesanan sedang diproses',
            'dikirim' => $this->isPickup($transaksi) ? 'Pesanan siap diambil' : 'Pesanan sedang dikirim',
            'selesai' => 'Pesanan sudah selesai',
            'dibatalkan' => 'Pesanan dibatalkan',
            default => 'Update pesanan',
        };
    }

    private function isPickup(Transaksi $transaksi): bool
    {
        return $transaksi->pengiriman?->metode_kirim === 'ambil_sendiri';
    }

    private function transactionIcon(string $status): string
    {
        return match ($status) {
            'belum_bayar' => 'fa-credit-card',
            'pending' => 'fa-receipt',
            'diproses' => 'fa-clock',
            'dikirim' => 'fa-truck',
            'selesai' => 'fa-check-circle',
            'dibatalkan' => 'fa-ban',
            default => 'fa-bell',
        };
    }

    private function transactionTone(string $status): string
    {
        return match ($status) {
            'belum_bayar' => 'warning',
            'pending' => 'warning',
            'diproses', 'dikirim' => 'info',
            'selesai' => 'success',
            'dibatalkan' => 'danger',
            default => 'primary',
        };
    }

    private function statusLabel(?string $status): string
    {
        return match ($status) {
            'proses' => 'proses',
            'selesai' => 'selesai',
            'gagal' => 'gagal',
            default => 'update',
        };
    }

    private function item(string $icon, string $title, string $description, string $url, ?CarbonInterface $time, string $tone): array
    {
        $time ??= now();

        return [
            'icon' => $icon,
            'title' => $title,
            'description' => $description,
            'url' => $url,
            'time' => $time->diffForHumans(),
            'tone' => $tone,
            '_sort' => $time->getTimestamp(),
        ];
    }

    private function routeUrl(string $name, mixed $parameters = [], string $fallback = '#'): string
    {
        return Route::has($name) ? route($name, $parameters) : $fallback;
    }

    private function tableReady(string $table): bool
    {
        try {
            return Schema::hasTable($table);
        } catch (Throwable) {
            return false;
        }
    }

    private function money(mixed $value): string
    {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    }

    private function number(mixed $value): string
    {
        $formatted = number_format((float) $value, 2, ',', '.');
        return rtrim(rtrim($formatted, '0'), ',');
    }
}

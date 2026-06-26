@extends('layouts.admin')
@section('title', 'Transaksi')
@section('page-title', 'Riwayat Transaksi')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Penjualan</div>
<li><a href="{{ route('penjualan.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('penjualan.pos') }}"><i class="fas fa-cash-register"></i> POS / Kasir</a></li>
<li><a href="{{ route('penjualan.transaksi') }}" class="active"><i class="fas fa-history"></i> Riwayat Transaksi</a></li>
<li><a href="{{ route('penjualan.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<style>
    .contact-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
    }
    .contact-actions a {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        text-decoration: none;
        font-weight: 700;
        white-space: nowrap;
    }
    .contact-actions a:hover {
        text-decoration: underline;
    }
    .contact-actions .wa-link { color: #25D366; }
    .contact-actions .email-link { color: #1a73e8; }
</style>

<div class="stats-grid mb-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
    <div class="stat-card" style="background: #fff; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid var(--primary); display: flex;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-light); margin-bottom: 0.5rem; font-weight: 600;">Total Transaksi</h4>
            <p style="font-size: 1.75rem; font-weight: 700; color: var(--text-dark); margin: 0;">{{ $totalTransaksi }}</p>
        </div>
    </div>
    <div class="stat-card" style="background: #fff; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid var(--primary); display: flex;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-light); margin-bottom: 0.5rem; font-weight: 600;">Transaksi Selesai</h4>
            <p style="font-size: 1.75rem; font-weight: 700; color: var(--text-dark); margin: 0;">{{ $transaksiSelesai }}</p>
        </div>
    </div>
    <div class="stat-card" style="background: #fff; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid var(--primary); display: flex;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-light); margin-bottom: 0.5rem; font-weight: 600;">Pesanan Pending</h4>
            <p style="font-size: 1.75rem; font-weight: 700; color: var(--text-dark); margin: 0;">{{ $transaksiPending }}</p>
        </div>
    </div>
</div>

<div class="card mb-4" style="border-radius: 16px; border: 1px solid var(--border); background: #fff; border-left: 5px solid var(--primary);">
    <div class="card-body" style="padding: 1.5rem;">
        <form method="GET" action="{{ route('penjualan.transaksi') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
            <div class="form-group mb-0">
                <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem;">Periode</label>
                <select name="periode" class="form-control" onchange="this.form.submit()" style="min-width: 150px; padding: 0.5rem 0.75rem;">
                    <option value="all" {{ $periode == 'all' ? 'selected' : '' }}>Semua Periode</option>
                    <option value="harian" {{ $periode == 'harian' ? 'selected' : '' }}>Harian</option>
                    <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="tahunan" {{ $periode == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>

            @if($periode == 'harian')
                <div class="form-group mb-0">
                    <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem;">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" style="min-width: 180px; padding: 0.5rem 0.75rem;">
                </div>
            @elseif($periode == 'bulanan')
                <div class="form-group mb-0">
                    <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem;">Bulan</label>
                    <select name="bulan" class="form-control" style="min-width: 150px; padding: 0.5rem 0.75rem;">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(2024, $i, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
            @endif

            @if($periode != 'harian' && $periode != 'all')
                <div class="form-group mb-0">
                    <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem;">Tahun</label>
                    <select name="tahun" class="form-control" style="min-width: 120px; padding: 0.5rem 0.75rem;">
                        @for($i = 2024; $i <= date('Y'); $i++)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            @endif

            <div class="form-group mb-0" style="margin-left: auto;">
                <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.5rem; visibility: hidden;">Pencarian</label>
                <div class="d-flex gap-1" style="flex: 1; flex-wrap: wrap">
                    <input type="text" name="search" class="form-control" placeholder="Cari kode transaksi..." value="{{ request('search') }}" style="width:250px; padding: 0.5rem 0.75rem;">
                    <select name="status" class="form-control" style="width:150px; padding: 0.5rem 0.75rem;" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach(['belum_bayar','pending','diproses','dikirim','selesai','dibatalkan'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary" type="submit" style="background: var(--gradient-gold); border: none; padding: 0.5rem 1rem; font-size: 0.875rem; height: 42px;">Cari</button>
                    <a href="{{ route('penjualan.transaksi') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem; height: 42px; display: flex; align-items: center;">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card" style="border-left: 5px solid var(--primary);"><div class="table-responsive"><table>
    <thead><tr><th>Kode</th><th>Customer</th><th>Kontak</th><th>Tipe</th><th>Total</th><th>Bayar</th><th style="text-align:center">Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
    <tbody>
        @foreach($transaksi as $t)
        @php
            $customerName = $t->pengiriman->nama_penerima ?? $t->nama_pelanggan ?? $t->user->name ?? 'Customer';
            $phone = $t->pengiriman->phone_penerima ?? $t->phone_pelanggan;
            $email = trim((string) ($t->email_pelanggan ?? ''));
            $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;

            $isPickup = $t->pengiriman && in_array($t->pengiriman->metode_kirim, ['ambil_sendiri']);
            $displayStatus = $t->status;
            if ($isPickup && $t->status === 'dikirim') {
                $displayStatus = 'siap diambil';
            }

            $productList = '';
            foreach ($t->detail as $item) {
                $productList .= "- " . ($item->produk->nama_produk ?? 'Produk') . " x " . $item->jumlah . "\n";
            }

            $message = "Yth. " . $customerName . ",\n\n"
                . "Berikut update pesanan Anda di Mondial Bakery.\n\n"
                . "Kode Pesanan: " . $t->kode_transaksi . "\n"
                . "Status: " . ucfirst(str_replace('_', ' ', $displayStatus)) . "\n"
                . "Detail Pesanan:\n" . trim($productList) . "\n"
                . "Total: Rp " . number_format($t->total, 0, ',', '.') . "\n\n"
                . "Terima kasih telah berbelanja di Mondial Bakery.";

            $whatsappUrl = null;
            if ($phone) {
                $whatsappPhone = preg_replace('/[^0-9]/', '', $phone);
                if (substr($whatsappPhone, 0, 1) === '0') {
                    $whatsappPhone = '62' . substr($whatsappPhone, 1);
                }
                $whatsappUrl = "https://wa.me/" . $whatsappPhone . "?text=" . urlencode($message);
            }

            $emailUrl = null;
            if ($validEmail) {
                $emailUrl = "https://mail.google.com/mail/?" . http_build_query([
                    'authuser' => config('mail.from.address'),
                    'view' => 'cm',
                    'fs' => '1',
                    'to' => $validEmail,
                    'su' => "Update Pesanan " . $t->kode_transaksi . " - Mondial Bakery",
                    'body' => $message,
                ], '', '&', PHP_QUERY_RFC3986);
            }
        @endphp
        <tr>
            <td><strong>{{ $t->kode_transaksi }}</strong></td>
            <td>{{ $t->user->name ?? $t->nama_pelanggan ?? 'Walk-in' }}</td>
            <td>
                @if($whatsappUrl || $emailUrl)
                    <div class="contact-actions">
                        @if($whatsappUrl)
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="wa-link" title="Kirim WhatsApp ke {{ $phone }}">
                                <i class="fab fa-whatsapp"></i> WA
                            </a>
                        @endif
                        @if($emailUrl)
                            <a href="{{ $emailUrl }}" target="_blank" rel="noopener noreferrer" class="email-link" title="Kirim email ke {{ $validEmail }}">
                                <i class="fas fa-envelope"></i> Email
                            </a>
                        @endif
                    </div>
                @else
                    -
                @endif
            </td>
            <td><span class="badge badge-{{ $t->tipe=='pos'?'info':'primary' }}">{{ strtoupper($t->tipe) }}</span></td>
            <td style="font-weight:600">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
            <td>{{ $t->pembayaran->metode_label ?? '-' }}</td>
            <td style="text-align:center"><span class="badge badge-{{ match($t->status) { 'selesai'=>'success','pending'=>'warning','belum_bayar'=>'danger','dibatalkan'=>'danger',default=>'info' } }}">{{ ucfirst(str_replace('_',' ', $displayStatus)) }}</span></td>
            <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
            <td><a href="{{ route('penjualan.transaksi.detail', $t) }}" class="btn btn-sm btn-secondary">Lihat</a></td>
        </tr>
        @endforeach
    </tbody>
</table></div></div>
@endsection

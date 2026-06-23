@extends('layouts.app')
@section('title', 'Pesanan Saya - Mondial Bakery')

@section('styles')
<style>
    .page-header {
        position: relative;
        padding: 5rem 0;
        background: #ffffff;
        text-align: center;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(30deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(150deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(60deg, rgba(199, 131, 77, 0.01) 25%, transparent 25.5%, transparent 75%, rgba(199, 131, 77, 0.01) 75%, rgba(199, 131, 77, 0.01));
        background-size: 40px 70px;
        opacity: 0.8;
        z-index: 0;
    }

    .page-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        background: linear-gradient(135deg, var(--text-dark) 0%, var(--text-dark) 30%, var(--primary) 50%, var(--accent) 70%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .page-header p {
        font-size: 1.1rem;
        color: var(--text-light);
        max-width: 600px;
        margin: 0 auto;
    }

    .pesanan-section {
        position: relative;
        padding: 2rem 0 5rem;
        background: #fff;
        overflow: hidden;
    }

    .pesanan-section::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: -10px;
        right: -10px;
        background-image: 
            linear-gradient(30deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(150deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(60deg, rgba(199, 131, 77, 0.01) 25%, transparent 25.5%, transparent 75%, rgba(199, 131, 77, 0.01) 75%, rgba(199, 131, 77, 0.01));
        background-size: 40px 70px;
        opacity: 0.5;
        z-index: 0;
        pointer-events: none;
    }

    .pesanan-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
        position: relative;
        z-index: 1;
    }

    .pesanan-group-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: var(--text-dark);
        margin: 2rem 0 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--primary-light);
    }

    .status-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-belum_bayar {
        background: #dc2626;
        color: white;
        text-align: center;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-diproses {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-dikirim {
        background: #cce5ff;
        color: #004085;
    }

    .status-selesai {
        background: #d4edda;
        color: #155724;
    }

    .status-dibatalkan {
        background: #f8d7da;
        color: #721c24;
    }

    .detail-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        background: var(--primary);
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(122, 75, 34, 0.2);
    }

    .detail-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(122, 75, 34, 0.3);
        background: var(--primary-dark);
    }
    
    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 0 1.5rem;
        }
        
        .page-header h1 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            font-size: 0.75rem;
            padding: 0 1rem;
        }
        
        .pesanan-section {
            padding: 1.5rem 0 3rem;
        }
        
        .pesanan-container {
            padding: 0 1.25rem;
        }
        
        .pesanan-group-title {
            font-size: 1.2rem;
            margin: 1.5rem 0 1rem;
            padding-bottom: 0.5rem;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 0.3rem 0.7rem;
        }
        
        .detail-btn {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
        
        .card-body h4 {
            font-size: 0.9rem;
        }
        
        .card-body small {
            font-size: 0.75rem;
        }
        
        .card-body p {
            font-size: 0.95rem;
        }
    }
</style>
@endsection

@section('content')
<header class="page-header">
    <div class="container">
        <h1 class="reveal fade-bottom">Pesanan Saya</h1>
        <p class="reveal fade-bottom delay-100">Pantau status pesanan roti dan kue Anda secara real-time</p>
    </div>
</header>

<section class="pesanan-section">
    <div class="pesanan-container">
        @if($pesanan->count() > 0)
            @php
                $pesananBelumBayar = $pesanan->filter(function($trx) {
                    return $trx->status === 'belum_bayar';
                });
                $pesananBerjalan = $pesanan->filter(function($trx) {
                    return in_array($trx->status, ['pending', 'diproses', 'dikirim']);
                });
                $pesananSelesai = $pesanan->filter(function($trx) {
                    return in_array($trx->status, ['selesai', 'dibatalkan']);
                });
            @endphp

            @if($pesananBelumBayar->count() > 0)
                <h2 class="pesanan-group-title reveal fade-bottom">Pesanan Belum Bayar</h2>
                @foreach($pesananBelumBayar as $trx)
                    <div class="card reveal fade-bottom delay-{{ ($loop->index % 3 + 1) * 100 }}" style="margin-bottom:1rem">
                        <div class="card-body" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
                            <div>
                                <h4 style="font-size:1rem">{{ $trx->kode_transaksi }}</h4>
                                @if($trx->detail->count())
                                    <p style="color:var(--text-dark);margin:0.35rem 0 0;font-size:0.9rem">
                                        {{ $trx->detail->map(fn($d) => ($d->produk->nama_produk ?? 'Produk dihapus') . ' x' . $d->jumlah)->take(2)->implode(', ') }}@if($trx->detail->count() > 2) +{{ $trx->detail->count() - 2 }} produk @endif
                                    </p>
                                @endif
                                <small style="color:var(--text-light)">{{ $trx->created_at->format('d M Y H:i') }} • {{ ucfirst($trx->tipe) }}</small>
                            </div>
                            <div style="text-align:center">
                                <span class="status-badge status-belum_bayar">{{ ucfirst(str_replace('_', ' ', $trx->status)) }}</span>
                                @if($trx->pembayaran)
                                    <br><small style="color:var(--text-light)">{{ $trx->pembayaran->metode_label }}</small>
                                @endif
                            </div>
                            <div style="text-align:right">
                                <p style="font-weight:700;color:var(--primary);font-size:1.1rem">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                                <form action="{{ route('customer.pesanan.detail', $trx) }}" method="GET" style="margin:0">
                                    <button type="submit" class="detail-btn">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @if($pesananBerjalan->count() > 0)
                <h2 class="pesanan-group-title reveal fade-bottom">Pesanan Berjalan</h2>
                @foreach($pesananBerjalan as $trx)
                    <div class="card reveal fade-bottom delay-{{ ($loop->index % 3 + 1) * 100 }}" style="margin-bottom:1rem">
                        <div class="card-body" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
                            <div>
                                <h4 style="font-size:1rem">{{ $trx->kode_transaksi }}</h4>
                                @if($trx->detail->count())
                                    <p style="color:var(--text-dark);margin:0.35rem 0 0;font-size:0.9rem">
                                        {{ $trx->detail->map(fn($d) => ($d->produk->nama_produk ?? 'Produk dihapus') . ' x' . $d->jumlah)->take(2)->implode(', ') }}@if($trx->detail->count() > 2) +{{ $trx->detail->count() - 2 }} produk @endif
                                    </p>
                                @endif
                                <small style="color:var(--text-light)">{{ $trx->created_at->format('d M Y H:i') }} • {{ ucfirst($trx->tipe) }}</small>
                            </div>
                            <div style="text-align:center">
                                @php
                                    $isPickup = $trx->pengiriman && in_array($trx->pengiriman->metode_kirim, ['ambil_sendiri']);
                                    $displayStatus = $trx->status;
                                    if ($isPickup && $trx->status === 'dikirim') {
                                        $displayStatus = 'siap diambil';
                                    }
                                    $statusClass = $isPickup && $displayStatus === 'siap diambil' ? 'dikirim' : $trx->status;
                                @endphp
                                <span class="status-badge status-{{ $statusClass }}">{{ ucfirst($displayStatus) }}</span>
                                @if($trx->pembayaran)
                                    <br><small style="color:var(--text-light)">{{ $trx->pembayaran->metode_label }}</small>
                                @endif
                            </div>
                            <div style="text-align:right">
                                <p style="font-weight:700;color:var(--primary);font-size:1.1rem">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                                <form action="{{ route('customer.pesanan.detail', $trx) }}" method="GET" style="margin:0">
                                    <button type="submit" class="detail-btn">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @if($pesananSelesai->count() > 0)
                <h2 class="pesanan-group-title reveal fade-bottom">Pesanan Selesai</h2>
                @foreach($pesananSelesai as $trx)
                    <div class="card reveal fade-bottom delay-{{ ($loop->index % 3 + 1) * 100 }}" style="margin-bottom:1rem">
                        <div class="card-body" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
                            <div>
                                <h4 style="font-size:1rem">{{ $trx->kode_transaksi }}</h4>
                                @if($trx->detail->count())
                                    <p style="color:var(--text-dark);margin:0.35rem 0 0;font-size:0.9rem">
                                        {{ $trx->detail->map(fn($d) => ($d->produk->nama_produk ?? 'Produk dihapus') . ' x' . $d->jumlah)->take(2)->implode(', ') }}@if($trx->detail->count() > 2) +{{ $trx->detail->count() - 2 }} produk @endif
                                    </p>
                                @endif
                                <small style="color:var(--text-light)">{{ $trx->created_at->format('d M Y H:i') }} • {{ ucfirst($trx->tipe) }}</small>
                            </div>
                            <div style="text-align:center">
                                @php
                                    $isPickup = $trx->pengiriman && in_array($trx->pengiriman->metode_kirim, ['ambil_sendiri']);
                                    $displayStatus = $trx->status;
                                    if ($isPickup && $trx->status === 'dikirim') {
                                        $displayStatus = 'siap diambil';
                                    }
                                    $statusClass = $isPickup && $displayStatus === 'siap diambil' ? 'dikirim' : $trx->status;
                                @endphp
                                <span class="status-badge status-{{ $statusClass }}">{{ ucfirst($displayStatus) }}</span>
                                @if($trx->pembayaran)
                                    <br><small style="color:var(--text-light)">{{ $trx->pembayaran->metode_label }}</small>
                                @endif
                            </div>
                            <div style="text-align:right">
                                <p style="font-weight:700;color:var(--primary);font-size:1.1rem">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                                <form action="{{ route('customer.pesanan.detail', $trx) }}" method="GET" style="margin:0">
                                    <button type="submit" class="detail-btn">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @if($pesananBelumBayar->count() == 0 && $pesananBerjalan->count() == 0 && $pesananSelesai->count() == 0)
                <div class="card reveal fade-bottom" style="text-align:center;padding:4rem">
                    <i class="fas fa-box-open" style="font-size:4rem;color:var(--border);margin-bottom:1rem"></i>
                    <h3 style="color:var(--text-light)">Belum Ada Pesanan</h3>
                    <form action="{{ route('katalog') }}" method="GET" style="margin-top:1rem">
                        <button type="submit" class="detail-btn">
                            <i class="fas fa-shopping-cart"></i> Mulai Belanja
                        </button>
                    </form>
                </div>
            @endif

        @else
            <div class="card reveal fade-bottom" style="text-align:center;padding:4rem">
                <i class="fas fa-box-open" style="font-size:4rem;color:var(--border);margin-bottom:1rem"></i>
                <h3 style="color:var(--text-light)">Belum Ada Pesanan</h3>
                <form action="{{ route('katalog') }}" method="GET" style="margin-top:1rem">
                    <button type="submit" class="detail-btn">
                        <i class="fas fa-shopping-cart"></i> Mulai Belanja
                    </button>
                </form>
            </div>
        @endif
    </div>
</section>
@endsection

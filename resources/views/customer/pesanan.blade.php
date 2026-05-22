@extends('layouts.app')
@section('title', 'Pesanan Saya - Mondial Bakery')

@section('styles')
<style>
    .pesanan-header {
        position: relative;
        background: #ffffff;
        padding: 4rem 0;
        width: 100%;
        border-bottom: none;
        text-align: center;
        overflow: hidden;
        margin-bottom: 3rem;
    }

    .pesanan-header::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: -10px;
        right: -10px;
        background-image: 
            /* Motif Batik Geometris Modern (Truntum Style) */
            linear-gradient(30deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(150deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(30deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(150deg, rgba(122, 75, 34, 0.015) 12%, transparent 12.5%, transparent 87%, rgba(122, 75, 34, 0.015) 87.5%, rgba(122, 75, 34, 0.015)),
            linear-gradient(60deg, rgba(199, 131, 77, 0.01) 25%, transparent 25.5%, transparent 75%, rgba(199, 131, 77, 0.01) 75%, rgba(199, 131, 77, 0.01)),
            linear-gradient(60deg, rgba(199, 131, 77, 0.01) 25%, transparent 25.5%, transparent 75%, rgba(199, 131, 77, 0.01) 75%, rgba(199, 131, 77, 0.01));
        background-size: 40px 70px;
        background-position: center;
        background-repeat: repeat;
        opacity: 0.8;
        pointer-events: none;
        z-index: 0;
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
</style>
@endsection

@section('content')
<div class="pesanan-header">
    <h1 style="position: relative; z-index: 1; font-family: 'Playfair Display', serif; font-size: 2.5rem; background: linear-gradient(135deg, var(--text-dark) 0%, var(--text-dark) 30%, var(--primary) 50%, var(--accent) 70%, var(--accent) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; margin: 0;">
        Pesanan Saya
    </h1>
    <p class="text-muted" style="position: relative; z-index: 1; margin-top: 0.5rem;">Pantau status pesanan roti dan kue Anda secara real-time</p>
</div>

<section class="pesanan-section">
    <div class="pesanan-container">
        @if($pesanan->count() > 0)
            @foreach($pesanan as $trx)
                <div class="card" style="margin-bottom:1rem">
                    <div class="card-body" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
                        <div>
                            <h4 style="font-size:1rem">{{ $trx->kode_transaksi }}</h4>
                            <small style="color:var(--text-light)">{{ $trx->created_at->format('d M Y H:i') }} • {{ ucfirst($trx->tipe) }}</small>
                        </div>
                        <div style="text-align:center">
                            <span class="badge badge-{{ match($trx->status) { 'pending' => 'warning', 'diproses' => 'info', 'dikirim' => 'primary', 'selesai' => 'success', 'dibatalkan' => 'danger', default => 'primary' } }}">{{ ucfirst($trx->status) }}</span>
                            @if($trx->pembayaran)
                                <br><small style="color:var(--text-light)">{{ $trx->pembayaran->metode_label }}</small>
                            @endif
                        </div>
                        <div style="text-align:right">
                            <p style="font-weight:700;color:var(--primary);font-size:1.1rem">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                            <a href="{{ route('customer.pesanan.detail', $trx) }}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i> Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
            {{ $pesanan->links() }}
        @else
            <div class="card" style="text-align:center;padding:4rem">
                <i class="fas fa-box-open" style="font-size:4rem;color:var(--border);margin-bottom:1rem"></i>
                <h3 style="color:var(--text-light)">Belum Ada Pesanan</h3>
                <a href="{{ route('katalog') }}" class="btn btn-primary" style="margin-top:1rem"><i class="fas fa-shopping-cart"></i> Mulai Belanja</a>
            </div>
        @endif
    </div>
</section>
@endsection

@extends('layouts.admin')
@section('title', 'Resep')
@section('page-title', 'Manajemen Resep')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Produksi</div>
<li><a href="{{ route('produksi.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('produksi.produk') }}"><i class="fas fa-birthday-cake"></i> Produk</a></li>
<li><a href="{{ route('produksi.kategori') }}"><i class="fas fa-tags"></i> Kategori</a></li>
<li><a href="{{ route('produksi.resep') }}" class="active"><i class="fas fa-book-open"></i> Resep</a></li>
<li><a href="{{ route('produksi.input') }}"><i class="fas fa-plus-circle"></i> Input Produksi</a></li>
<li><a href="{{ route('produksi.riwayat') }}"><i class="fas fa-history"></i> Detail Riwayat Produksi</a></li>
<li><a href="{{ route('produksi.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('styles')
<style>
    /* ─── Resep Card Styles ─── */
    .resep-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid rgba(122, 75, 34, 0.08);
        border-left: 5px solid var(--primary);
        overflow: hidden;
        margin-bottom: 1.75rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .resep-card:hover {
        box-shadow: 0 8px 32px rgba(122, 75, 34, 0.1);
        transform: translateY(-2px);
    }

    .resep-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 2rem;
        background: linear-gradient(135deg, #fffaf5 0%, #fff4e7 100%);
        border-bottom: 1px solid rgba(122, 75, 34, 0.08);
        gap: 1rem;
        flex-wrap: wrap;
    }

    .resep-card-header .resep-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.3rem;
        font-weight: 700;
        font-family: 'Raleway', sans-serif;
        color: var(--text-dark);
        margin: 0;
    }

    .resep-card-header .resep-title .resep-icon {
        color: var(--accent);
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .resep-meta {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

    .resep-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 1rem;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .resep-badge-produk {
        background: #fff4e7;
        color: var(--primary);
        border: 1px solid rgba(122, 75, 34, 0.15);
    }

    .resep-badge-hasil {
        background: #fef3e2;
        color: var(--primary-dark);
        border: 1px solid rgba(122, 75, 34, 0.12);
    }

    .resep-badge-waktu {
        background: #f9f0e3;
        color: var(--primary);
        border: 1px solid rgba(122, 75, 34, 0.1);
    }

    .btn-delete-resep {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        border: 1px solid #fee2e2;
        background: #fff;
        color: #ef4444;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.25s ease;
        font-size: 0.9rem;
        flex-shrink: 0;
        box-shadow: none;
    }

    .btn-delete-resep:hover {
        background: #fef2f2;
        border-color: #fca5a5;
        color: #dc2626;
        transform: scale(1.05);
    }

    .resep-card-body {
        padding: 1.75rem 2rem;
    }

    .resep-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .resep-section-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.85rem;
    }

    .resep-section-title i {
        color: var(--accent);
        font-size: 0.95rem;
    }

    /* ─── Ingredients Table ─── */
    .resep-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(122, 75, 34, 0.08);
    }

    .resep-table thead th {
        background: #fff9f2;
        padding: 0.75rem 1rem;
        text-align: left;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--primary);
        font-weight: 700;
        border-bottom: 1px solid rgba(122, 75, 34, 0.1);
    }

    .resep-table tbody td {
        padding: 0.7rem 1rem;
        font-size: 0.95rem;
        color: var(--text-medium);
        border-bottom: 1px solid rgba(122, 75, 34, 0.05);
    }

    .resep-table tbody tr:last-child td {
        border-bottom: none;
    }

    .resep-table tbody tr:hover {
        background: #fffcf8;
    }

    /* ─── Instruction Text ─── */
    .resep-instruksi {
        font-size: 0.95rem;
        color: var(--text-medium);
        line-height: 1.85;
        padding: 1rem 1.25rem;
        background: #fefcf9;
        border-radius: 12px;
        border: 1px solid rgba(122, 75, 34, 0.06);
    }

    .resep-waktu {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
        padding: 0.6rem 1.1rem;
        background: #fff4e7;
        border-radius: 10px;
        font-size: 0.9rem;
        color: var(--primary);
        font-weight: 600;
    }

    .resep-waktu i {
        font-size: 0.85rem;
    }

    /* ─── Empty State ─── */
    .resep-empty {
        text-align: center;
        padding: 4rem 2rem;
        background: #fff;
        border-radius: 20px;
        border: 2px dashed rgba(122, 75, 34, 0.12);
    }

    .resep-empty i {
        font-size: 3rem;
        color: var(--accent);
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .resep-empty h3 {
        font-family: 'Raleway', sans-serif;
        font-size: 1.3rem;
        color: var(--text-medium);
        margin-bottom: 0.5rem;
    }

    .resep-empty p {
        color: var(--text-light);
        font-size: 0.95rem;
    }

    /* ─── Pagination ─── */
    .resep-pagination {
        margin-top: 0.5rem;
    }

    @media (max-width: 768px) {
        .resep-grid {
            grid-template-columns: 1fr;
        }
        .resep-card-header {
            padding: 1.25rem 1.5rem;
        }
        .resep-card-body {
            padding: 1.25rem 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="action-header" style="border-left: 5px solid var(--primary);">
    <div style="flex: 1"></div>
    <a href="{{ route('produksi.resep.create') }}" class="btn btn-primary">Tambah Resep</a>
</div>

@forelse($resep as $r)
<div class="resep-card">
    {{-- Header --}}
    <div class="resep-card-header">
        <h3 class="resep-title">
            Resep {{ $r->nama_resep }}
        </h3>
        <div class="resep-meta">
            <span class="resep-badge resep-badge-produk">
                {{ $r->produk->nama_produk ?? $r->nama_produk ?? '-' }}
            </span>
            @if($r->kategori)
            <span class="resep-badge" style="background: #e8f5e9; color: #2e7d32; border: 1px solid rgba(46, 125, 50, 0.2);">
                {{ $r->kategori->nama_kategori }}
            </span>
            @endif
            <span class="resep-badge resep-badge-hasil">
                Hasil: {{ $r->hasil_produksi }} pcs
            </span>
            @if($r->waktu_produksi)
            <span class="resep-badge resep-badge-waktu">
                {{ $r->waktu_produksi }} menit
            </span>
            @endif
            <form method="POST" action="{{ route('produksi.resep.delete', $r) }}" onsubmit="return confirm('Yakin ingin menghapus resep ini?')" style="margin:0;display:inline-flex">
                @csrf @method('DELETE')
                <button type="submit" class="btn-delete-resep" title="Hapus Resep">Hapus</button>
            </form>
        </div>
    </div>

    {{-- Body --}}
    <div class="resep-card-body">
        <div class="resep-grid">
            {{-- Bahan --}}
            <div>
                <div class="resep-section-title">
                    Bahan yang Dibutuhkan
                </div>
                @if($r->detail->count() > 0)
                <table class="resep-table">
                    <thead>
                        <tr>
                            <th>Bahan</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($r->detail as $d)
                        <tr>
                            <td>{{ $d->bahanBaku->nama_bahan ?? '-' }}</td>
                            <td>{{ $d->jumlah }}</td>
                            <td>{{ $d->satuan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p style="color:var(--text-light); font-style:italic; font-size:0.9rem;">Belum ada bahan yang ditambahkan.</p>
                @endif
            </div>

            {{-- Instruksi --}}
            <div>
                @if($r->instruksi)
                <div class="resep-section-title">
                    Instruksi
                </div>
                <div class="resep-instruksi">{{ $r->instruksi }}</div>
                @endif

                @if($r->waktu_produksi)
                <div class="resep-waktu">
                    Waktu Produksi: {{ $r->waktu_produksi }} menit
                </div>
                @endif

                @if(!$r->instruksi && !$r->waktu_produksi)
                <p style="color:var(--text-light); font-style:italic; font-size:0.9rem;">Belum ada instruksi.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@empty
<div class="resep-empty">
    <h3>Belum Ada Resep</h3>
    <p>Mulai tambahkan resep baru untuk produk Anda.</p>
</div>
@endforelse

<div class="resep-pagination">
    {{ $resep->links() }}
</div>
@endsection

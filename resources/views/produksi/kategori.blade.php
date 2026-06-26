@extends('layouts.admin')
@section('title', 'Kategori')
@section('page-title', 'Kategori Produk')
@section('styles')
<style>
    .kategori-grid {
        display: grid;
        grid-template-columns: minmax(320px, 0.9fr) minmax(560px, 1.15fr);
        gap: 1.75rem;
        align-items: start;
    }

    .kategori-table-wrap {
        width: 100%;
        overflow-x: auto;
        border-radius: 0 0 var(--radius-lg) var(--radius-lg);
    }

    .kategori-table-wrap > table {
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
    }

    .kategori-table-wrap th,
    .kategori-table-wrap td {
        white-space: nowrap;
    }

    .kategori-table-wrap th:last-child,
    .kategori-table-wrap td:last-child {
        width: 1%;
        text-align: center;
    }

    .kategori-table-wrap form {
        display: flex;
        justify-content: center;
        margin: 0;
    }

    @media (max-width: 1180px) {
        .kategori-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
@section('sidebar-menu')
<div class="sidebar-divider">Menu Produksi</div>
<li><a href="{{ route('produksi.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('produksi.produk') }}"><i class="fas fa-birthday-cake"></i> Produk</a></li>
<li><a href="{{ route('produksi.kategori') }}" class="active"><i class="fas fa-tags"></i> Kategori</a></li>
<li><a href="{{ route('produksi.resep') }}"><i class="fas fa-book-open"></i> Resep</a></li>
<li><a href="{{ route('produksi.input') }}"><i class="fas fa-plus-circle"></i> Input Produksi</a></li>
<li><a href="{{ route('produksi.riwayat') }}"><i class="fas fa-history"></i> Detail Riwayat Produksi</a></li>
<li><a href="{{ route('produksi.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div style="padding-top: 1rem;">
<div class="stat-grid mb-4" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
    @foreach($kategori as $kat)
    <a href="{{ route('produksi.produk', ['kategori_id' => $kat->id]) }}" class="stat-card" style="background: #fff; padding: 1.25rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid var(--primary); display: flex; text-decoration: none; color: inherit; transition: transform 0.2s;">
        <div class="stat-info" style="flex: 1;">
            <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-light); margin-bottom: 0.5rem; font-weight: 600;">{{ $kat->nama_kategori }}</h4>
            <p style="font-size: 1.5rem; font-weight: 700; color: var(--text-dark); margin: 0;">{{ $kat->produk_count }} <span style="font-size: 0.85rem; font-weight: normal; color: var(--text-light);">Produk</span></p>
        </div>
    </a>
    @endforeach
</div>

<div class="kategori-grid">
    <div class="card" style="border-left: 5px solid var(--primary);">
        <div class="card-header"><h3>Tambah Kategori</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('produksi.kategori.store') }}">@csrf
                <div class="form-group"><label class="form-label">Nama Kategori *</label><input type="text" name="nama_kategori" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control"></textarea></div>
                <button class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
    <div class="card" style="border-left: 5px solid var(--primary);">
        <div class="card-header"><h3>Daftar Kategori</h3></div>
        <div class="card-body table-responsive kategori-table-wrap" style="padding:0"><table>
            <thead><tr><th>Kategori</th><th>Slug</th><th>Jumlah Produk</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($kategori as $kat)
                <tr>
                    <td><strong>{{ $kat->nama_kategori }}</strong></td>
                    <td>{{ $kat->slug }}</td>
                    <td>{{ $kat->produk_count }}</td>
                    <td>
                        <form method="POST" action="{{ route('produksi.kategori.delete', $kat) }}" onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table></div>
    </div>
</div>
</div>
@endsection

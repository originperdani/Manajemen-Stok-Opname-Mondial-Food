@extends('layouts.admin')
@section('title', 'Detail Riwayat Produksi')
@section('page-title', 'Detail Riwayat Produksi')
@section('sidebar-menu')
<div class="sidebar-divider">Menu Produksi</div>
<li><a href="{{ route('produksi.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="{{ route('produksi.produk') }}"><i class="fas fa-birthday-cake"></i> Produk</a></li>
<li><a href="{{ route('produksi.kategori') }}"><i class="fas fa-tags"></i> Kategori</a></li>
<li><a href="{{ route('produksi.resep') }}"><i class="fas fa-book-open"></i> Resep</a></li>
<li><a href="{{ route('produksi.input') }}"><i class="fas fa-plus-circle"></i> Input Produksi</a></li>
<li><a href="{{ route('produksi.riwayat') }}" class="active"><i class="fas fa-history"></i> Detail Riwayat Produksi</a></li>
<li><a href="{{ route('produksi.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="card" style="border-left: 5px solid var(--primary);">
    <div class="card-header">
        <h3 style="font-size: 1.2rem;">Riwayat Produksi Lengkap</h3>
    </div>
    <div class="card-body" style="padding:0">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah Produksi</th>
                    <th>Penanggung Jawab</th>
                    <th>Status</th>
                    <th>Tanggal Produksi</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produksi as $p)
                <tr>
                    <td><strong>{{ $p->produk->nama_produk ?? '-' }}</strong></td>
                    <td style="font-weight: 700; color: var(--primary);">{{ $p->jumlah_produksi }}</td>
                    <td style="font-size: 0.95rem;">{{ $p->user->penanggung_jawab ?? '-' }}</td>
                    <td>
                        <span class="badge badge-{{ $p->status=='selesai'?'success':'warning' }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td style="font-size: 0.9rem; color: var(--text-light);">{{ $p->tanggal_produksi->format('d/m/Y') }}</td>
                    <td style="font-size: 0.9rem; color: var(--text-medium);">{{ $p->catatan ?? '-' }}</td>
                </tr>
                @endforeach
                @if($produksi->isEmpty())
                <tr>
                    <td colspan="6" class="text-center" style="padding: 3rem; color: var(--text-light);">Belum ada riwayat aktivitas produksi.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
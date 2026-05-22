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
<li><a href="{{ route('produksi.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
@endsection

@section('content')
<div class="action-header">
    <div style="flex: 1"></div>
    <a href="{{ route('produksi.resep.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Resep</a>
</div>

@foreach($resep as $r)
<div class="card mb-2">
    <div class="card-header">
        <h3>📖 {{ $r->nama_resep }}</h3>
        <div class="d-flex gap-1 align-center">
            <span class="badge badge-primary">{{ $r->produk->nama_produk ?? '-' }}</span>
            <span class="badge badge-info">Hasil: {{ $r->hasil_produksi }} pcs</span>
            <form method="POST" action="{{ route('produksi.resep.delete', $r) }}" onsubmit="return confirm('Hapus resep?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
        </div>
    </div>
    <div class="card-body">
        <div class="grid-2">
            <div>
                <h4 style="font-size:0.9rem;margin-bottom:0.5rem">📦 Bahan yang Dibutuhkan:</h4>
                <table><thead><tr><th>Bahan</th><th>Jumlah</th><th>Satuan</th></tr></thead>
                <tbody>
                    @foreach($r->detail as $d)
                    <tr><td>{{ $d->bahanBaku->nama_bahan ?? '-' }}</td><td>{{ $d->jumlah }}</td><td>{{ $d->satuan }}</td></tr>
                    @endforeach
                </tbody></table>
            </div>
            <div>
                @if($r->instruksi)<h4 style="font-size:0.9rem;margin-bottom:0.5rem">📝 Instruksi:</h4><p style="font-size:0.85rem;color:var(--text-medium);line-height:1.8">{{ $r->instruksi }}</p>@endif
                @if($r->waktu_produksi)<p style="margin-top:0.5rem;font-size:0.85rem"><strong>⏱ Waktu:</strong> {{ $r->waktu_produksi }} menit</p>@endif
            </div>
        </div>
    </div>
</div>
@endforeach
{{ $resep->links() }}
@endsection

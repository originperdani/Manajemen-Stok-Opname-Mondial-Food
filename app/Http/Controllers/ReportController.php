<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Produksi;
use App\Models\StokLog;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function ownerIndex()
    {
        abort_unless(auth()->user()->isOwner(), 403);

        return view('reports.index');
    }

    public function gudang(Request $request)
    {
        $this->ensureAccess('gudang');

        return $this->renderModuleReport('gudang', $request, auth()->user()->isOwner() ? 'owner' : 'module');
    }

    public function produksi(Request $request)
    {
        $this->ensureAccess('produksi');

        return $this->renderModuleReport('produksi', $request, auth()->user()->isOwner() ? 'owner' : 'module');
    }

    public function penjualan(Request $request)
    {
        $this->ensureAccess('penjualan');

        return $this->renderModuleReport('penjualan', $request, auth()->user()->isOwner() ? 'owner' : 'module');
    }

    public function exportGudang(Request $request, string $format)
    {
        $this->ensureAccess('gudang');

        return $this->exportModuleReport('gudang', $request, $format, auth()->user()->isOwner() ? 'owner' : 'module');
    }

    public function exportProduksi(Request $request, string $format)
    {
        $this->ensureAccess('produksi');

        return $this->exportModuleReport('produksi', $request, $format, auth()->user()->isOwner() ? 'owner' : 'module');
    }

    public function exportPenjualan(Request $request, string $format)
    {
        $this->ensureAccess('penjualan');

        return $this->exportModuleReport('penjualan', $request, $format, auth()->user()->isOwner() ? 'owner' : 'module');
    }

    private function renderModuleReport(string $module, Request $request, string $viewer)
    {
        $report = $this->buildReport($module, $request);

        return view('reports.module', [
            'report' => $report,
            'module' => $module,
            'viewer' => $viewer,
        ]);
    }

    private function exportModuleReport(string $module, Request $request, string $format, string $viewer)
    {
        $report = $this->buildReport($module, $request);
        $owner = User::where('role', 'owner')->first();
        $filename = $this->buildFilename($module, $report);

        if ($format === 'xls') {
            return response()
                ->view('reports.excel', [
                    'report' => $report,
                    'owner' => $owner,
                ])
                ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '.xls"');
        }

        if ($format === 'pdf') {
            return view('reports.pdf', [
                'report' => $report,
                'owner' => $owner,
                'ownerName' => $owner?->name ?? 'Owner Mondial Bakery',
                'generatedBy' => auth()->user()->name,
                'isPrint' => true,
                'module' => $module,
                'viewer' => $viewer
            ]);
        }

        abort(404);
    }

    private function buildReport(string $module, Request $request): array
    {
        return match ($module) {
            'gudang' => $this->buildGudangReport($request),
            'produksi' => $this->buildProduksiReport($request),
            'penjualan' => $this->buildPenjualanReport($request),
            default => abort(404),
        };
    }

    private function buildGudangReport(Request $request): array
    {
        $period = $this->resolvePeriod($request);
        $logs = StokLog::where('tipe', 'bahan_baku')
            ->with('user')
            ->whereBetween('created_at', [$period['start'], $period['end']])
            ->orderByDesc('created_at')
            ->get();

        $bahanMap = BahanBaku::whereIn('id', $logs->pluck('referensi_id')->unique())
            ->pluck('nama_bahan', 'id');

        $ringkasanBahan = BahanBaku::query()
            ->select(
                'nama_bahan',
                'satuan',
                'stok',
                'stok_minimum',
                DB::raw('stok * harga_per_satuan as nilai_stok')
            )
            ->orderBy('nama_bahan')
            ->get()
            ->map(fn ($item) => [
                $item->nama_bahan,
                $this->formatDecimal($item->stok) . ' ' . $item->satuan,
                $this->formatDecimal($item->stok_minimum) . ' ' . $item->satuan,
                'Rp ' . number_format((float) $item->nilai_stok, 0, ',', '.'),
            ])
            ->all();

        $detailLogs = $logs->map(fn ($log) => [
            Carbon::parse($log->created_at)->format('d/m/Y H:i'),
            $bahanMap[$log->referensi_id] ?? ('ID #' . $log->referensi_id),
            strtoupper($log->jenis),
            $this->formatDecimal($log->jumlah),
            $this->formatDecimal($log->stok_sebelum),
            $this->formatDecimal($log->stok_sesudah),
            $log->user->name ?? '-',
            $log->keterangan ?? '-',
        ])->all();

        return [
            'title' => 'Laporan Bahan Baku',
            'subtitle' => 'Laporan stok dan pergerakan bahan baku',
            'module' => 'gudang',
            'period_type' => $period['type'],
            'period_label' => $period['label'],
            'month' => $period['month'],
            'year' => $period['year'],
            'summary' => [
                ['label' => 'Total Aktivitas Stok', 'value' => number_format($logs->count(), 0, ',', '.')],
                ['label' => 'Total Bahan Masuk', 'value' => $this->formatDecimal($logs->where('jenis', 'masuk')->sum('jumlah'))],
                ['label' => 'Total Bahan Keluar', 'value' => $this->formatDecimal($logs->where('jenis', 'keluar')->sum('jumlah'))],
                ['label' => 'Bahan Stok Menipis', 'value' => number_format(BahanBaku::whereColumn('stok', '<=', 'stok_minimum')->count(), 0, ',', '.')],
                ['label' => 'Nilai Persediaan Saat Ini', 'value' => 'Rp ' . number_format((float) BahanBaku::sum(DB::raw('stok * harga_per_satuan')), 0, ',', '.')],
            ],
            'sections' => [
                [
                    'title' => 'Ringkasan Stok Bahan',
                    'headers' => ['Nama Bahan', 'Stok Saat Ini', 'Stok Minimum', 'Nilai Stok'],
                    'rows' => $ringkasanBahan,
                    'widths' => [28, 18, 18, 20],
                ],
                [
                    'title' => 'Riwayat Pergerakan Stok',
                    'headers' => ['Tanggal', 'Bahan', 'Jenis', 'Jumlah', 'Sebelum', 'Sesudah', 'Petugas', 'Keterangan'],
                    'rows' => $detailLogs,
                    'widths' => [16, 20, 8, 10, 10, 10, 16, 24],
                ],
            ],
        ];
    }

    private function buildProduksiReport(Request $request): array
    {
        $period = $this->resolvePeriod($request);
        $produksi = Produksi::with(['produk', 'resep', 'user'])
            ->whereBetween('tanggal_produksi', [$period['start']->toDateString(), $period['end']->toDateString()])
            ->orderByDesc('tanggal_produksi')
            ->get();

        $ringkasanProduk = Produksi::query()
            ->join('produk', 'produksi.produk_id', '=', 'produk.id')
            ->whereBetween('tanggal_produksi', [$period['start']->toDateString(), $period['end']->toDateString()])
            ->select('produk.nama_produk', DB::raw('SUM(produksi.jumlah_produksi) as total_jadi'))
            ->groupBy('produk.nama_produk')
            ->orderByDesc('total_jadi')
            ->get()
            ->map(fn ($item) => [
                $item->nama_produk,
                number_format((float) $item->total_jadi, 0, ',', '.'),
            ])
            ->all();

        $detailProduksi = $produksi->map(fn ($item) => [
            $item->tanggal_produksi->format('d/m/Y'),
            $item->produk->nama_produk ?? '-',
            $item->resep->nama_resep ?? '-',
            number_format((float) $item->jumlah_produksi, 0, ',', '.'),
            strtoupper($item->status),
            $item->user->name ?? '-',
            $item->catatan ?: '-',
        ])->all();

        return [
            'title' => 'Laporan Bahan Jadi / Produksi',
            'subtitle' => 'Laporan hasil produksi bahan jadi',
            'module' => 'produksi',
            'period_type' => $period['type'],
            'period_label' => $period['label'],
            'month' => $period['month'],
            'year' => $period['year'],
            'summary' => [
                ['label' => 'Total Aktivitas Produksi', 'value' => number_format($produksi->count(), 0, ',', '.')],
                ['label' => 'Total Bahan Jadi', 'value' => number_format((float) $produksi->sum('jumlah_produksi'), 0, ',', '.')],
                ['label' => 'Produk Diproduksi', 'value' => number_format($produksi->pluck('produk_id')->filter()->unique()->count(), 0, ',', '.')],
                ['label' => 'Produksi Selesai', 'value' => number_format($produksi->where('status', 'selesai')->count(), 0, ',', '.')],
            ],
            'sections' => [
                [
                    'title' => 'Ringkasan Hasil Produksi per Produk',
                    'headers' => ['Produk', 'Total Jadi'],
                    'rows' => $ringkasanProduk,
                    'widths' => [36, 16],
                ],
                [
                    'title' => 'Detail Produksi',
                    'headers' => ['Tanggal', 'Produk', 'Resep', 'Jumlah Jadi', 'Status', 'Petugas', 'Catatan'],
                    'rows' => $detailProduksi,
                    'widths' => [14, 20, 22, 12, 10, 16, 20],
                ],
            ],
        ];
    }

    private function buildPenjualanReport(Request $request): array
    {
        $period = $this->resolvePeriod($request);
        $transaksi = Transaksi::with(['user', 'kasir', 'pembayaran'])
            ->whereBetween('created_at', [$period['start'], $period['end']])
            ->orderByDesc('created_at')
            ->get();

        $transaksiSelesai = $transaksi->where('status', 'selesai');

        $produkTerjual = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id')
            ->whereBetween('transaksi.created_at', [$period['start'], $period['end']])
            ->where('transaksi.status', '!=', 'dibatalkan')
            ->select('produk.nama_produk', DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'))
            ->groupBy('produk.nama_produk')
            ->orderByDesc('total_terjual')
            ->get()
            ->map(fn ($item) => [
                $item->nama_produk,
                number_format((float) $item->total_terjual, 0, ',', '.'),
            ])
            ->all();

        $detailTransaksi = $transaksi->map(fn ($item) => [
            Carbon::parse($item->created_at)->format('d/m/Y H:i'),
            $item->kode_transaksi,
            strtoupper($item->tipe),
            $item->nama_pelanggan ?: ($item->user->name ?? '-'),
            $item->kasir->name ?? '-',
            strtoupper($item->status),
            'Rp ' . number_format((float) $item->total, 0, ',', '.'),
        ])->all();

        $avg = $transaksiSelesai->count() > 0 ? ((float) $transaksiSelesai->sum('total') / $transaksiSelesai->count()) : 0;

        return [
            'title' => 'Laporan Penjualan',
            'subtitle' => 'Laporan transaksi penjualan online dan POS',
            'module' => 'penjualan',
            'period_type' => $period['type'],
            'period_label' => $period['label'],
            'month' => $period['month'],
            'year' => $period['year'],
            'summary' => [
                ['label' => 'Total Transaksi', 'value' => number_format($transaksi->count(), 0, ',', '.')],
                ['label' => 'Transaksi Selesai', 'value' => number_format($transaksiSelesai->count(), 0, ',', '.')],
                ['label' => 'Total Pendapatan', 'value' => 'Rp ' . number_format((float) $transaksiSelesai->sum('total'), 0, ',', '.')],
                ['label' => 'Rata-rata Transaksi', 'value' => 'Rp ' . number_format($avg, 0, ',', '.')],
            ],
            'sections' => [
                [
                    'title' => 'Produk Terjual',
                    'headers' => ['Produk', 'Total Terjual'],
                    'rows' => $produkTerjual,
                    'widths' => [36, 18],
                ],
                [
                    'title' => 'Detail Transaksi',
                    'headers' => ['Tanggal', 'Kode', 'Tipe', 'Pelanggan', 'Kasir', 'Status', 'Total'],
                    'rows' => $detailTransaksi,
                    'widths' => [16, 18, 8, 18, 16, 12, 16],
                ],
            ],
        ];
    }

    private function resolvePeriod(Request $request): array
    {
        $type = $request->get('periode') === 'tahunan' ? 'tahunan' : 'bulanan';
        $year = max(2024, (int) $request->get('tahun', now()->year));
        $month = (int) $request->get('bulan', now()->month);
        $month = min(max($month, 1), 12);

        if ($type === 'tahunan') {
            $start = Carbon::create($year, 1, 1)->startOfDay();
            $end = Carbon::create($year, 12, 31)->endOfDay();
            $label = 'Tahun ' . $year;
        } else {
            $start = Carbon::create($year, $month, 1)->startOfDay();
            $end = (clone $start)->endOfMonth();
            $label = $this->monthName($month) . ' ' . $year;
        }

        return [
            'type' => $type,
            'year' => $year,
            'month' => $month,
            'start' => $start,
            'end' => $end,
            'label' => $label,
        ];
    }

    private function ensureAccess(string $module): void
    {
        $user = auth()->user();

        $allowed = match ($module) {
            'gudang' => $user->isOwner() || $user->isAdminGudang(),
            'produksi' => $user->isOwner() || $user->isAdminProduksi(),
            'penjualan' => $user->isOwner() || $user->isAdminPenjualan(),
            default => false,
        };

        abort_unless($allowed, 403);
    }

    private function buildFilename(string $module, array $report): string
    {
        $suffix = $report['period_type'] === 'tahunan'
            ? $report['year']
            : $report['year'] . '-' . str_pad((string) $report['month'], 2, '0', STR_PAD_LEFT);

        return 'laporan-' . $module . '-' . $suffix;
    }

    private function monthName(int $month): string
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ][$month] ?? 'Bulan';
    }

    private function formatDecimal(float|int|string $value): string
    {
        $number = (float) $value;

        return fmod($number, 1.0) === 0.0
            ? number_format($number, 0, ',', '.')
            : number_format($number, 2, ',', '.');
    }
}

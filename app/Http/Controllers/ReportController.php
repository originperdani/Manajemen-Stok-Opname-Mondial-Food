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
use Barryvdh\DomPDF\Facade\Pdf;

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
            $content = $this->buildExcelXml($module, $report, $owner);
            return response($content, 200, $this->downloadHeaders($filename . '.xls', 'application/vnd.ms-excel; charset=utf-8', strlen($content)));
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf', [
                'report' => $report,
                'owner' => $owner,
                'ownerName' => $owner?->name ?? 'Owner Mondial Bakery',
                'generatedBy' => auth()->user()->name,
                'module' => $module,
                'viewer' => $viewer
            ])->setPaper('a4', 'landscape')->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
            ]);

            return $pdf->download($filename . '.pdf');
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
            ->with(['user', 'bahanBaku'])
            ->whereBetween('created_at', [$period['start'], $period['end']])
            ->orderByDesc('created_at')
            ->get();

        $bahanMap = BahanBaku::whereIn('id', $logs->pluck('referensi_id')->unique())
            ->pluck('nama_bahan', 'id');

        // Get all bahan baku
        $allBahan = BahanBaku::all();
        $totalBahanBaku = $allBahan->count();
        
        // Calculate stok at end of period for each bahan
        $stokAkhirPeriode = [];
        foreach ($allBahan as $bahan) {
            // First try: get last log before or at period end
            $lastLogBeforePeriod = StokLog::where('tipe', 'bahan_baku')
                ->where('referensi_id', $bahan->id)
                ->where('created_at', '<=', $period['end'])
                ->orderByDesc('created_at')
                ->first();
            
            if ($lastLogBeforePeriod) {
                $stokAkhir = $lastLogBeforePeriod->stok_sesudah;
            } else {
                // If no log before period, check first log after period (use stok_sebelum)
                $firstLogAfterPeriod = StokLog::where('tipe', 'bahan_baku')
                    ->where('referensi_id', $bahan->id)
                    ->where('created_at', '>', $period['end'])
                    ->orderBy('created_at')
                    ->first();
                
                if ($firstLogAfterPeriod) {
                    $stokAkhir = $firstLogAfterPeriod->stok_sebelum;
                } else {
                    // If no logs at all, use current stok as fallback
                    $stokAkhir = $bahan->stok;
                }
            }
            
            $stokAkhirPeriode[$bahan->id] = $stokAkhir;
        }

        $ringkasanBahan = $allBahan->map(function ($item) use ($stokAkhirPeriode) {
            $stokAkhir = $stokAkhirPeriode[$item->id] ?? 0;
            $nilaiStok = $stokAkhir * $item->harga_per_satuan;
            return [
                'name' => $item->nama_bahan,
                'stok' => $this->formatDecimal($stokAkhir) . ' ' . $item->satuan,
                'stok_min' => $this->formatDecimal($item->stok_minimum) . ' ' . $item->satuan,
                'nilai_stok_formatted' => 'Rp ' . number_format($nilaiStok, 0, ',', '.'),
                'nilai_stok_raw' => $nilaiStok,
            ];
        })->all();

        $logsMasuk = $logs->where('jenis', 'masuk')->map(fn ($log) => [
            Carbon::parse($log->created_at)->format('d/m/Y H:i'),
            $bahanMap[$log->referensi_id] ?? ('ID #' . $log->referensi_id),
            $this->formatDecimal($log->jumlah),
            $this->formatDecimal($log->stok_sebelum),
            $this->formatDecimal($log->stok_sesudah),
            $log->user->name ?? '-',
            $log->keterangan ?? '-',
        ])->all();

        $logsMasukExcel = $logs->where('jenis', 'masuk')->map(fn ($log) => [
            Carbon::parse($log->created_at)->format('d/m/Y H:i'),
            $bahanMap[$log->referensi_id] ?? ('ID #' . $log->referensi_id),
            (float) $log->jumlah,
            (float) $log->stok_sebelum,
            (float) $log->stok_sesudah,
            $log->user->name ?? '-',
            $log->keterangan ?? '-',
        ])->all();

        $logsKeluar = $logs->where('jenis', 'keluar')->map(fn ($log) => [
            Carbon::parse($log->created_at)->format('d/m/Y H:i'),
            $bahanMap[$log->referensi_id] ?? ('ID #' . $log->referensi_id),
            $this->formatDecimal($log->jumlah),
            $this->formatDecimal($log->stok_sebelum),
            $this->formatDecimal($log->stok_sesudah),
            $log->user->name ?? '-',
            $log->keterangan ?? '-',
        ])->all();

        $logsKeluarExcel = $logs->where('jenis', 'keluar')->map(fn ($log) => [
            Carbon::parse($log->created_at)->format('d/m/Y H:i'),
            $bahanMap[$log->referensi_id] ?? ('ID #' . $log->referensi_id),
            (float) $log->jumlah,
            (float) $log->stok_sebelum,
            (float) $log->stok_sesudah,
            $log->user->name ?? '-',
            $log->keterangan ?? '-',
        ])->all();

        // Calculate total nilai persediaan at end of period
        $totalNilaiPersediaan = 0;
        foreach ($allBahan as $bahan) {
            $stokAkhir = $stokAkhirPeriode[$bahan->id] ?? 0;
            $totalNilaiPersediaan += $stokAkhir * $bahan->harga_per_satuan;
        }

        // Prepare section rows for web/PDF and Excel separately
        $ringkasanBahanWebPdf = array_map(function ($item) {
            return [
                $item['name'],
                $item['stok'],
                $item['stok_min'],
                $item['nilai_stok_formatted'],
            ];
        }, $ringkasanBahan);

        $ringkasanBahanExcel = array_map(function ($item) {
            return [
                $item['name'],
                $item['stok'],
                $item['stok_min'],
                $item['nilai_stok_raw'],
            ];
        }, $ringkasanBahan);

        return [
            'title' => 'Laporan Bahan Baku',
            'subtitle' => 'Laporan stok dan pergerakan bahan baku',
            'module' => 'gudang',
            'period_type' => $period['type'],
            'period_label' => $period['label'],
            'month' => $period['month'],
            'year' => $period['year'],
            'date' => $period['date'],
            'total_logs' => $logs->count(),
            'total_bahan_baku' => $totalBahanBaku,
            'total_masuk' => $logs->where('jenis', 'masuk')->sum('jumlah'),
            'total_keluar' => $logs->where('jenis', 'keluar')->sum('jumlah'),
            'stok_menipis' => BahanBaku::whereColumn('stok', '<=', 'stok_minimum')->count(),
            'total_nilai_persediaan' => $totalNilaiPersediaan,
            'summary' => [
                ['label' => 'Total Jenis Bahan Baku', 'value' => number_format($totalBahanBaku, 0, ',', '.')],
                ['label' => 'Total Aktivitas Stok', 'value' => number_format($logs->count(), 0, ',', '.')],
                ['label' => 'Total Bahan Masuk', 'value' => $this->formatDecimal($logs->where('jenis', 'masuk')->sum('jumlah'))],
                ['label' => 'Total Bahan Keluar', 'value' => $this->formatDecimal($logs->where('jenis', 'keluar')->sum('jumlah'))],
                ['label' => 'Bahan Stok Menipis', 'value' => number_format(BahanBaku::whereColumn('stok', '<=', 'stok_minimum')->count(), 0, ',', '.')],
                ['label' => 'Nilai Persediaan Akhir Periode', 'value' => 'Rp ' . number_format((float) $totalNilaiPersediaan, 0, ',', '.')],
            ],
            'sections' => [
                [
                    'title' => 'Ringkasan Stok Bahan Akhir Periode',
                    'headers' => ['Nama Bahan', 'Stok Akhir Periode', 'Stok Minimum', 'Nilai Stok'],
                    'rows' => $ringkasanBahanWebPdf,
                    'rows_excel' => $ringkasanBahanExcel,
                    'widths' => [28, 18, 18, 20],
                ],
                [
                    'title' => 'Riwayat Stok Bahan Baku',
                    'headers_masuk' => ['Tanggal', 'Bahan Baku', 'Jumlah', 'Stok Sebelum', 'Stok Sesudah', 'Petugas', 'Keterangan'],
                    'rows_masuk' => $logsMasuk,
                    'rows_masuk_excel' => $logsMasukExcel,
                    'headers_keluar' => ['Tanggal', 'Bahan Baku', 'Jumlah', 'Stok Sebelum', 'Stok Sesudah', 'Petugas', 'Keterangan'],
                    'rows_keluar' => $logsKeluar,
                    'rows_keluar_excel' => $logsKeluarExcel,
                ],
            ],
            'generated_at' => now(),
        ];
    }

    private function buildProduksiReport(Request $request): array
    {
        $period = $this->resolvePeriod($request);
        $produksi = Produksi::with(['produk', 'resep', 'user'])
            ->whereBetween('tanggal_produksi', [$period['start']->toDateString(), $period['end']->toDateString()])
            ->orderByDesc('tanggal_produksi')
            ->get();

        $ringkasanProdukData = Produksi::query()
            ->join('produk', 'produksi.produk_id', '=', 'produk.id')
            ->whereBetween('tanggal_produksi', [$period['start']->toDateString(), $period['end']->toDateString()])
            ->select('produk.nama_produk', DB::raw('SUM(produksi.jumlah_produksi) as total_jadi'))
            ->groupBy('produk.nama_produk')
            ->orderByDesc('total_jadi')
            ->get();

        $ringkasanProdukWebPdf = $ringkasanProdukData->map(fn ($item) => [
                $item->nama_produk,
                number_format((float) $item->total_jadi, 0, ',', '.'),
            ])
            ->all();

        $ringkasanProdukExcel = $ringkasanProdukData->map(fn ($item) => [
                $item->nama_produk,
                (float) $item->total_jadi,
            ])
            ->all();

        $detailProduksiWebPdf = $produksi->map(fn ($item) => [
            $item->tanggal_produksi->format('d/m/Y'),
            $item->produk->nama_produk ?? '-',
            $item->resep->nama_resep ?? '-',
            number_format((float) $item->jumlah_produksi, 0, ',', '.'),
            strtoupper($item->status),
            $item->user->name ?? '-',
            $item->catatan ?: '-',
        ])->all();

        $detailProduksiExcel = $produksi->map(fn ($item) => [
            $item->tanggal_produksi->format('d/m/Y'),
            $item->produk->nama_produk ?? '-',
            $item->resep->nama_resep ?? '-',
            (float) $item->jumlah_produksi,
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
            'date' => $period['date'],
            'total_produksi' => $produksi->count(),
            'total_jadi' => $produksi->sum('jumlah_produksi'),
            'produk_diproduksi' => $produksi->pluck('produk_id')->filter()->unique()->count(),
            'produksi_selesai' => $produksi->where('status', 'selesai')->count(),
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
                    'rows' => $ringkasanProdukWebPdf,
                    'rows_excel' => $ringkasanProdukExcel,
                    'widths' => [36, 16],
                ],
                [
                    'title' => 'Detail Produksi',
                    'headers' => ['Tanggal', 'Produk', 'Resep', 'Jumlah Jadi', 'Status', 'Petugas', 'Catatan'],
                    'rows' => $detailProduksiWebPdf,
                    'rows_excel' => $detailProduksiExcel,
                    'widths' => [14, 20, 22, 12, 10, 16, 20],
                ],
            ],
            'generated_at' => now(),
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

        $produkTerjualData = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id')
            ->whereBetween('transaksi.created_at', [$period['start'], $period['end']])
            ->where('transaksi.status', '!=', 'dibatalkan')
            ->select('produk.nama_produk', DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'))
            ->groupBy('produk.nama_produk')
            ->orderByDesc('total_terjual')
            ->get();

        $produkTerjualWebPdf = $produkTerjualData->map(fn ($item) => [
            $item->nama_produk,
            number_format((float) $item->total_terjual, 0, ',', '.'),
        ])->all();

        $produkTerjualExcel = $produkTerjualData->map(fn ($item) => [
            $item->nama_produk,
            (float) $item->total_terjual,
        ])->all();

        $detailTransaksi = $transaksi->map(function ($item) {
            $metodeBayar = $item->pembayaran?->metode_label ?? '-';
            return [
                'tanggal' => Carbon::parse($item->created_at)->format('d/m/Y H:i'),
                'kode' => $item->kode_transaksi,
                'tipe' => strtoupper($item->tipe),
                'pelanggan' => $item->nama_pelanggan ?: ($item->user->name ?? '-'),
                'kasir' => $item->kasir->name ?? '-',
                'metode' => $metodeBayar,
                'status' => strtoupper($item->status),
                'total_formatted' => 'Rp ' . number_format((float) $item->total, 0, ',', '.'),
                'total_raw' => (float) $item->total,
            ];
        })->all();

        // Prepare section rows for web/PDF and Excel separately
        $detailTransaksiWebPdf = array_map(function ($item) {
            return [
                $item['tanggal'],
                $item['kode'],
                $item['tipe'],
                $item['pelanggan'],
                $item['kasir'],
                $item['metode'],
                $item['status'],
                $item['total_formatted'],
            ];
        }, $detailTransaksi);

        $detailTransaksiExcel = array_map(function ($item) {
            return [
                $item['tanggal'],
                $item['kode'],
                $item['tipe'],
                $item['pelanggan'],
                $item['kasir'],
                $item['metode'],
                $item['status'],
                $item['total_raw'],
            ];
        }, $detailTransaksi);

        $avg = $transaksiSelesai->count() > 0 ? ((float) $transaksiSelesai->sum('total') / $transaksiSelesai->count()) : 0;

        // Calculate total per payment method
        $totalPerMetode = [];
        $metodeLabels = [
            'cash' => 'CASH',
            'qris' => 'QRIS',
            'mandiri' => 'Mandiri',
            'bca' => 'BCA',
            'bri' => 'BRI',
            'bni' => 'BNI',
            'e_wallet' => 'E-Wallet',
            'm_banking' => 'M-Banking',
        ];
        
        foreach ($metodeLabels as $metode => $label) {
            $total = $transaksiSelesai->filter(function ($t) use ($metode) {
                return $t->pembayaran?->metode === $metode;
            })->sum('total');
            $totalPerMetode[] = [
                'label' => $label,
                'total_raw' => (float) $total,
                'total_formatted' => 'Rp ' . number_format((float) $total, 0, ',', '.'),
            ];
        }

        // Prepare summary rows for payment methods
        $totalPerMetodeWebPdf = array_map(fn ($item) => [$item['label'], $item['total_formatted']], $totalPerMetode);
        $totalPerMetodeExcel = array_map(fn ($item) => [$item['label'], $item['total_raw']], $totalPerMetode);

        return [
            'title' => 'Laporan Penjualan',
            'subtitle' => 'Laporan transaksi penjualan online dan POS',
            'module' => 'penjualan',
            'period_type' => $period['type'],
            'period_label' => $period['label'],
            'month' => $period['month'],
            'year' => $period['year'],
            'date' => $period['date'],
            'total_transaksi' => $transaksi->count(),
            'transaksi_selesai' => $transaksiSelesai->count(),
            'total_pendapatan' => $transaksiSelesai->sum('total'),
            'avg_transaksi' => $avg,
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
                    'rows' => $produkTerjualWebPdf,
                    'rows_excel' => $produkTerjualExcel,
                    'widths' => [36, 18],
                ],
                [
                    'title' => 'Total Per Metode Pembayaran',
                    'headers' => ['Metode Pembayaran', 'Total'],
                    'rows' => $totalPerMetodeWebPdf,
                    'rows_excel' => $totalPerMetodeExcel,
                    'widths' => [36, 18],
                ],
                [
                    'title' => 'Detail Transaksi',
                    'headers' => ['Tanggal', 'Kode', 'Tipe', 'Pelanggan', 'Kasir', 'Metode', 'Status', 'Total'],
                    'rows' => $detailTransaksiWebPdf,
                    'rows_excel' => $detailTransaksiExcel,
                    'widths' => [14, 16, 8, 16, 14, 14, 10, 14],
                ],
            ],
            'generated_at' => now(),
        ];
    }

    private function resolvePeriod(Request $request): array
    {
        $type = $request->get('periode') === 'tahunan' ? 'tahunan' : ($request->get('periode') === 'all' ? 'all' : ($request->get('periode') === 'harian' ? 'harian' : 'bulanan'));
        $year = max(2024, (int) $request->get('tahun', now()->year));
        $month = $request->get('bulan', now()->month);
        $date = $request->get('tanggal', now()->format('Y-m-d'));

        if ($type === 'tahunan') {
            $start = Carbon::create($year, 1, 1)->startOfDay();
            $end = Carbon::create($year, 12, 31)->endOfDay();
            $label = 'Tahun ' . $year;
        } elseif ($type === 'all') {
            $start = Carbon::create($year, 1, 1)->startOfDay();
            $end = Carbon::create($year, 12, 31)->endOfDay();
            $label = 'Semua Bulan ' . $year;
        } elseif ($type === 'harian') {
            $start = Carbon::parse($date)->startOfDay();
            $end = Carbon::parse($date)->endOfDay();
            $label = $start->translatedFormat('d F Y');
        } else {
            $month = min(max((int)$month, 1), 12);
            $start = Carbon::create($year, $month, 1)->startOfDay();
            $end = (clone $start)->endOfMonth();
            $label = $this->monthName($month) . ' ' . $year;
        }

        return [
            'type' => $type,
            'year' => $year,
            'month' => $month,
            'date' => $date,
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
        if ($report['period_type'] === 'harian') {
            $suffix = Carbon::parse($report['date'])->format('d_m_Y');
        } elseif ($report['period_type'] === 'tahunan') {
            $suffix = 'Tahun_' . $report['year'];
        } elseif ($report['period_type'] === 'all') {
            $suffix = 'SemuaBulan_' . $report['year'];
        } else {
            $suffix = $this->monthName($report['month']) . '_' . $report['year'];
        }
        
        return 'Laporan_' . ucfirst($module) . '_' . $suffix;
    }

    private function downloadHeaders(string $filename, string $contentType, int $contentLength): array
    {
        return [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"; filename*=UTF-8\'\'' . rawurlencode($filename),
            'Content-Length' => (string) $contentLength,
            'Content-Transfer-Encoding' => 'binary',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public',
            'X-Content-Type-Options' => 'nosniff',
        ];
    }

    private function buildExcelXml(string $module, array $report, $owner): string
    {
        $primaryColor = '#d97706'; // Orange for Mondial Bakery theme
        $secondaryColor = '#fef3c7';
        $borderColor = '#92400e';
        $borderXml = '<Borders><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="' . $borderColor . '"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="' . $borderColor . '"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="' . $borderColor . '"/><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="' . $borderColor . '"/></Borders>';
        $accountingFormat = '_(&quot;Rp&quot;* #,##0_);_(&quot;Rp&quot;* (#,##0);_(&quot;Rp&quot;* &quot;-&quot;_);_(@_)';
        $dataColumnWidths = match ($module) {
            'gudang' => [110, 104, 54, 116, 104, 72, 118],
            'produksi' => [112, 115, 100, 110, 60, 72, 115],
            'penjualan' => [108, 84, 42, 120, 72, 64, 120],
            default => [88, 110, 110, 110, 110, 110, 110],
        };
        $contentStartIndex = 2;
        $columnWidths = array_merge([68], $dataColumnWidths, [68]);
        $columnCount = count($columnWidths);
        $mergeAcross = count($dataColumnWidths) - 1;

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" ';
        $xml .= 'xmlns:o="urn:schemas-microsoft-com:office:office" ';
        $xml .= 'xmlns:x="urn:schemas-microsoft-com:office:excel" ';
        $xml .= 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" ';
        $xml .= 'xmlns:html="http://www.w3.org/TR/REC-html40">';

        // Styles
        $xml .= '<Styles>';
        $xml .= '<Style ss:ID="Default" ss:Name="Normal"><Font ss:FontName="Calibri" ss:Size="8" ss:Color="#111827"/><Alignment ss:Vertical="Center"/></Style>';
        $xml .= '<Style ss:ID="Title"><Font ss:Bold="1" ss:Size="14" ss:Color="' . $primaryColor . '"/><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/></Style>';
        $xml .= '<Style ss:ID="Subtitle"><Font ss:Size="8" ss:Color="#64748b"/><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/></Style>';
        $xml .= '<Style ss:ID="SectionTitle"><Font ss:Bold="1" ss:Size="10" ss:Color="#111827"/><Alignment ss:Horizontal="Left" ss:Vertical="Center" ss:WrapText="1"/></Style>';
        $xml .= '<Style ss:ID="SubSection"><Font ss:Bold="1" ss:Size="8" ss:Color="#111827"/><Alignment ss:Horizontal="Left" ss:Vertical="Center"/></Style>';
        $xml .= '<Style ss:ID="SummaryLabel"><Interior ss:Color="' . $secondaryColor . '" ss:Pattern="Solid"/><Font ss:Bold="1" ss:Size="8" ss:Color="' . $primaryColor . '"/>' . $borderXml . '<Alignment ss:Horizontal="Left" ss:Vertical="Center" ss:WrapText="1" ss:ShrinkToFit="1"/></Style>';
        $xml .= '<Style ss:ID="SummaryValue"><Font ss:Bold="1" ss:Size="8" ss:Color="#111827"/>' . $borderXml . '<Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1" ss:ShrinkToFit="1"/></Style>';
        $xml .= '<Style ss:ID="SignatureName"><Font ss:Bold="1" ss:Size="8" ss:Color="#111827"/><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/></Style>';
        $xml .= '<Style ss:ID="Header"><Interior ss:Color="' . $primaryColor . '" ss:Pattern="Solid"/><Font ss:Bold="1" ss:Size="8" ss:Color="#ffffff"/><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1" ss:ShrinkToFit="1"/>' . $borderXml . '</Style>';
        $xml .= '<Style ss:ID="Number"><NumberFormat ss:Format="#,##0.##"/>' . $borderXml . '<Alignment ss:Horizontal="Right" ss:Vertical="Center"/></Style>';
        $xml .= '<Style ss:ID="Currency"><NumberFormat ss:Format="' . $accountingFormat . '"/>' . $borderXml . '<Alignment ss:Horizontal="Right" ss:Vertical="Center"/></Style>';
        $xml .= '<Style ss:ID="Cell">' . $borderXml . '<Alignment ss:Horizontal="Left" ss:Vertical="Center" ss:WrapText="1" ss:ShrinkToFit="1"/></Style>';
        $xml .= '<Style ss:ID="Empty"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Font ss:Italic="1" ss:Size="8" ss:Color="#64748b"/>' . $borderXml . '</Style>';
        $xml .= '</Styles>';

        $xml .= '<Worksheet ss:Name="Laporan">';
        
        // Fit every Excel report to one printed page width without shrinking rows into a single page.
        $xml .= '<x:WorksheetOptions>';
        $xml .= '<x:PageSetup>';
        $xml .= '<x:Layout x:Orientation="Landscape" x:CenterHorizontal="1"/>';
        $xml .= '<x:Header x:Margin="0.25"/>';
        $xml .= '<x:Footer x:Margin="0.25"/>';
        $xml .= '<x:PageMargins x:Bottom="0.35" x:Left="0.2" x:Right="0.2" x:Top="0.35"/>';
        $xml .= '</x:PageSetup>';
        $xml .= '<x:FitToPage/>';
        $xml .= '<x:Print>';
        $xml .= '<x:FitWidth>1</x:FitWidth>';
        $xml .= '<x:FitHeight>0</x:FitHeight>';
        $xml .= '<x:PaperSizeIndex>9</x:PaperSizeIndex>';
        $xml .= '<x:ValidPrinterInfo/>';
        $xml .= '</x:Print>';
        $xml .= '</x:WorksheetOptions>';

        $xml .= '<Table ss:ExpandedColumnCount="' . $columnCount . '" ss:DefaultRowHeight="16">';

        foreach ($columnWidths as $width) {
            $xml .= '<Column ss:AutoFitWidth="0" ss:Width="' . $width . '"/>';
        }

        // Header - Brand
        $xml .= '<Row ss:Height="28"><Cell ss:Index="' . $contentStartIndex . '" ss:MergeAcross="' . $mergeAcross . '" ss:StyleID="Title"><Data ss:Type="String">Mondial Bakery</Data></Cell></Row>';
        $xml .= '<Row ss:AutoFitHeight="1"><Cell ss:Index="' . $contentStartIndex . '" ss:MergeAcross="' . $mergeAcross . '" ss:StyleID="Subtitle"><Data ss:Type="String">' . $this->xml($report['title']) . '</Data></Cell></Row>';
        $xml .= '<Row ss:AutoFitHeight="1"><Cell ss:Index="' . $contentStartIndex . '" ss:MergeAcross="' . $mergeAcross . '" ss:StyleID="Subtitle"><Data ss:Type="String">' . $this->xml($report['period_label']) . ' - Dicetak ' . $this->xml($report['generated_at']->format('d/m/Y H:i')) . '</Data></Cell></Row>';
        $xml .= '<Row ss:Height="12"><Cell/></Row>';

        // Summary section
        if ($module === 'gudang') {
            $xml .= $this->summaryRow('Total Jenis Bahan Baku', $report['total_bahan_baku'], 'Total Aktivitas Stok', $report['total_logs'], false, $contentStartIndex);
            $xml .= $this->summaryRow('Total Bahan Masuk', $report['total_masuk'], 'Total Bahan Keluar', $report['total_keluar'], false, $contentStartIndex);
            $xml .= $this->summaryRow('Bahan Stok Menipis', $report['stok_menipis'], 'Nilai Persediaan Akhir', $report['total_nilai_persediaan'], true, $contentStartIndex);
        } elseif ($module === 'produksi') {
            $xml .= $this->summaryRow('Total Aktivitas Produksi', $report['total_produksi'], 'Total Bahan Jadi', $report['total_jadi'], false, $contentStartIndex);
            $xml .= $this->summaryRow('Produk Diproduksi', $report['produk_diproduksi'], 'Produksi Selesai', $report['produksi_selesai'], false, $contentStartIndex);
        } elseif ($module === 'penjualan') {
            $xml .= $this->summaryRow('Total Transaksi', $report['total_transaksi'], 'Transaksi Selesai', $report['transaksi_selesai'], false, $contentStartIndex);
            $xml .= $this->summaryRow('Total Pendapatan', $report['total_pendapatan'], 'Rata-rata Transaksi', $report['avg_transaksi'], true, $contentStartIndex);
        }

        $xml .= '<Row ss:Height="12"><Cell/></Row>';

        // Data sections
        foreach ($report['sections'] as $section) {
            $xml .= '<Row ss:AutoFitHeight="1" ss:Height="26"><Cell ss:Index="' . $contentStartIndex . '" ss:MergeAcross="' . $mergeAcross . '" ss:StyleID="SectionTitle"><Data ss:Type="String">' . $this->xml(strtoupper($section['title'])) . '</Data></Cell></Row>';
            
            if ($module === 'gudang' && isset($section['headers_masuk'])) {
                // Stok Masuk
                $xml .= '<Row ss:Height="20"><Cell ss:Index="' . $contentStartIndex . '" ss:StyleID="SubSection"><Data ss:Type="String">STOK MASUK</Data></Cell></Row>';
                $xml .= '<Row ss:AutoFitHeight="1" ss:Height="30">';
                foreach ($section['headers_masuk'] as $index => $header) {
                    $xml .= $this->excelHeader($header, $index === 0 ? $contentStartIndex : null);
                }
                $xml .= '</Row>';
                
                $rowsMasuk = $section['rows_masuk_excel'] ?? $section['rows_masuk'];

                if (empty($rowsMasuk)) {
                    $xml .= '<Row ss:Height="24"><Cell ss:Index="' . $contentStartIndex . '" ss:MergeAcross="' . (count($section['headers_masuk']) - 1) . '" ss:StyleID="Empty"><Data ss:Type="String">Tidak ada data</Data></Cell></Row>';
                } else {
                    foreach ($rowsMasuk as $row) {
                        $xml .= '<Row ss:AutoFitHeight="1" ss:Height="28">';
                        foreach ($row as $index => $cell) {
                            if (in_array($index, [2, 3, 4], true)) {
                                $xml .= $this->excelCell($cell, 'Number', 'Number', $index === 0 ? $contentStartIndex : null);
                            } else {
                                $xml .= $this->excelCell($cell, 'String', null, $index === 0 ? $contentStartIndex : null);
                            }
                        }
                        $xml .= '</Row>';
                    }
                }

                $xml .= '<Row ss:Height="12"><Cell/></Row>';

                // Stok Keluar
                $xml .= '<Row ss:Height="20"><Cell ss:Index="' . $contentStartIndex . '" ss:StyleID="SubSection"><Data ss:Type="String">STOK KELUAR</Data></Cell></Row>';
                $xml .= '<Row ss:AutoFitHeight="1" ss:Height="30">';
                foreach ($section['headers_keluar'] as $index => $header) {
                    $xml .= $this->excelHeader($header, $index === 0 ? $contentStartIndex : null);
                }
                $xml .= '</Row>';
                
                $rowsKeluar = $section['rows_keluar_excel'] ?? $section['rows_keluar'];

                if (empty($rowsKeluar)) {
                    $xml .= '<Row ss:Height="24"><Cell ss:Index="' . $contentStartIndex . '" ss:MergeAcross="' . (count($section['headers_keluar']) - 1) . '" ss:StyleID="Empty"><Data ss:Type="String">Tidak ada data</Data></Cell></Row>';
                } else {
                    foreach ($rowsKeluar as $row) {
                        $xml .= '<Row ss:AutoFitHeight="1" ss:Height="28">';
                        foreach ($row as $index => $cell) {
                            if (in_array($index, [2, 3, 4], true)) {
                                $xml .= $this->excelCell($cell, 'Number', 'Number', $index === 0 ? $contentStartIndex : null);
                            } else {
                                $xml .= $this->excelCell($cell, 'String', null, $index === 0 ? $contentStartIndex : null);
                            }
                        }
                        $xml .= '</Row>';
                    }
                }
            } else {
                // Regular table
                $xml .= '<Row ss:AutoFitHeight="1" ss:Height="30">';
                foreach ($section['headers'] as $index => $header) {
                    $xml .= $this->excelHeader($header, $index === 0 ? $contentStartIndex : null);
                }
                $xml .= '</Row>';

                $sectionRows = isset($section['rows_excel']) ? $section['rows_excel'] : $section['rows'];

                if (empty($sectionRows)) {
                    $xml .= '<Row ss:Height="24"><Cell ss:Index="' . $contentStartIndex . '" ss:MergeAcross="' . (count($section['headers']) - 1) . '" ss:StyleID="Empty"><Data ss:Type="String">Tidak ada data</Data></Cell></Row>';
                } else {
                    foreach ($sectionRows as $row) {
                        $xml .= '<Row ss:AutoFitHeight="1" ss:Height="28">';
                        foreach ($row as $index => $cell) {
                            $header = strtolower($section['headers'][$index] ?? '');
                            $cellIndex = $index === 0 ? $contentStartIndex : null;

                            if (in_array($header, ['nilai stok', 'total'], true)) {
                                $xml .= $this->excelCell($cell, 'Number', 'Currency', $cellIndex);
                            } elseif (is_int($cell) || is_float($cell)) {
                                $xml .= $this->excelCell($cell, 'Number', 'Number', $cellIndex);
                            } else {
                                $xml .= $this->excelCell($cell, 'String', null, $cellIndex);
                            }
                        }
                        $xml .= '</Row>';
                    }
                    
                    // Add grand total for Produk Terjual section
                    if ($module === 'penjualan' && strtoupper($section['title']) === 'PRODUK TERJUAL') {
                        $totalProdukTerjual = 0;
                        foreach ($sectionRows as $row) {
                            // Assume second column is the number (index 1)
                            $totalProdukTerjual += (float) $row[1];
                        }
                        $xml .= '<Row ss:Height="24">';
                        $xml .= '<Cell ss:Index="' . $contentStartIndex . '" ss:StyleID="SummaryLabel"><Data ss:Type="String">Keseluruhan Produk Terjual</Data></Cell>';
                        $xml .= $this->excelCell($totalProdukTerjual, 'Number');
                        $xml .= '</Row>';
                    }
                }
            }

            $xml .= '<Row ss:Height="12"><Cell/></Row>';
        }

        $xml .= '<Row ss:Height="24"><Cell ss:Index="' . $contentStartIndex . '"><Data ss:Type="String">Depok, ' . $report['generated_at']->format('d/m/Y') . '</Data></Cell></Row>';
        $xml .= '<Row ss:Height="24"><Cell ss:Index="' . $contentStartIndex . '"><Data ss:Type="String">Mengetahui,</Data></Cell></Row>';
        $xml .= '<Row ss:Height="60"><Cell/></Row>';
        $xml .= '<Row ss:Height="24"><Cell ss:Index="' . $contentStartIndex . '" ss:StyleID="SignatureName"><Data ss:Type="String">' . $this->xml($owner?->name ?? 'Owner Mondial Bakery') . '</Data></Cell></Row>';
        $xml .= '<Row ss:Height="24"><Cell ss:Index="' . $contentStartIndex . '"><Data ss:Type="String">Owner</Data></Cell></Row>';

        $xml .= '</Table></Worksheet></Workbook>';

        return $xml;
    }

    private function summaryRow(string $labelA, $valueA, string $labelB, $valueB, bool $isCurrency = false, int $startIndex = 1): string
    {
        $row = '<Row ss:AutoFitHeight="1" ss:Height="34">';
        $row .= '<Cell ss:Index="' . $startIndex . '" ss:MergeAcross="1" ss:StyleID="SummaryLabel"><Data ss:Type="String">' . $this->xml($labelA) . '</Data></Cell>';
        $row .= $this->excelCell(
            $valueA,
            is_numeric($valueA) ? 'Number' : 'String',
            is_numeric($valueA) ? 'Number' : 'SummaryValue'
        );
        $row .= '<Cell ss:MergeAcross="1" ss:StyleID="SummaryLabel"><Data ss:Type="String">' . $this->xml($labelB) . '</Data></Cell>';
        $row .= $this->excelCell(
            $valueB,
            is_numeric($valueB) ? 'Number' : 'String',
            $isCurrency ? 'Currency' : (is_numeric($valueB) ? 'Number' : 'SummaryValue'),
            null,
            1
        );
        $row .= '</Row>';
        return $row;
    }

    private function excelHeader(string $value, ?int $index = null): string
    {
        $indexAttribute = $index ? ' ss:Index="' . $index . '"' : '';
        return '<Cell' . $indexAttribute . ' ss:StyleID="Header"><Data ss:Type="String">' . $this->xml($value) . '</Data></Cell>';
    }

    private function excelCell(mixed $value, string $type = 'String', ?string $style = null, ?int $index = null, ?int $mergeAcross = null): string
    {
        $styleToUse = $style ?? ($type === 'Number' ? 'Number' : 'Cell');
        $indexAttribute = $index ? ' ss:Index="' . $index . '"' : '';
        $mergeAttribute = $mergeAcross !== null ? ' ss:MergeAcross="' . $mergeAcross . '"' : '';
        $styleAttribute = ' ss:StyleID="' . $styleToUse . '"';
        if ($type === 'Number') {
            $value = (string) ((float) $value);
        } else {
            $value = $this->xml((string) $value);
        }
        return '<Cell' . $indexAttribute . $mergeAttribute . $styleAttribute . '><Data ss:Type="' . $type . '">' . $value . '</Data></Cell>';
    }

    private function xml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
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

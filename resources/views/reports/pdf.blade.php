<!DOCTYPE html>
<html>
<head>
    <title>{{ $report['title'] }}</title>
    <style>
        @page {
            margin: 22px 24px 34px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1f2937;
            font-size: 10px;
            line-height: 1.35;
        }

        .header {
            border-bottom: 3px solid #d97706;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }

        .brand {
            color: #d97706;
            font-size: 22px;
            font-weight: bold;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0 0;
        }

        .meta {
            color: #64748b;
            margin-top: 4px;
        }

        .summary {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin: 0 -8px 14px;
        }

        .summary td {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 10px;
        }

        .summary-label {
            display: block;
            color: #64748b;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .summary-value {
            color: #111827;
            font-size: 13px;
            font-weight: bold;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #d97706;
            margin-top: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .sub-section-title {
            font-size: 10px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 8px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .report-table th {
            background: #d97706;
            color: #ffffff;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 6px;
            border: 1px solid #92400e;
        }

        .report-table td {
            padding: 7px 6px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            word-wrap: break-word;
        }

        .report-table tr:nth-child(even) td {
            background: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .muted {
            color: #64748b;
            font-size: 8px;
        }

        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -20px;
            color: #94a3b8;
            font-size: 8px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td>
                    <h1 class="brand">Mondial Bakery</h1>
                    <p class="title">{{ $report['title'] }}</p>
                    <p class="meta">{{ $report['period_label'] }}</p>
                </td>
                <td style="text-align: right;">
                    <p class="meta">Tanggal cetak</p>
                    <p style="font-weight: bold; margin: 0;">{{ $report['generated_at']->format('d/m/Y H:i') }} WIB</p>
                </td>
            </tr>
        </table>
    </div>

    @if($report['module'] === 'gudang')
        <table class="summary">
            <tr>
                <td>
                    <span class="summary-label">Total Jenis Bahan Baku</span>
                    <span class="summary-value">{{ number_format($report['total_bahan_baku'], 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="summary-label">Total Aktivitas Stok</span>
                    <span class="summary-value">{{ number_format($report['total_logs'], 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="summary-label">Total Bahan Masuk</span>
                    <span class="summary-value">{{ number_format($report['total_masuk'], 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="summary-label">Bahan Stok Menipis</span>
                    <span class="summary-value">{{ number_format($report['stok_menipis'], 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="summary-label">Nilai Persediaan Akhir</span>
                    <span class="summary-value">Rp {{ number_format($report['total_nilai_persediaan'], 0, ',', '.') }}</span>
                </td>
            </tr>
        </table>
    @elseif($report['module'] === 'produksi')
        <table class="summary">
            <tr>
                <td>
                    <span class="summary-label">Total Aktivitas Produksi</span>
                    <span class="summary-value">{{ number_format($report['total_produksi'], 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="summary-label">Total Bahan Jadi</span>
                    <span class="summary-value">{{ number_format($report['total_jadi'], 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="summary-label">Produk Diproduksi</span>
                    <span class="summary-value">{{ number_format($report['produk_diproduksi'], 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="summary-label">Produksi Selesai</span>
                    <span class="summary-value">{{ number_format($report['produksi_selesai'], 0, ',', '.') }}</span>
                </td>
            </tr>
        </table>
    @elseif($report['module'] === 'penjualan')
        <table class="summary">
            <tr>
                <td>
                    <span class="summary-label">Total Transaksi</span>
                    <span class="summary-value">{{ number_format($report['total_transaksi'], 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="summary-label">Transaksi Selesai</span>
                    <span class="summary-value">{{ number_format($report['transaksi_selesai'], 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="summary-label">Total Pendapatan</span>
                    <span class="summary-value">Rp {{ number_format($report['total_pendapatan'], 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="summary-label">Rata-rata Transaksi</span>
                    <span class="summary-value">Rp {{ number_format($report['avg_transaksi'], 0, ',', '.') }}</span>
                </td>
            </tr>
        </table>
    @endif

    @foreach($report['sections'] as $section)
        <div class="section-title">{{ $section['title'] }}</div>
        
        @if($report['module'] === 'gudang' && isset($section['headers_masuk']))
            <div class="sub-section-title">STOK MASUK</div>
            <table class="report-table">
                <thead>
                    <tr>
                        @foreach($section['headers_masuk'] as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($section['rows_masuk'] as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td>{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($section['headers_masuk']) }}" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="sub-section-title">STOK KELUAR</div>
            <table class="report-table">
                <thead>
                    <tr>
                        @foreach($section['headers_keluar'] as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($section['rows_keluar'] as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td>{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($section['headers_keluar']) }}" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <table class="report-table">
                            <thead>
                                <tr>
                                    @foreach($section['headers'] as $hIndex => $header)
                                        <th width="{{ $section['widths'][$hIndex] ?? '' }}%">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($section['rows'] as $row)
                                    <tr>
                                        @foreach($row as $index => $cell)
                                        <td>
                                            @if(isset($section['headers'][$index]) && in_array(strtolower($section['headers'][$index]), ['nilai stok', 'total']))
                                                {{ $cell }}
                                            @else
                                                {{ $cell }}
                                            @endif
                                        </td>
                                    @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($section['headers']) }}" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                @if($report['module'] === 'penjualan' && strtoupper($section['title']) === 'PRODUK TERJUAL')
                                    @php
                                        $totalProdukTerjual = 0;
                                        foreach($section['rows'] as $row) {
                                            // Remove any non-numeric characters (like dots used as thousand separators)
                                            $cleanNumber = preg_replace('/[^0-9]/', '', $row[1]);
                                            $totalProdukTerjual += (int) $cleanNumber;
                                        }
                                    @endphp
                                    <tr style="background: #fef3c7; font-weight: bold;">
                                        <td style="border-top: 2px solid #d97706;">Keseluruhan Produk Terjual</td>
                                        <td style="border-top: 2px solid #d97706;">{{ number_format($totalProdukTerjual, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
        @endif
    @endforeach

    <div style="margin-top: 40px; page-break-inside: avoid;">
        <div style="float: right; width: 200px; text-align: center;">
            <p>Depok, {{ $report['generated_at']->format('d/m/Y') }}</p>
            <p>Mengetahui,</p>
            <p><strong>Owner Mondial Bakery</strong></p>
            <div style="height: 60px;"></div>
            <p style="font-weight: bold; text-decoration: underline;">{{ $ownerName }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        Laporan ini dibuat otomatis oleh Sistem Informasi Mondial Bakery
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>{{ $report['title'] }}</title>
    <style>
        /* Reset default margins for print */
        @page {
            margin: 0;
            size: auto;
        }
        
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 10pt; 
            color: #333; 
            line-height: 1.4; 
            padding: 0; 
            margin: 0; 
        }
        
        /* Kop Surat Styles - Full Width */
        .kop-surat {
            width: 100%;
            margin: 0;
            padding: 0;
            line-height: 0; /* Remove extra space below image */
        }
        .kop-surat img {
            width: 100%;
            height: auto;
            display: block;
            margin: 0;
            padding: 0;
        }

        /* Content Wrapper - To give margins to the report data only */
        .content-wrapper {
            padding: 0 40px 40px 40px;
        }

        .report-title { text-align: center; margin-bottom: 20px; margin-top: 20px; }
        .report-title h2 { margin: 0; font-size: 14pt; text-transform: uppercase; }
        .report-title p { margin: 5px 0; font-size: 10pt; }

        .info { margin-bottom: 20px; width: 100%; }
        .info td { padding: 2px 0; }

        .summary-box { background: #f9f9f9; border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; }
        .summary-box h3 { margin-top: 0; font-size: 11pt; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .summary-item { margin-bottom: 5px; }
        .summary-label { font-weight: bold; width: 180px; display: inline-block; }

        .section { margin-bottom: 30px; }
        .section-title { font-weight: bold; font-size: 11pt; margin-bottom: 10px; text-transform: uppercase; color: #2c3e50; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; table-layout: fixed; }
        table.data-table th { background: #f2f2f2; border: 1px solid #ccc; padding: 8px 5px; text-align: left; font-size: 9pt; }
        table.data-table td { border: 1px solid #eee; padding: 6px 5px; font-size: 8.5pt; word-wrap: break-word; vertical-align: top; }
        
        .footer { margin-top: 50px; width: 100%; }
        .signature { float: right; width: 200px; text-align: center; }
        .signature-space { height: 80px; display: flex; align-items: center; justify-content: center; }
        .signature-name { font-weight: bold; text-decoration: underline; }

        .page-break { page-break-after: always; }

        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; }
            .header { border-bottom: 2px solid #000; }
        }
    </style>
</head>
<body onload="{{ isset($isPrint) ? 'window.print()' : '' }}">
    @if(isset($isPrint))
    <div class="no-print" style="background: #f8d7da; color: #721c24; padding: 10px; text-align: center; margin-bottom: 20px;">
        Halaman ini siap cetak. Gunakan fitur <strong>Save as PDF</strong> pada menu print browser Anda. 
        <button onclick="window.print()" style="margin-left: 10px;">Cetak Sekarang</button>
        
        @php
            if (isset($viewer) && $viewer === 'owner') {
                $backUrl = route('owner.reports.' . $module);
            } else {
                $backUrl = route($module . '.laporan');
            }
        @endphp
        <a href="{{ $backUrl }}" style="margin-left: 5px; text-decoration: none; background: #efefef; color: black; padding: 2px 10px; border: 1px solid #767676; border-radius: 2px; font-size: 13px; display: inline-block; vertical-align: middle;">Kembali</a>
    </div>
    @endif
 
    <div class="kop-surat">
        <img src="{{ asset('images/kop-surat.png') }}" alt="Kop Surat">
    </div>

    <div class="content-wrapper">
        <div class="report-title">
            <h2>{{ $report['title'] }}</h2>
            <p>{{ $report['subtitle'] }}</p>
        </div>

        <table class="info">
            <tr>
                <td width="100">Periode</td>
                <td>: {{ $report['period_label'] }}</td>
                <td width="100" align="right">Dicetak pada</td>
                <td width="120">: {{ date('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Dicetak oleh</td>
                <td>: {{ $generatedBy }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <div class="summary-box">
            <h3>RINGKASAN</h3>
            @foreach($report['summary'] as $summary)
                <div class="summary-item">
                    <span class="summary-label">{{ $summary['label'] }}</span>
                    <span>: {{ $summary['value'] }}</span>
                </div>
            @endforeach
        </div>

        @foreach($report['sections'] as $index => $section)
            <div class="section">
                <div class="section-title">{{ $section['title'] }}</div>
                <table class="data-table">
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
                                @foreach($row as $cell)
                                    <td>{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($section['headers']) }}" align="center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(!$loop->last && count($section['rows']) > 15)
                <div class="page-break"></div>
            @endif
        @endforeach

        <div class="footer">
            <div class="signature">
                <p>Jakarta, {{ date('d/m/Y') }}</p>
                <p>Mengetahui,</p>
                <p><strong>Owner Mondial Bakery</strong></p>
                <div class="signature-space">
                    @php
                        $ttdPath = $owner && $owner->ttd ? 'storage/' . $owner->ttd : null;
                        $fotoPath = $owner && $owner->foto ? 'storage/' . $owner->foto : null;
                    @endphp
                    
                    @if($ttdPath && file_exists(public_path($ttdPath)))
                        <img src="{{ asset($ttdPath) }}" height="60">
                    @elseif($fotoPath && file_exists(public_path($fotoPath)))
                        <img src="{{ asset($fotoPath) }}" height="60">
                    @else
                        <br><br><br>
                        <p style="color: #ccc; font-style: italic;">(Tanda Tangan)</p>
                    @endif
                </div>
                <p class="signature-name">{{ $ownerName }}</p>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</body>
</html>

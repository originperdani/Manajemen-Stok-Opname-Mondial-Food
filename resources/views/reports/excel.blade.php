<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <table>
        <tr>
            <td colspan="{{ count($report['sections'][0]['headers'] ?? [1]) }}" style="font-weight: bold; font-size: 16pt; text-align: center; color: #ff9a44;">MONDIAL FOOD</td>
        </tr>
        <tr>
            <td colspan="{{ count($report['sections'][0]['headers'] ?? [1]) }}" style="text-align: center; font-style: italic; color: #ff9a44;">Life is better with cake</td>
        </tr>
        <tr>
            <td colspan="{{ count($report['sections'][0]['headers'] ?? [1]) }}" style="text-align: center; font-size: 9pt;">Jl. Masjid Al Akhyar no.34 RT 08/RW 08, gandul, cinere, depok 16512</td>
        </tr>
        <tr>
            <td colspan="{{ count($report['sections'][0]['headers'] ?? [1]) }}" style="text-align: center; font-size: 9pt;">Email : mondialfood.co@gmail.com, tlp 0217661291, WA 0813-1102-9808</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td colspan="{{ count($report['sections'][0]['headers'] ?? [1]) }}" style="font-weight: bold; font-size: 14pt; text-align: center; border-top: 2px solid #000;">{{ strtoupper($report['title']) }}</td>
        </tr>
        <tr>
            <td colspan="{{ count($report['sections'][0]['headers'] ?? [1]) }}" style="text-align: center;">Periode: {{ $report['period_label'] }}</td>
        </tr>
        <tr><td></td></tr>

        <tr>
            <td style="font-weight: bold;">RINGKASAN</td>
        </tr>
        @foreach($report['summary'] as $summary)
            <tr>
                <td>{{ $summary['label'] }}</td>
                <td>{{ $summary['value'] }}</td>
            </tr>
        @endforeach
        <tr><td></td></tr>

        @foreach($report['sections'] as $section)
            <tr>
                <td style="font-weight: bold;">{{ strtoupper($section['title']) }}</td>
            </tr>

            @if($report['module'] === 'gudang' && isset($section['headers_masuk']))
                <!-- Stok Masuk -->
                <tr>
                    <td style="font-weight: bold;">STOK MASUK</td>
                </tr>
                <tr>
                    @foreach($section['headers_masuk'] as $header)
                        <th style="background-color: #f2f2f2; font-weight: bold; border: 1px solid #000000;">{{ $header }}</th>
                    @endforeach
                </tr>
                @forelse($section['rows_masuk'] as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td style="border: 1px solid #000000;">{{ $cell }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($section['headers_masuk']) }}" style="text-align: center; border: 1px solid #000000;">Tidak ada data</td>
                    </tr>
                @endforelse
                <tr><td></td></tr>

                <!-- Stok Keluar -->
                <tr>
                    <td style="font-weight: bold;">STOK KELUAR</td>
                </tr>
                <tr>
                    @foreach($section['headers_keluar'] as $header)
                        <th style="background-color: #f2f2f2; font-weight: bold; border: 1px solid #000000;">{{ $header }}</th>
                    @endforeach
                </tr>
                @forelse($section['rows_keluar'] as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td style="border: 1px solid #000000;">{{ $cell }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($section['headers_keluar']) }}" style="text-align: center; border: 1px solid #000000;">Tidak ada data</td>
                    </tr>
                @endforelse
            @else
                <tr>
                    @foreach($section['headers'] as $header)
                        <th style="background-color: #f2f2f2; font-weight: bold; border: 1px solid #000000;">{{ $header }}</th>
                    @endforeach
                </tr>
                @forelse($section['rows'] as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td style="border: 1px solid #000000;">{{ $cell }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($section['headers']) }}" style="text-align: center; border: 1px solid #000000;">Tidak ada data</td>
                    </tr>
                @endforelse
            @endif
            <tr><td></td></tr>
        @endforeach

        <tr><td></td></tr>
        <tr>
            <td>Depok, {{ date('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>Mengetahui,</td>
        </tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr>
            <td style="font-weight: bold;">{{ $owner->name ?? 'Owner Mondial Bakery' }}</td>
        </tr>
        <tr>
            <td>Owner</td>
        </tr>
    </table>
</body>
</html>

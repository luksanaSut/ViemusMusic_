<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'Sarabun';
            src: url('{{ resource_path('fonts/sarabun/Sarabun-Regular.ttf') }}') format('truetype');
            font-weight: normal;
        }

        @font-face {
            font-family: 'Sarabun';
            src: url('{{ resource_path('fonts/sarabun/Sarabun-Bold.ttf') }}') format('truetype');
            font-weight: bold;
        }

        body {
            font-family: 'Sarabun', 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #1c1a17;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background: #f4f3f1;
        }

        .section-title {
            font-weight: bold;
            font-size: 14px;
            margin-top: 18px;
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div>
            <div class="title">รายงานนักเรียน</div>
            <div>{{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}</div>
        </div>
        <div style="text-align:right;"><strong>VIEMUS International School of Music</strong></div>
    </div>

    <table>
        <tr>
            <th>สรุป</th>
            <th style="text-align:right;">จำนวน</th>
        </tr>
        <tr>
            <td>จำนวนนักเรียนทั้งหมด</td>
            <td style="text-align:right;">{{ number_format($summary['total']) }}</td>
        </tr>
        <tr>
            <td>นักเรียนใหม่ในช่วงที่เลือก</td>
            <td style="text-align:right;">{{ number_format($summary['new']) }}</td>
        </tr>
    </table>

    <div class="section-title">แยกตามคอร์ส</div>
    <table>
        <tr>
            <th>คอร์สเรียน</th>
            <th style="text-align:right;">จำนวนนักเรียน</th>
        </tr>
        @foreach ($byCourse as $row)
            <tr>
                <td>{{ $row->label }}</td>
                <td style="text-align:right;">{{ number_format($row->total) }}</td>
            </tr>
        @endforeach
    </table>

    <div class="section-title">แยกตามเครื่องดนตรี</div>
    <table>
        <tr>
            <th>เครื่องดนตรี</th>
            <th style="text-align:right;">จำนวนนักเรียน</th>
        </tr>
        @foreach ($byInstrument as $row)
            <tr>
                <td>{{ $row->label }}</td>
                <td style="text-align:right;">{{ number_format($row->total) }}</td>
            </tr>
        @endforeach
    </table>

    <div class="section-title">แยกตามสาขา</div>
    <table>
        <tr>
            <th>สาขา</th>
            <th style="text-align:right;">จำนวนนักเรียน</th>
        </tr>
        @foreach ($byBranch as $row)
            <tr>
                <td>{{ $row['label'] }}</td>
                <td style="text-align:right;">{{ number_format($row['total']) }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>

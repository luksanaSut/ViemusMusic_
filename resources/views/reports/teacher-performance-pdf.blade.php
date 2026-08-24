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
            font-size: 12px;
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
    </style>
</head>

<body>
    <div class="header">
        <div>
            <div class="title">รายงาน Performance อาจารย์</div>
            <div>
                {{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}
                @if ($branch)
                    | สาขา {{ $branch }}
                @endif
            </div>
        </div>
        <div style="text-align:right;"><strong>VIEMUS International School of Music</strong></div>
    </div>

    <table>
        <tr>
            <th>อาจารย์</th>
            <th>สาขา</th>
            <th style="text-align:right;">ชั่วโมงสอน</th>
            <th style="text-align:right;">จำนวนคลาส</th>
            <th style="text-align:right;">จำนวนนักเรียน</th>
            <th style="text-align:right;">จำนวนการลา</th>
        </tr>
        @foreach ($rows as $row)
            <tr>
                <td>{{ $row['teacher']->full_name }} ({{ $row['teacher']->teacher_code }})</td>
                <td>{{ $row['teacher']->branch ?: '-' }}</td>
                <td style="text-align:right;">{{ number_format($row['hours'], 1) }}</td>
                <td style="text-align:right;">{{ number_format($row['class_count']) }}</td>
                <td style="text-align:right;">{{ number_format($row['student_count']) }}</td>
                <td style="text-align:right;">{{ number_format($row['leave_count']) }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>

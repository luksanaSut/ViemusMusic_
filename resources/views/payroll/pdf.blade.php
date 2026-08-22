<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1c1a17;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
            font-size: 11px;
        }

        th {
            background: #f4f3f1;
        }

        .totals {
            margin-top: 10px;
            width: 280px;
            margin-left: auto;
        }

        .totals td {
            border: none;
            padding: 3px 8px;
        }

        .totals .grand {
            font-weight: bold;
            font-size: 13px;
            border-top: 2px solid #1c1a17;
        }
    </style>
</head>

<body>
    <div class="header">
        <div>
            <div class="title">สลิปเงินเดือน</div>
            <div>{{ $payrollRun->teacher->full_name }} ({{ $payrollRun->teacher->teacher_code }})</div>
            <div>รอบ: {{ $payrollRun->periodLabel() }}</div>
        </div>
        <div style="text-align:right;"><strong>VIEMUS International School of Music</strong></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>วันที่สอน</th>
                <th>นักเรียน</th>
                <th>ชั่วโมง</th>
                <th>เรทที่ใช้</th>
                <th style="text-align:right;">รายได้</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payrollRun->items as $item)
                <tr>
                    <td>{{ optional($item->teachingSession->session_date)->format('d/m/Y') }}</td>
                    <td>{{ $item->teachingSession->student_name }}</td>
                    <td>{{ $item->teachingSession->hours }}</td>
                    <td>฿{{ number_format($item->teachingSession->rate_applied, 2) }}</td>
                    <td style="text-align:right;">฿{{ number_format($item->income_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>ค่าสอนรวม</td>
            <td style="text-align:right;">{{ number_format($payrollRun->teaching_income_total, 2) }}</td>
        </tr>
        <tr>
            <td>ค่าเดินทาง</td>
            <td style="text-align:right;">{{ number_format($payrollRun->transport_fee_total, 2) }}</td>
        </tr>
        @if ($payrollRun->adjustment_amount != 0)
            <tr>
                <td>ปรับปรุงยอด ({{ $payrollRun->adjustment_reason }})</td>
                <td style="text-align:right;">{{ number_format($payrollRun->adjustment_amount, 2) }}</td>
            </tr>
        @endif
        <tr class="grand">
            <td>ยอดรวมสุทธิ</td>
            <td style="text-align:right;">{{ number_format($payrollRun->total_amount, 2) }} บาท</td>
        </tr>
    </table>
</body>

</html>

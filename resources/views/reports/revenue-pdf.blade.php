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

        .grand td {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <div>
            <div class="title">รายงานรายได้</div>
            <div>{{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}</div>
        </div>
        <div style="text-align:right;"><strong>VIEMUS International School of Music</strong></div>
    </div>

    <table>
        <tr>
            <th>สรุปรายได้</th>
            <th style="text-align:right;">จำนวนเงิน</th>
        </tr>
        <tr>
            <td>รายได้คอร์สเรียน</td>
            <td style="text-align:right;">฿{{ number_format($courseIncome, 2) }}</td>
        </tr>
        <tr>
            <td>รายได้ขายสินค้า</td>
            <td style="text-align:right;">฿{{ number_format($productIncome, 2) }}</td>
        </tr>
        <tr class="grand">
            <td>รวมรายได้</td>
            <td style="text-align:right;">฿{{ number_format($courseIncome + $productIncome, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">แยกตามช่องทางชำระเงิน</div>
    <table>
        <tr>
            <th>ช่องทาง</th>
            <th style="text-align:right;">จำนวนเงิน</th>
        </tr>
        @php
            $methodLabels = [
                'cash' => 'เงินสด',
                'transfer' => 'โอนเงิน',
                'credit_card' => 'บัตรเครดิต',
                'promptpay' => 'PromptPay/QR',
                'other' => 'อื่นๆ',
            ];
        @endphp
        @foreach ($byMethod as $method => $total)
            <tr>
                <td>{{ $methodLabels[$method] ?? $method }}</td>
                <td style="text-align:right;">฿{{ number_format($total, 2) }}</td>
            </tr>
        @endforeach
    </table>

    <div class="section-title">แยกตามสาขา</div>
    <table>
        <tr>
            <th>สาขา</th>
            <th style="text-align:right;">จำนวนเงิน</th>
        </tr>
        @foreach ($byBranch as $branch => $total)
            <tr>
                <td>{{ $branch }}</td>
                <td style="text-align:right;">฿{{ number_format($total, 2) }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>

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
            font-size: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f4f3f1;
        }

        .totals {
            margin-top: 10px;
            width: 300px;
            margin-left: auto;
        }

        .totals td {
            border: none;
            padding: 4px 8px;
        }

        .totals .grand {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #1c1a17;
        }

        .footer {
            margin-top: 40px;
            font-size: 10px;
            color: #6b655e;
        }
    </style>
</head>

<body>
    <div class="header">
        <div>
            <div class="title">{{ $saleOrder->taxInvoice->invoiceTypeLabel() }}</div>
            <div>เลขที่: {{ $saleOrder->taxInvoice->invoice_no }}</div>
            <div>วันที่ออกเอกสาร: {{ $saleOrder->taxInvoice->issued_date->format('d/m/Y') }}</div>
        </div>
        <div style="text-align:right;">
            <strong>VIEMUS International School of Music</strong><br>
            เลขคำสั่งซื้อ: {{ $saleOrder->order_no }}
        </div>
    </div>

    <table>
        <tr>
            <th style="width:150px;">ผู้ซื้อ / บริษัท</th>
            <td>{{ $saleOrder->taxInvoice->buyer_name }}</td>
        </tr>
        @if ($saleOrder->taxInvoice->is_company)
            <tr>
                <th>เลขผู้เสียภาษี</th>
                <td>{{ $saleOrder->taxInvoice->buyer_tax_id }}</td>
            </tr>
        @endif
        <tr>
            <th>ที่อยู่</th>
            <td>{{ $saleOrder->taxInvoice->buyer_address ?: '-' }}</td>
        </tr>
        <tr>
            <th>เบอร์โทร</th>
            <td>{{ $saleOrder->taxInvoice->buyer_phone ?: '-' }}</td>
        </tr>
        <tr>
            <th>นักเรียน</th>
            <td>{{ $saleOrder->student->full_name }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>รายการ</th>
                <th>รูปแบบ</th>
                <th style="text-align:right;">จำนวนเงิน</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $saleOrder->course->name }} ({{ $saleOrder->course->course_code }})</td>
                <td>{{ optional($saleOrder->teacher)->full_name ?? 'ให้ทางโรงเรียนจัดให้' }}</td>
                <td style="text-align:right;">{{ number_format($saleOrder->total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>ราคาก่อน VAT</td>
            <td style="text-align:right;">{{ number_format($saleOrder->base_price, 2) }}</td>
        </tr>
        <tr>
            <td>VAT {{ $saleOrder->vat_rate }}%</td>
            <td style="text-align:right;">{{ number_format($saleOrder->vat_amount, 2) }}</td>
        </tr>
        @if ($saleOrder->discount_amount > 0)
            <tr>
                <td>ส่วนลดคูปอง</td>
                <td style="text-align:right;">-{{ number_format($saleOrder->discount_amount, 2) }}</td>
            </tr>
        @endif
        @if ($saleOrder->points_discount_amount > 0)
            <tr>
                <td>แลกแต้ม</td>
                <td style="text-align:right;">-{{ number_format($saleOrder->points_discount_amount, 2) }}</td>
            </tr>
        @endif
        @if ($saleOrder->credit_used > 0)
            <tr>
                <td>ใช้เครดิต</td>
                <td style="text-align:right;">-{{ number_format($saleOrder->credit_used, 2) }}</td>
            </tr>
        @endif
        <tr class="grand">
            <td>ยอดชำระสุทธิ</td>
            <td style="text-align:right;">{{ number_format($saleOrder->taxInvoice->total_amount, 2) }} บาท</td>
        </tr>
    </table>

    <div class="footer">
        เอกสารนี้ออกโดยระบบอัตโนมัติ · ช่องทางชำระเงิน: {{ $saleOrder->paymentMethodLabel() }}
        @if ($saleOrder->payment_reference)
            · เลขอ้างอิง: {{ $saleOrder->payment_reference }}
        @endif
    </div>
</body>

</html>

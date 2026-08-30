@php
    $methodLabels = [
        'cash' => 'เงินสด',
        'transfer' => 'โอนเงิน',
        'credit_card' => 'บัตรเครดิต',
        'promptpay' => 'PromptPay/QR',
        'other' => 'อื่นๆ',
    ];
@endphp
<table>
    <tr>
        <th colspan="2">รายงานรายได้</th>
    </tr>
    <tr>
        <td>ช่วงเวลา</td>
        <td>{{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}</td>
    </tr>
    <tr><td></td><td></td></tr>

    <tr>
        <th>สรุปรายได้</th>
        <th>จำนวนเงิน</th>
    </tr>
    <tr>
        <td>รายได้คอร์สเรียน</td>
        <td>{{ $courseIncome }}</td>
    </tr>
    <tr>
        <td>รายได้ค่าทดลองเรียน</td>
        <td>{{ $trialIncome }}</td>
    </tr>
    <tr>
        <td>รายได้ขายสินค้า</td>
        <td>{{ $productIncome }}</td>
    </tr>
    <tr>
        <td>รวมรายได้</td>
        <td>{{ $courseIncome + $trialIncome + $productIncome }}</td>
    </tr>
    <tr><td></td><td></td></tr>

    <tr>
        <th>แยกตามช่องทางชำระเงิน</th>
        <th>จำนวนเงิน</th>
    </tr>
    @foreach ($byMethod as $method => $total)
        <tr>
            <td>{{ $methodLabels[$method] ?? $method }}</td>
            <td>{{ $total }}</td>
        </tr>
    @endforeach
    <tr><td></td><td></td></tr>

    <tr>
        <th>แยกตามสาขา</th>
        <th>จำนวนเงิน</th>
    </tr>
    @foreach ($byBranch as $branch => $total)
        <tr>
            <td>{{ $branch }}</td>
            <td>{{ $total }}</td>
        </tr>
    @endforeach
</table>

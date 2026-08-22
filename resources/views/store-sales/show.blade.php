@extends('layouts.app')
@section('title', $storeSale->sale_no)

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h4>{{ $storeSale->sale_no }}</h4>
            <span class="badge {{ $storeSale->statusBadgeClass() }}">{{ $storeSale->statusLabel() }}</span>
        </div>
        <a href="{{ route('store-sales.index') }}" class="btn btn-outline-secondary btn-sm">กลับ</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <p class="mb-1"><strong>ลูกค้า:</strong>
                {{ $storeSale->student->full_name ?? ($storeSale->buyer_name ?? 'ลูกค้าทั่วไป') }}</p>
            <p class="mb-1"><strong>ช่องทางชำระเงิน:</strong> {{ $storeSale->paymentMethodLabel() }}</p>
            <p class="mb-0"><strong>ขายโดย:</strong> {{ $storeSale->sold_by }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>สินค้า</th>
                    <th>จำนวน</th>
                    <th>ราคาต่อชิ้น</th>
                    <th class="text-end">รวม</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($storeSale->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>฿{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-end">฿{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="3">ยอดรวม</td>
                    <td class="text-end">฿{{ number_format($storeSale->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if ($storeSale->status === 'completed')
        <form action="{{ route('store-sales.cancel', $storeSale) }}" method="POST"
            onsubmit="return confirm('ยกเลิกการขายนี้? ระบบจะคืนสต็อกสินค้าให้อัตโนมัติ')">
            @csrf @method('PATCH')
            <button class="btn btn-outline-danger">ยกเลิกการขาย (คืนสต็อก)</button>
        </form>
    @endif
@endsection

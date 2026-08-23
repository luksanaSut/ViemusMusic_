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
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-2"><i class="bi bi-truck"></i> การรับสินค้า — {{ $storeSale->deliveryMethodLabel() }}
                </h6>
                @if ($storeSale->delivery_method === 'delivery')
                    <p class="mb-1 small"><strong>ผู้รับ:</strong> {{ $storeSale->delivery_recipient_name }}
                        ({{ $storeSale->delivery_phone }})</p>
                    <p class="mb-2 small"><strong>ที่อยู่:</strong> {{ $storeSale->delivery_address }}</p>
                @endif
                <form action="{{ route('store-sales.delivery-status', $storeSale) }}" method="POST" class="row g-2">
                    @csrf @method('PATCH')
                    <div class="col-md-5">
                        <select name="delivery_status" class="form-select form-select-sm">
                            @if ($storeSale->delivery_method === 'delivery')
                                <option value="preparing" @selected($storeSale->delivery_status == 'preparing')>กำลังเตรียมสินค้า</option>
                                <option value="shipped" @selected($storeSale->delivery_status == 'shipped')>จัดส่งแล้ว</option>
                                <option value="delivered" @selected($storeSale->delivery_status == 'delivered')>จัดส่งถึงแล้ว</option>
                            @else
                                <option value="ready_for_pickup" @selected($storeSale->delivery_status == 'ready_for_pickup')>พร้อมให้รับที่ร้าน</option>
                                <option value="picked_up" @selected($storeSale->delivery_status == 'picked_up')>รับสินค้าแล้ว</option>
                            @endif
                        </select>
                    </div>
                    @if ($storeSale->delivery_method === 'delivery')
                        <div class="col-md-5"><input type="text" name="delivery_tracking_no"
                                class="form-control form-control-sm" placeholder="เลขพัสดุ (ถ้ามี)"
                                value="{{ $storeSale->delivery_tracking_no }}"></div>
                    @endif
                    <div class="col-md-2 d-grid"><button class="btn btn-sm btn-accent">อัปเดต</button></div>
                </form>
            </div>
        </div>
    @endif

    @if ($storeSale->status === 'completed')
        <form action="{{ route('store-sales.cancel', $storeSale) }}" method="POST"
            onsubmit="return confirm('ยกเลิกการขายนี้? ระบบจะคืนสต็อกสินค้าให้อัตโนมัติ')">
            @csrf @method('PATCH')
            <button class="btn btn-outline-danger">ยกเลิกการขาย (คืนสต็อก)</button>
        </form>
    @endif
@endsection

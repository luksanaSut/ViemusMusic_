@extends('layouts.app')
@section('title', $storeSale->sale_no)

@section('content')
    <style>
        .sale-info-row {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
        }

        .sale-info-row+.sale-info-row {
            margin-top: 1rem;
        }

        .sale-info-row .icon-chip {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent-dark, #13233a);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .sale-info-row .label {
            font-size: .74rem;
            color: var(--muted, #6b655e);
            font-weight: 600;
            margin-bottom: .1rem;
        }

        .sale-info-row .value {
            font-weight: 600;
            font-size: .92rem;
            word-break: break-word;
        }

        .item-thumb {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid var(--border, #e4e1dc);
            flex-shrink: 0;
            background: var(--surface, #f4f3f1);
        }

        .item-thumb-placeholder {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--surface, #f4f3f1);
            color: var(--muted, #6b655e);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
        }

        .sale-detail-table thead th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--muted, #6b655e);
            border-top: 0;
            font-weight: 700;
        }

        .sale-detail-table td {
            vertical-align: middle;
        }

        .sale-total-row td {
            font-family: 'Prompt', sans-serif;
            font-size: 1.05rem;
        }

        .address-box {
            background: var(--surface, #f4f3f1);
            border-radius: 10px;
            padding: .75rem .9rem;
            font-size: .85rem;
        }

        .form-label-sm {
            font-size: .74rem;
            font-weight: 600;
            color: var(--muted, #6b655e);
            margin-bottom: .25rem;
        }

        .cancel-card {
            border: 1px solid #f1d9d4;
            background: #fdf4f2;
            border-radius: 14px;
        }
    </style>

    <div class="breadcrumb-sm">
        <a href="{{ route('store-sales.index') }}" class="text-decoration-none">ประวัติการขายสินค้า</a>
        <i class="bi bi-chevron-right small"></i> {{ $storeSale->sale_no }}
    </div>
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h1 class="page-title mb-1">{{ $storeSale->sale_no }}</h1>
            <div class="d-flex align-items-center gap-2">
                <span class="badge {{ $storeSale->statusBadgeClass() }} rounded-pill"><i class="bi bi-circle-fill"
                        style="font-size:.5rem;"></i> {{ $storeSale->statusLabel() }}</span>
                <span class="page-sub mb-0">สั่งซื้อเมื่อ {{ $storeSale->created_at->translatedFormat('d M Y, H:i น.') }}</span>
            </div>
        </div>
        <a href="{{ route('store-sales.index') }}" class="btn btn-outline-secondary btn-sm"><i
                class="bi bi-arrow-left"></i> กลับ</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="table-responsive">
                    <table class="table sale-detail-table align-middle mb-0">
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
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($item->product?->image_path)
                                                <img src="{{ asset('storage/' . $item->product->image_path) }}"
                                                    class="item-thumb" alt="">
                                            @else
                                                <div class="item-thumb-placeholder"><i class="bi bi-box-seam"></i>
                                                </div>
                                            @endif
                                            <span>{{ $item->product_name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>฿{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-end">฿{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">ยอดรวม</td>
                                <td class="text-end">฿{{ number_format($storeSale->total_amount, 2) }}</td>
                            </tr>
                            @if ($storeSale->auto_discount_amount > 0)
                                <tr class="text-success">
                                    <td colspan="3">ส่วนลดโปรโมชัน{{ $storeSale->autoPromotion?->name ? " ({$storeSale->autoPromotion->name})" : '' }}</td>
                                    <td class="text-end">-฿{{ number_format($storeSale->auto_discount_amount, 2) }}</td>
                                </tr>
                            @endif
                            @if ($storeSale->discount_amount > 0)
                                <tr class="text-success">
                                    <td colspan="3">ส่วนลดคูปอง{{ $storeSale->promotion_code ? " ({$storeSale->promotion_code})" : '' }}</td>
                                    <td class="text-end">-฿{{ number_format($storeSale->discount_amount, 2) }}</td>
                                </tr>
                            @endif
                            <tr class="sale-total-row fw-bold">
                                <td colspan="3">ยอดสุทธิ</td>
                                <td class="text-end">฿{{ number_format($storeSale->net_payable ?? $storeSale->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if ($storeSale->status === 'completed')
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0"><i class="bi bi-truck"></i>
                                การรับสินค้า — {{ $storeSale->deliveryMethodLabel() }}</h6>
                            <span
                                class="badge {{ $storeSale->deliveryStatusBadgeClass() }} rounded-pill">{{ $storeSale->deliveryStatusLabel() }}</span>
                        </div>

                        @if ($storeSale->delivery_method === 'delivery')
                            <div class="address-box mb-3">
                                <div class="mb-1"><strong>ผู้รับ:</strong> {{ $storeSale->delivery_recipient_name }}
                                    ({{ $storeSale->delivery_phone }})</div>
                                <div><strong>ที่อยู่:</strong> {{ $storeSale->delivery_address }}</div>
                            </div>
                        @endif

                        <form action="{{ route('store-sales.delivery-status', $storeSale) }}" method="POST"
                            class="row g-2 align-items-end">
                            @csrf @method('PATCH')
                            <div class="col-md-5">
                                <div class="form-label-sm">สถานะการจัดส่ง</div>
                                <select name="delivery_status" class="form-select form-select-sm">
                                    @if ($storeSale->delivery_method === 'delivery')
                                        <option value="preparing" @selected($storeSale->delivery_status == 'preparing')>กำลังเตรียมสินค้า
                                        </option>
                                        <option value="shipped"
                                            @selected(in_array($storeSale->delivery_status, ['shipped', 'delivered']))>จัดส่งแล้ว
                                        </option>
                                    @else
                                        <option value="ready_for_pickup" @selected($storeSale->delivery_status == 'ready_for_pickup')>พร้อมให้รับที่ร้าน
                                        </option>
                                        <option value="picked_up" @selected($storeSale->delivery_status == 'picked_up')>รับสินค้าแล้ว
                                        </option>
                                    @endif
                                </select>
                            </div>
                            @if ($storeSale->delivery_method === 'delivery')
                                <div class="col-md-5">
                                    <div class="form-label-sm">เลขพัสดุ (ถ้ามี)</div>
                                    <input type="text" name="delivery_tracking_no" class="form-control form-control-sm"
                                        placeholder="เช่น TH1234567890" value="{{ $storeSale->delivery_tracking_no }}">
                                </div>
                            @endif
                            <div class="{{ $storeSale->delivery_method === 'delivery' ? 'col-md-2' : 'col-md-7' }} d-grid">
                                <button class="btn btn-sm btn-accent"><i class="bi bi-check2"></i> อัปเดต</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">ข้อมูลคำสั่งซื้อ</h6>
                    <div class="sale-info-row">
                        <div class="icon-chip"><i class="bi bi-person"></i></div>
                        <div>
                            <div class="label">ลูกค้า</div>
                            <div class="value">
                                {{ $storeSale->student->full_name ?? ($storeSale->buyer_name ?? 'ลูกค้าทั่วไป') }}</div>
                        </div>
                    </div>
                    <div class="sale-info-row">
                        <div class="icon-chip"><i class="bi bi-credit-card"></i></div>
                        <div>
                            <div class="label">ช่องทางชำระเงิน</div>
                            <div class="value">{{ $storeSale->paymentMethodLabel() }}</div>
                        </div>
                    </div>
                    <div class="sale-info-row">
                        <div class="icon-chip"><i class="bi bi-person-badge"></i></div>
                        <div>
                            <div class="label">ขายโดย</div>
                            <div class="value">{{ $storeSale->sold_by ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($storeSale->status === 'completed' && !in_array($storeSale->delivery_status, ['shipped', 'delivered', 'picked_up']))
                <div class="card cancel-card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1 text-danger"><i class="bi bi-exclamation-triangle"></i> ยกเลิกการขาย
                        </h6>
                        <p class="small text-muted mb-3">ระบบจะคืนสต็อกสินค้ากลับเข้าคลังให้อัตโนมัติ</p>
                        <form action="{{ route('store-sales.cancel', $storeSale) }}" method="POST"
                            onsubmit="return confirm('ยกเลิกการขายนี้? ระบบจะคืนสต็อกสินค้าให้อัตโนมัติ')">
                            @csrf @method('PATCH')
                            <button class="btn btn-outline-danger btn-sm w-100">ยกเลิกการขาย (คืนสต็อก)</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

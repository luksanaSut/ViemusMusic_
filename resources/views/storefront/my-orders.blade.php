@extends('layouts.app')
@section('title', 'คำสั่งซื้อของฉัน')

@section('content')
    <style>
        .stat-tile {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.2rem 1.4rem;
            height: 100%;
        }

        .stat-tile .head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: .6rem;
        }

        .stat-tile .label {
            font-size: .85rem;
            color: var(--muted, #6b655e);
            font-weight: 600;
        }

        .stat-tile .icon-box {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .stat-tile .value {
            font-size: 1.9rem;
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            line-height: 1;
        }

        .stat-tile .sub {
            font-size: .78rem;
            margin-top: .4rem;
            color: var(--muted, #6b655e);
        }

        .filter-pills {
            display: flex;
            gap: .5rem;
            margin-bottom: 1.2rem;
            flex-wrap: wrap;
        }

        .filter-pill {
            border-radius: 10px;
            padding: .5rem 1.1rem;
            font-size: .85rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--muted, #6b655e);
            background: #f4f3f1;
        }

        .filter-pill.active {
            background: var(--ink, #1c1a17);
            color: #fff;
        }

        .order-table thead th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--muted, #6b655e);
            border-top: 0;
            font-weight: 700;
        }

        .order-table td {
            vertical-align: middle;
        }

        .order-no {
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
        }

        .order-items-preview {
            font-size: .8rem;
            color: var(--muted, #6b655e);
            margin-top: .1rem;
        }

        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
        }

        .empty-state .icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent, #1f3350);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1rem;
        }
    </style>

    <div class="breadcrumb-sm">Music Store <i class="bi bi-chevron-right small"></i> คำสั่งซื้อของฉัน</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title mb-0">คำสั่งซื้อของฉัน</h1>
            <div class="page-sub">ประวัติและสถานะคำสั่งซื้อของฉัน</div>
        </div>
        <a href="{{ route('store.index') }}" class="btn btn-accent"><i class="bi bi-cart"></i> เลือกซื้อสินค้า</a>
    </div>

    @php $total = $counts['pending_payment'] + $counts['completed'] + $counts['cancelled']; @endphp

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-tile">
                <div class="head">
                    <span class="label">คำสั่งซื้อทั้งหมด</span>
                    <div class="icon-box" style="background:var(--accent-soft,#e7ebf1);color:var(--accent-dark,#13233a);"><i
                            class="bi bi-bag"></i></div>
                </div>
                <div class="value">{{ $total }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-tile">
                <div class="head">
                    <span class="label">รอชำระเงิน</span>
                    <div class="icon-box" style="background:var(--amber-soft,#f3ece2);color:var(--amber,#8a5a2b);"><i
                            class="bi bi-credit-card"></i></div>
                </div>
                <div class="value">{{ $counts['pending_payment'] }}</div>
                <div class="sub {{ $counts['pending_payment'] > 0 ? 'text-warning' : '' }}">
                    {{ $counts['pending_payment'] > 0 ? 'ต้องดำเนินการ' : 'ไม่มี' }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-tile">
                <div class="head">
                    <span class="label">สำเร็จ</span>
                    <div class="icon-box" style="background:var(--success-soft,#e7f2ec);color:var(--success,#2f6f4e);"><i
                            class="bi bi-check-lg"></i></div>
                </div>
                <div class="value">{{ $counts['completed'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-tile">
                <div class="head">
                    <span class="label">ยกเลิก</span>
                    <div class="icon-box" style="background:#f1efec;color:var(--muted,#6b655e);"><i class="bi bi-x-lg"></i>
                    </div>
                </div>
                <div class="value">{{ $counts['cancelled'] }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body pb-0">
            <div class="filter-pills">
                <a href="{{ route('store.my-orders') }}" class="filter-pill {{ !$status ? 'active' : '' }}">ทั้งหมด <span
                        class="opacity-75">{{ $total }}</span></a>
                <a href="{{ route('store.my-orders', ['status' => 'pending_payment']) }}"
                    class="filter-pill {{ $status === 'pending_payment' ? 'active' : '' }}">รอชำระเงิน <span
                        class="opacity-75">{{ $counts['pending_payment'] }}</span></a>
                <a href="{{ route('store.my-orders', ['status' => 'completed']) }}"
                    class="filter-pill {{ $status === 'completed' ? 'active' : '' }}">สำเร็จ <span
                        class="opacity-75">{{ $counts['completed'] }}</span></a>
                <a href="{{ route('store.my-orders', ['status' => 'cancelled']) }}"
                    class="filter-pill {{ $status === 'cancelled' ? 'active' : '' }}">ยกเลิก <span
                        class="opacity-75">{{ $counts['cancelled'] }}</span></a>
            </div>
        </div>

        @if ($orders->isEmpty())
            <div class="empty-state">
                <div class="icon-wrap"><i class="bi bi-bag-x"></i></div>
                <h5 class="fw-bold mb-1">{{ $status ? 'ไม่พบคำสั่งซื้อในหมวดนี้' : 'ยังไม่มีประวัติการสั่งซื้อ' }}</h5>
                <p class="text-muted small mb-3">เลือกซื้ออุปกรณ์ดนตรีและของใช้ต่างๆ ได้ที่ร้านค้า Music Store</p>
                <a href="{{ route('store.index') }}" class="btn btn-accent btn-sm"><i class="bi bi-shop"></i>
                    ไปที่ร้านค้า</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table order-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>เลขที่</th>
                            <th>วันที่</th>
                            <th>รายการ</th>
                            <th class="text-end">ยอดสุทธิ</th>
                            <th>สถานะ</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $o)
                            <tr>
                                <td class="order-no">{{ $o->sale_no }}</td>
                                <td class="small">{{ $o->created_at->translatedFormat('d M Y') }}</td>
                                <td>
                                    {{ $o->items->first()?->product_name }}
                                    @if ($o->items->count() > 1)
                                        และอีก {{ $o->items->count() - 1 }} รายการ
                                    @endif
                                    <div class="order-items-preview">
                                        {{ $o->items->count() }} ชิ้น
                                        @if ($o->status === 'pending_payment')
                                            · ยังไม่ชำระเงิน
                                        @else
                                            · {{ $o->paymentMethodLabel() }}
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end fw-bold">฿{{ number_format($o->total_amount, 0) }}</td>
                                <td>
                                    <span class="badge {{ $o->statusBadgeClass() }} rounded-pill"><i
                                            class="bi bi-circle-fill" style="font-size:.5rem;"></i>
                                        {{ $o->statusLabel() }}</span>
                                    @if ($o->status === 'completed' && $o->delivery_status !== 'not_applicable')
                                        <div class="order-items-preview mt-1">{{ $o->deliveryStatusLabel() }}</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('store.show', $o) }}" class="btn btn-sm btn-outline-secondary"><i
                                            class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        <div class="card-body">{{ $orders->links() }}</div>
    </div>
@endsection

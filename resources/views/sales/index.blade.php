@extends('layouts.app')
@section('title', 'ระบบขายคอร์สเรียน')

@section('content')
    <div class="breadcrumb-sm">งานขาย <i class="bi bi-chevron-right small"></i> สมัครเรียนคอร์ส</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title">ระบบขายคอร์สเรียน (Course Sales)</h1>
            <div class="page-sub">คำสั่งสมัครเรียนทั้งหมด {{ $orders->total() }} รายการ</div>
        </div>
        <a href="{{ route('sales.create') }}" class="btn btn-accent"><i class="bi bi-cart-plus"></i> สมัครเรียนคอร์สใหม่</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-5"><input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาเลขคำสั่ง / ชื่อนักเรียน / รหัสนักเรียน"></div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="pending_payment" @selected(request('status') == 'pending_payment')>รอตรวจสอบการชำระเงิน</option>
                        <option value="paid" @selected(request('status') == 'paid')>ชำระเงินแล้ว</option>
                        <option value="cancelled" @selected(request('status') == 'cancelled')>ยกเลิก</option>
                    </select>
                </div>
                <div class="col-md-1 d-grid"><button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>เลขคำสั่ง</th>
                        <th>นักเรียน</th>
                        <th>คอร์ส</th>
                        <th>ยอดชำระ</th>
                        <th>วันที่</th>
                        <th>สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_no }}</td>
                            <td>{{ $order->student->full_name ?? '-' }}</td>
                            <td>{{ $order->course->name ?? '-' }}</td>
                            <td class="fw-semibold">{{ number_format($order->total_amount, 2) }}</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td><span class="badge {{ $order->statusBadgeClass() }}">{{ $order->statusLabel() }}</span></td>
                            <td class="text-end"><a href="{{ route('sales.show', $order) }}"
                                    class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i> ดู</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">ยังไม่มีคำสั่งสมัครเรียน</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $orders->links() }}</div>
    </div>
@endsection

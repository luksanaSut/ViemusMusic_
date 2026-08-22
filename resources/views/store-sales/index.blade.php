@extends('layouts.app')
@section('title', 'ประวัติการขายสินค้า')

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h1 class="page-title"><i class="bi bi-receipt"></i> ประวัติการขายสินค้า</h1>
        <a href="{{ route('store-sales.create') }}" class="btn btn-accent"><i class="bi bi-cart-plus"></i> ขายสินค้าใหม่
            (หน้าร้าน)</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-4"><input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาเลขที่/ชื่อลูกค้า"></div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="pending_payment" @selected(request('status') == 'pending_payment')>รอชำระเงิน (สั่งออนไลน์)</option>
                        <option value="completed" @selected(request('status') == 'completed')>สำเร็จ</option>
                        <option value="cancelled" @selected(request('status') == 'cancelled')>ยกเลิก</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid"><button class="btn btn-outline-secondary">ค้นหา</button></div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>เลขที่</th>
                        <th>ลูกค้า</th>
                        <th>สั่งโดย</th>
                        <th>จำนวนรายการ</th>
                        <th>ยอดรวม</th>
                        <th>ช่องทาง</th>
                        <th>สถานะ</th>
                        <th class="text-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $s)
                        <tr>
                            <td>{{ $s->sale_no }}</td>
                            <td>{{ $s->student->full_name ?? ($s->buyer_name ?? 'ลูกค้าทั่วไป') }}</td>
                            <td class="small text-muted">
                                {{ $s->ordered_by_user_id ? $s->orderedBy?->displayName() . ' (สั่งเอง)' : $s->sold_by }}
                            </td>
                            <td>{{ $s->items->count() }}</td>
                            <td class="fw-bold">฿{{ number_format($s->total_amount, 2) }}</td>
                            <td>{{ $s->status === 'pending_payment' ? '-' : $s->paymentMethodLabel() }}</td>
                            <td><span class="badge {{ $s->statusBadgeClass() }}">{{ $s->statusLabel() }}</span></td>
                            <td class="text-end"><a href="{{ route('store-sales.show', $s) }}"
                                    class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">ยังไม่มีประวัติการขาย</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $sales->links() }}</div>
    </div>
@endsection

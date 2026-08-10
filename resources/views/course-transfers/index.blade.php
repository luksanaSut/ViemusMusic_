@extends('layouts.app')
@section('title', 'ประวัติการเปลี่ยนคอร์สเรียน')

@section('content')
    <div class="breadcrumb-sm">งานขาย <i class="bi bi-chevron-right small"></i> ประวัติการเปลี่ยนคอร์ส</div>
    <h1 class="page-title mb-3">ประวัติการเปลี่ยนคอร์สเรียน</h1>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-5"><input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาเลขรายการ / ชื่อนักเรียน"></div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="pending_payment" @selected(request('status') == 'pending_payment')>รอชำระเงินเพิ่ม</option>
                        <option value="completed" @selected(request('status') == 'completed')>เปลี่ยนคอร์สสำเร็จ</option>
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
                        <th>เลขรายการ</th>
                        <th>นักเรียน</th>
                        <th>คอร์สเดิม → คอร์สใหม่</th>
                        <th>ส่วนต่าง</th>
                        <th>วันที่</th>
                        <th>สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $t)
                        <tr>
                            <td>{{ $t->transfer_no }}</td>
                            <td>{{ $t->student->full_name ?? '-' }}</td>
                            <td class="small">{{ $t->oldCourse->name ?? '-' }} <i class="bi bi-arrow-right"></i>
                                {{ $t->newCourse->name ?? '-' }}</td>
                            <td
                                class="{{ $t->price_difference > 0 ? 'text-danger' : ($t->price_difference < 0 ? 'text-success' : '') }} fw-semibold">
                                {{ $t->priceDifferenceLabel() }}</td>
                            <td>{{ $t->created_at->format('d/m/Y') }}</td>
                            <td><span class="badge {{ $t->statusBadgeClass() }}">{{ $t->statusLabel() }}</span></td>
                            <td class="text-end"><a href="{{ route('course-transfers.show', $t) }}"
                                    class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">ยังไม่มีประวัติการเปลี่ยนคอร์ส</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $transfers->links() }}</div>
    </div>
@endsection

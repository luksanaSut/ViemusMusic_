@extends('layouts.app')
@section('title', 'จัดการนักเรียน')

@section('content')
    <div class="breadcrumb-sm">บุคคล <i class="bi bi-chevron-right small"></i> นักเรียน</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title">จัดการนักเรียน</h1>
            <div class="page-sub">นักเรียนทั้งหมด {{ $students->total() }} คน</div>
        </div>
        <a href="{{ route('students.create') }}" class="btn btn-accent"><i class="bi bi-plus-lg"></i> เพิ่มข้อมูลนักเรียน</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาชื่อ / รหัส / เบอร์โทร">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="active" @selected(request('status') == 'active')>กำลังเรียน</option>
                        <option value="paused" @selected(request('status') == 'paused')>พักเรียน</option>
                        <option value="cancelled" @selected(request('status') == 'cancelled')>ยกเลิกเรียน</option>
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
                        <th>รหัส</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>ผู้ปกครอง</th>
                        <th>คอร์สที่กำลังเรียน</th>
                        <th>สถานะ</th>
                        <th>การชำระเงิน</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->student_code }}</td>
                            <td>
                                <a href="{{ route('students.show', $student) }}"
                                    class="fw-semibold text-decoration-none">{{ $student->full_name }}</a>
                                @if ($student->nickname)
                                    <div class="text-muted small">({{ $student->nickname }})</div>
                                @endif
                            </td>
                            <td>{{ $student->guardian_name ?: '-' }}</td>
                            <td>{{ $student->active_enrollments_count }} คอร์ส</td>
                            <td><span class="badge {{ $student->statusBadgeClass() }}">{{ $student->statusLabel() }}</span>
                            </td>
                            <td>
                                @if ($student->hasOverduePayment())
                                    <span class="badge text-bg-danger"><i class="bi bi-exclamation-triangle"></i>
                                        ค้างชำระ</span>
                                @else
                                    <span class="badge text-bg-light border">ปกติ</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('students.show', $student) }}"
                                    class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-primary"><i
                                        class="bi bi-pencil"></i></a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('ยืนยันการลบข้อมูลนักเรียนนี้?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">ไม่พบข้อมูลนักเรียน</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $students->links() }}</div>
    </div>
@endsection

@extends('layouts.app')
@section('title', 'จัดการเรียนชดเชย')

@section('content')
    <div class="breadcrumb-sm">
        งานวิชาการ <i class="bi bi-chevron-right small"></i> เรียนชดเชย</div>
    <h1 class="page-title mb-3"><i class="bi bi-arrow-repeat"></i> จัดการเรียนชดเชย (Makeup Class)</h1>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="pending" @selected(request('status') == 'pending')>รออนุมัติ</option>
                        <option value="approved" @selected(request('status') == 'approved')>อนุมัติแล้ว</option>
                        <option value="rejected" @selected(request('status') == 'rejected')>ปฏิเสธ</option>
                        <option value="completed" @selected(request('status') == 'completed')>เรียนชดเชยเสร็จแล้ว</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="overdue_only" value="1" id="overdueOnly"
                            {{ request('overdue_only') ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="form-check-label small" for="overdueOnly">แสดงเฉพาะที่เกินกำหนด</label>
                    </div>
                </div>
                <div class="col-md-2 d-grid"><button class="btn btn-outline-secondary btn-sm">กรอง</button></div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>นักเรียน</th>
                        <th>คอร์ส</th>
                        <th>วันเรียนชดเชย</th>
                        <th>อาจารย์</th>
                        <th>Admin</th>
                        <th>อาจารย์</th>
                        <th>สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $r)
                        <tr class="{{ $r->is_overdue ? 'table-warning' : '' }}">
                            <td>{{ $r->student->full_name ?? '-' }}</td>
                            <td>{{ $r->enrollment->course->name ?? '-' }}</td>
                            <td>{{ $r->makeup_date->format('d/m/Y') }} {{ $r->start_time }}-{{ $r->end_time }} @if ($r->is_overdue)
                                    <span class="badge text-bg-danger">เกินกำหนด</span>
                                @endif
                            </td>
                            <td>{{ $r->teacher->full_name ?? '-' }}</td>
                            <td><span
                                    class="badge {{ $r->admin_approval_status == 'approved' ? 'text-bg-success' : ($r->admin_approval_status == 'rejected' ? 'text-bg-danger' : 'text-bg-warning') }}">{{ $r->admin_approval_status }}</span>
                            </td>
                            <td><span
                                    class="badge {{ $r->instructor_approval_status == 'approved' ? 'text-bg-success' : ($r->instructor_approval_status == 'rejected' ? 'text-bg-danger' : 'text-bg-warning') }}">{{ $r->instructor_approval_status }}</span>
                            </td>
                            <td><span
                                    class="badge {{ $r->overallStatusBadgeClass() }}">{{ $r->overallStatusLabel() }}</span>
                            </td>
                            <td class="text-end"><a href="{{ route('makeup-requests.show', $r) }}"
                                    class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">ยังไม่มีคำขอเรียนชดเชย</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $requests->links() }}</div>
    </div>
@endsection

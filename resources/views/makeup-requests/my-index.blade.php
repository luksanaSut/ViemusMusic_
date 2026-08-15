@extends('layouts.app')
@section('title', 'คำขอสอนชดเชย')

@section('content')
    <style>
        .table-clean thead th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--muted, #6b655e);
            border-top: 0;
        }
    </style>

    <h1 class="page-title mb-1"><i class="bi bi-arrow-repeat"></i> คำขอสอนชดเชย</h1>
    <div class="page-sub mb-3">รายการคำขอเรียนชดเชยที่มอบหมายให้ {{ $teacher->nickname ?: $teacher->full_name }} เป็นผู้สอน
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm table-clean align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>นักเรียน</th>
                        <th>คอร์ส</th>
                        <th>วันเรียนชดเชย</th>
                        <th>Admin</th>
                        <th>คุณ</th>
                        <th>สถานะรวม</th>
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
                            <td><span
                                    class="badge {{ $r->admin_approval_status == 'approved' ? 'text-bg-success' : ($r->admin_approval_status == 'rejected' ? 'text-bg-danger' : 'text-bg-warning') }}">{{ $r->admin_approval_status }}</span>
                            </td>
                            <td><span
                                    class="badge {{ $r->instructor_approval_status == 'approved' ? 'text-bg-success' : ($r->instructor_approval_status == 'rejected' ? 'text-bg-danger' : 'text-bg-warning') }}">{{ $r->instructor_approval_status }}</span>
                            </td>
                            <td><span
                                    class="badge {{ $r->overallStatusBadgeClass() }}">{{ $r->overallStatusLabel() }}</span>
                            </td>
                            <td class="text-end">
                                @if ($r->instructor_approval_status === 'pending')
                                    <form action="{{ route('makeup-requests.approve-instructor', $r) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success"><i class="bi bi-check-lg"></i>
                                            อนุมัติ</button>
                                    </form>
                                    <form action="{{ route('makeup-requests.reject', $r) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('ปฏิเสธคำขอนี้?')">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                @else
                                    <a href="{{ route('makeup-requests.show', $r) }}"
                                        class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i> ดู</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">ยังไม่มีคำขอสอนชดเชยที่มอบหมายให้คุณ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $requests->links() }}</div>
    </div>
@endsection

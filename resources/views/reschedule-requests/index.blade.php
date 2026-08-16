@extends('layouts.app')
@section('title', 'สลับคลาส (Reschedule)')

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

    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> สลับคลาส</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h1 class="page-title"><i class="bi bi-arrow-left-right"></i> สลับคลาส / ประวัติการเปลี่ยนแปลง</h1>
        <a href="{{ route('reschedule-requests.create') }}" class="btn btn-accent"><i class="bi bi-plus-lg"></i>
            ขอเปลี่ยนแปลง</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="pending" @selected(request('status') == 'pending')>รออนุมัติ</option>
                        <option value="approved" @selected(request('status') == 'approved')>อนุมัติแล้ว</option>
                        <option value="rejected" @selected(request('status') == 'rejected')>ปฏิเสธ</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid"><button class="btn btn-outline-secondary btn-sm">กรอง</button></div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm table-clean align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ประเภท</th>
                        <th>นักเรียน</th>
                        <th>คอร์ส</th>
                        <th>รายละเอียด</th>
                        <th>เหตุผล</th>
                        <th>ผู้ขอ</th>
                        <th>สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $r)
                        @php $schedule = $r->classSchedule; @endphp
                        <tr>
                            <td><span
                                    class="badge {{ $r->type == 'swap' ? 'text-bg-primary' : 'text-bg-light border' }}">{{ $r->typeLabel() }}</span>
                            </td>
                            <td>{{ $schedule->enrollment->student->full_name ?? '-' }}</td>
                            <td>{{ $schedule->enrollment->course->name ?? '-' }}</td>
                            <td class="small">
                                @if ($r->type === 'swap')
                                    แลกกับ {{ $r->swapWithClassSchedule->enrollment->student->full_name ?? '-' }}
                                    ({{ optional($r->swapWithClassSchedule)->schedule_date?->format('d/m/Y') }})
                                @else
                                    @if ($r->new_date)
                                        → {{ $r->new_date->format('d/m/Y') }}
                                        {{ $r->new_start_time }}-{{ $r->new_end_time }}
                                    @endif
                                    @if ($r->newTeacher)
                                        · อ.{{ $r->newTeacher->nickname ?: $r->newTeacher->full_name }}
                                    @endif
                                    @if ($r->newRoom)
                                        · {{ $r->newRoom->name }}
                                    @endif
                                @endif
                            </td>
                            <td class="small">{{ $r->reason ?: '-' }}</td>
                            <td class="small">{{ $r->requested_by }}</td>
                            <td><span class="badge {{ $r->statusBadgeClass() }}">{{ $r->statusLabel() }}</span></td>
                            <td class="text-end">
                                @if ($r->status === 'pending')
                                    <form action="{{ route('reschedule-requests.approve', $r) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success"><i
                                                class="bi bi-check-lg"></i></button>
                                    </form>
                                    <form action="{{ route('reschedule-requests.reject', $r) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('ปฏิเสธคำขอนี้?')">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">ยังไม่มีประวัติการเปลี่ยนแปลงตารางเรียน
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $requests->links() }}</div>
    </div>
@endsection

@extends('layouts.app')
@section('title', 'ประวัติการเข้าเรียน')

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

    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> ประวัติการเข้าเรียน</div>
    <h1 class="page-title mb-3"><i class="bi bi-journal-check"></i> ประวัติการเข้าเรียน (Teaching Log)</h1>

    @if ($pendingSchedules->isNotEmpty())
        <div class="card mb-3" style="border-color:#e6d9c3;">
            <div class="card-body">
                <h6 class="fw-bold mb-2" style="font-family:'Prompt',sans-serif;"><i class="bi bi-hourglass-split"
                        style="color:#8a5a2b;"></i> รอเช็คชื่อ ({{ $pendingSchedules->count() }} คาบ)</h6>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            @foreach ($pendingSchedules as $s)
                                <tr>
                                    <td class="small">{{ $s->schedule_date->format('d/m/Y') }}
                                        {{ $s->start_time }}-{{ $s->end_time }}</td>
                                    <td class="small">{{ $s->enrollment->student->full_name ?? '-' }}</td>
                                    <td class="small text-muted">{{ $s->enrollment->course->name ?? '-' }}</td>
                                    <td class="small text-muted">{{ $s->teacher->full_name ?? '-' }}</td>
                                    <td class="text-end"><a href="{{ route('teaching-logs.show', $s) }}"
                                            class="btn btn-sm btn-accent"><i class="bi bi-clipboard-check"></i> เช็คชื่อ</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3"><input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาชื่อนักเรียน/อาจารย์"></div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="present" @selected(request('status') == 'present')>เข้าเรียน</option>
                        <option value="late" @selected(request('status') == 'late')>เข้าเรียนสาย</option>
                        <option value="absent" @selected(request('status') == 'absent')>ขาดเรียน</option>
                        <option value="excused_leave" @selected(request('status') == 'excused_leave')>ลา</option>
                    </select>
                </div>
                <div class="col-md-2"><input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="form-control"></div>
                <div class="col-md-2"><input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="form-control"></div>
                <div class="col-md-1 d-grid"><button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm table-clean align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>วันที่</th>
                        <th>นักเรียน</th>
                        <th>คอร์ส</th>
                        <th>อาจารย์</th>
                        <th>เช็คชื่อ</th>
                        <th>เวลาสอนจริง</th>
                        <th>ตัดคอร์ส</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->classSchedule->schedule_date->format('d/m/Y') }}
                                {{ $log->classSchedule->start_time }}</td>
                            <td>{{ $log->student->full_name ?? '-' }}</td>
                            <td>{{ $log->enrollment->course->name ?? '-' }}</td>
                            <td>{{ $log->teacher->full_name ?? '-' }}</td>
                            <td><span
                                    class="badge {{ $log->attendanceStatusBadgeClass() }}">{{ $log->attendanceStatusLabel() }}</span>
                            </td>
                            <td class="small">{{ $log->durationLabel() }}</td>
                            <td>
                                @if ($log->session_deducted)
                                    <i class="bi bi-check-circle text-success"></i>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end"><a href="{{ route('teaching-logs.show', $log->classSchedule) }}"
                                    class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">ยังไม่มีประวัติการเข้าเรียน</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $logs->links() }}</div>
    </div>
@endsection

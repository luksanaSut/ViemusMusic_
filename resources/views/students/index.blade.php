@extends('layouts.app')
@section('title', 'จัดการนักเรียน')

@section('content')
    <style>
        .stat-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: .75rem;
        }

        @media (max-width: 900px) {
            .stat-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .stat-card {
            background: var(--card, #fff);
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 14px;
            padding: .95rem 1.1rem;
            display: flex;
            align-items: center;
            gap: .8rem;
        }

        .stat-card .icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .stat-card .icon.total {
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent, #1f3350);
        }

        .stat-card .icon.active {
            background: var(--success-soft, #e7f2ec);
            color: var(--success, #2f6f4e);
        }

        .stat-card .icon.paused {
            background: var(--amber-soft, #f3ece2);
            color: var(--amber, #8a5a2b);
        }

        .stat-card .icon.overdue {
            background: #fbeae7;
            color: #b3392c;
        }

        .stat-card .value {
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            line-height: 1.1;
        }

        .stat-card .label {
            color: var(--muted, #6b655e);
            font-size: .78rem;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            font-size: .88rem;
        }

        .student-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            flex-shrink: 0;
            object-fit: cover;
            background: linear-gradient(135deg, var(--accent, #1f3350), var(--accent-dark, #13233a));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: .95rem;
        }

        .students-table th {
            font-size: .76rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--muted, #6b655e);
            font-weight: 600;
            border-bottom-width: 1px;
        }

        .students-table td {
            vertical-align: middle;
        }

        .students-table tbody tr {
            transition: background .1s;
        }

        .row-actions .btn {
            border-radius: 8px;
        }

        .empty-state {
            padding: 3.5rem 1rem;
            text-align: center;
            color: var(--muted, #6b655e);
        }

        .empty-state i {
            font-size: 2.4rem;
            color: var(--border, #e4e1dc);
            margin-bottom: .6rem;
            display: block;
        }
    </style>

    <div class="breadcrumb-sm">บุคคล <i class="bi bi-chevron-right small"></i> นักเรียน</div>
    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
        <div>
            <h1 class="page-title">จัดการนักเรียน</h1>
            <div class="page-sub">นักเรียนทั้งหมด {{ $students->total() }} คน</div>
        </div>
        <a href="{{ route('students.create') }}" class="btn btn-accent"><i class="bi bi-plus-lg"></i> เพิ่มข้อมูลนักเรียน</a>
    </div>

    <div class="stat-row mb-3">
        <div class="stat-card">
            <div class="icon total"><i class="bi bi-people"></i></div>
            <div>
                <div class="value">{{ $students->total() }}</div>
                <div class="label">นักเรียนทั้งหมด</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon active"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="value">{{ $statusCounts->get('active', 0) }}</div>
                <div class="label">กำลังเรียน</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon paused"><i class="bi bi-pause-circle"></i></div>
            <div>
                <div class="value">{{ $statusCounts->get('paused', 0) }}</div>
                <div class="label">พักเรียน</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon overdue"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <div class="value">{{ $overdueCount }}</div>
                <div class="label">ค้างชำระเงิน</div>
            </div>
        </div>
    </div>

    <div class="card mb-3 filter-card">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute" style="left:.8rem; top:50%; transform:translateY(-50%); color:var(--muted);"></i>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control ps-5"
                            placeholder="ค้นหาชื่อ / รหัส / เบอร์โทร">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="active" @selected(request('status') == 'active')>กำลังเรียน</option>
                        <option value="paused" @selected(request('status') == 'paused')>พักเรียน</option>
                        <option value="cancelled" @selected(request('status') == 'cancelled')>ยกเลิกเรียน</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-accent"><i class="bi bi-search"></i> ค้นหา</button>
                </div>
                @if (request('q') || request('status'))
                    <div class="col-md-2 d-grid">
                        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary"><i
                                class="bi bi-x-lg"></i> ล้างตัวกรอง</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 students-table">
                <thead class="table-light">
                    <tr>
                        <th>นักเรียน</th>
                        <th>รหัส</th>
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
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($student->photo_path)
                                        <img src="{{ asset('storage/' . $student->photo_path) }}" class="student-avatar">
                                    @else
                                        <div class="student-avatar">{{ $student->initials() }}</div>
                                    @endif
                                    <div>
                                        <a href="{{ route('students.show', $student) }}"
                                            class="fw-semibold text-decoration-none text-body">{{ $student->full_name }}</a>
                                        @if ($student->nickname)
                                            <div class="text-muted small">({{ $student->nickname }})</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ $student->student_code }}</td>
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
                                <div class="d-inline-flex gap-1 row-actions">
                                    <a href="{{ route('students.show', $student) }}"
                                        class="btn btn-sm btn-outline-secondary" title="ดูข้อมูล"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('students.edit', $student) }}"
                                        class="btn btn-sm btn-outline-primary" title="แก้ไข"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('ยืนยันการลบข้อมูลนักเรียนนี้?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="ลบ"><i
                                                class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-people"></i>
                                    ไม่พบข้อมูลนักเรียน
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $students->links() }}</div>
    </div>
@endsection

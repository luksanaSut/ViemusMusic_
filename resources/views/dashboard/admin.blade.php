@extends('layouts.app')
@section('title', 'แดชบอร์ดผู้ดูแลระบบ')

@section('content')
    @php $u = auth()->user(); @endphp
    <style>
        .admin-hero { display:flex; justify-content:space-between; align-items:flex-end; gap:1rem; margin-bottom:1rem; }
        .stat-card { border:1px solid var(--border); border-radius:14px; background:var(--card); padding:1rem; height:100%; }
        .stat-icon { width:38px; height:38px; border-radius:11px; display:flex; align-items:center; justify-content:center; background:var(--accent-soft); color:var(--accent); flex-shrink:0; }
        .stat-value { font-family:'Prompt',sans-serif; font-size:1.35rem; font-weight:700; line-height:1; }
        .info-card { border-radius:16px; height:100%; }
        .info-row { display:flex; gap:.65rem; align-items:flex-start; padding:.65rem 0; border-bottom:1px solid var(--border); }
        .info-row:last-child { border-bottom:0; }
        .approval-card { border:1px solid var(--border); border-radius:14px; background:var(--card); padding:1rem; height:100%; text-decoration:none; color:inherit; display:block; transition:.15s ease; }
        .approval-card:hover { transform:translateY(-1px); box-shadow:0 6px 16px rgba(28,26,23,.08); color:inherit; }
        .approval-card.is-empty { opacity:.55; pointer-events:none; }
        .empty-note { text-align:center; color:var(--muted); font-size:.82rem; padding:1.5rem .5rem; }
    </style>

    <div class="admin-hero">
        <div>
            <h1 class="page-title mb-1">สวัสดี, {{ $u->displayName() }} 👋</h1>
            <p class="text-muted small mb-0">ภาพรวมระบบวันนี้ · {{ now()->translatedFormat('d M Y') }}</p>
        </div>
    </div>

    {{-- ===== สรุปภาพรวม ===== --}}
    <div class="row g-2 mb-3">
        @if ($u->hasModulePermission('students.manage'))
            <div class="col-6 col-lg-3"><div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-mortarboard"></i></div>
                <div><div class="stat-value">{{ $stats['students_active'] }}</div><div class="small text-muted">นักเรียนกำลังเรียน</div></div>
            </div></div>
        @endif
        @if ($u->hasModulePermission('teachers.manage'))
            <div class="col-6 col-lg-3"><div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-person-badge"></i></div>
                <div><div class="stat-value">{{ $stats['teachers_total'] }}</div><div class="small text-muted">อาจารย์ทั้งหมด</div></div>
            </div></div>
        @endif
        @if ($u->hasModulePermission('courses.manage'))
            <div class="col-6 col-lg-3"><div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-journal-bookmark"></i></div>
                <div><div class="stat-value">{{ $stats['courses_active'] }}</div><div class="small text-muted">คอร์สที่เปิดสอน</div></div>
            </div></div>
        @endif
        @if ($u->hasModulePermission('schedules.manage'))
            <div class="col-6 col-lg-3"><div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-calendar-day"></i></div>
                <div><div class="stat-value">{{ $stats['today_classes'] }}</div><div class="small text-muted">คาบเรียนวันนี้</div></div>
            </div></div>
        @endif
        @if ($u->hasModulePermission('trial_leads.manage'))
            <div class="col-6 col-lg-3"><div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:var(--amber-soft,#f3ece2);color:var(--amber,#8a5a2b);"><i class="bi bi-person-check"></i></div>
                <div><div class="stat-value">{{ $stats['today_trials'] }}</div><div class="small text-muted">นัดทดลองวันนี้</div></div>
            </div></div>
        @endif
    </div>

    {{-- ===== การเงินเดือนนี้ ===== --}}
    @if ($finance)
        <div class="row g-2 mb-3">
            <div class="col-6 col-lg-3"><div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon text-success" style="background:var(--success-soft);"><i class="bi bi-graph-up-arrow"></i></div>
                <div><div class="stat-value">฿{{ number_format($finance['income']['total'], 0) }}</div><div class="small text-muted">รายรับเดือนนี้</div></div>
            </div></div>
            <div class="col-6 col-lg-3"><div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon text-danger" style="background:#fbeae7;"><i class="bi bi-graph-down-arrow"></i></div>
                <div><div class="stat-value">฿{{ number_format($finance['expense']['total'], 0) }}</div><div class="small text-muted">รายจ่ายเดือนนี้</div></div>
            </div></div>
            <div class="col-6 col-lg-3"><div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
                <div><div class="stat-value">{{ $finance['net_profit'] >= 0 ? '฿' : '-฿' }}{{ number_format(abs($finance['net_profit']), 0) }}</div><div class="small text-muted">กำไร/ขาดทุนสุทธิ</div></div>
            </div></div>
            @if ($u->hasModulePermission('students.manage'))
                <div class="col-6 col-lg-3"><div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-icon text-danger" style="background:#fbeae7;"><i class="bi bi-exclamation-circle"></i></div>
                    <div><div class="stat-value">{{ $overduePaymentsCount }}</div><div class="small text-muted">ค้างชำระ · ฿{{ number_format($overduePaymentsAmount, 0) }}</div></div>
                </div></div>
            @endif
            <div class="col-12 text-end"><a href="{{ route('finance.dashboard') }}" class="small">ดูภาพรวมการเงิน <i class="bi bi-arrow-right"></i></a></div>
        </div>
    @endif

    {{-- ===== คำขอรออนุมัติ ===== --}}
    @if ($u->hasModulePermission('teachers.manage') || $u->hasModulePermission('makeup_reschedule.manage') || $u->hasModulePermission('student_leaves.manage'))
        <h6 class="fw-bold mb-2" style="font-family:'Prompt',sans-serif;"><i class="bi bi-hourglass-split"></i> คำขอรออนุมัติ</h6>
        <div class="row g-2 mb-3">
            @if ($u->hasModulePermission('teachers.manage'))
                <div class="col-6 col-lg-3">
                    <a href="{{ route('teacher-leaves.index') }}" class="approval-card {{ $pending['teacher_leaves'] === 0 ? 'is-empty' : '' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon"><i class="bi bi-calendar-x"></i></div>
                            <div><div class="stat-value">{{ $pending['teacher_leaves'] }}</div><div class="small text-muted">ลาหยุดสอน</div></div>
                        </div>
                    </a>
                </div>
            @endif
            @if ($u->hasModulePermission('makeup_reschedule.manage'))
                <div class="col-6 col-lg-3">
                    <a href="{{ route('reschedule-requests.index') }}" class="approval-card {{ $pending['reschedule_requests'] === 0 ? 'is-empty' : '' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon"><i class="bi bi-arrow-left-right"></i></div>
                            <div><div class="stat-value">{{ $pending['reschedule_requests'] }}</div><div class="small text-muted">สลับ/เปลี่ยนตาราง</div></div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-3">
                    <a href="{{ route('makeup-requests.index') }}" class="approval-card {{ $pending['makeup_requests'] === 0 ? 'is-empty' : '' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon"><i class="bi bi-calendar-plus"></i></div>
                            <div><div class="stat-value">{{ $pending['makeup_requests'] }}</div><div class="small text-muted">เรียนชดเชย</div></div>
                        </div>
                    </a>
                </div>
            @endif
            @if ($u->hasModulePermission('student_leaves.manage'))
                <div class="col-6 col-lg-3">
                    <a href="{{ route('students.index') }}" class="approval-card {{ $pending['student_leaves'] === 0 ? 'is-empty' : '' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon"><i class="bi bi-journal-x"></i></div>
                            <div><div class="stat-value">{{ $pending['student_leaves'] }}</div><div class="small text-muted">ลาเรียนของนักเรียน</div></div>
                        </div>
                    </a>
                </div>
            @endif
        </div>
    @endif

    <div class="row g-3">
        {{-- ===== ตารางเรียนวันนี้ ===== --}}
        @if ($u->hasModulePermission('schedules.manage'))
            <div class="col-lg-7"><div class="card info-card"><div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="fw-bold mb-0" style="font-family:'Prompt',sans-serif;"><i class="bi bi-calendar3"></i> คาบเรียนวันนี้</h6>
                    <a href="{{ route('schedules.index') }}" class="small">ดูตารางทั้งหมด</a>
                </div>
                @forelse ($todaySchedules as $schedule)
                    <div class="info-row">
                        <div class="stat-icon flex-shrink-0"><i class="bi bi-clock"></i></div>
                        <div class="flex-grow-1 small">
                            <div class="fw-semibold">{{ substr($schedule->start_time, 0, 5) }}–{{ substr($schedule->end_time, 0, 5) }} · {{ $schedule->enrollment->student->full_name ?? 'ไม่ระบุนักเรียน' }}</div>
                            <div class="text-muted">{{ $schedule->enrollment->course->name ?? 'ไม่ระบุคอร์ส' }} · อ.{{ $schedule->teacher->nickname ?? $schedule->teacher->full_name ?? '-' }}@if($schedule->room) · {{ $schedule->room->name }}@endif</div>
                        </div>
                        <span class="badge {{ $schedule->statusBadgeClass() }} flex-shrink-0 align-self-center">{{ $schedule->statusLabel() }}</span>
                    </div>
                @empty
                    @if($todayTrialLeads->isEmpty())
                        <div class="empty-note"><i class="bi bi-calendar-x fs-4 d-block mb-1"></i>วันนี้ไม่มีคาบเรียน</div>
                    @endif
                @endforelse
                @foreach ($todayTrialLeads as $trial)
                    <a href="{{ route('trial-leads.show', $trial) }}" class="info-row text-decoration-none text-reset">
                        <div class="stat-icon flex-shrink-0"><i class="bi bi-person-check"></i></div>
                        <div class="flex-grow-1 small">
                            <div class="fw-semibold">{{ $trial->trial_start_time ? substr($trial->trial_start_time, 0, 5).'–'.substr($trial->trial_end_time, 0, 5) : 'ไม่ระบุเวลา' }} · {{ $trial->student_name }} <span class="badge text-bg-warning">นัดทดลอง</span></div>
                            <div class="text-muted">{{ $trial->course->name ?? $trial->interest ?? 'ไม่ระบุคอร์ส' }} · อ.{{ $trial->teacher->nickname ?? $trial->teacher->full_name ?? '-' }}@if($trial->room) · {{ $trial->room->name }}@endif</div>
                        </div>
                        <span class="badge {{ $trial->confirmationStatusBadgeClass() }} flex-shrink-0 align-self-center">{{ $trial->confirmationStatusLabel() }}</span>
                    </a>
                @endforeach
            </div></div></div>
        @endif

        {{-- ===== รายการขายล่าสุด ===== --}}
        @if ($u->hasModulePermission('sales.manage'))
            <div class="col-lg-5"><div class="card info-card"><div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="fw-bold mb-0" style="font-family:'Prompt',sans-serif;"><i class="bi bi-cart-check"></i> การขายล่าสุด</h6>
                    <a href="{{ route('sales.index') }}" class="small">ดูทั้งหมด</a>
                </div>
                @forelse ($recentSales as $sale)
                    <a href="{{ route('sales.show', $sale) }}" class="info-row text-decoration-none text-reset">
                        <div class="stat-icon flex-shrink-0"><i class="bi bi-receipt"></i></div>
                        <div class="flex-grow-1 small">
                            <div class="fw-semibold">{{ $sale->student->full_name ?? '-' }}</div>
                            <div class="text-muted">{{ $sale->course->name ?? 'ไม่ระบุคอร์ส' }} · {{ $sale->created_at->format('d/m/Y') }}</div>
                        </div>
                        <span class="badge {{ $sale->statusBadgeClass() }} flex-shrink-0 align-self-center">{{ $sale->statusLabel() }}</span>
                    </a>
                @empty
                    <div class="empty-note"><i class="bi bi-cart fs-4 d-block mb-1"></i>ยังไม่มีรายการขาย</div>
                @endforelse
            </div></div></div>
        @endif
    </div>
@endsection

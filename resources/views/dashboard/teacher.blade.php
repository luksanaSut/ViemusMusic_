@extends('layouts.app')
@section('title', 'แดชบอร์ดของฉัน')

@section('content')
    <style>
        .teacher-hero { display:flex; justify-content:space-between; align-items:flex-end; gap:1rem; margin-bottom:1rem; }
        .stat-card { border:1px solid var(--border); border-radius:14px; background:var(--card); padding:1rem; height:100%; }
        .stat-icon { width:38px; height:38px; border-radius:11px; display:flex; align-items:center; justify-content:center; background:var(--accent-soft); color:var(--accent); }
        .stat-value { font-family:'Prompt',sans-serif; font-size:1.35rem; font-weight:700; line-height:1; }
        .calendar-card { border:1px solid var(--border); border-radius:18px; overflow:hidden; background:var(--card); }
        .calendar-toolbar { display:flex; align-items:center; justify-content:space-between; gap:.75rem; padding:1rem 1.15rem; border-bottom:1px solid var(--border); }
        .calendar-nav { display:flex; align-items:center; gap:.4rem; }
        .calendar-grid { display:grid; grid-template-columns:repeat(7, minmax(150px, 1fr)); min-width:1050px; }
        .calendar-scroll { overflow-x:auto; }
        .calendar-day { min-height:260px; border-right:1px solid var(--border); padding:.65rem; background:var(--card); }
        .calendar-day:last-child { border-right:0; }
        .calendar-day.is-today { background:linear-gradient(180deg, var(--accent-soft), var(--card) 34%); }
        .day-heading { text-align:center; padding:.25rem 0 .7rem; }
        .day-name { color:var(--muted); font-size:.72rem; }
        .day-number { width:34px; height:34px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-family:'Prompt',sans-serif; font-weight:700; }
        .is-today .day-number { background:var(--accent); color:#fff; }
        .class-event { display:block; border:1px solid var(--border); border-left:4px solid var(--accent); border-radius:10px; padding:.55rem .6rem; margin-bottom:.5rem; color:inherit; text-decoration:none; background:var(--surface); transition:.15s ease; }
        .class-event:hover { color:inherit; transform:translateY(-1px); box-shadow:0 6px 16px rgba(28,26,23,.08); }
        .class-time { font-family:'Prompt',sans-serif; font-size:.76rem; font-weight:700; }
        .class-student { font-size:.82rem; font-weight:600; margin-top:.18rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .class-meta { font-size:.69rem; color:var(--muted); margin-top:.18rem; line-height:1.45; }
        .empty-day { text-align:center; color:var(--muted); font-size:.74rem; padding:1.3rem .25rem; }
        .info-card { border-radius:16px; height:100%; }
        .info-row { display:flex; gap:.65rem; align-items:flex-start; padding:.65rem 0; border-bottom:1px solid var(--border); }
        .info-row:last-child { border-bottom:0; }
        .week-panel[hidden] { display:none !important; }
        @media (max-width:767.98px) {
            .teacher-hero { align-items:flex-start; flex-direction:column; }
            .calendar-toolbar { align-items:flex-start; }
            .calendar-title small { display:block; margin-top:.2rem; }
            .calendar-grid { grid-template-columns:repeat(7, minmax(138px, 1fr)); }
            .calendar-day { min-height:220px; }
        }
    </style>

    <div class="teacher-hero">
        <div><h1 class="page-title mb-1">สวัสดี, {{ $teacher->nickname ?: $teacher->full_name }} 👋</h1><p class="text-muted small mb-0">ภาพรวมงานสอนและสิ่งที่ต้องจัดการในช่วง 4 สัปดาห์ข้างหน้า</p></div>
        <a href="{{ route('teaching-logs.index') }}" class="btn btn-accent btn-sm"><i class="bi bi-check2-square"></i> เช็กชื่อ / บันทึกการสอน</a>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-6 col-lg-3"><div class="stat-card d-flex align-items-center gap-3"><div class="stat-icon"><i class="bi bi-calendar-day"></i></div><div><div class="stat-value">{{ $dashboardStats['today_classes'] }}</div><div class="small text-muted">คาบวันนี้</div></div></div></div>
        <div class="col-6 col-lg-3"><div class="stat-card d-flex align-items-center gap-3"><div class="stat-icon"><i class="bi bi-clock"></i></div><div><div class="stat-value">{{ $dashboardStats['week_hours'] }}</div><div class="small text-muted">ชั่วโมงสัปดาห์นี้ · {{ $dashboardStats['week_classes'] }} คาบ</div></div></div></div>
        <div class="col-6 col-lg-3"><div class="stat-card d-flex align-items-center gap-3"><div class="stat-icon"><i class="bi bi-people"></i></div><div><div class="stat-value">{{ $dashboardStats['students'] }}</div><div class="small text-muted">นักเรียนใน 4 สัปดาห์</div></div></div></div>
        <div class="col-6 col-lg-3"><div class="stat-card d-flex align-items-center gap-3"><div class="stat-icon"><i class="bi bi-arrow-repeat"></i></div><div><div class="stat-value">{{ $dashboardStats['pending_makeups'] }}</div><div class="small text-muted">ชดเชยรออนุมัติ</div></div></div></div>
        <div class="col-6 col-lg-3"><a href="{{ route('trial-leads.my-index') }}" class="text-decoration-none text-reset"><div class="stat-card d-flex align-items-center gap-3"><div class="stat-icon" style="background:var(--amber-soft,#f3ece2);color:var(--amber,#8a5a2b);"><i class="bi bi-person-check"></i></div><div><div class="stat-value">{{ $dashboardStats['today_trials'] }}</div><div class="small text-muted">นัดทดลองวันนี้</div></div></div></a></div>
    </div>

    <section class="calendar-card mb-3">
        <div class="calendar-toolbar">
            <div class="calendar-title"><h6 class="fw-bold mb-0" style="font-family:'Prompt',sans-serif;"><i class="bi bi-calendar3"></i> ปฏิทินตารางสอน</h6><small class="text-muted" id="weekRangeLabel"></small></div>
            <div class="calendar-nav"><button type="button" class="btn btn-sm btn-outline-secondary" id="prevWeek" aria-label="สัปดาห์ก่อนหน้า"><i class="bi bi-chevron-left"></i></button><button type="button" class="btn btn-sm btn-outline-secondary" id="thisWeek">สัปดาห์นี้</button><button type="button" class="btn btn-sm btn-outline-secondary" id="nextWeek" aria-label="สัปดาห์ถัดไป"><i class="bi bi-chevron-right"></i></button></div>
        </div>

        @php
            $thaiDays = ['จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.', 'อา.'];
            $thaiMonths = [1 => 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
            $schedulesByDate = $scheduleUpcoming->groupBy(fn ($schedule) => $schedule->schedule_date->toDateString());
            $trialsByDate = $trialsUpcoming->groupBy(fn ($trial) => $trial->trial_date->toDateString());
        @endphp
        <div class="calendar-scroll">
            @for ($week = 0; $week < 4; $week++)
                @php
                    $weekStart = $calendarStart->copy()->addWeeks($week);
                    $weekEnd = $weekStart->copy()->endOfWeek();
                    $range = $weekStart->day . ' ' . $thaiMonths[$weekStart->month] . ' - ' . $weekEnd->day . ' ' . $thaiMonths[$weekEnd->month] . ' ' . ($weekEnd->year + 543);
                @endphp
                <div class="calendar-grid week-panel" data-week="{{ $week }}" data-range="{{ $range }}" @if($week > 0) hidden @endif>
                    @for ($day = 0; $day < 7; $day++)
                        @php
                            $date = $weekStart->copy()->addDays($day);
                            $daySchedules = $schedulesByDate->get($date->toDateString(), collect());
                            $dayTrials = $trialsByDate->get($date->toDateString(), collect());
                        @endphp
                        <div class="calendar-day {{ $date->isToday() ? 'is-today' : '' }}">
                            <div class="day-heading"><div class="day-name">{{ $thaiDays[$day] }}</div><div class="day-number">{{ $date->day }}</div></div>
                            @forelse ($daySchedules as $schedule)
                                <a href="{{ route('teaching-logs.show', $schedule) }}" class="class-event" title="เปิดรายละเอียดและเช็กชื่อ">
                                    <div class="class-time"><i class="bi bi-clock"></i> {{ substr($schedule->start_time, 0, 5) }}–{{ substr($schedule->end_time, 0, 5) }}</div>
                                    <div class="class-student">{{ $schedule->enrollment->student->full_name ?? 'ไม่ระบุนักเรียน' }}</div>
                                    <div class="class-meta">{{ $schedule->enrollment->course->name ?? 'ไม่ระบุคอร์ส' }}</div>
                                    <div class="class-meta"><i class="bi {{ $schedule->delivery_mode === 'online' ? 'bi-camera-video' : 'bi-geo-alt' }}"></i> {{ $schedule->deliveryModeLabel() }}@if($schedule->room) · {{ $schedule->room->name }}@endif</div>
                                    @if($schedule->notes)<div class="class-meta text-truncate"><i class="bi bi-sticky"></i> {{ $schedule->notes }}</div>@endif
                                </a>
                            @empty
                                @if($dayTrials->isEmpty())
                                    <div class="empty-day"><i class="bi bi-dash-circle"></i><br>ไม่มีคาบสอน</div>
                                @endif
                            @endforelse
                            @foreach ($dayTrials as $trial)
                                <a href="{{ route('trial-leads.my-show', $trial) }}" class="class-event" style="border-left-color:var(--amber,#8a5a2b);" title="นัดทดลองเรียน">
                                    <div class="class-time"><i class="bi bi-person-check"></i> {{ $trial->trial_start_time ? substr($trial->trial_start_time, 0, 5).'–'.substr($trial->trial_end_time, 0, 5) : 'ทดลองเรียน' }}</div>
                                    <div class="class-student">{{ $trial->student_name }}</div>
                                    <div class="class-meta">{{ $trial->course->name ?? $trial->interest ?? 'นัดทดลองเรียน' }}</div>
                                </a>
                            @endforeach
                        </div>
                    @endfor
                </div>
            @endfor
        </div>
    </section>

    <div class="row g-3">
        <div class="col-lg-7"><div class="card info-card"><div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-1"><h6 class="fw-bold mb-0" style="font-family:'Prompt',sans-serif;"><i class="bi bi-arrow-repeat"></i> คำขอสอนชดเชยรออนุมัติ</h6><a href="{{ route('makeup-requests.my-index') }}" class="small">ดูทั้งหมด</a></div>
            @forelse ($pendingMakeups->take(4) as $makeup)
                <div class="info-row"><div class="stat-icon flex-shrink-0"><i class="bi bi-calendar-plus"></i></div><div class="flex-grow-1 small"><div class="fw-semibold">{{ $makeup->student->full_name ?? '-' }} · {{ $makeup->enrollment->course->name ?? 'ไม่ระบุคอร์ส' }}</div><div class="text-muted">{{ $makeup->makeup_date->format('d/m/Y') }} · {{ substr($makeup->start_time, 0, 5) }}–{{ substr($makeup->end_time, 0, 5) }} · {{ $makeup->delivery_mode === 'online' ? 'ออนไลน์' : ($makeup->room->name ?? 'ไม่ระบุห้อง') }}</div></div><a href="{{ route('makeup-requests.show', $makeup) }}" class="btn btn-sm btn-outline-primary flex-shrink-0">ตรวจสอบ</a></div>
            @empty
                <div class="text-center text-muted small py-4"><i class="bi bi-check-circle fs-4 d-block mb-1"></i>ไม่มีคำขอที่รอคุณอนุมัติ</div>
            @endforelse
        </div></div></div>
        <div class="col-lg-5"><div class="card info-card"><div class="card-body">
            <h6 class="fw-bold" style="font-family:'Prompt',sans-serif;"><i class="bi bi-person-vcard"></i> ข้อมูลการสอนของฉัน</h6>
            <div class="info-row small"><i class="bi bi-briefcase text-muted"></i><div><div class="text-muted">ประเภทการจ้าง</div><strong>{{ $teacher->employmentTypeLabel() }}</strong></div></div>
            <div class="info-row small"><i class="bi bi-music-note-beamed text-muted"></i><div><div class="text-muted">เครื่องดนตรี</div><strong>{{ $teacher->instruments->pluck('name')->join(', ') ?: '-' }}</strong></div></div>
            <div class="info-row small"><i class="bi bi-building text-muted"></i><div><div class="text-muted">สาขา</div><strong>{{ $teacher->branch ?: '-' }}</strong></div></div>
            <a href="{{ route('teacher-leaves.my-index') }}" class="btn btn-sm btn-outline-secondary w-100 mt-3"><i class="bi bi-calendar-x"></i> แจ้งลาหยุดสอน</a>
        </div></div></div>
    </div>

    <script>
        (() => {
            const panels = [...document.querySelectorAll('.week-panel')];
            const rangeLabel = document.getElementById('weekRangeLabel');
            const prev = document.getElementById('prevWeek');
            const next = document.getElementById('nextWeek');
            let currentWeek = 0;
            function showWeek(index) {
                currentWeek = Math.max(0, Math.min(panels.length - 1, index));
                panels.forEach((panel, i) => panel.hidden = i !== currentWeek);
                rangeLabel.textContent = panels[currentWeek]?.dataset.range || '';
                prev.disabled = currentWeek === 0;
                next.disabled = currentWeek === panels.length - 1;
            }
            prev.addEventListener('click', () => showWeek(currentWeek - 1));
            next.addEventListener('click', () => showWeek(currentWeek + 1));
            document.getElementById('thisWeek').addEventListener('click', () => showWeek(0));
            showWeek(0);
        })();
    </script>
@endsection

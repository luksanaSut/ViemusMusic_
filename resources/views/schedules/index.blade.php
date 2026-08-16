@extends('layouts.app')
@section('title', 'ตารางเรียน')

@section('content')
    <style>
        .form-section {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.4rem 1.6rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 2px rgba(28, 26, 23, .04);
        }

        .schedule-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: .75rem 0;
            border-bottom: 1px solid #f0efec;
        }

        .schedule-row:last-child {
            border-bottom: 0;
        }

        .schedule-time {
            min-width: 110px;
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            color: var(--accent-dark, #13233a);
            font-size: .88rem;
        }

        .schedule-info {
            flex: 1;
        }

        .schedule-info .main {
            font-weight: 600;
            font-size: .9rem;
        }

        .schedule-info .meta {
            font-size: .78rem;
            color: var(--muted, #6b655e);
        }

        /* ===== ปฏิทินรายเดือน ===== */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: var(--border, #e4e1dc);
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 14px;
            overflow: hidden;
        }

        .calendar-weekday {
            background: #f4f3f1;
            padding: .6rem;
            text-align: center;
            font-size: .72rem;
            font-weight: 700;
            color: var(--muted, #6b655e);
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .calendar-cell {
            background: #fff;
            min-height: 120px;
            padding: .5rem;
            display: flex;
            flex-direction: column;
            gap: .3rem;
        }

        .calendar-cell.dim {
            background: #faf9f7;
        }

        .calendar-cell.today {
            background: var(--accent-soft, #e7ebf1);
        }

        .calendar-date {
            font-size: .8rem;
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            color: var(--ink, #1c1a17);
        }

        .calendar-cell.dim .calendar-date {
            color: var(--muted, #6b655e);
        }

        .calendar-cell.today .calendar-date {
            color: var(--accent-dark, #13233a);
        }

        .calendar-events {
            display: flex;
            flex-direction: column;
            gap: .2rem;
            max-height: 88px;
            overflow-y: auto;
        }

        .calendar-chip {
            font-size: .68rem;
            padding: .18rem .4rem;
            border-radius: 5px;
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent-dark, #13233a);
            text-decoration: none;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
        }

        .calendar-chip:hover {
            background: var(--accent, #1f3350);
            color: #fff;
        }

        .calendar-chip.status-cancelled {
            background: #efe9e4;
            color: var(--muted, #6b655e);
            text-decoration: line-through;
        }

        .calendar-more {
            font-size: .68rem;
            color: var(--accent, #1f3350);
            font-weight: 600;
            cursor: pointer;
        }

        /* ===== ปฏิทินรายสัปดาห์ ===== */
        .week-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: .7rem;
        }

        .week-col {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 14px;
            overflow: hidden;
            min-height: 200px;
        }

        .week-col-header {
            background: #f4f3f1;
            padding: .6rem;
            text-align: center;
        }

        .week-col-header.today {
            background: var(--accent-soft, #e7ebf1);
        }

        .week-col-header .dow {
            font-size: .68rem;
            color: var(--muted, #6b655e);
            text-transform: uppercase;
        }

        .week-col-header .dnum {
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
        }

        .week-col-body {
            padding: .5rem;
            display: flex;
            flex-direction: column;
            gap: .4rem;
        }

        .week-event {
            background: var(--accent-soft, #e7ebf1);
            border-radius: 8px;
            padding: .4rem .5rem;
            font-size: .72rem;
            text-decoration: none;
            color: var(--accent-dark, #13233a);
            display: block;
        }

        .week-event:hover {
            background: var(--accent, #1f3350);
            color: #fff;
        }

        .week-event .t {
            font-weight: 700;
        }

        .week-event.status-cancelled {
            background: #efe9e4;
            color: var(--muted, #6b655e);
            text-decoration: line-through;
        }

        /* ===== Modal รายละเอียดวัน (คลิก +N more) ===== */
        .day-modal {
            position: fixed;
            inset: 0;
            background: rgba(28, 26, 23, .4);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1050;
        }

        .day-modal.show {
            display: flex;
        }

        .day-modal-box {
            background: #fff;
            border-radius: 16px;
            padding: 1.4rem;
            max-width: 480px;
            width: 92%;
            max-height: 80vh;
            overflow-y: auto;
        }
    </style>

    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> ตารางเรียน</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h1 class="page-title">ระบบจัดตารางเรียน</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('schedules.create') }}" class="btn btn-outline-secondary"><i class="bi bi-calendar-plus"></i>
                เพิ่มทีละคาบ</a>
            <a href="{{ route('schedules.bulk-create') }}" class="btn btn-accent"><i class="bi bi-calendar2-range"></i>
                จัดตารางแบบชุด</a>
        </div>
    </div>

    @if (session('bulk_skipped') && count(session('bulk_skipped')) > 0)
        <div class="alert alert-warning">
            <strong><i class="bi bi-exclamation-triangle"></i> ข้ามไป {{ count(session('bulk_skipped')) }}
                วันเพราะมีตารางชนกัน:</strong>
            <ul class="mb-0 mt-1 small">
                @foreach (session('bulk_skipped') as $skip)
                    <li>{{ $skip['date'] }} — {{ implode(' / ', $skip['reasons']) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small">มุมมอง</label>
                    <select name="view" class="form-select">
                        <option value="day" @selected($view == 'day')>รายวัน</option>
                        <option value="week" @selected($view == 'week')>รายสัปดาห์ (ปฏิทิน)</option>
                        <option value="month" @selected($view == 'month')>รายเดือน (ปฏิทิน)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">วันที่อ้างอิง</label>
                    <input type="date" name="date" value="{{ $date->toDateString() }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">ตารางนักเรียน</label>
                    <select name="student_id" class="form-select">
                        <option value="">ทุกคน</option>
                        @foreach ($students as $s)
                            <option value="{{ $s->id }}" @selected(request('student_id') == $s->id)>{{ $s->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">ตารางอาจารย์</label>
                    <select name="teacher_id" class="form-select">
                        <option value="">ทุกคน</option>
                        @foreach ($teachers as $t)
                            <option value="{{ $t->id }}" @selected(request('teacher_id') == $t->id)>
                                {{ $t->nickname ?: $t->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">ตารางห้องเรียน</label>
                    <select name="room_id" class="form-select">
                        <option value="">ทุกห้อง</option>
                        @foreach ($rooms as $r)
                            <option value="{{ $r->id }}" @selected(request('room_id') == $r->id)>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2"><button class="btn btn-accent w-100">แสดงตาราง</button></div>
                <div class="col-12 mt-2">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาชื่อนักเรียน / อาจารย์ / คอร์ส...">
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <p class="text-muted mb-0">ช่วงวันที่: {{ $from->format('d/m/Y') }} - {{ $to->format('d/m/Y') }}</p>
        <div class="d-flex gap-2">
            <a href="{{ request()->fullUrlWithQuery(['date' => $date->copy()->sub(1, $view)->toDateString()]) }}"
                class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-left"></i></a>
            <a href="{{ request()->fullUrlWithQuery(['date' => now()->toDateString()]) }}"
                class="btn btn-sm btn-outline-secondary">วันนี้</a>
            <a href="{{ request()->fullUrlWithQuery(['date' => $date->copy()->add(1, $view)->toDateString()]) }}"
                class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-right"></i></a>
        </div>
    </div>

    @php
        // Flatten ตารางเรียนทั้งหมดในช่วงเวลาที่กรองไว้ ให้พร้อมใช้ทั้ง 3 มุมมอง
        $allEvents = collect($schedules)->flatten(1);
        $eventsByDate = $allEvents->groupBy(fn($s) => $s->schedule_date->toDateString());

        $studentDeletedLabel = fn($s) => $s->enrollment?->student?->trashed();
    @endphp

    @if ($view === 'month')
        {{-- ===== ปฏิทินรายเดือน ===== --}}
        @php
            $gridStart = $from->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
            $gridEnd = $to->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
            $dayLabels = ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'];
        @endphp
        <div class="calendar-grid">
            @foreach ($dayLabels as $lbl)
                <div class="calendar-weekday">{{ $lbl }}</div>
            @endforeach

            @php $cursor = $gridStart->copy(); @endphp
            @while ($cursor->lte($gridEnd))
                @php
                    $isDim = !$cursor->between($from, $to);
                    $isToday = $cursor->isToday();
                    $dayEvents = $eventsByDate->get($cursor->toDateString(), collect())->sortBy('start_time');
                @endphp
                <div class="calendar-cell {{ $isDim ? 'dim' : '' }} {{ $isToday ? 'today' : '' }}">
                    <div class="calendar-date">{{ $cursor->day }}</div>
                    <div class="calendar-events">
                        @foreach ($dayEvents->take(4) as $ev)
                            <a href="{{ route('schedules.edit', $ev) }}"
                                class="calendar-chip {{ $ev->status == 'cancelled' ? 'status-cancelled' : '' }}"
                                title="{{ $ev->enrollment?->student?->full_name }} — {{ $ev->enrollment?->course?->name }}">
                                {{ $ev->start_time }} {{ $ev->enrollment?->student?->full_name ?? '-' }}
                            </a>
                        @endforeach
                        @if ($dayEvents->count() > 4)
                            <span class="calendar-more"
                                onclick="openDayModal('{{ $cursor->toDateString() }}')">+{{ $dayEvents->count() - 4 }}
                                เพิ่มเติม</span>
                        @endif
                    </div>
                </div>
                @php $cursor->addDay(); @endphp
            @endwhile
        </div>
    @elseif($view === 'week')
        {{-- ===== ปฏิทินรายสัปดาห์ ===== --}}
        <div class="week-grid">
            @php $cursor = $from->copy(); @endphp
            @for ($i = 0; $i < 7; $i++)
                @php
                    $isToday = $cursor->isToday();
                    $dayEvents = $eventsByDate->get($cursor->toDateString(), collect())->sortBy('start_time');
                @endphp
                <div class="week-col">
                    <div class="week-col-header {{ $isToday ? 'today' : '' }}">
                        <div class="dow">{{ $cursor->translatedFormat('D') }}</div>
                        <div class="dnum">{{ $cursor->day }}</div>
                    </div>
                    <div class="week-col-body">
                        @forelse($dayEvents as $ev)
                            <a href="{{ route('schedules.edit', $ev) }}"
                                class="week-event {{ $ev->status == 'cancelled' ? 'status-cancelled' : '' }}">
                                <div class="t">{{ $ev->start_time }}-{{ $ev->end_time }}</div>
                                <div>{{ $ev->enrollment?->student?->full_name ?? '-' }}</div>
                                <div class="text-muted">
                                    {{ $ev->teacher->nickname ?? ($ev->teacher->full_name ?? 'ไม่ระบุอาจารย์') }}</div>
                            </a>
                        @empty
                            <div class="text-muted small text-center py-2">ว่าง</div>
                        @endforelse
                    </div>
                </div>
                @php $cursor->addDay(); @endphp
            @endfor
        </div>
    @else
        {{-- ===== รายวัน: คงรูปแบบรายการเดิม (อ่านง่ายสุดสำหรับ 1 วัน) ===== --}}
        @forelse($schedules as $dateKey => $daySchedules)
            <div class="form-section">
                <div class="form-section-title" style="border-bottom:0; margin-bottom:.5rem; padding-bottom:0;">
                    <div class="icon-badge"><i class="bi bi-calendar-day"></i></div>
                    {{ \Carbon\Carbon::parse($dateKey)->translatedFormat('l d/m/Y') }}
                </div>
                @foreach ($daySchedules as $s)
                    <div class="schedule-row">
                        <a href="{{ route('reschedule-requests.create', ['class_schedule_id' => $s->id]) }}"
                            class="btn btn-sm btn-outline-secondary" title="ขอเปลี่ยนแปลง/แลกคาบ"><i
                                class="bi bi-arrow-left-right"></i></a>
                        <div class="schedule-time"><i class="bi bi-clock"></i> {{ $s->start_time }} - {{ $s->end_time }}
                        </div>
                        <div class="schedule-info">
                            <div class="main">
                                @php $studentDeleted = $s->enrollment?->student?->trashed(); @endphp
                                @if ($s->enrollment?->student && !$studentDeleted)
                                    <a
                                        href="{{ route('students.show', $s->enrollment->student) }}">{{ $s->enrollment->student->full_name }}</a>
                                @elseif($s->enrollment?->student)
                                    <span class="text-muted">{{ $s->enrollment->student->full_name }} <span
                                            class="badge text-bg-secondary">ลบแล้ว</span></span>
                                @else
                                    <span class="text-muted">(ไม่พบข้อมูลนักเรียน)</span>
                                @endif
                                — {{ $s->enrollment?->course?->name ?? '-' }}
                            </div>
                            <div class="meta">
                                <i class="bi bi-person-badge"></i> {{ $s->teacher->full_name ?? 'ไม่ระบุอาจารย์' }}
                                @if ($s->room)
                                    · <i class="bi bi-door-open"></i> {{ $s->room->name }}
                                @endif
                                · {{ $s->deliveryModeLabel() }}
                            </div>
                        </div>
                        <span class="badge {{ $s->statusBadgeClass() }}">{{ $s->statusLabel() }}</span>
                        <a href="{{ route('schedules.edit', $s) }}" class="btn btn-sm btn-outline-primary"><i
                                class="bi bi-pencil"></i></a>
                        <form action="{{ route('schedules.cancel', $s) }}" method="POST"
                            onsubmit="return confirm('ยกเลิกคาบนี้?')">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i></button>
                        </form>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="alert alert-light border text-center text-muted">ไม่มีตารางเรียนในช่วงเวลาที่เลือก</div>
        @endforelse
    @endif

    {{-- ===== Modal แสดงคาบทั้งหมดของวันที่คลิก "+N เพิ่มเติม" (เฉพาะมุมมองรายเดือน) ===== --}}
    <div class="day-modal" id="dayModal" onclick="if(event.target===this) closeDayModal()">
        <div class="day-modal-box">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" id="dayModalTitle" style="font-family:'Prompt',sans-serif;"></h6>
                <button class="btn btn-sm btn-outline-secondary" onclick="closeDayModal()"><i
                        class="bi bi-x-lg"></i></button>
            </div>
            <div id="dayModalBody"></div>
        </div>
    </div>

    <script id="calendarEventsData" type="application/json">
{!! $eventsByDate->map(fn($list) => $list->map(fn($s) => [
    'id' => $s->id,
    'start' => $s->start_time,
    'end' => $s->end_time,
    'student' => $s->enrollment?->student?->full_name ?? '-',
    'course' => $s->enrollment?->course?->name ?? '-',
    'teacher' => $s->teacher->nickname ?? ($s->teacher->full_name ?? 'ไม่ระบุอาจารย์'),
    'status' => $s->statusLabel(),
    'edit_url' => route('schedules.edit', $s),
])->values())->toJson() !!}
</script>

    <script>
        const calendarEventsData = JSON.parse(document.getElementById('calendarEventsData').textContent);

        function openDayModal(dateKey) {
            const events = calendarEventsData[dateKey] || [];
            document.getElementById('dayModalTitle').textContent = new Date(dateKey + 'T00:00:00').toLocaleDateString(
                'th-TH', {
                    weekday: 'long',
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
            document.getElementById('dayModalBody').innerHTML = events.map(ev => `
        <a href="${ev.edit_url}" class="d-block text-decoration-none border-bottom py-2">
            <div class="fw-semibold small" style="color:var(--ink,#1c1a17);">${ev.start}-${ev.end} · ${ev.student}</div>
            <div class="text-muted small">${ev.course} · ${ev.teacher} · ${ev.status}</div>
        </a>
    `).join('') || '<div class="text-muted small text-center py-3">ไม่มีตารางเรียน</div>';
            document.getElementById('dayModal').classList.add('show');
        }

        function closeDayModal() {
            document.getElementById('dayModal').classList.remove('show');
        }
    </script>
@endsection

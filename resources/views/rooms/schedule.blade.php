@extends('layouts.app')
@section('title', 'ตารางการใช้งานห้องเรียน')

@section('content')
    {{-- สไตล์ปฏิทินเดียวกับหน้าระบบจัดตารางเรียน (schedules/index) --}}
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
            text-decoration: none;
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

        .calendar-chip.trial-chip,
        .week-event.trial-chip {
            background: var(--amber-soft, #f3ece2);
            color: var(--amber, #8a5a2b);
        }

        .calendar-chip.trial-chip:hover,
        .week-event.trial-chip:hover {
            background: var(--amber, #8a5a2b);
            color: #fff;
        }

        .calendar-chip.booking-chip,
        .week-event.booking-chip {
            background: #e6f2ea;
            color: #23613a;
        }

        .calendar-chip.booking-chip:hover,
        .week-event.booking-chip:hover {
            background: #23613a;
            color: #fff;
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

        .cal-legend span {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 3px;
            margin-right: 4px;
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

    <div class="breadcrumb-sm">ห้องเรียน <i class="bi bi-chevron-right small"></i> ตารางการใช้งานห้องเรียน</div>
    <h1 class="page-title mb-3">ตารางการใช้งานห้องเรียน</h1>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small">มุมมอง</label>
                    <select name="view" class="form-select">
                        <option value="day" @selected($view == 'day')>รายวัน</option>
                        <option value="week" @selected($view == 'week')>รายสัปดาห์ (ปฏิทิน)</option>
                        <option value="month" @selected($view == 'month')>รายเดือน (ปฏิทิน)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">วันที่อ้างอิง</label>
                    <input type="date" name="date" value="{{ $date->toDateString() }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small">ห้อง</label>
                    <select name="room_id" class="form-select">
                        <option value="">ทุกห้อง</option>
                        @foreach ($rooms as $r)
                            <option value="{{ $r->id }}" @selected(request('room_id') == $r->id)>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2"><button class="btn btn-accent w-100">แสดงตาราง</button></div>
            </form>
        </div>
    </div>

    @php
        $nav = fn($d, $v = null) => request()->fullUrlWithQuery(['date' => $d->toDateString(), 'view' => $v ?? $view]);
        $chipClass = ['class' => '', 'trial' => 'trial-chip', 'booking' => 'booking-chip'];
        $typeLabel = ['class' => 'คาบเรียน', 'trial' => 'ทดลองเรียน', 'booking' => 'จองห้อง'];
        $typeIcon = ['class' => 'bi-music-note-beamed', 'trial' => 'bi-person-check', 'booking' => 'bi-bookmark'];
        $timeRange = fn($e, $sep = '-') => $e['start'] ? $e['start'] . ($e['end'] ? $sep . $e['end'] : '') : 'ไม่ระบุเวลา';
    @endphp

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
        <p class="text-muted mb-0">ช่วงวันที่: {{ $from->format('d/m/Y') }} - {{ $to->format('d/m/Y') }}</p>
        <div class="cal-legend small text-muted">
            <span style="background:var(--accent-soft,#e7ebf1); border:1px solid var(--accent,#1f3350)"></span>คาบเรียน
            <span class="ms-2" style="background:var(--amber-soft,#f3ece2); border:1px solid var(--amber,#8a5a2b)"></span>ทดลองเรียน
            <span class="ms-2" style="background:#e6f2ea; border:1px solid #23613a"></span>จองห้อง
        </div>
        <div class="d-flex gap-2">
            <a href="{{ $nav($prev) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-left"></i></a>
            <a href="{{ $nav(now()) }}" class="btn btn-sm btn-outline-secondary">วันนี้</a>
            <a href="{{ $nav($next) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-right"></i></a>
        </div>
    </div>

    @if ($view === 'month')
        {{-- ===== ปฏิทินรายเดือน ===== --}}
        <div class="calendar-grid">
            @foreach (['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'] as $lbl)
                <div class="calendar-weekday">{{ $lbl }}</div>
            @endforeach

            @foreach ($days as $day)
                @php $dayEvents = $events->get($day->toDateString(), collect()); @endphp
                <div class="calendar-cell {{ $day->month !== $date->month ? 'dim' : '' }} {{ $day->isToday() ? 'today' : '' }}">
                    <a href="{{ $nav($day, 'day') }}" class="calendar-date">{{ $day->day }}</a>
                    <div class="calendar-events">
                        @foreach ($dayEvents->take(4) as $e)
                            <a href="{{ $e['url'] }}" class="calendar-chip {{ $chipClass[$e['type']] }}"
                                title="{{ $typeLabel[$e['type']] }}: {{ $timeRange($e) }} · {{ $e['room']->name }} · {{ $e['title'] }}">
                                <i class="bi {{ $typeIcon[$e['type']] }}"></i> {{ $e['start'] }} {{ $e['room']->name }} · {{ $e['title'] }}
                            </a>
                        @endforeach
                        @if ($dayEvents->count() > 4)
                            <span class="calendar-more" onclick="openDayModal('{{ $day->toDateString() }}')">+{{ $dayEvents->count() - 4 }}
                                เพิ่มเติม</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @elseif ($view === 'week')
        {{-- ===== ปฏิทินรายสัปดาห์ ===== --}}
        <div class="week-grid">
            @foreach ($days as $day)
                <div class="week-col">
                    <a href="{{ $nav($day, 'day') }}" class="d-block text-decoration-none" style="color:inherit">
                        <div class="week-col-header {{ $day->isToday() ? 'today' : '' }}">
                            <div class="dow">{{ $day->translatedFormat('D') }}</div>
                            <div class="dnum">{{ $day->day }}</div>
                        </div>
                    </a>
                    <div class="week-col-body">
                        @forelse ($events->get($day->toDateString(), collect()) as $e)
                            <a href="{{ $e['url'] }}" class="week-event {{ $chipClass[$e['type']] }}">
                                <div class="t">{{ $timeRange($e) }}</div>
                                <div><i class="bi bi-door-open"></i> {{ $e['room']->name }}</div>
                                <div><i class="bi {{ $typeIcon[$e['type']] }}"></i> {{ $e['title'] }}</div>
                                <div class="text-muted">{{ $e['teacher'] ?? '-' }}</div>
                            </a>
                        @empty
                            <div class="text-muted small text-center py-2">ว่าง</div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- ===== รายวัน ===== --}}
        @php $dayEvents = $events->get($date->toDateString(), collect()); @endphp
        <div class="form-section">
            <div class="form-section-title" style="border-bottom:0; margin-bottom:.5rem; padding-bottom:0;">
                <div class="icon-badge"><i class="bi bi-calendar-day"></i></div>
                {{ $date->translatedFormat('l d/m/Y') }}
            </div>
            @forelse ($dayEvents as $e)
                <div class="schedule-row">
                    <div class="schedule-time"><i class="bi bi-clock"></i> {{ $timeRange($e, ' - ') }}</div>
                    <div class="schedule-info">
                        <div class="main"><i class="bi bi-door-open"></i> {{ $e['room']->name }} — {{ $e['title'] }}</div>
                        <div class="meta">
                            <i class="bi bi-person-badge"></i> {{ $e['teacher'] ?? 'ไม่ระบุอาจารย์' }}
                            @if ($e['course'])
                                · {{ $e['course'] }}
                            @endif
                        </div>
                    </div>
                    <span class="badge {{ ['class' => 'text-bg-primary', 'trial' => 'text-bg-warning', 'booking' => 'text-bg-success'][$e['type']] }}">{{ $typeLabel[$e['type']] }}</span>
                    <a href="{{ $e['url'] }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                </div>
            @empty
                <div class="text-center text-muted py-3">ไม่มีการใช้ห้องในวันนี้</div>
            @endforelse
        </div>
    @endif

    {{-- ===== Modal แสดงรายการทั้งหมดของวันที่คลิก "+N เพิ่มเติม" (เฉพาะมุมมองรายเดือน) ===== --}}
    <div class="day-modal" id="dayModal" onclick="if(event.target===this) closeDayModal()">
        <div class="day-modal-box">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" id="dayModalTitle" style="font-family:'Prompt',sans-serif;"></h6>
                <button class="btn btn-sm btn-outline-secondary" onclick="closeDayModal()"><i class="bi bi-x-lg"></i></button>
            </div>
            <div id="dayModalBody"></div>
        </div>
    </div>

    <script id="calendarEventsData" type="application/json">
{!! $events->map(fn($list) => $list->map(fn($e) => [
    'time' => $timeRange($e),
    'room' => $e['room']->name,
    'title' => $e['title'],
    'type' => $typeLabel[$e['type']],
    'teacher' => $e['teacher'] ?? '-',
    'url' => $e['url'],
])->values())->toJson(JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
</script>

    <script>
        const calendarEventsData = JSON.parse(document.getElementById('calendarEventsData').textContent);
        const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c]));

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
        <a href="${esc(ev.url)}" class="d-block text-decoration-none border-bottom py-2">
            <div class="fw-semibold small" style="color:var(--ink,#1c1a17);">${esc(ev.time)} · ${esc(ev.room)} · ${esc(ev.title)}</div>
            <div class="text-muted small">${esc(ev.type)} · ${esc(ev.teacher)}</div>
        </a>
    `).join('') || '<div class="text-muted small text-center py-3">ไม่มีการใช้ห้อง</div>';
            document.getElementById('dayModal').classList.add('show');
        }

        function closeDayModal() {
            document.getElementById('dayModal').classList.remove('show');
        }
    </script>
@endsection

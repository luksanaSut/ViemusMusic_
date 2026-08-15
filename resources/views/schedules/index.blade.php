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
    </style>

    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> ตารางเรียน</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h1 class="page-title">ระบบจัดตารางเรียน</h1>
        <a href="{{ route('schedules.create') }}" class="btn btn-accent"><i class="bi bi-calendar-plus"></i>
            เพิ่มตารางเรียน</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small">มุมมอง</label>
                    <select name="view" class="form-select">
                        <option value="day" @selected($view == 'day')>รายวัน</option>
                        <option value="week" @selected($view == 'week')>รายสัปดาห์</option>
                        <option value="month" @selected($view == 'month')>รายเดือน</option>
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

    <p class="text-muted">ช่วงวันที่: {{ $from->format('d/m/Y') }} - {{ $to->format('d/m/Y') }}</p>

    @forelse($schedules as $dateKey => $daySchedules)
        <div class="form-section">
            <div class="form-section-title" style="border-bottom:0; margin-bottom:.5rem; padding-bottom:0;">
                <div class="icon-badge"><i class="bi bi-calendar-day"></i></div>
                {{ \Carbon\Carbon::parse($dateKey)->translatedFormat('l d/m/Y') }}
            </div>
            @foreach ($daySchedules as $s)
                <div class="schedule-row">
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
@endsection

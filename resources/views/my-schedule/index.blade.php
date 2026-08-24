@extends('layouts.app')
@section('title', 'ตารางเรียนของฉัน')

@section('content')
    <style>
        .day-group {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.2rem 1.4rem;
            margin-bottom: 1.1rem;
        }

        .day-title {
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            margin-bottom: .8rem;
        }

        .schedule-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: .7rem 0;
            border-bottom: 1px solid #f0efec;
        }

        .schedule-row:last-child {
            border-bottom: 0;
        }

        .schedule-time {
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            min-width: 100px;
        }

        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
        }

        .empty-state .icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent, #1f3350);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1rem;
        }
    </style>

    <div class="breadcrumb-sm">การเรียนของฉัน <i class="bi bi-chevron-right small"></i> ตารางเรียนของฉัน</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title mb-0">ตารางเรียนของฉัน</h1>
            <div class="page-sub">{{ $dateFrom->format('d/m/Y') }} - {{ $dateTo->format('d/m/Y') }}</div>
        </div>
        <a href="{{ route('enrollments.my-index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-journal-bookmark"></i> ดูคอร์สเรียนของฉัน
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small">จากวันที่</label>
                    <input type="date" name="date_from" value="{{ request('date_from', $dateFrom->toDateString()) }}"
                        class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">ถึงวันที่</label>
                    <input type="date" name="date_to" value="{{ request('date_to', $dateTo->toDateString()) }}"
                        class="form-control">
                </div>
                @if ($students->count() > 1)
                    <div class="col-md-3">
                        <label class="form-label small">นักเรียน</label>
                        <select name="student_id" class="form-select">
                            <option value="">ทุกคน</option>
                            @foreach ($students as $s)
                                <option value="{{ $s->id }}" @selected(request('student_id') == $s->id)>{{ $s->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-3">
                    <button class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i> ดูตารางเรียน</button>
                </div>
            </form>
        </div>
    </div>

    @if ($students->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="icon-wrap"><i class="bi bi-calendar-week"></i></div>
                <h5 class="fw-bold mb-1">ไม่พบข้อมูลนักเรียน</h5>
                <p class="text-muted small mb-0">บัญชีนี้ยังไม่ได้ผูกกับข้อมูลนักเรียน</p>
            </div>
        </div>
    @elseif ($schedules->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="icon-wrap"><i class="bi bi-calendar-x"></i></div>
                <h5 class="fw-bold mb-1">ไม่มีตารางเรียนในช่วงที่เลือก</h5>
                <p class="text-muted small mb-0">ลองเปลี่ยนช่วงวันที่ดู</p>
            </div>
        </div>
    @else
        @foreach ($schedules as $date => $daySchedules)
            <div class="day-group">
                <div class="day-title">{{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y (D)') }}</div>
                @foreach ($daySchedules as $schedule)
                    <div class="schedule-row">
                        <div class="schedule-time">
                            {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">
                                {{ $schedule->enrollment->course->name ?? 'ไม่พบข้อมูลคอร์ส' }}
                                @if ($students->count() > 1)
                                    <span class="text-muted small">— {{ $schedule->enrollment->student->full_name ?? '-' }}</span>
                                @endif
                            </div>
                            <div class="text-muted small mt-1">
                                @if ($schedule->teacher)
                                    อาจารย์{{ $schedule->teacher->full_name }}
                                @endif
                                @if ($schedule->room)
                                    · ห้อง {{ $schedule->room->name }}
                                @endif
                                · {{ $schedule->deliveryModeLabel() }}
                            </div>
                        </div>
                        <span class="badge {{ $schedule->statusBadgeClass() }}">{{ $schedule->statusLabel() }}</span>
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
@endsection

@extends('layouts.app')
@section('title', 'คอร์สเรียนของฉัน')

@section('content')
    <style>
        .stat-tile {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.2rem 1.4rem;
            height: 100%;
        }

        .student-card {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.4rem 1.6rem;
            margin-bottom: 1.25rem;
        }

        .course-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: .8rem 0;
            border-bottom: 1px solid #f0efec;
        }

        .course-row:last-child {
            border-bottom: 0;
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

    <div class="breadcrumb-sm">การเรียนของฉัน <i class="bi bi-chevron-right small"></i> คอร์สเรียนของฉัน</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title mb-0">คอร์สเรียนของฉัน</h1>
            <div class="page-sub">รายการคอร์สเรียนทั้งหมดที่เคยและกำลังเรียนอยู่</div>
        </div>
        <a href="{{ route('schedules.my-index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-calendar-week"></i> ดูตารางเรียนของฉัน
        </a>
    </div>

    @if ($students->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="icon-wrap"><i class="bi bi-journal-bookmark"></i></div>
                <h5 class="fw-bold mb-1">ไม่พบข้อมูลนักเรียน</h5>
                <p class="text-muted small mb-0">บัญชีนี้ยังไม่ได้ผูกกับข้อมูลนักเรียน</p>
            </div>
        </div>
    @else
        @foreach ($students as $student)
            @php
                $studentEnrollments = $enrollments->get($student->id, collect());
                $activeCount = $studentEnrollments->where('status', 'active')->count();
            @endphp
            <div class="student-card">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <div class="fw-bold fs-5" style="font-family:'Prompt',sans-serif;">{{ $student->full_name }}</div>
                        <div class="text-muted small">{{ $student->student_code }}</div>
                    </div>
                    <div class="stat-tile" style="min-width:160px;">
                        <div class="text-muted small mb-1">คอร์สที่กำลังเรียน</div>
                        <div class="fs-4 fw-bold" style="font-family:'Prompt',sans-serif;">{{ $activeCount }} คอร์ส</div>
                    </div>
                </div>

                @forelse ($studentEnrollments as $enrollment)
                    <div class="course-row">
                        <div>
                            <div class="fw-semibold">
                                {{ $enrollment->course->name ?? 'ไม่พบข้อมูลคอร์ส' }}
                                <span class="badge {{ $enrollment->statusBadgeClass() }} ms-1">{{ $enrollment->statusLabel() }}</span>
                            </div>
                            <div class="text-muted small mt-1">
                                {{ $enrollment->course->course_code ?? '-' }}
                                @if ($enrollment->teacher)
                                    · อาจารย์{{ $enrollment->teacher->full_name }}
                                @endif
                                @if ($enrollment->remainingSessions() !== null)
                                    · เหลือ {{ $enrollment->remainingSessions() }} ครั้ง
                                @endif
                            </div>
                            <div class="text-muted small mt-1">
                                เริ่มเรียน {{ optional($enrollment->enrolled_date)->format('d/m/Y') ?: '-' }}
                                @if ($enrollment->expected_end_date)
                                    · คาดว่าจบ {{ $enrollment->expected_end_date->format('d/m/Y') }}
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted small text-center py-3">ยังไม่มีคอร์สเรียน</div>
                @endforelse
            </div>
        @endforeach
    @endif
@endsection

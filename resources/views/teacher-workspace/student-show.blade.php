@extends('layouts.app')
@section('title', 'รายละเอียดนักเรียน')
@section('content')
<div class="d-flex justify-content-between align-items-start gap-2 mb-3">
    <div>
        <h1 class="page-title mb-1"><i class="bi bi-person-lines-fill"></i> {{ $student->full_name }}</h1>
        <p class="text-muted small mb-0">{{ $student->student_code }}@if($student->nickname) · ชื่อเล่น {{ $student->nickname }}@endif</p>
    </div>
    <a href="{{ route('teacher.students') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> กลับหน้านักเรียนของฉัน</a>
</div>

<p class="text-muted small mb-3">คอร์สที่เรียนกับอาจารย์ {{ $teacher->full_name }} ทั้งหมด {{ $enrollments->count() }} คอร์ส</p>

@foreach($enrollments as $e)
@php
    $courseSchedules = $schedules->get($e->id, collect());
    $upcoming = $courseSchedules->where('status', 'scheduled')->filter(fn($s) => $s->schedule_date->greaterThanOrEqualTo(now()->startOfDay()))->take(5);
    $recent = $courseSchedules->where('status', '!=', 'scheduled')->sortByDesc('schedule_date')->take(5);
    $studyDays = $courseSchedules->where('status', 'scheduled')->map(fn($s) => $s->schedule_date->dayOfWeek)->unique()->sort();
    $dayLabels = \App\Models\TeacherAvailability::dayLabels();
    $total = $e->course->total_sessions;
    $progress = $total ? min(100, round($e->sessions_used / $total * 100)) : null;
@endphp
<div class="card mb-3" style="border-radius:15px">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-1">{{ $e->course->name ?? '-' }}</h5>
                <span class="badge {{ $e->statusBadgeClass() }}">{{ $e->statusLabel() }}</span>
            </div>
            <div class="text-end small text-muted">
                วันที่เรียนประจำ<br>
                @if($studyDays->isEmpty())
                    <span class="text-muted">ยังไม่มีนัดหมาย</span>
                @else
                    @foreach($studyDays as $d)<span class="badge text-bg-light border me-1">{{ $dayLabels[$d] }}</span>@endforeach
                @endif
            </div>
        </div>
        <hr>
        <div class="row small g-2 mb-3">
            <div class="col-6 col-md-3"><span class="text-muted d-block">คาบคงเหลือ</span><strong>{{ $e->remainingSessions() ?? 'ไม่จำกัด' }}</strong></div>
            <div class="col-6 col-md-3"><span class="text-muted d-block">ความก้าวหน้า</span><strong>{{ $progress !== null ? $progress.'%' : 'เรียนต่อเนื่อง' }}</strong></div>
            <div class="col-6 col-md-3"><span class="text-muted d-block">ผลประเมินล่าสุด</span><strong>{{ $e->courseEvaluation ? $e->courseEvaluation->averageScore().'/5' : 'ยังไม่มี' }}</strong></div>
            <div class="col-6 col-md-3"><span class="text-muted d-block">ประวัติการลา</span><strong>{{ $e->leaves_count }} ครั้ง</strong></div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="fw-semibold small mb-2"><i class="bi bi-calendar-event"></i> คาบที่จะมาถึง</div>
                @forelse($upcoming as $s)
                <div class="d-flex justify-content-between align-items-center small py-1 border-bottom">
                    <span>{{ $s->schedule_date->format('d/m/Y') }} ({{ $dayLabels[$s->schedule_date->dayOfWeek] }}) · {{ $s->start_time }}-{{ $s->end_time }}</span>
                    <a href="{{ route('teaching-logs.show', $s) }}" class="btn btn-sm btn-outline-secondary py-0">เช็คชื่อ</a>
                </div>
                @empty
                <div class="text-muted small">ยังไม่มีนัดหมายคาบถัดไป</div>
                @endforelse
            </div>
            <div class="col-md-6">
                <div class="fw-semibold small mb-2"><i class="bi bi-clock-history"></i> ประวัติล่าสุด</div>
                @forelse($recent as $s)
                <div class="d-flex justify-content-between align-items-center small py-1 border-bottom">
                    <span>{{ $s->schedule_date->format('d/m/Y') }} ({{ $dayLabels[$s->schedule_date->dayOfWeek] }}) · {{ $s->start_time }}-{{ $s->end_time }}</span>
                    <span class="badge {{ $s->statusBadgeClass() }}">{{ $s->statusLabel() }}</span>
                </div>
                @empty
                <div class="text-muted small">ยังไม่มีประวัติการเรียน</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

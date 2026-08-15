@extends('layouts.app')
@section('title', 'แดชบอร์ดของฉัน')

@section('content')
    <h1 class="page-title mb-3">สวัสดี, {{ $student->nickname ?: $student->full_name }} 👋</h1>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card" style="border-radius:16px;">
                <div class="card-body">
                    <h6 class="fw-bold" style="font-family:'Prompt',sans-serif;"><i class="bi bi-journal-bookmark"></i>
                        คอร์สที่กำลังเรียน</h6>
                    @forelse($student->enrollments->where('status','active') as $e)
                        <div class="border-bottom py-2 small">{{ $e->course->name ?? '-' }}</div>
                    @empty
                        <p class="text-muted small mb-0">ยังไม่มีคอร์สที่กำลังเรียน</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card" style="border-radius:16px;">
                <div class="card-body">
                    <h6 class="fw-bold" style="font-family:'Prompt',sans-serif;"><i class="bi bi-calendar-week"></i>
                        ตารางเรียนที่จะถึง</h6>
                    @forelse($scheduleUpcoming as $s)
                        <div class="d-flex justify-content-between border-bottom py-2 small">
                            <span>{{ $s->schedule_date->format('d/m/Y') }} {{ $s->start_time }}-{{ $s->end_time }}</span>
                            <span class="text-muted">{{ $s->teacher->full_name ?? 'ไม่ระบุอาจารย์' }}</span>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">ยังไม่มีตารางเรียนที่กำลังจะถึง</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection

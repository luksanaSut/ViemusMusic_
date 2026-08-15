@extends('layouts.app')
@section('title', 'แดชบอร์ดของฉัน')

@section('content')
    <h1 class="page-title mb-3">สวัสดี, {{ $teacher->nickname ?: $teacher->full_name }} 👋</h1>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card" style="border-radius:16px;">
                <div class="card-body">
                    <h6 class="fw-bold" style="font-family:'Prompt',sans-serif;"><i class="bi bi-calendar-week"></i>
                        ตารางสอนที่จะถึง</h6>
                    @forelse($scheduleUpcoming as $s)
                        <div class="d-flex justify-content-between border-bottom py-2 small">
                            <span>{{ $s->schedule_date->format('d/m/Y') }} {{ $s->start_time }}-{{ $s->end_time }}</span>
                            <span class="text-muted">{{ $s->enrollment->student->full_name ?? '-' }} ·
                                {{ $s->enrollment->course->name ?? '-' }}</span>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">ยังไม่มีตารางสอนที่กำลังจะถึง</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card" style="border-radius:16px;">
                <div class="card-body">
                    <h6 class="fw-bold" style="font-family:'Prompt',sans-serif;"><i class="bi bi-info-circle"></i>
                        ข้อมูลของฉัน</h6>
                    <p class="small mb-1">ประเภทการจ้าง: {{ $teacher->employmentTypeLabel() }}</p>
                    <p class="small mb-1">เครื่องดนตรี: {{ $teacher->instruments->pluck('name')->join(', ') ?: '-' }}</p>
                    <p class="small mb-0">สาขา: {{ $teacher->branch ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    @php
        // คำขอสอนชดเชยที่มอบหมายให้อาจารย์คนนี้ และยังรอให้คุณอนุมัติ (instructor_approval_status ยังเป็น pending)
        $pendingMakeups = \App\Models\MakeupRequest::where('teacher_id', $teacher->id)
            ->where('instructor_approval_status', 'pending')
            ->with('student')
            ->orderBy('makeup_date')
            ->get();
    @endphp

    @if ($pendingMakeups->count())
        <div class="card mt-3" style="border-radius:16px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0" style="font-family:'Prompt',sans-serif;"><i class="bi bi-arrow-repeat"></i>
                        คำขอสอนชดเชยรออนุมัติจากคุณ ({{ $pendingMakeups->count() }})</h6>
                    <a href="{{ route('makeup-requests.my-index') }}" class="small">ดูทั้งหมด</a>
                </div>
                @foreach ($pendingMakeups as $m)
                    <div class="d-flex justify-content-between border-bottom py-2 small">
                        <span>{{ $m->student->full_name ?? '-' }} — {{ $m->makeup_date->format('d/m/Y') }}
                            {{ $m->start_time }}-{{ $m->end_time }}</span>
                        <a href="{{ route('makeup-requests.show', $m) }}"
                            class="btn btn-sm btn-outline-primary">ตรวจสอบ</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card mt-3" style="border-radius:16px; background:var(--accent-soft); border:none;">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-bold" style="font-family:'Prompt',sans-serif;"><i class="bi bi-calendar-x"></i>
                    ต้องการลาหยุดสอน?</div>
                <div class="small text-muted">แจ้งลาหยุดสอนได้ที่เมนู "แจ้งลาหยุดสอน"</div>
            </div>
            <a href="{{ route('teacher-leaves.my-index') }}" class="btn btn-accent btn-sm">ไปแจ้งลา</a>
        </div>
    </div>
@endsection

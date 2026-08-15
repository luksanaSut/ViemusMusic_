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
@endsection

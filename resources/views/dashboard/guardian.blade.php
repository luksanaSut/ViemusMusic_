@extends('layouts.app')
@section('title', 'แดชบอร์ดของฉัน')

@section('content')
    <h1 class="page-title mb-3">สวัสดี, {{ $guardian->full_name }} 👋</h1>

    <div class="row g-3">
        @forelse($guardian->students as $s)
            <div class="col-md-6">
                <div class="card" style="border-radius:16px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0" style="font-family:'Prompt',sans-serif;">{{ $s->full_name }}</h6>
                            @if ($s->hasOverduePayment())
                                <span class="badge text-bg-danger">มีคอร์สค้างชำระ</span>
                            @endif
                        </div>
                        <p class="small text-muted mb-0 mt-1">คอร์สที่กำลังเรียน:
                            {{ $s->enrollments->where('status', 'active')->pluck('course.name')->filter()->join(', ') ?: '-' }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">ยังไม่มีข้อมูลนักเรียนที่ผูกกับบัญชีนี้</p>
            </div>
        @endforelse
    </div>

    @if ($guardian->students->isNotEmpty())
        <div class="card mt-3" style="border-radius:16px; background:var(--accent-soft); border:none;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-bold" style="font-family:'Prompt',sans-serif;"><i class="bi bi-calendar-x"></i>
                        ต้องการลาเรียนให้บุตรหลาน?</div>
                    <div class="small text-muted">แจ้งลาและเสนอวันเรียนชดเชยได้ที่เมนู "แจ้งลาเรียน"</div>
                </div>
                <a href="{{ route('leaves.index') }}" class="btn btn-accent btn-sm">ไปที่หน้าแจ้งลา</a>
            </div>
        </div>
    @endif
@endsection

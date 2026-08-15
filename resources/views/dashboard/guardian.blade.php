@extends('layouts.app')
@section('title', 'แดชบอร์ดของฉัน')

@section('content')
    <h1 class="page-title mb-3">สวัสดี, {{ $guardian->full_name }} 👋</h1>

    <div class="row g-3">
        @forelse($guardian->students as $s)
            <div class="col-md-6">
                <div class="card" style="border-radius:16px;">
                    <div class="card-body">
                        <h6 class="fw-bold" style="font-family:'Prompt',sans-serif;">{{ $s->full_name }}</h6>
                        <p class="small text-muted mb-2">คอร์สที่กำลังเรียน:
                            {{ $s->enrollments->where('status', 'active')->pluck('course.name')->filter()->join(', ') ?: '-' }}
                        </p>
                        @if ($s->hasOverduePayment())
                            <span class="badge text-bg-danger">มีคอร์สค้างชำระ</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">ยังไม่มีข้อมูลนักเรียนที่ผูกกับบัญชีนี้</p>
            </div>
        @endforelse
    </div>
@endsection

@extends('layouts.app')
@section('title', $trialLead->student_name)
@section('content')
@php
    $trialStartsAt = $trialLead->trial_date && $trialLead->trial_start_time
        ? \Carbon\Carbon::parse($trialLead->trial_date->toDateString().' '.$trialLead->trial_start_time)
        : null;
    $isTerminal = in_array($trialLead->confirmation_status, ['cancelled','no_show']);
    $canProcess = $trialStartsAt && now()->gte($trialStartsAt) && !$isTerminal && !in_array($trialLead->status, ['converted','lost']);
@endphp
<style>
    .form-section{background:#fff;border:1px solid var(--border,#e4e1dc);border-radius:16px;padding:1.4rem 1.6rem;margin-bottom:1.25rem;box-shadow:0 1px 2px rgba(28,26,23,.04)}
    .form-section-title{display:flex;align-items:center;gap:.7rem;font-weight:700;font-size:1.02rem;margin-bottom:1.2rem;padding-bottom:.9rem;border-bottom:1px solid var(--border,#e4e1dc);font-family:'Prompt',sans-serif}
    .icon-badge{width:36px;height:36px;border-radius:10px;background:var(--accent-soft,#e7ebf1);color:var(--accent-dark,#13233a);display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .info-row{display:flex;gap:.6rem;padding:.4rem 0;border-bottom:1px dashed var(--border,#e4e1dc)}
    .info-row .label{font-size:.75rem;color:var(--muted,#6b655e);min-width:150px}
    .info-row .value{font-weight:500}
</style>

<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
    <div>
        <div class="breadcrumb-sm"><a href="{{ route('trial-leads.my-index') }}" class="text-decoration-none">นัดทดลองของฉัน</a> <i class="bi bi-chevron-right small"></i> {{ $trialLead->lead_no }}</div>
        <h1 class="page-title">{{ $trialLead->student_name }}</h1>
        <div class="page-sub">{{ $trialLead->guardian_name ?: 'ไม่ระบุผู้ปกครอง' }} · {{ $trialLead->phone }}</div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="badge {{ $trialLead->confirmationStatusBadgeClass() }} fs-6">{{ $trialLead->confirmationStatusLabel() }}</span>
        @if($trialLead->phone)<a href="tel:{{ $trialLead->phone }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-telephone"></i> โทร</a>@endif
        <a href="{{ route('trial-leads.my-index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> กลับ</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="form-section h-100">
            <div class="form-section-title"><div class="icon-badge"><i class="bi bi-person-vcard"></i></div> ข้อมูลผู้สนใจ</div>
            <div class="info-row"><div class="label">วิชา/เครื่องดนตรีที่สนใจ</div><div class="value">{{ $trialLead->course->name ?? $trialLead->interest ?? '-' }}</div></div>
            <div class="info-row"><div class="label">วันที่ทดลอง</div><div class="value">{{ $trialLead->trial_date?->format('d/m/Y') ?? 'ยังไม่นัด' }}</div></div>
            <div class="info-row"><div class="label">เวลา</div><div class="value">{{ $trialLead->trial_start_time ? substr($trialLead->trial_start_time,0,5).'–'.substr($trialLead->trial_end_time,0,5) : '-' }}</div></div>
            <div class="info-row"><div class="label">รูปแบบ</div><div class="value">{{ $trialLead->delivery_mode === 'online' ? 'ออนไลน์' : 'ที่โรงเรียน' }}{{ $trialLead->room ? ' · '.$trialLead->room->name : '' }}</div></div>
            <div class="info-row"><div class="label">ช่องทางที่รู้จัก</div><div class="value">{{ $trialLead->source ?: '-' }}</div></div>
            @if($trialLead->notes)<div class="info-row"><div class="label">หมายเหตุ</div><div class="value">{{ $trialLead->notes }}</div></div>@endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-section h-100">
            <div class="form-section-title"><div class="icon-badge"><i class="bi bi-signpost-split"></i></div> สถานะการคอนเฟิร์ม</div>
            <div class="info-row"><div class="label">ผู้ปกครองคอนเฟิร์ม</div><div class="value">{{ $trialLead->guardian_confirmed_at ? $trialLead->guardian_confirmed_at->format('d/m/Y H:i').' โดย '.$trialLead->guardian_confirmed_by : 'ยังไม่คอนเฟิร์ม' }}</div></div>
            <div class="info-row"><div class="label">ครูคอนเฟิร์ม</div><div class="value">{{ $trialLead->teacher_confirmed_at ? $trialLead->teacher_confirmed_at->format('d/m/Y H:i').' โดย '.$trialLead->teacher_confirmed_by : 'ยังไม่คอนเฟิร์ม' }}</div></div>
            @if($trialLead->confirmation_notes)<div class="info-row"><div class="label">หมายเหตุ</div><div class="value">{{ $trialLead->confirmation_notes }}</div></div>@endif

            @if(!$trialLead->teacher_confirmed_at && !in_array($trialLead->confirmation_status, ['cancelled','no_show']))
            <form method="POST" action="{{ route('trial-leads.teacher-confirm',$trialLead) }}" class="mt-3">@csrf
                <button class="btn btn-accent w-100"><i class="bi bi-check-lg"></i> ยืนยันนัดทดลอง</button>
            </form>
            @endif

            @if(!$trialLead->checked_in_at && $canProcess && !$trialLead->trial_result)
            <form method="POST" action="{{ route('trial-leads.check-in',$trialLead) }}" class="mt-2">@csrf
                <button class="btn btn-outline-success w-100"><i class="bi bi-person-check"></i> เช็กอิน (มาทดลองแล้ว)</button>
            </form>
            @endif

            @if(!$trialLead->checked_in_at && $canProcess && !$trialLead->trial_result)
            <form method="POST" action="{{ route('trial-leads.submit-result',$trialLead) }}" class="mt-2"
                onsubmit="return confirm('ยืนยันว่าผู้เรียนไม่มาตามนัดทดลอง?')">@csrf
                <input type="hidden" name="trial_result" value="no_show">
                <button class="btn btn-outline-danger w-100"><i class="bi bi-person-x"></i> ไม่มาตามนัด</button>
            </form>
            @endif

            @if($trialStartsAt && now()->lt($trialStartsAt) && !$isTerminal)
                <div class="alert alert-info py-2 mt-3 mb-0"><i class="bi bi-clock"></i> เช็กอินและบันทึกผลได้ตั้งแต่ {{ $trialStartsAt->format('d/m/Y H:i') }}</div>
            @endif

            @if($trialLead->checked_in_at)
                <div class="alert alert-success py-2 mt-3 mb-0"><i class="bi bi-check-circle"></i> เช็กอินแล้วเมื่อ {{ $trialLead->checked_in_at->format('d/m/Y H:i') }}</div>
            @endif
            @if($trialLead->result_recorded_at)
                <div class="alert alert-light border py-2 mt-2 mb-0"><i class="bi bi-clipboard-check"></i> บันทึกผล {{ $trialLead->resultLabel() }} เมื่อ {{ $trialLead->result_recorded_at->format('d/m/Y H:i') }} โดย {{ $trialLead->result_recorded_by }}</div>
            @endif
        </div>
    </div>
</div>

@if($trialLead->checked_in_at)
<div class="form-section">
    <div class="form-section-title"><div class="icon-badge"><i class="bi bi-clipboard2-check"></i></div> บันทึกผลทดลองเรียนและคำแนะนำคอร์ส</div>
    <form method="POST" action="{{ route('trial-leads.submit-result',$trialLead) }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">ผลทดลองเรียน *</label>
                <select name="trial_result" class="form-select" required>
                    <option value="">เลือกผล</option>
                    @foreach(['interested'=>'สนใจสมัคร','considering'=>'ขอพิจารณา','not_interested'=>'ไม่สนใจ'] as $value=>$label)
                        <option value="{{ $value }}" @selected(old('trial_result',$trialLead->trial_result)===$value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">ความคิดเห็นและคำแนะนำคอร์ส</label>
                <textarea name="teacher_feedback" class="form-control" rows="3" placeholder="เช่น ระดับพื้นฐาน แนะนำคอร์สที่เหมาะสม">{{ old('teacher_feedback',$trialLead->teacher_feedback) }}</textarea>
            </div>
        </div>
        <button class="btn btn-accent mt-3"><i class="bi bi-save"></i> บันทึกผลทดลองเรียน</button>
    </form>
</div>
@endif
@endsection

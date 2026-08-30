@extends('layouts.app')
@section('title',$trialLead->student_name)
@section('content')
<style>
    .stat-card {
        background: #fff;
        border: 1px solid var(--border, #e4e1dc);
        border-radius: 14px;
        padding: 1rem 1.2rem;
        display: flex;
        align-items: center;
        gap: .9rem;
        height: 100%;
        box-shadow: 0 1px 2px rgba(28, 26, 23, .04);
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .step-banner {
        background: var(--accent, #1f3350);
        color: #fff;
        border-radius: 12px;
        padding: .9rem 1.2rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .7rem;
        flex-wrap: wrap;
    }

    .pipeline-btn.active {
        pointer-events: none;
    }

    .empty-state {
        text-align: center;
        padding: 2.2rem 1rem;
        color: var(--muted, #6b655e);
    }

    .empty-state i {
        font-size: 1.8rem;
        opacity: .5;
        display: block;
        margin-bottom: .5rem;
    }

    .icon-badge {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--accent-soft, #e7ebf1);
        color: var(--accent-dark, #13233a);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>

@php
    $paidAmount = $trialLead->confirmedPaidAmount();
    $outstanding = max(0, (float) $trialLead->trial_fee - $paidAmount);
    $isFollowUpDue = $trialLead->next_follow_up_date && $trialLead->next_follow_up_date->isPast() && !in_array($trialLead->status, ['converted', 'lost']);
    $readyToConvert = $trialLead->status !== 'converted' && (in_array($trialLead->status, ['completed', 'contacted', 'scheduled']) || $trialLead->trial_result === 'interested');
@endphp

{{-- ===== หัวเรื่อง ===== --}}
<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
    <div>
        <div class="breadcrumb-sm"><a href="{{ route('trial-leads.index') }}" class="text-decoration-none">ผู้สนใจ</a> <i class="bi bi-chevron-right small"></i> {{ $trialLead->lead_no }}</div>
        <h1 class="page-title">{{ $trialLead->student_name }}</h1>
        <div class="page-sub">{{ $trialLead->guardian_name ?: 'ไม่ระบุผู้ปกครอง' }} · {{ $trialLead->phone }}</div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="badge {{ $trialLead->statusBadgeClass() }} fs-6">{{ $trialLead->statusLabel() }}</span>
        @if($trialLead->phone)<a href="tel:{{ $trialLead->phone }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-telephone"></i> โทร</a>@endif
        <a href="{{ route('trial-leads.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> กลับ</a>
    </div>
</div>

@if($isFollowUpDue)
<div class="alert alert-warning d-flex align-items-center gap-2"><i class="bi bi-alarm fs-5"></i> ถึงกำหนดติดตามผู้สนใจรายนี้แล้ว (นัดติดตามวันที่ {{ $trialLead->next_follow_up_date->format('d/m/Y') }})</div>
@endif

@if($trialLead->status==='converted')
    <div class="alert alert-success"><i class="bi bi-check-circle"></i> แปลงเป็นนักเรียนแล้ว @if($trialLead->convertedStudent)<a href="{{ route('students.show',$trialLead->convertedStudent) }}" class="alert-link">เปิดโปรไฟล์นักเรียน</a>@endif</div>
@else
    @if($readyToConvert)
    <div class="step-banner">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-person-check fs-4"></i>
            <div><strong>พร้อมสมัครเรียนจริงแล้ว?</strong> — ระบบจะสร้างโปรไฟล์นักเรียน จากนั้นสามารถเปิดรายการขายคอร์สได้</div>
        </div>
        <form method="POST" action="{{ route('trial-leads.convert',$trialLead) }}" onsubmit="return confirm('ยืนยันการสร้างนักเรียนจากผู้สนใจรายนี้?')">@csrf<button class="btn btn-light"><i class="bi bi-person-check"></i> แปลงเป็นนักเรียน</button></form>
    </div>
    @endif

    {{-- ===== สรุปข้อมูล ===== --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--accent-soft,#e7ebf1);color:var(--accent-dark,#13233a);"><i class="bi bi-cash-coin"></i></div>
                <div><div class="text-muted small">ค่าทดลอง</div><div class="fs-5 fw-bold" style="font-family:'Prompt',sans-serif;">฿{{ number_format($trialLead->trial_fee,2) }}</div></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--success-soft,#e7f2ec);color:var(--success,#2f6f4e);"><i class="bi bi-wallet2"></i></div>
                <div><div class="text-muted small">รับสุทธิแล้ว</div><div class="fs-5 fw-bold text-success" style="font-family:'Prompt',sans-serif;">฿{{ number_format($paidAmount,2) }}</div></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--amber-soft,#f3ece2);color:var(--amber,#8a5a2b);"><i class="bi bi-exclamation-circle"></i></div>
                <div><div class="text-muted small">คงค้าง</div><div class="fs-5 fw-bold {{ $outstanding>0?'text-danger':'' }}" style="font-family:'Prompt',sans-serif;">฿{{ number_format($outstanding,2) }}</div></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--accent-soft,#e7ebf1);color:var(--accent-dark,#13233a);"><i class="bi bi-calendar2-event"></i></div>
                <div><div class="text-muted small">นัดทดลอง</div><div class="fs-6 fw-bold" style="font-family:'Prompt',sans-serif;">
                    @if($trialLead->trial_date){{ $trialLead->trial_date->format('d/m/Y') }} @if($trialLead->trial_start_time)<span class="d-block small fw-normal text-muted">{{ substr($trialLead->trial_start_time,0,5) }}–{{ substr($trialLead->trial_end_time,0,5) }}</span>@endif
                    @else <span class="text-muted">ยังไม่นัด</span> @endif
                </div></div>
            </div>
        </div>
    </div>

    {{-- ===== สถานะคอนเฟิร์มนัดทดลอง ===== --}}
    <div class="form-section">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-clipboard-check text-muted"></i><strong class="small">สถานะคอนเฟิร์มนัดทดลอง</strong>
            <span class="badge {{ $trialLead->confirmationStatusBadgeClass() }} ms-auto">{{ $trialLead->confirmationStatusLabel() }}</span>
        </div>
        <div class="small text-muted mb-2">
            ผู้ปกครอง: {{ $trialLead->guardian_confirmed_at ? 'คอนเฟิร์มแล้ว ('.$trialLead->guardian_confirmed_at->format('d/m/Y H:i').')' : 'ยังไม่คอนเฟิร์ม' }}
            · ครู: {{ $trialLead->teacher_confirmed_at ? 'คอนเฟิร์มแล้ว ('.$trialLead->teacher_confirmed_at->format('d/m/Y H:i').')' : 'ยังไม่คอนเฟิร์ม' }}
        </div>
        <form method="POST" action="{{ route('trial-leads.confirmation-status',$trialLead) }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-md-4">
                <label class="form-label small">อัปเดตสถานะ</label>
                <select name="confirmation_status" class="form-select">
                    @foreach(['pending'=>'รอคอนเฟิร์ม','guardian_confirmed'=>'ผู้ปกครองคอนเฟิร์มแล้ว','unreachable'=>'ติดต่อไม่ได้','reschedule_requested'=>'ขอเลื่อน','cancelled'=>'ยกเลิก','no_show'=>'ไม่มาตามนัด'] as $value=>$label)
                        <option value="{{ $value }}" @selected($trialLead->confirmation_status===$value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label small">หมายเหตุ</label>
                <input name="confirmation_notes" class="form-control" placeholder="เช่น เหตุผลที่ติดต่อไม่ได้/ขอเลื่อน" value="{{ old('confirmation_notes', $trialLead->confirmation_notes) }}">
            </div>
            <div class="col-md-2 d-grid"><button class="btn btn-accent">บันทึก</button></div>
        </form>
    </div>

    {{-- ===== อัปเดตสถานะอย่างรวดเร็ว ===== --}}
    <div class="form-section">
        <div class="d-flex align-items-center gap-2 mb-2"><i class="bi bi-signpost-split text-muted"></i><strong class="small">อัปเดตสถานะอย่างรวดเร็ว</strong></div>
        <div class="d-flex flex-wrap gap-2">
            @foreach(['new'=>'ผู้สนใจใหม่','contacted'=>'ติดต่อแล้ว','scheduled'=>'นัดทดลองแล้ว','completed'=>'ทดลองแล้ว','lost'=>'ไม่ดำเนินการต่อ'] as $value=>$label)
                <button type="button" class="btn btn-sm pipeline-btn {{ $trialLead->status===$value ? 'btn-accent active' : 'btn-outline-secondary' }}"
                    onclick="document.getElementById('leadStatusSelect').value='{{ $value }}'; document.getElementById('leadForm').submit();"
                    {{ $trialLead->status===$value ? 'disabled' : '' }}>{{ $label }}</button>
            @endforeach
        </div>
        <div class="form-text mt-2">คลิกเพื่อบันทึกสถานะทันที (ข้อมูลอื่น ๆ ในฟอร์มด้านล่างจะถูกบันทึกไปพร้อมกัน)</div>
    </div>

    <form id="leadForm" method="POST" action="{{ route('trial-leads.update',$trialLead) }}">@csrf @method('PUT') @include('trial-leads._form')<button class="btn btn-accent mb-3"><i class="bi bi-save"></i> บันทึกการเปลี่ยนแปลง</button></form>
@endif

{{-- ===== การชำระค่าทดลองเรียน ===== --}}
<div class="form-section" style="background:#fff;border:1px solid var(--border,#e4e1dc);border-radius:16px;padding:1.4rem 1.6rem;margin-bottom:1.25rem;">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom:1px solid var(--border,#e4e1dc);">
        <div class="d-flex align-items-center gap-2">
            <div class="icon-badge"><i class="bi bi-cash-coin"></i></div>
            <div><strong style="font-family:'Prompt',sans-serif;">การชำระค่าทดลองเรียน</strong><div class="small text-muted">ค่าทดลอง ฿{{ number_format($trialLead->trial_fee,2) }}</div></div>
        </div>
        <div class="text-end"><div class="small text-muted">รับสุทธิแล้ว</div><strong class="text-success fs-5">฿{{ number_format($paidAmount,2) }}</strong></div>
    </div>

    @if($outstanding > 0)
        <div class="alert alert-warning py-2 mb-3"><i class="bi bi-exclamation-circle"></i> ยังมียอดค้างชำระ ฿{{ number_format($outstanding,2) }} — ระบบรับชำระค่าทดลองเรียนตอนลงทะเบียนผู้สนใจใหม่เท่านั้น</div>
    @else
        <div class="alert alert-success py-2 mb-3"><i class="bi bi-check-circle"></i> รับค่าทดลองครบแล้ว</div>
    @endif

    <div class="table-responsive"><table class="table table-sm align-middle mb-0"><thead><tr><th>เลขรายการ/วันที่</th><th>ประเภท</th><th>ช่องทาง</th><th>ยอด</th><th>สถานะ</th><th>หลักฐาน</th><th class="text-end">ดำเนินการ</th></tr></thead><tbody>
    @forelse($trialLead->payments->sortByDesc('transaction_at') as $payment)
        <tr>
            <td><strong>{{ $payment->transaction_no }}</strong><div class="small text-muted">{{ $payment->transaction_at->format('d/m/Y H:i') }}</div></td>
            <td>{{ $payment->type==='refund'?'คืนเงิน':'รับเงิน' }}</td><td>{{ $payment->methodLabel() }}</td>
            <td class="fw-semibold {{ $payment->type==='refund'?'text-danger':'text-success' }}">{{ $payment->type==='refund'?'-':'+' }}฿{{ number_format($payment->amount,2) }}</td>
            <td><span class="badge {{ $payment->status==='confirmed'?'text-bg-success':($payment->status==='pending'?'text-bg-warning':'text-bg-secondary') }}">{{ $payment->statusLabel() }}</span>@if($payment->confirmed_by)<div class="small text-muted">โดย {{ $payment->confirmed_by }}</div>@endif</td>
            <td>@if($payment->proof_path)<a href="{{ route('trial-payments.proof',$payment) }}" class="btn btn-sm btn-light"><i class="bi bi-paperclip"></i> ดู</a>@else - @endif</td>
            <td class="text-end">
                @if($payment->status==='pending')
                    <form method="POST" action="{{ route('trial-payments.confirm',$payment) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success">ยืนยัน</button></form>
                    <form method="POST" action="{{ route('trial-payments.cancel',$payment) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-danger">ยกเลิก</button></form>
                @elseif($payment->type==='payment' && $payment->status==='confirmed')
                    @php $refundable=max(0,(float)$payment->amount-(float)$payment->refunds->where('status','confirmed')->sum('amount')); @endphp
                    @if($refundable>0)<button class="btn btn-sm btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#refund-{{ $payment->id }}">คืนเงิน</button>@endif
                @endif
            </td>
        </tr>
        @if($payment->type==='payment' && $payment->status==='confirmed')
        <tr class="collapse" id="refund-{{ $payment->id }}"><td colspan="7" class="bg-light"><form method="POST" action="{{ route('trial-payments.refund',$payment) }}" class="row g-2 align-items-end">@csrf<div class="col-md-3"><label class="form-label small">ยอดคืน (สูงสุด ฿{{ number_format($refundable,2) }})</label><input type="number" name="amount" min="0.01" max="{{ $refundable }}" step="0.01" class="form-control form-control-sm" required></div><div class="col-md-7"><label class="form-label small">เหตุผล *</label><input name="notes" class="form-control form-control-sm" required></div><div class="col-md-2 d-grid"><button class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการคืนเงิน?')">บันทึกคืนเงิน</button></div></form></td></tr>
        @endif
    @empty
        <tr><td colspan="7"><div class="empty-state"><i class="bi bi-receipt"></i>ยังไม่มีรายการชำระเงิน</div></td></tr>
    @endforelse
    </tbody></table></div>
</div>
@endsection

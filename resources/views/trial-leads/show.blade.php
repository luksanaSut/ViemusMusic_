@extends('layouts.app')
@section('title',$trialLead->student_name)
@section('content')
<style>
    .channel-card { display:block; position:relative; height:100%; border:1.5px solid var(--border,#e4e1dc); border-radius:12px; padding:1.2rem 1rem; text-align:center; cursor:pointer; transition:.15s; background:var(--surface,#fff); }
    .channel-card:hover { border-color:#c9c4bb; box-shadow:0 2px 8px rgba(28,26,23,.06); }
    .channel-card:has(input:checked) { border-color:var(--accent,#1f3350); border-width:2px; background:var(--accent-soft,#e7ebf1); }
    .channel-card:focus-within { outline:2px solid var(--accent,#1f3350); outline-offset:2px; }
    .channel-card input { position:absolute; top:12px; right:12px; accent-color:var(--accent,#1f3350); }
    .channel-card i { font-size:1.8rem; color:var(--accent-dark,#13233a); }
    .channel-card .name { font-weight:700; margin-top:.5rem; font-family:'Prompt',sans-serif; }
    .channel-card .desc { font-size:.78rem; color:var(--muted,#6b655e); }

    .payment-detail-box { border:1px solid var(--border,#e4e1dc); border-radius:12px; padding:1.2rem; }
    .qr-wrap { text-align:center; }
    .qr-wrap img { max-width:220px; border:1px solid var(--border,#e4e1dc); border-radius:10px; padding:8px; background:#fff; }
    .bank-info-row { display:flex; justify-content:space-between; align-items:center; padding:.6rem 0; border-bottom:1px dashed var(--border,#e4e1dc); }
    .bank-info-row .value { font-weight:700; font-family:'Prompt',sans-serif; }
    .copy-btn { font-size:.75rem; padding:.2rem .6rem; }

    .icon-badge {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--accent-soft, #e7ebf1);
        color: var(--accent-dark, #13233a);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .form-section-title {
        display: flex;
        align-items: center;
        gap: .7rem;
        font-weight: 700;
        font-size: 1.02rem;
        margin-bottom: 1.2rem;
        padding-bottom: .9rem;
        border-bottom: 1px solid var(--border, #e4e1dc);
        font-family: 'Prompt', sans-serif;
        color: var(--ink, #1c1a17);
    }

    .price-summary {
        background: var(--accent-soft, #e7ebf1);
        border-radius: 14px;
        padding: 1.2rem;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        font-size: .88rem;
        padding: .3rem 0;
    }

    .price-row.discount {
        color: #2f6f4e;
    }

    .price-row.total {
        font-weight: 700;
        font-size: 1.25rem;
        border-top: 1px solid rgba(19, 35, 58, .15);
        margin-top: .4rem;
        padding-top: .7rem;
        color: var(--accent-dark, #13233a);
    }

    .lead-avatar-lg {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        flex-shrink: 0;
        background: linear-gradient(135deg, var(--accent, #1f3350), var(--accent-dark, #13233a));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Prompt', sans-serif;
        font-weight: 700;
        font-size: 1.3rem;
    }

    .lead-no-pill {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        background: var(--accent-soft, #e7ebf1);
        color: var(--accent-dark, #13233a);
        font-weight: 600;
        font-size: .74rem;
        padding: .15rem .55rem;
        border-radius: 999px;
        letter-spacing: .3px;
    }

    .stat-card {
        background: #fff;
        border: 1px solid var(--border, #e4e1dc);
        border-radius: 14px;
        padding: 1rem 1.2rem;
        display: flex;
        align-items: center;
        gap: .9rem;
        height: 100%;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .step-banner {
        background: var(--accent-soft, #e7ebf1);
        color: var(--accent-dark, #13233a);
        border: 1px solid var(--border, #e4e1dc);
        border-radius: 12px;
        padding: .9rem 1.2rem;
        margin-bottom: 1rem;
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

    .confirm-chip {
        display: flex;
        align-items: center;
        gap: .55rem;
        padding: .6rem .85rem;
        border-radius: 10px;
        background: #f7f5f2;
        border: 1px solid var(--border, #e4e1dc);
        font-size: .82rem;
        flex: 1;
        min-width: 220px;
    }

    .confirm-chip.done {
        background: var(--success-soft, #e7f2ec);
        border-color: transparent;
    }

    .confirm-chip .chip-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: #eceae6;
        color: var(--muted, #6b655e);
    }

    .confirm-chip.done .chip-icon {
        background: var(--success, #2f6f4e);
        color: #fff;
    }

    .confirm-chip .chip-title {
        font-weight: 600;
        color: var(--ink, #1c1a17);
    }

    .badge-pay-status {
        font-weight: 600;
        padding: .38rem .65rem;
        border-radius: 8px;
        font-size: .78rem;
    }

    .badge-pay-status.confirmed { background: var(--success-soft, #e7f2ec); color: var(--success, #2f6f4e); }
    .badge-pay-status.pending { background: var(--amber-soft, #f3ece2); color: var(--amber, #8a5a2b); }
    .badge-pay-status.other { background: #f1efec; color: #6b655e; }

    .payments-table th {
        font-size: .74rem;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: var(--muted, #6b655e);
        font-weight: 600;
        border-bottom-width: 1px;
        white-space: nowrap;
    }
</style>

@php
    $paidAmount = $trialLead->confirmedPaidAmount();
    $outstanding = max(0, (float) $trialLead->trial_fee - $paidAmount);
    $isFollowUpDue = $trialLead->next_follow_up_date && $trialLead->next_follow_up_date->isPast() && !in_array($trialLead->status, ['converted', 'lost']);
    $readyToConvert = $trialLead->status !== 'converted' && (in_array($trialLead->status, ['completed', 'contacted', 'scheduled']) || $trialLead->trial_result === 'interested');
@endphp

{{-- ===== หัวเรื่อง ===== --}}
<div class="breadcrumb-sm mb-2"><a href="{{ route('trial-leads.index') }}" class="text-decoration-none">ผู้สนใจ</a> <i class="bi bi-chevron-right small"></i> {{ $trialLead->lead_no }}</div>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
    <div class="d-flex align-items-center gap-3">
        <div class="lead-avatar-lg">{{ mb_substr($trialLead->student_name, 0, 1) }}</div>
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h1 class="page-title mb-0">{{ $trialLead->student_name }}</h1>
                <span class="lead-no-pill"><i class="bi bi-hash"></i>{{ $trialLead->lead_no }}</span>
            </div>
            <div class="page-sub">{{ $trialLead->guardian_name ?: 'ไม่ระบุผู้ปกครอง' }} · {{ $trialLead->phone }}</div>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="badge {{ $trialLead->statusBadgeClass() }} fs-6">{{ $trialLead->statusLabel() }}</span>
        @if($trialLead->phone)<a href="tel:{{ $trialLead->phone }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-telephone"></i> โทร</a>@endif
        <a href="{{ route('trial-leads.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> กลับ</a>
        <form method="POST" action="{{ route('trial-leads.destroy', $trialLead) }}"
            onsubmit="return confirm('ยืนยันการลบผู้สนใจและนัดทดลองเรียนรายการนี้? เมื่อลบแล้วจะไม่สามารถกู้คืนได้')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> ลบ</button>
        </form>
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
            <i class="bi bi-person-check fs-5"></i>
            <div><strong>พร้อมสมัครเรียนจริงแล้ว?</strong> — ระบบจะสร้างโปรไฟล์นักเรียน จากนั้นสามารถเปิดรายการขายคอร์สได้</div>
        </div>
        <form method="POST" action="{{ route('trial-leads.convert',$trialLead) }}" onsubmit="return confirm('ยืนยันการสร้างนักเรียนจากผู้สนใจรายนี้?')">@csrf<button class="btn btn-accent"><i class="bi bi-person-check"></i> แปลงเป็นนักเรียน</button></form>
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
                <div class="stat-icon" style="background:#ece9f6;color:#4b3f8a;"><i class="bi bi-calendar2-event"></i></div>
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
        <div class="d-flex flex-wrap gap-2 mb-3">
            <div class="confirm-chip {{ $trialLead->guardian_confirmed_at ? 'done' : '' }}">
                <div class="chip-icon"><i class="bi {{ $trialLead->guardian_confirmed_at ? 'bi-check-lg' : 'bi-hourglass-split' }}"></i></div>
                <div>
                    <div class="chip-title">ผู้ปกครอง</div>
                    <div class="text-muted">{{ $trialLead->guardian_confirmed_at ? 'คอนเฟิร์มแล้ว ('.$trialLead->guardian_confirmed_at->format('d/m/Y H:i').')' : 'ยังไม่คอนเฟิร์ม' }}</div>
                </div>
            </div>
            <div class="confirm-chip {{ $trialLead->teacher_confirmed_at ? 'done' : '' }}">
                <div class="chip-icon"><i class="bi {{ $trialLead->teacher_confirmed_at ? 'bi-check-lg' : 'bi-hourglass-split' }}"></i></div>
                <div>
                    <div class="chip-title">ครู</div>
                    <div class="text-muted">{{ $trialLead->teacher_confirmed_at ? 'คอนเฟิร์มแล้ว ('.$trialLead->teacher_confirmed_at->format('d/m/Y H:i').')' : 'ยังไม่คอนเฟิร์ม' }}</div>
                </div>
            </div>
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

{{-- ===== การชำระค่าทดลองเรียน (แบบเดียวกับหน้าขายคอร์ส) ===== --}}
<div class="row g-3 mb-3">
    <div class="col-md-7">
        <div class="form-section h-100 mb-0">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-credit-card"></i></div>
                {{ $outstanding > 0 ? 'เลือกช่องทางชำระเงิน' : 'ช่องทางที่ชำระ' }}
            </div>

            @if($outstanding > 0)
                <form method="POST" action="{{ route('trial-payments.store', $trialLead) }}" enctype="multipart/form-data" id="trialPaymentForm" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <div class="row g-3">
                            @foreach([
                                'promptpay' => ['bi-qr-code', 'PromptPay / QR', 'สแกนจ่ายผ่านแอปธนาคาร'],
                                'transfer' => ['bi-bank', 'โอนเงินผ่านธนาคาร', 'แนบหลักฐานการโอน'],
                                'credit_card' => ['bi-credit-card-2-front', 'บัตรเครดิต / เดบิต', 'บันทึกรายการจากเครื่องรูดบัตร'],
                            ] as $value => [$icon, $label, $description])
                                <div class="col-6 col-lg-4">
                                    <label class="channel-card">
                                        <input type="radio" name="payment_method" value="{{ $value }}" required @checked(old('payment_method') === $value)>
                                        <i class="bi {{ $icon }} d-block"></i>
                                        <span class="name d-block">{{ $label }}</span>
                                        <span class="desc">{{ $description }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12" data-trial-detail="promptpay" hidden>
                        <div class="payment-detail-box">
                            <div class="qr-wrap">
                                @if(config('payment.promptpay_id'))
                                    <img id="trialPaymentQr" alt="QR ชำระเงิน PromptPay" data-promptpay-id="{{ config('payment.promptpay_id') }}">
                                    <div id="trialPaymentQrAmount" class="fw-bold mt-2"></div>
                                    <div class="text-muted small">สแกนด้วยแอปธนาคาร จากนั้นแนบสลิปด้านล่าง</div>
                                @else
                                    <div class="text-muted">ยังไม่ได้ตั้งค่าเลข PromptPay ของโรงเรียน</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12" data-trial-detail="transfer" hidden>
                        <div class="payment-detail-box">
                            <div class="bank-info-row"><span class="text-muted">ธนาคาร</span><span class="value">{{ config('payment.bank_name') ?: '-' }}</span></div>
                            <div class="bank-info-row"><span class="text-muted">ชื่อบัญชี</span><span class="value">{{ config('payment.bank_account_name') ?: '-' }}</span></div>
                            <div class="bank-info-row">
                                <span class="text-muted">เลขบัญชี</span>
                                <span class="d-flex align-items-center gap-2">
                                    <span class="value">{{ config('payment.bank_account_no') ?: '-' }}</span>
                                    <button type="button" class="btn btn-outline-secondary copy-btn"
                                        onclick="navigator.clipboard.writeText('{{ config('payment.bank_account_no') }}'); this.textContent='คัดลอกแล้ว'">คัดลอก</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12" data-trial-detail="credit_card" hidden>
                        <div class="payment-detail-box">
                            <label for="trialPaymentReference" class="form-label">เลขอ้างอิงการทำรายการ (Ref. no / Auth code) *</label>
                            <input id="trialPaymentReference" name="reference_no" class="form-control" maxlength="100"
                                placeholder="เช่น เลขอ้างอิงจากใบบันทึกรายการบัตร หรือ 4 ตัวท้ายบัตร" value="{{ old('reference_no') }}">
                            <div class="text-muted small mt-2"><i class="bi bi-info-circle"></i> ใช้เลขอ้างอิง/Auth code
                                ที่ปรากฏบนใบบันทึกรายการ (Sales Slip) จากเครื่องรูดบัตร
                                หรือถ่ายภาพหน้าจอแจ้งเตือนการชำระเงินสำเร็จแนบเป็นหลักฐานเพิ่มเติมด้านล่าง</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="trialPaymentProof" class="form-label">แนบสลิป/หลักฐานการชำระเงิน<span
                                id="trialProofRequiredMark" class="text-danger" style="display:none;"> *</span></label>
                        <input id="trialPaymentProof" name="payment_proof" type="file" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        <div class="form-text">โอนเงินและ PromptPay ต้องแนบหลักฐาน JPG, PNG หรือ PDF ขนาดไม่เกิน 4 MB และรอตรวจสอบการชำระ</div>
                    </div>
                    <input id="trialPaymentAmount" name="amount" type="hidden" value="{{ old('amount', $outstanding) }}">
                    <input id="trialPaymentDate" name="transaction_at" type="hidden" value="{{ old('transaction_at', now()->format('Y-m-d\TH:i')) }}">
                    <div class="col-12">
                        <label for="trialPaymentNotes" class="form-label">หมายเหตุการชำระเงิน</label>
                        <input id="trialPaymentNotes" name="notes" class="form-control" maxlength="1000" value="{{ old('notes') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-accent"><i class="bi bi-cash-coin"></i> บันทึกรับชำระค่าทดลอง</button>
                    </div>
                </form>
            @else
                <div class="alert alert-success py-2 mb-0"><i class="bi bi-check-circle"></i> รับค่าทดลองครบแล้ว</div>
            @endif
        </div>
    </div>

    <div class="col-md-5">
        <div class="form-section h-100 mb-0">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-receipt"></i></div> สรุปยอดค่าทดลองเรียน
            </div>
            <div class="price-summary">
                <div class="price-row"><span>ค่าทดลองเรียน</span><span>฿{{ number_format($trialLead->trial_fee,2) }}</span></div>
                @if($paidAmount > 0)
                    <div class="price-row discount"><span>รับสุทธิแล้ว</span><span>-฿{{ number_format($paidAmount,2) }}</span></div>
                @endif
                <div class="price-row total"><span>คงค้าง</span><span>฿{{ number_format($outstanding,2) }}</span></div>
            </div>

            @if($outstanding > 0)
                <div class="alert alert-warning py-2 mt-3 mb-0 small"><i class="bi bi-exclamation-circle"></i> ยังมียอดค้างชำระ ฿{{ number_format($outstanding,2) }}</div>
            @else
                <div class="alert alert-success py-2 mt-3 mb-0 small"><i class="bi bi-check-circle"></i> รับค่าทดลองครบแล้ว</div>
            @endif
        </div>
    </div>
</div>

<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-clock-history"></i></div> ประวัติการชำระเงิน
    </div>
    <div class="table-responsive"><table class="table table-sm align-middle mb-0 payments-table"><thead><tr><th>เลขรายการ/วันที่</th><th>ประเภท</th><th>ช่องทาง</th><th>ยอด</th><th>สถานะ</th><th>หลักฐาน</th><th class="text-end">ดำเนินการ</th></tr></thead><tbody>
    @forelse($trialLead->payments->sortByDesc('transaction_at') as $payment)
        <tr>
            <td><strong>{{ $payment->transaction_no }}</strong><div class="small text-muted">{{ $payment->transaction_at->format('d/m/Y H:i') }}</div></td>
            <td>{{ $payment->type==='refund'?'คืนเงิน':'รับเงิน' }}</td><td>{{ $payment->methodLabel() }}</td>
            <td class="fw-semibold {{ $payment->type==='refund'?'text-danger':'text-success' }}">{{ $payment->type==='refund'?'-':'+' }}฿{{ number_format($payment->amount,2) }}</td>
            <td><span class="badge-pay-status {{ $payment->status==='confirmed'?'confirmed':($payment->status==='pending'?'pending':'other') }}">{{ $payment->statusLabel() }}</span>@if($payment->confirmed_by)<div class="small text-muted">โดย {{ $payment->confirmed_by }}</div>@endif</td>
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
<script>
    (() => {
        const form = document.getElementById('trialPaymentForm');
        if (!form) return;
        const amount = form.querySelector('#trialPaymentAmount');
        const qr = form.querySelector('#trialPaymentQr');
        const proofInput = form.querySelector('#trialPaymentProof');
        const proofRequiredMark = form.querySelector('#trialProofRequiredMark');
        const referenceInput = form.querySelector('#trialPaymentReference');
        function updatePaymentDetails() {
            const method = form.querySelector('input[name="payment_method"]:checked')?.value;
            form.querySelectorAll('[data-trial-detail]').forEach(box => {
                box.hidden = box.dataset.trialDetail !== method;
            });
            const needsProof = ['transfer', 'promptpay'].includes(method);
            proofInput.required = needsProof;
            if (proofRequiredMark) proofRequiredMark.style.display = needsProof ? 'inline' : 'none';
            if (qr && method === 'promptpay') {
                const value = Number(amount.value);
                qr.hidden = !Number.isFinite(value) || value <= 0;
                if (!qr.hidden) {
                    qr.src = `https://promptpay.io/${encodeURIComponent(qr.dataset.promptpayId)}/${value.toFixed(2)}.png`;
                } else {
                    qr.removeAttribute('src');
                }
                form.querySelector('#trialPaymentQrAmount').textContent = qr.hidden ? 'กรุณาระบุยอดชำระ' : `ยอดชำระ ฿${value.toFixed(2)}`;
            }
        }
        form.querySelectorAll('input[name="payment_method"]').forEach(input => input.addEventListener('change', updatePaymentDetails));
        amount.addEventListener('input', updatePaymentDetails);
        updatePaymentDetails();

        form.addEventListener('submit', (e) => {
            const method = form.querySelector('input[name="payment_method"]:checked')?.value;
            if (method === 'credit_card' && !referenceInput.value.trim()) {
                e.preventDefault();
                alert('กรุณากรอกเลขอ้างอิงการทำรายการบัตรก่อนบันทึก');
                referenceInput.focus();
            }
        });
    })();
</script>
@endsection

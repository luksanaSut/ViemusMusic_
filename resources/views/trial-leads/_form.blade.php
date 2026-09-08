@php $lead = $trialLead ?? null; @endphp
<style>
    .form-section {
        background: #fff;
        border: 1px solid var(--border, #e4e1dc);
        border-radius: 14px;
        padding: 1.4rem 1.5rem;
        margin-bottom: 1rem;
    }

    .form-section-title {
        display: flex;
        align-items: center;
        gap: .6rem;
        font-weight: 600;
        font-size: .95rem;
        margin-bottom: 1.1rem;
        padding-bottom: .85rem;
        border-bottom: 1px solid var(--border, #e4e1dc);
        font-family: 'Prompt', sans-serif;
        color: var(--ink, #1c1a17);
    }

    .form-section-title i {
        color: var(--muted, #6b655e);
        font-size: 1.05rem;
    }

    .form-section-title .tag {
        margin-left: auto;
        font-size: .7rem;
        color: var(--muted, #6b655e);
        font-weight: 500;
    }

    .form-label {
        font-size: .82rem;
        font-weight: 600;
        color: #40382f;
        margin-bottom: .35rem;
    }

    .form-control,
    .form-select {
        border-color: var(--border, #e4e1dc);
        font-size: .9rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--accent, #1f3350);
        box-shadow: 0 0 0 .2rem rgba(31, 51, 80, .1);
    }

    .info-row {
        display: flex;
        gap: .6rem;
        padding: .4rem 0;
        border-bottom: 1px dashed var(--border, #e4e1dc);
    }

    .info-row .label {
        font-size: .75rem;
        color: var(--muted, #6b655e);
        min-width: 130px;
    }

    .info-row .value {
        font-weight: 500;
    }

    .channel-card {
        position: relative;
        display: block;
        height: 100%;
        border: 1.5px solid var(--border, #e4e1dc);
        border-radius: 12px;
        padding: 1.2rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: .15s;
        background: var(--surface, #fff);
    }

    .channel-card:hover {
        border-color: #c9c4bb;
        box-shadow: 0 2px 8px rgba(28, 26, 23, .06);
    }

    .channel-card.active,
    .channel-card:has(input:checked) {
        border-color: var(--accent, #1f3350);
        border-width: 2px;
        background: var(--accent-soft, #e7ebf1);
    }

    .channel-card:focus-within {
        outline: 2px solid var(--accent, #1f3350);
        outline-offset: 2px;
    }

    .channel-card input {
        position: absolute;
        top: 12px;
        right: 12px;
        accent-color: var(--accent, #1f3350);
    }

    .channel-card i {
        font-size: 1.8rem;
        color: var(--accent-dark, #13233a);
    }

    .channel-card .name {
        font-weight: 700;
        margin-top: .5rem;
        font-family: 'Prompt', sans-serif;
    }

    .channel-card .desc {
        font-size: .78rem;
        color: var(--muted, #6b655e);
    }

    .payment-detail-box {
        border: 1px solid var(--border, #e4e1dc);
        border-radius: 12px;
        padding: 1.2rem;
        margin-top: 1rem;
    }

    .qr-wrap {
        text-align: center;
    }

    .qr-wrap img {
        max-width: 220px;
        border: 1px solid var(--border, #e4e1dc);
        border-radius: 10px;
        padding: 8px;
        background: #fff;
    }

    .bank-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .6rem 0;
        border-bottom: 1px dashed var(--border, #e4e1dc);
    }

    .bank-info-row .value {
        font-weight: 700;
        font-family: 'Prompt', sans-serif;
    }

    .copy-btn {
        font-size: .75rem;
        padding: .2rem .6rem;
    }
</style>

{{-- ===== 1. ข้อมูลผู้สนใจ ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <i class="bi bi-person-vcard"></i>
        ข้อมูลผู้สนใจ
    </div>
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">ชื่อผู้เรียน *</label><input name="student_name"
                class="form-control" required value="{{ old('student_name', $lead?->student_name) }}"></div>
        <div class="col-md-6"><label class="form-label">ชื่อเล่น</label><input name="nickname" class="form-control"
                value="{{ old('nickname', $lead?->nickname) }}"></div>
        <div class="col-md-4"><label class="form-label">วันเกิด</label><input name="date_of_birth" type="date"
                class="form-control" value="{{ old('date_of_birth', $lead?->date_of_birth?->format('Y-m-d')) }}"></div>
        <div class="col-md-4"><label class="form-label">ชื่อผู้ปกครอง</label><input name="guardian_name"
                class="form-control" value="{{ old('guardian_name', $lead?->guardian_name) }}"></div>
        <div class="col-md-4"><label class="form-label">เบอร์โทร *</label><input name="phone" class="form-control"
                required value="{{ old('phone', $lead?->phone) }}"></div>
        <div class="col-md-4"><label class="form-label">อีเมล</label><input name="email" type="email"
                class="form-control" value="{{ old('email', $lead?->email) }}"></div>
        <div class="col-md-4"><label class="form-label">LINE ID</label><input name="line_id" class="form-control"
                value="{{ old('line_id', $lead?->line_id) }}"></div>
        <div class="col-md-4"><label class="form-label">ช่องทางที่รู้จัก</label><input name="source"
                class="form-control" placeholder="เช่น Facebook, แนะนำต่อ" value="{{ old('source', $lead?->source) }}">
        </div>
        <div class="col-md-6"><label class="form-label">วิชา/เครื่องดนตรีที่สนใจ</label><input name="interest"
                class="form-control" value="{{ old('interest', $lead?->interest) }}"></div>
        <div class="col-md-6"><label class="form-label">วันเวลาที่สะดวก</label><input name="preferred_schedule"
                class="form-control" placeholder="เช่น เสาร์ 10:00–13:00"
                value="{{ old('preferred_schedule', $lead?->preferred_schedule) }}"></div>
    </div>
</div>

{{-- ===== 2. นัดทดลองเรียน ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <i class="bi bi-calendar2-event"></i>
        นัดทดลองเรียน
    </div>
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label">คอร์สที่สนใจ</label><select name="course_id"
                class="form-select">
                <option value="">ยังไม่ระบุ</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $lead?->course_id) == $course->id)>{{ $course->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4"><label class="form-label">อาจารย์ทดลอง</label><select name="teacher_id"
                class="form-select">
                <option value="">ยังไม่เลือก</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected(old('teacher_id', $lead?->teacher_id) == $teacher->id)>{{ $teacher->full_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4"><label class="form-label">ห้อง</label><select name="room_id" class="form-select">
                <option value="">ไม่ระบุ/ออนไลน์</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" @selected(old('room_id', $lead?->room_id) == $room->id)>{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3"><label class="form-label">วันที่ทดลอง</label><input name="trial_date" type="date"
                class="form-control" value="{{ old('trial_date', $lead?->trial_date?->format('Y-m-d')) }}"></div>
        <div class="col-md-3"><label class="form-label">เริ่ม</label><input name="trial_start_time" type="time"
                class="form-control"
                value="{{ old('trial_start_time', $lead?->trial_start_time ? substr($lead->trial_start_time, 0, 5) : '') }}">
        </div>
        <div class="col-md-3"><label class="form-label">สิ้นสุด</label><input name="trial_end_time" type="time"
                class="form-control"
                value="{{ old('trial_end_time', $lead?->trial_end_time ? substr($lead->trial_end_time, 0, 5) : '') }}">
        </div>
        <div class="col-md-3"><label class="form-label">รูปแบบ</label><select name="delivery_mode"
                class="form-select">
                <option value="onsite" @selected(old('delivery_mode', $lead?->delivery_mode) === 'onsite')>ที่โรงเรียน</option>
                <option value="online" @selected(old('delivery_mode', $lead?->delivery_mode) === 'online')>ออนไลน์</option>
            </select></div>
        <div class="col-md-3"><label class="form-label">ค่าทดลอง</label><input name="trial_fee" id="trialFeeInput"
                type="number" min="0" step="0.01" class="form-control"
                value="{{ old('trial_fee', $lead?->trial_fee ?? 0) }}"></div>
        <div class="col-md-3"><label class="form-label">ติดตามครั้งถัดไป</label><input name="next_follow_up_date"
                type="date" class="form-control"
                value="{{ old('next_follow_up_date', $lead?->next_follow_up_date?->format('Y-m-d')) }}"></div>
        @if ($lead)
            <div class="col-md-3"><label class="form-label">สถานะ</label><select name="status"
                    id="leadStatusSelect" class="form-select">
                    @foreach (['new' => 'ผู้สนใจใหม่', 'contacted' => 'ติดต่อแล้ว', 'scheduled' => 'นัดทดลองแล้ว', 'completed' => 'ทดลองแล้ว', 'lost' => 'ไม่ดำเนินการต่อ'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $lead->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>
</div>

@unless ($lead)
    {{-- ===== ชำระค่าทดลองเรียน (เฉพาะตอนสร้างใหม่) ===== --}}
    <div class="form-section">
        <div class="form-section-title">
            <i class="bi bi-cash-coin"></i>
            ชำระค่าทดลองเรียน
            <span class="tag">ไม่บังคับ</span>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" id="collectPaymentToggle"
                {{ old('payment_method') ? 'checked' : '' }}>
            <label class="form-check-label" for="collectPaymentToggle">รับชำระค่าทดลองตอนนี้เลย</label>
        </div>

        <div id="paymentFields" style="{{ old('payment_method') ? '' : 'display:none;' }}">
            <input type="hidden" name="payment_method" id="paymentMethodInput" value="{{ old('payment_method') }}">

            <label class="form-label">ช่องทางรับเงิน *</label>
            <div class="row g-2 mb-3">
                <div class="col-6 col-md-4">
                    <div class="channel-card" data-value="promptpay"><i class="bi bi-qr-code"></i>
                        <div class="name">PromptPay/QR</div>
                        <div class="desc">สแกนจ่ายผ่านแอปธนาคาร</div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="channel-card" data-value="transfer"><i class="bi bi-bank"></i>
                        <div class="name">โอนธนาคาร</div>
                        <div class="desc">แนบหลักฐานการโอน</div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="channel-card" data-value="credit_card"><i class="bi bi-credit-card-2-front"></i>
                        <div class="name">บัตรเครดิต</div>
                        <div class="desc">บันทึกจากเครื่องรูดบัตร</div>
                    </div>
                </div>
            </div>

            <div id="promptpayBox" class="payment-detail-box" style="display:none;">
                <div class="qr-wrap">
                    @if (config('payment.promptpay_id'))
                        <img id="promptpayQr" src="" alt="PromptPay QR">
                        <div class="fw-bold mt-2" id="promptpayAmountLabel"></div>
                        <div class="text-muted small">สแกนด้วยแอปธนาคารเพื่อชำระเงิน จากนั้นแนบสลิปด้านล่าง</div>
                    @else
                        <p class="text-muted small mb-0"><i class="bi bi-exclamation-circle"></i> ยังไม่ได้ตั้งค่าเลข
                            PromptPay ของโรงเรียน</p>
                    @endif
                </div>
            </div>
            <div id="transferBox" class="payment-detail-box" style="display:none;">
                <div class="bank-info-row"><span class="text-muted">ธนาคาร</span><span
                        class="value">{{ config('payment.bank_name') }}</span></div>
                <div class="bank-info-row"><span class="text-muted">ชื่อบัญชี</span><span
                        class="value">{{ config('payment.bank_account_name') }}</span></div>
                <div class="bank-info-row">
                    <span class="text-muted">เลขบัญชี</span>
                    <span class="d-flex align-items-center gap-2">
                        <span class="value">{{ config('payment.bank_account_no') ?: '-' }}</span>
                        <button type="button" class="btn btn-outline-secondary copy-btn"
                            onclick="navigator.clipboard.writeText('{{ config('payment.bank_account_no') }}'); this.textContent='คัดลอกแล้ว'">คัดลอก</button>
                    </span>
                </div>
            </div>

            <div id="creditCardBox" class="payment-detail-box" style="display:none;">
                <label for="paymentReferenceInput" class="form-label">เลขอ้างอิงการทำรายการ (Ref. no / Auth code)
                    *</label>
                <input type="text" name="payment_reference_no" id="paymentReferenceInput" class="form-control"
                    placeholder="เช่น เลขอ้างอิงจากใบบันทึกรายการบัตร หรือ 4 ตัวท้ายบัตร"
                    value="{{ old('payment_reference_no') }}">
                <div class="text-muted small mt-2"><i class="bi bi-info-circle"></i> ใช้เลขอ้างอิง/Auth code
                    ที่ปรากฏบนใบบันทึกรายการ (Sales Slip) จากเครื่องรูดบัตร
                    หรือถ่ายภาพหน้าจอแจ้งเตือนการชำระเงินสำเร็จแนบเป็นหลักฐานเพิ่มเติมด้านล่าง</div>
            </div>

            <div class="row g-3 mb-2">
                <div class="col-md-6"><label class="form-label">จำนวนเงิน *</label><input type="number"
                        name="payment_amount" id="paymentAmountInput" min="0.01" step="0.01"
                        class="form-control" value="{{ old('payment_amount') }}"></div>
                <div class="col-md-6"><label class="form-label">แนบสลิป/หลักฐานการชำระเงิน<span
                            id="proofRequiredMark" class="text-danger" style="display:none;"> *</span></label><input
                        type="file" name="payment_proof" id="proofInput" class="form-control"
                        accept=".jpg,.jpeg,.png,.pdf"></div>
            </div>
            <div class="form-text mb-2">บังคับแนบหลักฐานสำหรับโอน/PromptPay</div>
            <input name="payment_notes" class="form-control form-control-sm" placeholder="หมายเหตุการชำระเงิน (ถ้ามี)"
                value="{{ old('payment_notes') }}">
        </div>
    </div>
@endunless

@if ($lead)
    {{-- ===== 3. ผลทดลองเรียน ===== --}}
    <div class="form-section">
        <div class="form-section-title">
            <i class="bi bi-clipboard2-check"></i>
            ผลทดลองเรียน
        </div>
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label">ผล</label><select name="trial_result"
                    class="form-select">
                    <option value="">ยังไม่บันทึก</option>
                    @foreach (['interested' => 'สนใจสมัคร', 'considering' => 'ขอพิจารณา', 'not_interested' => 'ไม่สนใจ', 'no_show' => 'ไม่มาตามนัด'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('trial_result', $lead->trial_result) === $value)>{{ $label }}</option>
                    @endforeach
                </select></div>
            <div class="col-md-8"><label class="form-label">ความคิดเห็นอาจารย์</label>
                <textarea name="teacher_feedback" class="form-control" rows="3">{{ old('teacher_feedback', $lead->teacher_feedback) }}</textarea>
            </div>
        </div>
    </div>
@endif

{{-- ===== หมายเหตุ ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <i class="bi bi-journal-text"></i>
        หมายเหตุ
    </div>
    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $lead?->notes) }}</textarea>
</div>

@unless ($lead)
    <script>
        (function() {
            const toggle = document.getElementById('collectPaymentToggle');
            if (!toggle) return;

            const fieldsBox = document.getElementById('paymentFields');
            const cards = fieldsBox.querySelectorAll('.channel-card');
            const methodInput = document.getElementById('paymentMethodInput');
            const amountInput = document.getElementById('paymentAmountInput');
            const trialFeeInput = document.getElementById('trialFeeInput');
            const proofInput = document.getElementById('proofInput');
            const proofRequiredMark = document.getElementById('proofRequiredMark');
            const referenceInput = document.getElementById('paymentReferenceInput');
            const promptpayQr = document.getElementById('promptpayQr');
            const promptpayAmountLabel = document.getElementById('promptpayAmountLabel');
            const promptpayId = @json(config('payment.promptpay_id'));
            const boxMap = {
                promptpay: 'promptpayBox',
                transfer: 'transferBox',
                credit_card: 'creditCardBox'
            };

            function updatePromptpayQr() {
                if (!promptpayQr || !promptpayId) return;
                const amount = parseFloat(amountInput.value || trialFeeInput.value || 0).toFixed(2);
                promptpayQr.src = `https://promptpay.io/${promptpayId}/${amount}.png`;
                if (promptpayAmountLabel) promptpayAmountLabel.textContent = `ยอดชำระ ฿${amount}`;
            }

            function selectCard(value) {
                cards.forEach(c => c.classList.toggle('active', c.dataset.value === value));
                methodInput.value = value;

                Object.values(boxMap).forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.style.display = 'none';
                });
                if (boxMap[value]) {
                    const el = document.getElementById(boxMap[value]);
                    if (el) el.style.display = 'block';
                }
                if (value === 'promptpay') updatePromptpayQr();

                const needsProof = ['transfer', 'promptpay'].includes(value);
                if (proofRequiredMark) proofRequiredMark.style.display = needsProof ? 'inline' : 'none';
            }

            toggle.addEventListener('change', () => {
                fieldsBox.style.display = toggle.checked ? 'block' : 'none';
                if (toggle.checked && !amountInput.value) amountInput.value = trialFeeInput.value || '';
                if (!toggle.checked) {
                    cards.forEach(c => c.classList.remove('active'));
                    methodInput.value = '';
                    Object.values(boxMap).forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.style.display = 'none';
                    });
                }
            });

            cards.forEach(card => card.addEventListener('click', () => selectCard(card.dataset.value)));
            amountInput.addEventListener('input', updatePromptpayQr);

            if (methodInput.value) selectCard(methodInput.value);

            const outerForm = toggle.closest('form');
            outerForm.addEventListener('submit', (e) => {
                if (!toggle.checked) return;
                if (!methodInput.value) {
                    e.preventDefault();
                    alert('กรุณาเลือกช่องทางรับเงิน หรือปิดสวิตช์นี้หากยังไม่รับชำระตอนนี้');
                    return;
                }
                if (!amountInput.value || parseFloat(amountInput.value) <= 0) {
                    e.preventDefault();
                    alert('กรุณาระบุจำนวนเงินที่ได้รับ');
                    return;
                }
                if (['transfer', 'promptpay'].includes(methodInput.value) && !proofInput.files.length) {
                    e.preventDefault();
                    alert('กรุณาแนบสลิป/หลักฐานการชำระเงิน');
                    return;
                }
                if (methodInput.value === 'credit_card' && !referenceInput.value.trim()) {
                    e.preventDefault();
                    alert('กรุณากรอกเลขอ้างอิงการทำรายการบัตร');
                }
            });
        })();
    </script>
@endunless

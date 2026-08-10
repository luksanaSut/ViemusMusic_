@extends('layouts.app')
@section('title', $courseTransfer->transfer_no)

@section('content')
    <style>
        .form-section {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.4rem 1.6rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 2px rgba(28, 26, 23, .04);
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

        .info-row {
            display: flex;
            gap: .6rem;
            padding: .4rem 0;
            border-bottom: 1px dashed var(--border, #e4e1dc);
        }

        .info-row .label {
            font-size: .75rem;
            color: var(--muted, #6b655e);
            min-width: 170px;
        }

        .info-row .value {
            font-weight: 500;
        }

        .channel-card {
            border: 1.5px solid var(--border, #e4e1dc);
            border-radius: 12px;
            padding: 1.1rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: .15s;
            height: 100%;
        }

        .channel-card:hover {
            border-color: #c9c4bb;
        }

        .channel-card.active {
            border-color: var(--accent, #1f3350);
            border-width: 2px;
            background: var(--accent-soft, #e7ebf1);
        }

        .channel-card i {
            font-size: 1.6rem;
            color: var(--accent-dark, #13233a);
        }

        .channel-card .name {
            font-weight: 700;
            margin-top: .4rem;
            font-family: 'Prompt', sans-serif;
            font-size: .85rem;
        }
    </style>

    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h4 class="mb-0">{{ $courseTransfer->transfer_no }}</h4>
            <span class="badge {{ $courseTransfer->statusBadgeClass() }}">{{ $courseTransfer->statusLabel() }}</span>
        </div>
        <a href="{{ route('course-transfers.index') }}" class="btn btn-outline-secondary btn-sm"><i
                class="bi bi-arrow-left"></i> กลับ</a>
    </div>

    <div class="row g-3">
        <div class="col-md-7">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-arrow-left-right"></i></div> รายละเอียดการเปลี่ยนคอร์ส
                </div>
                <div class="info-row">
                    <div class="label">นักเรียน</div>
                    <div class="value"><a
                            href="{{ route('students.show', $courseTransfer->student) }}">{{ $courseTransfer->student->full_name }}</a>
                    </div>
                </div>
                <div class="info-row">
                    <div class="label">คอร์สเดิม</div>
                    <div class="value">{{ $courseTransfer->oldCourse->name }}</div>
                </div>
                <div class="info-row">
                    <div class="label">คอร์สใหม่</div>
                    <div class="value">{{ $courseTransfer->newCourse->name }}</div>
                </div>
                <div class="info-row">
                    <div class="label">อาจารย์ใหม่</div>
                    <div class="value">{{ $courseTransfer->newTeacher->full_name ?? 'ให้ทางโรงเรียนจัดให้' }}</div>
                </div>
                @if ($courseTransfer->reason)
                    <div class="info-row">
                        <div class="label">เหตุผล</div>
                        <div class="value">{{ $courseTransfer->reason }}</div>
                    </div>
                @endif
                @if ($courseTransfer->notes)
                    <div class="info-row">
                        <div class="label">หมายเหตุ</div>
                        <div class="value">{{ $courseTransfer->notes }}</div>
                    </div>
                @endif
                <div class="info-row">
                    <div class="label">ทำรายการโดย</div>
                    <div class="value">{{ $courseTransfer->transferred_by }}</div>
                </div>
            </div>

            @if ($courseTransfer->status === 'pending_payment')
                <div class="form-section">
                    <div class="form-section-title">
                        <div class="icon-badge"><i class="bi bi-credit-card"></i></div>
                        ชำระส่วนต่างเพื่อยืนยันการเปลี่ยนคอร์ส
                    </div>
                    <form action="{{ route('course-transfers.confirm-payment', $courseTransfer) }}" method="POST"
                        enctype="multipart/form-data" id="payForm">
                        @csrf
                        <input type="hidden" name="payment_method" id="paymentMethodInput" required>
                        <div class="row g-2 mb-3">
                            <div class="col-md-3">
                                <div class="channel-card" data-value="promptpay"><i class="bi bi-qr-code"></i>
                                    <div class="name">PromptPay/QR</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="channel-card" data-value="transfer"><i class="bi bi-bank"></i>
                                    <div class="name">โอนธนาคาร</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="channel-card" data-value="credit_card"><i class="bi bi-credit-card-2-front"></i>
                                    <div class="name">บัตรเครดิต</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="channel-card" data-value="cash"><i class="bi bi-cash-coin"></i>
                                    <div class="name">เงินสด</div>
                                </div>
                            </div>
                        </div>

                        <div id="promptpayBox" class="text-center mb-3" style="display:none;">
                            @if (config('payment.promptpay_id'))
                                <img src="https://promptpay.io/{{ config('payment.promptpay_id') }}/{{ number_format($courseTransfer->price_difference, 2, '.', '') }}.png"
                                    style="max-width:200px;">
                            @endif
                            <div class="fw-bold mt-1">ยอดชำระ ฿{{ number_format($courseTransfer->price_difference, 2) }}
                            </div>
                        </div>
                        <div id="transferBox" class="mb-3" style="display:none;">
                            <div class="info-row">
                                <div class="label">ธนาคาร</div>
                                <div class="value">{{ config('payment.bank_name') }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">ชื่อบัญชี</div>
                                <div class="value">{{ config('payment.bank_account_name') }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">เลขบัญชี</div>
                                <div class="value">{{ config('payment.bank_account_no') }}</div>
                            </div>
                        </div>
                        <div id="creditCardBox" class="mb-3" style="display:none;">
                            <label class="form-label">เลขอ้างอิงการทำรายการ (Ref. no / Auth code) *</label>
                            <input type="text" name="payment_reference" id="refInput" class="form-control">
                        </div>

                        <label class="form-label">แนบสลิป/หลักฐานการชำระเงิน</label>
                        <input type="file" name="payment_proof" class="form-control mb-3"
                            accept="image/*,application/pdf">

                        <button type="button" class="btn btn-accent" id="confirmBtn" disabled><i
                                class="bi bi-check-lg"></i> ยืนยันชำระเงิน</button>
                    </form>
                    <form action="{{ route('course-transfers.cancel', $courseTransfer) }}" method="POST"
                        class="d-inline" onsubmit="return confirm('ยกเลิกรายการเปลี่ยนคอร์สนี้?')">
                        @csrf @method('PATCH')
                        <button class="btn btn-outline-danger mt-2">ยกเลิกรายการ</button>
                    </form>
                </div>
            @endif
        </div>

        <div class="col-md-5">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-calculator"></i></div> สรุปส่วนต่างราคา
                </div>
                <div class="info-row">
                    <div class="label">มูลค่าคงเหลือคอร์สเดิม</div>
                    <div class="value">฿{{ number_format($courseTransfer->old_course_remaining_value, 2) }}</div>
                </div>
                <div class="info-row">
                    <div class="label">ราคาคอร์สใหม่</div>
                    <div class="value">฿{{ number_format($courseTransfer->new_course_price, 2) }}</div>
                </div>
                @if ($courseTransfer->teacher_change_fee > 0)
                    <div class="info-row">
                        <div class="label">ค่าธรรมเนียมเปลี่ยนอาจารย์</div>
                        <div class="value">฿{{ number_format($courseTransfer->teacher_change_fee, 2) }}</div>
                    </div>
                @endif
                <div class="info-row">
                    <div class="label">ส่วนต่าง</div>
                    <div
                        class="value fw-bold {{ $courseTransfer->price_difference > 0 ? 'text-danger' : ($courseTransfer->price_difference < 0 ? 'text-success' : '') }}">
                        {{ $courseTransfer->priceDifferenceLabel() }}</div>
                </div>
                @if ($courseTransfer->credit_issued > 0)
                    <div class="info-row">
                        <div class="label">เครดิตที่ได้รับ</div>
                        <div class="value text-success">฿{{ number_format($courseTransfer->credit_issued, 2) }}</div>
                    </div>
                @endif

                @if ($courseTransfer->status === 'completed')
                    <hr class="my-3">
                    <div class="text-success small"><i class="bi bi-check-circle"></i> เปลี่ยนคอร์สสำเร็จเมื่อ
                        {{ $courseTransfer->completed_at->format('d/m/Y H:i') }}</div>
                    @if ($courseTransfer->newEnrollment)
                        <a href="{{ route('students.show', $courseTransfer->student) }}"
                            class="btn btn-outline-secondary btn-sm w-100 mt-2">ดูโปรไฟล์นักเรียน</a>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.channel-card').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.channel-card').forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                document.getElementById('paymentMethodInput').value = card.dataset.value;
                document.getElementById('confirmBtn').disabled = false;

                ['promptpayBox', 'transferBox', 'creditCardBox'].forEach(id => document.getElementById(id)
                    .style.display = 'none');
                const map = {
                    promptpay: 'promptpayBox',
                    transfer: 'transferBox',
                    credit_card: 'creditCardBox'
                };
                if (map[card.dataset.value]) document.getElementById(map[card.dataset.value]).style
                    .display = 'block';
            });
        });
        document.getElementById('confirmBtn')?.addEventListener('click', function() {
            const method = document.getElementById('paymentMethodInput').value;
            if (method === 'credit_card' && !document.getElementById('refInput').value.trim()) {
                alert('กรุณากรอกเลขอ้างอิงการทำรายการบัตร');
                return;
            }
            if (!confirm('ยืนยันการชำระเงิน?')) return;
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...';
            document.getElementById('payForm').submit();
        });
    </script>
@endsection

@extends('layouts.app')
@section('title', $saleOrder->order_no)

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
            min-width: 150px;
        }

        .info-row .value {
            font-weight: 500;
        }

        .toggle-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 12px;
            padding: .9rem 1.1rem;
            margin-bottom: .7rem;
        }

        .toggle-row .title {
            font-weight: 600;
        }

        .toggle-row .desc {
            font-size: .8rem;
            color: var(--muted, #6b655e);
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

        .channel-card {
            border: 1.5px solid var(--border, #e4e1dc);
            border-radius: 12px;
            padding: 1.2rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: .15s;
            height: 100%;
        }

        .channel-card:hover {
            border-color: #c9c4bb;
            box-shadow: 0 2px 8px rgba(28, 26, 23, .06);
        }

        .channel-card.active {
            border-color: var(--accent, #1f3350);
            border-width: 2px;
            background: var(--accent-soft, #e7ebf1);
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

        .step-banner {
            background: var(--accent, #1f3350);
            color: #fff;
            border-radius: 12px;
            padding: .9rem 1.2rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: .7rem;
        }
    </style>

    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h4 class="mb-0">{{ $saleOrder->order_no }}</h4>
            <span class="badge {{ $saleOrder->statusBadgeClass() }}">{{ $saleOrder->statusLabel() }}</span>
        </div>
        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> กลับ</a>
    </div>

    @if ($saleOrder->status === 'pending_payment')
        <div class="step-banner">
            <i class="bi bi-clipboard-check fs-4"></i>
            <div>
                <strong>สรุปข้อมูลการสมัครเรียน</strong> — ตรวจสอบข้อมูลด้านล่างให้ถูกต้องก่อนดำเนินการชำระเงิน
            </div>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-7">
            {{-- ข้อมูลการสมัครเรียน --}}
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-person"></i></div> ข้อมูลการสมัครเรียน
                </div>
                <div class="info-row">
                    <div class="label">นักเรียน</div>
                    <div class="value"><a
                            href="{{ route('students.show', $saleOrder->student) }}">{{ $saleOrder->student->full_name }}</a>
                    </div>
                </div>
                <div class="info-row">
                    <div class="label">คอร์ส</div>
                    <div class="value">{{ $saleOrder->course->name }} ({{ $saleOrder->course->course_code }})</div>
                </div>
                <div class="info-row">
                    <div class="label">อาจารย์</div>
                    <div class="value">{{ $saleOrder->teacher->full_name ?? 'ให้ทางโรงเรียนจัดให้' }}</div>
                </div>
                <div class="info-row">
                    <div class="label">สาขา</div>
                    <div class="value">{{ $saleOrder->branch ?: '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="label">รูปแบบการเรียน</div>
                    <div class="value">{{ $saleOrder->deliveryModeLabel() }}</div>
                </div>
                <div class="info-row">
                    <div class="label">วัน/เวลาที่สะดวก</div>
                    <div class="value">{{ $saleOrder->preferredDayLabel() ?: '-' }}
                        {{ $saleOrder->preferred_start_time }} - {{ $saleOrder->preferred_end_time }}</div>
                </div>
                @if ($saleOrder->notes)
                    <div class="info-row">
                        <div class="label">หมายเหตุ</div>
                        <div class="value">{{ $saleOrder->notes }}</div>
                    </div>
                @endif
            </div>

            @if ($saleOrder->status === 'pending_payment')
                {{-- ส่วนลด / คูปอง / เครดิต / แต้ม --}}
                <div class="form-section">
                    <div class="form-section-title">
                        <div class="icon-badge"><i class="bi bi-tags"></i></div> ส่วนลด / คูปอง / เครดิต / แต้ม
                    </div>
                    <form action="{{ route('sales.apply-discount', $saleOrder) }}" method="POST" id="discountForm">
                        @csrf
                        <label class="form-label">โปรโมชั่น / คูปอง</label>
                        <div class="input-group mb-3">
                            <input type="text" name="coupon_code" class="form-control"
                                placeholder="เช่น SUMMER25 / EARLY" value="{{ $saleOrder->coupon_code }}"
                                style="text-transform:uppercase">
                            <button class="btn btn-outline-secondary" type="submit">ใช้</button>
                        </div>
                        {{-- ส่ง state เดิมของ toggle อื่นๆ ไปด้วยตอนกดปุ่ม "ใช้" คูปอง เพื่อไม่ให้ค่าที่เลือกไว้หายไป --}}
                        <input type="hidden" name="use_points" value="{{ $saleOrder->points_used > 0 ? 1 : 0 }}">
                        <input type="hidden" name="use_credit" value="{{ $saleOrder->credit_used > 0 ? 1 : 0 }}">
                    </form>

                    <form action="{{ route('sales.apply-discount', $saleOrder) }}" method="POST" id="pointsForm">
                        @csrf
                        <input type="hidden" name="coupon_code" value="{{ $saleOrder->coupon_code }}">
                        <input type="hidden" name="use_credit" value="{{ $saleOrder->credit_used > 0 ? 1 : 0 }}">
                        <div class="toggle-row">
                            <div>
                                <div class="title">ใช้แต้มสะสม</div>
                                <div class="desc">มี {{ $saleOrder->student->pointBalance() }} แต้ม · แลกได้สูงสุด
                                    ฿{{ number_format($saleOrder->student->maxPointsRedeemableValue($saleOrder->total_amount - $saleOrder->discount_amount), 0) }}
                                </div>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" name="use_points"
                                    value="1" {{ $saleOrder->points_used > 0 ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                            </div>
                        </div>
                    </form>

                    <form action="{{ route('sales.apply-discount', $saleOrder) }}" method="POST" id="creditForm">
                        @csrf
                        <input type="hidden" name="coupon_code" value="{{ $saleOrder->coupon_code }}">
                        <input type="hidden" name="use_points" value="{{ $saleOrder->points_used > 0 ? 1 : 0 }}">
                        <div class="toggle-row">
                            <div>
                                <div class="title">ใช้เครดิตคงเหลือ</div>
                                <div class="desc">เครดิตคงเหลือ
                                    ฿{{ number_format($saleOrder->student->creditBalance(), 2) }} · ใช้หักยอดได้</div>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" name="use_credit"
                                    value="1" {{ $saleOrder->credit_used > 0 ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            {{-- ช่องทางชำระเงิน --}}
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-credit-card"></i></div>
                    {{ $saleOrder->status === 'paid' ? 'ช่องทางที่ชำระ' : 'เลือกช่องทางชำระเงิน' }}
                </div>

                @if ($saleOrder->status === 'pending_payment')
                    <form action="{{ route('sales.confirm-payment', $saleOrder) }}" method="POST"
                        enctype="multipart/form-data" id="paymentForm">
                        @csrf
                        <input type="hidden" name="payment_method" id="paymentMethodInput" required>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="channel-card" data-value="promptpay">
                                    <i class="bi bi-qr-code"></i>
                                    <div class="name">PromptPay / QR</div>
                                    <div class="desc">สแกนจ่ายผ่านแอปธนาคาร</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="channel-card" data-value="credit_card">
                                    <i class="bi bi-credit-card-2-front"></i>
                                    <div class="name">บัตรเครดิต / เดบิต</div>
                                    <div class="desc">Visa, Mastercard, JCB</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="channel-card" data-value="transfer">
                                    <i class="bi bi-bank"></i>
                                    <div class="name">โอนเงินผ่านธนาคาร</div>
                                    <div class="desc">แนบหลักฐานการโอน</div>
                                </div>
                            </div>
                        </div>

                        <label class="form-label">แนบสลิป/หลักฐานการชำระเงิน</label>
                        <input type="file" name="payment_proof" class="form-control mb-3"
                            accept="image/*,application/pdf">

                        <button type="button" class="btn btn-accent" id="confirmPaymentBtn" disabled>
                            <i class="bi bi-check-lg"></i> ยืนยันการชำระเงิน
                        </button>
                    </form>
                    <form action="{{ route('sales.cancel', $saleOrder) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('ยกเลิกคำสั่งนี้?')">
                        @csrf @method('PATCH')
                        <button class="btn btn-outline-danger mt-2">ยกเลิกคำสั่ง</button>
                    </form>
                @else
                    <div class="info-row">
                        <div class="label">ช่องทาง</div>
                        <div class="value">{{ $saleOrder->paymentMethodLabel() }}</div>
                    </div>
                    @if ($saleOrder->payment_proof_path)
                        <a href="{{ asset('storage/' . $saleOrder->payment_proof_path) }}" target="_blank"
                            class="btn btn-outline-secondary btn-sm mt-2"><i class="bi bi-file-earmark-check"></i>
                            ดูไฟล์ที่แนบไว้</a>
                    @endif
                @endif
            </div>
        </div>

        {{-- สรุปคำสั่งซื้อ / ใบเสร็จ --}}
        <div class="col-md-5">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-receipt"></i></div> สรุปคำสั่งซื้อ
                </div>
                <div class="price-summary">
                    <div class="price-row">
                        <span>ราคาแพ็กเกจ</span><span>฿{{ number_format($saleOrder->total_amount, 2) }}</span></div>
                    @if ($saleOrder->discount_amount > 0)
                        <div class="price-row discount">
                            <span>ส่วนลดคูปอง{{ $saleOrder->coupon_code ? " ($saleOrder->coupon_code)" : '' }}</span><span>-฿{{ number_format($saleOrder->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    @if ($saleOrder->points_discount_amount > 0)
                        <div class="price-row discount"><span>แลกแต้ม ({{ $saleOrder->points_used }}
                                แต้ม)</span><span>-฿{{ number_format($saleOrder->points_discount_amount, 2) }}</span></div>
                    @endif
                    @if ($saleOrder->credit_used > 0)
                        <div class="price-row discount">
                            <span>ใช้เครดิตคงเหลือ</span><span>-฿{{ number_format($saleOrder->credit_used, 2) }}</span>
                        </div>
                    @endif
                    <div class="price-row total">
                        <span>ยอดสุทธิ</span><span>฿{{ number_format($saleOrder->net_payable ?? $saleOrder->total_amount, 2) }}</span>
                    </div>
                </div>

                <hr class="my-3">
                <div class="fw-semibold small mb-2">{{ $saleOrder->taxInvoice->invoiceTypeLabel() }}</div>
                <div class="info-row">
                    <div class="label">เลขที่เอกสาร</div>
                    <div class="value">{{ $saleOrder->taxInvoice->invoice_no }}</div>
                </div>
                <div class="info-row">
                    <div class="label">ชื่อผู้ซื้อ</div>
                    <div class="value">{{ $saleOrder->taxInvoice->buyer_name }}</div>
                </div>
                @if ($saleOrder->taxInvoice->is_company)
                    <div class="info-row">
                        <div class="label">เลขผู้เสียภาษี</div>
                        <div class="value">{{ $saleOrder->taxInvoice->buyer_tax_id }}</div>
                    </div>
                @endif

                @if ($saleOrder->status === 'paid')
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm w-100 mt-3"><i
                            class="bi bi-printer"></i> พิมพ์เอกสาร</button>
                @else
                    <p class="text-muted small mt-3 mb-0"><i class="bi bi-info-circle"></i>
                        เลขที่เอกสารจริงจะออกให้หลังยืนยันการชำระเงินแล้วเท่านั้น</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.channel-card').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.channel-card').forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                const input = document.getElementById('paymentMethodInput');
                if (input) {
                    input.value = card.dataset.value;
                    document.getElementById('confirmPaymentBtn').disabled = false;
                }
            });
        });

        document.getElementById('confirmPaymentBtn')?.addEventListener('click', function() {
            if (!confirm('ยืนยันว่าตรวจสอบการชำระเงินแล้ว และต้องการยืนยันการสมัครเรียน?')) return;
            const form = document.getElementById('paymentForm');
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...';
            form.submit();
        });
    </script>
@endsection

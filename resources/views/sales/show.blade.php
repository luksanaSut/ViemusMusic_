@extends('layouts.app')
@section('title', $saleOrder->order_no)

@section('content')
    @php
        $isPending = $saleOrder->status === 'pending_payment';
        $isPaid = $saleOrder->status === 'paid';
        $isCancelled = $saleOrder->status === 'cancelled';
        $netAmount = $saleOrder->net_payable ?? $saleOrder->total_amount;
        $totalDiscount = $saleOrder->total_amount - $netAmount;
        $promptpayId = config('payment.promptpay_id');
        $bankAccNo = config('payment.bank_account_no');
        $studentName = $saleOrder->student->full_name;
        $initial = mb_substr(trim($studentName), 0, 1);
        $currentStep = $isPaid ? 4 : 3;
    @endphp

    <style>
        .so-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }

        .so-header .back-link {
            font-size: .8rem;
            color: var(--muted);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            margin-bottom: .35rem;
        }

        .so-header .back-link:hover {
            color: var(--accent);
        }

        .so-header h4 {
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: .6rem;
            flex-wrap: wrap;
        }

        .so-header .meta {
            font-size: .8rem;
            color: var(--muted);
            margin-top: .25rem;
        }

        .status-pill {
            font-size: .72rem;
            font-weight: 600;
            padding: .3rem .7rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-family: 'Sarabun', sans-serif;
        }

        .status-pill::before {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-pill.pending_payment {
            background: var(--amber-soft);
            color: var(--amber);
        }

        .status-pill.paid {
            background: var(--success-soft);
            color: var(--success);
        }

        .status-pill.cancelled {
            background: #eeece9;
            color: var(--muted);
        }

        /* Stepper */
        .stepper {
            display: flex;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
            gap: .5rem;
        }

        .step {
            flex: 1;
            display: flex;
            align-items: center;
            gap: .6rem;
            position: relative;
            min-width: 0;
        }

        .step:not(:last-child)::after {
            content: '';
            flex: 1;
            height: 2px;
            background: var(--border);
            border-radius: 2px;
            margin: 0 .25rem;
        }

        .step.done:not(:last-child)::after {
            background: var(--success);
        }

        .step-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            font-weight: 700;
            flex-shrink: 0;
            background: #eeece9;
            color: var(--muted);
            font-family: 'Prompt', sans-serif;
        }

        .step.done .step-dot {
            background: var(--success);
            color: #fff;
        }

        .step.current .step-dot {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 0 0 4px var(--accent-soft);
        }

        .step-label {
            font-size: .8rem;
            font-weight: 600;
            white-space: nowrap;
            color: var(--muted);
        }

        .step.current .step-label,
        .step.done .step-label {
            color: var(--ink);
        }

        @media (max-width: 767.98px) {
            .step-label {
                display: none;
            }

            .step.current .step-label {
                display: block;
            }
        }

        /* Cards */
        .so-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.35rem 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 2px rgba(28, 26, 23, .04);
        }

        .so-card-title {
            display: flex;
            align-items: center;
            gap: .7rem;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 1.1rem;
            font-family: 'Prompt', sans-serif;
        }

        .so-card-title .sub {
            font-family: 'Sarabun', sans-serif;
            font-weight: 400;
            font-size: .78rem;
            color: var(--muted);
            display: block;
        }

        .icon-badge {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: var(--accent-soft);
            color: var(--accent-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.05rem;
        }

        /* Student hero */
        .student-hero {
            display: flex;
            align-items: center;
            gap: .9rem;
            padding: .9rem 1rem;
            border-radius: 12px;
            background: var(--surface);
            margin-bottom: 1rem;
        }

        .avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .student-hero .name {
            font-family: 'Prompt', sans-serif;
            font-weight: 600;
            color: var(--ink);
            text-decoration: none;
        }

        .student-hero .name:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .75rem;
        }

        @media (max-width: 575.98px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        .info-tile {
            display: flex;
            gap: .7rem;
            align-items: flex-start;
            padding: .75rem .85rem;
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .info-tile.full {
            grid-column: 1 / -1;
        }

        .info-tile>i {
            color: var(--accent);
            font-size: 1rem;
            margin-top: .1rem;
        }

        .info-tile .label {
            font-size: .72rem;
            color: var(--muted);
            margin-bottom: .1rem;
        }

        .info-tile .value {
            font-weight: 600;
            font-size: .9rem;
            word-break: break-word;
        }

        /* Discount */
        .coupon-group .form-control {
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 600;
        }

        .coupon-group .form-control::placeholder {
            text-transform: none;
            letter-spacing: normal;
            font-weight: 400;
        }

        .toggle-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: .85rem 1rem;
            margin-bottom: .6rem;
            cursor: pointer;
            transition: border-color .15s, background .15s;
        }

        .toggle-card:hover {
            border-color: #c9c4bb;
        }

        .toggle-card.on {
            border-color: var(--success);
            background: var(--success-soft);
        }

        .toggle-card .t-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: var(--amber-soft);
            color: var(--amber);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .toggle-card .title {
            font-weight: 600;
            font-size: .9rem;
        }

        .toggle-card .desc {
            font-size: .78rem;
            color: var(--muted);
        }

        .toggle-card .form-switch .form-check-input {
            width: 2.6em;
            height: 1.4em;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--success);
            border-color: var(--success);
        }

        /* Payment channel */
        .channel-card {
            position: relative;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 1.1rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: .15s;
            height: 100%;
            background: var(--card);
            user-select: none;
        }

        .channel-card:hover {
            border-color: #c9c4bb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(28, 26, 23, .06);
        }

        .channel-card:focus-visible {
            outline: 3px solid var(--accent-soft);
            outline-offset: 2px;
        }

        .channel-card.active {
            border-color: var(--accent);
            background: var(--accent-soft);
            box-shadow: 0 0 0 1px var(--accent);
        }

        .channel-card .check {
            position: absolute;
            top: .55rem;
            right: .55rem;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            color: transparent;
            background: #fff;
        }

        .channel-card.active .check {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .channel-card>i {
            font-size: 1.7rem;
            color: var(--accent-dark);
        }

        .channel-card .name {
            font-weight: 700;
            margin-top: .45rem;
            font-size: .9rem;
            font-family: 'Prompt', sans-serif;
        }

        .channel-card .desc {
            font-size: .75rem;
            color: var(--muted);
        }

        .payment-detail-box {
            border: 1px dashed #cfcac2;
            background: var(--surface);
            border-radius: 14px;
            padding: 1.2rem;
            margin-top: 1rem;
            display: none;
        }

        .payment-detail-box.show {
            display: block;
            animation: fadeIn .2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .qr-wrap {
            text-align: center;
        }

        .qr-wrap img {
            max-width: 220px;
            width: 100%;
            border-radius: 12px;
            padding: 10px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(28, 26, 23, .08);
        }

        .qr-amount {
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: 1.35rem;
            color: var(--accent-dark);
            margin-top: .7rem;
        }

        .bank-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .75rem;
            padding: .65rem 0;
            border-bottom: 1px dashed var(--border);
            font-size: .9rem;
        }

        .bank-info-row:last-of-type {
            border-bottom: 0;
        }

        .bank-info-row .value {
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            text-align: right;
        }

        .copy-btn {
            font-size: .75rem;
            padding: .2rem .6rem;
            border-radius: 8px;
        }

        /* Upload */
        .upload-zone {
            display: flex;
            align-items: center;
            gap: .9rem;
            border: 1.5px dashed #cfcac2;
            border-radius: 14px;
            padding: 1rem 1.1rem;
            cursor: pointer;
            transition: .15s;
            background: var(--card);
        }

        .upload-zone:hover,
        .upload-zone.dragover {
            border-color: var(--accent);
            background: var(--accent-soft);
        }

        .upload-zone.has-file {
            border-style: solid;
            border-color: var(--success);
            background: var(--success-soft);
        }

        .upload-zone .u-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--accent-soft);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .upload-zone.has-file .u-icon {
            background: var(--success);
            color: #fff;
        }

        .upload-zone .u-title {
            font-weight: 600;
            font-size: .88rem;
            word-break: break-all;
        }

        .upload-zone .u-desc {
            font-size: .75rem;
            color: var(--muted);
        }

        .action-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1.25rem;
            padding-top: 1.1rem;
            border-top: 1px solid var(--border);
        }

        .action-bar .amount-label {
            font-size: .75rem;
            color: var(--muted);
        }

        .action-bar .amount {
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--accent-dark);
            line-height: 1.1;
        }

        .action-bar .btn-accent {
            padding: .6rem 1.4rem;
            font-weight: 600;
            border-radius: 10px;
        }

        .action-bar .btn-accent:disabled {
            opacity: .45;
        }

        .hint-select {
            font-size: .78rem;
            color: var(--muted);
        }

        /* Summary */
        .summary-sticky {
            position: sticky;
            top: 1rem;
        }

        .summary-hero {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            color: #fff;
            border-radius: 14px;
            padding: 1.1rem 1.2rem;
            margin-bottom: 1rem;
        }

        .summary-hero .lbl {
            font-size: .78rem;
            opacity: .8;
        }

        .summary-hero .amt {
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: 1.9rem;
            line-height: 1.2;
        }

        .summary-hero .saved {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            font-size: .75rem;
            background: rgba(255, 255, 255, .15);
            border-radius: 999px;
            padding: .15rem .6rem;
            margin-top: .35rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            gap: .75rem;
            font-size: .88rem;
            padding: .4rem 0;
        }

        .price-row.discount {
            color: var(--success);
        }

        .price-row.total {
            font-weight: 700;
            font-size: 1.05rem;
            border-top: 1px solid var(--border);
            margin-top: .4rem;
            padding-top: .75rem;
            color: var(--accent-dark);
            font-family: 'Prompt', sans-serif;
        }

        .doc-box {
            background: var(--surface);
            border-radius: 12px;
            padding: .9rem 1rem;
            margin-top: 1rem;
        }

        .doc-box .doc-title {
            font-weight: 600;
            font-size: .82rem;
            display: flex;
            align-items: center;
            gap: .4rem;
            margin-bottom: .5rem;
        }

        .doc-row {
            display: flex;
            justify-content: space-between;
            gap: .75rem;
            font-size: .82rem;
            padding: .25rem 0;
        }

        .doc-row .label {
            color: var(--muted);
        }

        .doc-row .value {
            font-weight: 600;
            text-align: right;
        }

        .notice {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .9rem;
            flex-wrap: wrap;
            border-radius: 14px;
            padding: .9rem 1.2rem;
            margin-bottom: 1.25rem;
        }

        .notice.pending {
            background: var(--amber-soft);
            color: var(--amber);
        }

        .notice.cancelled {
            background: #eeece9;
            color: var(--muted);
        }

        .notice.paid {
            background: var(--success-soft);
            color: var(--success);
        }

        .notice strong {
            color: var(--ink);
        }

        @media print {

            .no-print,
            nav,
            aside,
            .sidebar {
                display: none !important;
            }

            .so-card {
                box-shadow: none;
                break-inside: avoid;
            }

            .summary-sticky {
                position: static;
            }
        }
    </style>

    {{-- Header --}}
    <div class="so-header">
        <div>
            <a href="{{ route('sales.index') }}" class="back-link no-print"><i class="bi bi-arrow-left"></i>
                กลับไปรายการขาย</a>
            <h4>
                {{ $saleOrder->order_no }}
                <span class="status-pill {{ $saleOrder->status }}">{{ $saleOrder->statusLabel() }}</span>
            </h4>
            @if ($saleOrder->created_at)
                <div class="meta"><i class="bi bi-clock"></i> สร้างเมื่อ
                    {{ $saleOrder->created_at->format('d/m/Y H:i') }} น.</div>
            @endif
        </div>
        <div class="d-flex gap-2 no-print">
            @if ($isPending)
                <a href="{{ route('sales.edit', $saleOrder) }}" class="btn btn-outline-secondary btn-sm"><i
                        class="bi bi-pencil"></i> แก้ไขข้อมูล</a>
            @endif
            @if ($isPaid)
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer"></i>
                    พิมพ์</button>
                @if ($saleOrder->taxInvoice)
                    <a href="{{ route('sales.invoice.download', $saleOrder) }}" class="btn btn-accent btn-sm"><i
                            class="bi bi-download"></i> ดาวน์โหลดใบเสร็จ/ใบกำกับภาษี</a>
                @endif
            @endif
        </div>
    </div>

    {{-- Stepper / status notice --}}
    @if ($isCancelled)
        <div class="notice cancelled">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-x-circle fs-4"></i>
                <div><strong>คำสั่งนี้ถูกยกเลิกแล้ว</strong> — ไม่สามารถชำระเงินหรือแก้ไขได้</div>
            </div>
        </div>
    @else
        <div class="stepper no-print">
            @foreach (['สร้างคำสั่ง', 'ตรวจสอบข้อมูล', 'ชำระเงิน', 'เสร็จสิ้น'] as $i => $label)
                @php $n = $i + 1; @endphp
                <div class="step {{ $n < $currentStep || $isPaid ? 'done' : ($n === $currentStep ? 'current' : '') }}">
                    <div class="step-dot">
                        @if ($n < $currentStep || $isPaid)
                            <i class="bi bi-check-lg"></i>
                        @else
                            {{ $n }}
                        @endif
                    </div>
                    <div class="step-label">{{ $label }}</div>
                </div>
            @endforeach
        </div>

        @if ($isPending)
            <div class="notice pending no-print">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clipboard-check fs-4"></i>
                    <div><strong>รอชำระเงิน</strong> — ตรวจสอบข้อมูลให้ถูกต้อง ใช้ส่วนลด (ถ้ามี) แล้วเลือกช่องทางชำระเงิน
                    </div>
                </div>
            </div>
        @elseif ($isPaid)
            <div class="notice paid">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-4"></i>
                    <div><strong>ชำระเงินเรียบร้อยแล้ว</strong> — การสมัครเรียนได้รับการยืนยัน</div>
                </div>
            </div>
        @endif
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            {{-- ข้อมูลการสมัครเรียน --}}
            <div class="so-card">
                <div class="so-card-title">
                    <div class="icon-badge"><i class="bi bi-person-vcard"></i></div>
                    <div>ข้อมูลการสมัครเรียน</div>
                </div>

                <div class="student-hero">
                    <div class="avatar">{{ $initial }}</div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="text-muted small">นักเรียน</div>
                        <a href="{{ route('students.show', $saleOrder->student) }}"
                            class="name">{{ $studentName }}</a>
                    </div>
                    <a href="{{ route('students.show', $saleOrder->student) }}"
                        class="btn btn-sm btn-outline-secondary no-print" title="ดูโปรไฟล์นักเรียน"><i
                            class="bi bi-box-arrow-up-right"></i></a>
                </div>

                <div class="info-grid">
                    <div class="info-tile full">
                        <i class="bi bi-music-note-beamed"></i>
                        <div>
                            <div class="label">คอร์ส</div>
                            <div class="value">{{ $saleOrder->course->name }}
                                <span class="text-muted fw-normal small">· {{ $saleOrder->course->course_code }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="info-tile">
                        <i class="bi bi-person-badge"></i>
                        <div>
                            <div class="label">อาจารย์</div>
                            <div class="value">{{ $saleOrder->teacher->full_name ?? 'ให้ทางโรงเรียนจัดให้' }}</div>
                        </div>
                    </div>
                    <div class="info-tile">
                        <i class="bi bi-geo-alt"></i>
                        <div>
                            <div class="label">สาขา</div>
                            <div class="value">{{ $saleOrder->branch ?: '-' }}</div>
                        </div>
                    </div>
                    <div class="info-tile">
                        <i class="bi bi-laptop"></i>
                        <div>
                            <div class="label">รูปแบบการเรียน</div>
                            <div class="value">{{ $saleOrder->deliveryModeLabel() }}</div>
                        </div>
                    </div>
                    <div class="info-tile">
                        <i class="bi bi-calendar-week"></i>
                        <div>
                            <div class="label">วัน/เวลาที่สะดวก</div>
                            <div class="value">{{ $saleOrder->preferredDayLabel() ?: '-' }}
                                @if ($saleOrder->preferred_start_time)
                                    <div class="text-muted fw-normal small">{{ $saleOrder->preferred_start_time }} -
                                        {{ $saleOrder->preferred_end_time }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($saleOrder->notes)
                        <div class="info-tile full">
                            <i class="bi bi-sticky"></i>
                            <div>
                                <div class="label">หมายเหตุ</div>
                                <div class="value fw-normal">{{ $saleOrder->notes }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if ($isPending)
                {{-- ส่วนลด --}}
                <div class="so-card no-print">
                    <div class="so-card-title">
                        <div class="icon-badge"><i class="bi bi-tags"></i></div>
                        <div>ส่วนลดและสิทธิประโยชน์
                            <span class="sub">ใช้คูปอง แต้มสะสม หรือเครดิตคงเหลือเพื่อลดยอดชำระ</span>
                        </div>
                    </div>

                    <form action="{{ route('sales.apply-discount', $saleOrder) }}" method="POST" id="discountForm">
                        @csrf
                        <label class="form-label small fw-semibold">โค้ดโปรโมชัน / คูปอง</label>
                        <div class="input-group coupon-group mb-3">
                            <span class="input-group-text bg-white"><i class="bi bi-ticket-perforated"></i></span>
                            <input type="text" name="coupon_code" class="form-control"
                                placeholder="เช่น SUMMER25 / EARLY" value="{{ $saleOrder->promotion_code }}">
                            <button class="btn btn-accent px-3" type="submit">ใช้โค้ด</button>
                        </div>
                        @if ($saleOrder->promotion_code && $saleOrder->discount_amount > 0)
                            <div class="small text-success mb-3" style="margin-top:-.5rem"><i
                                    class="bi bi-check-circle-fill"></i> ใช้โค้ด
                                <strong>{{ $saleOrder->promotion_code }}</strong> แล้ว ลด
                                ฿{{ number_format($saleOrder->discount_amount, 2) }}</div>
                        @endif
                        <input type="hidden" name="use_points" value="{{ $saleOrder->points_used > 0 ? 1 : 0 }}">
                        <input type="hidden" name="use_credit" value="{{ $saleOrder->credit_used > 0 ? 1 : 0 }}">
                    </form>

                    <form action="{{ route('sales.apply-discount', $saleOrder) }}" method="POST" id="pointsForm">
                        @csrf
                        <input type="hidden" name="coupon_code" value="{{ $saleOrder->promotion_code }}">
                        <input type="hidden" name="use_credit" value="{{ $saleOrder->credit_used > 0 ? 1 : 0 }}">
                        <label class="toggle-card {{ $saleOrder->points_used > 0 ? 'on' : '' }}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="t-icon"><i class="bi bi-star-fill"></i></div>
                                <div>
                                    <div class="title">ใช้แต้มสะสม</div>
                                    <div class="desc">มี {{ number_format($saleOrder->student->pointBalance()) }} แต้ม ·
                                        แลกได้สูงสุด
                                        ฿{{ number_format($saleOrder->student->maxPointsRedeemableValue($saleOrder->total_amount - $saleOrder->discount_amount), 0) }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" name="use_points"
                                    value="1" {{ $saleOrder->points_used > 0 ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                            </div>
                        </label>
                    </form>

                    <form action="{{ route('sales.apply-discount', $saleOrder) }}" method="POST" id="creditForm">
                        @csrf
                        <input type="hidden" name="coupon_code" value="{{ $saleOrder->promotion_code }}">
                        <input type="hidden" name="use_points" value="{{ $saleOrder->points_used > 0 ? 1 : 0 }}">
                        <label class="toggle-card mb-0 {{ $saleOrder->credit_used > 0 ? 'on' : '' }}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="t-icon"><i class="bi bi-wallet2"></i></div>
                                <div>
                                    <div class="title">ใช้เครดิตคงเหลือ</div>
                                    <div class="desc">คงเหลือ
                                        ฿{{ number_format($saleOrder->student->creditBalance(), 2) }} · ใช้หักยอดได้</div>
                                </div>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" name="use_credit"
                                    value="1" {{ $saleOrder->credit_used > 0 ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                            </div>
                        </label>
                    </form>
                </div>
            @endif

            {{-- ช่องทางชำระเงิน --}}
            <div class="so-card">
                <div class="so-card-title">
                    <div class="icon-badge"><i class="bi bi-credit-card"></i></div>
                    <div>{{ $isPending ? 'เลือกช่องทางชำระเงิน' : 'ข้อมูลการชำระเงิน' }}
                        @if ($isPending)
                            <span class="sub">เลือกช่องทาง แนบหลักฐาน แล้วกดยืนยัน</span>
                        @endif
                    </div>
                </div>

                @if ($isPending)
                    <form action="{{ route('sales.confirm-payment', $saleOrder) }}" method="POST"
                        enctype="multipart/form-data" id="paymentForm">
                        @csrf
                        <input type="hidden" name="payment_method" id="paymentMethodInput" required>
                        <div class="row g-2 g-md-3" role="radiogroup" aria-label="ช่องทางชำระเงิน">
                            <div class="col-12 col-sm-4">
                                <div class="channel-card" data-value="promptpay" role="radio" tabindex="0"
                                    aria-checked="false">
                                    <span class="check"><i class="bi bi-check"></i></span>
                                    <i class="bi bi-qr-code-scan"></i>
                                    <div class="name">PromptPay / QR</div>
                                    <div class="desc">สแกนจ่ายผ่านแอปธนาคาร</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="channel-card" data-value="transfer" role="radio" tabindex="0"
                                    aria-checked="false">
                                    <span class="check"><i class="bi bi-check"></i></span>
                                    <i class="bi bi-bank"></i>
                                    <div class="name">โอนผ่านธนาคาร</div>
                                    <div class="desc">แนบหลักฐานการโอน</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="channel-card" data-value="credit_card" role="radio" tabindex="0"
                                    aria-checked="false">
                                    <span class="check"><i class="bi bi-check"></i></span>
                                    <i class="bi bi-credit-card-2-front"></i>
                                    <div class="name">บัตรเครดิต / เดบิต</div>
                                    <div class="desc">Visa, Mastercard, JCB</div>
                                </div>
                            </div>
                        </div>

                        {{-- รายละเอียดตามช่องทางที่เลือก --}}
                        <div class="payment-detail-box" id="detailPromptpay">
                            <div class="qr-wrap">
                                @if ($promptpayId)
                                    <img src="https://promptpay.io/{{ $promptpayId }}/{{ number_format($netAmount, 2, '.', '') }}.png"
                                        alt="PromptPay QR">
                                    <div class="qr-amount">฿{{ number_format($netAmount, 2) }}</div>
                                    <div class="text-muted small">สแกนด้วยแอปธนาคารเพื่อชำระเงิน จากนั้นแนบสลิปด้านล่าง</div>
                                @else
                                    <p class="text-muted small mb-0"><i class="bi bi-exclamation-circle"></i>
                                        ยังไม่ได้ตั้งค่าเลข PromptPay ของโรงเรียน (แก้ไขได้ที่ไฟล์ .env — PROMPTPAY_ID)</p>
                                @endif
                            </div>
                        </div>

                        <div class="payment-detail-box" id="detailTransfer">
                            <div class="bank-info-row"><span class="text-muted">ธนาคาร</span><span
                                    class="value">{{ config('payment.bank_name') }}</span></div>
                            <div class="bank-info-row"><span class="text-muted">ชื่อบัญชี</span><span
                                    class="value">{{ config('payment.bank_account_name') }}</span></div>
                            <div class="bank-info-row">
                                <span class="text-muted">เลขบัญชี</span>
                                <span class="d-flex align-items-center gap-2">
                                    <span class="value" id="bankAccNoText">{{ $bankAccNo ?: '-' }}</span>
                                    @if ($bankAccNo)
                                        <button type="button" class="btn btn-outline-secondary copy-btn"
                                            data-copy="{{ $bankAccNo }}"><i class="bi bi-copy"></i> คัดลอก</button>
                                    @endif
                                </span>
                            </div>
                            <div class="bank-info-row">
                                <span class="text-muted">ยอดโอน</span>
                                <span class="value text-primary-emphasis">฿{{ number_format($netAmount, 2) }}</span>
                            </div>
                        </div>

                        <div class="payment-detail-box" id="detailCreditCard">
                            <label class="form-label small fw-semibold">เลขอ้างอิงการทำรายการ (Ref. no / Auth code)
                                <span class="text-danger">*</span></label>
                            <input type="text" name="payment_reference" id="paymentReferenceInput"
                                class="form-control"
                                placeholder="เช่น เลขอ้างอิงจากใบบันทึกรายการบัตร หรือ 4 ตัวท้ายบัตร">
                            <div class="text-muted small mt-2"><i class="bi bi-info-circle"></i> ใช้เลขอ้างอิง/Auth code
                                ที่ปรากฏบนใบบันทึกรายการ (Sales Slip) จากเครื่องรูดบัตร
                                หรือถ่ายภาพหน้าจอแจ้งเตือนการชำระเงินสำเร็จแนบเป็นหลักฐานเพิ่มเติมด้านล่าง</div>
                        </div>

                        <label class="form-label small fw-semibold mt-3">แนบสลิป/หลักฐานการชำระเงิน
                            <span class="text-muted fw-normal" id="proofRequiredHint"></span></label>
                        <label class="upload-zone" id="uploadZone">
                            <div class="u-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                            <div class="flex-grow-1">
                                <div class="u-title" id="uploadTitle">คลิกเพื่อเลือกไฟล์ หรือลากไฟล์มาวาง</div>
                                <div class="u-desc">รองรับรูปภาพ หรือ PDF</div>
                            </div>
                            <input type="file" name="payment_proof" id="paymentProofInput" class="d-none"
                                accept="image/*,application/pdf">
                        </label>

                        <div class="action-bar">
                            <div>
                                <div class="amount-label">ยอดที่ต้องชำระ</div>
                                <div class="amount">฿{{ number_format($netAmount, 2) }}</div>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-accent" id="confirmPaymentBtn" disabled>
                                    <i class="bi bi-check-lg"></i> ยืนยันการชำระเงิน
                                </button>
                                <div class="hint-select mt-1" id="selectHint">กรุณาเลือกช่องทางชำระเงินก่อน</div>
                            </div>
                        </div>
                    </form>
                    <div class="text-center mt-3 no-print">
                        <form action="{{ route('sales.cancel', $saleOrder) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('ยืนยันการยกเลิกคำสั่งนี้? การดำเนินการนี้ไม่สามารถย้อนกลับได้')">
                            @csrf @method('PATCH')
                            <button class="btn btn-link btn-sm text-danger text-decoration-none"><i
                                    class="bi bi-x-circle"></i> ยกเลิกคำสั่งนี้</button>
                        </form>
                    </div>
                @else
                    <div class="info-grid">
                        <div class="info-tile">
                            <i class="bi bi-wallet2"></i>
                            <div>
                                <div class="label">ช่องทาง</div>
                                <div class="value">{{ $saleOrder->paymentMethodLabel() }}</div>
                            </div>
                        </div>
                        @if ($saleOrder->payment_reference)
                            <div class="info-tile">
                                <i class="bi bi-hash"></i>
                                <div>
                                    <div class="label">เลขอ้างอิง</div>
                                    <div class="value">{{ $saleOrder->payment_reference }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if ($saleOrder->payment_proof_path)
                        <a href="{{ asset('storage/' . $saleOrder->payment_proof_path) }}" target="_blank"
                            class="btn btn-outline-secondary btn-sm mt-3 no-print"><i
                                class="bi bi-file-earmark-check"></i> ดูหลักฐานการชำระเงิน</a>
                    @endif
                @endif
            </div>
        </div>

        {{-- สรุปคำสั่งซื้อ / ใบเสร็จ --}}
        <div class="col-lg-5">
            <div class="summary-sticky">
                <div class="so-card">
                    <div class="so-card-title">
                        <div class="icon-badge"><i class="bi bi-receipt"></i></div>
                        <div>สรุปคำสั่งซื้อ</div>
                    </div>

                    <div class="summary-hero">
                        <div class="lbl">{{ $isPaid ? 'ยอดที่ชำระแล้ว' : 'ยอดสุทธิที่ต้องชำระ' }}</div>
                        <div class="amt">฿{{ number_format($netAmount, 2) }}</div>
                        @if ($totalDiscount > 0)
                            <div class="saved"><i class="bi bi-piggy-bank"></i> ประหยัดไป
                                ฿{{ number_format($totalDiscount, 2) }}</div>
                        @endif
                    </div>

                    <div class="price-row">
                        <span>ราคาแพ็กเกจ</span><span>฿{{ number_format($saleOrder->total_amount, 2) }}</span>
                    </div>
                    @if ($saleOrder->auto_discount_amount > 0)
                        <div class="price-row discount">
                            <span><i class="bi bi-gift"></i>
                                ส่วนลดโปรโมชัน{{ $saleOrder->autoPromotion?->name ? " ({$saleOrder->autoPromotion->name})" : '' }}</span><span>-฿{{ number_format($saleOrder->auto_discount_amount, 2) }}</span>
                        </div>
                    @endif
                    @if ($saleOrder->discount_amount > 0)
                        <div class="price-row discount">
                            <span><i class="bi bi-ticket-perforated"></i>
                                ส่วนลดคูปอง{{ $saleOrder->promotion_code ? " ({$saleOrder->promotion_code})" : '' }}</span><span>-฿{{ number_format($saleOrder->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    @if ($saleOrder->points_discount_amount > 0)
                        <div class="price-row discount"><span><i class="bi bi-star"></i> แลกแต้ม
                                ({{ number_format($saleOrder->points_used) }}
                                แต้ม)</span><span>-฿{{ number_format($saleOrder->points_discount_amount, 2) }}</span>
                        </div>
                    @endif
                    @if ($saleOrder->credit_used > 0)
                        <div class="price-row discount">
                            <span><i class="bi bi-wallet2"></i>
                                ใช้เครดิตคงเหลือ</span><span>-฿{{ number_format($saleOrder->credit_used, 2) }}</span>
                        </div>
                    @endif
                    <div class="price-row total">
                        <span>ยอดสุทธิ</span><span>฿{{ number_format($netAmount, 2) }}</span>
                    </div>

                    @if ($saleOrder->taxInvoice)
                        <div class="doc-box">
                            <div class="doc-title"><i class="bi bi-file-earmark-text"></i>
                                {{ $saleOrder->taxInvoice->invoiceTypeLabel() }}</div>
                            <div class="doc-row">
                                <span class="label">เลขที่เอกสาร</span>
                                <span class="value">
                                    @if ($isPaid)
                                        {{ $saleOrder->taxInvoice->invoice_no }}
                                    @else
                                        <span class="text-muted fw-normal">ออกให้หลังชำระเงิน</span>
                                    @endif
                                </span>
                            </div>
                            <div class="doc-row">
                                <span class="label">ชื่อผู้ซื้อ</span>
                                <span class="value">{{ $saleOrder->taxInvoice->buyer_name }}</span>
                            </div>
                            @if ($saleOrder->taxInvoice->is_company)
                                <div class="doc-row">
                                    <span class="label">เลขผู้เสียภาษี</span>
                                    <span class="value">{{ $saleOrder->taxInvoice->buyer_tax_id }}</span>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted small mb-0 mt-3"><i class="bi bi-info-circle"></i>
                            คำสั่งนี้ไม่ได้ขอใบเสร็จ/ใบกำกับภาษีไว้</p>
                    @endif

                    @if ($isPaid)
                        <div class="d-flex gap-2 mt-3 no-print">
                            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm flex-grow-1"><i
                                    class="bi bi-printer"></i> พิมพ์</button>
                            @if ($saleOrder->taxInvoice)
                                <a href="{{ route('sales.invoice.download', $saleOrder) }}"
                                    class="btn btn-accent btn-sm flex-grow-1"><i class="bi bi-download"></i> ดาวน์โหลด
                                    PDF</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const detailMap = {
                promptpay: 'detailPromptpay',
                transfer: 'detailTransfer',
                credit_card: 'detailCreditCard'
            };
            const methodInput = document.getElementById('paymentMethodInput');
            const confirmBtn = document.getElementById('confirmPaymentBtn');
            const selectHint = document.getElementById('selectHint');
            const proofHint = document.getElementById('proofRequiredHint');
            const proofInput = document.getElementById('paymentProofInput');

            function selectChannel(card) {
                document.querySelectorAll('.channel-card').forEach(c => {
                    c.classList.remove('active');
                    c.setAttribute('aria-checked', 'false');
                });
                card.classList.add('active');
                card.setAttribute('aria-checked', 'true');

                document.querySelectorAll('.payment-detail-box').forEach(box => box.classList.remove('show'));
                document.getElementById(detailMap[card.dataset.value])?.classList.add('show');

                if (methodInput) {
                    methodInput.value = card.dataset.value;
                    confirmBtn.disabled = false;
                    if (selectHint) selectHint.style.display = 'none';
                    if (proofHint) {
                        proofHint.textContent = card.dataset.value === 'credit_card' ? '(ไม่บังคับ)' : '(จำเป็น)';
                    }
                }
            }

            document.querySelectorAll('.channel-card').forEach(card => {
                card.addEventListener('click', () => selectChannel(card));
                card.addEventListener('keydown', e => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        selectChannel(card);
                    }
                });
            });

            // ปุ่มคัดลอก
            document.querySelectorAll('[data-copy]').forEach(btn => {
                btn.addEventListener('click', () => {
                    navigator.clipboard?.writeText(btn.dataset.copy);
                    const original = btn.innerHTML;
                    btn.innerHTML = '<i class="bi bi-check2"></i> คัดลอกแล้ว';
                    btn.classList.replace('btn-outline-secondary', 'btn-success');
                    setTimeout(() => {
                        btn.innerHTML = original;
                        btn.classList.replace('btn-success', 'btn-outline-secondary');
                    }, 1800);
                });
            });

            // พื้นที่อัปโหลดไฟล์
            const zone = document.getElementById('uploadZone');
            if (zone && proofInput) {
                const title = document.getElementById('uploadTitle');
                const defaultTitle = title.textContent;
                const refresh = () => {
                    const file = proofInput.files[0];
                    zone.classList.toggle('has-file', !!file);
                    zone.querySelector('.u-icon i').className = file ? 'bi bi-file-earmark-check' :
                        'bi bi-cloud-arrow-up';
                    title.textContent = file ? file.name : defaultTitle;
                };
                proofInput.addEventListener('change', refresh);
                ['dragenter', 'dragover'].forEach(ev => zone.addEventListener(ev, e => {
                    e.preventDefault();
                    zone.classList.add('dragover');
                }));
                ['dragleave', 'drop'].forEach(ev => zone.addEventListener(ev, e => {
                    e.preventDefault();
                    zone.classList.remove('dragover');
                }));
                zone.addEventListener('drop', e => {
                    if (e.dataTransfer.files.length) {
                        proofInput.files = e.dataTransfer.files;
                        refresh();
                    }
                });
            }

            confirmBtn?.addEventListener('click', function() {
                const method = methodInput.value;
                if (method === 'credit_card' && !document.getElementById('paymentReferenceInput').value.trim()) {
                    alert('กรุณากรอกเลขอ้างอิงการทำรายการบัตรก่อนยืนยัน');
                    document.getElementById('paymentReferenceInput').focus();
                    return;
                }
                if ((method === 'promptpay' || method === 'transfer') && !proofInput.files.length) {
                    alert('กรุณาแนบสลิป/หลักฐานการชำระเงินก่อนยืนยัน');
                    zone?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return;
                }
                if (!confirm('ยืนยันว่าตรวจสอบการชำระเงินแล้ว และต้องการยืนยันการสมัครเรียน?')) return;
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...';
                document.getElementById('paymentForm').submit();
            });
        })();
    </script>
@endsection

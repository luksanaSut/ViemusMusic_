@extends('layouts.app')
@section('title', $storeSale->sale_no)

@section('content')
    <style>
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .order-header h1 {
            font-size: 1.6rem;
            margin-bottom: .15rem;
        }

        .order-header .meta {
            color: var(--muted, #6b655e);
            font-size: .85rem;
        }

        .stepper {
            display: flex;
            align-items: flex-start;
            margin: 1.5rem 0 2rem;
            overflow-x: auto;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            min-width: 90px;
            position: relative;
        }

        .step .dot {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            background: #e9e7e3;
            color: var(--muted, #6b655e);
            z-index: 2;
        }

        .step.done .dot {
            background: var(--success, #2f6f4e);
            color: #fff;
        }

        .step.current .dot {
            background: var(--accent, #1f3350);
            color: #fff;
        }

        .step .label {
            font-size: .78rem;
            font-weight: 600;
            color: var(--muted, #6b655e);
            margin-top: .5rem;
            text-align: center;
        }

        .step.done .label,
        .step.current .label {
            color: var(--ink, #1c1a17);
        }

        .step .line {
            position: absolute;
            top: 18px;
            left: calc(-50% + 18px);
            width: calc(100% - 36px);
            height: 2px;
            background: #e9e7e3;
            z-index: 1;
        }

        .step:first-child .line {
            display: none;
        }

        .step.done .line,
        .step.current .line {
            background: var(--success, #2f6f4e);
        }

        .step.cancelled-x .dot {
            background: #fbeae7;
            color: #b3392c;
        }

        .item-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 12px;
            padding: .8rem 1rem;
            margin-bottom: .6rem;
        }

        .item-thumb {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            background: #f4f3f1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-name {
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            font-size: .92rem;
        }

        .item-sub {
            font-size: .8rem;
            color: var(--muted, #6b655e);
        }

        .item-price {
            font-weight: 700;
            margin-left: auto;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: .35rem 0;
            font-size: .9rem;
        }

        .summary-row.total {
            font-weight: 700;
            font-size: 1.15rem;
            border-top: 1px solid var(--border, #e4e1dc);
            margin-top: .4rem;
            padding-top: .7rem;
        }

        .proof-box {
            background: var(--success-soft, #e7f2ec);
            color: var(--success, #2f6f4e);
            border-radius: 12px;
            padding: .9rem 1.1rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .88rem;
            font-weight: 600;
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

        .delivery-card {
            border: 1.5px solid var(--border, #e4e1dc);
            border-radius: 12px;
            padding: 1rem;
            cursor: pointer;
            transition: .15s;
            display: flex;
            align-items: center;
            gap: .8rem;
        }

        .delivery-card:hover {
            border-color: #c9c4bb;
        }

        .delivery-card.active {
            border-color: var(--accent, #1f3350);
            border-width: 2px;
            background: var(--accent-soft, #e7ebf1);
        }

        .delivery-card i {
            font-size: 1.4rem;
            color: var(--accent-dark, #13233a);
        }

        .delivery-card .name {
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            font-size: .9rem;
        }

        .delivery-card .desc {
            font-size: .75rem;
            color: var(--muted, #6b655e);
        }
    </style>

    @php
        // แปลงสถานะจริงในระบบ ให้เป็นลำดับขั้น stepper ที่เข้าใจง่าย
        $stepKeys = ['pending_payment', 'paid', 'preparing', 'done'];
        $currentStepIndex = match (true) {
            $storeSale->status === 'cancelled' => -1,
            $storeSale->status === 'pending_payment' => 0,
            $storeSale->status === 'completed' && in_array($storeSale->delivery_status, ['preparing']) => 2,
            $storeSale->status === 'completed' && in_array($storeSale->delivery_status, ['shipped', 'ready_for_pickup'])
                => 2,
            $storeSale->status === 'completed' && in_array($storeSale->delivery_status, ['picked_up', 'delivered'])
                => 3,
            $storeSale->status === 'completed' => 3,
            default => 0,
        };
        $stepLabels = ['รอชำระเงิน', 'ชำระเงินแล้ว', 'จัดเตรียม/จัดส่ง', 'สำเร็จ'];
    @endphp

    <div class="order-header">
        <div>
            <h1 class="page-title mb-0">{{ $storeSale->sale_no }}</h1>
            <div class="meta">{{ $storeSale->created_at->translatedFormat('d M Y') }} @if ($storeSale->status !== 'pending_payment')
                    · {{ $storeSale->paymentMethodLabel() }}
                @endif
            </div>
        </div>
        <a href="{{ route('store.my-orders') }}" class="btn-close" aria-label="ปิด"></a>
    </div>

    @if ($storeSale->status !== 'cancelled')
        <div class="stepper">
            @foreach ($stepLabels as $i => $label)
                <div class="step {{ $i < $currentStepIndex ? 'done' : ($i === $currentStepIndex ? 'current' : '') }}">
                    <div class="line"></div>
                    <div class="dot">
                        @if ($i < $currentStepIndex)
                            <i class="bi bi-check-lg"></i>@else{{ $i + 1 }}
                        @endif
                    </div>
                    <div class="label">{{ $label }}</div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-secondary mb-4"><i class="bi bi-x-circle"></i> คำสั่งซื้อนี้ถูกยกเลิกแล้ว</div>
    @endif

    <div class="row g-3">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">รายการสินค้า</h6>
                    @foreach ($storeSale->items as $item)
                        <div class="item-card">
                            <div class="item-thumb">
                                @if ($item->product && $item->product->image_path)
                                    <img src="{{ asset('storage/' . $item->product->image_path) }}">
                                @else
                                    <i class="bi bi-box-seam text-muted"></i>
                                @endif
                            </div>
                            <div>
                                <div class="item-name">{{ $item->product_name }}</div>
                                <div class="item-sub">฿{{ number_format($item->unit_price, 0) }} × {{ $item->quantity }}
                                </div>
                            </div>
                            <div class="item-price">฿{{ number_format($item->subtotal, 0) }}</div>
                        </div>
                    @endforeach

                    <div class="mt-3">
                        <div class="summary-row">
                            <span>ยอดรวม</span><span>฿{{ number_format($storeSale->total_amount, 0) }}</span>
                        </div>
                        <div class="summary-row total">
                            <span>รวมสุทธิ</span><span>฿{{ number_format($storeSale->total_amount, 0) }}</span>
                        </div>
                    </div>

                    @if ($storeSale->status === 'completed' && $storeSale->payment_proof_path)
                        <div class="proof-box mt-3"><i class="bi bi-check-circle-fill"></i> อัปโหลดหลักฐานการชำระเงินแล้ว
                        </div>
                    @endif
                </div>
            </div>

            @if ($storeSale->status === 'completed')
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2"><i class="bi bi-truck"></i> การรับสินค้า</h6>
                        <p class="mb-1"><strong>วิธีรับสินค้า:</strong> {{ $storeSale->deliveryMethodLabel() }}</p>
                        <p class="mb-1"><strong>สถานะ:</strong> <span
                                class="badge {{ $storeSale->deliveryStatusBadgeClass() }}">{{ $storeSale->deliveryStatusLabel() }}</span>
                        </p>
                        @if ($storeSale->delivery_method === 'delivery')
                            <p class="mb-1"><strong>ผู้รับ:</strong> {{ $storeSale->delivery_recipient_name }}
                                ({{ $storeSale->delivery_phone }})</p>
                            <p class="mb-1"><strong>ที่อยู่:</strong> {{ $storeSale->delivery_address }}</p>
                            @if ($storeSale->delivery_tracking_no)
                                <p class="mb-0"><strong>เลขพัสดุ:</strong> {{ $storeSale->delivery_tracking_no }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            @endif

            @if ($storeSale->status === 'pending_payment')
                <form action="{{ route('store.confirm-payment', $storeSale) }}" method="POST"
                    enctype="multipart/form-data" id="paymentForm">
                    @csrf

                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">วิธีรับสินค้า</h6>
                            <input type="hidden" name="delivery_method" id="deliveryMethodInput" required>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="delivery-card" data-value="pickup">
                                        <i class="bi bi-shop"></i>
                                        <div>
                                            <div class="name">รับที่ร้าน</div>
                                            <div class="desc">รับสินค้าด้วยตัวเองที่โรงเรียน</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="delivery-card" data-value="delivery">
                                        <i class="bi bi-truck"></i>
                                        <div>
                                            <div class="name">จัดส่งถึงบ้าน</div>
                                            <div class="desc">มีค่าจัดส่งตามจริง (แจ้งภายหลัง)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="deliveryAddressBox" style="display:none;">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label small">ชื่อผู้รับ *</label>
                                        <input type="text" name="delivery_recipient_name" id="recipientInput"
                                            class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">เบอร์โทรติดต่อ *</label>
                                        <input type="text" name="delivery_phone" id="phoneInput"
                                            class="form-control form-control-sm">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small">ที่อยู่จัดส่ง *</label>
                                        <textarea name="delivery_address" id="addressInput" class="form-control form-control-sm" rows="3"
                                            placeholder="บ้านเลขที่ ถนน ตำบล/แขวง อำเภอ/เขต จังหวัด รหัสไปรษณีย์"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div id="pickupInfoBox" class="alert alert-light border small mb-0" style="display:none;">
                                <i class="bi bi-info-circle"></i> รับสินค้าได้ที่ VIEMUS International School of Music
                                หลังยืนยันการชำระเงินและได้รับแจ้งว่าสินค้าพร้อมให้รับแล้ว
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">เลือกช่องทางชำระเงิน</h6>
                            <input type="hidden" name="payment_method" id="paymentMethodInput" required>
                            <div class="row g-2 mb-3">
                                <div class="col-4">
                                    <div class="channel-card" data-value="promptpay"><i class="bi bi-qr-code"></i>
                                        <div class="name">PromptPay/QR</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="channel-card" data-value="transfer"><i class="bi bi-bank"></i>
                                        <div class="name">โอนธนาคาร</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="channel-card" data-value="credit_card"><i
                                            class="bi bi-credit-card-2-front"></i>
                                        <div class="name">บัตรเครดิต</div>
                                    </div>
                                </div>
                            </div>

                            <div id="promptpayBox" class="text-center mb-3" style="display:none;">
                                @if (config('payment.promptpay_id'))
                                    <img src="https://promptpay.io/{{ config('payment.promptpay_id') }}/{{ number_format($storeSale->total_amount, 2, '.', '') }}.png"
                                        style="max-width:200px;">
                                @endif
                                <div class="fw-bold mt-1">ยอดชำระ ฿{{ number_format($storeSale->total_amount, 2) }}</div>
                            </div>
                            <div id="transferBox" class="mb-3" style="display:none;">
                                <p class="mb-1"><strong>ธนาคาร:</strong> {{ config('payment.bank_name') }}</p>
                                <p class="mb-1"><strong>ชื่อบัญชี:</strong> {{ config('payment.bank_account_name') }}
                                </p>
                                <p class="mb-0"><strong>เลขบัญชี:</strong> {{ config('payment.bank_account_no') }}</p>
                            </div>
                            <div id="creditCardBox" class="mb-3" style="display:none;">
                                <label class="form-label small">เลขอ้างอิงการทำรายการ *</label>
                                <input type="text" name="payment_reference" id="refInput"
                                    class="form-control form-control-sm">
                            </div>

                            <label class="form-label small">แนบสลิป/หลักฐานการชำระเงิน</label>
                            <input type="file" name="payment_proof" class="form-control mb-3"
                                accept="image/*,application/pdf">

                            <button type="button" class="btn btn-accent w-100" id="confirmBtn" disabled><i
                                    class="bi bi-check-lg"></i> ยืนยันการชำระเงิน</button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script>
        document.querySelectorAll('.delivery-card').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.delivery-card').forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                const value = card.dataset.value;
                document.getElementById('deliveryMethodInput').value = value;

                const addressBox = document.getElementById('deliveryAddressBox');
                const pickupBox = document.getElementById('pickupInfoBox');
                const recipientInput = document.getElementById('recipientInput');
                const phoneInput = document.getElementById('phoneInput');
                const addressInput = document.getElementById('addressInput');

                if (value === 'delivery') {
                    addressBox.style.display = 'block';
                    pickupBox.style.display = 'none';
                    recipientInput.required = true;
                    phoneInput.required = true;
                    addressInput.required = true;
                } else {
                    addressBox.style.display = 'none';
                    pickupBox.style.display = 'block';
                    recipientInput.required = false;
                    phoneInput.required = false;
                    addressInput.required = false;
                }
                checkReady();
            });
        });

        document.querySelectorAll('.channel-card').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.channel-card').forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                document.getElementById('paymentMethodInput').value = card.dataset.value;
                checkReady();
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

        function checkReady() {
            const deliveryEl = document.getElementById('deliveryMethodInput');
            const paymentEl = document.getElementById('paymentMethodInput');
            if (!deliveryEl || !paymentEl) return;
            document.getElementById('confirmBtn').disabled = !(deliveryEl.value && paymentEl.value);
        }

        document.getElementById('confirmBtn')?.addEventListener('click', function() {
            const method = document.getElementById('paymentMethodInput').value;
            const deliveryMethod = document.getElementById('deliveryMethodInput').value;

            if (!deliveryMethod) {
                alert('กรุณาเลือกวิธีรับสินค้า');
                return;
            }
            if (deliveryMethod === 'delivery') {
                if (!document.getElementById('recipientInput').value.trim()) {
                    alert('กรุณากรอกชื่อผู้รับ');
                    return;
                }
                if (!document.getElementById('phoneInput').value.trim()) {
                    alert('กรุณากรอกเบอร์โทรติดต่อ');
                    return;
                }
                if (!document.getElementById('addressInput').value.trim()) {
                    alert('กรุณากรอกที่อยู่จัดส่ง');
                    return;
                }
            }
            if (method === 'credit_card' && !document.getElementById('refInput').value.trim()) {
                alert('กรุณากรอกเลขอ้างอิงการทำรายการบัตร');
                return;
            }
            if (!confirm('ยืนยันการชำระเงิน?')) return;
            this.disabled = true;
            document.getElementById('paymentForm').submit();
        });
    </script>
@endsection

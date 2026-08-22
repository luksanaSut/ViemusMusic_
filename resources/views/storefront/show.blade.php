@extends('layouts.app')
@section('title', $storeSale->sale_no)

@section('content')
    <style>
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

    <h1 class="page-title mb-1">{{ $storeSale->sale_no }}</h1>
    <span class="badge {{ $storeSale->statusBadgeClass() }} mb-3">{{ $storeSale->statusLabel() }}</span>

    <div class="row g-3">
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">รายการสินค้า</h6>
                    <table class="table table-sm mb-0">
                        <tbody>
                            @foreach ($storeSale->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>x{{ $item->quantity }}</td>
                                    <td class="text-end">฿{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="2">ยอดรวม</td>
                                <td class="text-end">฿{{ number_format($storeSale->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if ($storeSale->status === 'pending_payment')
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">เลือกช่องทางชำระเงิน</h6>
                        <form action="{{ route('store.confirm-payment', $storeSale) }}" method="POST"
                            enctype="multipart/form-data" id="paymentForm">
                            @csrf
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
                                <p class="mb-1"><strong>ชื่อบัญชี:</strong> {{ config('payment.bank_account_name') }}</p>
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
                        </form>
                    </div>
                </div>
            @elseif($storeSale->status === 'completed')
                <div class="alert alert-success"><i class="bi bi-check-circle"></i> ชำระเงินสำเร็จเมื่อ
                    {{ $storeSale->confirmed_at?->format('d/m/Y H:i') }}</div>
            @else
                <div class="alert alert-secondary">คำสั่งซื้อนี้ถูกยกเลิก</div>
            @endif
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
            document.getElementById('paymentForm').submit();
        });
    </script>
@endsection

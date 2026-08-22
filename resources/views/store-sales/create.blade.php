@extends('layouts.app')
@section('title', 'ขายสินค้า')

@section('content')
    <style>
        .pos-product {
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 10px;
            padding: .6rem;
            cursor: pointer;
            text-align: center;
            transition: .15s;
        }

        .pos-product:hover {
            border-color: var(--accent, #1f3350);
        }

        .pos-product .name {
            font-size: .78rem;
            font-weight: 600;
            margin-top: .3rem;
        }

        .cart-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .5rem 0;
            border-bottom: 1px solid #f0efec;
        }
    </style>

    <h1 class="page-title mb-3"><i class="bi bi-cart-plus"></i> ขายสินค้า</h1>

    <div class="row g-3">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">เลือกสินค้า</h6>
                    <div class="row g-2" id="productGrid">
                        @foreach ($products as $p)
                            <div class="col-4">
                                <div class="pos-product" data-id="{{ $p->id }}" data-name="{{ $p->name }}"
                                    data-price="{{ $p->price }}" data-stock="{{ $p->stock_quantity }}">
                                    <i class="bi bi-box-seam fs-3 text-secondary"></i>
                                    <div class="name">{{ $p->name }}</div>
                                    <div class="small text-muted">฿{{ number_format($p->price, 0) }} · เหลือ
                                        {{ $p->stock_quantity }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <form action="{{ route('store-sales.store') }}" method="POST" id="saleForm">
                @csrf
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">รายการที่เลือก</h6>
                        <div id="cartBox">
                            <p class="text-muted small mb-0">ยังไม่ได้เลือกสินค้า</p>
                        </div>
                        <div id="cartInputs"></div>
                        <div class="d-flex justify-content-between fw-bold mt-2 pt-2 border-top">
                            <span>ยอดรวม</span><span id="totalDisplay">฿0.00</span>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <label class="form-label small">ลูกค้า (ถ้ามี)</label>
                        <select name="student_id" class="form-select form-select-sm mb-2">
                            <option value="">ลูกค้าทั่วไป</option>
                            @foreach ($students as $s)
                                <option value="{{ $s->id }}">{{ $s->full_name }} ({{ $s->student_code }})</option>
                            @endforeach
                        </select>
                        <input type="text" name="buyer_name" class="form-control form-control-sm mb-2"
                            placeholder="ชื่อลูกค้า (ถ้าไม่ใช่นักเรียนในระบบ)">
                        <label class="form-label small">ช่องทางชำระเงิน</label>
                        <select name="payment_method" class="form-select form-select-sm" required>
                            <option value="cash">เงินสด</option>
                            <option value="transfer">โอนเงิน</option>
                            <option value="credit_card">บัตรเครดิต</option>
                            <option value="promptpay">PromptPay/QR</option>
                        </select>
                    </div>
                </div>

                <button class="btn btn-accent w-100" id="submitBtn" disabled><i class="bi bi-check-lg"></i>
                    บันทึกการขาย</button>
            </form>
        </div>
    </div>

    <script>
        (function() {
            let cart = {};

            document.querySelectorAll('.pos-product').forEach(el => {
                el.addEventListener('click', () => {
                    const id = el.dataset.id,
                        name = el.dataset.name,
                        price = parseFloat(el.dataset.price),
                        stock = parseInt(el.dataset.stock);
                    if (!cart[id]) cart[id] = {
                        name,
                        price,
                        qty: 0,
                        stock
                    };
                    if (cart[id].qty < stock) cart[id].qty++;
                    renderCart();
                });
            });

            function renderCart() {
                const box = document.getElementById('cartBox');
                const inputs = document.getElementById('cartInputs');
                const ids = Object.keys(cart).filter(id => cart[id].qty > 0);

                if (ids.length === 0) {
                    box.innerHTML = '<p class="text-muted small mb-0">ยังไม่ได้เลือกสินค้า</p>';
                    inputs.innerHTML = '';
                    updateTotal();
                    return;
                }

                box.innerHTML = ids.map(id => `
            <div class="cart-row">
                <div class="flex-grow-1 small">${cart[id].name}<div class="text-muted">฿${cart[id].price.toLocaleString()} x ${cart[id].qty}</div></div>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.__adjustQty('${id}', -1)">-</button>
                <span>${cart[id].qty}</span>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.__adjustQty('${id}', 1)">+</button>
            </div>
        `).join('');

                inputs.innerHTML = ids.map((id, i) => `
            <input type="hidden" name="items[${i}][product_id]" value="${id}">
            <input type="hidden" name="items[${i}][quantity]" value="${cart[id].qty}">
        `).join('');

                updateTotal();
            }

            window.__adjustQty = function(id, delta) {
                cart[id].qty = Math.max(0, Math.min(cart[id].stock, cart[id].qty + delta));
                renderCart();
            };

            function updateTotal() {
                const total = Object.values(cart).reduce((sum, i) => sum + i.price * i.qty, 0);
                document.getElementById('totalDisplay').textContent = '฿' + total.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                });
                document.getElementById('submitBtn').disabled = total === 0;
            }

            document.getElementById('saleForm').addEventListener('submit', function() {
                document.getElementById('submitBtn').disabled = true;
            });
        })();
    </script>
@endsection

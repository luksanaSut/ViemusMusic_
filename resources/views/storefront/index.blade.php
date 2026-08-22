@extends('layouts.app')
@section('title', 'ร้านค้า Music Store')

@section('content')
    <style>
        .shop-product {
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 14px;
            overflow: hidden;
            cursor: pointer;
            transition: .15s;
            height: 100%;
        }

        .shop-product:hover {
            border-color: var(--accent, #1f3350);
            box-shadow: 0 3px 12px rgba(28, 26, 23, .08);
        }

        .shop-cover {
            height: 120px;
            background: #f4f3f1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .shop-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .shop-body {
            padding: .8rem;
        }

        .shop-body .name {
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            font-size: .88rem;
        }

        .cart-float {
            position: sticky;
            top: 80px;
        }

        .cart-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .5rem 0;
            border-bottom: 1px solid #f0efec;
            font-size: .85rem;
        }
    </style>

    <h1 class="page-title mb-1"><i class="bi bi-shop"></i> ร้านค้า Music Store</h1>
    <div class="page-sub mb-3">เลือกซื้ออุปกรณ์ดนตรีและของใช้ต่างๆ ได้ที่นี่</div>

    <div class="row g-3 mb-3">
        <div class="col-md-6"><input type="text" id="searchInput" class="form-control" placeholder="ค้นหาสินค้า..."></div>
    </div>

    <div class="row g-3">
        <div class="col-md-8">
            <div class="row g-3" id="productGrid">
                @foreach ($products as $p)
                    <div class="col-md-4 product-item" data-name="{{ mb_strtolower($p->name) }}">
                        <div class="shop-product" data-id="{{ $p->id }}" data-name="{{ $p->name }}"
                            data-price="{{ $p->price }}" data-stock="{{ $p->stock_quantity }}">
                            <div class="shop-cover">
                                @if ($p->image_path)
                                    <img src="{{ asset('storage/' . $p->image_path) }}">
                                @else<i class="bi bi-box-seam fs-1 text-muted"></i>
                                @endif
                            </div>
                            <div class="shop-body">
                                <div class="name">{{ $p->name }}</div>
                                <div class="text-muted small">{{ $p->category->name ?? '' }}</div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <span class="fw-bold">฿{{ number_format($p->price, 0) }}</span>
                                    <span class="badge text-bg-light border">เหลือ {{ $p->stock_quantity }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($products->isEmpty())
                <div class="alert alert-light border text-center text-muted">ยังไม่มีสินค้าให้เลือกซื้อในขณะนี้</div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="cart-float">
                <form action="{{ route('store.checkout') }}" method="POST" id="checkoutForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2">ตะกร้าสินค้า</h6>

                            @if ($students->count() > 1)
                                <label class="form-label small">ซื้อให้</label>
                                <select name="student_id" class="form-select form-select-sm mb-3" required>
                                    @foreach ($students as $s)
                                        <option value="{{ $s->id }}">{{ $s->full_name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="student_id" value="{{ $students->first()->id }}">
                                <div class="small text-muted mb-2">สั่งซื้อสำหรับ: {{ $students->first()->full_name }}
                                </div>
                            @endif

                            <div id="cartBox">
                                <p class="text-muted small mb-0">ยังไม่ได้เลือกสินค้า</p>
                            </div>
                            <div id="cartInputs"></div>
                            <div class="d-flex justify-content-between fw-bold mt-2 pt-2 border-top">
                                <span>ยอดรวม</span><span id="totalDisplay">฿0.00</span>
                            </div>
                            <button class="btn btn-accent w-100 mt-3" id="checkoutBtn" disabled><i
                                    class="bi bi-bag-check"></i> สั่งซื้อ</button>
                        </div>
                    </div>
                </form>
                <div class="text-center mt-2">
                    <a href="{{ route('store.my-orders') }}" class="small">ดูประวัติคำสั่งซื้อของฉัน</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            let cart = {};

            document.getElementById('searchInput').addEventListener('input', function() {
                const q = this.value.trim().toLowerCase();
                document.querySelectorAll('.product-item').forEach(el => {
                    el.style.display = el.dataset.name.includes(q) ? '' : 'none';
                });
            });

            document.querySelectorAll('.shop-product').forEach(el => {
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
                <div class="flex-grow-1">${cart[id].name}<div class="text-muted">฿${cart[id].price.toLocaleString()} x ${cart[id].qty}</div></div>
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
                document.getElementById('checkoutBtn').disabled = total === 0;
            }

            document.getElementById('checkoutForm').addEventListener('submit', function() {
                document.getElementById('checkoutBtn').disabled = true;
            });
        })();
    </script>
@endsection

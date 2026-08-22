@extends('layouts.app')
@section('title', 'แก้ไขคำสั่งซื้อ')

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

        .shop-product.in-cart {
            border-color: var(--accent, #1f3350);
            background: var(--accent-soft, #e7ebf1);
        }

        .shop-cover {
            height: 110px;
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
            padding: .7rem;
        }

        .shop-body .name {
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            font-size: .85rem;
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

    <div class="breadcrumb-sm"><a href="{{ route('store.my-orders') }}"
            class="text-decoration-none text-muted">คำสั่งซื้อของฉัน</a> <i class="bi bi-chevron-right small"></i> แก้ไข
        {{ $storeSale->sale_no }}</div>
    <h1 class="page-title mb-1"><i class="bi bi-pencil"></i> แก้ไขคำสั่งซื้อ</h1>
    <div class="page-sub mb-3">เพิ่ม/ลบ/ปรับจำนวนสินค้าได้ตามต้องการ ก่อนไปชำระเงิน</div>

    <div class="row g-3">
        <div class="col-md-8">
            <input type="text" id="searchInput" class="form-control mb-3" placeholder="ค้นหาสินค้า...">
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
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <span class="fw-bold">฿{{ number_format($p->price, 0) }}</span>
                                    <span class="badge text-bg-light border">เหลือ {{ $p->stock_quantity }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-4">
            <div class="cart-float">
                <form action="{{ route('store.update', $storeSale) }}" method="POST" id="editForm">
                    @csrf @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2">รายการในคำสั่งซื้อ</h6>
                            <div id="cartBox"></div>
                            <div id="cartInputs"></div>
                            <div class="d-flex justify-content-between fw-bold mt-2 pt-2 border-top">
                                <span>ยอดรวม</span><span id="totalDisplay">฿0.00</span>
                            </div>
                            <button class="btn btn-accent w-100 mt-3" id="saveBtn" disabled><i class="bi bi-check-lg"></i>
                                บันทึกการแก้ไข</button>
                            <a href="{{ route('store.my-orders') }}"
                                class="btn btn-outline-secondary w-100 mt-2">ยกเลิกการแก้ไข</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function() {
            // เติมตะกร้าเริ่มต้นจากรายการเดิมในคำสั่งซื้อ
            let cart = {};
            @foreach ($storeSale->items as $item)
                @if ($item->product)
                    cart["{{ $item->product_id }}"] = {
                        name: @json($item->product_name),
                        price: {{ $item->product->price }},
                        qty: {{ $item->quantity }},
                        stock: {{ $item->product->stock_quantity }} +
                            {{ $item->quantity }} // คืนสิทธิ์จำนวนเดิมกลับมาก่อน เพราะยังไม่ตัดสต็อกจริง
                    };
                @endif
            @endforeach

            document.getElementById('searchInput').addEventListener('input', function() {
                const q = this.value.trim().toLowerCase();
                document.querySelectorAll('.product-item').forEach(el => {
                    el.style.display = el.dataset.name.includes(q) ? '' : 'none';
                });
            });

            function markActiveCards() {
                document.querySelectorAll('.shop-product').forEach(el => {
                    el.classList.toggle('in-cart', !!(cart[el.dataset.id] && cart[el.dataset.id].qty > 0));
                });
            }

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
                    if (cart[id].qty < cart[id].stock) cart[id].qty++;
                    renderCart();
                });
            });

            function renderCart() {
                const box = document.getElementById('cartBox');
                const inputs = document.getElementById('cartInputs');
                const ids = Object.keys(cart).filter(id => cart[id].qty > 0);

                if (ids.length === 0) {
                    box.innerHTML =
                        '<p class="text-muted small mb-0">ตะกร้าว่างเปล่า เลือกสินค้าเพิ่ม หรือกดยกเลิกคำสั่งซื้อในหน้ารายการ</p>';
                    inputs.innerHTML = '';
                    updateTotal();
                    markActiveCards();
                    return;
                }

                box.innerHTML = ids.map(id => `
            <div class="cart-row">
                <div class="flex-grow-1">${cart[id].name}<div class="text-muted">฿${cart[id].price.toLocaleString()} x ${cart[id].qty}</div></div>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.__adjustQty('${id}', -1)">-</button>
                <span>${cart[id].qty}</span>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.__adjustQty('${id}', 1)">+</button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.__removeItem('${id}')"><i class="bi bi-trash"></i></button>
            </div>
        `).join('');

                inputs.innerHTML = ids.map((id, i) => `
            <input type="hidden" name="items[${i}][product_id]" value="${id}">
            <input type="hidden" name="items[${i}][quantity]" value="${cart[id].qty}">
        `).join('');

                updateTotal();
                markActiveCards();
            }

            window.__adjustQty = function(id, delta) {
                cart[id].qty = Math.max(0, Math.min(cart[id].stock, cart[id].qty + delta));
                renderCart();
            };
            window.__removeItem = function(id) {
                cart[id].qty = 0;
                renderCart();
            };

            function updateTotal() {
                const total = Object.values(cart).reduce((sum, i) => sum + i.price * i.qty, 0);
                document.getElementById('totalDisplay').textContent = '฿' + total.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                });
                document.getElementById('saveBtn').disabled = total === 0;
            }

            document.getElementById('editForm').addEventListener('submit', function() {
                document.getElementById('saveBtn').disabled = true;
            });

            renderCart();
        })();
    </script>
@endsection

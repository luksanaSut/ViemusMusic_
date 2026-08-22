@extends('layouts.app')
@section('title', 'จัดการสินค้า')

@section('content')
    <style>
        .product-card {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 14px;
            overflow: hidden;
            height: 100%;
        }

        .product-cover {
            height: 140px;
            background: #f4f3f1;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .product-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-body {
            padding: .9rem 1rem;
        }

        .product-body .sku {
            font-size: .7rem;
            color: var(--muted, #6b655e);
        }

        .product-body .name {
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            margin: .15rem 0 .4rem;
        }

        .stock-badge.low {
            background: #fbeae7;
            color: #b3392c;
        }

        .stock-badge.ok {
            background: var(--success-soft, #e7f2ec);
            color: var(--success, #2f6f4e);
        }
    </style>

    <div class="breadcrumb-sm">Music Store <i class="bi bi-chevron-right small"></i> จัดการสินค้า</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title">จัดการสินค้า</h1>
            @if ($lowStockCount > 0)
                <a href="{{ route('products.index', ['low_stock_only' => 1]) }}"
                    class="badge text-bg-danger text-decoration-none"><i class="bi bi-exclamation-triangle"></i> สินค้าใกล้หมด
                    {{ $lowStockCount }} รายการ</a>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('product-categories.index') }}" class="btn btn-outline-secondary"><i class="bi bi-tags"></i>
                หมวดหมู่</a>
            <a href="{{ route('products.create') }}" class="btn btn-accent"><i class="bi bi-plus-lg"></i> เพิ่มสินค้า</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-4"><input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาชื่อ/รหัสสินค้า"></div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">ทุกหมวดหมู่</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected(request('category_id') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="active" @selected(request('status') == 'active')>พร้อมขาย</option>
                        <option value="inactive" @selected(request('status') == 'inactive')>ปิดการขาย</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid"><button class="btn btn-outline-secondary">ค้นหา</button></div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        @forelse($products as $p)
            <div class="col-md-3">
                <div class="product-card">
                    <div class="product-cover">
                        @if ($p->image_path)
                            <img src="{{ asset('storage/' . $p->image_path) }}">
                        @else
                            <i class="bi bi-box-seam fs-1 text-muted"></i>
                        @endif
                    </div>
                    <div class="product-body">
                        <div class="sku">{{ $p->sku }} @if ($p->category)
                                · {{ $p->category->name }}
                            @endif
                        </div>
                        <div class="name">{{ $p->name }}</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">฿{{ number_format($p->price, 0) }}</span>
                            <span class="badge stock-badge {{ $p->isLowStock() ? 'low' : 'ok' }}">{{ $p->stock_quantity }}
                                ชิ้น</span>
                        </div>
                        <span class="badge {{ $p->statusBadgeClass() }} mt-2">{{ $p->statusLabel() }}</span>
                        <div class="d-flex gap-1 mt-2">
                            <a href="{{ route('products.show', $p) }}"
                                class="btn btn-sm btn-outline-secondary flex-fill"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('products.edit', $p) }}" class="btn btn-sm btn-outline-primary flex-fill"><i
                                    class="bi bi-pencil"></i></a>
                            <form action="{{ route('products.destroy', $p) }}" method="POST"
                                onsubmit="return confirm('ลบสินค้านี้?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border text-center text-muted">ยังไม่มีสินค้าในระบบ</div>
            </div>
        @endforelse
    </div>
    <div class="mt-3">{{ $products->links() }}</div>
@endsection

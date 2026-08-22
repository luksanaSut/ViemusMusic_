@extends('layouts.app')
@section('title', 'ระบบสต็อกสินค้า')

@section('content')
    <h1 class="page-title mb-3"><i class="bi bi-boxes"></i> ระบบสต็อกสินค้า</h1>

    @if ($lowStockProducts->isNotEmpty())
        <div class="alert alert-warning">
            <strong><i class="bi bi-exclamation-triangle"></i> สินค้าใกล้หมด/หมดสต็อก ({{ $lowStockProducts->count() }}
                รายการ):</strong>
            <ul class="mb-0 mt-1 small">
                @foreach ($lowStockProducts as $p)
                    <li><a href="{{ route('products.show', $p) }}">{{ $p->name }}</a> — เหลือ {{ $p->stock_quantity }}
                        ชิ้น (เกณฑ์ {{ $p->reorder_level }})</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-4"><input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาสินค้า"></div>
                <div class="col-md-3">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="low_stock_only" value="1"
                            {{ request('low_stock_only') ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="form-check-label small">แสดงเฉพาะที่ใกล้หมด</label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>รหัส</th>
                        <th>สินค้า</th>
                        <th>คงเหลือ</th>
                        <th>เกณฑ์แจ้งเตือน</th>
                        <th>สถานะ</th>
                        <th class="text-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr class="{{ $p->isLowStock() ? 'table-warning' : '' }}">
                            <td>{{ $p->sku }}</td>
                            <td>{{ $p->name }}</td>
                            <td class="fw-bold">{{ $p->stock_quantity }} ชิ้น</td>
                            <td>{{ $p->reorder_level }}</td>
                            <td>
                                @if ($p->isOutOfStock())
                                    <span class="badge text-bg-danger">หมดสต็อก</span>
                                @elseif($p->isLowStock())
                                    <span class="badge text-bg-warning">ใกล้หมด</span>
                                @else<span class="badge text-bg-success">ปกติ</span>
                                @endif
                            </td>
                            <td class="text-end"><a href="{{ route('products.show', $p) }}"
                                    class="btn btn-sm btn-outline-secondary">จัดการสต็อก</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">ยังไม่มีสินค้าในระบบ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $products->links() }}</div>
    </div>
@endsection

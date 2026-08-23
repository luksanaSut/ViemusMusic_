@extends('layouts.app')
@section('title', 'บันทึกรายจ่าย')

@section('content')

    <div class="breadcrumb-sm mb-2">
        การเงิน
        <i class="bi bi-chevron-right small"></i>
        บันทึกรายจ่าย
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="bi bi-receipt-cutoff me-1"></i>
                บันทึกรายจ่าย
            </h1>
            <div class="page-sub">
                บันทึกและดูประวัติค่าใช้จ่ายของโรงเรียนตามหมวดหมู่
            </div>
        </div>

        <a href="{{ route('expenses.create') }}" class="btn btn-accent">
            <i class="bi bi-plus-lg me-1"></i>
            เพิ่มรายจ่ายใหม่
        </a>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">ทุกหมวดหมู่</option>
                        <option value="course" @selected(request('category') == 'course')>คอร์สเรียน</option>
                        <option value="product_cost" @selected(request('category') == 'product_cost')>ค่าซื้อสินค้า</option>
                        <option value="rent" @selected(request('category') == 'rent')>ค่าเช่า</option>
                        <option value="staff" @selected(request('category') == 'staff')>ค่าพนักงาน</option>
                        <option value="maintenance" @selected(request('category') == 'maintenance')>ค่าซ่อมบำรุง</option>
                        <option value="other" @selected(request('category') == 'other')>ค่าใช้จ่ายอื่นๆ</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control"
                        placeholder="จากวันที่">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control"
                        placeholder="ถึงวันที่">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i> ค้นหา</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">
                        <i class="bi bi-list-ul me-1 text-primary"></i>
                        รายการทั้งหมด
                    </div>
                    <div class="text-muted small mt-1">
                        ทั้งหมด {{ number_format($expenses->total()) }} รายการ
                    </div>
                </div>
                <span class="badge text-bg-light border">
                    รวม ฿{{ number_format($expenses->sum('amount'), 2) }} (หน้านี้)
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">วันที่</th>
                        <th>หมวดหมู่</th>
                        <th>หัวข้อ</th>
                        <th class="text-end">จำนวนเงิน</th>
                        <th>บันทึกโดย</th>
                        <th class="text-end pe-3">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td class="ps-3">{{ $expense->expense_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge text-bg-light border">
                                    <i class="bi {{ $expense->categoryIcon() }} me-1"></i>
                                    {{ $expense->categoryLabel() }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $expense->title }}</div>
                                @if ($expense->note)
                                    <div class="text-muted small">{{ Str::limit($expense->note, 60) }}</div>
                                @endif
                            </td>
                            <td class="text-end fw-semibold text-danger">-฿{{ number_format($expense->amount, 2) }}</td>
                            <td class="small text-muted">{{ $expense->recorded_by ?: '-' }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-outline-secondary" title="แก้ไข">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('ยืนยันการลบรายการ {{ $expense->title }} ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบ">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-receipt fs-1 text-secondary"></i>
                                <div class="fw-semibold mt-2">ยังไม่มีรายการรายจ่าย</div>
                                <div class="text-muted small mt-1">เพิ่มรายจ่ายใหม่ได้จากปุ่มด้านบน</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($expenses->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <div class="text-muted small">
                        แสดง {{ $expenses->firstItem() }} - {{ $expenses->lastItem() }}
                        จาก {{ $expenses->total() }} รายการ
                    </div>
                    <div>
                        {{ $expenses->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

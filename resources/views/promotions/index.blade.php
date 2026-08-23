@extends('layouts.app')
@section('title', 'โปรโมชัน / คูปอง')

@section('content')

    <div class="breadcrumb-sm mb-2">
        งานขาย
        <i class="bi bi-chevron-right small"></i>
        โปรโมชัน / คูปอง
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="bi bi-tags me-1"></i>
                โปรโมชัน / คูปอง
            </h1>
            <div class="page-sub">
                จัดการโปรโมชันอัตโนมัติและคูปองส่วนลด ใช้ได้ทั้งคอร์สเรียนและสินค้า
            </div>
        </div>

        <a href="{{ route('promotions.create') }}" class="btn btn-accent">
            <i class="bi bi-plus-lg me-1"></i>
            เพิ่มโปรโมชัน/คูปองใหม่
        </a>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาโค้ด หรือชื่อโปรโมชัน">
                </div>
                <div class="col-md-2">
                    <select name="scope" class="form-select">
                        <option value="">ทุกขอบเขต</option>
                        <option value="course" @selected(request('scope') == 'course')>คอร์สเรียน</option>
                        <option value="product" @selected(request('scope') == 'product')>สินค้า</option>
                        <option value="both" @selected(request('scope') == 'both')>ทั้งคู่</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">ทุกประเภท</option>
                        <option value="promotion" @selected(request('type') == 'promotion')>โปรโมชันอัตโนมัติ</option>
                        <option value="coupon" @selected(request('type') == 'coupon')>คูปอง (กรอกโค้ด)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="is_active" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="1" @selected(request('is_active') === '1')>เปิดใช้งาน</option>
                        <option value="0" @selected(request('is_active') === '0')>ปิดใช้งาน</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
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
                        <i class="bi bi-tags me-1 text-primary"></i>
                        รายการทั้งหมด
                    </div>
                    <div class="text-muted small mt-1">
                        ทั้งหมด {{ number_format($promotions->total()) }} รายการ
                    </div>
                </div>
                <span class="badge text-bg-light border">
                    {{ number_format($promotions->total()) }} รายการ
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">โค้ด / ประเภท</th>
                        <th>ชื่อโปรโมชัน</th>
                        <th>ส่วนลด</th>
                        <th>สิทธิ์การใช้</th>
                        <th>ระยะเวลา</th>
                        <th>ขอบเขต</th>
                        <th>สถานะ</th>
                        <th class="text-end pe-3">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promotion)
                        <tr>
                            <td class="ps-3">
                                @if ($promotion->isCoupon())
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-6">
                                        <i class="bi bi-ticket-perforated me-1"></i>
                                        {{ $promotion->code }}
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="bi bi-magic me-1"></i>
                                        อัตโนมัติ
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="fw-semibold">{{ $promotion->name }}</div>
                            </td>

                            <td>
                                <span class="fw-semibold">{{ $promotion->discountLabel() }}</span>
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-people text-muted"></i>
                                    <span>{{ $promotion->used_count }} / {{ $promotion->max_uses ?? '∞' }}</span>
                                </div>
                                @if ($promotion->per_customer_limit)
                                    <div class="text-muted small mt-1">
                                        สูงสุด {{ $promotion->per_customer_limit }} ครั้ง/คน
                                    </div>
                                @endif
                            </td>

                            <td>
                                @if ($promotion->valid_from || $promotion->valid_to)
                                    <div class="small">
                                        <i class="bi bi-calendar3 me-1 text-muted"></i>
                                        {{ optional($promotion->valid_from)->format('d/m/Y') ?: '-' }}
                                    </div>
                                    <div class="small text-muted mt-1">
                                        ถึง {{ optional($promotion->valid_to)->format('d/m/Y') ?: '-' }}
                                    </div>
                                @else
                                    <span class="text-muted small">
                                        <i class="bi bi-infinity me-1"></i>
                                        ไม่จำกัดเวลา
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="badge text-bg-light border">
                                    @if ($promotion->scope === 'course')
                                        <i class="bi bi-journal-bookmark me-1"></i> คอร์สเรียน
                                    @elseif ($promotion->scope === 'product')
                                        <i class="bi bi-box-seam me-1"></i> สินค้า
                                    @else
                                        <i class="bi bi-collection me-1"></i> ทั้งคู่
                                    @endif
                                </span>
                                <div class="text-muted small mt-1">
                                    @if ($promotion->applies_to_all)
                                        ใช้ได้ทุกรายการ
                                    @else
                                        {{ $promotion->courses->count() + $promotion->products->count() }} รายการที่เลือก
                                    @endif
                                </div>
                            </td>

                            <td>
                                <form action="{{ route('promotions.toggle-status', $promotion) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm p-0 border-0">
                                        @if ($promotion->is_active)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="bi bi-check-circle me-1"></i> เปิดใช้งาน
                                            </span>
                                        @else
                                            <span class="badge text-bg-secondary">
                                                <i class="bi bi-x-circle me-1"></i> ปิดใช้งาน
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>

                            <td class="text-end pe-3">
                                <a href="{{ route('promotions.edit', $promotion) }}" class="btn btn-sm btn-outline-secondary" title="แก้ไข">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('promotions.destroy', $promotion) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('ยืนยันการลบ {{ $promotion->code ?? $promotion->name }} ?')">
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
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-ticket-perforated fs-1 text-secondary"></i>
                                <div class="fw-semibold mt-2">ยังไม่มีโปรโมชัน/คูปอง</div>
                                <div class="text-muted small mt-1">เพิ่มโปรโมชันหรือคูปองใหม่ได้จากปุ่มด้านบน</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($promotions->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <div class="text-muted small">
                        แสดง {{ $promotions->firstItem() }} - {{ $promotions->lastItem() }}
                        จาก {{ $promotions->total() }} รายการ
                    </div>
                    <div>
                        {{ $promotions->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

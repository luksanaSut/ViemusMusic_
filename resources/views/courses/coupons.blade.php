@extends('layouts.app')
@section('title', 'Promotion / Coupon')

@section('content')
    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> คอร์สเรียน <i
            class="bi bi-chevron-right small"></i> Promotion / Coupon</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h1 class="page-title">Promotion / Coupon</h1>
        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>
            กลับไปคอร์สเรียน</a>
    </div>

    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-plus-circle"></i> เพิ่มคูปอง/โปรโมชันใหม่</div>
        <form action="{{ route('coupons.store') }}" method="POST" id="couponForm">
            @csrf
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">โค้ดคูปอง *</label>
                    <input type="text" name="code" class="form-control" maxlength="30"
                        style="text-transform:uppercase" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">ชื่อโปรโมชัน *</label>
                    <input type="text" name="name" class="form-control" maxlength="150" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">ประเภทส่วนลด *</label>
                    <select name="discount_type" class="form-select" required>
                        <option value="percent">เปอร์เซ็นต์ (%)</option>
                        <option value="fixed">จำนวนเงิน (บาท)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">มูลค่าส่วนลด *</label>
                    <input type="number" step="0.01" min="0" name="discount_value" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">จำนวนสิทธิ์ (ครั้ง)</label>
                    <input type="number" min="1" name="max_uses" class="form-control" placeholder="ไม่จำกัด">
                </div>
                <div class="col-md-3">
                    <label class="form-label">เริ่มใช้ได้</label>
                    <input type="date" name="valid_from" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">หมดอายุ</label>
                    <input type="date" name="valid_to" class="form-control">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="applies_to_all_courses"
                            id="appliesAll" value="1" checked>
                        <label class="form-check-label">ใช้ได้กับทุกคอร์ส</label>
                    </div>
                </div>
                <div class="col-12" id="courseScopeBox" style="display:none;">
                    <label class="form-label">เลือกคอร์สที่ใช้คูปองนี้ได้</label>
                    <div class="row g-1" style="max-height:200px; overflow-y:auto;">
                        @foreach ($courses as $c)
                            <div class="col-md-4">
                                <div class="chip-check">
                                    <input class="form-check-input me-1" type="checkbox" name="course_ids[]"
                                        value="{{ $c->id }}" id="crs{{ $c->id }}">
                                    <label class="form-check-label" for="crs{{ $c->id }}">{{ $c->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <button class="btn btn-accent mt-3"><i class="bi bi-plus-lg"></i> เพิ่มคูปอง</button>
        </form>
    </div>

    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-tag"></i> รายการคูปองทั้งหมด</div>
        <div class="table-responsive">
            <table class="table table-sm table-clean">
                <thead>
                    <tr>
                        <th>โค้ด</th>
                        <th>ชื่อ</th>
                        <th>ส่วนลด</th>
                        <th>สิทธิ์การใช้</th>
                        <th>ระยะเวลา</th>
                        <th>ขอบเขต</th>
                        <th>สถานะ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td class="fw-semibold">{{ $coupon->code }}</td>
                            <td>{{ $coupon->name }}</td>
                            <td>{{ $coupon->discountLabel() }}</td>
                            <td>{{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}</td>
                            <td class="small">
                                @if ($coupon->valid_from || $coupon->valid_to)
                                    {{ optional($coupon->valid_from)->format('d/m/Y') ?: '-' }} -
                                    {{ optional($coupon->valid_to)->format('d/m/Y') ?: '-' }}
                                @else
                                    ไม่จำกัดเวลา
                                @endif
                            </td>
                            <td class="small">
                                {{ $coupon->applies_to_all_courses ? 'ทุกคอร์ส' : $coupon->courses->count() . ' คอร์ส' }}
                            </td>
                            <td>
                                <form action="{{ route('coupons.toggle-status', $coupon) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm border-0 p-0">
                                        <span
                                            class="badge {{ $coupon->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                            {{ $coupon->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                        </span>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('coupons.destroy', $coupon) }}" method="POST"
                                    onsubmit="return confirm('ลบคูปองนี้?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">ยังไม่มีคูปอง</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-2">{{ $coupons->links() }}</div>
    </div>

    <script>
        document.getElementById('appliesAll').addEventListener('change', function() {
            document.getElementById('courseScopeBox').style.display = this.checked ? 'none' : 'block';
        });
        document.getElementById('couponForm').addEventListener('submit', function() {
            const code = this.querySelector('[name=code]');
            code.value = code.value.toUpperCase();
        });
    </script>
@endsection

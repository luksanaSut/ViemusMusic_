@extends('layouts.app')
@section('title', 'Promotion / Coupon')

@section('content')

    {{-- Breadcrumb --}}
    <div class="breadcrumb-sm mb-2">
        งานวิชาการ
        <i class="bi bi-chevron-right small"></i>
        คอร์สเรียน
        <i class="bi bi-chevron-right small"></i>
        Promotion / Coupon
    </div>


    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <h1 class="page-title mb-1">
                <i class="bi bi-tags me-1"></i>
                Promotion / Coupon
            </h1>
            <div class="page-sub">
                จัดการคูปอง ส่วนลด และโปรโมชันสำหรับคอร์สเรียน
            </div>
        </div>

        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            กลับไปคอร์สเรียน
        </a>

    </div>


    {{-- =====================================================
        เพิ่ม Coupon
    ====================================================== --}}
    <div class="card mb-4">

        <div class="card-header bg-white py-3">

            <div class="d-flex align-items-center gap-2">

                <div>
                    <div class="fw-semibold">
                        <i class="bi bi-plus-circle me-1 text-primary"></i>
                        เพิ่มคูปอง / โปรโมชันใหม่
                    </div>

                    <div class="text-muted small mt-1">
                        กำหนดรายละเอียด ส่วนลด และเงื่อนไขการใช้งาน
                    </div>
                </div>

            </div>

        </div>


        <div class="card-body p-4">

            <form action="{{ route('coupons.store') }}" method="POST" id="couponForm">

                @csrf


                {{-- ข้อมูลคูปอง --}}
                <div class="fw-semibold mb-3">
                    <i class="bi bi-ticket-perforated me-1"></i>
                    ข้อมูลคูปอง
                </div>


                <div class="row g-3">

                    {{-- Code --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            โค้ดคูปอง
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text" name="code" value="{{ old('code') }}"
                            class="form-control text-uppercase fw-semibold" maxlength="30" placeholder="เช่น MUSIC10"
                            required>

                        <div class="form-text">
                            รหัสสำหรับกรอกเพื่อรับส่วนลด
                        </div>

                    </div>


                    {{-- Name --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            ชื่อโปรโมชัน
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                            maxlength="150" placeholder="เช่น ส่วนลดเปิดเทอม" required>

                    </div>


                    {{-- Discount Type --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="form-label">
                            ประเภทส่วนลด
                            <span class="text-danger">*</span>
                        </label>

                        <select name="discount_type" id="discountType" class="form-select" required>

                            <option value="percent" @selected(old('discount_type') == 'percent')>
                                เปอร์เซ็นต์ (%)
                            </option>

                            <option value="fixed" @selected(old('discount_type') == 'fixed')>
                                จำนวนเงิน (บาท)
                            </option>

                        </select>

                    </div>


                    {{-- Discount Value --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            มูลค่าส่วนลด
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <input type="number" step="0.01" min="0" name="discount_value"
                                value="{{ old('discount_value') }}" class="form-control" placeholder="0" required>

                            <span class="input-group-text" id="discountUnit">
                                %
                            </span>

                        </div>

                    </div>

                </div>


                <hr class="my-4">


                {{-- เงื่อนไข --}}
                <div class="fw-semibold mb-3">
                    <i class="bi bi-sliders me-1"></i>
                    เงื่อนไขการใช้งาน
                </div>


                <div class="row g-3">

                    {{-- Max uses --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            จำนวนสิทธิ์
                        </label>

                        <div class="input-group">

                            <input type="number" min="1" name="max_uses" value="{{ old('max_uses') }}"
                                class="form-control" placeholder="ไม่จำกัด">

                            <span class="input-group-text">
                                ครั้ง
                            </span>

                        </div>

                        <div class="form-text">
                            เว้นว่างหากไม่จำกัดจำนวนการใช้
                        </div>

                    </div>


                    {{-- Start --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            เริ่มใช้ได้
                        </label>

                        <input type="date" name="valid_from" value="{{ old('valid_from') }}" class="form-control">

                    </div>


                    {{-- End --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            หมดอายุ
                        </label>

                        <input type="date" name="valid_to" value="{{ old('valid_to') }}" class="form-control">

                    </div>

                </div>


                <hr class="my-4">


                {{-- Course Scope --}}
                <div class="fw-semibold mb-3">
                    <i class="bi bi-journal-bookmark me-1"></i>
                    คอร์สที่เข้าร่วม
                </div>


                <div class="border rounded-3 p-3 bg-light">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="fw-semibold">
                                ใช้ได้กับทุกคอร์ส
                            </div>

                            <div class="text-muted small">
                                เปิดตัวเลือกนี้หากต้องการให้คูปองใช้ได้กับทุกคอร์ส
                            </div>

                        </div>


                        <div class="form-check form-switch">

                            <input class="form-check-input" type="checkbox" role="switch" name="applies_to_all_courses"
                                id="appliesAll" value="1" @checked(old('applies_to_all_courses', true))>

                        </div>

                    </div>

                </div>


                {{-- เลือก Course --}}
                <div id="courseScopeBox" class="border rounded-3 p-3 mt-3 bg-light" style="display:none;">

                    <div class="mb-3">

                        <div class="fw-semibold">
                            เลือกคอร์ส
                        </div>

                        <div class="text-muted small">
                            เลือกคอร์สที่สามารถใช้คูปองนี้ได้
                        </div>

                    </div>


                    <div class="row g-2">

                        @foreach ($courses as $c)
                            <div class="col-lg-4 col-md-6">

                                <div class="border rounded-3 bg-white p-2 h-100">

                                    <div class="form-check">

                                        <input class="form-check-input" type="checkbox" name="course_ids[]"
                                            value="{{ $c->id }}" id="crs{{ $c->id }}"
                                            @checked(in_array($c->id, old('course_ids', [])))>

                                        <label class="form-check-label" for="crs{{ $c->id }}">

                                            {{ $c->name }}

                                        </label>

                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>


                {{-- Submit --}}
                <div class="d-flex justify-content-end mt-4">

                    <button type="submit" class="btn btn-accent px-4">

                        <i class="bi bi-plus-lg me-1"></i>
                        เพิ่มคูปอง

                    </button>

                </div>

            </form>

        </div>

    </div>



    {{-- =====================================================
        รายการ Coupon
    ====================================================== --}}
    <div class="card">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="fw-semibold">
                        <i class="bi bi-tags me-1 text-primary"></i>
                        รายการคูปองทั้งหมด
                    </div>

                    <div class="text-muted small mt-1">
                        ทั้งหมด {{ number_format($coupons->total()) }} รายการ
                    </div>

                </div>

                <span class="badge text-bg-light border">
                    {{ number_format($coupons->total()) }} คูปอง
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th class="ps-3">โค้ดคูปอง</th>
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

                    @forelse($coupons as $coupon)
                        <tr>

                            {{-- Code --}}
                            <td class="ps-3">

                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-6">

                                    <i class="bi bi-ticket-perforated me-1"></i>

                                    {{ $coupon->code }}

                                </span>

                            </td>


                            {{-- Name --}}
                            <td>

                                <div class="fw-semibold">
                                    {{ $coupon->name }}
                                </div>

                            </td>


                            {{-- Discount --}}
                            <td>

                                <span class="fw-semibold">
                                    {{ $coupon->discountLabel() }}
                                </span>

                            </td>


                            {{-- Usage --}}
                            <td>

                                <div class="d-flex align-items-center gap-2">

                                    <i class="bi bi-people text-muted"></i>

                                    <span>
                                        {{ $coupon->used_count }}
                                        /
                                        {{ $coupon->max_uses ?? '∞' }}
                                    </span>

                                </div>

                                @if ($coupon->max_uses)
                                    @php
                                        $percent =
                                            $coupon->max_uses > 0
                                                ? min(100, ($coupon->used_count / $coupon->max_uses) * 100)
                                                : 0;
                                    @endphp

                                    <div class="progress mt-2" role="progressbar" style="height:4px;">

                                        <div class="progress-bar" style="width: {{ $percent }}%">
                                        </div>

                                    </div>
                                @endif

                            </td>


                            {{-- Date --}}
                            <td>

                                @if ($coupon->valid_from || $coupon->valid_to)
                                    <div class="small">

                                        <i class="bi bi-calendar3 me-1 text-muted"></i>

                                        {{ optional($coupon->valid_from)->format('d/m/Y') ?: '-' }}

                                    </div>

                                    <div class="small text-muted mt-1">

                                        ถึง
                                        {{ optional($coupon->valid_to)->format('d/m/Y') ?: '-' }}

                                    </div>
                                @else
                                    <span class="text-muted small">

                                        <i class="bi bi-infinity me-1"></i>
                                        ไม่จำกัดเวลา

                                    </span>
                                @endif

                            </td>


                            {{-- Scope --}}
                            <td>

                                @if ($coupon->applies_to_all_courses)
                                    <span class="badge text-bg-light border">

                                        <i class="bi bi-journals me-1"></i>
                                        ทุกคอร์ส

                                    </span>
                                @else
                                    <span class="badge text-bg-light border">

                                        <i class="bi bi-journal-bookmark me-1"></i>

                                        {{ $coupon->courses->count() }}
                                        คอร์ส

                                    </span>
                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                <form action="{{ route('coupons.toggle-status', $coupon) }}" method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('PATCH')


                                    <button type="submit" class="btn btn-sm p-0 border-0">

                                        @if ($coupon->is_active)
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle">

                                                <i class="bi bi-check-circle me-1"></i>
                                                เปิดใช้งาน

                                            </span>
                                        @else
                                            <span class="badge text-bg-secondary">

                                                <i class="bi bi-x-circle me-1"></i>
                                                ปิดใช้งาน

                                            </span>
                                        @endif

                                    </button>

                                </form>

                            </td>


                            {{-- Action --}}
                            <td class="text-end pe-3">

                                <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('ยืนยันการลบคูปอง {{ $coupon->code }} ?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบคูปอง">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="8" class="text-center py-5">

                                <i class="bi bi-ticket-perforated fs-1 text-secondary"></i>

                                <div class="fw-semibold mt-2">
                                    ยังไม่มีคูปอง
                                </div>

                                <div class="text-muted small mt-1">
                                    เพิ่มคูปองหรือโปรโมชันใหม่ได้จากแบบฟอร์มด้านบน
                                </div>

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($coupons->hasPages())
            <div class="card-footer bg-white">

                <div
                    class="d-flex flex-column flex-md-row
                            justify-content-between align-items-md-center gap-2">

                    <div class="text-muted small">

                        แสดง
                        {{ $coupons->firstItem() }}
                        -
                        {{ $coupons->lastItem() }}

                        จาก

                        {{ $coupons->total() }}
                        รายการ

                    </div>

                    <div>
                        {{ $coupons->links() }}
                    </div>

                </div>

            </div>
        @endif

    </div>



    {{-- =====================================================
        Javascript
    ====================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const appliesAll = document.getElementById('appliesAll');
            const courseScopeBox = document.getElementById('courseScopeBox');

            function updateCourseScope() {
                courseScopeBox.style.display =
                    appliesAll.checked ? 'none' : 'block';
            }

            appliesAll.addEventListener('change', updateCourseScope);
            updateCourseScope();


            // เปลี่ยนหน่วยส่วนลด
            const discountType = document.getElementById('discountType');
            const discountUnit = document.getElementById('discountUnit');

            function updateDiscountUnit() {
                discountUnit.textContent =
                    discountType.value === 'percent' ? '%' : 'บาท';
            }

            discountType.addEventListener('change', updateDiscountUnit);
            updateDiscountUnit();


            // เปลี่ยน Coupon Code เป็นตัวพิมพ์ใหญ่
            const couponForm = document.getElementById('couponForm');
            const code = couponForm.querySelector('[name="code"]');

            code.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

        });
    </script>

@endsection

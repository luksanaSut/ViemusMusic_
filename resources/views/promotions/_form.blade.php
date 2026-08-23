@php
    $promotion = $promotion ?? null;
    $selectedCourseIds = old('course_ids', $promotion?->courses->pluck('id')->all() ?? []);
    $selectedProductIds = old('product_ids', $promotion?->products->pluck('id')->all() ?? []);
@endphp

<div class="card mb-4">
    <div class="card-body p-4">

        {{-- ข้อมูลพื้นฐาน --}}
        <div class="fw-semibold mb-3">
            <i class="bi bi-ticket-perforated me-1"></i>
            ข้อมูลโปรโมชัน / คูปอง
        </div>

        <div class="row g-3">

            {{-- Code --}}
            <div class="col-lg-3 col-md-6">
                <label class="form-label">โค้ดคูปอง</label>
                <input type="text" name="code" value="{{ old('code', $promotion?->code) }}"
                    class="form-control text-uppercase fw-semibold" maxlength="30" placeholder="เว้นว่าง = อัตโนมัติ">
                <div class="form-text">
                    เว้นว่างไว้ = โปรโมชันอัตโนมัติ (ไม่ต้องกรอกโค้ด) · กรอกโค้ด = คูปองที่ลูกค้าต้องกรอกเอง
                </div>
            </div>

            {{-- Name --}}
            <div class="col-lg-4 col-md-6">
                <label class="form-label">ชื่อโปรโมชัน <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $promotion?->name) }}" class="form-control"
                    maxlength="150" placeholder="เช่น ส่วนลดเปิดเทอม" required>
            </div>

            {{-- Discount Type --}}
            <div class="col-lg-2 col-md-6">
                <label class="form-label">ประเภทส่วนลด <span class="text-danger">*</span></label>
                <select name="discount_type" id="discountType" class="form-select" required>
                    <option value="percent" @selected(old('discount_type', $promotion?->discount_type) == 'percent')>เปอร์เซ็นต์ (%)</option>
                    <option value="fixed" @selected(old('discount_type', $promotion?->discount_type) == 'fixed')>จำนวนเงิน (บาท)</option>
                    <option value="spend_get" @selected(old('discount_type', $promotion?->discount_type) == 'spend_get')>ซื้อครบ X ลด Y</option>
                </select>
            </div>

            {{-- Discount Value --}}
            <div class="col-lg-3 col-md-6">
                <label class="form-label">มูลค่าส่วนลด (Y) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" step="0.01" min="0" name="discount_value"
                        value="{{ old('discount_value', $promotion?->discount_value) }}" class="form-control"
                        placeholder="0" required>
                    <span class="input-group-text" id="discountUnit">%</span>
                </div>
            </div>

            {{-- Min spend (spend_get only) --}}
            <div class="col-lg-3 col-md-6" id="minSpendBox" style="display:none;">
                <label class="form-label">ยอดสั่งซื้อขั้นต่ำ (X)</label>
                <div class="input-group">
                    <input type="number" step="0.01" min="0" name="min_spend"
                        value="{{ old('min_spend', $promotion?->min_spend) }}" class="form-control" placeholder="0">
                    <span class="input-group-text">บาท</span>
                </div>
                <div class="form-text">ยอดสั่งซื้อต้องถึงจำนวนนี้ก่อนจึงจะได้ส่วนลด</div>
            </div>

        </div>

        <hr class="my-4">

        {{-- เงื่อนไข --}}
        <div class="fw-semibold mb-3">
            <i class="bi bi-sliders me-1"></i>
            เงื่อนไขการใช้งาน
        </div>

        <div class="row g-3">

            <div class="col-md-3">
                <label class="form-label">จำนวนสิทธิ์รวม</label>
                <div class="input-group">
                    <input type="number" min="1" name="max_uses" value="{{ old('max_uses', $promotion?->max_uses) }}"
                        class="form-control" placeholder="ไม่จำกัด">
                    <span class="input-group-text">ครั้ง</span>
                </div>
                <div class="form-text">เว้นว่างหากไม่จำกัดจำนวนการใช้รวม</div>
            </div>

            <div class="col-md-3">
                <label class="form-label">จำนวนสิทธิ์ต่อคน</label>
                <div class="input-group">
                    <input type="number" min="1" name="per_customer_limit"
                        value="{{ old('per_customer_limit', $promotion?->per_customer_limit) }}" class="form-control"
                        placeholder="ไม่จำกัด">
                    <span class="input-group-text">ครั้ง</span>
                </div>
                <div class="form-text">เว้นว่างหากไม่จำกัดต่อลูกค้า 1 คน</div>
            </div>

            <div class="col-md-3">
                <label class="form-label">เริ่มใช้ได้</label>
                <input type="date" name="valid_from"
                    value="{{ old('valid_from', optional($promotion?->valid_from)->format('Y-m-d')) }}"
                    class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">หมดอายุ</label>
                <input type="date" name="valid_to"
                    value="{{ old('valid_to', optional($promotion?->valid_to)->format('Y-m-d')) }}"
                    class="form-control">
            </div>

        </div>

        <div class="form-check form-switch mt-3">
            <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1"
                id="isActive" @checked(old('is_active', $promotion?->is_active ?? true))>
            <label class="form-check-label" for="isActive">เปิดใช้งาน</label>
        </div>

        <hr class="my-4">

        {{-- Scope --}}
        <div class="fw-semibold mb-3">
            <i class="bi bi-bullseye me-1"></i>
            ขอบเขตการใช้งาน
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label">ใช้ได้กับ <span class="text-danger">*</span></label>
                <select name="scope" id="scopeSelect" class="form-select" required>
                    <option value="course" @selected(old('scope', $promotion?->scope) == 'course')>คอร์สเรียนเท่านั้น</option>
                    <option value="product" @selected(old('scope', $promotion?->scope) == 'product')>สินค้าเท่านั้น</option>
                    <option value="both" @selected(old('scope', $promotion?->scope) == 'both')>ทั้งคอร์สเรียนและสินค้า</option>
                </select>
            </div>
        </div>

        <div class="border rounded-3 p-3 bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">ใช้ได้กับทุกรายการในขอบเขตที่เลือก</div>
                    <div class="text-muted small">เปิดตัวเลือกนี้หากต้องการให้ใช้ได้กับทุกคอร์ส/สินค้าโดยไม่ต้องเลือก</div>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="applies_to_all"
                        id="appliesAll" value="1" @checked(old('applies_to_all', $promotion?->applies_to_all ?? true))>
                </div>
            </div>
        </div>

        {{-- เลือก Course --}}
        <div id="courseScopeBox" class="border rounded-3 p-3 mt-3 bg-light" style="display:none;">
            <div class="mb-3">
                <div class="fw-semibold">เลือกคอร์ส</div>
                <div class="text-muted small">เลือกคอร์สที่สามารถใช้โปรโมชัน/คูปองนี้ได้</div>
            </div>
            <div class="row g-2">
                @foreach ($courses as $c)
                    <div class="col-lg-4 col-md-6">
                        <div class="border rounded-3 bg-white p-2 h-100">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="course_ids[]"
                                    value="{{ $c->id }}" id="crs{{ $c->id }}"
                                    @checked(in_array($c->id, $selectedCourseIds))>
                                <label class="form-check-label" for="crs{{ $c->id }}">{{ $c->name }}</label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- เลือก Product --}}
        <div id="productScopeBox" class="border rounded-3 p-3 mt-3 bg-light" style="display:none;">
            <div class="mb-3">
                <div class="fw-semibold">เลือกสินค้า</div>
                <div class="text-muted small">เลือกสินค้าที่สามารถใช้โปรโมชัน/คูปองนี้ได้</div>
            </div>
            <div class="row g-2">
                @foreach ($products as $p)
                    <div class="col-lg-4 col-md-6">
                        <div class="border rounded-3 bg-white p-2 h-100">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="product_ids[]"
                                    value="{{ $p->id }}" id="prd{{ $p->id }}"
                                    @checked(in_array($p->id, $selectedProductIds))>
                                <label class="form-check-label" for="prd{{ $p->id }}">{{ $p->name }}</label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const appliesAll = document.getElementById('appliesAll');
        const scopeSelect = document.getElementById('scopeSelect');
        const courseScopeBox = document.getElementById('courseScopeBox');
        const productScopeBox = document.getElementById('productScopeBox');

        function updateScopeBoxes() {
            const scope = scopeSelect.value;
            const showCourse = !appliesAll.checked && (scope === 'course' || scope === 'both');
            const showProduct = !appliesAll.checked && (scope === 'product' || scope === 'both');
            courseScopeBox.style.display = showCourse ? 'block' : 'none';
            productScopeBox.style.display = showProduct ? 'block' : 'none';
        }

        appliesAll.addEventListener('change', updateScopeBoxes);
        scopeSelect.addEventListener('change', updateScopeBoxes);
        updateScopeBoxes();

        // ประเภทส่วนลด: หน่วย + ช่องยอดขั้นต่ำ
        const discountType = document.getElementById('discountType');
        const discountUnit = document.getElementById('discountUnit');
        const minSpendBox = document.getElementById('minSpendBox');

        function updateDiscountType() {
            discountUnit.textContent = discountType.value === 'percent' ? '%' : 'บาท';
            minSpendBox.style.display = discountType.value === 'spend_get' ? 'block' : 'none';
        }

        discountType.addEventListener('change', updateDiscountType);
        updateDiscountType();

        // โค้ดคูปองเป็นตัวพิมพ์ใหญ่
        const codeInput = document.querySelector('[name="code"]');
        codeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });
</script>

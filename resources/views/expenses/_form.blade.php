@php
    $expense = $expense ?? null;
@endphp

<div class="card mb-4">
    <div class="card-body p-4">

        <div class="fw-semibold mb-3">
            <i class="bi bi-receipt-cutoff me-1"></i>
            ข้อมูลรายจ่าย
        </div>

        <div class="row g-3">

            <div class="col-lg-3 col-md-6">
                <label class="form-label">หมวดหมู่ <span class="text-danger">*</span></label>
                <select name="category" id="categorySelect" class="form-select" required>
                    <option value="course" @selected(old('category', $expense?->category) == 'course')>คอร์สเรียน</option>
                    <option value="product_cost" @selected(old('category', $expense?->category) == 'product_cost')>ค่าซื้อสินค้า</option>
                    <option value="rent" @selected(old('category', $expense?->category) == 'rent')>ค่าเช่า</option>
                    <option value="staff" @selected(old('category', $expense?->category) == 'staff')>ค่าพนักงาน</option>
                    <option value="maintenance" @selected(old('category', $expense?->category) == 'maintenance')>ค่าซ่อมบำรุง</option>
                    <option value="other" @selected(old('category', $expense?->category) == 'other')>ค่าใช้จ่ายอื่นๆ</option>
                </select>
                <div class="form-text" id="staffHint" style="display:none;">
                    <i class="bi bi-info-circle"></i>
                    ค่าจ้างอาจารย์ดึงจากระบบเงินเดือนอัตโนมัติแล้ว ไม่ต้องบันทึกซ้ำ — ใช้หมวดนี้สำหรับพนักงานอื่นๆ
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <label class="form-label">วันที่ <span class="text-danger">*</span></label>
                <input type="date" name="expense_date"
                    value="{{ old('expense_date', optional($expense?->expense_date)->format('Y-m-d') ?? now()->toDateString()) }}"
                    class="form-control" required>
            </div>

            <div class="col-lg-3 col-md-6">
                <label class="form-label">จำนวนเงิน <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" step="0.01" min="0.01" name="amount"
                        value="{{ old('amount', $expense?->amount) }}" class="form-control" placeholder="0" required>
                    <span class="input-group-text">บาท</span>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <label class="form-label">หัวข้อรายการ <span class="text-danger">*</span></label>
                <input type="text" name="title" value="{{ old('title', $expense?->title) }}" class="form-control"
                    maxlength="150" placeholder="เช่น ค่าเช่าอาคารเดือนสิงหาคม" required>
            </div>

            <div class="col-12">
                <label class="form-label">หมายเหตุ</label>
                <textarea name="note" class="form-control" rows="3" placeholder="รายละเอียดเพิ่มเติม (ถ้ามี)">{{ old('note', $expense?->note) }}</textarea>
            </div>

        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('categorySelect');
        const staffHint = document.getElementById('staffHint');

        function updateStaffHint() {
            staffHint.style.display = categorySelect.value === 'staff' ? 'block' : 'none';
        }

        categorySelect.addEventListener('change', updateStaffHint);
        updateStaffHint();
    });
</script>

@php
    $tier = $membershipTier ?? null;
@endphp

<div class="card mb-4">
    <div class="card-body p-4">

        <div class="fw-semibold mb-3">
            <i class="bi bi-award me-1"></i>
            ข้อมูลระดับสมาชิก
        </div>

        <div class="row g-3">

            <div class="col-lg-4 col-md-6">
                <label class="form-label">ชื่อระดับ <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $tier?->name) }}" class="form-control"
                    maxlength="100" placeholder="เช่น Silver, Gold, Platinum" required>
            </div>

            <div class="col-lg-3 col-md-6">
                <label class="form-label">ลำดับ (น้อย = ต่ำสุด)</label>
                <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $tier?->sort_order ?? 0) }}"
                    class="form-control" required>
            </div>

            <div class="col-lg-3 col-md-6">
                <label class="form-label">ยอดใช้จ่ายสะสมขั้นต่ำ (12 เดือนล่าสุด)</label>
                <div class="input-group">
                    <input type="number" step="0.01" min="0" name="min_spend"
                        value="{{ old('min_spend', $tier?->min_spend ?? 0) }}" class="form-control" required>
                    <span class="input-group-text">บาท</span>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <label class="form-label">สีป้าย</label>
                <select name="badge_color" class="form-select">
                    @foreach (['secondary' => 'เทา', 'success' => 'เขียว', 'primary' => 'น้ำเงิน', 'warning' => 'เหลือง/ทอง', 'danger' => 'แดง', 'dark' => 'ดำ', 'info' => 'ฟ้า'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('badge_color', $tier?->badge_color ?? 'secondary') == $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">สิทธิประโยชน์ (1 บรรทัด = 1 สิทธิ์)</label>
                <textarea name="benefits" class="form-control" rows="4" placeholder="เช่น&#10;ส่วนลดพิเศษวันเกิด&#10;ของสมนาคุณประจำปี">{{ old('benefits', $tier?->benefits) }}</textarea>
            </div>

        </div>

        <div class="form-check form-switch mt-3">
            <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1"
                id="isActive" @checked(old('is_active', $tier?->is_active ?? true))>
            <label class="form-check-label" for="isActive">เปิดใช้งาน</label>
        </div>

    </div>
</div>

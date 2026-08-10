{{-- ใช้ร่วมกันระหว่าง create.blade.php และ edit.blade.php --}}
<style>
    .form-section {
        background: #fff;
        border: 1px solid var(--border, #e4e1dc);
        border-radius: 16px;
        padding: 1.4rem 1.6rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 2px rgba(28, 26, 23, .04);
        transition: box-shadow .2s;
    }

    .form-section:hover {
        box-shadow: 0 4px 14px rgba(28, 26, 23, .06);
    }

    .form-section-title {
        display: flex;
        align-items: center;
        gap: .7rem;
        font-weight: 700;
        font-size: 1.02rem;
        margin-bottom: 1.2rem;
        padding-bottom: .9rem;
        border-bottom: 1px solid var(--border, #e4e1dc);
        font-family: 'Prompt', sans-serif;
        color: var(--ink, #1c1a17);
    }

    .form-section-title .icon-badge {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--accent-soft, #e7ebf1);
        color: var(--accent-dark, #13233a);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .form-section-title .step-no {
        margin-left: auto;
        font-size: .7rem;
        color: var(--muted, #6b655e);
        font-weight: 500;
        letter-spacing: .5px;
    }

    .form-section-desc {
        font-size: .8rem;
        color: var(--muted, #6b655e);
        margin: -.6rem 0 1rem;
    }

    .form-label {
        font-size: .82rem;
        font-weight: 600;
        color: #40382f;
        margin-bottom: .35rem;
    }

    .form-control,
    .form-select {
        border-color: var(--border, #e4e1dc);
        font-size: .9rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--accent, #1f3350);
        box-shadow: 0 0 0 .2rem rgba(31, 51, 80, .1);
    }

    .avail-table th,
    .avail-table td {
        vertical-align: middle;
    }

    .avail-table tbody tr:nth-child(even) {
        background: #faf9f7;
    }

    .avail-table tbody tr {
        transition: opacity .2s;
    }

    .avail-day-off {
        opacity: .4;
    }

    .avail-day-off td .form-control {
        background: #f4f3f1;
    }

    .form-check-input:checked {
        background-color: var(--accent, #1f3350);
        border-color: var(--accent, #1f3350);
    }

    .form-switch .form-check-input {
        width: 2.2em;
    }

    .chip-check {
        border: 1px solid var(--border, #e4e1dc);
        border-radius: 10px;
        padding: .5rem .7rem;
        margin-bottom: .4rem;
        transition: .15s;
        background: #fff;
    }

    .chip-check:hover {
        border-color: #c9c4bb;
    }

    .chip-check:has(input:checked) {
        background: var(--accent-soft, #e7ebf1);
        border-color: var(--accent, #1f3350);
    }

    .invalid-feedback-static {
        font-size: .75rem;
        color: #b3392c;
    }

    /* ===== Photo dropzone ===== */
    .photo-dropzone {
        width: 96px;
        height: 96px;
        border-radius: 18px;
        background: var(--accent-soft, #e7ebf1);
        overflow: hidden;
        border: 2px dashed #c9c4bb;
        cursor: pointer;
        transition: .15s;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .photo-dropzone:hover {
        border-color: var(--accent, #1f3350);
        background: #eef1f5;
    }

    .photo-dropzone.dragover {
        border-color: var(--accent, #1f3350);
        background: #dde3ec;
    }

    .photo-dropzone .upload-hint {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(28, 26, 23, .55);
        color: #fff;
        font-size: .7rem;
        opacity: 0;
        transition: .15s;
    }

    .photo-dropzone:hover .upload-hint {
        opacity: 1;
    }

    /* ===== Instrument picker ===== */
    #instrumentPicker {
        background: #faf9f7;
        border: 1px solid var(--border, #e4e1dc) !important;
        border-radius: 12px;
        padding: .7rem !important;
    }

    #instrumentSearch {
        border: 1px solid var(--border, #e4e1dc);
    }

    #instrumentDropdown {
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--border, #e4e1dc);
    }

    #instrumentDropdown .list-group-item {
        border: 0;
        border-bottom: 1px solid #f0efec;
    }

    #instrumentDropdown .list-group-item:hover {
        background: var(--accent-soft, #e7ebf1);
    }
</style>

{{-- ===== 1. รูปโปรไฟล์ + ข้อมูลทั่วไป ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-person-vcard"></i></div>
        ข้อมูลทั่วไป
        <span class="step-no">ขั้นตอน 1</span>
    </div>
    <div class="row g-3">
        <div class="col-md-2 text-center">
            <div id="photoPreviewWrap" class="photo-dropzone mx-auto mb-2"
                onclick="document.getElementById('teacherPhotoInput').click()">
                @if (isset($teacher) && $teacher->photo_path)
                    <img id="photoPreviewImg" src="{{ asset('storage/' . $teacher->photo_path) }}"
                        style="width:100%;height:100%;object-fit:cover;">
                @else
                    <i id="photoPreviewIcon" class="bi bi-person fs-2 text-secondary"></i>
                    <img id="photoPreviewImg" style="width:100%;height:100%;object-fit:cover;display:none;">
                @endif
                <div class="upload-hint"><i class="bi bi-camera-fill"></i></div>
            </div>
            <input type="file" name="photo" id="teacherPhotoInput" class="d-none"
                accept="image/png,image/jpeg,image/webp" onchange="previewPhoto(this)">
            <div id="photoError" class="invalid-feedback-static d-none mt-1"></div>
            <small class="text-muted d-block mt-1" style="font-size:.7rem;">JPG/PNG/WEBP ไม่เกิน 2MB</small>
        </div>
        <div class="col-md-10">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">รหัสอาจารย์ *</label>
                    <input type="text" name="teacher_code" id="teacherCode" class="form-control"
                        value="{{ old('teacher_code', $teacher->teacher_code ?? '') }}" maxlength="20"
                        pattern="[A-Za-z0-9\-]+" title="ใช้ได้เฉพาะตัวอักษร A-Z, ตัวเลข, และ - เท่านั้น" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">ชื่อ-นามสกุล *</label>
                    <input type="text" name="full_name" class="form-control" maxlength="150"
                        value="{{ old('full_name', $teacher->full_name ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ชื่อเล่น</label>
                    <input type="text" name="nickname" class="form-control" maxlength="50"
                        value="{{ old('nickname', $teacher->nickname ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" class="form-control" maxlength="150"
                        value="{{ old('email', $teacher->email ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">เบอร์โทร</label>
                    <input type="tel" name="phone" id="phoneInput" class="form-control" inputmode="numeric"
                        autocomplete="tel" maxlength="12" placeholder="0812345671"
                        value="{{ old('phone', $teacher->phone ?? '') }}">
                    <div id="phoneError" class="invalid-feedback-static d-none mt-1">กรอกได้เฉพาะตัวเลข 9-10 หลัก</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Line ID</label>
                    <input type="text" name="line_id" id="lineIdInput" class="form-control" maxlength="50"
                        value="{{ old('line_id', $teacher->line_id ?? '') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">ที่อยู่</label>
                    <textarea name="address" class="form-control" rows="2" maxlength="500">{{ old('address', $teacher->address ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 2. ประเภทการทำงาน ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-briefcase"></i></div>
        ประเภทการทำงาน
        <span class="step-no">ขั้นตอน 2</span>
    </div>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">ประเภทการจ้างงาน *</label>
            <select name="employment_type" class="form-select" required>
                <option value="freelance" @selected(old('employment_type', $teacher->employment_type ?? 'freelance') == 'freelance')>Freelance</option>
                <option value="full_time" @selected(old('employment_type', $teacher->employment_type ?? '') == 'full_time')>Full-time</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">สาขา</label>
            <input type="text" name="branch" class="form-control" maxlength="100"
                placeholder="เช่น Cloud 11, Astra Academy" value="{{ old('branch', $teacher->branch ?? '') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">วันที่เริ่มงาน</label>
            <input type="date" name="start_date" class="form-control"
                value="{{ old('start_date', isset($teacher) && $teacher->start_date ? $teacher->start_date->format('Y-m-d') : '') }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <div class="form-check form-switch">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1"
                    {{ old('is_active', $teacher->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label">ใช้งานอยู่</label>
            </div>
        </div>
    </div>
</div>

{{-- ===== 3. ความเชี่ยวชาญ ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-stars"></i></div>
        ความเชี่ยวชาญ
        <span class="step-no">ขั้นตอน 3</span>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <label class="form-label d-block">ประเภทอาจารย์</label>
            @php $selectedTypes = old('teaching_type_ids', isset($teacher) ? $teacher->teachingTypes->pluck('id')->toArray() : []); @endphp
            @foreach ($teachingTypes as $type)
                <div class="chip-check">
                    <input class="form-check-input me-1" type="checkbox" name="teaching_type_ids[]"
                        value="{{ $type->id }}" id="tt{{ $type->id }}"
                        {{ in_array($type->id, $selectedTypes) ? 'checked' : '' }}>
                    <label class="form-check-label" for="tt{{ $type->id }}">{{ $type->name }}</label>
                </div>
            @endforeach
        </div>

        <div class="col-md-4">
            <label class="form-label d-block">เครื่องดนตรีที่สอนได้</label>
            @php
                $selectedInstrumentsInit = old(
                    'instrument_ids',
                    isset($teacher) ? $teacher->instruments->pluck('id')->toArray() : [],
                );
                $primaryInstrumentInit = old(
                    'primary_instrument_id',
                    isset($teacher) ? optional($teacher->instruments->firstWhere('pivot.is_primary', true))->id : null,
                );
            @endphp

            <div id="instrumentPicker" class="border rounded p-2">
                <div id="instrumentChips" class="d-flex flex-wrap gap-1 mb-2"></div>
                <div class="position-relative">
                    <input type="text" id="instrumentSearch" class="form-control form-control-sm"
                        placeholder="พิมพ์ค้นหาหรือเพิ่มเครื่องดนตรีใหม่..." autocomplete="off">
                    <div id="instrumentDropdown" class="list-group position-absolute w-100 shadow-sm d-none"
                        style="z-index:20; max-height:220px; overflow-y:auto; top:100%;"></div>
                </div>
                <div id="instrumentError" class="invalid-feedback-static d-none mt-1"></div>
            </div>
            <div id="instrumentHiddenInputs"></div>
            <small class="text-muted d-block mt-1" style="font-size:.72rem;">คลิกดาว ★ บน chip
                เพื่อกำหนดเป็นเครื่องดนตรีหลัก</small>
        </div>

        {{-- ข้อมูลตั้งต้นสำหรับ JS: รายการเครื่องดนตรีทั้งหมด + ค่าที่เลือกไว้แล้ว --}}
        <script id="instrumentsCatalog" type="application/json">
            {!! $instruments->map(fn($i) => ['id' => $i->id, 'name' => $i->name])->values()->toJson() !!}
        </script>
        <script id="instrumentsSelectedInit" type="application/json">
            {!! json_encode(['ids' => $selectedInstrumentsInit, 'primary' => $primaryInstrumentInit]) !!}
        </script>

        <div class="col-md-4">
            <label class="form-label d-block">ระดับที่สอนได้</label>
            @php $selectedLevels = old('level_ids', isset($teacher) ? $teacher->levels->pluck('id')->toArray() : []); @endphp
            @foreach ($levels as $level)
                <div class="chip-check">
                    <input class="form-check-input me-1" type="checkbox" name="level_ids[]"
                        value="{{ $level->id }}" id="lv{{ $level->id }}"
                        {{ in_array($level->id, $selectedLevels) ? 'checked' : '' }}>
                    <label class="form-check-label" for="lv{{ $level->id }}">{{ $level->name }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>

@unless (isset($teacher))
    {{-- ===== 4. Availability / เวลาที่พร้อมสอน (เฉพาะตอนเพิ่มอาจารย์ใหม่) ===== --}}
    <div class="form-section">
        <div class="form-section-title">
            <div class="icon-badge"><i class="bi bi-calendar-week"></i></div>
            Availability / เวลาที่พร้อมสอน
            <span class="step-no">ขั้นตอน 4</span>
        </div>
        <div class="form-section-desc">ติ๊กวันที่อาจารย์สะดวกสอน แล้วระบุช่วงเวลา — แก้ไขเพิ่มเติมภายหลังได้ที่แท็บ
            "Availability" ในหน้าโปรไฟล์อาจารย์</div>
        <div class="table-responsive">
            <table class="table table-sm avail-table mb-0">
                <thead>
                    <tr>
                        <th style="width:40px"></th>
                        <th>วัน</th>
                        <th>เวลาเริ่ม</th>
                        <th>เวลาสิ้นสุด</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\App\Models\TeacherAvailability::dayLabels() as $dow => $label)
                        <tr id="avail-row-{{ $dow }}">
                            <td>
                                <input class="form-check-input avail-toggle" type="checkbox"
                                    name="availabilities[{{ $dow }}][is_available]" value="1"
                                    data-dow="{{ $dow }}" {{ !in_array($dow, [0, 6]) ? 'checked' : '' }}>
                                <input type="hidden" name="availabilities[{{ $dow }}][day_of_week]"
                                    value="{{ $dow }}">
                            </td>
                            <td class="fw-semibold">{{ $label }}</td>
                            <td><input type="time" name="availabilities[{{ $dow }}][start_time]"
                                    class="form-control form-control-sm" value="09:00"></td>
                            <td><input type="time" name="availabilities[{{ $dow }}][end_time]"
                                    class="form-control form-control-sm" value="18:00"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== 5. ค่าจ้างและเงื่อนไขพิเศษ (เฉพาะตอนเพิ่มอาจารย์ใหม่) ===== --}}
    <div class="form-section">
        <div class="form-section-title">
            <div class="icon-badge"><i class="bi bi-cash-coin"></i></div>
            ค่าจ้างและเงื่อนไขพิเศษ
            <span class="step-no">ขั้นตอน 5</span>
        </div>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">รูปแบบเรทค่าจ้าง *</label>
                <select name="rate_type" class="form-select" required>
                    <option value="per_hour">ต่อชั่วโมง</option>
                    <option value="per_session">ต่อคาบ/ครั้ง</option>
                    <option value="monthly_fixed">เหมาต่อเดือน</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">จำนวนเงิน (บาท) *</label>
                <input type="number" step="0.01" min="0" max="1000000" name="rate_amount"
                    class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">ประเภทค่ารถ</label>
                <select name="transport_fee_type" class="form-select">
                    <option value="fixed_per_day">เหมาต่อวัน</option>
                    <option value="per_km">ต่อกิโลเมตร</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">ค่ารถ (บาท)</label>
                <input type="number" step="0.01" min="0" max="100000" name="transport_fee_amount"
                    class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">เงื่อนไขพิเศษ (ถ้ามี)</label>
                <textarea name="rate_note" class="form-control" rows="2" maxlength="1000"
                    placeholder="เช่น เรทพิเศษสำหรับคอร์สกลุ่ม, ปรับเรทหลัง 3 เดือนแรก, มีค่าคอมมิชชันเพิ่มเมื่อรับนักเรียนใหม่ ฯลฯ">{{ old('rate_note') }}</textarea>
            </div>
        </div>
    </div>
@endunless

{{-- ===== 6. หมายเหตุเพิ่มเติม ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-journal-text"></i></div>
        หมายเหตุเพิ่มเติม
        <span class="step-no">ขั้นตอนสุดท้าย</span>
    </div>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">ประวัติย่อ / Bio</label>
            <textarea name="bio" class="form-control" rows="3" maxlength="2000"
                placeholder="ประสบการณ์ วุฒิการศึกษา ผลงานเด่น...">{{ old('bio', $teacher->bio ?? '') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label">หมายเหตุ (สำหรับแอดมิน)</label>
            <textarea name="notes" class="form-control" rows="3" maxlength="2000"
                placeholder="ข้อควรระวัง เงื่อนไขพิเศษ หรือข้อมูลภายในอื่นๆ ที่ไม่แสดงต่อสาธารณะ">{{ old('notes', $teacher->notes ?? '') }}</textarea>
        </div>
    </div>
</div>

<script>
    // ===== พรีวิวรูป + เช็คชนิด/ขนาดไฟล์ + รองรับลาก-วาง =====
    function previewPhoto(input) {
        const errorBox = document.getElementById('photoError');
        errorBox.classList.add('d-none');

        if (!input.files || !input.files[0]) return;
        const file = input.files[0];

        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        const maxSizeBytes = 2 * 1024 * 1024;

        if (!allowedTypes.includes(file.type)) {
            errorBox.textContent = 'รองรับเฉพาะไฟล์ JPG, PNG, WEBP เท่านั้น';
            errorBox.classList.remove('d-none');
            input.value = '';
            return;
        }
        if (file.size > maxSizeBytes) {
            errorBox.textContent = 'ไฟล์ต้องมีขนาดไม่เกิน 2MB';
            errorBox.classList.remove('d-none');
            input.value = '';
            return;
        }

        const img = document.getElementById('photoPreviewImg');
        const icon = document.getElementById('photoPreviewIcon');
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            img.style.display = 'block';
            if (icon) icon.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    (function() {
        const dropzone = document.getElementById('photoPreviewWrap');
        const fileInput = document.getElementById('teacherPhotoInput');
        ['dragenter', 'dragover'].forEach(evt => {
            dropzone.addEventListener(evt, e => {
                e.preventDefault();
                dropzone.classList.add('dragover');
            });
        });
        ['dragleave', 'drop'].forEach(evt => {
            dropzone.addEventListener(evt, e => {
                e.preventDefault();
                dropzone.classList.remove('dragover');
            });
        });
        dropzone.addEventListener('drop', e => {
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                fileInput.files = e.dataTransfer.files;
                previewPhoto(fileInput);
            }
        });
    })();

    // ===== เบอร์โทร: พิมพ์ได้แต่ตัวเลข ใส่ - ให้อัตโนมัติ =====
    document.getElementById('phoneInput').addEventListener('input', function() {
        let digits = this.value.replace(/\D/g, '').slice(0, 10);
        let formatted = digits;
        if (digits.length > 6) {
            formatted = digits.slice(0, 3) + '-' + digits.slice(3, 6) + '-' + digits.slice(6);
        } else if (digits.length > 3) {
            formatted = digits.slice(0, 3) + '-' + digits.slice(3);
        }
        this.value = formatted;

        const errorBox = document.getElementById('phoneError');
        const valid = digits.length === 0 || digits.length === 9 || digits.length === 10;
        errorBox.classList.toggle('d-none', valid);
    });

    // ===== รหัสอาจารย์: ตัวพิมพ์ใหญ่ + a-z, 0-9, - เท่านั้น =====
    document.getElementById('teacherCode').addEventListener('input', function() {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');
    });

    // ===== Line ID: กรองเฉพาะอักขระที่ Line ID ใช้จริง =====
    document.getElementById('lineIdInput').addEventListener('input', function() {
        this.value = this.value.replace(/[^a-zA-Z0-9._\-]/g, '');
    });

    // ===== Availability toggle =====
    document.querySelectorAll('.avail-toggle').forEach(cb => {
        cb.addEventListener('change', function() {
            const row = document.getElementById('avail-row-' + this.dataset.dow);
            row.classList.toggle('avail-day-off', !this.checked);
        });
        cb.dispatchEvent(new Event('change'));
    });

    // ===== กันกดส่งฟอร์มซ้ำ =====
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = form.querySelector('button[type=submit]');
            if (btn) {
                btn.disabled = true;
                btn.dataset.originalText = btn.innerHTML;
                btn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...';
            }
        });
    });

    // ===== เครื่องดนตรีที่สอนได้: ค้นหา + เลือกหลายค่า + เพิ่มใหม่แบบ inline =====
    (function() {
        let catalog = JSON.parse(document.getElementById('instrumentsCatalog').textContent);
        const init = JSON.parse(document.getElementById('instrumentsSelectedInit').textContent);

        let selected = catalog
            .filter(i => init.ids.map(String).includes(String(i.id)))
            .map(i => ({
                ...i,
                isPrimary: String(i.id) === String(init.primary)
            }));

        const chipsBox = document.getElementById('instrumentChips');
        const hiddenBox = document.getElementById('instrumentHiddenInputs');
        const searchBox = document.getElementById('instrumentSearch');
        const dropdown = document.getElementById('instrumentDropdown');
        const errorBox = document.getElementById('instrumentError');

        function renderChips() {
            chipsBox.innerHTML = '';
            selected.forEach(ins => {
                const chip = document.createElement('span');
                chip.className = 'badge rounded-pill d-flex align-items-center gap-1 py-2 px-2';
                chip.style.background = ins.isPrimary ? 'var(--accent,#1f3350)' : '#ece9e4';
                chip.style.color = ins.isPrimary ? '#fff' : '#40382f';
                chip.innerHTML = `
                <button type="button" title="ตั้งเป็นเครื่องดนตรีหลัก" class="btn-star border-0 bg-transparent p-0" style="color:${ins.isPrimary ? '#e8b04b' : '#a39c8f'};line-height:1;">★</button>
                <span>${ins.name}</span>
                <button type="button" title="ลบ" class="btn-remove border-0 bg-transparent p-0" style="color:inherit;line-height:1;">✕</button>
            `;
                chip.querySelector('.btn-star').addEventListener('click', () => {
                    selected = selected.map(s => ({
                        ...s,
                        isPrimary: s.id === ins.id
                    }));
                    renderChips();
                });
                chip.querySelector('.btn-remove').addEventListener('click', () => {
                    selected = selected.filter(s => s.id !== ins.id);
                    renderChips();
                });
                chipsBox.appendChild(chip);
            });
            renderHiddenInputs();
        }

        function renderHiddenInputs() {
            hiddenBox.innerHTML = '';
            selected.forEach(ins => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'instrument_ids[]';
                input.value = ins.id;
                hiddenBox.appendChild(input);
            });
            const primary = selected.find(s => s.isPrimary);
            const primaryInput = document.createElement('input');
            primaryInput.type = 'hidden';
            primaryInput.name = 'primary_instrument_id';
            primaryInput.value = primary ? primary.id : '';
            hiddenBox.appendChild(primaryInput);
        }

        function renderDropdown(query) {
            const q = query.trim().toLowerCase();
            const selectedIds = selected.map(s => s.id);
            const matches = catalog.filter(i => !selectedIds.includes(i.id) && i.name.toLowerCase().includes(q))
                .slice(0, 8);
            const exactExists = catalog.some(i => i.name.toLowerCase() === q);

            dropdown.innerHTML = '';
            if (q === '' && matches.length === 0) {
                dropdown.classList.add('d-none');
                return;
            }

            matches.forEach(ins => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action py-1 px-2 small';
                item.textContent = ins.name;
                item.addEventListener('click', () => selectInstrument(ins));
                dropdown.appendChild(item);
            });

            if (q !== '' && !exactExists) {
                const addItem = document.createElement('button');
                addItem.type = 'button';
                addItem.className = 'list-group-item list-group-item-action py-1 px-2 small fw-semibold';
                addItem.style.color = 'var(--accent-dark,#13233a)';
                addItem.textContent = `+ เพิ่ม "${query.trim()}" เป็นเครื่องดนตรีใหม่`;
                addItem.addEventListener('click', () => addNewInstrument(query.trim()));
                dropdown.appendChild(addItem);
            }

            dropdown.classList.toggle('d-none', matches.length === 0 && (q === '' || exactExists));
        }

        function selectInstrument(ins) {
            if (!selected.some(s => s.id === ins.id)) {
                selected.push({
                    ...ins,
                    isPrimary: selected.length === 0
                });
                renderChips();
            }
            searchBox.value = '';
            dropdown.classList.add('d-none');
        }

        async function addNewInstrument(name) {
            errorBox.classList.add('d-none');
            try {
                const res = await fetch('{{ route('instruments.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        name
                    }),
                });
                const body = await res.json();

                if (!res.ok) {
                    errorBox.textContent = body.errors?.name?.[0] || 'เพิ่มเครื่องดนตรีไม่สำเร็จ';
                    errorBox.classList.remove('d-none');
                    return;
                }

                catalog.push(body);
                selectInstrument(body);
            } catch (e) {
                errorBox.textContent = 'เกิดข้อผิดพลาด กรุณาลองใหม่';
                errorBox.classList.remove('d-none');
            }
        }

        searchBox.addEventListener('input', () => renderDropdown(searchBox.value));
        searchBox.addEventListener('focus', () => renderDropdown(searchBox.value));
        document.addEventListener('click', e => {
            if (!document.getElementById('instrumentPicker').contains(e.target)) {
                dropdown.classList.add('d-none');
            }
        });

        renderChips();
    })();
</script>

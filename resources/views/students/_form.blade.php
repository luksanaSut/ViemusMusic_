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

    .form-check-input:checked {
        background-color: var(--accent, #1f3350);
        border-color: var(--accent, #1f3350);
    }

    .photo-dropzone {
        width: 100%;
        aspect-ratio: 1/1;
        border-radius: 14px;
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

    .photo-dropzone .upload-hint {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(28, 26, 23, .55);
        color: #fff;
        font-size: .75rem;
        opacity: 0;
        transition: .15s;
    }

    .photo-dropzone:hover .upload-hint {
        opacity: 1;
    }

    .alert-info-soft {
        background: #eef1f5;
        border: 1px solid #d9dfe8;
        border-radius: 10px;
        padding: .7rem 1rem;
        font-size: .82rem;
        color: var(--accent-dark, #13233a);
        display: flex;
        gap: .6rem;
        align-items: flex-start;
    }
</style>

{{-- ===== 1. ข้อมูลทั่วไป ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-person-vcard"></i></div>
        ข้อมูลทั่วไป
        <span class="step-no">ขั้นตอน 1</span>
    </div>
    <div class="row g-3">
        <div class="col-md-2 text-center">
            <div class="photo-dropzone mx-auto mb-2" onclick="document.getElementById('studentPhotoInput').click()">
                @if (isset($student) && $student->photo_path)
                    <img id="studentPhotoImg" src="{{ asset('storage/' . $student->photo_path) }}"
                        style="width:100%;height:100%;object-fit:cover;">
                @else
                    <i id="studentPhotoIcon" class="bi bi-person fs-2 text-secondary"></i>
                    <img id="studentPhotoImg" style="width:100%;height:100%;object-fit:cover;display:none;">
                @endif
                <div class="upload-hint"><i class="bi bi-camera-fill"></i></div>
            </div>
            <input type="file" name="photo" id="studentPhotoInput" class="d-none"
                accept="image/png,image/jpeg,image/webp" onchange="previewStudentPhoto(this)">
        </div>
        <div class="col-md-10">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">รหัสนักเรียน *</label>
                    <input type="text" name="student_code" id="studentCode" class="form-control" maxlength="20"
                        pattern="[A-Za-z0-9\-]+" value="{{ old('student_code', $student->student_code ?? '') }}"
                        required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">ชื่อ-นามสกุล *</label>
                    <input type="text" name="full_name" class="form-control" maxlength="150"
                        value="{{ old('full_name', $student->full_name ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ชื่อเล่น</label>
                    <input type="text" name="nickname" class="form-control" maxlength="50"
                        value="{{ old('nickname', $student->nickname ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">วันเกิด</label>
                    <input type="date" name="date_of_birth" class="form-control"
                        value="{{ old('date_of_birth', isset($student) && $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">เพศ</label>
                    <select name="gender" class="form-select">
                        <option value="">ไม่ระบุ</option>
                        <option value="male" @selected(old('gender', $student->gender ?? '') == 'male')>ชาย</option>
                        <option value="female" @selected(old('gender', $student->gender ?? '') == 'female')>หญิง</option>
                        <option value="other" @selected(old('gender', $student->gender ?? '') == 'other')>อื่นๆ</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">เบอร์โทร</label>
                    <input type="tel" name="phone" id="studentPhone" class="form-control" inputmode="numeric"
                        maxlength="12" value="{{ old('phone', $student->phone ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">สถานะ *</label>
                    <select name="status" class="form-select" required>
                        <option value="active" @selected(old('status', $student->status ?? 'active') == 'active')>กำลังเรียน</option>
                        <option value="paused" @selected(old('status', $student->status ?? '') == 'paused')>พักเรียน</option>
                        <option value="cancelled" @selected(old('status', $student->status ?? '') == 'cancelled')>ยกเลิกเรียน</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" class="form-control" maxlength="150"
                        value="{{ old('email', $student->email ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Line ID</label>
                    <input type="text" name="line_id" id="studentLineId" class="form-control" maxlength="50"
                        value="{{ old('line_id', $student->line_id ?? '') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">ที่อยู่</label>
                    <textarea name="address" class="form-control" rows="2" maxlength="500">{{ old('address', $student->address ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 2. หมายเหตุ ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-journal-text"></i></div>
        หมายเหตุ
        <span class="step-no">ขั้นตอนสุดท้าย</span>
    </div>
    <textarea name="notes" class="form-control" rows="3" maxlength="2000"
        placeholder="ข้อมูลเพิ่มเติม เช่น ข้อควรระวังด้านสุขภาพ ความต้องการพิเศษ ฯลฯ">{{ old('notes', $student->notes ?? '') }}</textarea>

    @unless (isset($student))
        <div class="alert-info-soft mt-3">
            <i class="bi bi-info-circle fs-6"></i>
            <div>เพิ่มข้อมูล <strong>ผู้ปกครอง</strong> ได้หลังบันทึกนักเรียนคนนี้แล้ว ในแท็บ "ผู้ปกครอง"
                ที่หน้าโปรไฟล์นักเรียน</div>
        </div>
    @endunless
</div>

<script>
    function previewStudentPhoto(input) {
        if (!input.files || !input.files[0]) return;
        const img = document.getElementById('studentPhotoImg');
        const icon = document.getElementById('studentPhotoIcon');
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            img.style.display = 'block';
            if (icon) icon.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
    document.getElementById('studentCode').addEventListener('input', function() {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');
    });
    document.getElementById('studentPhone').addEventListener('input', function() {
        let digits = this.value.replace(/\D/g, '').slice(0, 10);
        this.value = digits.length > 6 ? digits.slice(0, 3) + '-' + digits.slice(3, 6) + '-' + digits.slice(6) :
            digits.length > 3 ? digits.slice(0, 3) + '-' + digits.slice(3) : digits;
    });
    document.getElementById('studentLineId').addEventListener('input', function() {
        this.value = this.value.replace(/[^a-zA-Z0-9._\-]/g, '');
    });
</script>

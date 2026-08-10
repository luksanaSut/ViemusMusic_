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

    /* ===== การ์ดเลือกแบบมีเงื่อนไข ===== */
    .select-card {
        border: 1.5px solid var(--border, #e4e1dc);
        border-radius: 12px;
        padding: 1rem 1.1rem;
        cursor: pointer;
        transition: .15s;
        background: #fff;
        height: 100%;
    }

    .select-card:hover {
        border-color: #c9c4bb;
        box-shadow: 0 2px 8px rgba(28, 26, 23, .06);
    }

    .select-card.active {
        border-color: var(--accent, #1f3350);
        border-width: 2px;
        background: var(--accent-soft, #e7ebf1);
        box-shadow: 0 2px 10px rgba(31, 51, 80, .1);
    }

    .select-card .title {
        font-weight: 700;
        font-family: 'Prompt', sans-serif;
        color: var(--ink, #1c1a17);
    }

    .select-card.active .title {
        color: var(--accent-dark, #13233a);
    }

    .select-card .desc {
        font-size: .78rem;
        color: var(--muted, #6b655e);
        margin-top: .15rem;
    }

    .select-card .check-mark {
        display: none;
        position: absolute;
        top: .6rem;
        right: .6rem;
        color: var(--accent, #1f3350);
        font-size: 1rem;
    }

    .select-card {
        position: relative;
    }

    .select-card.active .check-mark {
        display: block;
    }

    /* กล่องเงื่อนไขที่โผล่ตามตัวเลือก */
    .conditional-box {
        border: 1px solid #e6d9c3;
        background: #fbf7f0;
        border-radius: 12px;
        padding: 1.1rem;
        margin-top: 1rem;
        animation: fadeIn .2s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-4px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .field-hint {
        font-size: .78rem;
        color: var(--muted, #6b655e);
        margin-top: .35rem;
    }

    .field-hint.text-accent {
        color: var(--accent-dark, #13233a);
        font-weight: 600;
    }

    /* ===== Photo dropzone ===== */
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
        font-size: .75rem;
        opacity: 0;
        transition: .15s;
        flex-direction: column;
        gap: .3rem;
    }

    .photo-dropzone:hover .upload-hint {
        opacity: 1;
    }
</style>

{{-- ===== 1. ข้อมูลคอร์ส + รูปภาพ ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-journal-bookmark"></i></div>
        ข้อมูลคอร์ส
        <span class="step-no">ขั้นตอน 1</span>
    </div>
    <div class="row g-3">
        <div class="col-md-2 text-center">
            <div id="coursePhotoPreviewWrap" class="photo-dropzone mx-auto mb-2"
                onclick="document.getElementById('courseImageInput').click()">
                @if (isset($course) && $course->image_path)
                    <img id="coursePhotoImg" src="{{ asset('storage/' . $course->image_path) }}"
                        style="width:100%;height:100%;object-fit:cover;">
                @else
                    <i id="coursePhotoIcon" class="bi bi-image fs-2 text-secondary"></i>
                    <img id="coursePhotoImg" style="width:100%;height:100%;object-fit:cover;display:none;">
                @endif
                <div class="upload-hint"><i class="bi bi-camera-fill fs-5"></i><span>อัปโหลดรูป</span></div>
            </div>
            <input type="file" name="image" id="courseImageInput" class="d-none"
                accept="image/png,image/jpeg,image/webp" onchange="previewCoursePhoto(this)">
            <small class="text-muted d-block mt-1" style="font-size:.7rem;">JPG/PNG/WEBP ไม่เกิน 2MB</small>
        </div>
        <div class="col-md-10">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">รหัสคอร์ส *</label>
                    <input type="text" name="course_code" class="form-control" maxlength="20"
                        pattern="[A-Za-z0-9\-]+" title="ใช้ได้เฉพาะตัวอักษร A-Z, ตัวเลข, และ -"
                        value="{{ old('course_code', $course->course_code ?? '') }}" required>
                </div>
                <div class="col-md-9">
                    <label class="form-label">ชื่อคอร์ส *</label>
                    <input type="text" name="name" class="form-control" maxlength="150"
                        value="{{ old('name', $course->name ?? '') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">รายละเอียดคอร์ส</label>
                    <textarea name="description" class="form-control" rows="2" maxlength="2000">{{ old('description', $course->description ?? '') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">ประเภทเครื่องดนตรี</label>
                    @php
                        $selectedInstrument = old('instrument_id', $course->instrument_id ?? null);
                        $selectedInstrumentObj = $instruments->firstWhere('id', (int) $selectedInstrument);
                    @endphp

                    <div id="courseInstrumentPicker" class="border rounded p-2" style="background:#faf9f7;">
                        <div id="courseInstrumentChip" class="mb-2"></div>
                        <div class="position-relative">
                            <input type="text" id="courseInstrumentSearch" class="form-control form-control-sm"
                                placeholder="พิมพ์ค้นหาหรือเพิ่มเครื่องดนตรีใหม่..." autocomplete="off">
                            <div id="courseInstrumentDropdown"
                                class="list-group position-absolute w-100 shadow-sm d-none"
                                style="z-index:20; max-height:220px; overflow-y:auto; top:100%;"></div>
                        </div>
                        <div id="courseInstrumentError" class="invalid-feedback-static d-none mt-1"></div>
                    </div>
                    <input type="hidden" name="instrument_id" id="courseInstrumentHidden"
                        value="{{ $selectedInstrument }}">
                </div>

                <script id="courseInstrumentsCatalog" type="application/json">
                    {!! $instruments->map(fn($i) => ['id' => $i->id, 'name' => $i->name])->values()->toJson() !!}
                </script>
                <script id="courseInstrumentSelectedInit" type="application/json">
                    {!! json_encode($selectedInstrumentObj ? ['id' => $selectedInstrumentObj->id, 'name' => $selectedInstrumentObj->name] : null) !!}
                </script>

                <div class="col-md-6">
                    <label class="form-label">ระดับคอร์ส</label>
                    <select name="level_id" class="form-select">
                        <option value="">ไม่ระบุ</option>
                        @foreach ($levels as $lv)
                            <option value="{{ $lv->id }}" @selected(old('level_id', $course->level_id ?? '') == $lv->id)>{{ $lv->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 2. โครงสร้างคอร์ส & ประเภทการเรียน ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-diagram-3"></i></div>
        โครงสร้างคอร์ส & ประเภทการเรียน
        <span class="step-no">ขั้นตอน 2</span>
    </div>

    @php
        $structureType = old('structure_type', $course->structure_type ?? 'regular');
        $classType = old('class_type', $course->class_type ?? 'private');
        $activityType = old('activity_type', $course->activity_type ?? '');
        $deliveryMode = old('delivery_mode', $course->delivery_mode ?? 'onsite');
    @endphp

    <label class="form-label d-block">ประเภทโครงสร้างคอร์ส</label>
    <div class="row g-2 mb-3">
        <div class="col-md-6">
            <label class="select-card d-block m-0 structure-card {{ $structureType == 'regular' ? 'active' : '' }}"
                data-value="regular">
                <input type="radio" name="structure_type" value="regular" class="d-none"
                    {{ $structureType == 'regular' ? 'checked' : '' }}>
                <i class="bi bi-check-circle-fill check-mark"></i>
                <div class="title">แบบปกติ</div>
                <div class="desc">เรียนรายสัปดาห์ / แพ็กเกจปกติ</div>
            </label>
        </div>
        <div class="col-md-6">
            <label class="select-card d-block m-0 structure-card {{ $structureType == 'special' ? 'active' : '' }}"
                data-value="special">
                <input type="radio" name="structure_type" value="special" class="d-none"
                    {{ $structureType == 'special' ? 'checked' : '' }}>
                <i class="bi bi-check-circle-fill check-mark"></i>
                <div class="title">แบบพิเศษ</div>
                <div class="desc">กิจกรรมเฉพาะ เช่น 5 วัน วันละ 2 ชม.</div>
            </label>
        </div>
    </div>

    <label class="form-label d-block">ประเภทการเรียน *</label>
    <div class="row g-2">
        <div class="col-md-4">
            <label class="select-card d-block m-0 class-card {{ $classType == 'private' ? 'active' : '' }}"
                data-value="private">
                <input type="radio" name="class_type" value="private" class="d-none"
                    {{ $classType == 'private' ? 'checked' : '' }} required>
                <i class="bi bi-check-circle-fill check-mark"></i>
                <div class="title">Private</div>
                <div class="desc">รายบุคคล · ไม่มีเต็ม</div>
            </label>
        </div>
        <div class="col-md-4">
            <label class="select-card d-block m-0 class-card {{ $classType == 'group' ? 'active' : '' }}"
                data-value="group">
                <input type="radio" name="class_type" value="group" class="d-none"
                    {{ $classType == 'group' ? 'checked' : '' }}>
                <i class="bi bi-check-circle-fill check-mark"></i>
                <div class="title">Group</div>
                <div class="desc">กลุ่ม · มีจำนวนเต็ม</div>
            </label>
        </div>
        <div class="col-md-4">
            <label class="select-card d-block m-0 class-card {{ $classType == 'special_activity' ? 'active' : '' }}"
                data-value="special_activity">
                <input type="radio" name="class_type" value="special_activity" class="d-none"
                    {{ $classType == 'special_activity' ? 'checked' : '' }}>
                <i class="bi bi-check-circle-fill check-mark"></i>
                <div class="title">Special Activity</div>
                <div class="desc">กิจกรรม · จำกัดผู้เข้าร่วม</div>
            </label>
        </div>
    </div>

    {{-- รูปแบบการเรียน: ซ่อนอัตโนมัติเมื่อเลือกประเภทโครงสร้างคอร์สแบบพิเศษ (จัดการด้วย JS ด้านล่าง) --}}
    <div id="deliveryModeBox">
        <label class="form-label d-block mt-3">รูปแบบการเรียน *</label>
        <div class="row g-2">
            <div class="col-md-4">
                <label class="select-card d-block m-0 delivery-card {{ $deliveryMode == 'onsite' ? 'active' : '' }}"
                    data-value="onsite">
                    <input type="radio" name="delivery_mode" value="onsite" class="d-none"
                        {{ $deliveryMode == 'onsite' ? 'checked' : '' }}>
                    <i class="bi bi-check-circle-fill check-mark"></i>
                    <div class="title"><i class="bi bi-building"></i> ที่โรงเรียน</div>
                    <div class="desc">เรียนสด ณ สถานที่</div>
                </label>
            </div>
            <div class="col-md-4">
                <label class="select-card d-block m-0 delivery-card {{ $deliveryMode == 'online' ? 'active' : '' }}"
                    data-value="online">
                    <input type="radio" name="delivery_mode" value="online" class="d-none"
                        {{ $deliveryMode == 'online' ? 'checked' : '' }}>
                    <i class="bi bi-check-circle-fill check-mark"></i>
                    <div class="title"><i class="bi bi-camera-video"></i> ออนไลน์</div>
                    <div class="desc">เรียนผ่านวิดีโอคอล</div>
                </label>
            </div>
            <div class="col-md-4">
                <label class="select-card d-block m-0 delivery-card {{ $deliveryMode == 'hybrid' ? 'active' : '' }}"
                    data-value="hybrid">
                    <input type="radio" name="delivery_mode" value="hybrid" class="d-none"
                        {{ $deliveryMode == 'hybrid' ? 'checked' : '' }}>
                    <i class="bi bi-check-circle-fill check-mark"></i>
                    <div class="title"><i class="bi bi-arrow-left-right"></i> ไฮบริด</div>
                    <div class="desc">ผสมทั้งสองรูปแบบ</div>
                </label>
            </div>
        </div>
    </div>

    {{-- กล่องประเภทกิจกรรมย่อย: แสดงเมื่อเลือก Special Activity --}}
    <div id="activityTypeBox" class="conditional-box" style="display:none;">
        <label class="form-label d-block"><i class="bi bi-stars me-1"></i>ประเภทกิจกรรม (Activity Type) *</label>
        <div class="row g-2">
            <div class="col-md-4">
                <label class="select-card d-block m-0 activity-card {{ $activityType == 'camp' ? 'active' : '' }}"
                    data-value="camp">
                    <input type="radio" name="activity_type" value="camp" class="d-none"
                        {{ $activityType == 'camp' ? 'checked' : '' }}>
                    <i class="bi bi-check-circle-fill check-mark"></i>
                    <div class="title text-center">Camp</div>
                </label>
            </div>
            <div class="col-md-4">
                <label class="select-card d-block m-0 activity-card {{ $activityType == 'workshop' ? 'active' : '' }}"
                    data-value="workshop">
                    <input type="radio" name="activity_type" value="workshop" class="d-none"
                        {{ $activityType == 'workshop' ? 'checked' : '' }}>
                    <i class="bi bi-check-circle-fill check-mark"></i>
                    <div class="title text-center">Workshop</div>
                </label>
            </div>
            <div class="col-md-4">
                <label
                    class="select-card d-block m-0 activity-card {{ $activityType == 'master_class' ? 'active' : '' }}"
                    data-value="master_class">
                    <input type="radio" name="activity_type" value="master_class" class="d-none"
                        {{ $activityType == 'master_class' ? 'checked' : '' }}>
                    <i class="bi bi-check-circle-fill check-mark"></i>
                    <div class="title text-center">Master Class</div>
                </label>
            </div>
        </div>
    </div>

    {{-- กล่องแบบพิเศษ: จำนวนวัน / ชม.ต่อวัน / วันที่เริ่ม-สิ้นสุด --}}
    <div id="specialFieldsBox" class="conditional-box" style="display:none;">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">จำนวนวัน *</label>
                <input type="number" name="days_count" class="form-control" min="1" max="60"
                    value="{{ old('days_count', $course->days_count ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">ชั่วโมง/วัน *</label>
                <input type="number" step="0.5" name="hours_per_day" class="form-control" min="0.5"
                    max="12" value="{{ old('hours_per_day', $course->hours_per_day ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">วันที่เริ่ม *</label>
                <input type="date" name="course_start_date" class="form-control"
                    value="{{ old('course_start_date', isset($course) && $course->course_start_date ? $course->course_start_date->format('Y-m-d') : '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">วันที่สิ้นสุด *</label>
                <input type="date" name="course_end_date" class="form-control"
                    value="{{ old('course_end_date', isset($course) && $course->course_end_date ? $course->course_end_date->format('Y-m-d') : '') }}">
            </div>
        </div>
    </div>

    {{-- กล่องแบบปกติ: จำนวนครั้งเรียน / ระยะเวลาคอร์ส --}}
    <div id="regularFieldsBox" class="conditional-box" style="display:none;">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">จำนวนครั้งเรียน *</label>
                <input type="number" name="total_sessions" class="form-control" min="1" max="500"
                    value="{{ old('total_sessions', $course->total_sessions ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">ระยะเวลาคอร์ส (เดือน) *</label>
                <input type="number" name="duration_months" id="durationMonths" class="form-control"
                    min="1" max="36"
                    value="{{ old('duration_months', $course->duration_months ?? '') }}">
                <div id="extensionHint" class="field-hint"></div>
            </div>
        </div>
    </div>
</div>

{{-- ===== 3. ราคา / จำนวนผู้เรียน / สถานะ ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-cash-coin"></i></div>
        ราคา, จำนวนผู้เรียน & สถานะ
        <span class="step-no">ขั้นตอน 3</span>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">ราคา (บาท) *</label>
            <input type="number" step="0.01" name="price" class="form-control" min="0" max="1000000"
                placeholder="เช่น 14400" value="{{ old('price', $course->price ?? '') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">จำนวนผู้เรียนสูงสุด <span id="maxStudentsRequiredMark"
                    class="text-danger">*</span></label>
            <input type="number" name="max_students" id="maxStudentsInput" class="form-control" min="1"
                max="100" value="{{ old('max_students', $course->max_students ?? '') }}">
            <div id="capacityHint" class="field-hint text-accent"></div>
        </div>
        <div class="col-md-4">
            <label class="form-label">สถานะคอร์ส</label>
            <select name="is_active" class="form-select">
                <option value="1" @selected(old('is_active', isset($course) ? (int) $course->is_active : 1) == 1)>เปิดใช้งาน (Active)</option>
                <option value="0" @selected(old('is_active', isset($course) ? (int) $course->is_active : 1) == 0)>ปิดใช้งาน (Inactive)</option>
            </select>
        </div>
    </div>
</div>

{{-- ===== 4. สิทธิ์และเงื่อนไข ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-shield-check"></i></div>
        สิทธิ์และเงื่อนไข
        <span class="step-no">ขั้นตอน 4</span>
    </div>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">โควตาลาฉุกเฉิน (ครั้ง/คอร์ส) *</label>
            <input type="number" name="emergency_leave_quota" class="form-control" min="0" max="10"
                value="{{ old('emergency_leave_quota', $course->emergency_leave_quota ?? 1) }}" required>
            <small class="text-muted" style="font-size:.72rem;">ค่าเริ่มต้นตามนโยบาย = 1 ครั้ง/คอร์ส</small>
        </div>
        <div class="col-md-3 d-flex align-items-center">
            <div class="form-check form-switch mt-4">
                <input type="hidden" name="allow_makeup_class" value="0">
                <input class="form-check-input" type="checkbox" role="switch" name="allow_makeup_class"
                    value="1"
                    {{ old('allow_makeup_class', $course->allow_makeup_class ?? true) ? 'checked' : '' }}>
                <label class="form-check-label">สิทธิ์เรียนชดเชย</label>
            </div>
        </div>
        <div class="col-md-3 d-flex align-items-center">
            <div class="form-check form-switch mt-4">
                <input type="hidden" name="is_adult_flexi" value="0">
                <input class="form-check-input" type="checkbox" role="switch" name="is_adult_flexi" value="1"
                    {{ old('is_adult_flexi', $course->is_adult_flexi ?? false) ? 'checked' : '' }}>
                <label class="form-check-label">Adult Flexi Course</label>
            </div>
        </div>
    </div>
</div>

{{-- ===== 5. อาจารย์ผู้สอนได้ ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-person-badge"></i></div>
        อาจารย์ผู้สอนได้
        <span class="step-no">ขั้นตอนสุดท้าย</span>
    </div>
    @php $selectedTeachers = old('teacher_ids', isset($course) ? $course->teachers->pluck('id')->toArray() : []); @endphp

    <div class="row g-2 mb-3">
        <div class="col-md-7">
            <div class="position-relative">
                <i class="bi bi-search position-absolute"
                    style="left:.7rem; top:50%; transform:translateY(-50%); color:var(--muted,#6b655e); font-size:.85rem;"></i>
                <input type="text" id="teacherSearchInput" class="form-control form-control-sm"
                    style="padding-left:2rem;" placeholder="ค้นหาชื่ออาจารย์...">
            </div>
        </div>
        <div class="col-md-5">
            <select id="teacherInstrumentFilter" class="form-select form-select-sm">
                <option value="">ทุกเครื่องดนตรี</option>
                @foreach ($instruments as $ins)
                    <option value="{{ $ins->id }}">{{ $ins->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="teacherListBox" class="row g-2" style="max-height:260px; overflow-y:auto;">
        @forelse($teachers as $t)
            <div class="col-md-4 teacher-item" data-name="{{ mb_strtolower($t->full_name . ' ' . $t->nickname) }}"
                data-instruments="{{ $t->instruments->pluck('id')->implode(',') }}">
                <div class="chip-check">
                    <input class="form-check-input me-1" type="checkbox" name="teacher_ids[]"
                        value="{{ $t->id }}" id="tch{{ $t->id }}"
                        {{ in_array($t->id, $selectedTeachers) ? 'checked' : '' }}>
                    <label class="form-check-label"
                        for="tch{{ $t->id }}">{{ $t->nickname ?: $t->full_name }}</label>
                </div>
            </div>
        @empty
            <p class="text-muted">ยังไม่มีอาจารย์ในระบบ</p>
        @endforelse
    </div>
    <div id="teacherNoResult" class="text-muted small text-center py-3 d-none">ไม่พบอาจารย์ที่ตรงกับการค้นหา</div>
</div>

<script>
    (function() {
        // ===== พรีวิวรูปคอร์ส + ลาก-วาง =====
        window.previewCoursePhoto = function(input) {
            if (!input.files || !input.files[0]) return;
            const img = document.getElementById('coursePhotoImg');
            const icon = document.getElementById('coursePhotoIcon');
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                img.style.display = 'block';
                if (icon) icon.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        };

        const dropzone = document.getElementById('coursePhotoPreviewWrap');
        const fileInput = document.getElementById('courseImageInput');
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
                previewCoursePhoto(fileInput);
            }
        });

        // ===== การ์ดเลือก: structure_type / class_type / activity_type / delivery_mode =====
        function bindCardGroup(selector, onChange) {
            document.querySelectorAll(selector).forEach(card => {
                card.addEventListener('click', () => {
                    document.querySelectorAll(selector).forEach(c => c.classList.remove('active'));
                    card.classList.add('active');
                    card.querySelector('input[type=radio]').checked = true;
                    onChange(card.dataset.value);
                });
            });
        }

        function updateStructureFields(value) {
            document.getElementById('specialFieldsBox').style.display = value === 'special' ? 'block' : 'none';
            document.getElementById('regularFieldsBox').style.display = value === 'regular' ? 'block' : 'none';
            // แบบพิเศษ = กิจกรรมเฉพาะ ไม่ต้องกำหนดรูปแบบการเรียน (ที่โรงเรียน/ออนไลน์/ไฮบริด)
            document.getElementById('deliveryModeBox').style.display = value === 'special' ? 'none' : 'block';
        }

        function updateClassFields(value) {
            document.getElementById('activityTypeBox').style.display = value === 'special_activity' ? 'block' :
                'none';

            const maxInput = document.getElementById('maxStudentsInput');
            const requiredMark = document.getElementById('maxStudentsRequiredMark');
            const hint = document.getElementById('capacityHint');
            const hints = {
                private: 'Private = ไม่จำกัด/ไม่มีเต็ม',
                group: 'Group กำหนดได้มากกว่า 1',
                special_activity: 'เต็มตามจำนวนที่กำหนดของกิจกรรม',
            };
            hint.textContent = hints[value] || '';

            if (value === 'private') {
                maxInput.value = '';
                maxInput.disabled = true;
                maxInput.placeholder = 'ไม่จำกัด';
                requiredMark.style.display = 'none';
            } else {
                maxInput.disabled = false;
                maxInput.placeholder = '';
                requiredMark.style.display = 'inline';
                if (value === 'group' && (!maxInput.value || parseInt(maxInput.value) < 2)) {
                    maxInput.value = 2;
                }
            }
        }

        bindCardGroup('.structure-card', updateStructureFields);
        bindCardGroup('.class-card', updateClassFields);
        bindCardGroup('.activity-card', () => {});
        bindCardGroup('.delivery-card', () => {});

        const initStructure = document.querySelector('.structure-card.active');
        const initClass = document.querySelector('.class-card.active');
        updateStructureFields(initStructure ? initStructure.dataset.value : 'regular');
        updateClassFields(initClass ? initClass.dataset.value : 'private');

        // ===== hint สิทธิ์ขยายเวลาอัตโนมัติ ตาม duration_months =====
        const durationInput = document.getElementById('durationMonths');
        const extHint = document.getElementById('extensionHint');

        function updateExtensionHint() {
            const months = parseInt(durationInput.value, 10);
            const map = {
                3: 1,
                6: 2,
                12: 0
            };
            extHint.textContent = map.hasOwnProperty(months) ?
                (map[months] > 0 ? `สิทธิ์ขยายเวลาอัตโนมัติ: ขยายได้ ${map[months]} เดือน` :
                    'สิทธิ์ขยายเวลาอัตโนมัติ: ไม่อนุญาตให้ขยาย') :
                '';
            extHint.classList.toggle('text-accent', map.hasOwnProperty(months));
        }
        durationInput.addEventListener('input', updateExtensionHint);
        updateExtensionHint();
    })();

    // ===== ประเภทเครื่องดนตรี: ค้นหา + เลือกทีละ 1 + เพิ่มใหม่แบบ inline =====
    (function() {
        let catalog = JSON.parse(document.getElementById('courseInstrumentsCatalog').textContent);
        let selected = JSON.parse(document.getElementById('courseInstrumentSelectedInit')
        .textContent); // {id, name} หรือ null

        const chipBox = document.getElementById('courseInstrumentChip');
        const hiddenInp = document.getElementById('courseInstrumentHidden');
        const searchBox = document.getElementById('courseInstrumentSearch');
        const dropdown = document.getElementById('courseInstrumentDropdown');
        const errorBox = document.getElementById('courseInstrumentError');

        function renderChip() {
            chipBox.innerHTML = '';
            hiddenInp.value = selected ? selected.id : '';

            if (!selected) return;

            const chip = document.createElement('span');
            chip.className = 'badge rounded-pill d-inline-flex align-items-center gap-1 py-2 px-2';
            chip.style.background = 'var(--accent,#1f3350)';
            chip.style.color = '#fff';
            chip.innerHTML =
                `<span>${selected.name}</span><button type="button" class="btn-remove border-0 bg-transparent p-0" style="color:inherit;line-height:1;">✕</button>`;
            chip.querySelector('.btn-remove').addEventListener('click', () => {
                selected = null;
                renderChip();
            });
            chipBox.appendChild(chip);
        }

        function renderDropdown(query) {
            const q = query.trim().toLowerCase();
            const matches = catalog.filter(i => i.name.toLowerCase().includes(q)).slice(0, 8);
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
            selected = ins;
            renderChip();
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
            if (!document.getElementById('courseInstrumentPicker').contains(e.target)) {
                dropdown.classList.add('d-none');
            }
        });

        renderChip();
    })();

    // ===== ค้นหา + กรองอาจารย์ผู้สอนได้ =====
    (function() {
        const searchInput = document.getElementById('teacherSearchInput');
        const instrumentFilter = document.getElementById('teacherInstrumentFilter');
        const items = document.querySelectorAll('.teacher-item');
        const noResultBox = document.getElementById('teacherNoResult');

        function applyFilter() {
            const keyword = searchInput.value.trim().toLowerCase();
            const instrumentId = instrumentFilter.value;
            let visibleCount = 0;

            items.forEach(item => {
                const matchesName = keyword === '' || item.dataset.name.includes(keyword);
                const teacherInstruments = item.dataset.instruments.split(',').filter(Boolean);
                const matchesInstrument = instrumentId === '' || teacherInstruments.includes(instrumentId);

                const visible = matchesName && matchesInstrument;
                item.style.display = visible ? '' : 'none';
                if (visible) visibleCount++;
            });

            noResultBox.classList.toggle('d-none', visibleCount > 0);
        }

        if (searchInput) {
            searchInput.addEventListener('input', applyFilter);
            instrumentFilter.addEventListener('change', applyFilter);
        }
    })();
</script>

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

    .icon-badge {
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

    .step-no {
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

    .selection-summary {
        display: none;
        align-items: center;
        gap: .8rem;
        background: var(--accent-soft, #e7ebf1);
        border-radius: 12px;
        padding: .9rem 1.1rem;
        margin-top: 1rem;
    }

    .selection-summary.show {
        display: flex;
    }

    .selection-summary .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        color: var(--accent-dark, #13233a);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    .selection-summary .name {
        font-weight: 700;
        font-family: 'Prompt', sans-serif;
        font-size: .92rem;
    }

    .selection-summary .meta {
        font-size: .78rem;
        color: var(--muted, #6b655e);
    }

    .info-hint {
        font-size: .78rem;
        color: var(--muted, #6b655e);
        margin-top: .4rem;
        display: flex;
        align-items: flex-start;
        gap: .4rem;
    }

    .status-box {
        border-radius: 12px;
        padding: .85rem 1rem;
        font-size: .85rem;
        display: flex;
        align-items: flex-start;
        gap: .6rem;
        margin-top: 1rem;
    }

    .status-box.ok {
        background: var(--success-soft, #e9f9ef);
        color: var(--success, #2f6f4e);
    }

    .status-box.warn {
        background: var(--amber-soft, #fdf1e2);
        color: var(--amber, #8a5a2b);
    }

    .status-box.danger {
        background: #fbeae7;
        color: #b3392c;
    }

    .status-box i {
        font-size: 1rem;
        margin-top: .1rem;
    }

    .pill-options {
        display: flex;
        gap: .6rem;
        flex-wrap: wrap;
    }

    .pill-option {
        border: 1.5px solid var(--border, #e4e1dc);
        border-radius: 10px;
        padding: .55rem 1rem;
        cursor: pointer;
        font-size: .85rem;
        font-weight: 600;
        transition: .15s;
        user-select: none;
        display: flex;
        align-items: center;
        gap: .4rem;
    }

    .pill-option:hover {
        border-color: #c9c4bb;
    }

    .pill-option.active {
        border-color: var(--accent, #1f3350);
        background: var(--accent-soft, #e7ebf1);
        color: var(--accent-dark, #13233a);
    }
</style>

<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-mortarboard"></i></div> เลือกนักเรียน (จากการลงทะเบียนเรียนที่ Active)
        <span class="step-no">ขั้นตอน 1</span>
    </div>

    <select name="enrollment_id" id="enrollmentSelect" class="form-select" required>
        <option value="">เลือกนักเรียน + คอร์สที่ลงทะเบียนไว้...</option>
        @foreach ($enrollments as $e)
            @continue(!$e->student)
            <option value="{{ $e->id }}" data-teacher-id="{{ $e->teacher_id }}"
                data-teacher-name="{{ $e->teacher->nickname ?? ($e->teacher->full_name ?? '') }}"
                data-total-sessions="{{ $e->course->total_sessions ?? '' }}"
                data-student-name="{{ $e->student->full_name }}{{ $e->student->trashed() ? ' (ลบแล้ว)' : '' }}"
                data-course-name="{{ $e->course->name ?? 'ไม่พบคอร์ส' }}"
                data-structure-type="{{ $e->course->structure_type ?? 'regular' }}"
                data-course-start="{{ optional($e->course->course_start_date)->format('Y-m-d') }}"
                data-course-end="{{ optional($e->course->course_end_date)->format('Y-m-d') }}"
                {{ old('enrollment_id', $classSchedule->enrollment_id ?? ($preselectedEnrollment->id ?? '')) == $e->id ? 'selected' : '' }}>
                {{ $e->student->full_name }}{{ $e->student->trashed() ? ' (ลบแล้ว)' : '' }} —
                {{ $e->course->name ?? 'ไม่พบคอร์ส' }} ({{ $e->course->course_code ?? '-' }})
            </option>
        @endforeach
    </select>

    <div class="info-hint"><i class="bi bi-info-circle"></i> แสดงเฉพาะนักเรียนที่มีสถานะ "กำลังเรียน"
        (ลงทะเบียน/ชำระเงินสำเร็จแล้วเท่านั้น)</div>

    @if ($enrollments->isEmpty())
        <div class="status-box warn"><i class="bi bi-exclamation-circle"></i>
            ยังไม่มีนักเรียนที่ลงทะเบียนเรียนอยู่ในระบบ — ต้องให้นักเรียนสมัครเรียนผ่านเมนู "ระบบขายคอร์สเรียน" ก่อน
        </div>
    @endif

    <div id="selectionSummary" class="selection-summary">
        <div class="avatar" id="summaryAvatar">?</div>
        <div>
            <div class="name" id="summaryName">-</div>
            <div class="meta" id="summaryCourse">-</div>
        </div>
    </div>

    <div id="sessionInfoBox" class="status-box ok d-none"></div>
    <div id="courseDateRangeBox" class="status-box ok d-none"></div>
</div>

<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-person-badge"></i></div> อาจารย์ & ห้องเรียน <span
            class="step-no">ขั้นตอน 2</span>
    </div>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">อาจารย์ผู้สอน</label>
            <select name="teacher_id" id="teacherSelect" class="form-select">
                <option value="">ให้ทางโรงเรียนจัดให้</option>
                @foreach ($teachers as $t)
                    <option value="{{ $t->id }}"
                        {{ old('teacher_id', $classSchedule->teacher_id ?? '') == $t->id ? 'selected' : '' }}>
                        {{ $t->nickname ?: $t->full_name }}</option>
                @endforeach
            </select>
            <div class="info-hint" id="teacherAutoHint"></div>
        </div>
        <div class="col-md-6">
            <label class="form-label">ห้องเรียน</label>
            <select name="room_id" class="form-select">
                <option value="">ไม่ระบุห้อง (เรียนออนไลน์)</option>
                @foreach ($rooms as $r)
                    <option value="{{ $r->id }}"
                        {{ old('room_id', $classSchedule->room_id ?? '') == $r->id ? 'selected' : '' }}>
                        {{ $r->name }} (ความจุ {{ $r->capacity }} คน)</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-calendar-event"></i></div> วันเวลาเรียน <span class="step-no">ขั้นตอน
            3</span>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">วันที่ *</label>
            <input type="date" name="schedule_date" id="scheduleDate" class="form-control"
                value="{{ old('schedule_date', isset($classSchedule) ? $classSchedule->schedule_date->format('Y-m-d') : now()->toDateString()) }}"
                required>
            <div class="info-hint" id="dateRangeHint"></div>
        </div>
        <div class="col-md-4">
            <label class="form-label">เวลาเริ่ม *</label>
            <input type="time" name="start_time" id="startTime" class="form-control"
                value="{{ old('start_time', $classSchedule->start_time ?? '') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">เวลาสิ้นสุด *</label>
            <input type="time" name="end_time" id="endTime" class="form-control"
                value="{{ old('end_time', $classSchedule->end_time ?? '') }}" required>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <label class="form-label d-block">รูปแบบการเรียน *</label>
            <div class="pill-options" id="deliveryModePills">
                <div class="pill-option" data-value="onsite"><i class="bi bi-building"></i> ที่โรงเรียน</div>
                <div class="pill-option" data-value="online"><i class="bi bi-camera-video"></i> ออนไลน์</div>
                <div class="pill-option" data-value="hybrid"><i class="bi bi-arrow-left-right"></i> ไฮบริด</div>
            </div>
            <input type="hidden" name="delivery_mode" id="deliveryModeInput"
                value="{{ old('delivery_mode', $classSchedule->delivery_mode ?? 'onsite') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label d-block">สถานะ *</label>
            <div class="pill-options" id="statusPills">
                <div class="pill-option" data-value="scheduled"><i class="bi bi-clock"></i> นัดสอน</div>
                <div class="pill-option" data-value="completed"><i class="bi bi-check-circle"></i> สอนแล้ว</div>
                <div class="pill-option" data-value="no_show"><i class="bi bi-x-circle"></i> ขาดเรียน</div>
            </div>
            <input type="hidden" name="status" id="statusInput"
                value="{{ old('status', $classSchedule->status ?? 'scheduled') }}">
        </div>
        <div class="col-12">
            <label class="form-label">หมายเหตุ</label>
            <textarea name="notes" class="form-control" rows="2" maxlength="1000"
                placeholder="เช่น เนื้อหาที่จะสอน, ข้อควรระวัง ฯลฯ">{{ old('notes', $classSchedule->notes ?? '') }}</textarea>
        </div>
    </div>

    <div id="conflictBox" class="status-box danger d-none"></div>
</div>

<script>
    (function() {
        const enrollmentSelect = document.getElementById('enrollmentSelect');
        const teacherSelect = document.getElementById('teacherSelect');
        const teacherAutoHint = document.getElementById('teacherAutoHint');
        const roomSelect = document.querySelector('[name=room_id]');
        const dateInput = document.getElementById('scheduleDate');
        const dateRangeHint = document.getElementById('dateRangeHint');
        const courseDateRangeBox = document.getElementById('courseDateRangeBox');
        const startInput = document.getElementById('startTime');
        const endInput = document.getElementById('endTime');
        const conflictBox = document.getElementById('conflictBox');
        const sessionInfoBox = document.getElementById('sessionInfoBox');
        const submitBtn = document.querySelector('button[type=submit]');
        const excludeId = "{{ $classSchedule->id ?? '' }}";
        const isEditMode = {{ isset($classSchedule) ? 'true' : 'false' }};

        // ===== การ์ด pill: รูปแบบการเรียน / สถานะ =====
        function bindPills(containerId, hiddenInputId) {
            const container = document.getElementById(containerId);
            const hiddenInput = document.getElementById(hiddenInputId);
            const options = container.querySelectorAll('.pill-option');

            function setActive(value) {
                options.forEach(o => o.classList.toggle('active', o.dataset.value === value));
            }
            setActive(hiddenInput.value);

            options.forEach(opt => {
                opt.addEventListener('click', () => {
                    hiddenInput.value = opt.dataset.value;
                    setActive(opt.dataset.value);
                });
            });
        }
        bindPills('deliveryModePills', 'deliveryModeInput');
        bindPills('statusPills', 'statusInput');

        // ===== สรุปนักเรียน/คอร์สที่เลือก + เติมอาจารย์อัตโนมัติ + จำกัดช่วงวันที่ตามประเภทคอร์ส =====
        const summaryBox = document.getElementById('selectionSummary');
        const summaryAvatar = document.getElementById('summaryAvatar');
        const summaryName = document.getElementById('summaryName');
        const summaryCourse = document.getElementById('summaryCourse');

        function formatThaiDate(isoDate) {
            if (!isoDate) return '';
            const d = new Date(isoDate + 'T00:00:00');
            return d.toLocaleDateString('th-TH', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        // Business rule:
        // - คอร์สแบบพิเศษ (มีวันที่เริ่ม-สิ้นสุดกำหนดตายตัว) -> จำกัดให้เลือกวันที่จัดตารางได้เฉพาะในช่วงนั้น
        // - คอร์สแบบปกติ (นับจำนวนครั้ง) -> เลือกวันที่ได้อิสระตามที่นักเรียน/อาจารย์สะดวก
        function applyDateConstraint(opt) {
            const structureType = opt?.dataset.structureType;
            const courseStart = opt?.dataset.courseStart;
            const courseEnd = opt?.dataset.courseEnd;

            if (structureType === 'special' && (courseStart || courseEnd)) {
                dateInput.min = courseStart || '';
                dateInput.max = courseEnd || '';

                // ถ้าวันที่ที่เลือกไว้อยู่นอกช่วง ให้ปรับเข้ามาในช่วงอัตโนมัติ
                if (courseStart && dateInput.value < courseStart) dateInput.value = courseStart;
                if (courseEnd && dateInput.value > courseEnd) dateInput.value = courseEnd;

                dateRangeHint.innerHTML =
                    `<i class="bi bi-calendar-range text-warning"></i> คอร์สแบบพิเศษ เลือกวันที่ได้เฉพาะ ${formatThaiDate(courseStart)} - ${formatThaiDate(courseEnd)}`;
                courseDateRangeBox.className = 'status-box warn';
                courseDateRangeBox.innerHTML =
                    `<i class="bi bi-calendar-range"></i> คอร์สนี้เป็นแบบพิเศษ กำหนดวันเรียนไว้ระหว่าง <strong>${formatThaiDate(courseStart)} - ${formatThaiDate(courseEnd)}</strong> — เลือกวันนอกช่วงนี้ไม่ได้`;
                courseDateRangeBox.classList.remove('d-none');
            } else {
                dateInput.removeAttribute('min');
                dateInput.removeAttribute('max');
                dateRangeHint.innerHTML =
                    '<i class="bi bi-info-circle"></i> คอร์สแบบปกติ เลือกวันที่ได้อิสระตามที่สะดวกเรียน';
                courseDateRangeBox.classList.add('d-none');
            }
        }

        enrollmentSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const teacherId = opt?.dataset.teacherId;
            const teacherName = opt?.dataset.teacherName;
            const studentName = opt?.dataset.studentName;
            const courseName = opt?.dataset.courseName;

            if (studentName) {
                summaryAvatar.textContent = studentName.charAt(0);
                summaryName.textContent = studentName;
                summaryCourse.textContent = courseName;
                summaryBox.classList.add('show');
            } else {
                summaryBox.classList.remove('show');
            }

            applyDateConstraint(opt);

            if (!isEditMode && teacherId && teacherId !== '' && teacherId !== '0') {
                teacherSelect.value = teacherId;
                teacherAutoHint.innerHTML =
                    `<i class="bi bi-check-circle text-success"></i> เติมอาจารย์ "${teacherName}" ที่เลือกไว้ตอนสมัครเรียนให้อัตโนมัติ — เปลี่ยนได้ถ้าต้องการ`;
            } else if (teacherId) {
                teacherAutoHint.textContent = '';
            } else {
                teacherAutoHint.innerHTML =
                    '<i class="bi bi-info-circle"></i> ตอนสมัครเรียนไม่ได้ระบุอาจารย์ไว้ — เลือกอาจารย์ที่นี่ได้เลย';
            }
            checkConflict();
        });
        enrollmentSelect.dispatchEvent(new Event('change'));

        dateInput.addEventListener('change', checkConflict);

        async function checkConflict() {
            if (!enrollmentSelect.value || !dateInput.value || !startInput.value || !endInput.value) {
                conflictBox.classList.add('d-none');
                sessionInfoBox.classList.add('d-none');
                return;
            }
            const params = new URLSearchParams({
                enrollment_id: enrollmentSelect.value,
                teacher_id: teacherSelect.value || '',
                room_id: roomSelect.value || '',
                schedule_date: dateInput.value,
                start_time: startInput.value,
                end_time: endInput.value,
                exclude_id: excludeId,
            });

            try {
                const res = await fetch(`{{ route('schedules.check-conflict') }}?${params.toString()}`);
                const data = await res.json();
                let hasBlockingError = false;

                if (data.conflicts && data.conflicts.length > 0) {
                    conflictBox.innerHTML = '<i class="bi bi-exclamation-triangle"></i><div>' + data.conflicts
                        .join('<br>') + '</div>';
                    conflictBox.classList.remove('d-none');
                    hasBlockingError = true;
                } else {
                    conflictBox.classList.add('d-none');
                }

                if (data.session_info) {
                    const s = data.session_info;
                    sessionInfoBox.classList.remove('d-none');
                    if (s.is_full) {
                        sessionInfoBox.className = 'status-box danger';
                        sessionInfoBox.innerHTML =
                            `<i class="bi bi-exclamation-triangle"></i><div>จัดตารางครบ ${s.total} ครั้งตามแพ็กเกจแล้ว (ใช้ไป ${s.used}/${s.total}) ไม่สามารถเพิ่มคาบใหม่ได้</div>`;
                        hasBlockingError = true;
                    } else {
                        sessionInfoBox.className = 'status-box ok';
                        sessionInfoBox.innerHTML =
                            `<i class="bi bi-calendar2-check"></i><div>จัดตารางไปแล้ว ${s.used} จาก ${s.total} ครั้งตามแพ็กเกจ (คงเหลือ ${s.remaining} ครั้ง)</div>`;
                    }
                } else {
                    sessionInfoBox.classList.add('d-none');
                }

                // เช็คช่วงวันที่คอร์สแบบพิเศษอีกชั้นฝั่ง JS (กันกรณีแก้ min/max ผ่าน devtools)
                if (dateInput.min && dateInput.value < dateInput.min) hasBlockingError = true;
                if (dateInput.max && dateInput.value > dateInput.max) hasBlockingError = true;

                if (submitBtn) submitBtn.disabled = hasBlockingError;
            } catch (e) {
                console.error(e);
            }
        }

        [teacherSelect, roomSelect, startInput, endInput].forEach(el => el.addEventListener('change',
            checkConflict));
        checkConflict();
    })();
</script>

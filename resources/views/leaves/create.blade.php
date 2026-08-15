@extends('layouts.app')
@section('title', 'แจ้งลาเรียน')

@section('content')
    <style>
        .form-section {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.4rem 1.6rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 2px rgba(28, 26, 23, .04);
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
            flex-shrink: 0;
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

        .quota-box {
            border-radius: 12px;
            padding: 1rem 1.1rem;
            margin-top: 1rem;
            display: none;
        }

        .quota-box.show {
            display: block;
        }

        .quota-box .row-line {
            display: flex;
            justify-content: space-between;
            padding: .35rem 0;
            border-bottom: 1px dashed rgba(0, 0, 0, .08);
            font-size: .85rem;
        }

        .quota-box .row-line:last-child {
            border-bottom: 0;
        }

        .makeup-fields-box {
            background: #fbf7f0;
            border: 1px solid #e6d9c3;
            border-radius: 12px;
            padding: 1.1rem;
            margin-top: 1rem;
        }
    </style>

    <div class="breadcrumb-sm"><a href="{{ route('leaves.index') }}" class="text-decoration-none text-muted">ลาเรียน</a> <i
            class="bi bi-chevron-right small"></i> แจ้งลาใหม่</div>
    <h1 class="page-title mb-3"><i class="bi bi-calendar-x"></i> แจ้งลาเรียน</h1>

    @if ($students->isEmpty())
        <div class="alert alert-warning">บัญชีนี้ยังไม่ได้ผูกกับข้อมูลนักเรียนคนไหนเลย กรุณาติดต่อผู้ดูแลระบบ</div>
    @else
        <form action="{{ route('students.leaves.store', ['student' => $preselectedStudentId]) }}" method="POST"
            id="leaveForm">
            @csrf

            {{-- 1. เลือกนักเรียน (แสดงเฉพาะกรณีผู้ปกครองมีลูกหลายคน) --}}
            @if ($students->count() > 1)
                <div class="form-section">
                    <div class="form-section-title">
                        <div class="icon-badge"><i class="bi bi-person"></i></div> เลือกนักเรียน
                    </div>
                    <select id="studentSwitcher" class="form-select">
                        @foreach ($students as $s)
                            <option value="{{ $s->id }}" {{ $s->id == $preselectedStudentId ? 'selected' : '' }}>
                                {{ $s->full_name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted"><i class="bi bi-info-circle"></i>
                        เปลี่ยนนักเรียนแล้วหน้าจะโหลดคอร์สของคนนั้นให้อัตโนมัติ</small>
                </div>
            @endif

            {{-- 2. เลือกคอร์ส + ประเภทการลา --}}
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-journal-bookmark"></i></div> คอร์สและประเภทการลา
                </div>
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">คอร์สที่ลา *</label>
                        <select name="enrollment_id" id="enrollmentSelect" class="form-select" required></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ประเภทการลา *</label>
                        <select name="leave_type" id="leaveTypeSelect" class="form-select" required>
                            <option value="normal">ลาปกติ (ขอชดเชย)</option>
                            <option value="emergency">ลาฉุกเฉิน</option>
                            <option value="no_makeup">ลาแบบไม่ชดเชย</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">วันที่ลา *</label>
                        <input type="date" name="leave_date" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">เหตุผล</label>
                        <input type="text" name="reason" class="form-control" placeholder="เช่น ป่วย, ธุระครอบครัว">
                    </div>
                </div>

                {{-- สิทธิ์คงเหลือของคอร์สที่เลือก --}}
                <div id="quotaBox" class="quota-box">
                    <div class="fw-semibold small mb-2" style="font-family:'Prompt',sans-serif;"><i
                            class="bi bi-shield-check"></i> สิทธิ์การลาของคอร์สนี้</div>
                    <div class="row-line"><span>ลาฉุกเฉิน (ตามที่คอร์สกำหนด)</span><span id="quotaEmergencyText">-</span>
                    </div>
                    <div class="row-line"><span>สิทธิ์เรียนชดเชย</span><span id="quotaMakeupText">-</span></div>
                    <div class="row-line"><span>ระยะเวลาแจ้งล่วงหน้าที่ต้องใช้ (ลาปกติ/ไม่ชดเชย)</span><span>อย่างน้อย
                            {{ config('leave.normal_advance_notice_hours', 24) }} ชม.</span></div>
                </div>
            </div>

            {{-- 3. เลือกวันเรียนชดเชย (บังคับสำหรับลาปกติ) --}}
            <div class="form-section d-none" id="makeupSection">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-calendar-plus"></i></div> เลือกวันเรียนชดเชย (บังคับกรอก)
                </div>
                <div class="makeup-fields-box">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">อาจารย์ผู้สอนชดเชย</label>
                            <select name="makeup_teacher_id" id="makeupTeacherSelect" class="form-select"></select>
                            <small class="text-muted" id="teacherDefaultHint"></small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ห้องเรียน</label>
                            <select name="makeup_room_id" class="form-select">
                                <option value="">ไม่ระบุ (ออนไลน์)</option>
                                @foreach (\App\Models\Room::where('is_active', true)->orderBy('name')->get() as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">รูปแบบการเรียน</label>
                            <select name="makeup_delivery_mode" class="form-select">
                                <option value="onsite">ที่โรงเรียน</option>
                                <option value="online">ออนไลน์</option>
                                <option value="hybrid">ไฮบริด</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">วันที่เรียนชดเชย</label>
                            <input type="date" name="makeup_date" id="makeupDateInput" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">เวลาเริ่ม</label>
                            <input type="time" name="makeup_start_time" id="makeupStartInput" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">เวลาสิ้นสุด</label>
                            <input type="time" name="makeup_end_time" id="makeupEndInput" class="form-control">
                        </div>
                    </div>
                    <div id="makeupConflictBox" class="alert alert-danger small mt-3 mb-0 d-none"></div>
                </div>
            </div>

            <div class="d-flex gap-2 mb-4">
                <button class="btn btn-accent" id="submitBtn"><i class="bi bi-send"></i> ส่งคำขอลา</button>
                <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>

        <script id="studentsData" type="application/json">
{!! json_encode($students->mapWithKeys(function ($s) {
    return [$s->id => $s->enrollments->map(function ($e) {
        return [
            'id' => $e->id,
            'course_name' => $e->course->name ?? '-',
            'allow_makeup' => (bool) ($e->course->allow_makeup_class ?? false),
            'emergency_used' => $e->emergencyLeaveUsed(),
            'emergency_quota' => $e->emergencyLeaveQuota(),
            'teacher_id' => $e->teacher_id,
            'teacher_name' => $e->teacher->nickname ?? ($e->teacher->full_name ?? null),
        ];
    })->values()];
})) !!}
</script>

        <script>
            (function() {
                const studentsData = JSON.parse(document.getElementById('studentsData').textContent);
                const studentSwitcher = document.getElementById('studentSwitcher');
                const enrollmentSelect = document.getElementById('enrollmentSelect');
                const leaveTypeSelect = document.getElementById('leaveTypeSelect');
                const quotaBox = document.getElementById('quotaBox');
                const quotaEmergencyText = document.getElementById('quotaEmergencyText');
                const quotaMakeupText = document.getElementById('quotaMakeupText');
                const makeupSection = document.getElementById('makeupSection');
                const makeupTeacherSelect = document.getElementById('makeupTeacherSelect');
                const teacherDefaultHint = document.getElementById('teacherDefaultHint');
                const makeupDate = document.getElementById('makeupDateInput');
                const makeupStart = document.getElementById('makeupStartInput');
                const makeupEnd = document.getElementById('makeupEndInput');
                const conflictBox = document.getElementById('makeupConflictBox');
                const submitBtn = document.getElementById('submitBtn');
                const leaveForm = document.getElementById('leaveForm');

                const allTeachers = @json(\App\Models\Teacher::where('is_active', true)->orderBy('full_name')->get()->map(fn($t) => ['id' => $t->id, 'name' => $t->nickname ?: $t->full_name]));

                function currentStudentId() {
                    return studentSwitcher ? studentSwitcher.value : Object.keys(studentsData)[0];
                }

                function populateEnrollments() {
                    const list = studentsData[currentStudentId()] || [];
                    enrollmentSelect.innerHTML = '';
                    if (list.length === 0) {
                        enrollmentSelect.innerHTML = '<option value="">ไม่มีคอร์สที่กำลังเรียนอยู่</option>';
                        return;
                    }
                    list.forEach(e => {
                        enrollmentSelect.insertAdjacentHTML('beforeend',
                            `<option value="${e.id}"
                data-allow-makeup="${e.allow_makeup ? '1':'0'}"
                data-emergency-used="${e.emergency_used}" data-emergency-quota="${e.emergency_quota}"
                data-teacher-id="${e.teacher_id ?? ''}" data-teacher-name="${e.teacher_name ?? ''}">${e.course_name}</option>`);
                    });
                    updateQuotaAndMakeup();
                }

                function updateQuotaAndMakeup() {
                    const opt = enrollmentSelect.options[enrollmentSelect.selectedIndex];
                    if (!opt || !opt.value) {
                        quotaBox.classList.remove('show');
                        return;
                    }

                    const emergencyUsed = parseInt(opt.dataset.emergencyUsed || 0);
                    const emergencyQuota = parseInt(opt.dataset.emergencyQuota || 0);
                    const allowMakeup = opt.dataset.allowMakeup === '1';

                    quotaBox.classList.add('show');
                    const remaining = Math.max(0, emergencyQuota - emergencyUsed);
                    quotaEmergencyText.innerHTML = emergencyQuota > 0 ?
                        `ใช้ไปแล้ว ${emergencyUsed}/${emergencyQuota} ครั้ง <strong>(เหลือ ${remaining} ครั้ง)</strong>` :
                        'คอร์สนี้ไม่ได้กำหนดสิทธิ์ลาฉุกเฉิน';
                    quotaMakeupText.innerHTML = allowMakeup ?
                        '<span class="text-success">เปิดสิทธิ์เรียนชดเชย</span>' :
                        '<span class="text-danger">ไม่เปิดสิทธิ์เรียนชดเชย (เลือกได้เฉพาะ "ลาแบบไม่ชดเชย" หรือ "ลาฉุกเฉิน")</span>';

                    const normalOpt = leaveTypeSelect.querySelector('option[value=normal]');
                    normalOpt.disabled = !allowMakeup;
                    if (!allowMakeup && leaveTypeSelect.value === 'normal') leaveTypeSelect.value = 'no_makeup';

                    // เติมรายชื่ออาจารย์ในช่องเรียนชดเชย โดยเลือกอาจารย์เดิมของคอร์สนี้ไว้ก่อนเป็นค่าเริ่มต้น
                    const defaultTeacherId = opt.dataset.teacherId;
                    const defaultTeacherName = opt.dataset.teacherName;
                    makeupTeacherSelect.innerHTML = '<option value="">ให้ทางโรงเรียนจัดให้</option>';
                    allTeachers.forEach(t => makeupTeacherSelect.insertAdjacentHTML('beforeend',
                        `<option value="${t.id}">${t.name}</option>`));
                    if (defaultTeacherId) {
                        makeupTeacherSelect.value = defaultTeacherId;
                        teacherDefaultHint.innerHTML =
                            `<i class="bi bi-check-circle text-success"></i> เติมอาจารย์ประจำของคอร์สนี้ (${defaultTeacherName}) ให้อัตโนมัติ — เปลี่ยนได้ถ้าต้องการ`;
                    } else {
                        teacherDefaultHint.textContent = 'คอร์สนี้ยังไม่ได้ระบุอาจารย์ประจำไว้ — เลือกอาจารย์ได้เลย';
                    }

                    toggleMakeupSection();
                }

                function toggleMakeupSection() {
                    const isNormal = leaveTypeSelect.value === 'normal';
                    makeupSection.classList.toggle('d-none', !isNormal);
                    [makeupTeacherSelect, makeupDate, makeupStart, makeupEnd].forEach(el => el.required = isNormal);
                }

                async function checkMakeupConflict() {
                    if (leaveTypeSelect.value !== 'normal') {
                        conflictBox.classList.add('d-none');
                        submitBtn.disabled = false;
                        return;
                    }
                    if (!makeupTeacherSelect.value || !makeupDate.value || !makeupStart.value || !makeupEnd.value)
                        return;

                    const params = new URLSearchParams({
                        student_id: currentStudentId(),
                        teacher_id: makeupTeacherSelect.value,
                        date: makeupDate.value,
                        start_time: makeupStart.value,
                        end_time: makeupEnd.value,
                    });
                    try {
                        const res = await fetch(`{{ route('makeup-requests.check-conflict') }}?${params.toString()}`);
                        const data = await res.json();
                        if (data.conflicts && data.conflicts.length > 0) {
                            conflictBox.innerHTML = '<i class="bi bi-exclamation-triangle"></i> ' + data.conflicts.join(
                                '<br>');
                            conflictBox.classList.remove('d-none');
                            submitBtn.disabled = true;
                        } else {
                            conflictBox.classList.add('d-none');
                            submitBtn.disabled = false;
                        }
                    } catch (e) {
                        console.error(e);
                    }
                }

                if (studentSwitcher) {
                    studentSwitcher.addEventListener('change', function() {
                        leaveForm.action = leaveForm.action.replace(/students\/\d+/, `students/${this.value}`);
                        populateEnrollments();
                    });
                }

                enrollmentSelect.addEventListener('change', updateQuotaAndMakeup);
                leaveTypeSelect.addEventListener('change', () => {
                    toggleMakeupSection();
                    checkMakeupConflict();
                });
                [makeupTeacherSelect, makeupDate, makeupStart, makeupEnd].forEach(el => el.addEventListener('change',
                    checkMakeupConflict));

                leaveForm.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังส่ง...';
                });

                populateEnrollments();
            })();
        </script>
    @endif
@endsection

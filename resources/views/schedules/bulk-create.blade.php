@extends('layouts.app')
@section('title', 'จัดตารางเรียนแบบชุด')
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

        .day-pills {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .day-pill {
            border: 1.5px solid var(--border, #e4e1dc);
            border-radius: 10px;
            padding: .5rem 1rem;
            cursor: pointer;
            font-size: .85rem;
            font-weight: 600;
            transition: .15s;
            user-select: none;
        }

        .day-pill:hover {
            border-color: #c9c4bb;
        }

        .day-pill.active {
            border-color: var(--accent, #1f3350);
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent-dark, #13233a);
        }

        .special-info-box {
            background: var(--amber-soft, #f3ece2);
            border: 1px solid #e6d9c3;
            border-radius: 12px;
            padding: 1rem 1.2rem;
            display: flex;
            gap: .7rem;
        }

        .status-box {
            border-radius: 12px;
            padding: .85rem 1rem;
            font-size: .85rem;
            margin-top: 1rem;
        }

        .status-box.ok {
            background: var(--success-soft, #e7f2ec);
            color: var(--success, #2f6f4e);
        }

        .status-box.danger {
            background: #fbeae7;
            color: #b3392c;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            gap: .6rem;
            margin-bottom: 1.4rem;
        }

        .step-dot {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .85rem;
            font-weight: 600;
            color: var(--muted, #6b655e);
        }

        .step-dot .num {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--border, #e4e1dc);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
        }

        .step-dot.active {
            color: var(--accent-dark, #13233a);
        }

        .step-dot.active .num {
            background: var(--accent, #1f3350);
        }

        .step-dot.done .num {
            background: var(--success, #2f6f4e);
        }

        .step-line {
            flex: 1;
            height: 2px;
            background: var(--border, #e4e1dc);
            max-width: 60px;
        }

        .preview-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 .5rem;
        }

        .preview-row {
            background: #fff;
        }

        .preview-row.has-conflict {
            background: #fef3f1;
        }

        .preview-row td {
            padding: .5rem .6rem;
            vertical-align: middle;
            border-top: 1px solid var(--border, #e4e1dc);
            border-bottom: 1px solid var(--border, #e4e1dc);
        }

        .preview-row td:first-child {
            border-left: 1px solid var(--border, #e4e1dc);
            border-radius: 10px 0 0 10px;
        }

        .preview-row td:last-child {
            border-right: 1px solid var(--border, #e4e1dc);
            border-radius: 0 10px 10px 0;
        }

        .preview-row.has-conflict td {
            border-color: #f3c3ba;
        }

        .preview-input {
            font-size: .82rem;
            padding: .3rem .5rem;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 6px;
            width: 100%;
        }

        .row-conflict-msg {
            font-size: .72rem;
            color: #b3392c;
            margin-top: .2rem;
        }

        .row-number {
            font-size: .78rem;
            color: var(--muted, #6b655e);
            font-weight: 600;
            width: 30px;
        }

        .summary-bar {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            padding: .9rem 1.2rem;
            background: var(--accent-soft, #e7ebf1);
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: .85rem;
        }

        .summary-bar strong {
            font-family: 'Prompt', sans-serif;
        }
    </style>
    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> ตารางเรียน <i
            class="bi bi-chevron-right small"></i> จัดแบบชุด</div>
    <h1 class="page-title mb-1"><i class="bi bi-calendar2-range"></i> จัดตารางเรียนแบบชุด</h1>
    <div class="page-sub mb-3">กำหนดค่าเริ่มต้น → ดูตัวอย่างและแก้ไขทีละคาบ → ยืนยันบันทึกทั้งหมด</div>
    <div class="step-indicator">
        <div class="step-dot active" id="stepDot1"><span class="num">1</span> กำหนดค่าเริ่มต้น</div>
        <div class="step-line"></div>
        <div class="step-dot" id="stepDot2"><span class="num">2</span> ตรวจสอบและแก้ไข</div>
    </div>
    <div id="step1">
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-mortarboard"></i></div> เลือกนักเรียน (จากการลงทะเบียนเรียนที่
                Active)
            </div>
            <select id="enrollmentSelect" class="form-select" required>
                <option value="">เลือกนักเรียน + คอร์สที่ลงทะเบียนไว้...</option>
                @foreach ($enrollments as $e)
                    @continue(!$e->student)
                    <option value="{{ $e->id }}" data-teacher-id="{{ $e->teacher_id }}"
                        data-structure-type="{{ $e->course->structure_type ?? 'regular' }}"
                        data-total-sessions="{{ $e->course->total_sessions ?? '' }}"
                        data-course-start="{{ optional($e->course->course_start_date)->format('d/m/Y') }}"
                        data-course-end="{{ optional($e->course->course_end_date)->format('d/m/Y') }}"
                        {{ old('enrollment_id', $preselectedEnrollment->id ?? '') == $e->id ? 'selected' : '' }}>
                        {{ $e->student->full_name }} — {{ $e->course->name ?? 'ไม่พบคอร์ส' }}
                        ({{ $e->course->course_code ?? '-' }})
                    </option>
                @endforeach
            </select>
            <div id="sessionInfoBox" class="status-box ok d-none"></div>
            @if ($enrollments->isEmpty())
                <div class="alert alert-warning small mt-2 mb-0">ยังไม่มีนักเรียนที่ลงทะเบียนเรียนอยู่ในระบบ</div>
            @endif
        </div>
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-person-badge"></i></div> ค่าเริ่มต้น (แก้ไขทีละคาบได้ในขั้นถัดไป)
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">อาจารย์ผู้สอน</label>
                    <select id="teacherSelect" class="form-select">
                        <option value="">ให้ทางโรงเรียนจัดให้</option>
                        @foreach ($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->nickname ?: $t->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ห้องเรียน</label>
                    <select id="roomSelect" class="form-select">
                        <option value="">ไม่ระบุห้อง (ออนไลน์)</option>
                        @foreach ($rooms as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">รูปแบบการเรียน</label>
                    <select id="deliveryModeSelect" class="form-select">
                        <option value="onsite">ที่โรงเรียน</option>
                        <option value="online">ออนไลน์</option>
                        <option value="hybrid">ไฮบริด</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- จุดแก้บั๊กหลัก: ช่วงเวลาเรียนแยกออกมาเป็นกล่องอิสระ ใช้ร่วมกันทั้ง 2 โหมด ไม่ถูกซ่อนไปพร้อมกล่องอื่นเด็ดขาด --}}
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-clock"></i></div> ช่วงเวลาเรียน
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">เวลาเริ่ม *</label>
                    <input type="time" id="startTimeInput" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">เวลาสิ้นสุด *</label>
                    <input type="time" id="endTimeInput" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="form-section" id="weeklyModeBox">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-calendar-week"></i></div> รูปแบบการเรียนรายสัปดาห์
            </div>
            <label class="form-label d-block">เลือกวันเรียน (เลือกได้หลายวัน)</label>
            <div class="day-pills mb-3" id="dayPills">
                @foreach (\App\Models\TeacherAvailability::dayLabels() as $dow => $label)
                    <div class="day-pill" data-value="{{ $dow }}">{{ $label }}</div>
                @endforeach
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">เริ่มจากวันที่ *</label>
                    <input type="date" id="startDateInput" class="form-control" value="{{ now()->toDateString() }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">จำนวนครั้งที่จะสร้าง *</label>
                    <input type="number" id="sessionCountInput" class="form-control" min="1" max="200">
                    <small class="text-muted" id="sessionCountHint"></small>
                </div>
            </div>
        </div>
        <div class="form-section d-none" id="dailyRangeModeBox">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-calendar-range"></i></div> รูปแบบคอร์สพิเศษ
                (สร้างตามช่วงวันที่คอร์สกำหนด)
            </div>
            <div class="special-info-box mb-3">
                <i class="bi bi-info-circle fs-5"></i>
                <div>คอร์สนี้กำหนดวันเรียนไว้แล้วตั้งแต่ <strong id="specialDateRangeText">-</strong> —
                    ระบบจะสร้างร่างตารางให้ครบทุกวันในช่วงนี้ ให้ตรวจสอบและแก้ไขได้ในขั้นถัดไป กรอกแค่เวลาเรียนด้านบนก็พอ
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mb-4">
            <button type="button" class="btn btn-accent" id="generatePreviewBtn"><i class="bi bi-eye"></i>
                สร้างตัวอย่างและตรวจสอบ</button>
            <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </div>
    <div id="step2" class="d-none">
        <div class="summary-bar">
            <span><i class="bi bi-list-ol"></i> ทั้งหมด <strong id="summaryTotal">0</strong> คาบ</span>
            <span class="text-success"><i class="bi bi-check-circle"></i> พร้อมบันทึก <strong id="summaryOk">0</strong>
                คาบ</span>
            <span class="text-danger"><i class="bi bi-exclamation-triangle"></i> ตารางชน <strong
                    id="summaryConflict">0</strong> คาบ</span>
            <a href="#" id="backToStep1" class="ms-auto small"><i class="bi bi-arrow-left"></i>
                กลับไปแก้ค่าเริ่มต้น</a>
        </div>
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-table"></i></div> ตรวจสอบและแก้ไขแต่ละคาบก่อนบันทึกจริง
            </div>
            <div class="table-responsive">
                <table class="preview-table" id="previewTable">
                    <thead>
                        <tr style="font-size:.72rem; color:var(--muted,#6b655e); text-transform:uppercase;">
                            <th></th>
                            <th>วันที่</th>
                            <th>เวลาเริ่ม</th>
                            <th>เวลาสิ้นสุด</th>
                            <th>อาจารย์</th>
                            <th>ห้องเรียน</th>
                            <th>รูปแบบ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="previewTbody"></tbody>
                </table>
            </div>
            <small class="text-muted d-block mt-2"><i class="bi bi-info-circle"></i> แถวที่มีพื้นหลังสีแดง =
                ตารางชนกับคาบอื่น ต้องแก้ไขวัน/เวลา/อาจารย์/ห้อง หรือลบแถวออกก่อนบันทึก</small>
        </div>
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-journal-text"></i></div> หมายเหตุ (ใช้ร่วมกันทุกคาบ)
            </div>
            <textarea id="notesInput" class="form-control" rows="2" maxlength="1000"></textarea>
        </div>
        <form action="{{ route('schedules.bulk-confirm') }}" method="POST" id="confirmForm">
            @csrf
            <input type="hidden" name="enrollment_id" id="confirmEnrollmentId">
            <input type="hidden" name="notes" id="confirmNotes">
            <div id="confirmRowsContainer"></div>
            <div class="d-flex gap-2 mb-4">
                <button class="btn btn-accent" id="confirmBtn"><i class="bi bi-calendar2-check"></i>
                    ยืนยันบันทึกทั้งหมด</button>
                <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
    <script>
        (function() {
            const enrollmentSelect = document.getElementById('enrollmentSelect');
            const sessionInfoBox = document.getElementById('sessionInfoBox');
            const weeklyBox = document.getElementById('weeklyModeBox');
            const dailyRangeBox = document.getElementById('dailyRangeModeBox');
            const dayPills = document.getElementById('dayPills');
            const sessionCountInput = document.getElementById('sessionCountInput');
            const sessionCountHint = document.getElementById('sessionCountHint');
            const startTimeInput = document.getElementById('startTimeInput');
            const endTimeInput = document.getElementById('endTimeInput');
            const startDateInput = document.getElementById('startDateInput');
            const teacherSelect = document.getElementById('teacherSelect');
            const roomSelect = document.getElementById('roomSelect');
            const deliveryModeSelect = document.getElementById('deliveryModeSelect');
            const generateBtn = document.getElementById('generatePreviewBtn');
            const teachersList = @json($teachers->map(fn($t) => ['id' => $t->id, 'name' => $t->nickname ?: $t->full_name]));
            const roomsList = @json($rooms->map(fn($r) => ['id' => $r->id, 'name' => $r->name]));
            let currentMode = 'weekly';
            let selectedDays = [];
            dayPills.querySelectorAll('.day-pill').forEach(pill => {
                pill.addEventListener('click', () => {
                    const val = pill.dataset.value;
                    pill.classList.toggle('active');
                    selectedDays = selectedDays.includes(val) ? selectedDays.filter(d => d !== val) : [
                        ...selectedDays, val
                    ];
                });
            });

            function switchMode(structureType, totalSessions, courseStart, courseEnd) {
                currentMode = structureType === 'special' ? 'daily_range' : 'weekly';
                weeklyBox.classList.toggle('d-none', currentMode === 'daily_range');
                dailyRangeBox.classList.toggle('d-none', currentMode !== 'daily_range');
                if (currentMode === 'daily_range') {
                    document.getElementById('specialDateRangeText').textContent =
                        `${courseStart || '-'} ถึง ${courseEnd || '-'}`;
                } else if (totalSessions) {
                    sessionCountInput.value = totalSessions;
                    sessionCountInput.max = totalSessions;
                    sessionCountHint.textContent = `คอร์สนี้มีทั้งหมด ${totalSessions} ครั้งตามแพ็กเกจ`;
                } else {
                    sessionCountInput.value = '';
                    sessionCountInput.removeAttribute('max');
                    sessionCountHint.textContent = 'คอร์สนี้ไม่ได้จำกัดจำนวนครั้ง';
                }
            }
            enrollmentSelect.addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                if (!opt || !opt.value) {
                    sessionInfoBox.classList.add('d-none');
                    return;
                }
                switchMode(opt.dataset.structureType, opt.dataset.totalSessions, opt.dataset.courseStart, opt
                    .dataset.courseEnd);
                if (opt.dataset.teacherId) teacherSelect.value = opt.dataset.teacherId;
            });
            enrollmentSelect.dispatchEvent(new Event('change'));
            generateBtn.addEventListener('click', async function() {
                if (!enrollmentSelect.value) {
                    alert('กรุณาเลือกนักเรียนก่อน');
                    return;
                }
                if (!startTimeInput.value || !endTimeInput.value) {
                    alert('กรุณากรอกเวลาเริ่มและเวลาสิ้นสุดก่อน');
                    return;
                }
                if (endTimeInput.value <= startTimeInput.value) {
                    alert('เวลาสิ้นสุดต้องอยู่หลังเวลาเริ่ม');
                    return;
                }
                if (currentMode === 'weekly') {
                    if (selectedDays.length === 0) {
                        alert('กรุณาเลือกวันเรียนอย่างน้อย 1 วัน');
                        return;
                    }
                    if (!startDateInput.value) {
                        alert('กรุณาเลือกวันที่เริ่มต้น');
                        return;
                    }
                    if (!sessionCountInput.value || sessionCountInput.value < 1) {
                        alert('กรุณากรอกจำนวนครั้งที่จะสร้าง');
                        return;
                    }
                }
                const payload = {
                    enrollment_id: enrollmentSelect.value,
                    teacher_id: teacherSelect.value || null,
                    room_id: roomSelect.value || null,
                    delivery_mode: deliveryModeSelect.value,
                    mode: currentMode,
                    days_of_week: selectedDays,
                    start_date: startDateInput.value,
                    session_count: sessionCountInput.value,
                    start_time: startTimeInput.value,
                    end_time: endTimeInput.value,
                };
                generateBtn.disabled = true;
                generateBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span> กำลังสร้างตัวอย่าง...';
                try {
                    const res = await fetch('{{ route('schedules.bulk-preview') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: JSON.stringify(payload),
                    });
                    let body;
                    try {
                        body = await res.json();
                    } catch (parseErr) {
                        console.error('Response is not JSON:', parseErr);
                        alert('เซิร์ฟเวอร์ตอบกลับผิดปกติ กรุณาโหลดหน้านี้ใหม่แล้วลองอีกครั้ง');
                        return;
                    }
                    if (!res.ok) {
                        let msg = body.error || body.message || 'สร้างตัวอย่างไม่สำเร็จ กรุณาตรวจสอบข้อมูล';
                        if (body.errors) {
                            msg += '\n\n' + Object.values(body.errors).flat().join('\n');
                        }
                        alert(msg);
                        return;
                    }
                    renderPreview(body.rows, enrollmentSelect.value);
                } catch (e) {
                    console.error('Bulk preview error:', e);
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์ กรุณาลองใหม่');
                } finally {
                    generateBtn.disabled = false;
                    generateBtn.innerHTML = '<i class="bi bi-eye"></i> สร้างตัวอย่างและตรวจสอบ';
                }
            });
            let previewRows = [];

            function teacherOptions(selectedId) {
                return `<option value="">ให้โรงเรียนจัดให้</option>` + teachersList.map(t =>
                    `<option value="${t.id}" ${t.id == selectedId ? 'selected' : ''}>${t.name}</option>`).join('');
            }

            function roomOptions(selectedId) {
                return `<option value="">ไม่ระบุ</option>` + roomsList.map(r =>
                    `<option value="${r.id}" ${r.id == selectedId ? 'selected' : ''}>${r.name}</option>`).join('');
            }

            function deliveryOptions(selected) {
                return ['onsite', 'online', 'hybrid'].map(v => {
                    const labels = {
                        onsite: 'ที่โรงเรียน',
                        online: 'ออนไลน์',
                        hybrid: 'ไฮบริด'
                    };
                    return `<option value="${v}" ${v === selected ? 'selected' : ''}>${labels[v]}</option>`;
                }).join('');
            }

            function renderPreview(rows, enrollmentId) {
                previewRows = rows.map((r, i) => ({
                    ...r,
                    _key: i
                }));
                document.getElementById('confirmEnrollmentId').value = enrollmentId;
                document.getElementById('step1').classList.add('d-none');
                document.getElementById('step2').classList.remove('d-none');
                document.getElementById('stepDot1').classList.replace('active', 'done');
                document.getElementById('stepDot2').classList.add('active');
                renderTable();
            }

            function renderTable() {
                const tbody = document.getElementById('previewTbody');
                tbody.innerHTML = '';
                if (previewRows.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="8" class="text-center text-muted py-3">ไม่มีคาบเรียนที่สร้างได้ในเงื่อนไขนี้</td></tr>';
                    updateSummary();
                    return;
                }
                previewRows.forEach((row, idx) => {
                    const hasConflict = row.conflicts && row.conflicts.length > 0;
                    const tr = document.createElement('tr');
                    tr.className = 'preview-row' + (hasConflict ? ' has-conflict' : '');
                    tr.dataset.key = row._key;
                    tr.innerHTML = `
                <td class="row-number">${idx + 1}</td>
                <td><input type="date" class="preview-input f-date" value="${row.date}"></td>
                <td><input type="time" class="preview-input f-start" value="${row.start_time}"></td>
                <td><input type="time" class="preview-input f-end" value="${row.end_time}"></td>
                <td><select class="preview-input f-teacher">${teacherOptions(row.teacher_id)}</select></td>
                <td><select class="preview-input f-room">${roomOptions(row.room_id)}</select></td>
                <td><select class="preview-input f-delivery">${deliveryOptions(row.delivery_mode)}</select></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger btn-remove-row"><i class="bi bi-trash"></i></button></td>
            `;
                    if (hasConflict) {
                        const msgTr = document.createElement('tr');
                        msgTr.innerHTML =
                            `<td></td><td colspan="7" class="row-conflict-msg" style="padding-top:0;">${row.conflicts.join(' / ')}</td>`;
                        tbody.appendChild(tr);
                        tbody.appendChild(msgTr);
                    } else {
                        tbody.appendChild(tr);
                    }
                    tr.querySelector('.btn-remove-row').addEventListener('click', () => {
                        previewRows = previewRows.filter(r => r._key !== row._key);
                        renderTable();
                    });
                    ['f-date', 'f-start', 'f-end', 'f-teacher', 'f-room'].forEach(cls => {
                        tr.querySelector('.' + cls).addEventListener('change', () => recheckRow(row
                            ._key, tr));
                    });
                    tr.querySelector('.f-delivery').addEventListener('change', function() {
                        previewRows = previewRows.map(r => r._key === row._key ? {
                            ...r,
                            delivery_mode: this.value
                        } : r);
                    });
                });
                updateSummary();
            }
            async function recheckRow(key, tr) {
                const date = tr.querySelector('.f-date').value;
                const start = tr.querySelector('.f-start').value;
                const end = tr.querySelector('.f-end').value;
                const teacherId = tr.querySelector('.f-teacher').value;
                const roomId = tr.querySelector('.f-room').value;
                const params = new URLSearchParams({
                    enrollment_id: document.getElementById('confirmEnrollmentId').value,
                    teacher_id: teacherId || '',
                    room_id: roomId || '',
                    date,
                    start_time: start,
                    end_time: end,
                });
                try {
                    const res = await fetch(
                        `{{ route('schedules.bulk-row-check-conflict') }}?${params.toString()}`);
                    const data = await res.json();
                    previewRows = previewRows.map(r => r._key === key ? {
                            ...r,
                            date,
                            start_time: start,
                            end_time: end,
                            teacher_id: teacherId || null,
                            room_id: roomId || null,
                            conflicts: data.conflicts || []
                        } :
                        r);
                    renderTable();
                } catch (e) {
                    console.error(e);
                }
            }

            function updateSummary() {
                const total = previewRows.length;
                const conflictCount = previewRows.filter(r => r.conflicts && r.conflicts.length > 0).length;
                document.getElementById('summaryTotal').textContent = total;
                document.getElementById('summaryOk').textContent = total - conflictCount;
                document.getElementById('summaryConflict').textContent = conflictCount;
                document.getElementById('confirmBtn').disabled = total === 0;
            }
            document.getElementById('backToStep1').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('step2').classList.add('d-none');
                document.getElementById('step1').classList.remove('d-none');
                document.getElementById('stepDot1').classList.replace('done', 'active');
                document.getElementById('stepDot2').classList.remove('active');
            });
            document.getElementById('confirmForm').addEventListener('submit', function(e) {
                if (previewRows.length === 0) {
                    e.preventDefault();
                    alert('ไม่มีคาบเรียนให้บันทึก');
                    return;
                }
                document.getElementById('confirmNotes').value = document.getElementById('notesInput').value;
                const container = document.getElementById('confirmRowsContainer');
                container.innerHTML = '';
                previewRows.forEach((row, i) => {
                    container.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="rows[${i}][date]" value="${row.date}">
                <input type="hidden" name="rows[${i}][start_time]" value="${row.start_time}">
                <input type="hidden" name="rows[${i}][end_time]" value="${row.end_time}">
                <input type="hidden" name="rows[${i}][teacher_id]" value="${row.teacher_id ?? ''}">
                <input type="hidden" name="rows[${i}][room_id]" value="${row.room_id ?? ''}">
                <input type="hidden" name="rows[${i}][delivery_mode]" value="${row.delivery_mode}">
            `);
                });
                const btn = document.getElementById('confirmBtn');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...';
            });
        })();
    </script>
@endsection

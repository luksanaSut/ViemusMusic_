@extends('layouts.app')
@section('title', 'ขอเปลี่ยนแปลงตารางเรียน')

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

        .pill-options {
            display: flex;
            gap: .6rem;
            flex-wrap: wrap;
        }

        .pill-option {
            border: 1.5px solid var(--border, #e4e1dc);
            border-radius: 10px;
            padding: .6rem 1.1rem;
            cursor: pointer;
            font-size: .85rem;
            font-weight: 600;
            transition: .15s;
        }

        .pill-option:hover {
            border-color: #c9c4bb;
        }

        .pill-option.active {
            border-color: var(--accent, #1f3350);
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent-dark, #13233a);
        }

        .schedule-preview {
            background: #faf9f7;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 12px;
            padding: 1rem;
            margin-top: .6rem;
            font-size: .85rem;
            display: none;
        }

        .schedule-preview.show {
            display: block;
        }

        .conflict-box {
            background: #fbeae7;
            color: #b3392c;
            border-radius: 12px;
            padding: .85rem 1rem;
            font-size: .85rem;
            margin-top: 1rem;
            display: none;
        }

        .conflict-box.show {
            display: block;
        }
    </style>

    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> สลับคลาส <i
            class="bi bi-chevron-right small"></i> ขอเปลี่ยนแปลง</div>
    <h1 class="page-title mb-3"><i class="bi bi-arrow-left-right"></i> ขอเปลี่ยนแปลงตารางเรียน</h1>

    <form action="{{ route('reschedule-requests.store') }}" method="POST" id="rescheduleForm">
        @csrf

        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-list-check"></i></div> เลือกประเภทการเปลี่ยนแปลง
            </div>
            <div class="pill-options" id="typePills">
                <div class="pill-option" data-value="change"><i class="bi bi-pencil"></i> เปลี่ยนอาจารย์/ห้อง/วันเวลา</div>
                <div class="pill-option" data-value="swap"><i class="bi bi-arrow-left-right"></i> แลกคาบกับอีกคาบหนึ่ง</div>
            </div>
            <input type="hidden" name="type" id="typeInput" value="change">
        </div>

        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-calendar-event"></i></div> เลือกคาบเรียนที่ต้องการเปลี่ยน
            </div>
            <select name="class_schedule_id" id="scheduleSelect" class="form-select" required>
                <option value="">เลือกคาบเรียน...</option>
                @foreach ($schedules as $s)
                    <option value="{{ $s->id }}" data-teacher-id="{{ $s->teacher_id }}"
                        data-teacher-name="{{ $s->teacher->full_name ?? '-' }}"
                        data-room-name="{{ $s->room->name ?? 'ออนไลน์' }}" {{ $preselectedId == $s->id ? 'selected' : '' }}>
                        {{ $s->schedule_date->format('d/m/Y') }} {{ $s->start_time }}-{{ $s->end_time }} —
                        {{ $s->enrollment->student->full_name ?? '-' }} ({{ $s->enrollment->course->name ?? '-' }})
                        · อ.{{ $s->teacher->nickname ?? ($s->teacher->full_name ?? '-') }}
                    </option>
                @endforeach
            </select>
            @if ($schedules->isEmpty())
                <div class="alert alert-warning small mt-2 mb-0">ไม่พบคาบเรียนที่จะขอเปลี่ยนแปลงได้</div>
            @endif
            <div id="currentPreview" class="schedule-preview"></div>
        </div>

        {{-- แบบเปลี่ยนแปลง --}}
        <div class="form-section" id="changeFieldsBox">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-sliders"></i></div> ค่าใหม่ที่ต้องการเปลี่ยน
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">อาจารย์ใหม่ (ไม่ระบุ = คงเดิม)</label>
                    <select name="new_teacher_id" class="form-select">
                        <option value="">คงอาจารย์เดิม</option>
                        @foreach ($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->nickname ?: $t->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ห้องเรียนใหม่</label>
                    <select name="new_room_id" class="form-select">
                        <option value="">ไม่ระบุ (ออนไลน์)</option>
                        @foreach ($rooms as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <label class="form-label">วันที่ใหม่ *</label>
                    <input type="date" name="new_date" id="newDateInput" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">เวลาเริ่ม *</label>
                    <input type="time" name="new_start_time" id="newStartInput" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">เวลาสิ้นสุด *</label>
                    <input type="time" name="new_end_time" id="newEndInput" class="form-control">
                </div>
            </div>
            <div id="conflictBox" class="conflict-box"></div>
        </div>

        {{-- แบบแลกคาบ --}}
        <div class="form-section d-none" id="swapFieldsBox">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-arrow-left-right"></i></div> เลือกคาบที่จะแลกด้วย
            </div>
            <select name="swap_with_class_schedule_id" id="swapSelect" class="form-select">
                <option value="">เลือกคาบเรียนที่จะแลก...</option>
                @foreach ($schedules as $s)
                    <option value="{{ $s->id }}">
                        {{ $s->schedule_date->format('d/m/Y') }} {{ $s->start_time }}-{{ $s->end_time }} —
                        {{ $s->enrollment->student->full_name ?? '-' }} ({{ $s->enrollment->course->name ?? '-' }})
                        · อ.{{ $s->teacher->nickname ?? ($s->teacher->full_name ?? '-') }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted d-block mt-2"><i class="bi bi-info-circle"></i> เมื่ออนุมัติแล้ว ทั้งวันเวลา อาจารย์
                และห้องเรียนของทั้ง 2 คาบ จะสลับกันทั้งหมด</small>
        </div>

        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-journal-text"></i></div> เหตุผล
            </div>
            <textarea name="reason" class="form-control" rows="2" maxlength="500"
                placeholder="เช่น อาจารย์ติดธุระ, นักเรียนขอเปลี่ยนเวลา ฯลฯ"></textarea>
        </div>

        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent" id="submitBtn"><i class="bi bi-send"></i> ส่งคำขอ</button>
            <a href="{{ route('reschedule-requests.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>

    <script>
        (function() {
            const typePills = document.getElementById('typePills');
            const typeInput = document.getElementById('typeInput');
            const changeBox = document.getElementById('changeFieldsBox');
            const swapBox = document.getElementById('swapFieldsBox');
            const scheduleSelect = document.getElementById('scheduleSelect');
            const currentPreview = document.getElementById('currentPreview');
            const newDate = document.getElementById('newDateInput');
            const newStart = document.getElementById('newStartInput');
            const newEnd = document.getElementById('newEndInput');
            const newTeacher = document.querySelector('[name=new_teacher_id]');
            const newRoom = document.querySelector('[name=new_room_id]');
            const conflictBox = document.getElementById('conflictBox');
            const submitBtn = document.getElementById('submitBtn');

            typePills.querySelectorAll('.pill-option').forEach(opt => {
                opt.addEventListener('click', () => {
                    typePills.querySelectorAll('.pill-option').forEach(o => o.classList.remove(
                        'active'));
                    opt.classList.add('active');
                    typeInput.value = opt.dataset.value;
                    changeBox.classList.toggle('d-none', opt.dataset.value !== 'change');
                    swapBox.classList.toggle('d-none', opt.dataset.value !== 'swap');
                    [newDate, newStart, newEnd].forEach(el => el.required = opt.dataset.value ===
                        'change');
                });
            });
            typePills.querySelector('[data-value=change]').click();

            function updatePreview() {
                const opt = scheduleSelect.options[scheduleSelect.selectedIndex];
                if (!opt || !opt.value) {
                    currentPreview.classList.remove('show');
                    return;
                }
                currentPreview.innerHTML =
                    `<i class="bi bi-info-circle"></i> ปัจจุบัน: อาจารย์ <strong>${opt.dataset.teacherName}</strong> · ห้อง <strong>${opt.dataset.roomName}</strong>`;
                currentPreview.classList.add('show');
            }
            scheduleSelect.addEventListener('change', () => {
                updatePreview();
                checkConflict();
            });
            updatePreview();

            async function checkConflict() {
                    if (typeInput.value !== 'change') {
                        conflictBox.classList.remove('show');
                        submitBtn.disabled = false;
                        return;
                    }
                    if (!scheduleSelect.value || !newDate.value || !newStart.value || !newEnd.value) return;

                    const params = new URLSearchParams({
                        class_schedule_id: scheduleSelect.value,
                        teacher_id: newTeacher.value || '',
                        room_id: newRoom.value || '',
                        date: newDate.value,
                        start_time: newStart.value,
                        end_time: newEnd.value,
                    });
                    try {
                        const res = await fetch(
                            `{{ route('reschedule-requests.check-conflict') }}?${params.toString()}`);
                        const data = await res.json();
                        if (data.conflicts && data.conflicts.length > 0) {
                            conflictBox.innerHTML = '<i class="bi bi-exclamation-triangle"></i> ' + data.conflicts.join(
                                '<br>');
                            conflictBox.classList.add('show');
                            submitBtn.disabled = true;
                        } else {
                            conflictBox.classList.remove('show');
                            submitBtn.disabled = false;
                        }
                    } catch (e) {
                        console.error(e);
                    }
                }
                [newTeacher, newRoom, newDate, newStart, newEnd].forEach(el => el.addEventListener('change',
                    checkConflict));

            document.getElementById('rescheduleForm').addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังส่ง...';
            });
        })();
    </script>
@endsection

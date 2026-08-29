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
            padding: 1rem 1.1rem;
            cursor: pointer;
            font-size: .85rem;
            font-weight: 600;
            transition: .15s;
            flex: 1;
            min-width: 240px;
            background: var(--card, #fff);
        }

        .pill-option i { font-size:1.15rem; margin-right:.35rem; }
        .pill-option .option-title { font-family:'Prompt',sans-serif; font-weight:700; }
        .pill-option .option-help { display:block; margin:.3rem 0 0 1.65rem; color:var(--muted,#6b655e); font-size:.74rem; font-weight:400; }

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

        .step-badge { width:28px; height:28px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; background:var(--accent,#1f3350); color:#fff; font-size:.78rem; flex-shrink:0; }
        .page-head { display:flex; justify-content:space-between; align-items:flex-end; gap:1rem; margin-bottom:1.1rem; }
        .request-summary { border:1px solid var(--border,#e4e1dc); border-radius:14px; padding:1rem; background:linear-gradient(135deg,var(--accent-soft,#e7ebf1),#fff); display:none; }
        .request-summary.show { display:block; }
        .summary-grid { display:grid; grid-template-columns:1fr auto 1fr; gap:1rem; align-items:center; }
        .summary-side { min-width:0; }
        .summary-label { color:var(--muted,#6b655e); font-size:.72rem; margin-bottom:.25rem; }
        .summary-value { font-size:.84rem; font-weight:600; }
        .summary-arrow { width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; background:#fff; color:var(--accent,#1f3350); }
        .check-status { display:none; align-items:center; gap:.45rem; margin-top:.75rem; font-size:.8rem; }
        .check-status.show { display:flex; }
        .action-bar { position:sticky; bottom:0; z-index:6; display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem; margin:0 0 1rem; border:1px solid var(--border,#e4e1dc); border-radius:14px; background:rgba(255,255,255,.96); box-shadow:0 -6px 22px rgba(28,26,23,.08); backdrop-filter:blur(8px); }
        .error-summary { border:1px solid #efc4be; border-radius:12px; background:#fbeae7; color:#8f2d24; padding:.9rem 1rem; margin-bottom:1rem; }
        .schedule-search { position:relative; margin-bottom:.65rem; }
        .schedule-search > i { position:absolute; left:.85rem; top:50%; transform:translateY(-50%); color:var(--muted,#6b655e); pointer-events:none; }
        .schedule-search .form-control { padding-left:2.35rem; padding-right:2.4rem; }
        .schedule-search .clear-search { position:absolute; right:.45rem; top:50%; transform:translateY(-50%); border:0; background:transparent; color:var(--muted,#6b655e); padding:.35rem .5rem; display:none; }
        .search-result-hint { font-size:.74rem; color:var(--muted,#6b655e); margin-bottom:.45rem; }
        .schedule-picker { display:flex; flex-direction:column; gap:.5rem; max-height:390px; overflow-y:auto; padding:.15rem .25rem .15rem 0; }
        .schedule-choice { width:100%; display:grid; grid-template-columns:82px minmax(0,1fr) auto; align-items:center; gap:.85rem; border:1px solid var(--border,#e4e1dc); border-radius:12px; padding:.7rem .8rem; background:#fff; color:inherit; text-align:left; transition:.15s; }
        .schedule-choice:hover { border-color:#b8b1a8; background:var(--surface,#f7f6f4); }
        .schedule-choice.active { border:2px solid var(--accent,#1f3350); background:var(--accent-soft,#e7ebf1); }
        .schedule-choice .choice-date { text-align:center; border-right:1px solid var(--border,#e4e1dc); padding-right:.75rem; }
        .schedule-choice .choice-date strong { display:block; font-family:'Prompt',sans-serif; font-size:.85rem; }
        .schedule-choice .choice-date span { font-size:.72rem; color:var(--muted,#6b655e); }
        .schedule-choice .choice-student { font-weight:700; font-size:.86rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .schedule-choice .choice-meta { color:var(--muted,#6b655e); font-size:.72rem; margin-top:.18rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .schedule-choice .choice-check { width:28px; height:28px; border:1px solid var(--border,#e4e1dc); border-radius:50%; display:flex; align-items:center; justify-content:center; color:transparent; background:#fff; }
        .schedule-choice.active .choice-check { background:var(--accent,#1f3350); border-color:var(--accent,#1f3350); color:#fff; }
        .schedule-picker-empty { display:none; text-align:center; padding:2rem 1rem; color:var(--muted,#6b655e); border:1px dashed var(--border,#e4e1dc); border-radius:12px; }

        @media(max-width:767.98px){.form-section{padding:1rem}.page-head{align-items:flex-start;flex-direction:column}.pill-option{min-width:100%}.summary-grid{grid-template-columns:1fr}.summary-arrow{transform:rotate(90deg);margin:auto}.action-bar{margin-inline:-.25rem}.action-bar .btn{flex:1}.action-copy{display:none}.schedule-choice{grid-template-columns:68px minmax(0,1fr) auto;padding:.65rem}.schedule-picker{max-height:460px}}
    </style>

    @php $requestsIndexRoute = auth()->user()->isTeacher() ? route('reschedule-requests.my-index') : route('reschedule-requests.index'); @endphp
    <div class="page-head"><div><div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> สลับคลาส <i class="bi bi-chevron-right small"></i> ขอเปลี่ยนแปลง</div><h1 class="page-title mb-1"><i class="bi bi-arrow-left-right"></i> ขอเปลี่ยนแปลงตารางเรียน</h1><p class="text-muted small mb-0">เลือกคาบและระบุสิ่งที่ต้องการเปลี่ยน ระบบจะตรวจตารางชนก่อนส่งคำขอ</p></div><a href="{{ $requestsIndexRoute }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> กลับหน้าคำขอ</a></div>

    @if($errors->any())<div class="error-summary"><strong><i class="bi bi-exclamation-circle"></i> กรุณาตรวจสอบข้อมูล</strong><ul class="small mb-0 mt-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <form action="{{ route('reschedule-requests.store') }}" method="POST" id="rescheduleForm">
        @csrf

        <div class="form-section">
            <div class="form-section-title">
                <span class="step-badge">1</span> เลือกประเภทการเปลี่ยนแปลง
            </div>
            <div class="pill-options" id="typePills">
                <div class="pill-option" data-value="change" role="button" tabindex="0"><i class="bi bi-pencil-square"></i><span class="option-title">เปลี่ยนรายละเอียดคาบ</span><span class="option-help">เปลี่ยนอาจารย์ ห้องเรียน วัน หรือเวลา</span></div>
                <div class="pill-option" data-value="swap" role="button" tabindex="0"><i class="bi bi-arrow-left-right"></i><span class="option-title">แลกกับอีกคาบ</span><span class="option-help">สลับวัน เวลา อาจารย์ และห้องของสองคาบ</span></div>
            </div>
            <input type="hidden" name="type" id="typeInput" value="change">
        </div>

        <div class="form-section">
            <div class="form-section-title">
                <span class="step-badge">2</span> เลือกคาบเรียนที่ต้องการเปลี่ยน
            </div>
            <div class="schedule-search">
                <i class="bi bi-search"></i>
                <input type="search" id="scheduleSearch" class="form-control"
                    placeholder="ค้นหาวันที่ นักเรียน คอร์ส อาจารย์ หรือห้อง" autocomplete="off">
                <button type="button" id="clearScheduleSearch" class="clear-search" aria-label="ล้างคำค้นหา"><i class="bi bi-x-lg"></i></button>
            </div>
            <div id="scheduleSearchHint" class="search-result-hint">มีคาบให้เลือก {{ $schedules->count() }} รายการ</div>
            <select name="class_schedule_id" id="scheduleSelect" class="d-none" aria-hidden="true">
                <option value="">เลือกคาบเรียน...</option>
                @foreach ($schedules as $s)
                    <option value="{{ $s->id }}" data-teacher-id="{{ $s->teacher_id }}"
                        data-teacher-name="{{ $s->teacher->full_name ?? '-' }}"
                        data-room-name="{{ $s->room->name ?? 'ออนไลน์' }}" data-date="{{ $s->schedule_date->format('d/m/Y') }}" data-time="{{ substr($s->start_time,0,5) }}–{{ substr($s->end_time,0,5) }}" data-student="{{ $s->enrollment->student->full_name ?? '-' }}" data-course="{{ $s->enrollment->course->name ?? '-' }}" {{ old('class_schedule_id',$preselectedId) == $s->id ? 'selected' : '' }}>
                        {{ $s->schedule_date->format('d/m/Y') }} {{ $s->start_time }}-{{ $s->end_time }} —
                        {{ $s->enrollment->student->full_name ?? '-' }} ({{ $s->enrollment->course->name ?? '-' }})
                        · อ.{{ $s->teacher->nickname ?? ($s->teacher->full_name ?? '-') }}
                    </option>
                @endforeach
            </select>
            <div id="schedulePicker" class="schedule-picker">
                @foreach ($schedules as $s)
                    <button type="button" class="schedule-choice {{ old('class_schedule_id',$preselectedId) == $s->id ? 'active' : '' }}" data-schedule-id="{{ $s->id }}" data-search="{{ mb_strtolower($s->schedule_date->format('d/m/Y').' '.$s->start_time.' '.$s->end_time.' '.($s->enrollment->student->full_name??'').' '.($s->enrollment->course->name??'').' '.($s->teacher->full_name??'').' '.($s->teacher->nickname??'').' '.($s->room->name??'ออนไลน์')) }}" aria-pressed="{{ old('class_schedule_id',$preselectedId) == $s->id ? 'true' : 'false' }}">
                        <span class="choice-date"><strong>{{ $s->schedule_date->format('d/m') }}</strong><span>{{ substr($s->start_time,0,5) }}–{{ substr($s->end_time,0,5) }}</span></span>
                        <span class="min-width-0"><span class="choice-student d-block">{{ $s->enrollment->student->full_name??'-' }}</span><span class="choice-meta d-block">{{ $s->enrollment->course->name??'-' }} · อ.{{ $s->teacher->nickname??($s->teacher->full_name??'-') }} · {{ $s->room->name??'ออนไลน์' }}</span></span>
                        <span class="choice-check"><i class="bi bi-check-lg"></i></span>
                    </button>
                @endforeach
            </div>
            <div id="schedulePickerEmpty" class="schedule-picker-empty"><i class="bi bi-search fs-4 d-block mb-1"></i>ไม่พบคาบที่ตรงกับคำค้นหา</div>
            <div id="scheduleRequiredError" class="text-danger small mt-2 d-none"><i class="bi bi-exclamation-circle"></i> กรุณาเลือกคาบเรียนที่ต้องการเปลี่ยน</div>
            @if ($schedules->isEmpty())
                <div class="alert alert-warning small mt-2 mb-0">ไม่พบคาบเรียนที่จะขอเปลี่ยนแปลงได้</div>
            @endif
            <div id="currentPreview" class="schedule-preview"></div>
        </div>

        {{-- แบบเปลี่ยนแปลง --}}
        <div class="form-section" id="changeFieldsBox">
            <div class="form-section-title">
                <span class="step-badge">3</span> รายละเอียดใหม่ที่ต้องการ
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
            <div id="checkingStatus" class="check-status text-muted"><span class="spinner-border spinner-border-sm"></span> กำลังตรวจสอบตาราง...</div><div id="availableStatus" class="check-status text-success"><i class="bi bi-check-circle-fill"></i> วันและเวลานี้ไม่พบตารางชน</div><div id="conflictBox" class="conflict-box"></div>
        </div>

        {{-- แบบแลกคาบ --}}
        <div class="form-section d-none" id="swapFieldsBox">
            <div class="form-section-title">
                <span class="step-badge">3</span> เลือกคาบที่จะแลกด้วย
            </div>
            <div class="schedule-search">
                <i class="bi bi-search"></i>
                <input type="search" id="swapScheduleSearch" class="form-control"
                    placeholder="ค้นหาคาบที่จะแลกจากวันที่ นักเรียน คอร์ส อาจารย์ หรือห้อง" autocomplete="off">
                <button type="button" id="clearSwapScheduleSearch" class="clear-search" aria-label="ล้างคำค้นหา"><i class="bi bi-x-lg"></i></button>
            </div>
            <div id="swapScheduleSearchHint" class="search-result-hint">มีคาบให้เลือก {{ max(0, $schedules->count() - ($preselectedId ? 1 : 0)) }} รายการ</div>
            <select name="swap_with_class_schedule_id" id="swapSelect" class="d-none" aria-hidden="true">
                <option value="">เลือกคาบเรียนที่จะแลก...</option>
                @foreach ($schedules as $s)
                    <option value="{{ $s->id }}" {{ old('swap_with_class_schedule_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->schedule_date->format('d/m/Y') }} {{ $s->start_time }}-{{ $s->end_time }} —
                        {{ $s->enrollment->student->full_name ?? '-' }} ({{ $s->enrollment->course->name ?? '-' }})
                        · อ.{{ $s->teacher->nickname ?? ($s->teacher->full_name ?? '-') }}
                    </option>
                @endforeach
            </select>
            <div id="swapSchedulePicker" class="schedule-picker">
                @foreach ($schedules as $s)
                    <button type="button" class="schedule-choice swap-schedule-choice {{ old('swap_with_class_schedule_id') == $s->id ? 'active' : '' }}" data-schedule-id="{{ $s->id }}" data-search="{{ mb_strtolower($s->schedule_date->format('d/m/Y').' '.$s->start_time.' '.$s->end_time.' '.($s->enrollment->student->full_name??'').' '.($s->enrollment->course->name??'').' '.($s->teacher->full_name??'').' '.($s->teacher->nickname??'').' '.($s->room->name??'ออนไลน์')) }}" aria-pressed="{{ old('swap_with_class_schedule_id') == $s->id ? 'true' : 'false' }}">
                        <span class="choice-date"><strong>{{ $s->schedule_date->format('d/m') }}</strong><span>{{ substr($s->start_time,0,5) }}–{{ substr($s->end_time,0,5) }}</span></span>
                        <span class="min-width-0"><span class="choice-student d-block">{{ $s->enrollment->student->full_name??'-' }}</span><span class="choice-meta d-block">{{ $s->enrollment->course->name??'-' }} · อ.{{ $s->teacher->nickname??($s->teacher->full_name??'-') }} · {{ $s->room->name??'ออนไลน์' }}</span></span>
                        <span class="choice-check"><i class="bi bi-check-lg"></i></span>
                    </button>
                @endforeach
            </div>
            <div id="swapSchedulePickerEmpty" class="schedule-picker-empty"><i class="bi bi-search fs-4 d-block mb-1"></i>ไม่พบคาบที่สามารถเลือกแลกได้</div>
            <div id="swapRequiredError" class="text-danger small mt-2 d-none"><i class="bi bi-exclamation-circle"></i> กรุณาเลือกคาบที่จะแลกด้วย</div>
            <small class="text-muted d-block mt-2"><i class="bi bi-info-circle"></i> เมื่ออนุมัติแล้ว ทั้งวันเวลา อาจารย์
                และห้องเรียนของทั้ง 2 คาบ จะสลับกันทั้งหมด</small>
        </div>

        <div class="form-section">
            <div class="form-section-title">
                <span class="step-badge">4</span> เหตุผลประกอบคำขอ
            </div>
            <textarea name="reason" class="form-control" rows="2" maxlength="500"
                placeholder="เช่น อาจารย์ติดธุระ, นักเรียนขอเปลี่ยนเวลา ฯลฯ"></textarea>
        </div>

        <div id="requestSummary" class="request-summary mb-3"><div class="fw-bold mb-3" style="font-family:'Prompt',sans-serif"><i class="bi bi-eye"></i> ตรวจสอบก่อนส่ง</div><div class="summary-grid"><div class="summary-side"><div class="summary-label">คาบปัจจุบัน</div><div class="summary-value" id="summaryBefore">-</div></div><div class="summary-arrow"><i class="bi bi-arrow-right"></i></div><div class="summary-side"><div class="summary-label">สิ่งที่ขอเปลี่ยน</div><div class="summary-value" id="summaryAfter">-</div></div></div></div>
        <div class="action-bar"><div class="action-copy"><strong>ตรวจสอบข้อมูลครบแล้วหรือยัง?</strong><div class="small text-muted">คำขอของอาจารย์จะถูกส่งให้ Admin อนุมัติ</div></div><div class="d-flex gap-2"><a href="{{ $requestsIndexRoute }}" class="btn btn-outline-secondary">ยกเลิก</a><button class="btn btn-accent" id="submitBtn"><i class="bi bi-send"></i> ส่งคำขอ</button></div></div>
    </form>

    <script>
        (function() {
            const typePills = document.getElementById('typePills');
            const typeInput = document.getElementById('typeInput');
            const changeBox = document.getElementById('changeFieldsBox');
            const swapBox = document.getElementById('swapFieldsBox');
            const scheduleSelect = document.getElementById('scheduleSelect');
            const scheduleSearch = document.getElementById('scheduleSearch');
            const clearScheduleSearch = document.getElementById('clearScheduleSearch');
            const scheduleSearchHint = document.getElementById('scheduleSearchHint');
            const scheduleChoices = [...document.querySelectorAll('#schedulePicker .schedule-choice')];
            const schedulePickerEmpty = document.getElementById('schedulePickerEmpty');
            const scheduleRequiredError = document.getElementById('scheduleRequiredError');
            const currentPreview = document.getElementById('currentPreview');
            const newDate = document.getElementById('newDateInput');
            const newStart = document.getElementById('newStartInput');
            const newEnd = document.getElementById('newEndInput');
            const newTeacher = document.querySelector('[name=new_teacher_id]');
            const newRoom = document.querySelector('[name=new_room_id]');
            const conflictBox = document.getElementById('conflictBox');
            const submitBtn = document.getElementById('submitBtn');
            const checkingStatus = document.getElementById('checkingStatus');
            const availableStatus = document.getElementById('availableStatus');
            const requestSummary = document.getElementById('requestSummary');
            const summaryBefore = document.getElementById('summaryBefore');
            const summaryAfter = document.getElementById('summaryAfter');
            const swapSelect = document.getElementById('swapSelect');
            const swapScheduleSearch = document.getElementById('swapScheduleSearch');
            const clearSwapScheduleSearch = document.getElementById('clearSwapScheduleSearch');
            const swapScheduleSearchHint = document.getElementById('swapScheduleSearchHint');
            const swapChoices = [...document.querySelectorAll('#swapSchedulePicker .swap-schedule-choice')];
            const swapSchedulePickerEmpty = document.getElementById('swapSchedulePickerEmpty');
            const swapRequiredError = document.getElementById('swapRequiredError');

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
                    updateSummary();
                });
                opt.addEventListener('keydown', event => {
                    if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); opt.click(); }
                });
            });
            typePills.querySelector('[data-value=change]').click();

            function filterSchedules() {
                const term = scheduleSearch.value.trim().toLocaleLowerCase('th');
                let visible = 0;
                scheduleChoices.forEach(choice => {
                    const matches = !term || choice.dataset.search.includes(term);
                    choice.classList.toggle('d-none', !matches);
                    if (matches) visible++;
                });
                clearScheduleSearch.style.display = term ? 'block' : 'none';
                scheduleSearchHint.textContent = term
                    ? `พบ ${visible} คาบที่ตรงกับ “${scheduleSearch.value.trim()}”`
                    : `มีคาบให้เลือก ${visible} รายการ`;
                scheduleSelect.classList.toggle('is-invalid', !!term && visible === 0);
                schedulePickerEmpty.style.display = visible === 0 ? 'block' : 'none';
            }

            scheduleChoices.forEach(choice => choice.addEventListener('click', () => {
                scheduleChoices.forEach(item => { item.classList.remove('active'); item.setAttribute('aria-pressed','false'); });
                choice.classList.add('active');
                choice.setAttribute('aria-pressed','true');
                scheduleSelect.value = choice.dataset.scheduleId;
                scheduleRequiredError.classList.add('d-none');
                scheduleSelect.dispatchEvent(new Event('change'));
                currentPreview.scrollIntoView({ behavior:'smooth', block:'nearest' });
            }));

            scheduleSearch.addEventListener('input', filterSchedules);
            clearScheduleSearch.addEventListener('click', () => {
                scheduleSearch.value = '';
                filterSchedules();
                scheduleSearch.focus();
            });
            filterSchedules();

            function filterSwapSchedules() {
                const term = swapScheduleSearch.value.trim().toLocaleLowerCase('th');
                let visible = 0;
                swapChoices.forEach(choice => {
                    const isSameClass = choice.dataset.scheduleId === scheduleSelect.value;
                    const matches = !isSameClass && (!term || choice.dataset.search.includes(term));
                    choice.classList.toggle('d-none', !matches);
                    if (matches) visible++;
                });
                clearSwapScheduleSearch.style.display = term ? 'block' : 'none';
                swapScheduleSearchHint.textContent = term
                    ? `พบ ${visible} คาบที่ตรงกับ “${swapScheduleSearch.value.trim()}”`
                    : `มีคาบให้เลือก ${visible} รายการ`;
                swapSchedulePickerEmpty.style.display = visible === 0 ? 'block' : 'none';
                if (swapSelect.value === scheduleSelect.value) {
                    swapSelect.value = '';
                    swapChoices.forEach(item => { item.classList.remove('active'); item.setAttribute('aria-pressed','false'); });
                    updateSummary();
                }
            }

            swapChoices.forEach(choice => choice.addEventListener('click', () => {
                swapChoices.forEach(item => { item.classList.remove('active'); item.setAttribute('aria-pressed','false'); });
                choice.classList.add('active');
                choice.setAttribute('aria-pressed','true');
                swapSelect.value = choice.dataset.scheduleId;
                swapRequiredError.classList.add('d-none');
                swapSelect.dispatchEvent(new Event('change'));
            }));
            swapScheduleSearch.addEventListener('input', filterSwapSchedules);
            clearSwapScheduleSearch.addEventListener('click', () => {
                swapScheduleSearch.value = '';
                filterSwapSchedules();
                swapScheduleSearch.focus();
            });
            filterSwapSchedules();

            function updatePreview() {
                const opt = scheduleSelect.options[scheduleSelect.selectedIndex];
                if (!opt || !opt.value) {
                    currentPreview.classList.remove('show');
                    return;
                }
                currentPreview.innerHTML =
                    `<div class="d-flex gap-2"><i class="bi bi-calendar-check text-muted"></i><div><strong>${opt.dataset.student}</strong> · ${opt.dataset.course}<div class="text-muted mt-1">${opt.dataset.date} · ${opt.dataset.time} · อาจารย์ ${opt.dataset.teacherName} · ${opt.dataset.roomName}</div></div></div>`;
                currentPreview.classList.add('show');
                updateSummary();
            }
            scheduleSelect.addEventListener('change', () => {
                updatePreview();
                filterSwapSchedules();
                checkConflict();
            });
            updatePreview();

            async function checkConflict() {
                    if (typeInput.value !== 'change') {
                        conflictBox.classList.remove('show');
                        checkingStatus.classList.remove('show');
                        availableStatus.classList.remove('show');
                        submitBtn.disabled = false;
                        return;
                    }
                    if (!scheduleSelect.value || !newDate.value || !newStart.value || !newEnd.value) { availableStatus.classList.remove('show'); updateSummary(); return; }

                    checkingStatus.classList.add('show');
                    availableStatus.classList.remove('show');
                    conflictBox.classList.remove('show');

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
                        if (!res.ok) throw new Error('ไม่สามารถตรวจสอบตารางได้');
                        const data = await res.json();
                        checkingStatus.classList.remove('show');
                        if (data.conflicts && data.conflicts.length > 0) {
                            conflictBox.innerHTML = '<i class="bi bi-exclamation-triangle"></i> ' + data.conflicts.join(
                                '<br>');
                            conflictBox.classList.add('show');
                            submitBtn.disabled = true;
                        } else {
                            conflictBox.classList.remove('show');
                            availableStatus.classList.add('show');
                            submitBtn.disabled = false;
                        }
                    } catch (e) {
                        checkingStatus.classList.remove('show');
                        console.error(e);
                    }
                    updateSummary();
                }
                [newTeacher, newRoom, newDate, newStart, newEnd].forEach(el => el.addEventListener('change',
                    checkConflict));
            swapSelect.addEventListener('change', updateSummary);

            function updateSummary() {
                const selected = scheduleSelect.options[scheduleSelect.selectedIndex];
                if (!selected?.value) { requestSummary.classList.remove('show'); return; }
                requestSummary.classList.add('show');
                summaryBefore.textContent = `${selected.dataset.date} ${selected.dataset.time} · ${selected.dataset.student} · ${selected.dataset.teacherName} · ${selected.dataset.roomName}`;
                if (typeInput.value === 'swap') {
                    const swap = swapSelect.options[swapSelect.selectedIndex];
                    summaryAfter.textContent = swap?.value ? `แลกกับ ${swap.textContent.trim()}` : 'กรุณาเลือกคาบที่จะแลก';
                } else {
                    const teacherName = newTeacher.options[newTeacher.selectedIndex]?.text || 'คงอาจารย์เดิม';
                    const roomName = newRoom.options[newRoom.selectedIndex]?.text || 'ไม่ระบุห้อง';
                    summaryAfter.textContent = newDate.value && newStart.value && newEnd.value
                        ? `${newDate.value} ${newStart.value}–${newEnd.value} · ${teacherName} · ${roomName}`
                        : 'กรุณาระบุวันและเวลาใหม่';
                }
            }
            [newTeacher, newRoom, newDate, newStart, newEnd].forEach(el => el.addEventListener('change', updateSummary));
            updateSummary();

            document.getElementById('rescheduleForm').addEventListener('submit', function(event) {
                if (!scheduleSelect.value) {
                    event.preventDefault();
                    scheduleRequiredError.classList.remove('d-none');
                    scheduleSearch.focus();
                    document.getElementById('schedulePicker').scrollIntoView({ behavior:'smooth', block:'center' });
                    return;
                }
                if (typeInput.value === 'swap' && !swapSelect.value) {
                    event.preventDefault();
                    swapRequiredError.classList.remove('d-none');
                    swapScheduleSearch.focus();
                    document.getElementById('swapSchedulePicker').scrollIntoView({ behavior:'smooth', block:'center' });
                    return;
                }
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังส่ง...';
            });
        })();
    </script>
@endsection

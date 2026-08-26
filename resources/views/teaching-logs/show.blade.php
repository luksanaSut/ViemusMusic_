@extends('layouts.app')
@section('title', 'เช็คชื่อเข้าเรียน')

@section('content')
    <style>
        .form-section {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.4rem 1.6rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 28px rgba(28, 26, 23, .05);
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

        .info-row {
            display: flex;
            gap: .6rem;
            padding: .4rem 0;
            border-bottom: 1px dashed var(--border, #e4e1dc);
        }

        .info-row .label {
            font-size: .75rem;
            color: var(--muted, #6b655e);
            min-width: 150px;
        }

        .status-pills {
            display: flex;
            gap: .6rem;
            flex-wrap: wrap;
        }

        .status-pill {
            border: 1.5px solid var(--border, #e4e1dc);
            border-radius: 12px;
            padding: .9rem 1.1rem;
            cursor: pointer;
            text-align: center;
            flex: 1;
            min-width: 110px;
            transition: .15s;
            background: var(--card, #fff);
            color: var(--ink, #1c1a17);
            position: relative;
        }

        .status-pill:hover {
            border-color: #c9c4bb;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(28, 26, 23, .07);
        }

        .status-pill:focus-visible,
        .duration-pill:focus-visible {
            outline: 3px solid rgba(31, 51, 80, .2);
            outline-offset: 2px;
        }

        .status-pill.active {
            border-width: 2px;
        }

        .status-pill.present.active {
            border-color: var(--success, #2f6f4e);
            background: var(--success-soft, #e7f2ec);
        }

        .status-pill.late.active {
            border-color: var(--amber, #8a5a2b);
            background: var(--amber-soft, #f3ece2);
        }

        .status-pill.absent.active {
            border-color: #b3392c;
            background: #fbeae7;
        }

        .duration-pills {
            display: flex;
            gap: .6rem;
            flex-wrap: wrap;
        }

        .duration-pill {
            border: 1.5px solid var(--border, #e4e1dc);
            border-radius: 12px;
            padding: .8rem 1.3rem;
            cursor: pointer;
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            transition: .15s;
            background: var(--card, #fff);
            color: var(--ink, #1c1a17);
        }

        .duration-pill:hover {
            border-color: #c9c4bb;
        }

        .duration-pill.active {
            border-color: var(--accent, #1f3350);
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent-dark, #13233a);
            border-width: 2px;
        }

        .locked-box {
            background: var(--success-soft, #e7f2ec);
            border-radius: 12px;
            padding: 1.2rem;
            text-align: center;
        }

        .attendance-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .session-hero {
            background: linear-gradient(135deg, var(--accent-dark, #13233a), var(--accent, #1f3350));
            color: #fff;
            border: 0;
            overflow: hidden;
            position: relative;
        }

        .session-hero::after {
            content: "\F4D7";
            font-family: bootstrap-icons;
            position: absolute;
            right: 1.2rem;
            bottom: -2.2rem;
            font-size: 8rem;
            opacity: .06;
        }

        .student-name {
            font-family: 'Prompt', sans-serif;
            font-size: clamp(1.25rem, 3vw, 1.75rem);
            font-weight: 700;
            line-height: 1.3;
        }

        .session-meta-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .7rem;
            margin-top: 1.1rem;
        }

        .session-meta-item {
            display: flex;
            align-items: center;
            gap: .55rem;
            padding: .7rem .8rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, .09);
            min-width: 0;
        }

        .session-meta-item i { font-size: 1.05rem; opacity: .8; }
        .session-meta-item .meta-label { font-size: .68rem; opacity: .68; }
        .session-meta-item .meta-value { font-size: .83rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .step-label {
            display: flex;
            align-items: center;
            gap: .65rem;
            margin-bottom: .75rem;
            font-family: 'Prompt', sans-serif;
            font-weight: 600;
        }

        .step-number {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent-dark, #13233a);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            flex-shrink: 0;
        }

        .selection-summary {
            border-radius: 12px;
            background: var(--surface, #f7f6f4);
            padding: .8rem 1rem;
            color: var(--muted, #6b655e);
            font-size: .84rem;
        }

        .submit-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding-top: 1rem;
            margin-top: 1rem;
            border-top: 1px solid var(--border, #e4e1dc);
        }

        .submit-bar .btn { min-height: 46px; padding-inline: 1.35rem; }

        .report-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(280px, .85fr);
            gap: 1rem;
        }

        .report-panel {
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 14px;
            padding: 1.1rem;
            background: var(--card, #fff);
        }

        .report-panel.homework-panel {
            border-color: #e5d8bf;
            background: linear-gradient(180deg, var(--amber-soft, #f3ece2), #fff 55%);
        }

        .report-panel-heading {
            display: flex;
            align-items: flex-start;
            gap: .7rem;
            margin-bottom: 1rem;
        }

        .report-panel-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent-dark, #13233a);
        }

        .homework-panel .report-panel-icon {
            background: #ead9bc;
            color: var(--amber, #8a5a2b);
        }

        .report-panel-title {
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: .95rem;
        }

        .report-panel-hint {
            color: var(--muted, #6b655e);
            font-size: .75rem;
            margin-top: .1rem;
        }

        .report-attachments {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed var(--border, #e4e1dc);
        }

        @media (max-width: 767.98px) {
            .form-section { padding: 1.1rem; border-radius: 14px; }
            .attendance-header { align-items: center; }
            .attendance-header .breadcrumb-sm { display: none; }
            .session-meta-grid { grid-template-columns: 1fr; gap: .45rem; }
            .session-meta-item { padding: .55rem .7rem; }
            .status-pills { display: grid; grid-template-columns: 1fr; }
            .status-pill { display: flex; align-items: center; gap: .8rem; text-align: left; padding: .8rem 1rem; }
            .status-pill .mt-1 { margin-top: 0 !important; }
            .duration-pills { display: grid; grid-template-columns: repeat(3, 1fr); }
            .duration-pill { text-align: center; padding: .7rem .35rem; }
            .duration-pill[data-minutes="extra"] { grid-column: 1 / -1; }
            .submit-bar { align-items: stretch; flex-direction: column; }
            .submit-bar .btn { width: 100%; }
            .report-grid { grid-template-columns: 1fr; }
            .report-panel { padding: .9rem; }
        }
    </style>

    <div class="attendance-header">
        <div>
            <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> เช็คชื่อเข้าเรียน</div>
            <h1 class="page-title mb-0"><i class="bi bi-clipboard-check"></i> เช็คชื่อเข้าเรียน</h1>
        </div>
        <a href="{{ route('teaching-logs.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> กลับหน้ารวม</a>
    </div>

    <div class="form-section session-hero">
        <div class="small opacity-75 mb-1">นักเรียน</div>
        <div class="student-name">{{ $classSchedule->enrollment->student->full_name ?? '-' }}</div>
        <div class="opacity-75 mt-1">{{ $classSchedule->enrollment->course->name ?? '-' }}</div>
        <div class="session-meta-grid">
            <div class="session-meta-item"><i class="bi bi-calendar3"></i><div><div class="meta-label">วันและเวลา</div><div class="meta-value">{{ $classSchedule->schedule_date->format('d/m/Y') }} · {{ $classSchedule->start_time }}–{{ $classSchedule->end_time }}</div></div></div>
            <div class="session-meta-item"><i class="bi bi-door-open"></i><div><div class="meta-label">ห้องเรียน</div><div class="meta-value">{{ $classSchedule->room->name ?? 'ออนไลน์' }}</div></div></div>
            <div class="session-meta-item"><i class="bi bi-person-badge"></i><div><div class="meta-label">ผู้สอน</div><div class="meta-value">{{ $classSchedule->teacher->full_name ?? '-' }}</div></div></div>
        </div>
    </div>

    @if ($approvedLeave)
        <div class="form-section" style="background:var(--amber-soft,#f3ece2); border-color:#e6d9c3;">
            <div class="d-flex gap-2">
                <i class="bi bi-info-circle fs-5" style="color:#8a5a2b;"></i>
                <div>คาบนี้มีคำขอลาที่ได้รับอนุมัติแล้วผูกอยู่ ({{ $approvedLeave->leaveTypeLabel() }}) ระบบตั้งสถานะเป็น
                    "ลา" ให้อัตโนมัติ และจะไม่ตัดจำนวนครั้งเรียนของคอร์สนี้</div>
            </div>
        </div>
    @endif

    {{-- ===== เช็คชื่อ + ยืนยันเวลาสอนจริง (ทำครั้งเดียวจบ) ===== --}}
    <div class="form-section">
        <div class="form-section-title">
            <div class="icon-badge"><i class="bi bi-person-check"></i></div> เช็คชื่อ &amp; ยืนยันเวลาสอนจริง
        </div>

        @if ($log->confirmed_at)
            {{-- เสร็จสิ้นแล้วทั้งสองขั้นตอน --}}
            <div class="locked-box">
                <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                <div class="fw-bold mt-1" style="font-family:'Prompt',sans-serif;">
                    {{ $log->attendanceStatusLabel() }} — {{ $log->durationLabel() }}
                </div>
                <div class="text-muted small mt-1">ยืนยันเมื่อ {{ $log->confirmed_at->format('d/m/Y H:i') }} โดย
                    {{ $log->confirmed_by }}</div>
                @if ($log->session_deducted)
                    <div class="text-muted small">✓ ตัดจำนวนครั้งเรียนของคอร์สแล้ว</div>
                @endif
                @if ($log->teaching_session_id)
                    <div class="text-muted small">✓ บันทึกลงระบบเงินเดือนแล้ว</div>
                @endif
            </div>
        @else
            <form id="combinedForm" onsubmit="return false;">
                @csrf
                <input type="hidden" id="attendanceStatusInput" value="{{ $log->attendance_status }}">
                <input type="hidden" id="durationInput">
                <input type="hidden" id="isExtraInput" value="0">

                <div class="step-label"><span class="step-number">1</span><span>สถานะการเข้าเรียน</span></div>
                @if ($log->student_leave_id)
                    <div class="status-pill absent active" style="max-width:250px;">
                        <i class="bi bi-calendar-x fs-4"></i>
                        <div class="fw-bold mt-1">{{ $log->attendanceStatusLabel() }}</div>
                    </div>
                @else
                    <div class="status-pills mb-3">
                        <button type="button" class="status-pill present {{ $log->attendance_status == 'present' ? 'active' : '' }}"
                            data-value="present" aria-pressed="{{ $log->attendance_status == 'present' ? 'true' : 'false' }}">
                            <i class="bi bi-check-circle fs-4"></i>
                            <div class="fw-bold mt-1">เข้าเรียน</div>
                        </button>
                        <button type="button" class="status-pill late {{ $log->attendance_status == 'late' ? 'active' : '' }}"
                            data-value="late" aria-pressed="{{ $log->attendance_status == 'late' ? 'true' : 'false' }}">
                            <i class="bi bi-clock-history fs-4"></i>
                            <div class="fw-bold mt-1">เข้าเรียนสาย</div>
                        </button>
                        <button type="button" class="status-pill absent {{ $log->attendance_status == 'absent' ? 'active' : '' }}"
                            data-value="absent" aria-pressed="{{ $log->attendance_status == 'absent' ? 'true' : 'false' }}">
                            <i class="bi bi-x-circle fs-4"></i>
                            <div class="fw-bold mt-1">ขาดเรียน</div>
                        </button>
                    </div>
                @endif

                <div class="step-label mt-4"><span class="step-number">2</span><span>เวลาที่สอนจริง</span></div>
                <div class="duration-pills mb-2">
                    <button type="button" class="duration-pill" data-minutes="30" aria-pressed="false">30 นาที</button>
                    <button type="button" class="duration-pill" data-minutes="45" aria-pressed="false">45 นาที</button>
                    <button type="button" class="duration-pill" data-minutes="60" aria-pressed="false">60 นาที</button>
                    <button type="button" class="duration-pill" data-minutes="extra" aria-pressed="false">กำหนดเอง</button>
                </div>

                <div id="extraMinutesBox" class="d-none mb-3">
                    <label class="form-label small">ระบุจำนวนนาทีที่สอนจริง</label>
                    <input type="number" id="extraMinutesInput" class="form-control" min="1" max="600"
                        style="max-width:200px;">
                </div>

                @php $transportFee = $classSchedule->teacher?->activeTransportFee(); @endphp
                @if ($transportFee && $transportFee->fee_type === 'per_km')
                    <div class="mb-3">
                        <label class="form-label small">ระยะทางเดินทาง (กิโลเมตร) — ใช้คำนวณค่ารถ</label>
                        <input type="number" step="0.1" id="kmTraveledInput" class="form-control"
                            style="max-width:200px;" placeholder="เช่น 12.5" min="0">
                        <small class="text-muted">อัตรา ฿{{ number_format($transportFee->fee_amount, 2) }}/กม.</small>
                    </div>
                @endif

                <label for="notesInput" class="form-label small mt-3">หมายเหตุ <span class="text-muted">(ถ้ามี)</span></label>
                <textarea id="notesInput" class="form-control" rows="2" placeholder="เช่น นักเรียนมาสาย 10 นาที">{{ $log->notes }}</textarea>

                <div class="text-danger small mt-2 d-none" id="combinedError"></div>
                <div class="submit-bar">
                    <div class="selection-summary" id="selectionSummary"><i class="bi bi-info-circle me-1"></i> เลือกสถานะและเวลาสอนเพื่อดำเนินการต่อ</div>
                    <button type="button" class="btn btn-accent" id="combinedSubmitBtn" disabled><i class="bi bi-check-lg"></i> ยืนยันและบันทึก</button>
                </div>
            </form>
        @endif
    </div>

    {{-- ===== เพิ่มเติม: บันทึกผลการสอน / หลักฐานการสอน ===== --}}
    @if ($log->attendance_status)
        @php $report = $log->teachingReport; @endphp
        <div class="form-section">
            <div class="form-section-title mb-0">
                <div class="icon-badge"><i class="bi bi-journal-text"></i></div> เพิ่มเติม: บันทึกผลการสอน &amp;
                หลักฐานการสอน <span class="text-muted small fw-normal ms-1">(ไม่บังคับ)</span>
            </div>

            <div class="pt-3">
                <form action="{{ route('teaching-reports.store', $log) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="report-grid">
                        <section class="report-panel" aria-labelledby="lesson-content-title">
                            <div class="report-panel-heading">
                                <span class="report-panel-icon"><i class="bi bi-music-note-list"></i></span>
                                <div>
                                    <div class="report-panel-title" id="lesson-content-title">เนื้อหาที่สอน</div>
                                    <div class="report-panel-hint">สรุปสิ่งที่เรียนและพัฒนาการในคาบนี้</div>
                                </div>
                            </div>
                            <label class="form-label small fw-semibold" for="contentTaught">รายละเอียดการสอน</label>
                            <textarea name="content_taught" class="form-control" rows="3"
                                id="contentTaught"
                                placeholder="เช่น ฝึกสเกล C Major, เพลง Für Elise ท่อนที่ 1-2">{{ old('content_taught', $report->content_taught ?? '') }}</textarea>
                            <label class="form-label small fw-semibold mt-3" for="progressNotes">ความก้าวหน้าของนักเรียน</label>
                            <textarea name="progress_notes" class="form-control" rows="2"
                                id="progressNotes"
                                placeholder="เช่น จับจังหวะได้ดีขึ้น ยังต้องฝึกมือซ้าย">{{ old('progress_notes', $report->progress_notes ?? '') }}</textarea>
                        </section>

                        <section class="report-panel homework-panel" aria-labelledby="homework-title">
                            <div class="report-panel-heading">
                                <span class="report-panel-icon"><i class="bi bi-pencil-square"></i></span>
                                <div>
                                    <div class="report-panel-title" id="homework-title">การบ้านครั้งถัดไป</div>
                                    <div class="report-panel-hint">ระบุสิ่งที่ต้องฝึกให้ชัดเจนและทำตามได้ง่าย</div>
                                </div>
                            </div>
                            <label class="form-label small fw-semibold" for="homeworkInput">โจทย์หรือแบบฝึกหัด</label>
                            <textarea name="homework" class="form-control" rows="4" id="homeworkInput"
                                placeholder="เช่น ฝึกเพลงเดิมวันละ 15 นาที เน้นห้องที่ 8–12">{{ old('homework', $report->homework ?? '') }}</textarea>
                            <label class="form-label small fw-semibold mt-3" for="reportNotes">หมายเหตุสำหรับผู้เรียน</label>
                            <textarea name="notes" class="form-control" rows="2" id="reportNotes"
                                placeholder="คำแนะนำเพิ่มเติม (ถ้ามี)">{{ old('notes', $report->notes ?? '') }}</textarea>
                        </section>
                    </div>

                    <div class="report-attachments">
                            <label class="form-label fw-semibold"><i class="bi bi-paperclip me-1"></i> ไฟล์ประกอบบทเรียน <span class="text-muted small fw-normal">(สูงสุด 5 ไฟล์)</span></label>
                            <input type="file" name="attachments[]" class="form-control" multiple
                                accept=".pdf,.jpg,.jpeg,.png,.mp3,.mp4,.mscz,.xml,.doc,.docx">
                            @if ($report && $report->attachments->count())
                                <div class="mt-2">
                                    @foreach ($report->attachments as $att)
                                        <span class="badge text-bg-light border me-1 mb-1">
                                            <a href="{{ $att->url() }}" target="_blank"
                                                class="text-decoration-none">{{ $att->original_name }}</a>
                                            <form action="{{ route('teaching-reports.attachments.destroy', $att) }}"
                                                method="POST" class="d-inline" onsubmit="return confirm('ลบไฟล์นี้?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm border-0 p-0 ms-1"
                                                    style="color:#b3392c;">✕</button>
                                            </form>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                    </div>
                    <button class="btn btn-accent mt-3"><i class="bi bi-save"></i>
                        {{ $report ? 'อัปเดตผลการสอน' : 'บันทึกผลการสอน' }}</button>
                </form>

                <hr class="my-4">

                <label class="form-label fw-semibold"><i class="bi bi-camera"></i> หลักฐานการสอน</label>
                <form action="{{ route('teaching-evidences.store', $log) }}" method="POST"
                    enctype="multipart/form-data" class="mb-3">
                    @csrf
                    <label class="form-label">อัปโหลดรูปภาพ / วิดีโอ / เอกสาร (สูงสุด 10 ไฟล์ต่อครั้ง, ไม่เกิน
                        50MB/ไฟล์)</label>
                    <div class="d-flex gap-2">
                        <input type="file" name="files[]" class="form-control" multiple
                            accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx" required>
                        <button class="btn btn-accent flex-shrink-0"><i class="bi bi-upload"></i> อัปโหลด</button>
                    </div>
                    <small class="text-muted d-block mt-1"><i class="bi bi-info-circle"></i> รองรับ: รูปภาพ
                        (JPG/PNG/WEBP/GIF), วิดีโอ (MP4/MOV/AVI/WEBM), เอกสาร (PDF/DOC/XLS)</small>
                </form>

                @if ($log->evidences->count())
                    <div class="row g-2">
                        @foreach ($log->evidences as $ev)
                            <div class="col-md-4">
                                <div class="border rounded p-2 h-100 d-flex flex-column">
                                    @if ($ev->file_type === 'image')
                                        <img src="{{ $ev->url() }}" class="rounded mb-2"
                                            style="width:100%; height:110px; object-fit:cover;">
                                    @elseif($ev->file_type === 'video')
                                        <video src="{{ $ev->url() }}" controls class="rounded mb-2"
                                            style="width:100%; height:110px; object-fit:cover;"></video>
                                    @else
                                        <div class="d-flex align-items-center justify-content-center rounded mb-2"
                                            style="height:110px; background:#f4f3f1;">
                                            <i class="bi {{ $ev->fileTypeIcon() }} fs-1 text-secondary"></i>
                                        </div>
                                    @endif
                                    <div class="small fw-semibold text-truncate" title="{{ $ev->original_name }}">
                                        {{ $ev->original_name }}</div>
                                    <div class="small text-muted">{{ $ev->fileTypeLabel() }} ·
                                        {{ $ev->formattedSize() }}
                                    </div>
                                    <div class="small text-muted"><i class="bi bi-person"></i>
                                        {{ $ev->uploaded_by_name }} ·
                                        {{ $ev->created_at->format('d/m/Y H:i') }}</div>
                                    <div class="d-flex gap-1 mt-2">
                                        <a href="{{ route('teaching-evidences.download', $ev) }}"
                                            class="btn btn-sm btn-outline-secondary flex-fill"><i
                                                class="bi bi-download"></i>
                                            ดาวน์โหลด</a>
                                        <form action="{{ route('teaching-evidences.destroy', $ev) }}" method="POST"
                                            onsubmit="return confirm('ลบไฟล์นี้?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small mb-0">ยังไม่มีหลักฐานการสอนที่อัปโหลดไว้</p>
                @endif
            </div>
        </div>
    @endif

    <a href="{{ route('teaching-logs.index') }}" class="btn btn-outline-secondary mb-4"><i class="bi bi-arrow-left"></i>
        กลับหน้ารวมเช็คชื่อ</a>

    <script>
        function updateCombinedSubmitState() {
            const btn = document.getElementById('combinedSubmitBtn');
            if (!btn) return;
            const status = document.getElementById('attendanceStatusInput').value;
            const duration = document.getElementById('durationInput').value;
            btn.disabled = !status || !duration || Number(duration) <= 0;
            const statusNames = { present: 'เข้าเรียน', late: 'เข้าเรียนสาย', absent: 'ขาดเรียน', excused_leave: 'ลาเรียน' };
            const summary = document.getElementById('selectionSummary');
            if (summary) {
                summary.innerHTML = status && duration
                    ? `<i class="bi bi-check-circle me-1"></i> ${statusNames[status] || status} · สอนจริง ${duration} นาที`
                    : '<i class="bi bi-info-circle me-1"></i> เลือกสถานะและเวลาสอนเพื่อดำเนินการต่อ';
            }
        }

        document.querySelectorAll('.status-pill[data-value]').forEach(pill => {
            pill.addEventListener('click', function() {
                document.querySelectorAll('.status-pill[data-value]').forEach(p => p.classList.remove(
                    'active'));
                document.querySelectorAll('.status-pill[data-value]').forEach(p => p.setAttribute('aria-pressed', 'false'));
                this.classList.add('active');
                this.setAttribute('aria-pressed', 'true');
                document.getElementById('attendanceStatusInput').value = this.dataset.value;
                updateCombinedSubmitState();
            });
        });

        document.querySelectorAll('.duration-pill').forEach(pill => {
            pill.addEventListener('click', function() {
                document.querySelectorAll('.duration-pill').forEach(p => p.classList.remove('active'));
                document.querySelectorAll('.duration-pill').forEach(p => p.setAttribute('aria-pressed', 'false'));
                this.classList.add('active');
                this.setAttribute('aria-pressed', 'true');
                const extraBox = document.getElementById('extraMinutesBox');
                const durationInput = document.getElementById('durationInput');
                const isExtraInput = document.getElementById('isExtraInput');

                if (this.dataset.minutes === 'extra') {
                    extraBox.classList.remove('d-none');
                    isExtraInput.value = '1';
                    durationInput.value = '';
                } else {
                    extraBox.classList.add('d-none');
                    isExtraInput.value = '0';
                    durationInput.value = this.dataset.minutes;
                }
                updateCombinedSubmitState();
            });
        });

        document.getElementById('extraMinutesInput')?.addEventListener('input', function() {
            document.getElementById('durationInput').value = this.value;
            updateCombinedSubmitState();
        });

        @php
            $scheduledMinutes = \Carbon\Carbon::parse($classSchedule->start_time)->diffInMinutes(\Carbon\Carbon::parse($classSchedule->end_time));
        @endphp
        const scheduledMinutes = @json($scheduledMinutes);
        const suggestedDuration = document.querySelector(`.duration-pill[data-minutes="${scheduledMinutes}"]`);
        if (suggestedDuration && !document.getElementById('durationInput').value) suggestedDuration.click();
        updateCombinedSubmitState();

        document.getElementById('combinedSubmitBtn')?.addEventListener('click', async function() {
            if (!confirm('บันทึกการเช็คชื่อและเวลาสอนจริงแล้วจะแก้ไขเวลาสอนไม่ได้ ยืนยันหรือไม่?')) return;

            const btn = this;
            const errorBox = document.getElementById('combinedError');
            errorBox.classList.add('d-none');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> กำลังบันทึก...';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const headers = {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            };

            try {
                // คาบที่ผูกกับคำขอลาที่อนุมัติแล้ว ระบบเช็คชื่อเป็น "ลา" ให้อัตโนมัติตั้งแต่เปิดหน้านี้แล้ว
                // (endpoint เช็คชื่อรับได้แค่ present/late/absent) จึงข้ามขั้นเช็คชื่อ ไปยืนยันเวลาสอนอย่างเดียว
                const skipCheckin = @json((bool) $log->student_leave_id);

                if (!skipCheckin) {
                    const checkinRes = await fetch('{{ route('teaching-logs.check-in', $log) }}', {
                        method: 'POST',
                        headers,
                        body: JSON.stringify({
                            attendance_status: document.getElementById('attendanceStatusInput').value,
                            notes: document.getElementById('notesInput')?.value ?? '',
                        }),
                    });
                    if (!checkinRes.ok) throw new Error('เช็คชื่อไม่สำเร็จ');
                }

                const durationRes = await fetch('{{ route('teaching-logs.confirm-duration', $log) }}', {
                    method: 'POST',
                    headers,
                    body: JSON.stringify({
                        duration_minutes: document.getElementById('durationInput').value,
                        is_extra_time: document.getElementById('isExtraInput').value === '1',
                        km_traveled: document.getElementById('kmTraveledInput')?.value || null,
                    }),
                });
                if (!durationRes.ok) throw new Error('ยืนยันเวลาสอนจริงไม่สำเร็จ');

                window.location.reload();
            } catch (err) {
                errorBox.textContent = err.message + ' — กรุณาลองใหม่อีกครั้ง';
                errorBox.classList.remove('d-none');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-lg"></i> ยืนยันและบันทึก';
            }
        });
    </script>
@endsection

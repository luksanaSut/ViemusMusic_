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
        }

        .status-pill:hover {
            border-color: #c9c4bb;
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
    </style>

    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> เช็คชื่อเข้าเรียน</div>
    <h1 class="page-title mb-3"><i class="bi bi-clipboard-check"></i> เช็คชื่อเข้าเรียน</h1>

    <div class="form-section">
        <div class="form-section-title">
            <div class="icon-badge"><i class="bi bi-info-circle"></i></div> ข้อมูลคาบเรียน
        </div>
        <div class="info-row">
            <div class="label">นักเรียน</div>
            <div class="value">{{ $classSchedule->enrollment->student->full_name ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="label">คอร์ส</div>
            <div class="value">{{ $classSchedule->enrollment->course->name ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="label">อาจารย์</div>
            <div class="value">{{ $classSchedule->teacher->full_name ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="label">วันเวลาตามตาราง</div>
            <div class="value">{{ $classSchedule->schedule_date->format('d/m/Y') }}
                {{ $classSchedule->start_time }}-{{ $classSchedule->end_time }}</div>
        </div>
        <div class="info-row">
            <div class="label">ห้องเรียน</div>
            <div class="value">{{ $classSchedule->room->name ?? 'ออนไลน์' }}</div>
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

    {{-- ===== ขั้นที่ 1: เช็คชื่อ ===== --}}
    <div class="form-section">
        <div class="form-section-title">
            <div class="icon-badge"><i class="bi bi-person-check"></i></div> ขั้นที่ 1: เช็คชื่อเข้าเรียน
        </div>

        @if ($log->student_leave_id)
            <div class="status-pill absent active" style="max-width:250px;">
                <i class="bi bi-calendar-x fs-4"></i>
                <div class="fw-bold mt-1">{{ $log->attendanceStatusLabel() }}</div>
            </div>
        @else
            <form action="{{ route('teaching-logs.check-in', $log) }}" method="POST" id="checkinForm">
                @csrf
                <input type="hidden" name="attendance_status" id="attendanceStatusInput"
                    value="{{ $log->attendance_status }}">
                <div class="status-pills mb-3">
                    <div class="status-pill present {{ $log->attendance_status == 'present' ? 'active' : '' }}"
                        data-value="present">
                        <i class="bi bi-check-circle fs-4"></i>
                        <div class="fw-bold mt-1">เข้าเรียน</div>
                    </div>
                    <div class="status-pill late {{ $log->attendance_status == 'late' ? 'active' : '' }}"
                        data-value="late">
                        <i class="bi bi-clock-history fs-4"></i>
                        <div class="fw-bold mt-1">เข้าเรียนสาย</div>
                    </div>
                    <div class="status-pill absent {{ $log->attendance_status == 'absent' ? 'active' : '' }}"
                        data-value="absent">
                        <i class="bi bi-x-circle fs-4"></i>
                        <div class="fw-bold mt-1">ขาดเรียน</div>
                    </div>
                </div>
                <textarea name="notes" class="form-control mb-3" rows="2" placeholder="หมายเหตุ (ถ้ามี)">{{ $log->notes }}</textarea>
                <button class="btn btn-accent" id="checkinBtn" {{ !$log->attendance_status ? 'disabled' : '' }}><i
                        class="bi bi-check-lg"></i> บันทึกการเช็คชื่อ</button>
            </form>
        @endif

        @if ($log->checked_in_at)
            <div class="text-muted small mt-2"><i class="bi bi-clock"></i> เช็คชื่อเมื่อ
                {{ $log->checked_in_at->format('d/m/Y H:i') }} โดย {{ $log->checked_in_by }}</div>
        @endif
    </div>

    {{-- ===== ขั้นที่ 2: Confirm เวลาสอนจริง ===== --}}
    <div class="form-section">
        <div class="form-section-title">
            <div class="icon-badge"><i class="bi bi-stopwatch"></i></div> ขั้นที่ 2: Confirm เวลาที่สอนจริง
        </div>

        @if ($log->confirmed_at)
            <div class="locked-box">
                <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                <div class="fw-bold mt-1" style="font-family:'Prompt',sans-serif;">ยืนยันแล้ว: {{ $log->durationLabel() }}
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
        @elseif(!$log->attendance_status)
            <p class="text-muted">กรุณาเช็คชื่อในขั้นที่ 1 ก่อน จึงจะยืนยันเวลาสอนจริงได้</p>
        @else
            <form action="{{ route('teaching-logs.confirm-duration', $log) }}" method="POST" id="durationForm">
                @csrf
                <input type="hidden" name="duration_minutes" id="durationInput">
                <input type="hidden" name="is_extra_time" id="isExtraInput" value="0">

                <label class="form-label small fw-semibold mb-2 d-block">เลือกเวลาที่สอนจริง</label>
                <div class="duration-pills mb-3">
                    <div class="duration-pill" data-minutes="30">30 นาที</div>
                    <div class="duration-pill" data-minutes="45">45 นาที</div>
                    <div class="duration-pill" data-minutes="60">60 นาที</div>
                    <div class="duration-pill" data-minutes="extra">สอนเพิ่ม (กำหนดเอง)</div>
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
                        <input type="number" step="0.1" name="km_traveled" class="form-control"
                            style="max-width:200px;" placeholder="เช่น 12.5" min="0">
                        <small class="text-muted">อัตรา ฿{{ number_format($transportFee->fee_amount, 2) }}/กม.</small>
                    </div>
                @endif

                <button class="btn btn-accent" id="confirmDurationBtn" disabled><i class="bi bi-check-lg"></i>
                    ยืนยันเวลาสอนจริง</button>
                <small class="text-muted d-block mt-2"><i class="bi bi-info-circle"></i>
                    เมื่อยืนยันแล้วจะไม่สามารถแก้ไขได้ —
                    ระบบจะบันทึกรายได้เข้าระบบเงินเดือนและตัดจำนวนครั้งเรียนของคอร์สอัตโนมัติ
                    (เฉพาะกรณีเข้าเรียนจริง)</small>
            </form>
        @endif
    </div>

    {{-- ===== ขั้นที่ 3: บันทึกผลการสอน (แสดงหลังเช็คชื่อแล้ว) ===== --}}
    @if ($log->attendance_status)
        @php $report = $log->teachingReport; @endphp
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-journal-text"></i></div> ขั้นที่ 3: บันทึกผลการสอน
            </div>
            <form action="{{ route('teaching-reports.store', $log) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">เนื้อหาที่สอน</label>
                        <textarea name="content_taught" class="form-control" rows="3"
                            placeholder="เช่น ฝึกสเกล C Major, เพลง Für Elise ท่อนที่ 1-2">{{ old('content_taught', $report->content_taught ?? '') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">การบ้าน</label>
                        <textarea name="homework" class="form-control" rows="2" placeholder="เช่น ฝึกเพลงเดิม 15 นาที/วัน">{{ old('homework', $report->homework ?? '') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ความก้าวหน้าของนักเรียน</label>
                        <textarea name="progress_notes" class="form-control" rows="2"
                            placeholder="เช่น จับจังหวะได้ดีขึ้น ยังต้องฝึกมือซ้าย">{{ old('progress_notes', $report->progress_notes ?? '') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">หมายเหตุเพิ่มเติม</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes', $report->notes ?? '') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">แนบโน้ตเพลง/ไฟล์ประกอบ (สูงสุด 5 ไฟล์)</label>
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
                                            <button class="btn btn-sm border-0 p-0 ms-1" style="color:#b3392c;">✕</button>
                                        </form>
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <button class="btn btn-accent mt-3"><i class="bi bi-save"></i>
                    {{ $report ? 'อัปเดตผลการสอน' : 'บันทึกผลการสอน' }}</button>
            </form>
        </div>
    @endif

    {{-- ===== ขั้นที่ 4: อัปโหลดหลักฐานการสอน ===== --}}
    @if ($log->attendance_status)
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-camera"></i></div> หลักฐานการสอน
            </div>

            <form action="{{ route('teaching-evidences.store', $log) }}" method="POST" enctype="multipart/form-data"
                class="mb-3">
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
                                <div class="small text-muted">{{ $ev->fileTypeLabel() }} · {{ $ev->formattedSize() }}
                                </div>
                                <div class="small text-muted"><i class="bi bi-person"></i> {{ $ev->uploaded_by_name }} ·
                                    {{ $ev->created_at->format('d/m/Y H:i') }}</div>
                                <div class="d-flex gap-1 mt-2">
                                    <a href="{{ route('teaching-evidences.download', $ev) }}"
                                        class="btn btn-sm btn-outline-secondary flex-fill"><i class="bi bi-download"></i>
                                        ดาวน์โหลด</a>
                                    <form action="{{ route('teaching-evidences.destroy', $ev) }}" method="POST"
                                        onsubmit="return confirm('ลบไฟล์นี้?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
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
    @endif

    <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary mb-4"><i class="bi bi-arrow-left"></i>
        กลับไปตารางเรียน</a>

    <script>
        document.querySelectorAll('.status-pill[data-value]').forEach(pill => {
            pill.addEventListener('click', function() {
                document.querySelectorAll('.status-pill[data-value]').forEach(p => p.classList.remove(
                    'active'));
                this.classList.add('active');
                document.getElementById('attendanceStatusInput').value = this.dataset.value;
                document.getElementById('checkinBtn').disabled = false;
            });
        });

        document.querySelectorAll('.duration-pill').forEach(pill => {
            pill.addEventListener('click', function() {
                document.querySelectorAll('.duration-pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                const extraBox = document.getElementById('extraMinutesBox');
                const durationInput = document.getElementById('durationInput');
                const isExtraInput = document.getElementById('isExtraInput');
                const confirmBtn = document.getElementById('confirmDurationBtn');

                if (this.dataset.minutes === 'extra') {
                    extraBox.classList.remove('d-none');
                    isExtraInput.value = '1';
                    durationInput.value = '';
                    confirmBtn.disabled = true;
                } else {
                    extraBox.classList.add('d-none');
                    isExtraInput.value = '0';
                    durationInput.value = this.dataset.minutes;
                    confirmBtn.disabled = false;
                }
            });
        });

        document.getElementById('extraMinutesInput')?.addEventListener('input', function() {
            document.getElementById('durationInput').value = this.value;
            document.getElementById('confirmDurationBtn').disabled = !this.value || this.value <= 0;
        });

        document.getElementById('durationForm')?.addEventListener('submit', function(e) {
            if (!confirm('ยืนยันเวลาสอนจริงแล้วจะแก้ไขไม่ได้ ยืนยันหรือไม่?')) {
                e.preventDefault();
                return;
            }
            document.getElementById('confirmDurationBtn').disabled = true;
        });
    </script>
@endsection

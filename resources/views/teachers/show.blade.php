@extends('layouts.app')
@section('title', $teacher->full_name)

@section('content')
    <style>
        .form-section {
            background: #fff;
            border: 1px solid var(--border, #e7e8f2);
            border-radius: 14px;
            padding: 1.25rem 1.4rem;
            margin-bottom: 1.25rem;
        }

        .form-section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .55rem;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 1rem;
            font-family: 'Prompt', sans-serif;
            color: var(--ink, #171a2b);
        }

        .form-section-title .title-left {
            display: flex;
            align-items: center;
            gap: .55rem;
        }

        .form-section-title i {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: var(--accent-soft, #eef0fd);
            color: var(--accent-dark, #4744b8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .profile-cover {
            height: 120px;
            border-radius: 14px 14px 0 0;
            background: linear-gradient(135deg, #1c1a17, var(--accent, #1f3350));
            position: relative;
        }

        .profile-avatar {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: #fff;
            border: 4px solid #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: absolute;
            left: 24px;
            bottom: -40px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid var(--border, #e7e8f2);
            border-radius: 14px;
            padding: 1.1rem 1.3rem;
            display: flex;
            align-items: center;
            gap: .9rem;
            height: 100%;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .info-row {
            display: flex;
            gap: .6rem;
            padding: .5rem 0;
            border-bottom: 1px dashed var(--border, #e7e8f2);
        }

        .info-row i {
            color: var(--accent, #5b57e0);
            width: 20px;
            margin-top: .15rem;
        }

        .info-row .label {
            font-size: .75rem;
            color: var(--muted, #6b7280);
        }

        .info-row .value {
            font-weight: 500;
        }

        .table-clean thead th {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--muted, #6b7280);
            border-top: 0;
        }

        .table-clean td,
        .table-clean th {
            vertical-align: middle;
        }
    </style>

    <div class="breadcrumb-sm">บุคคล <i class="bi bi-chevron-right small"></i> อาจารย์ <i
            class="bi bi-chevron-right small"></i> {{ $teacher->full_name }}</div>

    {{-- ===== Header: cover + avatar + ข้อมูลย่อ ===== --}}
    <div class="card mb-3 overflow-hidden">
        <div class="profile-cover">
            <div class="d-flex justify-content-end p-2 gap-2">
                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i>
                    แก้ไข</a>
                <a href="{{ route('teachers.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i>
                    กลับ</a>
            </div>
            <div class="profile-avatar">
                @if ($teacher->photo_path)
                    <img src="{{ asset('storage/' . $teacher->photo_path) }}"
                        style="width:100%;height:100%;object-fit:cover;">
                @else
                    <span
                        style="font-weight:700;font-size:1.6rem;color:var(--accent-dark,#4744b8);font-family:'Prompt',sans-serif;">{{ $teacher->initials() }}</span>
                @endif
            </div>
        </div>
        <div class="card-body pt-5 mt-2">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <h4 class="mb-0" style="font-family:'Prompt',sans-serif;">{{ $teacher->full_name }}</h4>
                <span class="text-muted small">({{ $teacher->teacher_code }})</span>
            </div>
            <div class="mt-2 d-flex flex-wrap gap-1">
                <span
                    class="badge {{ $teacher->employment_type == 'full_time' ? 'text-bg-success' : 'text-bg-info' }}">{{ $teacher->employmentTypeLabel() }}</span>
                @if ($teacher->branch)
                    <span class="badge text-bg-light border"><i class="bi bi-geo-alt"></i> {{ $teacher->branch }}</span>
                @endif
                @foreach ($teacher->teachingTypes as $tt)
                    <span class="badge text-bg-secondary">{{ $tt->name }}</span>
                @endforeach
                @if (!$teacher->is_active)
                    <span class="badge text-bg-danger">ปิดใช้งาน</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== สถิติสรุป ===== --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--accent-soft,#eef0fd);color:var(--accent-dark,#4744b8);"><i
                        class="bi bi-clock-history"></i></div>
                <div>
                    <div class="text-muted small">ชั่วโมงสอน (ช่วงที่เลือก)</div>
                    <div class="fs-4 fw-bold" style="font-family:'Prompt',sans-serif;">{{ number_format($totalHours, 2) }}
                        <span class="fs-6 fw-normal text-muted">ชม.</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--success-soft,#e9f9ef);color:var(--success,#16a34a);"><i
                        class="bi bi-cash-stack"></i></div>
                <div>
                    <div class="text-muted small">รายได้รวม (ช่วงที่เลือก)</div>
                    <div class="fs-4 fw-bold text-success" style="font-family:'Prompt',sans-serif;">
                        {{ number_format($totalIncome, 2) }} <span class="fs-6 fw-normal text-muted">บาท</span></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--amber-soft,#fdf1e2);color:var(--amber,#d97706);"><i
                        class="bi bi-music-note-list"></i></div>
                <div class="flex-grow-1">
                    <div class="text-muted small mb-1">เครื่องดนตรีที่สอนได้</div>
                    @forelse($teacher->instruments as $ins)
                        <span
                            class="badge text-bg-light border">{{ $ins->name }}{{ $ins->pivot->is_primary ? ' ★' : '' }}</span>
                    @empty
                        <span class="text-muted small">ยังไม่ระบุ</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ===== แท็บ ===== --}}
    <ul class="nav tab-pills mb-3" id="teacherTab" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info"><i
                    class="bi bi-person-vcard"></i> ข้อมูลทั่วไป</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#rates"><i
                    class="bi bi-cash-coin"></i> เรทค่าจ้าง / ค่ารถ</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#availability"><i
                    class="bi bi-calendar-week"></i> Availability</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#history"><i
                    class="bi bi-journal-text"></i> ประวัติการสอน</button></li>
    </ul>

    <div class="tab-content">

        {{-- ===== ข้อมูลทั่วไป ===== --}}
        <div class="tab-pane fade show active" id="info">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-section h-100">
                        <div class="form-section-title">
                            <div class="title-left"><i class="bi bi-person-lines-fill"></i> ข้อมูลติดต่อ</div>
                        </div>
                        <div class="info-row"><i class="bi bi-envelope"></i>
                            <div>
                                <div class="label">อีเมล</div>
                                <div class="value">{{ $teacher->email ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="info-row"><i class="bi bi-telephone"></i>
                            <div>
                                <div class="label">เบอร์โทร</div>
                                <div class="value">{{ $teacher->phone ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="info-row"><i class="bi bi-chat-dots"></i>
                            <div>
                                <div class="label">Line ID</div>
                                <div class="value">{{ $teacher->line_id ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="info-row"><i class="bi bi-geo-alt"></i>
                            <div>
                                <div class="label">ที่อยู่</div>
                                <div class="value">{{ $teacher->address ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="info-row" style="border-bottom:none;"><i class="bi bi-calendar-check"></i>
                            <div>
                                <div class="label">วันที่เริ่มงาน</div>
                                <div class="value">{{ optional($teacher->start_date)->format('d/m/Y') ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-section h-100">
                        <div class="form-section-title">
                            <div class="title-left"><i class="bi bi-stars"></i> ความเชี่ยวชาญ</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small mb-1">ระดับที่สอนได้</div>
                            @forelse($teacher->levels as $lv)
                            <span class="badge text-bg-light border me-1 mb-1">{{ $lv->name }}</span>@empty <span
                                    class="text-muted small">ยังไม่ระบุ</span>
                            @endforelse
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small mb-1">ประวัติย่อ / Bio</div>
                            <p class="mb-0">{{ $teacher->bio ?: '-' }}</p>
                        </div>
                        @if ($teacher->notes)
                            <div>
                                <div class="text-muted small mb-1"><i class="bi bi-eye-slash"></i> หมายเหตุ (สำหรับแอดมิน)
                                </div>
                                <p class="mb-0 text-muted fst-italic">{{ $teacher->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== เรทค่าจ้าง / ค่ารถ ===== --}}
        <div class="tab-pane fade" id="rates">
            <div class="row g-3">
                <div class="col-md-7">
                    <div class="form-section">
                        <div class="form-section-title">
                            <div class="title-left"><i class="bi bi-cash-coin"></i> เรทค่าจ้าง</div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-clean">
                                <thead>
                                    <tr>
                                        <th>รูปแบบ</th>
                                        <th>จำนวนเงิน</th>
                                        <th>ประเภทสอน</th>
                                        <th>เครื่องดนตรี</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($teacher->rates as $r)
                                        <tr>
                                            <td>{{ $r->rateTypeLabel() }}</td>
                                            <td class="fw-semibold">{{ number_format($r->rate_amount, 2) }}</td>
                                            <td>{{ optional($r->teachingType)->name ?? 'ทุกประเภท' }}</td>
                                            <td>{{ optional($r->instrument)->name ?? 'ทุกเครื่องดนตรี' }}</td>
                                            <td>{!! $r->is_active
                                                ? '<span class="badge text-bg-success">ใช้งาน</span>'
                                                : '<span class="badge text-bg-secondary">ปิด</span>' !!}</td>
                                            <td>
                                                @if ($r->is_active)
                                                    <form action="{{ route('teachers.rates.destroy', [$teacher, $r]) }}"
                                                        method="POST" onsubmit="return confirm('ปิดการใช้งานเรทนี้?')">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger"><i
                                                                class="bi bi-x-lg"></i></button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-muted text-center py-3">ยังไม่มีเรทค่าจ้าง</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <form action="{{ route('teachers.rates.store', $teacher) }}" method="POST"
                            class="row g-2 mt-2 pt-2 border-top">
                            @csrf
                            <div class="col-md-3">
                                <select name="rate_type" class="form-select form-select-sm" required>
                                    <option value="per_hour">ต่อชั่วโมง</option>
                                    <option value="per_session">ต่อคาบ/ครั้ง</option>
                                    <option value="monthly_fixed">เหมาต่อเดือน</option>
                                </select>
                            </div>
                            <div class="col-md-2"><input type="number" step="0.01" min="0" max="1000000"
                                    name="rate_amount" class="form-control form-control-sm" placeholder="จำนวนเงิน"
                                    required></div>
                            <div class="col-md-3">
                                <select name="teaching_type_id" class="form-select form-select-sm">
                                    <option value="">ทุกประเภทสอน</option>
                                    @foreach ($teacher->teachingTypes as $tt)
                                        <option value="{{ $tt->id }}">{{ $tt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="instrument_id" class="form-select form-select-sm">
                                    <option value="">ทุกเครื่องดนตรี</option>
                                    @foreach ($teacher->instruments as $ins)
                                        <option value="{{ $ins->id }}">{{ $ins->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 d-grid"><button class="btn btn-sm btn-accent"><i
                                        class="bi bi-plus-lg"></i></button></div>
                        </form>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-section">
                        <div class="form-section-title">
                            <div class="title-left"><i class="bi bi-truck"></i> ค่ารถ</div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-clean">
                                <thead>
                                    <tr>
                                        <th>ประเภท</th>
                                        <th>จำนวนเงิน</th>
                                        <th>สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($teacher->transportFees as $f)
                                        <tr>
                                            <td>{{ $f->fee_type == 'fixed_per_day' ? 'เหมาต่อวัน' : 'ต่อกิโลเมตร' }}</td>
                                            <td class="fw-semibold">{{ number_format($f->fee_amount, 2) }}</td>
                                            <td>{!! $f->is_active
                                                ? '<span class="badge text-bg-success">ใช้งาน</span>'
                                                : '<span class="badge text-bg-secondary">ปิด</span>' !!}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-muted text-center py-3">ยังไม่ระบุค่ารถ</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <form action="{{ route('teachers.transport-fee.store', $teacher) }}" method="POST"
                            class="row g-2 pt-2 border-top">
                            @csrf
                            <div class="col-md-6">
                                <select name="fee_type" class="form-select form-select-sm">
                                    <option value="fixed_per_day">เหมาต่อวัน</option>
                                    <option value="per_km">ต่อกิโลเมตร</option>
                                </select>
                            </div>
                            <div class="col-md-4"><input type="number" step="0.01" min="0" max="100000"
                                    name="fee_amount" class="form-control form-control-sm" placeholder="จำนวนเงิน"
                                    required></div>
                            <div class="col-md-2 d-grid"><button class="btn btn-sm btn-accent"><i
                                        class="bi bi-plus-lg"></i></button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Availability ===== --}}
        <div class="tab-pane fade" id="availability">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="title-left"><i class="bi bi-calendar-week"></i> เวลาที่พร้อมสอน</div>
                </div>
                <form action="{{ route('teachers.availability.update', $teacher) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="table-responsive">
                        <table class="table table-sm table-clean align-middle">
                            <thead>
                                <tr>
                                    <th style="width:60px">ว่าง</th>
                                    <th>วัน</th>
                                    <th>เวลาเริ่ม</th>
                                    <th>เวลาสิ้นสุด</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (\App\Models\TeacherAvailability::dayLabels() as $dow => $label)
                                    @php $existing = $teacher->availabilities->firstWhere('day_of_week', $dow); @endphp
                                    <tr>
                                        <td>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    name="availabilities[{{ $dow }}][is_available]"
                                                    value="1"
                                                    {{ !$existing || $existing->is_available ? 'checked' : '' }}>
                                            </div>
                                            <input type="hidden" name="availabilities[{{ $dow }}][day_of_week]"
                                                value="{{ $dow }}">
                                        </td>
                                        <td class="fw-semibold">{{ $label }}</td>
                                        <td><input type="time" name="availabilities[{{ $dow }}][start_time]"
                                                class="form-control form-control-sm"
                                                value="{{ $existing->start_time ?? '09:00' }}"></td>
                                        <td><input type="time" name="availabilities[{{ $dow }}][end_time]"
                                                class="form-control form-control-sm"
                                                value="{{ $existing->end_time ?? '18:00' }}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button class="btn btn-accent btn-sm mt-2"><i class="bi bi-save"></i> บันทึก Availability</button>
                </form>
            </div>
        </div>

        {{-- ===== ประวัติการสอน / ชม.สอน / รายได้ ===== --}}
        <div class="tab-pane fade" id="history">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="title-left"><i class="bi bi-funnel"></i> กรองช่วงวันที่</div>
                </div>
                <form method="GET" class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label small">จากวันที่</label>
                        <input type="date" name="from" value="{{ $from }}"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">ถึงวันที่</label>
                        <input type="date" name="to" value="{{ $to }}"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary btn-sm w-100"><i class="bi bi-filter"></i> กรอง</button>
                    </div>
                </form>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <div class="title-left"><i class="bi bi-plus-circle"></i> เพิ่มประวัติ/นัดสอน</div>
                </div>
                <form action="{{ route('teachers.sessions.store', $teacher) }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-2"><input type="date" name="session_date" class="form-control form-control-sm"
                            required></div>
                    <div class="col-md-1"><input type="time" name="start_time" class="form-control form-control-sm"
                            required></div>
                    <div class="col-md-1"><input type="time" name="end_time" class="form-control form-control-sm"
                            required></div>
                    <div class="col-md-2">
                        <select name="instrument_id" class="form-select form-select-sm">
                            <option value="">เครื่องดนตรี</option>
                            @foreach ($teacher->instruments as $ins)
                                <option value="{{ $ins->id }}">{{ $ins->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="teaching_type_id" class="form-select form-select-sm">
                            <option value="">ประเภทสอน</option>
                            @foreach ($teacher->teachingTypes as $tt)
                                <option value="{{ $tt->id }}">{{ $tt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2"><input type="text" name="student_name" class="form-control form-control-sm"
                            placeholder="ชื่อนักเรียน" maxlength="150"></div>
                    <div class="col-md-1">
                        <select name="status" class="form-select form-select-sm">
                            <option value="scheduled">นัดสอน</option>
                            <option value="completed">สอนแล้ว</option>
                            <option value="cancelled">ยกเลิก</option>
                            <option value="no_show">ขาดเรียน</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-grid"><button class="btn btn-sm btn-accent"><i
                                class="bi bi-plus-lg"></i></button></div>
                </form>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <div class="title-left"><i class="bi bi-clock-history"></i> ประวัติการสอน</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-clean">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>เวลา</th>
                                <th>ชม.</th>
                                <th>เครื่องดนตรี</th>
                                <th>ประเภท</th>
                                <th>นักเรียน</th>
                                <th>สถานะ</th>
                                <th>รายได้</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sessions as $s)
                                @php
                                    $statusMap = [
                                        'scheduled' => 'นัดสอน',
                                        'completed' => 'สอนแล้ว',
                                        'cancelled' => 'ยกเลิก',
                                        'no_show' => 'ขาดเรียน',
                                    ];
                                    $statusColor = [
                                        'scheduled' => 'text-bg-warning',
                                        'completed' => 'text-bg-success',
                                        'cancelled' => 'text-bg-secondary',
                                        'no_show' => 'text-bg-danger',
                                    ];
                                @endphp
                                <tr>
                                    <td>{{ $s->session_date->format('d/m/Y') }}</td>
                                    <td>{{ $s->start_time }}-{{ $s->end_time }}</td>
                                    <td>{{ $s->hours }}</td>
                                    <td>{{ optional($s->instrument)->name }}</td>
                                    <td>{{ optional($s->teachingType)->name }}</td>
                                    <td>{{ $s->student_name }}</td>
                                    <td><span
                                            class="badge {{ $statusColor[$s->status] }}">{{ $statusMap[$s->status] }}</span>
                                    </td>
                                    <td class="fw-semibold">{{ number_format($s->income_amount, 2) }}</td>
                                    <td>
                                        <form action="{{ route('teachers.sessions.destroy', [$teacher, $s]) }}"
                                            method="POST" onsubmit="return confirm('ลบรายการนี้?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">ไม่พบข้อมูลในช่วงเวลาที่เลือก
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">{{ $sessions->links() }}</div>
            </div>
        </div>
    </div>
@endsection

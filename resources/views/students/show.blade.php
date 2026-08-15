@extends('layouts.app')
@section('title', $student->full_name)

@section('content')
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

        .table-clean thead th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--muted, #6b655e);
            border-top: 0;
        }

        .table-clean td,
        .table-clean th {
            vertical-align: middle;
        }

        .table-borderless-info th {
            width: 150px;
            color: var(--muted, #6b655e);
            font-weight: 500;
            font-size: .85rem;
        }

        .table-borderless-info td {
            font-weight: 500;
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
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 14px;
            padding: 1.1rem 1.3rem;
            display: flex;
            align-items: center;
            gap: .9rem;
            height: 100%;
            box-shadow: 0 1px 2px rgba(28, 26, 23, .04);
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

        .empty-state {
            text-align: center;
            padding: 2.2rem 1rem;
            color: var(--muted, #6b655e);
        }

        .empty-state i {
            font-size: 1.8rem;
            opacity: .5;
            display: block;
            margin-bottom: .5rem;
        }

        .guardian-mini {
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 12px;
            padding: .8rem 1rem;
            display: flex;
            align-items: center;
            gap: .7rem;
            background: #faf9f7;
        }

        .guardian-mini .avatar-xs {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent-dark, #13233a);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .85rem;
            flex-shrink: 0;
        }
    </style>

    {{-- ===== Header: cover + avatar + ข้อมูลย่อ ===== --}}
    <div class="card mb-3 overflow-hidden">
        <div class="profile-cover">
            <div class="d-flex justify-content-end p-2 gap-2">
                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i>
                    แก้ไข</a>
                @if (!$student->user)
                    <form action="{{ route('students.create-account', $student) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-light"><i class="bi bi-key"></i> สร้างบัญชีผู้ใช้งาน</button>
                    </form>
                @endif
                <a href="{{ route('students.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i>
                    กลับ</a>
            </div>
            <div class="profile-avatar">
                @if ($student->photo_path)
                    <img src="{{ asset('storage/' . $student->photo_path) }}"
                        style="width:100%;height:100%;object-fit:cover;">
                @else
                    <span
                        style="font-weight:700;font-size:1.6rem;color:var(--accent-dark,#13233a);font-family:'Prompt',sans-serif;">{{ $student->initials() }}</span>
                @endif
            </div>
        </div>
        <div class="card-body pt-5 mt-2">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <h4 class="mb-0" style="font-family:'Prompt',sans-serif;">{{ $student->full_name }}</h4>
                <span class="text-muted small">({{ $student->student_code }})</span>
            </div>
            <div class="mt-2 d-flex flex-wrap gap-1">
                <span class="badge {{ $student->statusBadgeClass() }}">{{ $student->statusLabel() }}</span>
                @if ($student->hasOverduePayment())
                    <span class="badge text-bg-danger"><i class="bi bi-exclamation-triangle"></i> ค้างชำระ
                        ({{ $student->overduePaymentsCount() }} รายการ)</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== สถิติสรุป ===== --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--accent-soft,#e7ebf1);color:var(--accent-dark,#13233a);"><i
                        class="bi bi-wallet2"></i></div>
                <div>
                    <div class="text-muted small">เครดิตคงเหลือ</div>
                    <div class="fs-4 fw-bold" style="font-family:'Prompt',sans-serif;">
                        {{ number_format($student->creditBalance(), 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--success-soft,#e9f9ef);color:var(--success,#2f6f4e);"><i
                        class="bi bi-journal-check"></i></div>
                <div>
                    <div class="text-muted small">คอร์สที่กำลังเรียน</div>
                    <div class="fs-4 fw-bold" style="font-family:'Prompt',sans-serif;">
                        {{ $student->enrollments->where('status', 'active')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--amber-soft,#fdf1e2);color:var(--amber,#8a5a2b);"><i
                        class="bi bi-cash-coin"></i></div>
                <div>
                    <div class="text-muted small">ยอดค้างชำระ</div>
                    <div class="fs-4 fw-bold" style="font-family:'Prompt',sans-serif;">
                        {{ number_format($student->payments->whereIn('status', ['pending', 'partial', 'overdue'])->sum(fn($p) => $p->outstandingAmount()), 2) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#efe6da;color:#6b4a2b;"><i class="bi bi-mortarboard"></i></div>
                <div>
                    <div class="text-muted small">ผลสอบทั้งหมด</div>
                    <div class="fs-4 fw-bold" style="font-family:'Prompt',sans-serif;">{{ $student->examResults->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== แท็บ ===== --}}
    <ul class="nav tab-pills mb-3">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info"><i
                    class="bi bi-person-vcard"></i> ข้อมูลทั่วไป</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#guardians"><i
                    class="bi bi-people"></i> ผู้ปกครอง</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#courses"><i
                    class="bi bi-journal-bookmark"></i> คอร์สเรียน</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#payments"><i
                    class="bi bi-cash-coin"></i> การชำระเงิน</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#credits"><i
                    class="bi bi-wallet2"></i> เครดิต</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#skills"><i
                    class="bi bi-stars"></i> Skill Level</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#exams"><i
                    class="bi bi-award"></i> ผลสอบ</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#leaves"><i
                    class="bi bi-calendar-x"></i> ลา/เรียนชดเชย</button></li>
    </ul>

    <div class="tab-content">

        {{-- ข้อมูลทั่วไป --}}
        <div class="tab-pane fade show active" id="info">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-section h-100">
                        <div class="form-section-title">
                            <div class="icon-badge"><i class="bi bi-person-lines-fill"></i></div> ข้อมูลติดต่อ
                        </div>
                        <table class="table table-borderless table-borderless-info mb-0">
                            <tr>
                                <th>วันเกิด</th>
                                <td>{{ optional($student->date_of_birth)->format('d/m/Y') ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>เพศ</th>
                                <td>{{ ['male' => 'ชาย', 'female' => 'หญิง', 'other' => 'อื่นๆ'][$student->gender] ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>เบอร์โทร</th>
                                <td>{{ $student->phone ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>อีเมล</th>
                                <td>{{ $student->email ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>Line ID</th>
                                <td>{{ $student->line_id ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>ที่อยู่</th>
                                <td>{{ $student->address ?: '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-section h-100">
                        <div class="form-section-title">
                            <div class="icon-badge"><i class="bi bi-people"></i></div> ผู้ปกครองหลัก
                        </div>
                        @php $primary = $student->primaryGuardian(); @endphp
                        @if ($primary)
                            <div class="guardian-mini">
                                <div class="avatar-xs">{{ mb_substr($primary->full_name, 0, 1) }}</div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $primary->full_name }}</div>
                                    <div class="text-muted small">{{ $primary->phone ?: 'ไม่มีเบอร์โทร' }}</div>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tab"
                                    data-bs-target="#guardians">ดูทั้งหมด</button>
                            </div>
                        @else
                            <div class="text-muted small">ยังไม่มีข้อมูลผู้ปกครอง — เพิ่มได้ที่แท็บ
                                <button class="btn btn-sm btn-link p-0 align-baseline" data-bs-toggle="tab"
                                    data-bs-target="#guardians">"ผู้ปกครอง"</button>
                            </div>
                        @endif

                        @if ($student->notes)
                            <div class="mt-3 pt-3 border-top">
                                <div class="text-muted small mb-1">หมายเหตุ</div>
                                <p class="mb-0">{{ $student->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ผู้ปกครอง --}}
        <div class="tab-pane fade" id="guardians">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-person-plus"></i></div> เพิ่มผู้ปกครอง
                </div>

                <div id="guardianPicker" class="border rounded p-2 mb-2" style="background:#faf9f7;">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute"
                            style="left:.7rem; top:50%; transform:translateY(-50%); color:var(--muted,#6b655e); font-size:.85rem;"></i>
                        <input type="text" id="guardianSearchInput" class="form-control form-control-sm"
                            style="padding-left:2rem;"
                            placeholder="ค้นหาผู้ปกครองที่มีอยู่แล้ว (เช่น พี่น้องคนก่อน) หรือพิมพ์ชื่อใหม่..."
                            autocomplete="off">
                        <div id="guardianDropdown" class="list-group position-absolute w-100 shadow-sm d-none"
                            style="z-index:20; max-height:220px; overflow-y:auto; top:100%;"></div>
                    </div>
                </div>

                <form action="{{ route('students.guardians.store', $student) }}" method="POST" class="row g-2"
                    id="guardianForm">
                    @csrf
                    <input type="hidden" name="guardian_id" id="guardianIdInput">
                    <div class="col-md-4"><input type="text" name="full_name" id="guardianNameInput"
                            class="form-control form-control-sm" placeholder="ชื่อ-นามสกุล" required></div>
                    <div class="col-md-3"><input type="tel" name="phone" id="guardianPhoneInput"
                            class="form-control form-control-sm" placeholder="เบอร์โทร" inputmode="numeric"
                            maxlength="12"></div>
                    <div class="col-md-3"><input type="text" name="relation" class="form-control form-control-sm"
                            placeholder="ความสัมพันธ์ เช่น มารดา"></div>
                    <div class="col-md-1 d-grid"><button class="btn btn-sm btn-accent"><i
                                class="bi bi-plus-lg"></i></button></div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_primary" value="1"
                                id="isPrimaryCheck">
                            <label class="form-check-label small" for="isPrimaryCheck">ตั้งเป็นผู้ปกครองหลัก</label>
                        </div>
                    </div>
                </form>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-people-fill"></i></div> รายชื่อผู้ปกครอง
                </div>
                <table class="table table-sm table-clean">
                    <thead>
                        <tr>
                            <th>ชื่อ</th>
                            <th>เบอร์โทร</th>
                            <th>ความสัมพันธ์</th>
                            <th>สถานะ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->guardians as $g)
                            <tr>
                                <td class="fw-semibold">{{ $g->full_name }}</td>
                                <td>{{ $g->phone ?: '-' }}</td>
                                <td>{{ $g->pivot->relation ?: '-' }}</td>
                                <td>{!! $g->pivot->is_primary ? '<span class="badge text-bg-success">ผู้ปกครองหลัก</span>' : '' !!}</td>
                                <td>
                                    <form action="{{ route('students.guardians.destroy', [$student, $g]) }}"
                                        method="POST" onsubmit="return confirm('นำผู้ปกครองคนนี้ออกจากนักเรียนคนนี้?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state"><i class="bi bi-people"></i>ยังไม่มีข้อมูลผู้ปกครอง</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <small class="text-muted"><i class="bi bi-info-circle"></i> ผู้ปกครองที่เพิ่มที่นี่จะแสดงในเมนู
                    "จัดการผู้ปกครอง" ด้วยอัตโนมัติ</small>
            </div>
        </div>

        {{-- คอร์สเรียน --}}
        <div class="tab-pane fade" id="courses">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-plus-circle"></i></div> ลงทะเบียนคอร์สใหม่
                </div>
                <form action="{{ route('students.enrollments.store', $student) }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-5">
                        <select name="course_id" class="form-select form-select-sm" required>
                            <option value="">เลือกคอร์ส</option>
                            @foreach ($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->course_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3"><input type="date" name="enrolled_date"
                            class="form-control form-control-sm" required></div>
                    <div class="col-md-3"><input type="date" name="expected_end_date"
                            class="form-control form-control-sm" placeholder="วันคาดว่าจบ"></div>
                    <div class="col-md-1 d-grid"><button class="btn btn-sm btn-accent"><i
                                class="bi bi-plus-lg"></i></button></div>
                </form>
            </div>

            @forelse($student->enrollments as $enr)
                <div class="form-section">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold" style="font-family:'Prompt',sans-serif;">{{ $enr->course->name ?? '-' }}
                            </div>
                            <div class="text-muted small">ลงทะเบียน {{ $enr->enrolled_date->format('d/m/Y') }} · คาดว่าจบ
                                {{ optional($enr->expected_end_date)->format('d/m/Y') ?: '-' }}</div>
                        </div>
                        <span class="badge text-bg-light border">{{ $enr->statusLabel() }}</span>
                    </div>
                    <div class="row g-2 mt-2 small">
                        <div class="col-md-3"><i class="bi bi-calendar2-check text-muted"></i> ครั้งเรียนคงเหลือ:
                            <strong>{{ $enr->remainingSessions() ?? 'ไม่จำกัด' }}</strong>
                        </div>
                        <div class="col-md-3"><i class="bi bi-calendar-plus text-muted"></i> สิทธิ์ขยายเวลาคงเหลือ:
                            <strong>{{ $enr->remainingExtensionMonths() }} เดือน</strong>
                        </div>
                        <div class="col-md-3"><i class="bi bi-exclamation-circle text-muted"></i> ลาฉุกเฉินใช้ไป:
                            <strong>{{ $enr->emergencyLeaveUsed() }}/{{ $enr->emergencyLeaveQuota() }} ครั้ง</strong>
                        </div>
                        @if ($enr->status === 'active')
                            <div class="col-md-3">
                                <a href="{{ route('course-transfers.create', ['enrollment_id' => $enr->id]) }}"
                                    class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-arrow-left-right"></i>
                                    เปลี่ยนคอร์ส</a>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <form action="{{ route('students.enrollments.status', [$student, $enr]) }}" method="POST"
                                class="d-flex gap-1">
                                @csrf @method('PATCH')
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="active" @selected($enr->status == 'active')>กำลังเรียน</option>
                                    <option value="paused" @selected($enr->status == 'paused')>พักเรียน</option>
                                    <option value="completed" @selected($enr->status == 'completed')>เรียนจบแล้ว</option>
                                    <option value="cancelled" @selected($enr->status == 'cancelled')>ยกเลิก</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    @if ($enr->canExtend())
                        <form action="{{ route('students.enrollments.extend', [$student, $enr]) }}" method="POST"
                            class="row g-2 mt-2 pt-2 border-top">
                            @csrf
                            <div class="col-md-3">
                                <input type="number" name="extend_months" class="form-control form-control-sm"
                                    min="1" max="{{ $enr->remainingExtensionMonths() }}"
                                    placeholder="จำนวนเดือนที่ขอขยาย" required>
                            </div>
                            <div class="col-md-3 d-grid"><button
                                    class="btn btn-sm btn-outline-secondary">ขอขยายเวลา</button></div>
                        </form>
                    @else
                        <div class="text-muted small mt-2 pt-2 border-top"><i class="bi bi-slash-circle"></i>
                            คอร์สนี้ไม่มีสิทธิ์ขยายเวลาเพิ่มแล้ว (ตามนโยบายของคอร์ส)</div>
                    @endif
                </div>
            @empty
                <div class="form-section">
                    <div class="empty-state"><i class="bi bi-journal-bookmark"></i>ยังไม่มีคอร์สที่ลงทะเบียน</div>
                </div>
            @endforelse
        </div>

        {{-- การชำระเงิน --}}
        <div class="tab-pane fade" id="payments">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-plus-circle"></i></div> เพิ่มรายการชำระเงิน
                </div>
                <form action="{{ route('students.payments.store', $student) }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-2"><input type="text" name="invoice_no" class="form-control form-control-sm"
                            placeholder="เลขที่ใบแจ้งหนี้" required></div>
                    <div class="col-md-2"><input type="number" step="0.01" name="amount"
                            class="form-control form-control-sm" placeholder="ยอดรวม" required></div>
                    <div class="col-md-2"><input type="number" step="0.01" name="paid_amount"
                            class="form-control form-control-sm" placeholder="ชำระแล้ว"></div>
                    <div class="col-md-2"><input type="date" name="due_date" class="form-control form-control-sm"
                            placeholder="ครบกำหนด"></div>
                    <div class="col-md-2">
                        <select name="method" class="form-select form-select-sm">
                            <option value="">วิธีชำระ</option>
                            <option value="cash">เงินสด</option>
                            <option value="transfer">โอน</option>
                            <option value="credit_card">บัตรเครดิต</option>
                            <option value="other">อื่นๆ</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid"><button class="btn btn-sm btn-accent">บันทึก</button></div>
                </form>
            </div>
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-receipt"></i></div> ประวัติการชำระเงิน
                </div>
                <table class="table table-sm table-clean">
                    <thead>
                        <tr>
                            <th>เลขที่</th>
                            <th>ยอดรวม</th>
                            <th>ชำระแล้ว</th>
                            <th>คงค้าง</th>
                            <th>ครบกำหนด</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->payments as $p)
                            <tr>
                                <td>{{ $p->invoice_no }}</td>
                                <td>{{ number_format($p->amount, 2) }}</td>
                                <td>{{ number_format($p->paid_amount, 2) }}</td>
                                <td class="{{ $p->outstandingAmount() > 0 ? 'text-danger fw-semibold' : '' }}">
                                    {{ number_format($p->outstandingAmount(), 2) }}</td>
                                <td>{{ optional($p->due_date)->format('d/m/Y') ?: '-' }}</td>
                                <td><span class="badge {{ $p->statusBadgeClass() }}">{{ $p->statusLabel() }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state"><i class="bi bi-receipt"></i>ยังไม่มีประวัติการชำระเงิน</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- เครดิต --}}
        <div class="tab-pane fade" id="credits">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-plus-circle"></i></div> ทำรายการเครดิต
                </div>
                <form action="{{ route('students.credits.store', $student) }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-3">
                        <select name="type" class="form-select form-select-sm" required>
                            <option value="topup">เติมเครดิต</option>
                            <option value="use">ใช้เครดิต</option>
                            <option value="refund">คืนเครดิต</option>
                            <option value="adjustment">ปรับปรุงยอด</option>
                        </select>
                    </div>
                    <div class="col-md-3"><input type="number" step="0.01" min="0.01" name="amount"
                            class="form-control form-control-sm" placeholder="จำนวนเงิน" required></div>
                    <div class="col-md-4"><input type="text" name="reason" class="form-control form-control-sm"
                            placeholder="เหตุผล"></div>
                    <div class="col-md-2 d-grid"><button class="btn btn-sm btn-accent">บันทึก</button></div>
                </form>
            </div>
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-clock-history"></i></div> ประวัติเครดิต
                </div>
                <table class="table table-sm table-clean">
                    <thead>
                        <tr>
                            <th>วันที่</th>
                            <th>ประเภท</th>
                            <th>จำนวน</th>
                            <th>ยอดคงเหลือ</th>
                            <th>เหตุผล</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->creditTransactions as $t)
                            <tr>
                                <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $t->typeLabel() }}</td>
                                <td class="{{ $t->amount < 0 ? 'text-danger' : 'text-success' }} fw-semibold">
                                    {{ $t->amount > 0 ? '+' : '' }}{{ number_format($t->amount, 2) }}</td>
                                <td class="fw-semibold">{{ number_format($t->balance_after, 2) }}</td>
                                <td>{{ $t->reason ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state"><i class="bi bi-wallet2"></i>ยังไม่มีประวัติเครดิต</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Skill Level --}}
        <div class="tab-pane fade" id="skills">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-plus-circle"></i></div> บันทึก Skill Level
                </div>
                <form action="{{ route('students.skill-levels.store', $student) }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-4">
                        <select name="instrument_id" class="form-select form-select-sm" required>
                            <option value="">เครื่องดนตรี</option>
                            @foreach (\App\Models\Instrument::orderBy('name')->get() as $ins)
                                <option value="{{ $ins->id }}">{{ $ins->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div id="levelPicker" class="position-relative">
                            <input type="text" id="levelSearchInput" class="form-control form-control-sm"
                                placeholder="พิมพ์ค้นหาหรือเพิ่มระดับใหม่..." autocomplete="off">
                            <div id="levelDropdown" class="list-group position-absolute w-100 shadow-sm d-none"
                                style="z-index:20; max-height:200px; overflow-y:auto; top:100%; left:0;"></div>
                        </div>
                        <input type="hidden" name="level_id" id="levelIdInput" required>
                    </div>
                    <script id="levelsCatalog" type="application/json">
                    {!! \App\Models\Level::orderBy('sort_order')->get(['id','name'])->toJson() !!}
                </script>
                    <div class="col-md-3"><input type="date" name="assessed_date"
                            class="form-control form-control-sm"></div>
                    <div class="col-md-1 d-grid"><button class="btn btn-sm btn-accent"><i
                                class="bi bi-plus-lg"></i></button></div>
                </form>
            </div>
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-stars"></i></div> ระดับปัจจุบันของนักเรียน
                </div>
                <table class="table table-sm table-clean">
                    <thead>
                        <tr>
                            <th>เครื่องดนตรี</th>
                            <th>ระดับปัจจุบัน</th>
                            <th>วันที่ประเมิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->skillLevels as $sl)
                            <tr>
                                <td>{{ $sl->instrument->name ?? '-' }}</td>
                                <td><span class="badge text-bg-light border">{{ $sl->level->name ?? '-' }}</span></td>
                                <td>{{ optional($sl->assessed_date)->format('d/m/Y') ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state"><i class="bi bi-stars"></i>ยังไม่มีข้อมูล Skill Level</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ผลสอบ --}}
        <div class="tab-pane fade" id="exams">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-plus-circle"></i></div> บันทึกผลสอบ
                </div>
                <form action="{{ route('students.exam-results.store', $student) }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-2">
                        <select name="exam_board" class="form-select form-select-sm" required>
                            <option value="abrsm">ABRSM</option>
                            <option value="trinity">Trinity</option>
                        </select>
                    </div>
                    <div class="col-md-2"><input type="text" name="grade" class="form-control form-control-sm"
                            placeholder="เช่น Grade 5" required></div>
                    <div class="col-md-2"><input type="date" name="exam_date" class="form-control form-control-sm"
                            required></div>
                    <div class="col-md-2">
                        <select name="result" class="form-select form-select-sm">
                            <option value="">ผลสอบ</option>
                            <option value="distinction">Distinction</option>
                            <option value="merit">Merit</option>
                            <option value="pass">Pass</option>
                            <option value="fail">Fail</option>
                        </select>
                    </div>
                    <div class="col-md-2"><input type="text" name="score" class="form-control form-control-sm"
                            placeholder="คะแนน"></div>
                    <div class="col-md-2 d-grid"><button class="btn btn-sm btn-accent">บันทึก</button></div>
                </form>
            </div>
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-award"></i></div> ประวัติผลสอบ
                </div>
                <table class="table table-sm table-clean">
                    <thead>
                        <tr>
                            <th>สถาบัน</th>
                            <th>เกรด</th>
                            <th>วันสอบ</th>
                            <th>ผลสอบ</th>
                            <th>คะแนน</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->examResults as $ex)
                            @php
                                $resultBadge =
                                    [
                                        'distinction' => 'text-bg-success',
                                        'merit' => 'text-bg-primary',
                                        'pass' => 'text-bg-light',
                                        'fail' => 'text-bg-danger',
                                    ][$ex->result] ?? 'text-bg-light';
                            @endphp
                            <tr>
                                <td>{{ $ex->examBoardLabel() }}</td>
                                <td class="fw-semibold">{{ $ex->grade }}</td>
                                <td>{{ $ex->exam_date->format('d/m/Y') }}</td>
                                <td><span class="badge {{ $resultBadge }} border">{{ $ex->resultLabel() }}</span></td>
                                <td>{{ $ex->score ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state"><i class="bi bi-award"></i>ยังไม่มีข้อมูลผลสอบ</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ลา/เรียนชดเชย --}}
        <div class="tab-pane fade" id="leaves">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-plus-circle"></i></div> แจ้งลาเรียน
                </div>
                <form action="{{ route('students.leaves.store', $student) }}" method="POST" class="row g-2"
                    id="leaveForm">
                    @csrf
                    <div class="col-md-3">
                        <select name="enrollment_id" id="leaveEnrollmentSelect" class="form-select form-select-sm"
                            required>
                            <option value="">เลือกคอร์ส</option>
                            @foreach ($student->enrollments->where('status', 'active') as $enr)
                                <option value="{{ $enr->id }}"
                                    data-allow-makeup="{{ $enr->course->allow_makeup_class ? '1' : '0' }}">
                                    {{ $enr->course->name ?? '-' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="leave_type" id="leaveTypeSelect" class="form-select form-select-sm" required>
                            <option value="normal">ลาปกติ (ขอชดเชย)</option>
                            <option value="emergency">ลาฉุกเฉิน</option>
                            <option value="no_makeup">ลาแบบไม่ชดเชย</option>
                        </select>
                    </div>
                    <div class="col-md-2"><input type="date" name="leave_date" class="form-control form-control-sm"
                            required></div>
                    <div class="col-md-3"><input type="text" name="reason" class="form-control form-control-sm"
                            placeholder="เหตุผล"></div>
                    <div class="col-12 d-none" id="makeupFieldsBox">
                        <div class="alert alert-light border mt-2">
                            <strong><i class="bi bi-calendar-plus"></i> เลือกวันเรียนชดเชย
                                (บังคับกรอกสำหรับลาปกติ)</strong>
                            <div class="row g-2 mt-1">
                                <div class="col-md-4">
                                    <label class="form-label small">อาจารย์ผู้สอนชดเชย</label>
                                    <select name="makeup_teacher_id" id="makeupTeacherSelect"
                                        class="form-select form-select-sm">
                                        <option value="">เลือกอาจารย์</option>
                                        @foreach (\App\Models\Teacher::where('is_active', true)->orderBy('full_name')->get() as $t)
                                            <option value="{{ $t->id }}">{{ $t->nickname ?: $t->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">ห้องเรียน</label>
                                    <select name="makeup_room_id" class="form-select form-select-sm">
                                        <option value="">ไม่ระบุ (ออนไลน์)</option>
                                        @foreach (\App\Models\Room::where('is_active', true)->orderBy('name')->get() as $r)
                                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">รูปแบบการเรียน</label>
                                    <select name="makeup_delivery_mode" class="form-select form-select-sm">
                                        <option value="onsite">ที่โรงเรียน</option>
                                        <option value="online">ออนไลน์</option>
                                        <option value="hybrid">ไฮบริด</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">วันที่เรียนชดเชย</label>
                                    <input type="date" name="makeup_date" id="makeupDateInput"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">เวลาเริ่ม</label>
                                    <input type="time" name="makeup_start_time" id="makeupStartInput"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">เวลาสิ้นสุด</label>
                                    <input type="time" name="makeup_end_time" id="makeupEndInput"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                            <div id="makeupConflictBox" class="alert alert-danger small mt-2 mb-0 d-none"></div>
                        </div>
                    </div>
                    <div class="col-md-2 d-grid"><button class="btn btn-sm btn-accent">ส่งคำขอ</button></div>
                    <div class="col-12">
                        <small class="text-muted" id="leaveHint"><i class="bi bi-info-circle"></i> ลาปกติ/ลาไม่ชดเชย
                            ต้องแจ้งล่วงหน้าอย่างน้อย {{ config('leave.normal_advance_notice_hours', 24) }} ชั่วโมง —
                            ลาฉุกเฉินแจ้งกะทันหันได้ แต่จำกัดสิทธิ์ตามที่คอร์สกำหนด</small>
                    </div>
                </form>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-calendar-x"></i></div> ประวัติการลา / คำขอลา
                </div>
                <table class="table table-sm table-clean">
                    <thead>
                        <tr>
                            <th>คอร์ส</th>
                            <th>ประเภท</th>
                            <th>วันที่ลา</th>
                            <th>สถานะคำขอ</th>
                            <th>สถานะชดเชย</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->leaves->sortByDesc('created_at') as $lv)
                            <tr>
                                <td>{{ $lv->enrollment->course->name ?? '-' }}</td>
                                <td><span
                                        class="badge {{ $lv->leave_type == 'emergency' ? 'text-bg-danger' : 'text-bg-light border' }}">{{ $lv->leaveTypeLabel() }}</span>
                                </td>
                                <td>{{ $lv->leave_date->format('d/m/Y') }}</td>
                                <td><span class="badge {{ $lv->statusBadgeClass() }}">{{ $lv->statusLabel() }}</span>
                                </td>
                                <td>{{ $lv->makeupStatusLabel() }}</td>
                                <td>
                                    @if ($lv->status === 'pending')
                                        <form action="{{ route('students.leaves.approve', [$student, $lv]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success" title="อนุมัติ"><i
                                                    class="bi bi-check-lg"></i></button>
                                        </form>
                                        <form action="{{ route('students.leaves.reject', [$student, $lv]) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('ปฏิเสธคำขอลานี้?')">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" title="ปฏิเสธ"><i
                                                    class="bi bi-x-lg"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state"><i class="bi bi-calendar-x"></i>ยังไม่มีประวัติการลา</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


    </div>

    <script>
        // ===== ค้นหา + เพิ่มผู้ปกครองแบบ inline (search existing หรือสร้างใหม่) =====
        (function() {
            const searchInput = document.getElementById('guardianSearchInput');
            const dropdown = document.getElementById('guardianDropdown');
            const guardianIdInput = document.getElementById('guardianIdInput');
            const nameInput = document.getElementById('guardianNameInput');
            const phoneInput = document.getElementById('guardianPhoneInput');
            let debounceTimer;

            searchInput.addEventListener('input', function() {
                guardianIdInput.value = '';
                nameInput.value = this.value;
                clearTimeout(debounceTimer);
                const q = this.value.trim();
                if (q.length < 2) {
                    dropdown.classList.add('d-none');
                    return;
                }

                debounceTimer = setTimeout(async () => {
                    const res = await fetch(
                        `{{ route('guardians.search') }}?q=${encodeURIComponent(q)}`);
                    const results = await res.json();
                    dropdown.innerHTML = '';
                    if (results.length === 0) {
                        dropdown.classList.add('d-none');
                        return;
                    }

                    results.forEach(g => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className =
                            'list-group-item list-group-item-action py-1 px-2 small';
                        item.textContent =
                            `${g.full_name}${g.phone ? ' — ' + g.phone : ''}`;
                        item.addEventListener('click', () => {
                            guardianIdInput.value = g.id;
                            nameInput.value = g.full_name;
                            phoneInput.value = g.phone || '';
                            searchInput.value = g.full_name;
                            dropdown.classList.add('d-none');
                        });
                        dropdown.appendChild(item);
                    });
                    dropdown.classList.remove('d-none');
                }, 300);
            });

            document.addEventListener('click', e => {
                if (!document.getElementById('guardianPicker').contains(e.target)) dropdown.classList.add(
                    'd-none');
            });
        })();

        // ===== ระดับ (Skill Level): ค้นหา + เพิ่มใหม่แบบ inline =====
        (function() {
            const catalogEl = document.getElementById('levelsCatalog');
            if (!catalogEl) return;
            let catalog = JSON.parse(catalogEl.textContent);

            const searchInput = document.getElementById('levelSearchInput');
            const dropdown = document.getElementById('levelDropdown');
            const hiddenInput = document.getElementById('levelIdInput');

            function renderDropdown(query) {
                const q = query.trim().toLowerCase();
                const matches = catalog.filter(l => l.name.toLowerCase().includes(q)).slice(0, 8);
                const exactExists = catalog.some(l => l.name.toLowerCase() === q);

                dropdown.innerHTML = '';
                if (q === '' && matches.length === 0) {
                    dropdown.classList.add('d-none');
                    return;
                }

                matches.forEach(lv => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'list-group-item list-group-item-action py-1 px-2 small';
                    item.textContent = lv.name;
                    item.addEventListener('click', () => selectLevel(lv));
                    dropdown.appendChild(item);
                });

                if (q !== '' && !exactExists) {
                    const addItem = document.createElement('button');
                    addItem.type = 'button';
                    addItem.className = 'list-group-item list-group-item-action py-1 px-2 small fw-semibold';
                    addItem.style.color = 'var(--accent-dark,#13233a)';
                    addItem.textContent = `+ เพิ่ม "${query.trim()}" เป็นระดับใหม่`;
                    addItem.addEventListener('click', () => addNewLevel(query.trim()));
                    dropdown.appendChild(addItem);
                }

                dropdown.classList.toggle('d-none', matches.length === 0 && (q === '' || exactExists));
            }

            function selectLevel(lv) {
                hiddenInput.value = lv.id;
                searchInput.value = lv.name;
                dropdown.classList.add('d-none');
            }

            async function addNewLevel(name) {
                try {
                    const res = await fetch('{{ route('levels.store') }}', {
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
                        alert(body.errors?.name?.[0] || 'เพิ่มระดับไม่สำเร็จ');
                        return;
                    }
                    catalog.push(body);
                    selectLevel(body);
                } catch (e) {
                    alert('เกิดข้อผิดพลาด กรุณาลองใหม่');
                }
            }

            searchInput.addEventListener('input', () => renderDropdown(searchInput.value));
            searchInput.addEventListener('focus', () => renderDropdown(searchInput.value));
            document.addEventListener('click', e => {
                if (!document.getElementById('levelPicker').contains(e.target)) dropdown.classList.add(
                    'd-none');
            });
        })();


        (function() {
            const enrollmentSelect = document.getElementById('leaveEnrollmentSelect');
            const typeSelect = document.getElementById('leaveTypeSelect');
            const hint = document.getElementById('leaveHint');
            const makeupBox = document.getElementById('makeupFieldsBox');
            const makeupTeacher = document.getElementById('makeupTeacherSelect');
            const makeupDate = document.getElementById('makeupDateInput');
            const makeupStart = document.getElementById('makeupStartInput');
            const makeupEnd = document.getElementById('makeupEndInput');
            const conflictBox = document.getElementById('makeupConflictBox');
            const submitBtn = document.querySelector('#leaveForm button[type=submit]');
            const studentId = {{ $student->id }};

            function toggleMakeupBox() {
                const isNormal = typeSelect.value === 'normal';
                makeupBox.classList.toggle('d-none', !isNormal);
                [makeupTeacher, makeupDate, makeupStart, makeupEnd].forEach(el => el.required = isNormal);
            }

            function checkAllowMakeup() {
                const opt = enrollmentSelect.options[enrollmentSelect.selectedIndex];
                const allow = opt?.dataset.allowMakeup === '1';
                if (!allow && typeSelect.value === 'normal') {
                    typeSelect.value = 'no_makeup';
                    hint.innerHTML =
                        '<i class="bi bi-exclamation-circle text-warning"></i> คอร์สนี้ไม่เปิดสิทธิ์เรียนชดเชย ระบบเปลี่ยนเป็น "ลาแบบไม่ชดเชย" ให้อัตโนมัติ';
                }
                typeSelect.querySelector('option[value=normal]').disabled = !allow;
                toggleMakeupBox();
            }

            async function checkMakeupConflict() {
                if (typeSelect.value !== 'normal') {
                    conflictBox.classList.add('d-none');
                    if (submitBtn) submitBtn.disabled = false;
                    return;
                }
                if (!makeupTeacher.value || !makeupDate.value || !makeupStart.value || !makeupEnd.value) return;

                const params = new URLSearchParams({
                    student_id: studentId,
                    teacher_id: makeupTeacher.value,
                    date: makeupDate.value,
                    start_time: makeupStart.value,
                    end_time: makeupEnd.value,
                });
                try {
                    const res = await fetch(
                        `{{ route('makeup-requests.check-conflict') }}?${params.toString()}`);
                    const data = await res.json();
                    if (data.conflicts && data.conflicts.length > 0) {
                        conflictBox.innerHTML = '<i class="bi bi-exclamation-triangle"></i> ' + data.conflicts
                            .join('<br>');
                        conflictBox.classList.remove('d-none');
                        if (submitBtn) submitBtn.disabled = true;
                    } else {
                        conflictBox.classList.add('d-none');
                        if (submitBtn) submitBtn.disabled = false;
                    }
                } catch (e) {
                    console.error(e);
                }
            }

            enrollmentSelect.addEventListener('change', checkAllowMakeup);
            typeSelect.addEventListener('change', () => {
                toggleMakeupBox();
                checkMakeupConflict();
            });
            [makeupTeacher, makeupDate, makeupStart, makeupEnd].forEach(el => el.addEventListener('change',
                checkMakeupConflict));
        })();
    </script>
@endsection

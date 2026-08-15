@extends('layouts.app')
@section('title', 'สมัครเรียนคอร์ส')

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
            margin-bottom: .4rem;
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
        }

        .form-section-desc {
            font-size: .82rem;
            color: var(--muted, #6b655e);
            margin-bottom: 1rem;
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

        .price-box {
            background: var(--accent-soft, #e7ebf1);
            border-radius: 14px;
            padding: 1.2rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            font-size: .88rem;
            padding: .3rem 0;
        }

        .price-row.total {
            font-weight: 700;
            font-size: 1.15rem;
            border-top: 1px solid rgba(19, 35, 58, .15);
            margin-top: .4rem;
            padding-top: .7rem;
            color: var(--accent-dark, #13233a);
        }

        .capacity-hint {
            font-size: .82rem;
            padding: .5rem .8rem;
            border-radius: 8px;
            margin-top: .4rem;
        }

        .capacity-ok {
            background: var(--success-soft, #e9f9ef);
            color: var(--success, #2f6f4e);
        }

        .capacity-full {
            background: #fbeae7;
            color: #b3392c;
        }

        /* ===== แท็บเลือกนักเรียน ===== */
        .picker-tabs {
            display: flex;
            gap: .6rem;
            margin-bottom: 1rem;
        }

        .picker-tab {
            flex: 1;
            border: 1.5px solid var(--border, #e4e1dc);
            border-radius: 12px;
            padding: .8rem;
            text-align: center;
            font-weight: 600;
            cursor: pointer;
            transition: .15s;
        }

        .picker-tab.active {
            border-color: var(--accent, #1f3350);
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent-dark, #13233a);
        }

        .student-card {
            border: 1.5px solid var(--border, #e4e1dc);
            border-radius: 12px;
            padding: .9rem 1.1rem;
            display: flex;
            align-items: center;
            gap: .8rem;
            cursor: pointer;
            transition: .15s;
            margin-bottom: .6rem;
        }

        .student-card:hover {
            border-color: #c9c4bb;
        }

        .student-card.active {
            border-color: var(--accent, #1f3350);
            border-width: 2px;
            background: var(--accent-soft, #e7ebf1);
        }

        .student-card .avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #ece9e4;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--accent-dark, #13233a);
            flex-shrink: 0;
        }

        .student-card .info {
            flex: 1;
        }

        .student-card .name {
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
        }

        .student-card .meta {
            font-size: .8rem;
            color: var(--muted, #6b655e);
        }

        .student-card .check {
            display: none;
            color: var(--accent, #1f3350);
            font-size: 1.2rem;
        }

        .student-card.active .check {
            display: block;
        }

        /* ===== การ์ดคอร์ส ===== */
        .course-card {
            border: 1.5px solid var(--border, #e4e1dc);
            border-radius: 14px;
            overflow: hidden;
            cursor: pointer;
            transition: .15s;
            height: 100%;
        }

        .course-card:hover {
            border-color: #c9c4bb;
            box-shadow: 0 3px 12px rgba(28, 26, 23, .08);
        }

        .course-card.active {
            border-color: var(--accent, #1f3350);
            border-width: 2px;
        }

        .course-card.disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        .course-cover {
            height: 110px;
            position: relative;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: .6rem;
        }

        .course-cover .badge-discount {
            background: #fff;
            color: #8a5a2b;
            font-weight: 700;
            font-size: .72rem;
        }

        .course-cover .badge-instrument {
            background: rgba(255, 255, 255, .9);
            font-size: .72rem;
        }

        .course-body {
            padding: .9rem 1rem;
        }

        .course-body .code {
            font-size: .72rem;
            color: var(--muted, #6b655e);
        }

        .course-body .name {
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            margin: .15rem 0 .5rem;
        }

        .course-body .price-remaining {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .course-body .price {
            font-weight: 700;
            color: var(--accent-dark, #13233a);
        }

        .course-body .remaining {
            font-size: .78rem;
        }

        .remaining.ok {
            color: var(--success, #2f6f4e);
        }

        .remaining.full {
            color: #b3392c;
            font-weight: 600;
        }

        /* กล่องแจ้งเตือนคอร์สแบบพิเศษ */
        .special-schedule-box {
            border-radius: 12px;
            padding: .9rem 1.1rem;
            display: flex;
            gap: .6rem;
            align-items: flex-start;
            background: var(--amber-soft, #fdf1e2);
            color: #8a5a2b;
            margin-top: .75rem;
        }

        .special-schedule-box i {
            font-size: 1rem;
            margin-top: .1rem;
        }
    </style>

    <div class="breadcrumb-sm">งานขาย <i class="bi bi-chevron-right small"></i> สมัครเรียนคอร์ส</div>
    <h1 class="page-title mb-3"><i class="bi bi-cart-plus"></i> สมัครเรียนคอร์ส</h1>

    <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
        @csrf

        {{-- 1. เลือกนักเรียน --}}
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-person"></i></div> เลือกนักเรียน <span class="step-no">ขั้นตอน
                    1</span>
            </div>
            <div class="form-section-desc">เลือกนักเรียนเดิม หรือเพิ่มนักเรียนใหม่</div>

            <div class="picker-tabs">
                <div class="picker-tab active" id="tabExisting">เลือกนักเรียนเดิม</div>
                <div class="picker-tab" id="tabNew"><i class="bi bi-plus-lg"></i> เพิ่มนักเรียนใหม่</div>
            </div>

            <div id="existingStudentBox">
                <input type="text" id="studentFilterInput" class="form-control form-control-sm mb-2"
                    placeholder="ค้นหาชื่อ/รหัสนักเรียน...">
                <div id="studentCardList" style="max-height:420px; overflow-y:auto;"></div>
            </div>

            <div id="newStudentBox" class="d-none">
                <div class="row g-2">
                    <div class="col-md-4"><input type="text" id="qsName" class="form-control form-control-sm"
                            placeholder="ชื่อ-นามสกุล *"></div>
                    <div class="col-md-3"><input type="text" id="qsNickname" class="form-control form-control-sm"
                            placeholder="ชื่อเล่น"></div>
                    <div class="col-md-3"><input type="tel" id="qsPhone" class="form-control form-control-sm"
                            placeholder="เบอร์โทร"></div>
                    <div class="col-md-2"><input type="date" id="qsDob" class="form-control form-control-sm"></div>
                    <div class="col-12"><button type="button" id="qsSubmit" class="btn btn-sm btn-accent"><i
                                class="bi bi-check-lg"></i> เพิ่มและเลือกนักเรียนนี้</button></div>
                </div>
                <div id="qsError" class="text-danger small mt-2 d-none"></div>
            </div>

            <input type="hidden" name="student_id" id="studentIdInput" value="{{ $preselectedStudent->id ?? '' }}"
                required>
            <div id="studentSelectedInfo" class="mt-2 small fw-semibold text-success"></div>
        </div>

        {{-- 2. เลือกคอร์ส --}}
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-journal-bookmark"></i></div> เลือกคอร์สเรียน (แพ็กเกจ) <span
                    class="step-no">ขั้นตอน 2</span>
            </div>

            <div class="position-relative mb-3">
                <i class="bi bi-search position-absolute"
                    style="left:.9rem; top:50%; transform:translateY(-50%); color:var(--muted,#6b655e);"></i>
                <input type="text" id="courseFilterInput" class="form-control" style="padding-left:2.3rem;"
                    placeholder="ค้นหาชื่อคอร์ส / รหัสคอร์ส / เครื่องดนตรี...">
            </div>

            <input type="hidden" name="course_id" id="courseIdInput" required>
            <div class="row g-3" id="courseCardGrid">
                @php
                    $covers = [
                        'linear-gradient(135deg,#3a2a1c,#8a5a2b)',
                        'linear-gradient(135deg,#1c1a17,#3d3833)',
                        'linear-gradient(135deg,#1f3350,#3a5578)',
                        'linear-gradient(135deg,#2e2a26,#55504a)',
                    ];
                @endphp
                @foreach ($courseCards as $i => $card)
                    <div class="col-md-4">
                        <div class="course-card {{ $card['remaining'] === 0 ? 'disabled' : '' }}"
                            data-id="{{ $card['id'] }}" data-full="{{ $card['remaining'] === 0 ? '1' : '0' }}"
                            data-search="{{ mb_strtolower($card['name'] . ' ' . $card['code'] . ' ' . $card['instrument']) }}">
                            <div class="course-cover" style="background:{{ $covers[$i % count($covers)] }};">
                                @if ($card['discount_label'])
                                    <span class="badge badge-discount"><i class="bi bi-tag"></i> ลด
                                        {{ $card['discount_label'] }}</span>
                                @else
                                    <span></span>
                                @endif
                                @if ($card['instrument'])
                                    <span class="badge badge-instrument">{{ $card['instrument'] }}</span>
                                @endif
                            </div>
                            <div class="course-body">
                                <div class="code">{{ $card['code'] }}</div>
                                <div class="name">{{ $card['name'] }}</div>
                                <div class="price-remaining">
                                    <span class="price">฿{{ number_format($card['price'], 0) }}</span>
                                    @if ($card['remaining'] === null)
                                        <span class="remaining ok">ไม่จำกัด</span>
                                    @elseif($card['remaining'] === 0)
                                        <span class="remaining full">เต็มแล้ว</span>
                                    @else
                                        <span class="remaining ok">เหลือ {{ $card['remaining'] }} ที่</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="courseNoResult" class="text-muted small text-center py-4 d-none"><i
                    class="bi bi-search d-block fs-4 mb-1" style="opacity:.5;"></i>ไม่พบคอร์สที่ตรงกับการค้นหา</div>
            <div id="capacityHintBox" class="capacity-hint d-none"></div>
        </div>

        {{-- 3. อาจารย์ / สาขา / รูปแบบเรียน / วันเวลา --}}
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-sliders"></i></div> รายละเอียดการเรียน <span
                    class="step-no">ขั้นตอน 3</span>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">เลือกอาจารย์</label>
                    <select name="teacher_id" id="teacherSelect" class="form-select">
                        <option value="">ให้ทางโรงเรียนจัดให้</option>
                    </select>
                    <small class="text-muted" id="teacherHint">เลือกคอร์สก่อน
                        ระบบจะแสดงอาจารย์ที่สอนคอร์สนี้ได้ให้อัตโนมัติ</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">เลือกสาขาเรียน</label>
                    <input type="text" name="branch" class="form-control" list="branchList"
                        placeholder="เช่น Cloud 11">
                    <datalist id="branchList">
                        @foreach (\App\Models\Teacher::whereNotNull('branch')->distinct()->pluck('branch') as $b)
                            <option value="{{ $b }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="col-md-4">
                    <label class="form-label">รูปแบบการเรียน</label>
                    <select name="delivery_mode" id="deliveryModeSelect" class="form-select">
                        <option value="onsite">ที่โรงเรียน</option>
                        <option value="online">ออนไลน์</option>
                        <option value="hybrid">ไฮบริด</option>
                    </select>
                </div>
            </div>

            {{-- คอร์สแบบปกติ: เลือกวันที่สะดวกเรียนได้อิสระตามวันในสัปดาห์ --}}
            <div class="row g-3 mt-1" id="regularScheduleBox">
                <div class="col-md-4">
                    <label class="form-label">วันที่สะดวกเรียน</label>
                    <select name="preferred_day_of_week" id="preferredDaySelect" class="form-select">
                        <option value="">ไม่ระบุ</option>
                        @foreach (\App\Models\TeacherAvailability::dayLabels() as $dow => $label)
                            <option value="{{ $dow }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted" id="dayHint">เลือกอาจารย์ก่อน
                        ระบบจะกรองเฉพาะวันที่อาจารย์ว่างจริงให้อัตโนมัติ</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">เวลาเริ่ม</label>
                    <input type="time" name="preferred_start_time" id="preferredStartTime" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">เวลาสิ้นสุด</label>
                    <input type="time" name="preferred_end_time" id="preferredEndTime" class="form-control">
                </div>
            </div>

            {{-- คอร์สแบบพิเศษ: วันเวลากำหนดตายตัวจากตัวคอร์สอยู่แล้ว แสดงเป็นข้อมูลอย่างเดียว ไม่ต้องเลือกซ้ำ --}}
            <div class="special-schedule-box d-none" id="specialScheduleBox">
                <i class="bi bi-calendar-range"></i>
                <div>
                    คอร์สนี้เป็น<strong>แบบพิเศษ</strong> กำหนดวันเรียนไว้ตายตัวแล้ว: <strong
                        id="specialDateRangeText">-</strong>
                    <div class="small mt-1">รวม <strong id="specialDaysCountText">-</strong> วัน วันละ <strong
                            id="specialHoursText">-</strong> ชั่วโมง — ไม่ต้องเลือกวันที่สะดวกเรียนซ้ำ
                        ระบบจะให้จัดตารางเรียนแต่ละวันในเมนู "จัดตารางเรียน" ภายหลัง</div>
                </div>
            </div>
        </div>

        {{-- 4. สรุปราคา --}}
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-calculator"></i></div> คำนวณค่าเรียน <span
                    class="step-no">ขั้นตอน 4</span>
            </div>
            <div class="price-box">
                <div class="price-row"><span>ราคาคอร์ส (ก่อน VAT)</span><span id="priceSubtotal">0.00</span></div>
                <div class="price-row"><span>VAT 7%</span><span id="priceVat">0.00</span></div>
                <div class="price-row total"><span>ยอดชำระทั้งหมด</span><span id="priceTotal">0.00</span></div>
            </div>
            <small class="text-muted d-block mt-2"><i class="bi bi-info-circle"></i>
                ราคานี้ยังไม่รวมส่วนลดคูปอง/แต้ม/เครดิต — ปรับยอดสุทธิได้อีกครั้งในหน้าสรุปก่อนชำระเงิน</small>
        </div>

        {{-- 5. ใบเสร็จ/ใบกำกับภาษี (เลือกได้ว่าต้องการหรือไม่) --}}
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-receipt"></i></div> ใบเสร็จ / ใบกำกับภาษี
                <span class="step-no">ขั้นตอน 5</span>
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" role="switch" id="wantsInvoiceCheck" checked>
                <label class="form-check-label fw-semibold">ต้องการใบเสร็จ/ใบกำกับภาษี</label>
            </div>

            <div id="invoiceFieldsBox" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">ประเภทเอกสาร *</label>
                    <select name="invoice_type" id="invoiceType" class="form-select">
                        <option value="receipt">ใบเสร็จรับเงิน</option>
                        <option value="tax_invoice">ใบกำกับภาษีเต็มรูป</option>
                        <option value="none" hidden>ไม่ต้องการเอกสาร</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_company" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_company"
                            id="isCompanyCheck" value="1">
                        <label class="form-check-label">ออกในนามนิติบุคคล</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ชื่อผู้ซื้อ/บริษัท *</label>
                    <input type="text" name="buyer_name" id="buyerNameInput" class="form-control" maxlength="150">
                </div>
                <div class="col-md-6 d-none" id="taxIdBox">
                    <label class="form-label">เลขผู้เสียภาษี (13 หลัก) *</label>
                    <input type="text" name="buyer_tax_id" id="buyerTaxId" class="form-control" maxlength="13"
                        inputmode="numeric">
                </div>
                <div class="col-md-6">
                    <label class="form-label">เบอร์โทร</label>
                    <input type="text" name="buyer_phone" class="form-control" maxlength="20">
                </div>
                <div class="col-12">
                    <label class="form-label">ที่อยู่สำหรับออกเอกสาร</label>
                    <textarea name="buyer_address" class="form-control" rows="2" maxlength="500"></textarea>
                </div>
            </div>
            <p class="text-muted small mb-0 d-none" id="noInvoiceNote"><i class="bi bi-info-circle"></i>
                จะไม่มีการออกใบเสร็จ/ใบกำกับภาษีสำหรับคำสั่งนี้</p>
        </div>

        {{-- 6. หมายเหตุ --}}
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-journal-text"></i></div> หมายเหตุ <span
                    class="step-no">ขั้นตอนสุดท้าย</span>
            </div>
            <textarea name="notes" class="form-control" rows="2" maxlength="1000"
                placeholder="ข้อมูลเพิ่มเติม (ถ้ามี)"></textarea>
        </div>

        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-arrow-right-circle"></i> ไปหน้าสรุปข้อมูลก่อนชำระเงิน</button>
            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>

    <script id="studentsCatalog" type="application/json">{!! json_encode($students) !!}</script>
    <script id="teachersAvailabilityData" type="application/json">{!! json_encode($teachersAvailability) !!}</script>
    <script id="dayLabelsData" type="application/json">{!! json_encode(\App\Models\TeacherAvailability::dayLabels()) !!}</script>
    <script id="courseCardsData" type="application/json">
{!! json_encode(
    $courseCards->map(function ($c) use ($courses) {
        $course = $courses->firstWhere('id', $c['id']);
        return array_merge($c, [
            'structure_type' => $course->structure_type ?? 'regular',
            'course_start'   => optional($course->course_start_date)->format('d/m/Y'),
            'course_end'     => optional($course->course_end_date)->format('d/m/Y'),
            'days_count'     => $course->days_count,
            'hours_per_day'  => $course->hours_per_day,
        ]);
    })->keyBy('id')
) !!}
</script>

    <script>
        (function() {
            // ============ 1. เลือกนักเรียน ============
            let students = JSON.parse(document.getElementById('studentsCatalog').textContent);
            const studentListBox = document.getElementById('studentCardList');
            const studentFilter = document.getElementById('studentFilterInput');
            const studentIdInput = document.getElementById('studentIdInput');
            const studentInfo = document.getElementById('studentSelectedInfo');
            const preselectedId = "{{ $preselectedStudent->id ?? '' }}";

            function renderStudentCards(list) {
                studentListBox.innerHTML = '';
                if (list.length === 0) {
                    studentListBox.innerHTML =
                        '<p class="text-muted small text-center py-3">ไม่พบนักเรียนที่ตรงกับการค้นหา</p>';
                    return;
                }
                list.forEach(s => {
                    const card = document.createElement('div');
                    card.className = 'student-card' + (String(s.id) === String(studentIdInput.value) ?
                        ' active' : '');
                    card.innerHTML = `
                <div class="avatar">${(s.nickname || s.name).charAt(0)}</div>
                <div class="info">
                    <div class="name">${s.name}</div>
                    <div class="meta">${s.code} ${s.nickname ? '· ' + s.nickname : ''} ${s.age ? '· อายุ ' + s.age + ' ปี' : ''}</div>
                </div>
                ${s.instrument ? `<span class="badge text-bg-light border">${s.instrument}</span>` : ''}
                <i class="bi bi-check-circle-fill check"></i>
            `;
                    card.addEventListener('click', () => selectStudent(s));
                    studentListBox.appendChild(card);
                });
            }

            function selectStudent(s) {
                studentIdInput.value = s.id;
                studentInfo.innerHTML = `<i class="bi bi-check-circle"></i> เลือกแล้ว: ${s.name} (${s.code})`;
                renderStudentCards(currentFilteredList());
            }

            function currentFilteredList() {
                const q = studentFilter.value.trim().toLowerCase();
                if (!q) return students;
                return students.filter(s => s.name.toLowerCase().includes(q) || s.code.toLowerCase().includes(q) || (s
                    .nickname || '').toLowerCase().includes(q));
            }

            studentFilter.addEventListener('input', () => renderStudentCards(currentFilteredList()));
            renderStudentCards(students);
            if (preselectedId) {
                const pre = students.find(s => String(s.id) === String(preselectedId));
                if (pre) selectStudent(pre);
            }

            const tabExisting = document.getElementById('tabExisting');
            const tabNew = document.getElementById('tabNew');
            const existingBox = document.getElementById('existingStudentBox');
            const newBox = document.getElementById('newStudentBox');

            tabExisting.addEventListener('click', () => {
                tabExisting.classList.add('active');
                tabNew.classList.remove('active');
                existingBox.classList.remove('d-none');
                newBox.classList.add('d-none');
            });
            tabNew.addEventListener('click', () => {
                tabNew.classList.add('active');
                tabExisting.classList.remove('active');
                newBox.classList.remove('d-none');
                existingBox.classList.add('d-none');
            });

            document.getElementById('qsSubmit').addEventListener('click', async function() {
                const errBox = document.getElementById('qsError');
                errBox.classList.add('d-none');
                const name = document.getElementById('qsName').value.trim();
                if (!name) {
                    errBox.textContent = 'กรุณากรอกชื่อ-นามสกุล';
                    errBox.classList.remove('d-none');
                    return;
                }

                try {
                    const res = await fetch('{{ route('sales.quick-student') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                        },
                        body: JSON.stringify({
                            full_name: name,
                            nickname: document.getElementById('qsNickname').value.trim() ||
                                null,
                            phone: document.getElementById('qsPhone').value.trim() || null,
                            date_of_birth: document.getElementById('qsDob').value || null,
                        }),
                    });
                    const body = await res.json();
                    if (!res.ok) {
                        errBox.textContent = body.message || 'เพิ่มนักเรียนไม่สำเร็จ';
                        errBox.classList.remove('d-none');
                        return;
                    }

                    students.unshift(body);
                    selectStudent(body);
                    tabExisting.click();
                    studentFilter.value = '';
                } catch (e) {
                    errBox.textContent = 'เกิดข้อผิดพลาด กรุณาลองใหม่';
                    errBox.classList.remove('d-none');
                }
            });

            // ============ 2. เลือกคอร์ส (การ์ด + ค้นหา) ============
            const courseIdInput = document.getElementById('courseIdInput');
            const capacityBox = document.getElementById('capacityHintBox');
            const courseCardsData = JSON.parse(document.getElementById('courseCardsData').textContent);

            document.querySelectorAll('.course-card').forEach(card => {
                card.addEventListener('click', function() {
                    if (this.dataset.full === '1') {
                        capacityBox.classList.remove('d-none');
                        capacityBox.className = 'capacity-hint capacity-full';
                        capacityBox.innerHTML =
                            '<i class="bi bi-exclamation-triangle"></i> คอร์สนี้เต็มแล้ว ไม่สามารถเลือกได้';
                        return;
                    }
                    document.querySelectorAll('.course-card').forEach(c => c.classList.remove(
                    'active'));
                    this.classList.add('active');
                    courseIdInput.value = this.dataset.id;
                    updatePrice();
                    updateTeachers();
                    checkCapacity();
                    toggleScheduleBoxByStructureType();
                });
            });

            const courseFilterInput = document.getElementById('courseFilterInput');
            const courseNoResult = document.getElementById('courseNoResult');
            const courseCols = document.querySelectorAll('#courseCardGrid > .col-md-4');

            courseFilterInput.addEventListener('input', function() {
                const q = this.value.trim().toLowerCase();
                let visibleCount = 0;

                courseCols.forEach(col => {
                    const card = col.querySelector('.course-card');
                    const matches = q === '' || card.dataset.search.includes(q);
                    col.style.display = matches ? '' : 'none';
                    if (matches) visibleCount++;
                });

                courseNoResult.classList.toggle('d-none', visibleCount > 0);
            });

            // ============ 3. อาจารย์ (ตามคอร์ส) + วันว่างจริง + toggle ตามประเภทคอร์ส ============
            const teacherSelect = document.getElementById('teacherSelect');
            const teacherHint = document.getElementById('teacherHint');
            const deliverySelect = document.getElementById('deliveryModeSelect');
            const preferredDaySelect = document.getElementById('preferredDaySelect');
            const dayHint = document.getElementById('dayHint');
            const startTimeInput = document.getElementById('preferredStartTime');
            const endTimeInput = document.getElementById('preferredEndTime');
            const regularScheduleBox = document.getElementById('regularScheduleBox');
            const specialScheduleBox = document.getElementById('specialScheduleBox');

            const teachersByCourse = {!! json_encode(
                $courses->mapWithKeys(function ($c) {
                    return [
                        $c->id => $c->teachers->map(function ($t) {
                                return ['id' => $t->id, 'name' => $t->nickname ?: $t->full_name];
                            })->values(),
                    ];
                }),
            ) !!};

            const teachersAvailability = JSON.parse(document.getElementById('teachersAvailabilityData').textContent);
            const dayLabels = JSON.parse(document.getElementById('dayLabelsData').textContent);

            function updatePrice() {
                const price = parseFloat(courseCardsData[courseIdInput.value]?.price || 0);
                const subtotal = price / 1.07;
                const vat = price - subtotal;

                document.getElementById('priceSubtotal').textContent = subtotal.toFixed(2);
                document.getElementById('priceVat').textContent = vat.toFixed(2);
                document.getElementById('priceTotal').textContent = price.toFixed(2);
            }

            function updateTeachers() {
                const courseId = courseIdInput.value;
                const list = teachersByCourse[courseId] || [];

                teacherSelect.innerHTML = '<option value="">ให้ทางโรงเรียนจัดให้</option>';
                list.forEach(t => teacherSelect.insertAdjacentHTML('beforeend',
                    `<option value="${t.id}">${t.name}</option>`));

                teacherHint.textContent = list.length ?
                    `มีอาจารย์ที่สอนคอร์สนี้ได้ ${list.length} ท่าน` :
                    'คอร์สนี้ยังไม่ได้กำหนดอาจารย์ผู้สอนไว้ — เลือก "ให้ทางโรงเรียนจัดให้" ได้';

                resetDaySelect();
            }

            function resetDaySelect() {
                preferredDaySelect.innerHTML = '<option value="">ไม่ระบุ</option>';
                Object.keys(dayLabels).forEach(dow => {
                    preferredDaySelect.insertAdjacentHTML('beforeend',
                        `<option value="${dow}">${dayLabels[dow]}</option>`);
                });
                dayHint.textContent = 'เลือกอาจารย์ก่อน ระบบจะกรองเฉพาะวันที่อาจารย์ว่างจริงให้อัตโนมัติ';
                startTimeInput.removeAttribute('min');
                startTimeInput.removeAttribute('max');
                endTimeInput.removeAttribute('min');
                endTimeInput.removeAttribute('max');
            }

            // Business rule: คอร์สแบบพิเศษมีวันเวลาตายตัวอยู่แล้วตั้งแต่ตอนสร้างคอร์ส ไม่ต้องให้เลือกวันสะดวกเรียนซ้ำ
            // คอร์สแบบปกติ (นับจำนวนครั้ง) ยังเลือกวันที่สะดวกเรียนได้อิสระเหมือนเดิม
            function toggleScheduleBoxByStructureType() {
                const data = courseCardsData[courseIdInput.value];
                const structureType = data?.structure_type;

                if (structureType === 'special') {
                    regularScheduleBox.classList.add('d-none');
                    specialScheduleBox.classList.remove('d-none');

                    document.getElementById('specialDateRangeText').textContent =
                        `${data.course_start || '-'} ถึง ${data.course_end || '-'}`;
                    document.getElementById('specialDaysCountText').textContent = data.days_count || '-';
                    document.getElementById('specialHoursText').textContent = data.hours_per_day || '-';

                    // ล้างค่าช่องวันสะดวกเรียนของคอร์สปกติ ไม่ให้ส่งค่าเก่าติดไปโดยไม่ตั้งใจ
                    preferredDaySelect.value = '';
                    startTimeInput.value = '';
                    endTimeInput.value = '';
                } else {
                    regularScheduleBox.classList.remove('d-none');
                    specialScheduleBox.classList.add('d-none');
                }
            }

            teacherSelect.addEventListener('change', function() {
                const teacherId = this.value;
                const avail = teachersAvailability[teacherId];

                if (!teacherId || !avail || avail.length === 0) {
                    resetDaySelect();
                    if (teacherId) dayHint.textContent =
                        'อาจารย์ท่านนี้ยังไม่ได้ตั้งตาราง Availability ไว้ — เลือกวันได้อิสระ';
                    return;
                }

                const availableDays = [...new Set(avail.map(a => a.day))];
                preferredDaySelect.innerHTML = '<option value="">ไม่ระบุ</option>';
                availableDays.forEach(dow => {
                    preferredDaySelect.insertAdjacentHTML('beforeend',
                        `<option value="${dow}">${dayLabels[dow]}</option>`);
                });
                dayHint.innerHTML =
                    `<i class="bi bi-check-circle text-success"></i> แสดงเฉพาะวันที่อาจารย์ว่างจริง (${availableDays.length} วัน)`;

                preferredDaySelect.onchange = function() {
                    const dow = this.value;
                    const win = avail.find(a => a.day == dow);
                    if (win) {
                        startTimeInput.min = win.start;
                        startTimeInput.max = win.end;
                        endTimeInput.min = win.start;
                        endTimeInput.max = win.end;
                        dayHint.innerHTML =
                            `<i class="bi bi-clock text-success"></i> อาจารย์ว่างวันนี้ช่วง ${win.start} - ${win.end}`;
                    }
                };
            });

            async function checkCapacity() {
                const courseId = courseIdInput.value;
                if (!courseId) {
                    capacityBox.classList.add('d-none');
                    return;
                }
                try {
                    const res = await fetch(`{{ route('sales.course-availability') }}?course_id=${courseId}`);
                    const data = await res.json();
                    capacityBox.classList.remove('d-none');
                    if (data.unlimited) {
                        capacityBox.className = 'capacity-hint capacity-ok';
                        capacityBox.innerHTML =
                            '<i class="bi bi-check-circle"></i> คอร์สนี้เป็นแบบ Private ไม่จำกัดจำนวนที่นั่ง';
                    } else if (data.remaining > 0) {
                        capacityBox.className = 'capacity-hint capacity-ok';
                        capacityBox.innerHTML =
                            `<i class="bi bi-check-circle"></i> ที่นั่งคงเหลือ ${data.remaining} จาก ${data.max} ที่นั่ง`;
                    } else {
                        capacityBox.className = 'capacity-hint capacity-full';
                        capacityBox.innerHTML =
                            '<i class="bi bi-exclamation-triangle"></i> คอร์สนี้เต็มแล้ว ไม่สามารถสมัครเพิ่มได้';
                    }
                } catch (e) {
                    console.error(e);
                }
            }

            // ============ 5. Toggle ใบเสร็จ/ใบกำกับภาษี ============
            // สำคัญ: ห้าม disable ช่อง select เด็ดขาด เพราะ input ที่ disabled จะไม่ถูกส่งไปกับฟอร์มเลย
            const wantsInvoiceCheck = document.getElementById('wantsInvoiceCheck');
            const invoiceFieldsBox = document.getElementById('invoiceFieldsBox');
            const noInvoiceNote = document.getElementById('noInvoiceNote');
            const buyerNameInput = document.getElementById('buyerNameInput');
            const invoiceTypeSelect = document.getElementById('invoiceType');
            const isCompanyCheck = document.getElementById('isCompanyCheck');
            const taxIdBox = document.getElementById('taxIdBox');

            function toggleInvoiceFields() {
                const wants = wantsInvoiceCheck.checked;
                invoiceFieldsBox.classList.toggle('d-none', !wants);
                noInvoiceNote.classList.toggle('d-none', wants);
                buyerNameInput.required = wants;

                if (!wants) {
                    invoiceTypeSelect.value = 'none';
                } else if (invoiceTypeSelect.value === 'none') {
                    invoiceTypeSelect.value = 'receipt';
                }
            }
            wantsInvoiceCheck.addEventListener('change', toggleInvoiceFields);
            toggleInvoiceFields();

            function toggleTaxId() {
                const show = isCompanyCheck.checked;
                taxIdBox.classList.toggle('d-none', !show);
                document.getElementById('buyerTaxId').required = show;
                if (show) invoiceTypeSelect.value = 'tax_invoice';
            }
            isCompanyCheck.addEventListener('change', toggleTaxId);
            document.getElementById('buyerTaxId').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 13);
            });

            // ============ Submit guard ============
            document.getElementById('saleForm').addEventListener('submit', function(e) {
                if (!studentIdInput.value) {
                    e.preventDefault();
                    alert('กรุณาเลือกนักเรียนก่อน');
                    return;
                }
                if (!courseIdInput.value) {
                    e.preventDefault();
                    alert('กรุณาเลือกคอร์สก่อน');
                    return;
                }

                const btn = this.querySelector('button[type=submit]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...';
            });
        })();
    </script>
@endsection

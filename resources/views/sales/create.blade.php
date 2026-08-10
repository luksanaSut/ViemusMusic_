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
            <div id="studentPicker" class="border rounded p-2" style="background:#faf9f7;">
                <div class="position-relative">
                    <input type="text" id="studentSearch" class="form-control form-control-sm"
                        placeholder="พิมพ์ชื่อหรือรหัสนักเรียน..." autocomplete="off"
                        value="{{ $preselectedStudent->full_name ?? '' }}">
                    <div id="studentDropdown" class="list-group position-absolute w-100 shadow-sm d-none"
                        style="z-index:20; max-height:220px; overflow-y:auto; top:100%;"></div>
                </div>
            </div>
            <input type="hidden" name="student_id" id="studentIdInput" value="{{ $preselectedStudent->id ?? '' }}">
            <div id="studentSelectedInfo" class="mt-2 small text-muted"></div>
        </div>

        {{-- 2. เลือกคอร์ส --}}
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-journal-bookmark"></i></div> เลือกคอร์สเรียน (แพ็กเกจ) <span
                    class="step-no">ขั้นตอน 2</span>
            </div>
            <select id="courseSelect" name="course_id" class="form-select" required>
                <option value="">เลือกคอร์ส...</option>
                @foreach ($courses as $c)
                    <option value="{{ $c->id }}" data-price="{{ $c->price }}"
                        data-class-type="{{ $c->class_type }}" data-delivery="{{ $c->delivery_mode }}">
                        {{ $c->name }} ({{ $c->course_code }}) — {{ number_format($c->price, 2) }} บาท
                    </option>
                @endforeach
            </select>
            <div id="capacityHintBox" class="capacity-hint d-none"></div>
        </div>

        {{-- 3. เลือกอาจารย์ (ต้องเลือกคอร์สก่อน) / สาขา / รูปแบบเรียน / วันเวลา --}}
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-sliders"></i></div> รายละเอียดการเรียน <span class="step-no">ขั้นตอน
                    3</span>
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
                    <input type="text" name="branch" class="form-control" list="branchList" placeholder="เช่น Cloud 11">
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
                <div class="col-md-4">
                    <label class="form-label">วันที่สะดวกเรียน</label>
                    <select name="preferred_day_of_week" class="form-select">
                        <option value="">ไม่ระบุ</option>
                        @foreach (\App\Models\TeacherAvailability::dayLabels() as $dow => $label)
                            <option value="{{ $dow }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">เวลาเริ่ม</label>
                    <input type="time" name="preferred_start_time" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">เวลาสิ้นสุด</label>
                    <input type="time" name="preferred_end_time" class="form-control">
                </div>
            </div>
        </div>

        {{-- 4. สรุปราคา --}}
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-calculator"></i></div> คำนวณค่าเรียน <span class="step-no">ขั้นตอน
                    4</span>
            </div>
            <div class="price-box">
                <div class="price-row"><span>ราคาคอร์ส (ก่อน VAT)</span><span id="priceSubtotal">0.00</span></div>
                <div class="price-row"><span>VAT 7%</span><span id="priceVat">0.00</span></div>
                <div class="price-row total"><span>ยอดชำระทั้งหมด</span><span id="priceTotal">0.00</span></div>
            </div>
            <small class="text-muted d-block mt-2"><i class="bi bi-info-circle"></i>
                ราคานี้ยังไม่รวมส่วนลดคูปอง/แต้ม/เครดิต — จะปรับยอดสุทธิได้อีกครั้งในหน้าสรุปก่อนชำระเงิน</small>
        </div>

        {{-- 5. ข้อมูลใบเสร็จ/ใบกำกับภาษี --}}
        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-receipt"></i></div> ข้อมูลใบเสร็จ / ใบกำกับภาษี <span
                    class="step-no">ขั้นตอน 5</span>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">ประเภทเอกสาร *</label>
                    <select name="invoice_type" id="invoiceType" class="form-select" required>
                        <option value="receipt">ใบเสร็จรับเงิน</option>
                        <option value="tax_invoice">ใบกำกับภาษีเต็มรูป</option>
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
                    <input type="text" name="buyer_name" class="form-control" maxlength="150" required>
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

    <script>
        (function() {
            // ===== ค้นหานักเรียน =====
            const studentSearch = document.getElementById('studentSearch');
            const studentDropdown = document.getElementById('studentDropdown');
            const studentIdInput = document.getElementById('studentIdInput');
            const studentInfo = document.getElementById('studentSelectedInfo');
            let debounce;

            studentSearch.addEventListener('input', function() {
                studentIdInput.value = '';
                clearTimeout(debounce);
                const q = this.value.trim();
                if (q.length < 2) {
                    studentDropdown.classList.add('d-none');
                    return;
                }

                debounce = setTimeout(async () => {
                    try {
                        const res = await fetch(
                            `{{ route('students.search') }}?q=${encodeURIComponent(q)}`);
                        const results = await res.json();
                        studentDropdown.innerHTML = '';
                        if (results.length === 0) {
                            studentDropdown.classList.add('d-none');
                            return;
                        }

                        results.forEach(s => {
                            const item = document.createElement('button');
                            item.type = 'button';
                            item.className =
                                'list-group-item list-group-item-action py-1 px-2 small';
                            item.textContent = `${s.full_name} (${s.student_code})`;
                            item.addEventListener('click', () => {
                                studentIdInput.value = s.id;
                                studentSearch.value = s.full_name;
                                studentInfo.innerHTML =
                                    `<i class="bi bi-check-circle text-success"></i> เลือก: ${s.full_name} (${s.student_code})`;
                                studentDropdown.classList.add('d-none');
                            });
                            studentDropdown.appendChild(item);
                        });
                        studentDropdown.classList.remove('d-none');
                    } catch (e) {
                        console.error('ค้นหานักเรียนไม่สำเร็จ', e);
                    }
                }, 300);
            });
            document.addEventListener('click', e => {
                if (!document.getElementById('studentPicker').contains(e.target)) studentDropdown.classList.add(
                    'd-none');
            });

            // ===== เลือกคอร์ส -> คำนวณราคา + เช็คที่นั่งคงเหลือ + populate อาจารย์เฉพาะคอร์สนี้ =====
            const courseSelect = document.getElementById('courseSelect');
            const teacherSelect = document.getElementById('teacherSelect');
            const teacherHint = document.getElementById('teacherHint');
            const deliverySelect = document.getElementById('deliveryModeSelect');
            const capacityBox = document.getElementById('capacityHintBox');


            const teachersByCourse = {!! json_encode(
                $courses->mapWithKeys(function ($c) {
                    return [
                        $c->id => $c->teachers->map(function ($t) {
                                return ['id' => $t->id, 'name' => $t->nickname ?: $t->full_name];
                            })->values(),
                    ];
                }),
            ) !!};

            function updatePrice() {
                const opt = courseSelect.options[courseSelect.selectedIndex];
                const price = parseFloat(opt?.dataset.price || 0);
                const subtotal = price / 1.07;
                const vat = price - subtotal;

                document.getElementById('priceSubtotal').textContent = subtotal.toFixed(2);
                document.getElementById('priceVat').textContent = vat.toFixed(2);
                document.getElementById('priceTotal').textContent = price.toFixed(2);
            }

            // พฤติกรรมเดิม: dropdown อาจารย์ว่างเปล่าจนกว่าจะเลือกคอร์ส แล้ว populate เฉพาะอาจารย์ของคอร์สนั้น
            function updateTeachers() {
                const courseId = courseSelect.value;
                const list = teachersByCourse[courseId] || [];

                teacherSelect.innerHTML = '<option value="">ให้ทางโรงเรียนจัดให้</option>';
                list.forEach(t => {
                    teacherSelect.insertAdjacentHTML('beforeend', `<option value="${t.id}">${t.name}</option>`);
                });

                teacherHint.textContent = list.length ?
                    `มีอาจารย์ที่สอนคอร์สนี้ได้ ${list.length} ท่าน` :
                    'คอร์สนี้ยังไม่ได้กำหนดอาจารย์ผู้สอนไว้ — เลือก "ให้ทางโรงเรียนจัดให้" ได้';
            }

            async function checkCapacity() {
                const courseId = courseSelect.value;
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
                    console.error('ตรวจสอบที่นั่งไม่สำเร็จ', e);
                }
            }

            courseSelect.addEventListener('change', function() {
                updatePrice();
                updateTeachers();
                checkCapacity();
                const opt = this.options[this.selectedIndex];
                if (opt?.dataset.delivery) deliverySelect.value = opt.dataset.delivery;
            });

            // ===== ใบกำกับภาษี: โชว์ช่องเลขผู้เสียภาษีเมื่อติ๊กนิติบุคคล =====
            const isCompanyCheck = document.getElementById('isCompanyCheck');
            const taxIdBox = document.getElementById('taxIdBox');
            const invoiceType = document.getElementById('invoiceType');

            function toggleTaxId() {
                const show = isCompanyCheck.checked;
                taxIdBox.classList.toggle('d-none', !show);
                document.getElementById('buyerTaxId').required = show;
                if (show) invoiceType.value = 'tax_invoice';
            }
            isCompanyCheck.addEventListener('change', toggleTaxId);
            document.getElementById('buyerTaxId').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 13);
            });

            document.getElementById('saleForm').addEventListener('submit', function() {
                const btn = this.querySelector('button[type=submit]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...';
            });
        })();
    </script>
@endsection

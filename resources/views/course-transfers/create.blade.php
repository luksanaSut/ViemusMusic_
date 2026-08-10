@extends('layouts.app')
@section('title', 'เปลี่ยนคอร์สเรียน')

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

        .compare-box {
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 12px;
            padding: 1rem;
        }

        .compare-box .head {
            font-size: .75rem;
            color: var(--muted, #6b655e);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: .3rem;
        }

        .compare-box .name {
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
        }

        .compare-box .price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--accent-dark, #13233a);
        }

        .diff-summary {
            background: var(--accent-soft, #e7ebf1);
            border-radius: 14px;
            padding: 1.2rem;
            text-align: center;
        }

        .diff-summary .amount {
            font-size: 1.8rem;
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
        }

        .diff-positive {
            color: #b3392c;
        }

        .diff-negative {
            color: #2f6f4e;
        }
    </style>

    <div class="breadcrumb-sm">งานขาย <i class="bi bi-chevron-right small"></i> เปลี่ยนคอร์สเรียน</div>
    <h1 class="page-title mb-3"><i class="bi bi-arrow-left-right"></i> เปลี่ยนคอร์สเรียน</h1>

    <form action="{{ route('course-transfers.store') }}" method="POST" id="transferForm">
        @csrf
        <input type="hidden" name="old_enrollment_id" value="{{ $enrollment->id }}">

        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-person"></i></div> ข้อมูลนักเรียน & คอร์สปัจจุบัน
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="compare-box">
                        <div class="head">นักเรียน</div>
                        <div class="name">{{ $enrollment->student->full_name }}</div>
                        <div class="text-muted small">{{ $enrollment->student->student_code }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="compare-box">
                        <div class="head">คอร์สปัจจุบัน</div>
                        <div class="name">{{ $enrollment->course->name }}</div>
                        <div class="price">฿{{ number_format($enrollment->course->price, 0) }}</div>
                        <div class="text-muted small">
                            เหลือ {{ $enrollment->remainingSessions() ?? 'ไม่จำกัด' }} ครั้ง จากทั้งหมด
                            {{ $enrollment->course->total_sessions ?? '-' }} ครั้ง ·
                            มูลค่าคงเหลือโดยประมาณ <strong
                                id="remainingValueDisplay">฿{{ number_format($enrollment->remainingValue(), 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-journal-bookmark"></i></div> เลือกคอร์สใหม่
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">คอร์สใหม่ *</label>
                    <select name="new_course_id" id="newCourseSelect" class="form-select" required>
                        <option value="">เลือกคอร์ส...</option>
                        @foreach ($courses as $c)
                            <option value="{{ $c->id }}" data-price="{{ $c->price }}">{{ $c->name }}
                                ({{ $c->course_code }}) — ฿{{ number_format($c->price, 0) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">อาจารย์ผู้สอนใหม่</label>
                    <select name="new_teacher_id" id="newTeacherSelect" class="form-select">
                        <option value="">ให้ทางโรงเรียนจัดให้</option>
                    </select>
                </div>
                <div class="col-md-6" id="teacherFeeBox" style="display:none;">
                    <label class="form-label">ค่าธรรมเนียมเปลี่ยนอาจารย์ (ถ้ามี)</label>
                    <input type="number" step="0.01" min="0" name="teacher_change_fee" id="teacherFeeInput"
                        class="form-control" value="0">
                    <small class="text-muted">กรอกเฉพาะกรณีมีนโยบายเรียกเก็บเพิ่มเมื่อเปลี่ยนอาจารย์ —
                        ระบบไม่คำนวณให้อัตโนมัติ</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">เหตุผลการเปลี่ยนคอร์ส</label>
                    <input type="text" name="reason" class="form-control" maxlength="500"
                        placeholder="เช่น นักเรียนต้องการเรียนเครื่องดนตรีอื่น">
                </div>
                <div class="col-12">
                    <label class="form-label">หมายเหตุ</label>
                    <textarea name="notes" class="form-control" rows="2" maxlength="1000"></textarea>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">
                <div class="icon-badge"><i class="bi bi-calculator"></i></div> คำนวณส่วนต่างราคาอัตโนมัติ
            </div>
            <div class="diff-summary" id="diffSummaryBox">
                <div class="text-muted small">เลือกคอร์สใหม่เพื่อคำนวณส่วนต่าง</div>
            </div>
            <ul class="text-muted small mt-3 mb-0" style="padding-left:1.2rem;">
                <li>ถ้าคอร์สใหม่ราคาสูงกว่ามูลค่าคงเหลือ ระบบจะให้ชำระส่วนต่างก่อนยืนยันการเปลี่ยนคอร์ส</li>
                <li>ถ้าคอร์สใหม่ราคาต่ำกว่า ระบบจะเก็บส่วนต่างเป็นเครดิตคงเหลือให้นักเรียนอัตโนมัติ</li>
                <li>เมื่อยืนยันแล้ว ระบบจะปิดคอร์สเดิมและเปิดคอร์สใหม่ให้ทันที (ถือเป็นการทำรายการใหม่)</li>
            </ul>
        </div>

        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-arrow-left-right"></i> สร้างรายการเปลี่ยนคอร์ส</button>
            <a href="{{ route('students.show', $enrollment->student) }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>

    <script id="teachersByCourseData" type="application/json">
{!! json_encode($courses->mapWithKeys(function ($c) {
    return [$c->id => $c->teachers->map(function ($t) { return ['id' => $t->id, 'name' => $t->nickname ?: $t->full_name]; })->values()];
})) !!}
</script>

    <script>
        (function() {
            const remainingValue = {{ $enrollment->remainingValue() }};
            const courseSelect = document.getElementById('newCourseSelect');
            const teacherSelect = document.getElementById('newTeacherSelect');
            const teacherFeeBox = document.getElementById('teacherFeeBox');
            const teacherFeeInput = document.getElementById('teacherFeeInput');
            const diffBox = document.getElementById('diffSummaryBox');
            const teachersByCourse = JSON.parse(document.getElementById('teachersByCourseData').textContent);

            function updateTeachers() {
                const list = teachersByCourse[courseSelect.value] || [];
                teacherSelect.innerHTML = '<option value="">ให้ทางโรงเรียนจัดให้</option>';
                list.forEach(t => teacherSelect.insertAdjacentHTML('beforeend',
                    `<option value="${t.id}">${t.name}</option>`));
                teacherFeeBox.style.display = 'none';
            }

            function updateDiff() {
                const opt = courseSelect.options[courseSelect.selectedIndex];
                if (!opt || !opt.value) {
                    diffBox.innerHTML = '<div class="text-muted small">เลือกคอร์สใหม่เพื่อคำนวณส่วนต่าง</div>';
                    return;
                }

                const newPrice = parseFloat(opt.dataset.price || 0);
                const fee = parseFloat(teacherFeeInput.value || 0);
                const diff = (newPrice + fee) - remainingValue;

                let html =
                    `<div class="text-muted small">ราคาคอร์สใหม่ ฿${newPrice.toLocaleString()} ${fee > 0 ? '+ ค่าธรรมเนียมอาจารย์ ฿'+fee.toLocaleString() : ''} − มูลค่าคงเหลือ ฿${remainingValue.toLocaleString()}</div>`;
                if (diff > 0) {
                    html +=
                        `<div class="amount diff-positive">ต้องชำระเพิ่ม ฿${diff.toLocaleString(undefined,{minimumFractionDigits:2})}</div><div class="small">ต้องชำระก่อนยืนยันการเปลี่ยนคอร์ส</div>`;
                } else if (diff < 0) {
                    html +=
                        `<div class="amount diff-negative">ได้รับเครดิตคืน ฿${Math.abs(diff).toLocaleString(undefined,{minimumFractionDigits:2})}</div><div class="small">ระบบจะเก็บเป็นเครดิตคงเหลือให้อัตโนมัติ</div>`;
                } else {
                    html += `<div class="amount">ไม่มีส่วนต่าง</div>`;
                }
                diffBox.innerHTML = html;
            }

            courseSelect.addEventListener('change', function() {
                updateTeachers();
                updateDiff();
            });
            teacherSelect.addEventListener('change', function() {
                teacherFeeBox.style.display = this.value ? 'block' : 'none';
                updateDiff();
            });
            teacherFeeInput.addEventListener('input', updateDiff);

            document.getElementById('transferForm').addEventListener('submit', function() {
                const btn = this.querySelector('button[type=submit]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...';
            });
        })();
    </script>
@endsection

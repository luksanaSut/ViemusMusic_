@extends('layouts.app')
@section('title', 'สร้างบัญชีผู้ใช้งาน')

@section('content')
    <div class="breadcrumb-sm">ระบบ <i class="bi bi-chevron-right small"></i> ผู้ใช้งานระบบ <i
            class="bi bi-chevron-right small"></i> สร้างใหม่</div>
    <h1 class="page-title mb-3">สร้างบัญชีผู้ใช้งาน</h1>

    <div class="form-section" style="background:#fff;border:1px solid #e4e1dc;border-radius:16px;padding:1.4rem 1.6rem;">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">ชื่อที่แสดง *</label>
                    <input type="text" name="name" class="form-control" maxlength="150" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">อีเมล (ใช้ล็อกอิน) *</label>
                    <input type="email" name="email" class="form-control" maxlength="150" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">บทบาท *</label>
                    <select name="role" id="roleSelect" class="form-select" required>
                        <option value="admin">ผู้ดูแลระบบ</option>
                        <option value="teacher">อาจารย์</option>
                        <option value="student">นักเรียน</option>
                        <option value="guardian">ผู้ปกครอง</option>
                    </select>
                </div>
                <div class="col-md-8 d-none" id="teacherBox">
                    <label class="form-label">ผูกกับอาจารย์คนไหน</label>
                    <select name="teacher_id" class="form-select">
                        <option value="">ไม่ผูก</option>
                        @foreach ($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 d-none" id="studentBox">
                    <label class="form-label">ผูกกับนักเรียนคนไหน</label>
                    <select name="student_id" class="form-select">
                        <option value="">ไม่ผูก</option>
                        @foreach ($students as $s)
                            <option value="{{ $s->id }}">{{ $s->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 d-none" id="guardianBox">
                    <label class="form-label">ผูกกับผู้ปกครองคนไหน</label>
                    <select name="guardian_id" class="form-select">
                        <option value="">ไม่ผูก</option>
                        @foreach ($guardians as $g)
                            <option value="{{ $g->id }}">{{ $g->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="alert alert-light border mt-3 small"><i class="bi bi-info-circle"></i>
                ระบบจะสร้างรหัสผ่านสุ่มให้อัตโนมัติ และแสดงให้คัดลอกครั้งเดียวหลังบันทึก —
                ผู้ใช้จะถูกบังคับให้เปลี่ยนรหัสผ่านตอนล็อกอินครั้งแรก</div>
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-accent"><i class="bi bi-save"></i> สร้างบัญชี</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('roleSelect').addEventListener('change', function() {
            ['teacherBox', 'studentBox', 'guardianBox'].forEach(id => document.getElementById(id).classList.add(
                'd-none'));
            const map = {
                teacher: 'teacherBox',
                student: 'studentBox',
                guardian: 'guardianBox'
            };
            if (map[this.value]) document.getElementById(map[this.value]).classList.remove('d-none');
        });
    </script>
@endsection

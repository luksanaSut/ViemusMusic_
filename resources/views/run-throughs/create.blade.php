@extends('layouts.app')
@section('title', 'สร้างแบบฝึกหัดทบทวน')

@section('content')
    <h1 class="page-title mb-1"><i class="bi bi-arrow-repeat"></i> สร้างแบบฝึกหัดทบทวน (Run Through)</h1>
    <div class="page-sub mb-1">{{ $enrollment->student->full_name }} — {{ $enrollment->course->name }}</div>
    <p class="text-muted small mb-3"><i class="bi bi-1-circle"></i> ขั้นตอนที่ 1 จาก 2 — สร้างหัวข้อให้นักเรียนไปฝึกซ้อม
        หลังจากนักเรียนฝึกซ้อมแล้ว ให้กลับมาที่หน้า <a href="{{ route('run-throughs.index') }}">รายการ Run Through</a> เพื่อบันทึกผล (ขั้นตอนที่ 2)</p>

    <form action="{{ route('run-throughs.store', $enrollment) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mb-3" style="border-radius:16px;">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">หัวข้อแบบฝึกหัด *</label>
                    <input type="text" name="title" class="form-control" required
                        placeholder="เช่น ทบทวนก่อนสอบ Grade 3">
                </div>
                <div class="mb-3">
                    <label class="form-label">รายละเอียด</label>
                    <textarea name="description" class="form-control" rows="4"
                        placeholder="อธิบายสิ่งที่ต้องการให้นักเรียนทบทวน/ฝึกซ้อม"></textarea>
                </div>
                <div>
                    <label class="form-label">แนบเอกสาร/โน้ตเพลง</label>
                    <input type="file" name="attachments[]" class="form-control" multiple
                        accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx,.mscz,.xml">
                    <div class="form-text">สูงสุด 5 ไฟล์ ไฟล์ละไม่เกิน 20MB — รองรับ PDF, JPG, PNG, WEBP, DOC, DOCX, MSCZ, XML</div>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-save"></i> สร้างแบบฝึกหัด</button>
            <a href="{{ route('run-throughs.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
@endsection

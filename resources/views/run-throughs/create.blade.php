@extends('layouts.app')
@section('title', 'สร้างแบบฝึกหัดทบทวน')

@section('content')
    <h1 class="page-title mb-1"><i class="bi bi-arrow-repeat"></i> สร้างแบบฝึกหัดทบทวน (Run Through)</h1>
    <div class="page-sub mb-3">{{ $enrollment->student->full_name }} — {{ $enrollment->course->name }}</div>

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
                </div>
            </div>
        </div>
        <button class="btn btn-accent mb-4"><i class="bi bi-save"></i> สร้างแบบฝึกหัด</button>
    </form>
@endsection

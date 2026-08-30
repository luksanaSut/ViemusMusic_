@extends('layouts.app')
@section('title', 'Run Through')

@section('content')
    <h1 class="page-title mb-1"><i class="bi bi-arrow-repeat"></i> ระบบ Run Through</h1>
    <p class="text-muted small mb-3">แบบฝึกหัดทบทวนสำหรับนักเรียน มี 2 ขั้นตอน: <strong>1) สร้างหัวข้อ</strong> ให้นักเรียนไปฝึกซ้อม แล้ว
        <strong>2) กลับมาบันทึกผลการฝึกซ้อม</strong> เมื่อนักเรียนฝึกเสร็จแล้ว</p>

    <div class="card mb-3" style="border-radius:16px;">
        <div class="card-body">
            <div class="fw-bold mb-2" style="font-family:'Prompt',sans-serif;"><i class="bi bi-plus-circle"></i> สร้าง Run Through ใหม่ (ขั้นตอนที่ 1)</div>
            @if ($pickerEnrollments->isEmpty())
                <p class="text-muted small mb-0">ยังไม่มีนักเรียนที่กำลังเรียนอยู่ในความดูแลของคุณ จึงยังสร้าง Run Through ไม่ได้</p>
            @else
                <form method="GET" action="{{ route('run-throughs.new') }}" class="row g-2 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label small">เลือกนักเรียนและคอร์ส</label>
                        <select name="enrollment_id" class="form-select" required>
                            <option value="">— เลือกนักเรียนและคอร์ส —</option>
                            @foreach ($pickerEnrollments as $enr)
                                <option value="{{ $enr->id }}">{{ $enr->student->full_name ?? '-' }} — {{ $enr->course->name ?? '-' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-accent w-100"><i class="bi bi-arrow-right-circle"></i> ไปสร้าง Run Through</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    @if ($pendingCount > 0)
        <div class="alert alert-warning small d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-exclamation-circle fs-5"></i>
            <div>มี <strong>{{ $pendingCount }}</strong> รายการที่ยังไม่ได้บันทึกผลการฝึกซ้อม (ขั้นตอนที่ 2) — ดูรายการที่มีแถบสีเหลืองด้านล่าง</div>
        </div>
    @endif

    <div class="fw-bold mb-2" style="font-family:'Prompt',sans-serif;"><i class="bi bi-list-check"></i> รายการ Run Through ทั้งหมด</div>

    @forelse($runThroughs as $rt)
        <div class="card mb-3" style="border-radius:16px; {{ !$rt->result_recorded_at ? 'border-left:4px solid var(--bs-warning,#ffc107);' : '' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-0" style="font-family:'Prompt',sans-serif;">{{ $rt->title }}</h6>
                        <div class="text-muted small">{{ $rt->enrollment->student->full_name ?? '-' }} —
                            {{ $rt->enrollment->course->name ?? '-' }} · สร้างเมื่อ {{ $rt->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    <span class="badge {{ $rt->practiceResultBadgeClass() }}">{{ $rt->practiceResultLabel() }}</span>
                </div>
                @if ($rt->description)
                    <p class="small mt-2 mb-1">{{ $rt->description }}</p>
                @endif
                <div class="d-flex flex-wrap gap-2 mb-2">
                    @foreach ($rt->attachments as $att)
                        <a href="{{ $att->url() }}" target="_blank"
                            class="badge text-bg-light border text-decoration-none"><i class="bi bi-paperclip"></i>
                            {{ $att->original_name }}</a>
                    @endforeach
                </div>

                @if (!$rt->result_recorded_at)
                    <form action="{{ route('run-throughs.record-result', $rt) }}" method="POST"
                        class="row g-2 mt-2 border-top pt-2">
                        <div class="col-12 small fw-bold text-warning-emphasis"><i class="bi bi-2-circle"></i> ขั้นตอนที่ 2 — บันทึกผลการฝึกซ้อม</div>
                        @csrf
                        <div class="col-md-4">
                            <select name="practice_result" class="form-select form-select-sm" required>
                                <option value="">เลือกผลการฝึกซ้อม</option>
                                <option value="excellent">ดีเยี่ยม</option>
                                <option value="good">ดี</option>
                                <option value="needs_practice">ต้องฝึกเพิ่ม</option>
                            </select>
                        </div>
                        <div class="col-md-8"><input type="text" name="areas_to_improve"
                                class="form-control form-control-sm" placeholder="สิ่งที่ต้องฝึกเพิ่มเติม (ถ้ามี)"></div>
                        <div class="col-12">
                            <textarea name="teacher_comment" class="form-control form-control-sm" rows="2"
                                placeholder="ความคิดเห็นเพิ่มเติมจากอาจารย์"></textarea>
                        </div>
                        <div class="col-auto"><button class="btn btn-sm btn-accent">บันทึกผลการฝึกซ้อม</button></div>
                    </form>
                @else
                    @if ($rt->areas_to_improve)
                        <div class="alert alert-warning small mt-2 mb-1"><i class="bi bi-flag"></i>
                            <strong>ต้องฝึกเพิ่ม:</strong> {{ $rt->areas_to_improve }}</div>
                    @endif
                    @if ($rt->teacher_comment)
                        <p class="small mb-0"><strong>ความเห็นอาจารย์:</strong> {{ $rt->teacher_comment }}</p>
                    @endif
                @endif
            </div>
        </div>
    @empty
        <p class="text-muted">ยังไม่มี Run Through ในระบบ</p>
    @endforelse
    {{ $runThroughs->links() }}
@endsection

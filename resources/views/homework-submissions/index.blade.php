@extends('layouts.app')
@section('title', 'ตรวจการบ้าน')

@section('content')
    <h1 class="page-title mb-3"><i class="bi bi-journal-check"></i> ตรวจการบ้าน</h1>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="submitted" @selected(request('status') == 'submitted')>รอตรวจ</option>
                        <option value="approved" @selected(request('status') == 'approved')>ผ่านแล้ว</option>
                        <option value="needs_revision" @selected(request('status') == 'needs_revision')>ต้องแก้ไข</option>
                    </select>
                </div>
                <div class="col-md-2"><button class="btn btn-outline-secondary btn-sm">กรอง</button></div>
            </form>
        </div>
    </div>

    @forelse($submissions as $sub)
        <div class="card mb-3" style="border-radius:16px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-0" style="font-family:'Prompt',sans-serif;">{{ $sub->student->full_name }} —
                            ครั้งที่ {{ $sub->version }}</h6>
                        <div class="text-muted small">
                            {{ $sub->teachingReport->teachingLog->enrollment->course->name ?? '-' }} · ส่งเมื่อ
                            {{ $sub->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <span class="badge {{ $sub->statusBadgeClass() }}">{{ $sub->statusLabel() }}</span>
                </div>

                <p class="small mt-2 mb-1"><strong>โจทย์การบ้าน:</strong> {{ $sub->teachingReport->homework }}</p>
                @if ($sub->student_note)
                    <p class="small mb-2"><strong>หมายเหตุจากนักเรียน:</strong> {{ $sub->student_note }}</p>
                @endif

                <div class="d-flex flex-wrap gap-2 mb-2">
                    @foreach ($sub->files as $f)
                        <a href="{{ $f->url() }}" target="_blank"
                            class="badge text-bg-light border text-decoration-none"><i class="bi bi-paperclip"></i>
                            {{ $f->original_name }}</a>
                    @endforeach
                </div>

                @if ($sub->status === 'submitted')
                    <form action="{{ route('homework-submissions.review', $sub) }}" method="POST" class="row g-2 mt-2">
                        @csrf
                        <div class="col-12">
                            <textarea name="feedback" class="form-control form-control-sm" rows="2" placeholder="Feedback ให้นักเรียน"></textarea>
                        </div>
                        <div class="col-auto"><button type="submit" name="status" value="approved"
                                class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> ผ่าน</button></div>
                        <div class="col-auto"><button type="submit" name="status" value="needs_revision"
                                class="btn btn-sm btn-outline-danger"><i class="bi bi-arrow-repeat"></i> ให้แก้ไข</button>
                        </div>
                    </form>
                @else
                    <div class="small text-muted mt-2"><i class="bi bi-check-circle"></i> ตรวจโดย {{ $sub->reviewed_by }}
                        เมื่อ {{ $sub->reviewed_at?->format('d/m/Y H:i') }}</div>
                    @if ($sub->feedback)
                        <p class="small mb-0"><strong>Feedback:</strong> {{ $sub->feedback }}</p>
                    @endif
                @endif
            </div>
        </div>
    @empty
        <p class="text-muted">ยังไม่มีการส่งการบ้าน</p>
    @endforelse

    {{ $submissions->links() }}
@endsection

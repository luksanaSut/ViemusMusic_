@extends('layouts.app')
@section('title', 'ผลการสอนย้อนหลัง')

@section('content')
    <h1 class="page-title mb-3"><i class="bi bi-journal-text"></i> ผลการสอนย้อนหลัง</h1>

    @forelse($reports as $report)
        @php $log = $report->teachingLog; @endphp
        <div class="card mb-3" style="border-radius:16px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-0" style="font-family:'Prompt',sans-serif;">
                            {{ $log->classSchedule->schedule_date->format('d/m/Y') }} —
                            {{ $log->enrollment->course->name ?? '-' }}</h6>
                        <div class="text-muted small">นักเรียน: {{ $log->student->full_name }} · เช็คชื่อ:
                            {{ $log->attendanceStatusLabel() }}</div>
                    </div>
                </div>
                @if ($report->content_taught)
                    <div class="mt-2 small"><strong>เนื้อหาที่สอน:</strong> {{ $report->content_taught }}</div>
                @endif
                @if ($report->homework)
                    <div class="small"><strong>การบ้าน:</strong> {{ $report->homework }}</div>
                @endif
                @if ($report->homework)
                    @php $latestSub = $report->latestHomeworkSubmission(); @endphp
                    <div class="mt-2 border-top pt-2">
                        @if (!$latestSub || $latestSub->status === 'needs_revision')
                            <form action="{{ route('homework-submissions.store', $report) }}" method="POST"
                                enctype="multipart/form-data" class="row g-2">
                                @csrf
                                <div class="col-12"><input type="file" name="files[]"
                                        class="form-control form-control-sm" multiple required></div>
                                <div class="col-12"><input type="text" name="student_note"
                                        class="form-control form-control-sm" placeholder="หมายเหตุ (ถ้ามี)"></div>
                                <div class="col-auto"><button
                                        class="btn btn-sm btn-accent">{{ $latestSub ? 'ส่งการบ้านใหม่ (แก้ไข)' : 'ส่งการบ้าน' }}</button>
                                </div>
                            </form>
                        @else
                            <span class="badge {{ $latestSub->statusBadgeClass() }}">{{ $latestSub->statusLabel() }}</span>
                        @endif
                    </div>
                @endif
                @if ($report->progress_notes)
                    <div class="small"><strong>ความก้าวหน้า:</strong> {{ $report->progress_notes }}</div>
                @endif
                @if ($report->notes)
                    <div class="small text-muted"><strong>หมายเหตุ:</strong> {{ $report->notes }}</div>
                @endif
                @if ($report->attachments->count())
                    <div class="mt-2">
                        @foreach ($report->attachments as $att)
                            <a href="{{ $att->url() }}" target="_blank"
                                class="badge text-bg-light border text-decoration-none me-1"><i class="bi bi-paperclip"></i>
                                {{ $att->original_name }}</a>
                        @endforeach
                    </div>
                @endif
                @if ($log->evidences->count())
                    <div class="mt-2">
                        <div class="small text-muted mb-1"><i class="bi bi-camera"></i> หลักฐานการสอน:</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($log->evidences as $ev)
                                <a href="{{ route('teaching-evidences.download', $ev) }}"
                                    class="badge text-bg-light border text-decoration-none">
                                    <i class="bi {{ $ev->fileTypeIcon() }}"></i> {{ $ev->original_name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <p class="text-muted">ยังไม่มีผลการสอนบันทึกไว้</p>
    @endforelse

    {{ $reports->links() }}
@endsection

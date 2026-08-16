@extends('layouts.app')
@section('title', 'การบ้านของฉัน')

@section('content')
    <h1 class="page-title mb-3"><i class="bi bi-journal-check"></i> ประวัติการบ้าน</h1>

    @forelse($submissions as $sub)
        <div class="card mb-3" style="border-radius:16px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-0" style="font-family:'Prompt',sans-serif;">
                            {{ $sub->teachingReport->teachingLog->enrollment->course->name ?? '-' }} — ครั้งที่
                            {{ $sub->version }}</h6>
                        <div class="text-muted small">ส่งเมื่อ {{ $sub->created_at->format('d/m/Y H:i') }} โดย
                            {{ $sub->submitted_by }}</div>
                    </div>
                    <span class="badge {{ $sub->statusBadgeClass() }}">{{ $sub->statusLabel() }}</span>
                </div>
                <p class="small mt-2 mb-1"><strong>โจทย์:</strong> {{ $sub->teachingReport->homework }}</p>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    @foreach ($sub->files as $f)
                        <a href="{{ $f->url() }}" target="_blank"
                            class="badge text-bg-light border text-decoration-none"><i class="bi bi-paperclip"></i>
                            {{ $f->original_name }}</a>
                    @endforeach
                </div>
                @if ($sub->feedback)
                    <p class="small mb-0"><strong>Feedback จากอาจารย์:</strong> {{ $sub->feedback }}</p>
                @endif
                @if ($sub->status === 'needs_revision')
                    <div class="alert alert-warning small mt-2 mb-0">ต้องแก้ไขและส่งใหม่ —
                        ไปที่หน้าผลการสอนของคาบนี้เพื่อส่งการบ้านเวอร์ชันใหม่</div>
                @endif
            </div>
        </div>
    @empty
        <p class="text-muted">ยังไม่มีประวัติการส่งการบ้าน</p>
    @endforelse
    {{ $submissions->links() }}
@endsection

@extends('layouts.app')
@section('title', 'Run Through ของฉัน')

@section('content')
    <h1 class="page-title mb-3"><i class="bi bi-arrow-repeat"></i> ประวัติ Run Through</h1>

    @forelse($runThroughs as $rt)
        <div class="card mb-3" style="border-radius:16px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <h6 class="fw-bold mb-0" style="font-family:'Prompt',sans-serif;">{{ $rt->title }}</h6>
                    <span class="badge {{ $rt->practiceResultBadgeClass() }}">{{ $rt->practiceResultLabel() }}</span>
                </div>
                <div class="text-muted small mb-2">{{ $rt->enrollment->course->name ?? '-' }} ·
                    {{ $rt->created_at->format('d/m/Y') }}</div>
                @if ($rt->description)
                    <p class="small mb-2">{{ $rt->description }}</p>
                @endif
                <div class="d-flex flex-wrap gap-2 mb-2">
                    @foreach ($rt->attachments as $att)
                        <a href="{{ $att->url() }}" target="_blank"
                            class="badge text-bg-light border text-decoration-none"><i class="bi bi-paperclip"></i>
                            {{ $att->original_name }}</a>
                    @endforeach
                </div>
                @if ($rt->areas_to_improve)
                    <div class="alert alert-warning small mb-1"><i class="bi bi-flag"></i>
                        <strong>สิ่งที่ต้องฝึกเพิ่มเติม:</strong> {{ $rt->areas_to_improve }}</div>
                @endif
                @if ($rt->teacher_comment)
                    <p class="small mb-0"><strong>ความเห็นอาจารย์:</strong> {{ $rt->teacher_comment }}</p>
                @endif
            </div>
        </div>
    @empty
        <p class="text-muted">ยังไม่มี Run Through</p>
    @endforelse
@endsection

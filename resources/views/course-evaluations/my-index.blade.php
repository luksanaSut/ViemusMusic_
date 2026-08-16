@extends('layouts.app')
@section('title', 'ผลประเมินจบคอร์ส')

@section('content')
    <h1 class="page-title mb-3"><i class="bi bi-clipboard-data"></i> ผลประเมินจบคอร์ส</h1>

    @forelse($evaluations as $ev)
        <div class="card mb-3" style="border-radius:16px;">
            <div class="card-body">
                <h6 class="fw-bold" style="font-family:'Prompt',sans-serif;">{{ $ev->enrollment->student->full_name }} —
                    {{ $ev->enrollment->course->name }}</h6>
                <div class="text-muted small mb-2">ประเมินเมื่อ {{ $ev->evaluated_at?->format('d/m/Y') }} · คะแนนเฉลี่ย
                    {{ $ev->averageScore() }}/5</div>
                @foreach ($ev->items as $item)
                    <div class="d-flex justify-content-between border-bottom py-1 small">
                        <span>{{ $item->category->name ?? '-' }}</span>
                        <span class="fw-bold">{{ $item->score }}/5</span>
                    </div>
                @endforeach
                @if ($ev->overall_comment)
                    <p class="small mt-2 mb-0"><strong>ความเห็นโดยรวม:</strong> {{ $ev->overall_comment }}</p>
                @endif
            </div>
        </div>
    @empty
        <p class="text-muted">ยังไม่มีผลประเมินจบคอร์ส</p>
    @endforelse
@endsection

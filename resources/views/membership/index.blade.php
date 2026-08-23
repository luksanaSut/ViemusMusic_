@extends('layouts.app')
@section('title', 'สมาชิกของฉัน')

@section('content')
    <style>
        .stat-tile {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.2rem 1.4rem;
            height: 100%;
        }

        .student-card {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.4rem 1.6rem;
            margin-bottom: 1.25rem;
        }

        .student-card .head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: .5rem;
            margin-bottom: 1rem;
        }

        .progress-thin {
            height: 8px;
            border-radius: 6px;
        }

        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
        }

        .empty-state .icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent, #1f3350);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1rem;
        }
    </style>

    <div class="breadcrumb-sm">สมาชิก / แต้มสะสม <i class="bi bi-chevron-right small"></i> สมาชิกของฉัน</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title mb-0">สมาชิกของฉัน</h1>
            <div class="page-sub">สถานะระดับสมาชิกและสิทธิประโยชน์</div>
        </div>
        <a href="{{ route('membership.my-points') }}" class="btn btn-outline-secondary"><i class="bi bi-star"></i>
            ดูแต้มสะสม</a>
    </div>

    @if ($students->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="icon-wrap"><i class="bi bi-award"></i></div>
                <h5 class="fw-bold mb-1">ไม่พบข้อมูลนักเรียน</h5>
                <p class="text-muted small mb-0">บัญชีนี้ยังไม่ได้ผูกกับข้อมูลนักเรียน</p>
            </div>
        </div>
    @else
        @foreach ($students as $student)
            @php
                $membership = $student->membership;
                $currentTier = $membership?->tier;
                $spend = (float) ($membership?->total_spend_12m ?? 0);
                $nextTier = $tiers->first(fn($t) => (float) $t->min_spend > $spend);
                $progressPercent = $nextTier
                    ? min(100, round(($spend / max(1, (float) $nextTier->min_spend)) * 100))
                    : 100;
            @endphp
            <div class="student-card">
                <div class="head">
                    <div>
                        <div class="fw-bold fs-5" style="font-family:'Prompt',sans-serif;">{{ $student->full_name }}</div>
                        <div class="text-muted small">{{ $student->student_code }}</div>
                    </div>
                    @if ($currentTier)
                        <span class="badge {{ $currentTier->badgeClass() }} fs-6"><i class="bi bi-award me-1"></i>
                            {{ $currentTier->name }}</span>
                    @else
                        <span class="badge text-bg-light border">ยังไม่มีระดับสมาชิก</span>
                    @endif
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="stat-tile">
                            <div class="text-muted small mb-1">ยอดใช้จ่ายสะสม (12 เดือนล่าสุด)</div>
                            <div class="fs-4 fw-bold" style="font-family:'Prompt',sans-serif;">฿{{ number_format($spend, 0) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-tile">
                            <div class="text-muted small mb-1">ยอดใช้จ่ายสะสมตลอดชีพ</div>
                            <div class="fs-4 fw-bold" style="font-family:'Prompt',sans-serif;">
                                ฿{{ number_format($membership?->lifetime_spend ?? 0, 0) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-tile">
                            <div class="text-muted small mb-1">ทบทวนระดับล่าสุด</div>
                            <div class="fw-semibold">{{ $membership?->renewed_at?->translatedFormat('d M Y') ?? '-' }}</div>
                            <div class="text-muted small mt-1">ครั้งถัดไป
                                {{ $membership?->next_review_at?->translatedFormat('d M Y') ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                @if ($nextTier)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>ความคืบหน้าสู่ระดับ {{ $nextTier->name }}</span>
                            <span>฿{{ number_format($spend, 0) }} / ฿{{ number_format($nextTier->min_spend, 0) }}</span>
                        </div>
                        <div class="progress progress-thin">
                            <div class="progress-bar {{ $nextTier->badgeClass() === 'text-bg-secondary' ? 'bg-secondary' : '' }}"
                                style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>
                @endif

                @if ($currentTier && $currentTier->benefitsList())
                    <div class="border rounded-3 p-3 bg-light">
                        <div class="fw-semibold small mb-2"><i class="bi bi-gift me-1"></i> สิทธิประโยชน์ของคุณ</div>
                        <ul class="mb-0 ps-3 small">
                            @foreach ($currentTier->benefitsList() as $benefit)
                                <li>{{ $benefit }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
@endsection

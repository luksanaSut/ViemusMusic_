@extends('layouts.app')
@section('title', 'แต้มสะสมของฉัน')

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

    <div class="breadcrumb-sm">สมาชิก / แต้มสะสม <i class="bi bi-chevron-right small"></i> แต้มสะสมของฉัน</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title mb-0">แต้มสะสมของฉัน</h1>
            <div class="page-sub">ยอดแต้มคงเหลือและประวัติการสะสม/ใช้แต้ม</div>
        </div>
        <a href="{{ route('membership.my-index') }}" class="btn btn-outline-secondary"><i class="bi bi-award"></i>
            ดูสถานะสมาชิก</a>
    </div>

    @if ($students->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="icon-wrap"><i class="bi bi-star"></i></div>
                <h5 class="fw-bold mb-1">ไม่พบข้อมูลนักเรียน</h5>
                <p class="text-muted small mb-0">บัญชีนี้ยังไม่ได้ผูกกับข้อมูลนักเรียน</p>
            </div>
        </div>
    @else
        @foreach ($students as $student)
            @php $nextExpiring = $student->nextExpiringPointBatch(); @endphp
            <div class="student-card">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <div class="fw-bold fs-5" style="font-family:'Prompt',sans-serif;">{{ $student->full_name }}</div>
                        <div class="text-muted small">{{ $student->student_code }}</div>
                    </div>
                    <div class="stat-tile" style="min-width:180px;">
                        <div class="text-muted small mb-1">แต้มคงเหลือ</div>
                        <div class="fs-3 fw-bold" style="font-family:'Prompt',sans-serif;">
                            {{ number_format($student->pointBalance()) }} แต้ม</div>
                    </div>
                </div>

                @if ($nextExpiring)
                    <div class="alert alert-warning small mb-3">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        แต้มจำนวน {{ number_format($nextExpiring->remaining_points) }} แต้ม จะหมดอายุวันที่
                        {{ $nextExpiring->expires_at->translatedFormat('d M Y') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-sm table-clean">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>ประเภท</th>
                                <th>จำนวน</th>
                                <th>ยอดคงเหลือ</th>
                                <th>วันหมดอายุ</th>
                                <th>เหตุผล</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($student->pointTransactions as $t)
                                <tr>
                                    <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $t->typeLabel() }}</td>
                                    <td class="{{ $t->points < 0 ? 'text-danger' : 'text-success' }} fw-semibold">
                                        {{ $t->points > 0 ? '+' : '' }}{{ number_format($t->points) }}</td>
                                    <td class="fw-semibold">{{ number_format($t->balance_after) }}</td>
                                    <td>{{ $t->expires_at?->format('d/m/Y') ?: '-' }}</td>
                                    <td>{{ $t->reason ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state py-3"><i class="bi bi-star"></i> ยังไม่มีประวัติแต้มสะสม</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
@endsection

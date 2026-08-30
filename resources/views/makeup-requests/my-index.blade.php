@extends('layouts.app')
@section('title', 'คำขอสอนชดเชย')

@section('content')
    <style>
        .makeup-list { display: flex; flex-direction: column; gap: .65rem; }

        .makeup-item {
            position: relative;
            border: 1px solid var(--border);
            border-radius: 13px;
            padding: .9rem 1rem .9rem 1.1rem;
            background: #fff;
        }

        .makeup-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 13px 0 0 13px;
            background: var(--border);
        }

        .makeup-item.is-pending::before { background: #e0a415; }
        .makeup-item.is-approved::before { background: #2f9e5b; }
        .makeup-item.is-rejected::before { background: #c0392b; }
        .makeup-item.is-completed::before { background: var(--accent); }
        .makeup-item.is-cancelled::before { background: #9aa0aa; }

        .makeup-item.is-overdue { background: #fff8ee; }

        .makeup-item-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: .75rem;
            flex-wrap: wrap;
        }

        .makeup-item-title { font-weight: 600; font-size: .92rem; }
        .makeup-item-course { color: var(--muted); font-size: .8rem; margin-top: .1rem; }

        .makeup-item-datetime {
            font-size: .8rem;
            color: var(--ink-soft);
            margin-top: .55rem;
            display: flex;
            align-items: center;
            gap: .4rem;
            flex-wrap: wrap;
        }

        .makeup-item-approvals {
            display: flex;
            gap: .4rem;
            margin-top: .55rem;
            flex-wrap: wrap;
        }

        .makeup-item-approvals .badge { font-weight: 500; }

        .makeup-item-actions {
            display: flex;
            gap: .4rem;
            margin-top: .7rem;
            padding-top: .65rem;
            border-top: 1px dashed var(--border);
        }

        .makeup-empty { text-align: center; padding: 2.2rem 1rem; color: var(--muted); }
        .makeup-empty i { font-size: 1.6rem; display: block; margin-bottom: .5rem; opacity: .6; }
    </style>

    <div class="breadcrumb-sm">ตารางและคำร้อง <i class="bi bi-chevron-right small"></i> คำขอสอนชดเชย</div>
    <h1 class="page-title mb-1"><i class="bi bi-calendar-plus"></i> คำขอสอนชดเชย</h1>
    <p class="page-sub mb-3">รายการคำขอเรียนชดเชยที่มอบหมายให้ {{ $teacher->nickname ?: $teacher->full_name }}
        เป็นผู้สอน</p>

    <div class="card" style="border-radius:16px;">
        <div class="card-body">
            <div class="makeup-list">
                @forelse($requests as $r)
                    <div class="makeup-item is-{{ $r->overall_status }} {{ $r->is_overdue ? 'is-overdue' : '' }}">
                        <div class="makeup-item-top">
                            <div>
                                <div class="makeup-item-title">{{ $r->student->full_name ?? '-' }}</div>
                                <div class="makeup-item-course">{{ $r->enrollment->course->name ?? '-' }}</div>
                            </div>
                            <span class="badge {{ $r->overallStatusBadgeClass() }}">{{ $r->overallStatusLabel() }}</span>
                        </div>

                        <div class="makeup-item-datetime">
                            <i class="bi bi-calendar-event text-muted"></i>
                            {{ $r->makeup_date->format('d/m/Y') }} {{ $r->start_time }}-{{ $r->end_time }}
                            @if ($r->is_overdue)
                                <span class="badge text-bg-danger">เกินกำหนด</span>
                            @endif
                        </div>

                        <div class="makeup-item-approvals">
                            <span
                                class="badge {{ $r->admin_approval_status == 'approved' ? 'text-bg-success' : ($r->admin_approval_status == 'rejected' ? 'text-bg-danger' : 'text-bg-warning') }}">
                                Admin: {{ $r->admin_approval_status }}
                            </span>
                            <span
                                class="badge {{ $r->instructor_approval_status == 'approved' ? 'text-bg-success' : ($r->instructor_approval_status == 'rejected' ? 'text-bg-danger' : 'text-bg-warning') }}">
                                คุณ: {{ $r->instructor_approval_status }}
                            </span>
                        </div>

                        <div class="makeup-item-actions">
                            @if ($r->instructor_approval_status === 'pending')
                                <form action="{{ route('makeup-requests.approve-instructor', $r) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-check-lg"></i> อนุมัติ
                                    </button>
                                </form>
                                <form action="{{ route('makeup-requests.reject', $r) }}" method="POST"
                                    onsubmit="return confirm('ปฏิเสธคำขอนี้?')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x-lg"></i> ปฏิเสธ
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('makeup-requests.show', $r) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i> ดูรายละเอียด
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="makeup-empty">
                        <i class="bi bi-calendar-check"></i>
                        ยังไม่มีคำขอสอนชดเชยที่มอบหมายให้คุณ
                    </div>
                @endforelse
            </div>

            @if ($requests->hasPages())
                <div class="mt-3">{{ $requests->links() }}</div>
            @endif
        </div>
    </div>
@endsection

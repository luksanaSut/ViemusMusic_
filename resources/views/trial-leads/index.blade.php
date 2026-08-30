@extends('layouts.app')
@section('title','ผู้สนใจและทดลองเรียน')

@section('content')
    <style>
        .stat-row {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: .75rem;
        }

        @media (max-width: 1100px) {
            .stat-row {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 575.98px) {
            .stat-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .stat-card {
            background: var(--card, #fff);
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 14px;
            padding: .85rem 1rem;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            gap: .35rem;
            transition: border-color .15s;
        }

        .stat-card:hover {
            border-color: var(--accent, #1f3350);
            color: inherit;
        }

        .stat-card.is-active {
            border-color: var(--accent, #1f3350);
            background: var(--accent-soft, #e7ebf1);
        }

        .stat-card .icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            flex-shrink: 0;
        }

        .stat-card .icon.new { background: #e7ebf1; color: #1f3350; }
        .stat-card .icon.contacted { background: #ece9f6; color: #4b3f8a; }
        .stat-card .icon.scheduled { background: var(--accent-soft, #e7ebf1); color: var(--accent, #1f3350); }
        .stat-card .icon.completed { background: var(--amber-soft, #f3ece2); color: var(--amber, #8a5a2b); }
        .stat-card .icon.converted { background: var(--success-soft, #e7f2ec); color: var(--success, #2f6f4e); }
        .stat-card .icon.followup { background: #fbeae7; color: #b3392c; }

        .stat-card .value {
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: 1.3rem;
            line-height: 1.1;
        }

        .stat-card .label {
            color: var(--muted, #6b655e);
            font-size: .76rem;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            font-size: .88rem;
        }

        .lead-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--accent, #1f3350), var(--accent-dark, #13233a));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: .95rem;
        }

        .leads-table th {
            font-size: .76rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--muted, #6b655e);
            font-weight: 600;
            border-bottom-width: 1px;
            white-space: nowrap;
        }

        .leads-table td {
            vertical-align: middle;
        }

        .badge-status {
            font-weight: 600;
            padding: .38rem .65rem;
            border-radius: 8px;
        }

        .badge-status.st-new { background: #e7ebf1; color: #1f3350; }
        .badge-status.st-contacted { background: #ece9f6; color: #4b3f8a; }
        .badge-status.st-scheduled { background: var(--accent-soft, #e7ebf1); color: var(--accent, #1f3350); }
        .badge-status.st-completed { background: var(--amber-soft, #f3ece2); color: var(--amber, #8a5a2b); }
        .badge-status.st-converted { background: var(--success-soft, #e7f2ec); color: var(--success, #2f6f4e); }
        .badge-status.st-lost { background: #f1efec; color: #6b655e; }

        .badge-pay {
            font-weight: 600;
            padding: .38rem .65rem;
            border-radius: 8px;
        }

        .badge-pay.paid { background: var(--success-soft, #e7f2ec); color: var(--success, #2f6f4e); }
        .badge-pay.unpaid { background: var(--amber-soft, #f3ece2); color: var(--amber, #8a5a2b); }
        .badge-pay.other { background: #f1efec; color: #6b655e; }

        .empty-state {
            padding: 3.5rem 1rem;
            text-align: center;
            color: var(--muted, #6b655e);
        }

        .empty-state i {
            font-size: 2.4rem;
            color: var(--border, #e4e1dc);
            margin-bottom: .6rem;
            display: block;
        }
    </style>

    <div class="breadcrumb-sm">บุคคล <i class="bi bi-chevron-right small"></i> ผู้สนใจ</div>
    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
        <div>
            <h1 class="page-title">ผู้สนใจและทดลองเรียน</h1>
            <div class="page-sub">ติดตามตั้งแต่รับข้อมูลจนสมัครเป็นนักเรียน</div>
        </div>
        <a href="{{ route('trial-leads.create') }}" class="btn btn-accent"><i class="bi bi-plus-lg"></i> เพิ่มผู้สนใจ</a>
    </div>

    <div class="stat-row mb-3">
        @php
            $statusIcons = [
                'new' => 'bi-person-plus', 'contacted' => 'bi-telephone', 'scheduled' => 'bi-calendar-check',
                'completed' => 'bi-mortarboard', 'converted' => 'bi-check-circle',
            ];
        @endphp
        @foreach(['new'=>'ใหม่','contacted'=>'ติดต่อแล้ว','scheduled'=>'นัดทดลอง','completed'=>'ทดลองแล้ว','converted'=>'สมัครแล้ว'] as $key=>$label)
            <a href="{{ route('trial-leads.index',['status'=>$key]) }}" class="stat-card {{ $status === $key ? 'is-active' : '' }}">
                <div class="icon {{ $key }}"><i class="bi {{ $statusIcons[$key] }}"></i></div>
                <div>
                    <div class="value">{{ $counts[$key] ?? 0 }}</div>
                    <div class="label">{{ $label }}</div>
                </div>
            </a>
        @endforeach
        <div class="stat-card">
            <div class="icon followup"><i class="bi bi-bell"></i></div>
            <div>
                <div class="value">{{ $followUpCount }}</div>
                <div class="label">ต้องติดตาม</div>
            </div>
        </div>
    </div>

    <div class="card mb-3 filter-card">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute" style="left:.8rem; top:50%; transform:translateY(-50%); color:var(--muted);"></i>
                        <input name="q" class="form-control ps-5" value="{{ request('q') }}" placeholder="ค้นหาชื่อ เบอร์โทร หรือเลข Lead">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        @foreach(['new'=>'ผู้สนใจใหม่','contacted'=>'ติดต่อแล้ว','scheduled'=>'นัดทดลองแล้ว','completed'=>'ทดลองแล้ว','converted'=>'สมัครเรียนแล้ว','lost'=>'ไม่ดำเนินการต่อ'] as $value=>$label)
                            <option value="{{ $value }}" @selected($status===$value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-accent"><i class="bi bi-search"></i> ค้นหา</button>
                </div>
                @if (request('q') || $status)
                    <div class="col-md-2 d-grid">
                        <a href="{{ route('trial-leads.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i> ล้างตัวกรอง</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 leads-table">
                <thead class="table-light">
                    <tr>
                        <th>ผู้สนใจ</th>
                        <th>สนใจ</th>
                        <th>นัดทดลอง</th>
                        <th>ครู</th>
                        <th>ชำระเงิน</th>
                        <th>สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="lead-avatar">{{ mb_substr($lead->student_name, 0, 1) }}</div>
                                    <div>
                                        <a class="fw-semibold text-decoration-none text-body" href="{{ route('trial-leads.show',$lead) }}">{{ $lead->student_name }}</a>
                                        <div class="small text-muted">{{ $lead->lead_no }} · {{ $lead->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $lead->course?->name ?? $lead->interest ?? '-' }}</td>
                            <td>
                                {{ $lead->trial_date?->format('d/m/Y') ?? '-' }}
                                @if($lead->trial_start_time)
                                    <div class="small text-muted">{{ substr($lead->trial_start_time,0,5) }}–{{ substr($lead->trial_end_time,0,5) }}</div>
                                @endif
                            </td>
                            <td>{{ $lead->teacher?->full_name ?? '-' }}</td>
                            <td>
                                @php $payClass = $lead->payment_status==='paid' ? 'paid' : ($lead->payment_status==='unpaid' ? 'unpaid' : 'other'); @endphp
                                <span class="badge-pay {{ $payClass }}">{{ ['unpaid'=>'ยังไม่ชำระ','paid'=>'ชำระแล้ว','waived'=>'ยกเว้น','refunded'=>'คืนเงิน'][$lead->payment_status] }}</span>
                            </td>
                            <td><span class="badge-status st-{{ $lead->status }}">{{ $lead->statusLabel() }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('trial-leads.show',$lead) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i> ดู</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-person-plus"></i>
                                    ยังไม่มีข้อมูลผู้สนใจ
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $leads->links() }}</div>
    </div>
@endsection

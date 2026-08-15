@extends('layouts.app')
@section('title', 'การลาเรียนของฉัน')

@section('content')
    <style>
        .stat-card {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.2rem 1.4rem;
            height: 100%;
        }

        .stat-card .head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: .7rem;
        }

        .stat-card .label {
            font-size: .82rem;
            color: var(--muted, #6b655e);
            font-weight: 600;
        }

        .stat-card .icon-box {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .stat-card .value {
            font-size: 1.9rem;
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            line-height: 1;
        }

        .stat-card .value .unit {
            font-size: .95rem;
            font-weight: 500;
            color: var(--muted, #6b655e);
        }

        .stat-card .sub {
            font-size: .78rem;
            margin-top: .4rem;
        }

        .sub.warn {
            color: var(--amber, #8a5a2b);
        }

        .sub.ok {
            color: var(--success, #2f6f4e);
        }

        .sub.danger {
            color: #b3392c;
        }

        .sub.muted {
            color: var(--muted, #6b655e);
        }

        .leave-tabs {
            display: flex;
            gap: 1.6rem;
            border-bottom: 1px solid var(--border, #e4e1dc);
            margin-bottom: 1.2rem;
        }

        .leave-tab {
            padding: .7rem 0;
            font-size: .88rem;
            font-weight: 600;
            color: var(--muted, #6b655e);
            text-decoration: none;
            border-bottom: 2px solid transparent;
            display: flex;
            align-items: center;
            gap: .4rem;
        }

        .leave-tab.active {
            color: var(--ink, #1c1a17);
            border-bottom-color: var(--accent, #1f3350);
        }

        .leave-tab .count {
            font-size: .78rem;
            color: var(--muted, #6b655e);
        }

        .leave-tab.active .count {
            color: var(--accent, #1f3350);
        }

        .rules-box {
            background: #fbf7f0;
            border: 1px solid #e6d9c3;
            border-radius: 14px;
            padding: 1rem 1.3rem;
            margin-bottom: 1.2rem;
            display: flex;
            gap: .9rem;
        }

        .rules-box .icon-box {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #f3ece2;
            color: #8a5a2b;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .rules-box ul {
            margin: .3rem 0 0;
            padding-left: 1.1rem;
            font-size: .85rem;
        }

        .table-clean thead th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--muted, #6b655e);
            border-top: 0;
        }
    </style>

    <div class="breadcrumb-sm">ของฉัน <i class="bi bi-chevron-right small"></i> ลาเรียน</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title">การลาเรียนของฉัน</h1>
            <div class="page-sub">ส่งคำขอลาและเลือกวันเรียนชดเชยในรายการเดียว</div>
        </div>
        <a href="{{ route('leaves.create') }}" class="btn btn-accent"><i class="bi bi-plus-lg"></i> แจ้งลาเรียน</a>
    </div>

    {{-- ===== สถิติสรุป (คำนวณจากข้อมูลจริง) ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="head">
                    <span class="label">รออนุมัติ</span>
                    <div class="icon-box" style="background:var(--amber-soft,#f3ece2);color:var(--amber,#8a5a2b);"><i
                            class="bi bi-clipboard-check"></i></div>
                </div>
                <div class="value">{{ $pendingCount }} <span class="unit">คำขอ</span></div>
                <div class="sub {{ $pendingCount > 0 ? 'warn' : 'muted' }}">
                    {{ $pendingCount > 0 ? 'ต้องรอการพิจารณา' : 'ไม่มีคำขอค้าง' }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="head">
                    <span class="label">อนุมัติเดือนนี้</span>
                    <div class="icon-box" style="background:var(--success-soft,#e7f2ec);color:var(--success,#2f6f4e);"><i
                            class="bi bi-check-lg"></i></div>
                </div>
                <div class="value">{{ $approvedNormalCount + $approvedEmergencyCount }} <span class="unit">ครั้ง</span>
                </div>
                <div class="sub ok">ปกติ {{ $approvedNormalCount }} · ฉุกเฉิน {{ $approvedEmergencyCount }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="head">
                    <span class="label">ใช้สิทธิ์ลาฉุกเฉินครบแล้ว</span>
                    <div class="icon-box" style="background:#fbeae7;color:#b3392c;"><i
                            class="bi bi-exclamation-triangle"></i></div>
                </div>
                <div class="value">{{ $emergencyFullEnrollments->count() }} <span class="unit">คอร์ส</span></div>
                <div class="sub {{ $emergencyFullEnrollments->count() > 0 ? 'danger' : 'muted' }}">
                    @if ($emergencyFullEnrollments->count() > 0)
                        {{ $emergencyFullEnrollments->pluck('course.name')->filter()->take(2)->join(', ') }}{{ $emergencyFullEnrollments->count() > 2 ? ' ฯลฯ' : '' }}
                    @else
                        ยังมีสิทธิ์ทุกคอร์ส
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="head">
                    <span class="label">ชั่วโมงชดเชยคงค้าง</span>
                    <div class="icon-box" style="background:var(--accent-soft,#e7ebf1);color:var(--accent-dark,#13233a);"><i
                            class="bi bi-clock-history"></i></div>
                </div>
                <div class="value">{{ number_format($pendingMakeupHours, 0) }} <span class="unit">ชม.</span></div>
                <div class="sub {{ $overdueCount > 0 ? 'danger' : 'muted' }}">
                    {{ $overdueCount > 0 ? "{$overdueCount} รายการเกินกำหนด" : 'ไม่มีรายการเกินกำหนด' }}</div>
            </div>
        </div>
    </div>

    {{-- ===== แท็บ ===== --}}
    <div class="leave-tabs">
        <a href="{{ route('leaves.index', ['tab' => 'pending']) }}"
            class="leave-tab {{ $tab == 'pending' ? 'active' : '' }}">รออนุมัติ <span
                class="count">{{ $leaves->where('status', 'pending')->count() }}</span></a>
        <a href="{{ route('leaves.index', ['tab' => 'approved']) }}"
            class="leave-tab {{ $tab == 'approved' ? 'active' : '' }}">อนุมัติแล้ว <span
                class="count">{{ $leaves->where('status', 'approved')->count() }}</span></a>
        <a href="{{ route('leaves.index', ['tab' => 'rejected']) }}"
            class="leave-tab {{ $tab == 'rejected' ? 'active' : '' }}">ปฏิเสธ <span
                class="count">{{ $leaves->where('status', 'rejected')->count() }}</span></a>
        <a href="{{ route('leaves.index', ['tab' => 'all']) }}"
            class="leave-tab {{ $tab == 'all' ? 'active' : '' }}">ทั้งหมด <span
                class="count">{{ $leaves->count() }}</span></a>
    </div>

    {{-- ===== กฎการลาเรียนที่ระบบบังคับใช้จริง ===== --}}
    <div class="rules-box">
        <div class="icon-box"><i class="bi bi-info-circle"></i></div>
        <div>
            <strong style="font-family:'Prompt',sans-serif;">กฎการลาเรียนที่ระบบบังคับใช้</strong>
            <ul class="mb-0">
                <li>ลาปกติ/ลาไม่ชดเชย ต้องแจ้งล่วงหน้าอย่างน้อย
                    <strong>{{ config('leave.normal_advance_notice_hours', 24) }} ชั่วโมง</strong></li>
                <li>ลาฉุกเฉินแจ้งกะทันหันได้ แต่ใช้สิทธิ์ได้ตามจำนวนที่แต่ละคอร์สกำหนดไว้เท่านั้น (ปกติ 1 ครั้ง/คอร์ส)</li>
                <li>ลาปกติต้องเลือกวันเรียนชดเชยพร้อมกันในคำขอเดียว</li>
            </ul>
        </div>
    </div>

    {{-- ===== ตาราง ===== --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm table-clean align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>เลขที่</th>
                        <th>นักเรียน</th>
                        <th>ประเภท</th>
                        <th>แจ้งล่วงหน้า</th>
                        <th>สิทธิ์ที่ใช้</th>
                        <th>เรียนชดเชย</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($filtered as $lv)
                        @php
                            $noticeHours = $lv->created_at->diffInHours($lv->leave_date->startOfDay());
                            $enr = $lv->enrollment;
                        @endphp
                        <tr>
                            <td>#{{ $lv->id }}</td>
                            <td>{{ $lv->student->full_name ?? '-' }}</td>
                            <td><span
                                    class="badge {{ $lv->leave_type == 'emergency' ? 'text-bg-danger' : 'text-bg-light border' }}">{{ $lv->leaveTypeLabel() }}</span>
                            </td>
                            <td class="small">
                                {{ $noticeHours >= 24 ? floor($noticeHours / 24) . ' วัน' : $noticeHours . ' ชม.' }} ก่อนวันลา
                            </td>
                            <td class="small">
                                @if ($lv->leave_type === 'emergency' && $enr)
                                    {{ $enr->emergencyLeaveUsed() }}/{{ $enr->emergencyLeaveQuota() }} ครั้ง
                                @else
                                    -
                                @endif
                            </td>
                            <td class="small">
                                @if ($lv->makeupRequest)
                                    {{ $lv->makeupRequest->makeup_date->format('d/m/Y') }}
                                    {{ $lv->makeupRequest->start_time }}-{{ $lv->makeupRequest->end_time }}
                                    <div><span
                                            class="badge {{ $lv->makeupRequest->overallStatusBadgeClass() }}">{{ $lv->makeupRequest->overallStatusLabel() }}</span>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td><span class="badge {{ $lv->statusBadgeClass() }}">{{ $lv->statusLabel() }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">ไม่พบข้อมูล</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

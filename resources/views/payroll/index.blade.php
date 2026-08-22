@extends('layouts.app')
@section('title', 'ระบบเงินเดือนอาจารย์')

@section('content')
    <div class="breadcrumb-sm">ระบบ <i class="bi bi-chevron-right small"></i> เงินเดือนอาจารย์</div>
    <h1 class="page-title mb-3"><i class="bi bi-cash-stack"></i> ระบบเงินเดือนอาจารย์ (Payroll)</h1>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small">จากวันที่</label>
                    <input type="date" name="period_start" value="{{ $periodStart }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">ถึงวันที่</label>
                    <input type="date" name="period_end" value="{{ $periodEnd }}" class="form-control">
                </div>
                <div class="col-md-2"><button class="btn btn-accent w-100">ดูรอบนี้</button></div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>อาจารย์</th>
                        <th>จำนวนคาบที่สอนจริง</th>
                        <th>รายได้ประมาณการ</th>
                        <th>สถานะรอบจ่าย</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($summaries as $s)
                        <tr>
                            <td>{{ $s['teacher']->full_name }}</td>
                            <td>{{ $s['session_count'] }} คาบ</td>
                            <td class="fw-semibold">฿{{ number_format($s['income_estimate'], 2) }}</td>
                            <td>
                                @if ($s['run'])
                                    <span
                                        class="badge {{ $s['run']->statusBadgeClass() }}">{{ $s['run']->statusLabel() }}</span>
                                @else
                                    <span class="badge text-bg-light border">ยังไม่สร้าง</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('payroll.show', ['teacher' => $s['teacher'], 'period_start' => $periodStart, 'period_end' => $periodEnd]) }}"
                                    class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i> {{ $s['run'] ? 'ดูรายละเอียด' : 'สร้างรอบจ่าย' }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <h5 class="fw-bold mb-2" style="font-family:'Prompt',sans-serif;">ประวัติการจ่ายเงินย้อนหลัง</h5>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>อาจารย์</th>
                        <th>รอบ</th>
                        <th>ยอดรวม</th>
                        <th>สถานะ</th>
                        <th>วันที่จ่าย</th>
                        <th class="text-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $run)
                        <tr>
                            <td>{{ $run->teacher->full_name ?? '-' }}</td>
                            <td class="small">{{ $run->periodLabel() }}</td>
                            <td class="fw-semibold">฿{{ number_format($run->total_amount, 2) }}</td>
                            <td><span class="badge {{ $run->statusBadgeClass() }}">{{ $run->statusLabel() }}</span></td>
                            <td class="small">{{ $run->paid_date?->format('d/m/Y') ?: '-' }}</td>
                            <td class="text-end"><a
                                    href="{{ route('payroll.show', ['teacher' => $run->teacher, 'period_start' => $run->period_start->toDateString(), 'period_end' => $run->period_end->toDateString()]) }}"
                                    class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">ยังไม่มีประวัติการจ่ายเงิน</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $history->links() }}</div>
    </div>
@endsection

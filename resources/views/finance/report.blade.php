@extends('layouts.app')
@section('title', 'รายงานการเงิน')

@section('content')
    <style>
        .period-pill {
            border-radius: 10px;
            padding: .5rem 1.1rem;
            font-size: .85rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--muted, #6b655e);
            background: #f4f3f1;
            display: inline-block;
        }

        .period-pill.active {
            background: var(--ink, #1c1a17);
            color: #fff;
        }

        .breakdown-row {
            display: flex;
            justify-content: space-between;
            padding: .6rem 0;
            border-bottom: 1px solid #f0efec;
        }

        .breakdown-row:last-child {
            border-bottom: 0;
        }
    </style>

    <div class="breadcrumb-sm">การเงิน <i class="bi bi-chevron-right small"></i> รายงานการเงิน</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title mb-0">รายงานการเงิน</h1>
            <div class="page-sub">{{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}</div>
        </div>
        <a href="{{ route('finance.report.export', request()->query()) }}" class="btn btn-outline-secondary">
            <i class="bi bi-download"></i> ส่งออก CSV
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ route('finance.report', ['period' => 'daily']) }}"
                    class="period-pill {{ $period === 'daily' ? 'active' : '' }}">รายวัน</a>
                <a href="{{ route('finance.report', ['period' => 'weekly']) }}"
                    class="period-pill {{ $period === 'weekly' ? 'active' : '' }}">รายสัปดาห์</a>
                <a href="{{ route('finance.report', ['period' => 'monthly']) }}"
                    class="period-pill {{ $period === 'monthly' ? 'active' : '' }}">รายเดือน</a>
                <a href="{{ route('finance.report', ['period' => 'yearly']) }}"
                    class="period-pill {{ $period === 'yearly' ? 'active' : '' }}">รายปี</a>
            </div>

            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small">หรือกำหนดช่วงเอง — จากวันที่</label>
                    <input type="date" name="date_from" value="{{ request('date_from', $start->toDateString()) }}"
                        class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small">ถึงวันที่</label>
                    <input type="date" name="date_to" value="{{ request('date_to', $end->toDateString()) }}"
                        class="form-control">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i> ดูรายงาน</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white py-3 fw-semibold">
                    <i class="bi bi-arrow-down-circle text-success me-1"></i> รายรับ
                </div>
                <div class="card-body">
                    <div class="breakdown-row">
                        <span>คอร์สเรียน</span>
                        <span class="fw-semibold">฿{{ number_format($summary['income']['course'], 2) }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span>ค่าทดลองเรียน</span>
                        <span class="fw-semibold">฿{{ number_format($summary['income']['trial'], 2) }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span>ขายสินค้า</span>
                        <span class="fw-semibold">฿{{ number_format($summary['income']['product'], 2) }}</span>
                    </div>
                    <div class="breakdown-row fw-bold">
                        <span>รวมรายรับ</span>
                        <span>฿{{ number_format($summary['income']['total'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white py-3 fw-semibold">
                    <i class="bi bi-arrow-up-circle text-danger me-1"></i> รายจ่าย
                </div>
                <div class="card-body">
                    <div class="breakdown-row">
                        <span>คอร์สเรียน</span>
                        <span class="fw-semibold">฿{{ number_format($summary['expense']['course'], 2) }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span>ค่าซื้อสินค้า</span>
                        <span class="fw-semibold">฿{{ number_format($summary['expense']['product_cost'], 2) }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span>ค่าเช่า</span>
                        <span class="fw-semibold">฿{{ number_format($summary['expense']['rent'], 2) }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span>ค่าพนักงาน</span>
                        <span class="fw-semibold">฿{{ number_format($summary['expense']['staff'], 2) }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span>ค่าซ่อมบำรุง</span>
                        <span class="fw-semibold">฿{{ number_format($summary['expense']['maintenance'], 2) }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span>ค่าใช้จ่ายอื่นๆ</span>
                        <span class="fw-semibold">฿{{ number_format($summary['expense']['other'], 2) }}</span>
                    </div>
                    <div class="breakdown-row fw-bold">
                        <span>รวมรายจ่าย</span>
                        <span>฿{{ number_format($summary['expense']['total'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <span class="fw-bold fs-5" style="font-family:'Prompt',sans-serif;">กำไร/ขาดทุนสุทธิ</span>
            <span class="fw-bold fs-4 {{ $summary['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}"
                style="font-family:'Prompt',sans-serif;">
                {{ $summary['net_profit'] >= 0 ? '฿' : '-฿' }}{{ number_format(abs($summary['net_profit']), 2) }}
            </span>
        </div>
    </div>
@endsection

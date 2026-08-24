@extends('layouts.app')
@section('title', 'รายงานรายได้')

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

        .stat-tile {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.2rem 1.4rem;
            height: 100%;
        }

        .stat-tile .label {
            font-size: .85rem;
            color: var(--muted, #6b655e);
            font-weight: 600;
            margin-bottom: .5rem;
        }

        .stat-tile .value {
            font-size: 1.9rem;
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            line-height: 1;
        }
    </style>

    <div class="breadcrumb-sm"><a href="{{ route('reports.index') }}" class="text-reset">รายงาน</a> <i
            class="bi bi-chevron-right small"></i> รายงานรายได้</div>
    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
        <div>
            <h1 class="page-title mb-0">รายงานรายได้</h1>
            <div class="page-sub">{{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.revenue.export-excel', request()->query()) }}" class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ route('reports.revenue.export-pdf', request()->query()) }}" class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ route('reports.revenue', ['period' => 'daily']) }}"
                    class="period-pill {{ $period === 'daily' ? 'active' : '' }}">รายวัน</a>
                <a href="{{ route('reports.revenue', ['period' => 'weekly']) }}"
                    class="period-pill {{ $period === 'weekly' ? 'active' : '' }}">รายสัปดาห์</a>
                <a href="{{ route('reports.revenue', ['period' => 'monthly']) }}"
                    class="period-pill {{ $period === 'monthly' ? 'active' : '' }}">รายเดือน</a>
                <a href="{{ route('reports.revenue', ['period' => 'yearly']) }}"
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

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="stat-tile">
                <div class="label"><i class="bi bi-journal-bookmark me-1"></i> รายได้คอร์สเรียน</div>
                <div class="value text-success">฿{{ number_format($courseIncome, 2) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-tile">
                <div class="label"><i class="bi bi-box-seam me-1"></i> รายได้ขายสินค้า</div>
                <div class="value text-success">฿{{ number_format($productIncome, 2) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-tile">
                <div class="label"><i class="bi bi-cash-stack me-1"></i> รวมรายได้</div>
                <div class="value">฿{{ number_format($courseIncome + $productIncome, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white py-3 fw-semibold">
                    <i class="bi bi-credit-card me-1"></i> แยกตามช่องทางชำระเงิน
                </div>
                <div class="card-body">
                    @php
                        $methodLabels = [
                            'cash' => 'เงินสด',
                            'transfer' => 'โอนเงิน',
                            'credit_card' => 'บัตรเครดิต',
                            'promptpay' => 'PromptPay/QR',
                            'other' => 'อื่นๆ',
                        ];
                    @endphp
                    @foreach ($byMethod as $method => $total)
                        <div class="breakdown-row">
                            <span>{{ $methodLabels[$method] ?? $method }}</span>
                            <span class="fw-semibold">฿{{ number_format($total, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white py-3 fw-semibold">
                    <i class="bi bi-geo-alt me-1"></i> แยกตามสาขา
                </div>
                <div class="card-body">
                    @foreach ($byBranch as $branch => $total)
                        <div class="breakdown-row">
                            <span>{{ $branch }}</span>
                            <span class="fw-semibold">฿{{ number_format($total, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="text-muted small mt-3">
        <i class="bi bi-info-circle"></i>
        รายได้จากการขายสินค้าไม่มีข้อมูลสาขาในระบบ จึงรวมอยู่ใต้ "ไม่ระบุสาขา" ทั้งหมด
    </div>
@endsection

@extends('layouts.app')
@section('title', 'ภาพรวมการเงิน')

@section('content')
    <style>
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

    <div class="breadcrumb-sm">การเงิน <i class="bi bi-chevron-right small"></i> ภาพรวมการเงิน</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title mb-0">ภาพรวมการเงิน</h1>
            <div class="page-sub">สรุปรายรับ-รายจ่าย เดือน {{ $start->translatedFormat('F Y') }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.report') }}" class="btn btn-outline-secondary"><i class="bi bi-bar-chart-line"></i>
                ดูรายงาน</a>
            <a href="{{ route('expenses.create') }}" class="btn btn-accent"><i class="bi bi-plus-lg"></i> เพิ่มรายจ่าย</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-tile">
                <div class="label"><i class="bi bi-arrow-down-circle text-success me-1"></i> รายรับรวม</div>
                <div class="value text-success">฿{{ number_format($summary['income']['total'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-tile">
                <div class="label"><i class="bi bi-arrow-up-circle text-danger me-1"></i> รายจ่ายรวม</div>
                <div class="value text-danger">฿{{ number_format($summary['expense']['total'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-tile">
                <div class="label"><i class="bi bi-graph-up-arrow me-1"></i> กำไร/ขาดทุนสุทธิ</div>
                <div class="value {{ $summary['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $summary['net_profit'] >= 0 ? '฿' : '-฿' }}{{ number_format(abs($summary['net_profit']), 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white py-3 fw-semibold">
                    <i class="bi bi-arrow-down-circle text-success me-1"></i> รายรับแยกตามแหล่งที่มา
                </div>
                <div class="card-body">
                    <div class="breakdown-row">
                        <span><i class="bi bi-journal-bookmark me-1 text-muted"></i> คอร์สเรียน</span>
                        <span class="fw-semibold">฿{{ number_format($summary['income']['course'], 2) }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span><i class="bi bi-box-seam me-1 text-muted"></i> ขายสินค้า</span>
                        <span class="fw-semibold">฿{{ number_format($summary['income']['product'], 2) }}</span>
                    </div>
                    <div class="breakdown-row fw-bold">
                        <span>รวม</span>
                        <span>฿{{ number_format($summary['income']['total'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white py-3 fw-semibold">
                    <i class="bi bi-arrow-up-circle text-danger me-1"></i> รายจ่ายแยกตามหมวดหมู่
                </div>
                <div class="card-body">
                    <div class="breakdown-row">
                        <span><i class="bi bi-journal-bookmark me-1 text-muted"></i> คอร์สเรียน</span>
                        <span class="fw-semibold">฿{{ number_format($summary['expense']['course'], 2) }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span><i class="bi bi-box-seam me-1 text-muted"></i> ค่าซื้อสินค้า</span>
                        <span class="fw-semibold">฿{{ number_format($summary['expense']['product_cost'], 2) }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span><i class="bi bi-building me-1 text-muted"></i> ค่าเช่า</span>
                        <span class="fw-semibold">฿{{ number_format($summary['expense']['rent'], 2) }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span><i class="bi bi-people me-1 text-muted"></i> ค่าพนักงาน</span>
                        <span class="fw-semibold">฿{{ number_format($summary['expense']['staff'], 2) }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span><i class="bi bi-tools me-1 text-muted"></i> ค่าซ่อมบำรุง</span>
                        <span class="fw-semibold">฿{{ number_format($summary['expense']['maintenance'], 2) }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span><i class="bi bi-three-dots me-1 text-muted"></i> ค่าใช้จ่ายอื่นๆ</span>
                        <span class="fw-semibold">฿{{ number_format($summary['expense']['other'], 2) }}</span>
                    </div>
                    <div class="breakdown-row fw-bold">
                        <span>รวม</span>
                        <span>฿{{ number_format($summary['expense']['total'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-muted small mt-3">
        <i class="bi bi-info-circle"></i>
        ค่าพนักงานรวมเงินเดือนอาจารย์ที่จ่ายแล้ว ฿{{ number_format($summary['expense']['teacher_payroll'], 2) }}
        และรายจ่ายพนักงานอื่นที่บันทึกมือ ฿{{ number_format($summary['expense']['staff_manual'], 2) }}
    </div>
@endsection

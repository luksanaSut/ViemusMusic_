@extends('layouts.app')
@section('title', 'รายงานนักเรียน')

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

    <div class="breadcrumb-sm"><a href="{{ route('reports.index') }}" class="text-reset">รายงาน</a> <i
            class="bi bi-chevron-right small"></i> รายงานนักเรียน</div>
    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
        <div>
            <h1 class="page-title mb-0">รายงานนักเรียน</h1>
            <div class="page-sub">{{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.students.export-excel', request()->query()) }}" class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ route('reports.students.export-pdf', request()->query()) }}" class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small">จากวันที่</label>
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
        <div class="col-md-6">
            <div class="stat-tile">
                <div class="label"><i class="bi bi-people me-1"></i> จำนวนนักเรียนทั้งหมด</div>
                <div class="value">{{ number_format($summary['total']) }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-tile">
                <div class="label"><i class="bi bi-person-plus me-1"></i> นักเรียนใหม่ในช่วงที่เลือก</div>
                <div class="value text-success">{{ number_format($summary['new']) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-white py-3 fw-semibold">
                    <i class="bi bi-journal-bookmark me-1"></i> แยกตามคอร์ส
                </div>
                <div class="card-body">
                    @forelse ($byCourse as $row)
                        <div class="breakdown-row">
                            <span>{{ $row->label }}</span>
                            <span class="fw-semibold">{{ number_format($row->total) }}</span>
                        </div>
                    @empty
                        <div class="text-muted small text-center py-3">ไม่มีข้อมูล</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-white py-3 fw-semibold">
                    <i class="bi bi-music-note-beamed me-1"></i> แยกตามเครื่องดนตรี
                </div>
                <div class="card-body">
                    @forelse ($byInstrument as $row)
                        <div class="breakdown-row">
                            <span>{{ $row->label }}</span>
                            <span class="fw-semibold">{{ number_format($row->total) }}</span>
                        </div>
                    @empty
                        <div class="text-muted small text-center py-3">ไม่มีข้อมูล</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-white py-3 fw-semibold">
                    <i class="bi bi-geo-alt me-1"></i> แยกตามสาขา
                </div>
                <div class="card-body">
                    @forelse ($byBranch as $row)
                        <div class="breakdown-row">
                            <span>{{ $row['label'] }}</span>
                            <span class="fw-semibold">{{ number_format($row['total']) }}</span>
                        </div>
                    @empty
                        <div class="text-muted small text-center py-3">ไม่มีข้อมูล</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="text-muted small mt-3">
        <i class="bi bi-info-circle"></i>
        สาขานักเรียนอนุมานจากคำสั่งซื้อคอร์สเรียนล่าสุดที่ชำระเงินแล้ว นักเรียนที่ไม่เคยมีคำสั่งซื้อหรือไม่ได้กรอกสาขาจะถูกจัดเป็น "ไม่ระบุสาขา"
    </div>
@endsection

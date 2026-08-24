@extends('layouts.app')
@section('title', 'รายงาน Performance อาจารย์')

@section('content')
    <div class="breadcrumb-sm"><a href="{{ route('reports.index') }}" class="text-reset">รายงาน</a> <i
            class="bi bi-chevron-right small"></i> รายงาน Performance อาจารย์</div>
    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
        <div>
            <h1 class="page-title mb-0">รายงาน Performance อาจารย์</h1>
            <div class="page-sub">{{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.teacher-performance.export-excel', request()->query()) }}"
                class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ route('reports.teacher-performance.export-pdf', request()->query()) }}"
                class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small">จากวันที่</label>
                    <input type="date" name="date_from" value="{{ request('date_from', $start->toDateString()) }}"
                        class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">ถึงวันที่</label>
                    <input type="date" name="date_to" value="{{ request('date_to', $end->toDateString()) }}"
                        class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">สาขา</label>
                    <select name="branch" class="form-select">
                        <option value="">ทุกสาขา</option>
                        @foreach ($branches as $b)
                            <option value="{{ $b }}" @selected($branch === $b)>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i> ดูรายงาน</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white py-3 fw-semibold">
            <i class="bi bi-person-workspace me-1"></i> Performance อาจารย์รายบุคคล
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">อาจารย์</th>
                        <th>สาขา</th>
                        <th class="text-end">ชั่วโมงสอน</th>
                        <th class="text-end">จำนวนคลาส</th>
                        <th class="text-end">จำนวนนักเรียน</th>
                        <th class="text-end pe-3">จำนวนการลา</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr>
                            <td class="ps-3">
                                <div class="fw-semibold">{{ $row['teacher']->full_name }}</div>
                                <div class="text-muted small">{{ $row['teacher']->teacher_code }}</div>
                            </td>
                            <td>{{ $row['teacher']->branch ?: '-' }}</td>
                            <td class="text-end">{{ number_format($row['hours'], 1) }}</td>
                            <td class="text-end">{{ number_format($row['class_count']) }}</td>
                            <td class="text-end">{{ number_format($row['student_count']) }}</td>
                            <td class="text-end pe-3">{{ number_format($row['leave_count']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-person-workspace fs-1 text-secondary"></i>
                                <div class="fw-semibold mt-2">ไม่พบข้อมูลอาจารย์</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

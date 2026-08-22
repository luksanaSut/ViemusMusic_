@extends('layouts.app')
@section('title', 'ค่ารถอาจารย์')

@section('content')
    <h1 class="page-title mb-3"><i class="bi bi-car-front"></i> ค่ารถอาจารย์ (Transport Fee)</h1>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3"><label class="form-label small">จากวันที่</label><input type="date" name="period_start"
                        value="{{ $periodStart }}" class="form-control"></div>
                <div class="col-md-3"><label class="form-label small">ถึงวันที่</label><input type="date"
                        name="period_end" value="{{ $periodEnd }}" class="form-control"></div>
                <div class="col-md-2"><button class="btn btn-accent w-100">ดูรอบนี้</button></div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>อาจารย์</th>
                        <th>ค่ารถจากคลาสที่สอน</th>
                        <th>ค่าชดเชยเพิ่มเติม</th>
                        <th>รวม</th>
                        <th class="text-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($summaries as $s)
                        <tr>
                            <td>{{ $s['teacher']->full_name }}</td>
                            <td>฿{{ number_format($s['session_fees'], 2) }}</td>
                            <td>฿{{ number_format($s['compensations'], 2) }}</td>
                            <td class="fw-semibold">฿{{ number_format($s['total'], 2) }}</td>
                            <td class="text-end"><a
                                    href="{{ route('transport-fees.show', ['teacher' => $s['teacher'], 'period_start' => $periodStart, 'period_end' => $periodEnd]) }}"
                                    class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i> ดูรายละเอียด</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

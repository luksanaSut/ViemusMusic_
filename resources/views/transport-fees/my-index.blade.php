@extends('layouts.app')
@section('title', 'ค่ารถของฉัน')

@section('content')
    <h1 class="page-title mb-3"><i class="bi bi-car-front"></i> ค่ารถของฉัน</h1>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4"><label class="form-label small">จากวันที่</label><input type="date" name="period_start"
                        value="{{ $periodStart }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label small">ถึงวันที่</label><input type="date"
                        name="period_end" value="{{ $periodEnd }}" class="form-control"></div>
                <div class="col-md-2"><button class="btn btn-accent w-100">ดู</button></div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h6 class="fw-bold mb-2" style="font-family:'Prompt',sans-serif;">ค่ารถจากคลาสที่สอนจริง</h6>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>วันที่</th>
                        <th>นักเรียน</th>
                        <th>กม.</th>
                        <th class="text-end">ค่ารถ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $s)
                        <tr>
                            <td class="small">{{ $s->session_date->format('d/m/Y') }}</td>
                            <td class="small">{{ $s->student_name }}</td>
                            <td class="small">{{ $s->km_traveled ?: '-' }}</td>
                            <td class="text-end small">฿{{ number_format($s->transport_fee_applied, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">ไม่มีค่ารถในรอบนี้</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h6 class="fw-bold mb-2" style="font-family:'Prompt',sans-serif;">ค่าชดเชยเพิ่มเติม</h6>
            @forelse($compensations as $c)
                <div class="d-flex justify-content-between border-bottom py-2 small">
                    <span>{{ $c->compensation_date->format('d/m/Y') }} — {{ $c->reason }}</span><span
                        class="fw-semibold">฿{{ number_format($c->amount, 2) }}</span></div>
            @empty
                <p class="text-muted small mb-0">ไม่มีค่าชดเชยเพิ่มเติมในรอบนี้</p>
            @endforelse
            <div class="d-flex justify-content-between border-top pt-2 mt-2 fw-bold"
                style="font-family:'Prompt',sans-serif;">
                <span>รวมทั้งหมด</span><span>฿{{ number_format($sessions->sum('transport_fee_applied') + $compensations->sum('amount'), 2) }}</span>
            </div>
        </div>
    </div>
@endsection

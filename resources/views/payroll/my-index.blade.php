@extends('layouts.app')
@section('title', 'รายได้ของฉัน')

@section('content')
    <h1 class="page-title mb-3"><i class="bi bi-wallet2"></i> รายได้ของฉัน</h1>
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('payroll.my-index') }}" class="btn btn-sm btn-accent"><i class="bi bi-pie-chart"></i> สรุปและประวัติการจ่าย</a>
        <a href="{{ route('transport-fees.my-index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-car-front"></i> รายละเอียดค่ารถ</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>รอบ</th>
                        <th>ค่าสอน</th>
                        <th>ปรับปรุงยอด</th>
                        <th>ยอดรวม</th>
                        <th>สถานะ</th>
                        <th>วันที่จ่าย</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($runs as $run)
                        <tr>
                            <td>{{ $run->periodLabel() }}</td>
                            <td>฿{{ number_format($run->teaching_income_total, 2) }}</td>
                            <td class="{{ $run->adjustment_amount < 0 ? 'text-danger' : 'text-success' }}">
                                {{ $run->adjustment_amount != 0 ? number_format($run->adjustment_amount, 2) : '-' }}</td>
                            <td class="fw-semibold">฿{{ number_format($run->total_amount, 2) }}</td>
                            <td><span class="badge {{ $run->statusBadgeClass() }}">{{ $run->statusLabel() }}</span></td>
                            <td>{{ $run->paid_date?->format('d/m/Y') ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">ยังไม่มีประวัติเงินเดือน</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $runs->links() }}</div>
    </div>
@endsection

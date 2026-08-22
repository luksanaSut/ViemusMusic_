@extends('layouts.app')
@section('title', 'รายละเอียดค่ารถ')

@section('content')
    <h1 class="page-title mb-1">{{ $teacher->full_name }}</h1>
    <div class="page-sub mb-3">{{ \Carbon\Carbon::parse($periodStart)->format('d/m/Y') }} -
        {{ \Carbon\Carbon::parse($periodEnd)->format('d/m/Y') }}</div>

    @if ($activeFee)
        <div class="alert alert-light border small mb-3">
            <i class="bi bi-info-circle"></i> เงื่อนไขค่ารถปัจจุบัน:
            {{ $activeFee->fee_type === 'fixed_per_day' ? 'อัตราคงที่ ฿' . number_format($activeFee->fee_amount, 2) . ' ต่อวัน' : 'อัตรา ฿' . number_format($activeFee->fee_amount, 2) . ' ต่อกิโลเมตร' }}
        </div>
    @else
        <div class="alert alert-warning small mb-3"><i class="bi bi-exclamation-circle"></i>
            อาจารย์คนนี้ยังไม่ได้ตั้งเงื่อนไขค่ารถไว้ (ตั้งได้ที่หน้าโปรไฟล์อาจารย์ แท็บเรทค่าจ้าง)</div>
    @endif

    <div class="row g-3">
        <div class="col-md-7">
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

            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-2" style="font-family:'Prompt',sans-serif;">ค่าชดเชยเพิ่มเติม</h6>
                    <form action="{{ route('transport-fees.compensations.store', $teacher) }}" method="POST"
                        class="row g-2 mb-3">
                        @csrf
                        <div class="col-md-3"><input type="date" name="compensation_date"
                                class="form-control form-control-sm" value="{{ now()->toDateString() }}" required></div>
                        <div class="col-md-3"><input type="number" step="0.01" name="amount"
                                class="form-control form-control-sm" placeholder="จำนวนเงิน" required></div>
                        <div class="col-md-4"><input type="text" name="reason" class="form-control form-control-sm"
                                placeholder="เหตุผล เช่น ค่าทางด่วน" required></div>
                        <div class="col-md-2 d-grid"><button class="btn btn-sm btn-accent">เพิ่ม</button></div>
                    </form>
                    @forelse($compensations as $c)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2 small">
                            <span>{{ $c->compensation_date->format('d/m/Y') }} — {{ $c->reason }}</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-semibold">฿{{ number_format($c->amount, 2) }}</span>
                                <form action="{{ route('transport-fees.compensations.destroy', $c) }}" method="POST"
                                    onsubmit="return confirm('ลบรายการนี้?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger py-0 px-1"><i
                                            class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">ยังไม่มีค่าชดเชยเพิ่มเติมในรอบนี้</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold mb-2" style="font-family:'Prompt',sans-serif;">สรุปยอด</h6>
                    <div class="d-flex justify-content-between py-1 small">
                        <span>ค่ารถจากคลาสสอนจริง</span><span>฿{{ number_format($sessions->sum('transport_fee_applied'), 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 small">
                        <span>ค่าชดเชยเพิ่มเติม</span><span>฿{{ number_format($compensations->sum('amount'), 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-2 mt-2 fw-bold"
                        style="font-family:'Prompt',sans-serif;">
                        <span>รวมทั้งหมด</span><span>฿{{ number_format($sessions->sum('transport_fee_applied') + $compensations->sum('amount'), 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

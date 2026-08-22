@extends('layouts.app')
@section('title', 'รายละเอียดเงินเดือน')

@section('content')
    <style>
        .payroll-summary {
            background: var(--accent-soft, #e7ebf1);
            border-radius: 14px;
            padding: 1.2rem;
        }

        .payroll-row {
            display: flex;
            justify-content: space-between;
            padding: .4rem 0;
            font-size: .9rem;
        }

        .payroll-row.total {
            font-weight: 700;
            font-size: 1.2rem;
            border-top: 1px solid rgba(19, 35, 58, .15);
            margin-top: .4rem;
            padding-top: .7rem;
            color: var(--accent-dark, #13233a);
        }
    </style>

    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title mb-1">{{ $teacher->full_name }}</h1>
            <div class="page-sub">{{ $run->periodLabel() }} · <span
                    class="badge {{ $run->statusBadgeClass() }}">{{ $run->statusLabel() }}</span></div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('payroll.export-excel', $run) }}" class="btn btn-outline-secondary btn-sm"><i
                    class="bi bi-file-earmark-excel"></i> Excel</a>
            <a href="{{ route('payroll.export-pdf', $run) }}" class="btn btn-outline-secondary btn-sm"><i
                    class="bi bi-file-earmark-pdf"></i> PDF</a>
            <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i>
                กลับ</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-2" style="font-family:'Prompt',sans-serif;">รายการคลาสที่สอนจริง
                        ({{ $run->items->count() }} คาบ)</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>วันที่</th>
                                    <th>นักเรียน</th>
                                    <th>ชม.</th>
                                    <th>เรท</th>
                                    <th class="text-end">รายได้</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($run->items as $item)
                                    @php $s = $item->teachingSession; @endphp
                                    <tr>
                                        <td class="small">{{ optional($s->session_date)->format('d/m/Y') }}</td>
                                        <td class="small">{{ $s->student_name }}</td>
                                        <td class="small">{{ $s->hours }}</td>
                                        <td class="small">฿{{ number_format($s->rate_applied, 2) }}</td>
                                        <td class="text-end small">฿{{ number_format($item->income_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">ไม่มีคลาสที่สอนจริงในรอบนี้
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if ($run->status === 'draft')
                <div class="alert alert-light border small mb-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-info-circle"></i> ถ้าเพิ่งเพิ่มค่าชดเชยเพิ่มเติม
                        หรือตั้งเงื่อนไขค่ารถหลังจากสร้างรอบนี้ ให้กดคำนวณยอดใหม่</span>
                    <form action="{{ route('payroll.recalculate', $run) }}" method="POST" class="ms-2">
                        @csrf
                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-clockwise"></i>
                            คำนวณยอดใหม่</button>
                    </form>
                </div>
            @endif
            @if ($run->status !== 'paid')
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2" style="font-family:'Prompt',sans-serif;">ปรับแก้ยอดเงิน (Admin)</h6>
                        <form action="{{ route('payroll.adjust', $run) }}" method="POST" class="row g-2">
                            @csrf
                            <div class="col-md-4"><input type="number" step="0.01" name="adjustment_amount"
                                    class="form-control form-control-sm" value="{{ $run->adjustment_amount }}"
                                    placeholder="+โบนัส / -หัก"></div>
                            <div class="col-md-6"><input type="text" name="adjustment_reason"
                                    class="form-control form-control-sm" value="{{ $run->adjustment_reason }}"
                                    placeholder="เหตุผล เช่น โบนัสพิเศษ, หักค่าอุปกรณ์" required></div>
                            <div class="col-md-2 d-grid"><button class="btn btn-sm btn-accent">บันทึก</button></div>
                        </form>
                        @if ($run->adjusted_by)
                            <small class="text-muted d-block mt-1">ปรับล่าสุดโดย {{ $run->adjusted_by }}</small>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold mb-2" style="font-family:'Prompt',sans-serif;">สรุปยอด</h6>
                    <div class="payroll-summary">
                        <div class="payroll-row"><span>ค่าสอน
                                (คลาสที่สอนจริง)</span><span>฿{{ number_format($run->teaching_income_total, 2) }}</span>
                        </div>
                        <div class="payroll-row">
                            <span>ค่าเดินทาง</span><span>฿{{ number_format($run->transport_fee_total, 2) }}</span>
                        </div>
                        @if ($run->adjustment_amount != 0)
                            <div class="payroll-row"><span>ปรับปรุงยอด</span><span
                                    class="{{ $run->adjustment_amount < 0 ? 'text-danger' : 'text-success' }}">{{ $run->adjustment_amount > 0 ? '+' : '' }}฿{{ number_format($run->adjustment_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="payroll-row total">
                            <span>ยอดรวมสุทธิ</span><span>฿{{ number_format($run->total_amount, 2) }}</span>
                        </div>
                    </div>

                    @if ($run->status === 'draft')
                        <form action="{{ route('payroll.confirm', $run) }}" method="POST" class="mt-3">
                            @csrf
                            <button class="btn btn-accent w-100"
                                onclick="return confirm('ยืนยันยอดรอบนี้? หลังยืนยันจะยังปรับแก้ยอดได้ แต่ควรตรวจสอบให้ครบก่อน')"><i
                                    class="bi bi-check-lg"></i> ยืนยันยอดเงินเดือน</button>
                        </form>
                    @elseif($run->status === 'confirmed')
                        <form action="{{ route('payroll.mark-paid', $run) }}" method="POST" class="mt-3 row g-2">
                            @csrf
                            <div class="col-md-6"><input type="date" name="paid_date"
                                    class="form-control form-control-sm" value="{{ now()->toDateString() }}" required>
                            </div>
                            <div class="col-md-6"><input type="text" name="payment_method"
                                    class="form-control form-control-sm" placeholder="ช่องทางจ่าย เช่น โอนธนาคาร" required>
                            </div>
                            <div class="col-12"><button class="btn btn-accent w-100"><i class="bi bi-cash"></i>
                                    บันทึกว่าจ่ายเงินแล้ว</button></div>
                        </form>
                    @else
                        <div class="alert alert-success small mt-3 mb-0"><i class="bi bi-check-circle"></i> จ่ายเมื่อ
                            {{ $run->paid_date->format('d/m/Y') }} ผ่าน {{ $run->payment_method }} (โดย
                            {{ $run->paid_by }})</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

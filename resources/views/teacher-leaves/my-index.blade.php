@extends('layouts.app')
@section('title', 'แจ้งลาหยุดสอน')

@section('content')
    <h1 class="page-title mb-3"><i class="bi bi-calendar-x"></i> แจ้งลาหยุดสอน</h1>

    <div class="card mb-3" style="border-radius:16px;">
        <div class="card-body">
            <h6 class="fw-bold" style="font-family:'Prompt',sans-serif;"><i class="bi bi-plus-circle"></i> แจ้งลาหยุดสอนใหม่
            </h6>
            <form action="{{ route('teachers.leaves.store', $teacher) }}" method="POST" class="row g-2">
                @csrf
                <div class="col-md-3"><input type="date" name="leave_date_from" class="form-control form-control-sm"
                        required></div>
                <div class="col-md-3"><input type="date" name="leave_date_to" class="form-control form-control-sm"
                        required></div>
                <div class="col-md-4"><input type="text" name="reason" class="form-control form-control-sm"
                        placeholder="เหตุผล เช่น ลาป่วย, ธุระส่วนตัว"></div>
                <div class="col-md-2 d-grid"><button class="btn btn-sm btn-accent">ส่งคำขอลา</button></div>
            </form>
        </div>
    </div>

    <div class="card" style="border-radius:16px;">
        <div class="card-body">
            <h6 class="fw-bold" style="font-family:'Prompt',sans-serif;"><i class="bi bi-clock-history"></i>
                ประวัติการแจ้งลาของฉัน</h6>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>ช่วงวันที่ลา</th>
                        <th>เหตุผล</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $lv)
                        <tr>
                            <td>{{ $lv->leave_date_from->format('d/m/Y') }} - {{ $lv->leave_date_to->format('d/m/Y') }}</td>
                            <td>{{ $lv->reason ?: '-' }}</td>
                            <td><span class="badge {{ $lv->statusBadgeClass() }}">{{ $lv->statusLabel() }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">ยังไม่มีประวัติการแจ้งลา</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

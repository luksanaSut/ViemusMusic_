@extends('layouts.app')
@section('title', 'คำขอลาหยุดสอนของอาจารย์')

@section('content')
    <div class="breadcrumb-sm">บุคคล <i class="bi bi-chevron-right small"></i> คำขอลาหยุดสอน</div>
    <h1 class="page-title mb-3">คำขอลาหยุดสอนของอาจารย์</h1>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-5"><input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาชื่ออาจารย์"></div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="pending" @selected(request('status') == 'pending')>รออนุมัติ</option>
                        <option value="approved" @selected(request('status') == 'approved')>อนุมัติแล้ว</option>
                        <option value="rejected" @selected(request('status') == 'rejected')>ปฏิเสธ</option>
                    </select>
                </div>
                <div class="col-md-1 d-grid"><button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>อาจารย์</th>
                        <th>ช่วงวันที่ลา</th>
                        <th>เหตุผล</th>
                        <th>สถานะ</th>
                        <th>ไฟล์แนบ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $lv)
                        <tr>
                            <td><a
                                    href="{{ route('teachers.show', $lv->teacher) }}">{{ $lv->teacher->full_name ?? '-' }}</a>
                            </td>
                            <td>{{ $lv->leave_date_from->format('d/m/Y') }} - {{ $lv->leave_date_to->format('d/m/Y') }}</td>
                            <td>{{ $lv->reason ?: '-' }}</td>
                            <td><span class="badge {{ $lv->statusBadgeClass() }}">{{ $lv->statusLabel() }}</span></td>
                            <td>@forelse($lv->attachments as $attachment)<a href="{{ route('teacher-leaves.attachments.download',$attachment) }}" class="badge text-bg-light border text-decoration-none d-inline-block mb-1"><i class="bi bi-paperclip"></i> {{ $attachment->original_name }} <span class="text-muted">{{ $attachment->formattedSize() }}</span></a>@empty<span class="text-muted">-</span>@endforelse</td>
                            <td class="text-end">
                                @if ($lv->status === 'pending')
                                    <form action="{{ route('teacher-leaves.approve', $lv) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <input type="hidden" name="cancel_affected" value="1">
                                        <button class="btn btn-sm btn-outline-success"
                                            onclick="return confirm('อนุมัติและยกเลิกคาบสอนที่ได้รับผลกระทบทั้งหมด?')"><i
                                                class="bi bi-check-lg"></i> อนุมัติ</button>
                                    </form>
                                    <form action="{{ route('teacher-leaves.reject', $lv) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i>
                                            ปฏิเสธ</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">ยังไม่มีคำขอลาหยุดสอน</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $leaves->links() }}</div>
    </div>
@endsection

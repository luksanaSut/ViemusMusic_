@extends('layouts.app')
@section('title', 'รายละเอียดคำขอเรียนชดเชย')

@section('content')
    <h1 class="page-title mb-3">คำขอเรียนชดเชย #{{ $makeupRequest->id }}</h1>

    <div class="row g-3">
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="bi bi-info-circle"></i> รายละเอียด</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th style="width:160px">นักเรียน</th>
                            <td>{{ $makeupRequest->student->full_name }}</td>
                        </tr>
                        <tr>
                            <th>คอร์ส</th>
                            <td>{{ $makeupRequest->enrollment->course->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>อาจารย์ผู้สอนชดเชย</th>
                            <td>{{ $makeupRequest->teacher->full_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>ห้องเรียน</th>
                            <td>{{ $makeupRequest->room->name ?? 'ออนไลน์' }}</td>
                        </tr>
                        <tr>
                            <th>วันเวลาเรียนชดเชย</th>
                            <td>{{ $makeupRequest->makeup_date->format('d/m/Y') }}
                                {{ $makeupRequest->start_time }}-{{ $makeupRequest->end_time }}</td>
                        </tr>
                        @if ($makeupRequest->is_overdue)
                            <tr>
                                <th>หมายเหตุ</th>
                                <td><span class="badge text-bg-danger">⚠️ เกินกำหนดตามนโยบาย</span></td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="bi bi-check2-square"></i> สถานะการอนุมัติ (ต้องครบ 2 ฝ่าย)</h6>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>Admin</span>
                        <span
                            class="badge {{ $makeupRequest->admin_approval_status == 'approved' ? 'text-bg-success' : ($makeupRequest->admin_approval_status == 'rejected' ? 'text-bg-danger' : 'text-bg-warning') }}">{{ $makeupRequest->admin_approval_status }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>อาจารย์ผู้สอนชดเชย</span>
                        <span
                            class="badge {{ $makeupRequest->instructor_approval_status == 'approved' ? 'text-bg-success' : ($makeupRequest->instructor_approval_status == 'rejected' ? 'text-bg-danger' : 'text-bg-warning') }}">{{ $makeupRequest->instructor_approval_status }}</span>
                    </div>

                    @if ($makeupRequest->overall_status === 'pending')
                        <div class="d-flex gap-2 mt-3">
                            @if (auth()->user()->isAdmin() && $makeupRequest->admin_approval_status === 'pending')
                                <form action="{{ route('makeup-requests.approve-admin', $makeupRequest) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-success">อนุมัติในฐานะ Admin</button>
                                </form>
                            @endif
                            @if (auth()->user()->isTeacher() &&
                                    auth()->user()->teacher_id === $makeupRequest->teacher_id &&
                                    $makeupRequest->instructor_approval_status === 'pending')
                                <form action="{{ route('makeup-requests.approve-instructor', $makeupRequest) }}"
                                    method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-success">อนุมัติในฐานะอาจารย์</button>
                                </form>
                            @endif
                            <form action="{{ route('makeup-requests.reject', $makeupRequest) }}" method="POST"
                                onsubmit="return confirm('ปฏิเสธคำขอนี้?')">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger">ปฏิเสธ</button>
                            </form>
                        </div>
                    @elseif($makeupRequest->overall_status === 'approved')
                        <div class="alert alert-success small mt-3 mb-0"><i class="bi bi-check-circle"></i>
                            จัดตารางเรียนชดเชยให้แล้ว</div>
                    @elseif($makeupRequest->overall_status === 'rejected')
                        <div class="alert alert-danger small mt-3 mb-0"><i class="bi bi-x-circle"></i>
                            คำขอนี้ถูกปฏิเสธ{{ $makeupRequest->rejection_reason ? ': ' . $makeupRequest->rejection_reason : '' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

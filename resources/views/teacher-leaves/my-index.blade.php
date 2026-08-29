@extends('layouts.app')
@section('title', 'แจ้งลาหยุดสอน')

@section('content')
    <h1 class="page-title mb-3"><i class="bi bi-calendar-x"></i> แจ้งลาหยุดสอน</h1>

    <div class="card mb-3" style="border-radius:16px;">
        <div class="card-body">
            <h6 class="fw-bold" style="font-family:'Prompt',sans-serif;"><i class="bi bi-plus-circle"></i> แจ้งลาหยุดสอนใหม่
            </h6>
            @include('teacher-leaves._form')
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
                        <th>ไฟล์แนบ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $lv)
                        <tr>
                            <td>{{ $lv->leave_date_from->format('d/m/Y') }} - {{ $lv->leave_date_to->format('d/m/Y') }}</td>
                            <td>{{ $lv->reason ?: '-' }}</td>
                            <td><span class="badge {{ $lv->statusBadgeClass() }}">{{ $lv->statusLabel() }}</span></td>
                            <td>@forelse($lv->attachments as $attachment)<div class="d-flex align-items-center gap-1 mb-1"><a href="{{ route('teacher-leaves.attachments.download',$attachment) }}" class="badge text-bg-light border text-decoration-none"><i class="bi bi-paperclip"></i> {{ $attachment->original_name }}</a>@if($lv->status==='pending')<form action="{{ route('teacher-leaves.attachments.destroy',$attachment) }}" method="POST">@csrf @method('DELETE')<button class="btn btn-sm border-0 p-0 text-danger" title="ลบไฟล์">×</button></form>@endif</div>@empty<span class="text-muted">-</span>@endforelse</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">ยังไม่มีประวัติการแจ้งลา</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

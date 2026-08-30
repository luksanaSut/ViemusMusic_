@extends('layouts.app')
@section('title', 'แจ้งลาหยุดสอน')

@section('content')
    <style>
        .leave-history{display:flex;flex-direction:column;gap:.65rem}
        .leave-item{position:relative;border:1px solid var(--border);border-radius:13px;padding:.9rem 1rem .9rem 1.1rem;background:#fff}
        .leave-item::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;border-radius:13px 0 0 13px;background:var(--border)}
        .leave-item.is-approved::before{background:#2f9e5b}
        .leave-item.is-rejected::before{background:#9aa0aa}
        .leave-item.is-pending::before{background:#e0a415}
        .leave-item-top{display:flex;justify-content:space-between;align-items:flex-start;gap:.75rem;flex-wrap:wrap}
        .leave-item-dates{font-weight:600;font-size:.88rem}
        .leave-item-reason{color:var(--muted);font-size:.8rem;margin-top:.2rem}
        .leave-item-files{display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.55rem}
        .leave-empty{text-align:center;padding:2.2rem 1rem;color:var(--muted)}
        .leave-empty i{font-size:1.6rem;display:block;margin-bottom:.5rem;opacity:.6}
    </style>

    <div class="breadcrumb-sm">บุคคล <i class="bi bi-chevron-right small"></i> แจ้งลาหยุดสอน</div>
    <h1 class="page-title mb-1"><i class="bi bi-calendar-x"></i> แจ้งลาหยุดสอน</h1>
    <p class="page-sub mb-3">ส่งคำขอลาหยุดสอนและติดตามสถานะการอนุมัติของคุณ</p>

    <div class="card mb-3" style="border-radius:16px;">
        <div class="card-body">
            <h6 class="fw-bold mb-3" style="font-family:'Prompt',sans-serif;"><i class="bi bi-plus-circle"></i> แจ้งลาหยุดสอนใหม่</h6>
            @include('teacher-leaves._form')
        </div>
    </div>

    <div class="card" style="border-radius:16px;">
        <div class="card-body">
            <h6 class="fw-bold mb-3" style="font-family:'Prompt',sans-serif;"><i class="bi bi-clock-history"></i> ประวัติการแจ้งลาของฉัน</h6>

            <div class="leave-history">
                @forelse($leaves as $lv)
                    <div class="leave-item is-{{ $lv->status }}">
                        <div class="leave-item-top">
                            <div>
                                <div class="leave-item-dates"><i class="bi bi-calendar-event text-muted"></i>
                                    {{ $lv->leave_date_from->format('d/m/Y') }} - {{ $lv->leave_date_to->format('d/m/Y') }}</div>
                                <div class="leave-item-reason">{{ $lv->reason ?: 'ไม่ระบุเหตุผล' }}</div>
                            </div>
                            <span class="badge {{ $lv->statusBadgeClass() }}">{{ $lv->statusLabel() }}</span>
                        </div>
                        @if($lv->attachments->isNotEmpty())
                            <div class="leave-item-files">
                                @foreach($lv->attachments as $attachment)
                                    <div class="d-flex align-items-center gap-1">
                                        <a href="{{ route('teacher-leaves.attachments.download',$attachment) }}"
                                            class="badge text-bg-light border text-decoration-none">
                                            <i class="bi bi-paperclip"></i> {{ $attachment->original_name }}
                                        </a>
                                        @if($lv->status==='pending')
                                            <form action="{{ route('teacher-leaves.attachments.destroy',$attachment) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm border-0 p-0 text-danger" title="ลบไฟล์">×</button>
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="leave-empty">
                        <i class="bi bi-calendar-check"></i>
                        ยังไม่มีประวัติการแจ้งลา
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

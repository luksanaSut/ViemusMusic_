@extends('layouts.app')
@section('title', 'คำขอเปลี่ยนตารางของฉัน')

@section('content')
<style>
    .request-head{display:flex;justify-content:space-between;align-items:flex-end;gap:1rem;margin-bottom:1rem}.request-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:.65rem;margin-bottom:1rem}.stat-link{display:block;text-decoration:none;color:inherit;border:1px solid var(--border);border-radius:13px;background:var(--card);padding:.85rem 1rem}.stat-link:hover{color:inherit;border-color:#b9b3aa}.stat-link.active{border-color:var(--accent);background:var(--accent-soft)}.stat-number{font-family:'Prompt',sans-serif;font-size:1.25rem;font-weight:700}.request-card{border:1px solid var(--border);border-radius:16px;background:var(--card);margin-bottom:.75rem;overflow:hidden}.request-card-head{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;padding:1rem 1.15rem;border-bottom:1px solid var(--border)}.request-type{font-family:'Prompt',sans-serif;font-weight:700}.request-body{padding:1rem 1.15rem}.change-flow{display:grid;grid-template-columns:minmax(0,1fr) 38px minmax(0,1fr);gap:.8rem;align-items:center}.flow-box{background:var(--surface);border-radius:11px;padding:.75rem .85rem;min-height:86px}.flow-label{font-size:.7rem;color:var(--muted);margin-bottom:.25rem}.flow-value{font-size:.82rem;line-height:1.55}.flow-arrow{width:34px;height:34px;border-radius:50%;background:var(--accent-soft);color:var(--accent);display:flex;align-items:center;justify-content:center}.request-meta{display:flex;flex-wrap:wrap;gap:.5rem 1rem;color:var(--muted);font-size:.74rem;margin-top:.8rem}.rejection-box{background:#fbeae7;color:#9d3329;border-radius:10px;padding:.65rem .8rem;font-size:.8rem;margin-top:.75rem}.empty-requests{text-align:center;padding:4rem 1rem}.empty-icon{width:64px;height:64px;border-radius:18px;background:var(--accent-soft);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:1.6rem;margin:0 auto 1rem}@media(max-width:767.98px){.request-head{align-items:flex-start;flex-direction:column}.request-head .btn{width:100%}.request-stats{grid-template-columns:repeat(2,1fr)}.change-flow{grid-template-columns:1fr}.flow-arrow{transform:rotate(90deg);margin:auto}.request-card-head{padding:.85rem}.request-body{padding:.85rem}}
</style>

<div class="request-head"><div><div class="breadcrumb-sm">ตารางและคำร้อง <i class="bi bi-chevron-right small"></i> เปลี่ยนตาราง</div><h1 class="page-title mb-1"><i class="bi bi-arrow-left-right"></i> คำขอเปลี่ยนตารางของฉัน</h1><p class="text-muted small mb-0">ติดตามคำขอและผลการพิจารณาจากผู้ดูแลระบบ</p></div><a href="{{ route('reschedule-requests.create') }}" class="btn btn-accent"><i class="bi bi-plus-lg"></i> สร้างคำขอใหม่</a></div>

<div class="request-stats">
@foreach([[null,'ทั้งหมด',$stats['all']], ['pending','รออนุมัติ',$stats['pending']], ['approved','อนุมัติแล้ว',$stats['approved']], ['rejected','ปฏิเสธ',$stats['rejected']]] as $item)
<a href="{{ route('reschedule-requests.my-index',array_filter(['status'=>$item[0]])) }}" class="stat-link {{ $status===$item[0]?'active':'' }}"><div class="stat-number">{{ $item[2] }}</div><div class="small text-muted">{{ $item[1] }}</div></a>
@endforeach
</div>

@forelse($requests as $requestItem)
@php
    $schedule=$requestItem->classSchedule;
    $snapshot=$requestItem->snapshot_before??[];
    $oldDate=isset($snapshot['schedule_date'])?\Carbon\Carbon::parse($snapshot['schedule_date'])->format('d/m/Y'):$schedule?->schedule_date?->format('d/m/Y');
    $oldTime=substr($snapshot['start_time']??$schedule?->start_time??'',0,5).'–'.substr($snapshot['end_time']??$schedule?->end_time??'',0,5);
@endphp
<article class="request-card">
    <div class="request-card-head"><div><div class="request-type"><i class="bi {{ $requestItem->type==='swap'?'bi-arrow-left-right':'bi-calendar-event' }}"></i> {{ $requestItem->typeLabel() }}</div><div class="small text-muted mt-1">{{ $schedule->enrollment->student->full_name??'-' }} · {{ $schedule->enrollment->course->name??'-' }}</div></div><span class="badge {{ $requestItem->statusBadgeClass() }}">{{ $requestItem->statusLabel() }}</span></div>
    <div class="request-body"><div class="change-flow"><div class="flow-box"><div class="flow-label">ตารางเดิม</div><div class="flow-value"><strong>{{ $oldDate }} · {{ $oldTime }}</strong><br>{{ $requestItem->reason?'เหตุผล: '.$requestItem->reason:'ไม่ได้ระบุเหตุผล' }}</div></div><div class="flow-arrow"><i class="bi bi-arrow-right"></i></div><div class="flow-box"><div class="flow-label">รายการที่ขอ</div><div class="flow-value">
    @if($requestItem->type==='swap') @php $swap=$requestItem->swapWithClassSchedule; @endphp <strong>แลกกับ {{ $swap->enrollment->student->full_name??'-' }}</strong><br>{{ $swap?->schedule_date?->format('d/m/Y')??'-' }} · {{ $swap ? substr($swap->start_time,0,5) : '-' }}–{{ $swap ? substr($swap->end_time,0,5) : '-' }} · {{ $swap->enrollment->course->name??'-' }}
    @else <strong>{{ $requestItem->new_date?->format('d/m/Y')??'คงวันเดิม' }} · {{ $requestItem->new_start_time?substr($requestItem->new_start_time,0,5):'-' }}–{{ $requestItem->new_end_time?substr($requestItem->new_end_time,0,5):'-' }}</strong><br>{{ $requestItem->newTeacher?'อ.'.$requestItem->newTeacher->full_name:'คงอาจารย์เดิม' }} · {{ $requestItem->newRoom->name??'คงห้องเดิม/ออนไลน์' }} @endif
    </div></div></div>
    @if($requestItem->status==='rejected'&&$requestItem->rejection_reason)<div class="rejection-box"><strong><i class="bi bi-info-circle"></i> เหตุผลที่ปฏิเสธ:</strong> {{ $requestItem->rejection_reason }}</div>@endif
    <div class="request-meta"><span><i class="bi bi-clock"></i> ส่งเมื่อ {{ $requestItem->created_at->format('d/m/Y H:i') }}</span>@if($requestItem->reviewed_at)<span><i class="bi bi-person-check"></i> พิจารณาเมื่อ {{ $requestItem->reviewed_at->format('d/m/Y H:i') }}</span>@endif</div></div>
</article>
@empty<div class="request-card"><div class="empty-requests"><div class="empty-icon"><i class="bi bi-calendar2-x"></i></div><h5>ยังไม่มีคำขอ{{ $status?'ในสถานะนี้':'' }}</h5><p class="text-muted small">เมื่อต้องการเปลี่ยนคาบ คุณสามารถสร้างคำขอใหม่ได้ที่นี่</p><a href="{{ route('reschedule-requests.create') }}" class="btn btn-sm btn-accent">สร้างคำขอใหม่</a></div></div>@endforelse

<div class="mt-3">{{ $requests->links() }}</div>
@endsection

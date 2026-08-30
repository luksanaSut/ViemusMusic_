@extends('layouts.app')
@section('title', 'ตารางสอนของฉัน')
@section('content')
<style>
    .schedule-toolbar{display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap}.view-switch{display:flex;gap:.35rem}.calendar-scroll{overflow-x:auto}.week-grid{display:grid;grid-template-columns:repeat(7,minmax(145px,1fr));min-width:1015px}.month-grid{display:grid;grid-template-columns:repeat(7,minmax(130px,1fr));min-width:910px}.calendar-day{min-height:230px;border-right:1px solid var(--border);padding:.6rem}.month-grid .calendar-day{min-height:145px;border-bottom:1px solid var(--border)}.calendar-day:nth-child(7n){border-right:0}.day-outside{background:var(--surface);opacity:.55}.day-today{background:linear-gradient(180deg,var(--accent-soft),var(--card) 50%)}.day-head{text-align:center;font-size:.75rem;color:var(--muted);margin-bottom:.5rem}.day-number{font-family:'Prompt',sans-serif;font-size:1rem;font-weight:700;color:var(--text)}.event{display:block;color:inherit;text-decoration:none;background:var(--surface);border:1px solid var(--border);border-left:3px solid var(--accent);border-radius:9px;padding:.45rem .5rem;margin-bottom:.4rem;font-size:.72rem}.event:hover{color:inherit;box-shadow:0 4px 12px rgba(0,0,0,.08)}.event.makeup{border-left-color:#d88735}.event.done{opacity:.68}.event-name{font-size:.78rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.schedule-list-item{display:grid;grid-template-columns:90px 1fr auto;gap:1rem;align-items:center;padding:.85rem 0;border-bottom:1px solid var(--border)}
    @media(max-width:575.98px){.schedule-list-item{grid-template-columns:70px 1fr}.schedule-list-item>.btn{grid-column:1/-1}.schedule-toolbar{align-items:flex-start}.week-grid{grid-template-columns:repeat(7,minmax(135px,1fr))}}
</style>
@php
    $days=['จ.','อ.','พ.','พฤ.','ศ.','ส.','อา.'];
    $months=['','มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];
    $byDate=$schedules->groupBy(fn($s)=>$s->schedule_date->toDateString());
    $periodLabel=$display==='day' ? $focusDate->format('d/m/').($focusDate->year+543) : ($display==='month' ? $months[$focusDate->month].' '.($focusDate->year+543) : $rangeStart->format('d/m').' – '.$rangeEnd->format('d/m/').($rangeEnd->year+543));
@endphp

<div class="d-flex justify-content-between align-items-start gap-3 mb-3">
    <div><div class="breadcrumb-sm">การเรียนการสอน <i class="bi bi-chevron-right small"></i> ตารางสอน</div><h1 class="page-title mb-1"><i class="bi bi-calendar3"></i> ตารางสอนของฉัน</h1><p class="text-muted small mb-0">ตารางนี้ใช้สำหรับดูนัดหมาย กดเปิดคาบเมื่อต้องการเช็กชื่อหรือบันทึกการสอน</p></div>
    <a href="{{ route('teaching-logs.index') }}" class="btn btn-sm btn-outline-secondary flex-shrink-0"><i class="bi bi-journal-check"></i> ไปหน้าบันทึกการสอน</a>
</div>

<div class="card" style="border-radius:16px;overflow:hidden">
    <div class="card-body schedule-toolbar border-bottom">
        <div class="view-switch">
            @foreach(['day'=>'รายวัน','week'=>'รายสัปดาห์','month'=>'รายเดือน'] as $key=>$label)
                <a href="{{ route('teacher.schedule',['view'=>$key,'date'=>$focusDate->toDateString()]) }}" class="btn btn-sm {{ $display===$key?'btn-accent':'btn-outline-secondary' }}">{{ $label }}</a>
            @endforeach
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('teacher.schedule',['view'=>$display,'date'=>$previousDate]) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-left"></i></a>
            <a href="{{ route('teacher.schedule',['view'=>$display]) }}" class="btn btn-sm btn-outline-secondary">วันนี้</a>
            <strong style="font-family:'Prompt',sans-serif;min-width:145px;text-align:center">{{ $periodLabel }}</strong>
            <a href="{{ route('teacher.schedule',['view'=>$display,'date'=>$nextDate]) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-right"></i></a>
        </div>
    </div>

    @if($display==='day')
        <div class="card-body">
            @forelse($schedules as $s)
                <div class="schedule-list-item"><div><strong>{{ substr($s->start_time,0,5) }}</strong><div class="small text-muted">ถึง {{ substr($s->end_time,0,5) }}</div></div><div><strong>{{ $s->enrollment->student->full_name??'ไม่ระบุนักเรียน' }}</strong><div class="small text-muted">{{ $s->enrollment->course->name??'-' }} · {{ $s->deliveryModeLabel() }}{{ $s->room?' · '.$s->room->name:'' }} @if($makeupScheduleIds->has($s->id))<span class="badge text-bg-warning">ชดเชย</span>@endif</div>@if($s->notes)<div class="small mt-1"><i class="bi bi-sticky"></i> {{ $s->notes }}</div>@endif</div><a href="{{ route('teaching-logs.show',$s) }}" class="btn btn-sm btn-outline-primary">เปิดคาบ</a></div>
            @empty
                @if($trialLeads->get($focusDate->toDateString(),collect())->isEmpty())<div class="text-center text-muted py-5"><i class="bi bi-calendar-x fs-2"></i><p class="small mt-2 mb-0">ไม่มีตารางสอนในวันนี้</p></div>@endif
            @endforelse
            @foreach($trialLeads->get($focusDate->toDateString(),collect()) as $tr)
                <div class="schedule-list-item"><div><strong>{{ $tr->trial_start_time?substr($tr->trial_start_time,0,5):'-' }}</strong>@if($tr->trial_end_time)<div class="small text-muted">ถึง {{ substr($tr->trial_end_time,0,5) }}</div>@endif</div><div><strong>{{ $tr->student_name }}</strong> <span class="badge text-bg-warning">นัดทดลอง</span><div class="small text-muted">{{ $tr->course->name??$tr->interest??'-' }}{{ $tr->room?' · '.$tr->room->name:'' }} · <span class="badge {{ $tr->confirmationStatusBadgeClass() }}">{{ $tr->confirmationStatusLabel() }}</span></div></div><a href="{{ route('trial-leads.my-show',$tr) }}" class="btn btn-sm btn-outline-primary">ดูรายละเอียด</a></div>
            @endforeach
        </div>
    @else
        <div class="calendar-scroll">
            <div class="{{ $display==='month'?'month-grid':'week-grid' }}">
                @foreach($days as $name)<div class="text-center small fw-semibold py-2 border-bottom">{{ $name }}</div>@endforeach
                @for($date=$rangeStart->copy();$date->lte($rangeEnd);$date->addDay())
                    <div class="calendar-day {{ $display==='month'&&!$date->isSameMonth($focusDate)?'day-outside':'' }} {{ $date->isToday()?'day-today':'' }}">
                        <div class="day-head">{{ $display==='week'?$days[$date->dayOfWeekIso-1]:'' }} <span class="day-number">{{ $date->day }}</span></div>
                        @forelse($byDate->get($date->toDateString(),collect()) as $s)
                            <a href="{{ route('teaching-logs.show',$s) }}" class="event {{ $makeupScheduleIds->has($s->id)?'makeup':'' }} {{ $s->status==='completed'?'done':'' }}" title="เปิดรายละเอียดคาบ">
                                <strong>{{ substr($s->start_time,0,5) }}–{{ substr($s->end_time,0,5) }}</strong>@if($makeupScheduleIds->has($s->id)) <span title="คาบชดเชย">↻</span>@endif
                                <div class="event-name">{{ $s->enrollment->student->nickname?:$s->enrollment->student->full_name }}</div>
                                <div class="text-muted text-truncate">{{ $s->enrollment->course->name??'-' }}</div>
                                <div class="text-muted text-truncate"><i class="bi {{ $s->delivery_mode==='online'?'bi-camera-video':'bi-geo-alt' }}"></i> {{ $s->delivery_mode==='online'?'ออนไลน์':($s->room->name??$s->deliveryModeLabel()) }}</div>
                            </a>
                        @empty @if($display==='week' && $trialLeads->get($date->toDateString(),collect())->isEmpty())<div class="text-muted text-center small py-3">ไม่มีคาบ</div>@endif @endforelse
                        @foreach($trialLeads->get($date->toDateString(),collect()) as $tr)
                            <a href="{{ route('trial-leads.my-show',$tr) }}" class="event" style="border-left-color:var(--amber,#8a5a2b);" title="นัดทดลองเรียน">
                                <strong>{{ $tr->trial_start_time?substr($tr->trial_start_time,0,5):'' }}</strong> <i class="bi bi-person-check"></i>
                                <div class="event-name">{{ $tr->student_name }}</div>
                                <div class="text-muted text-truncate">{{ $tr->course->name??$tr->interest??'นัดทดลองเรียน' }}</div>
                            </a>
                        @endforeach
                    </div>
                @endfor
            </div>
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')
@section('title', 'นัดทดลองของฉัน')
@section('content')
<style>
    .schedule-list-item{display:grid;grid-template-columns:90px 1fr auto;gap:1rem;align-items:center;padding:.85rem 0;border-bottom:1px solid var(--border)}
    @media(max-width:575.98px){.schedule-list-item{grid-template-columns:70px 1fr}.schedule-list-item>.btn{grid-column:1/-1}}
</style>
@php
    $withDate = $leads->filter(fn($l) => $l->trial_date)->groupBy(fn($l) => $l->trial_date->toDateString());
    $noDate = $leads->filter(fn($l) => !$l->trial_date);
@endphp

<div class="breadcrumb-sm">การเรียนการสอน <i class="bi bi-chevron-right small"></i> นัดทดลองของฉัน</div>
<h1 class="page-title mb-1"><i class="bi bi-person-check"></i> นัดทดลองของฉัน</h1>
<p class="text-muted small mb-3">รายชื่อผู้สนใจที่นัดทดลองเรียนกับคุณ · {{ $leads->count() }} รายการ</p>

<div class="card" style="border-radius:16px;overflow:hidden">
    <div class="card-body">
        @forelse($withDate as $dateStr => $dayLeads)
            <div class="fw-bold small text-muted mt-2 mb-1">{{ \Carbon\Carbon::parse($dateStr)->format('d/m/Y') }}</div>
            @foreach($dayLeads as $lead)
                <div class="schedule-list-item">
                    <div><strong>{{ $lead->trial_start_time ? substr($lead->trial_start_time,0,5) : '-' }}</strong>
                        @if($lead->trial_end_time)<div class="small text-muted">ถึง {{ substr($lead->trial_end_time,0,5) }}</div>@endif
                    </div>
                    <div>
                        <strong>{{ $lead->student_name }}</strong>
                        <div class="small text-muted">{{ $lead->course->name ?? $lead->interest ?? '-' }} · {{ $lead->phone }}</div>
                        <span class="badge {{ $lead->confirmationStatusBadgeClass() }}">{{ $lead->confirmationStatusLabel() }}</span>
                    </div>
                    <a href="{{ route('trial-leads.my-show', $lead) }}" class="btn btn-sm btn-outline-primary">ดูรายละเอียด</a>
                </div>
            @endforeach
        @empty
        @endforelse

        @if($noDate->count())
            <div class="fw-bold small text-muted mt-3 mb-1">ยังไม่นัดวันที่</div>
            @foreach($noDate as $lead)
                <div class="schedule-list-item">
                    <div class="text-muted small">-</div>
                    <div>
                        <strong>{{ $lead->student_name }}</strong>
                        <div class="small text-muted">{{ $lead->course->name ?? $lead->interest ?? '-' }} · {{ $lead->phone }}</div>
                        <span class="badge {{ $lead->confirmationStatusBadgeClass() }}">{{ $lead->confirmationStatusLabel() }}</span>
                    </div>
                    <a href="{{ route('trial-leads.my-show', $lead) }}" class="btn btn-sm btn-outline-primary">ดูรายละเอียด</a>
                </div>
            @endforeach
        @endif

        @if($leads->isEmpty())
            <div class="text-center text-muted py-5"><i class="bi bi-calendar-x fs-2"></i><p class="small mt-2 mb-0">ยังไม่มีนัดทดลองเรียน</p></div>
        @endif
    </div>
</div>
@endsection

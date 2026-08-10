@extends('layouts.app')
@section('title', 'ตารางสอนรวม')

@section('content')
<h4 class="mb-3"><i class="bi bi-calendar3"></i> ตารางสอนรวม</h4>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">มุมมอง</label>
                <select name="view" class="form-select">
                    <option value="day" @selected($view=='day')>รายวัน</option>
                    <option value="week" @selected($view=='week')>รายสัปดาห์</option>
                    <option value="month" @selected($view=='month')>รายเดือน</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">วันที่อ้างอิง</label>
                <input type="date" name="date" value="{{ $date->toDateString() }}" class="form-control">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary"><i class="bi bi-filter"></i> แสดงตาราง</button>
            </div>
        </form>
    </div>
</div>

<p class="text-muted">ช่วงวันที่: {{ $from->format('d/m/Y') }} - {{ $to->format('d/m/Y') }}</p>

@forelse($sessions as $dateKey => $daySessions)
    <div class="card mb-3">
        <div class="card-header fw-semibold">{{ \Carbon\Carbon::parse($dateKey)->translatedFormat('l d/m/Y') }}</div>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr><th>เวลา</th><th>อาจารย์</th><th>เครื่องดนตรี</th><th>ประเภทสอน</th><th>นักเรียน</th><th>สถานะ</th></tr>
                </thead>
                <tbody>
                @foreach($daySessions as $s)
                    <tr>
                        <td>{{ $s->start_time }} - {{ $s->end_time }}</td>
                        <td><a href="{{ route('teachers.show', $s->teacher) }}">{{ $s->teacher->full_name }}</a></td>
                        <td>{{ optional($s->instrument)->name }}</td>
                        <td>{{ optional($s->teachingType)->name }}</td>
                        <td>{{ $s->student_name }}</td>
                        <td>{{ $s->status }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="alert alert-light border text-center text-muted">ไม่มีตารางสอนในช่วงเวลาที่เลือก</div>
@endforelse
@endsection

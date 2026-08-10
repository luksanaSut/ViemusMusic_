@extends('layouts.app')
@section('title', 'ตารางการใช้งานห้องเรียน')

@section('content')
    <h1 class="page-title mb-3">ตารางการใช้งานห้องเรียน</h1>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small">มุมมอง</label>
                    <select name="view" class="form-select">
                        <option value="day" @selected($view == 'day')>รายวัน</option>
                        <option value="week" @selected($view == 'week')>รายสัปดาห์</option>
                        <option value="month" @selected($view == 'month')>รายเดือน</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">วันที่อ้างอิง</label>
                    <input type="date" name="date" value="{{ $date->toDateString() }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">ห้อง</label>
                    <select name="room_id" class="form-select">
                        <option value="">ทุกห้อง</option>
                        @foreach ($rooms as $r)
                            <option value="{{ $r->id }}" @selected(request('room_id') == $r->id)>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2"><button class="btn btn-accent">แสดงตาราง</button></div>
            </form>
        </div>
    </div>

    <p class="text-muted">ช่วงวันที่: {{ $from->format('d/m/Y') }} - {{ $to->format('d/m/Y') }}</p>

    @forelse($bookings as $dateKey => $dayBookings)
        <div class="card mb-3">
            <div class="card-header fw-semibold">{{ \Carbon\Carbon::parse($dateKey)->translatedFormat('l d/m/Y') }}</div>
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>เวลา</th>
                        <th>ห้อง</th>
                        <th>วัตถุประสงค์</th>
                        <th>จำนวนคน</th>
                        <th>อาจารย์/คอร์ส</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dayBookings as $b)
                        <tr>
                            <td>{{ $b->start_time }} - {{ $b->end_time }}</td>
                            <td><a href="{{ route('rooms.show', $b->room) }}">{{ $b->room->name }}</a></td>
                            <td>{{ $b->purpose ?: '-' }}</td>
                            <td>{{ $b->attendees_count }} คน</td>
                            <td>{{ optional($b->teacher)->full_name }} {{ optional($b->course)->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div class="alert alert-light border text-center text-muted">ไม่มีการจองห้องในช่วงเวลาที่เลือก</div>
    @endforelse
@endsection

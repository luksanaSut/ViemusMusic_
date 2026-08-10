@extends('layouts.app')
@section('title', 'จัดการห้องเรียน')

@section('content')
    <div class="breadcrumb-sm">
        งานวิชาการ <i class="bi bi-chevron-right small"></i> ห้องเรียน</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title">จัดการห้องเรียน</h1>
            <div class="page-sub">ห้องเรียนทั้งหมด {{ $rooms->total() }} ห้อง</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('rooms.schedule') }}" class="btn btn-outline-secondary"><i class="bi bi-calendar3"></i>
                ตารางการใช้งาน</a>
            <a href="{{ route('rooms.create') }}" class="btn btn-accent"><i class="bi bi-plus-lg"></i> เพิ่มห้องเรียน</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-4"><input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาชื่อห้อง / รหัส / ตำแหน่ง"></div>
                <div class="col-md-2"><input type="number" name="min_capacity" value="{{ request('min_capacity') }}"
                        class="form-control" placeholder="ความจุขั้นต่ำ"></div>
                <div class="col-md-2">
                    <select name="is_active" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="1" @selected(request('is_active') === '1')>เปิดใช้งาน</option>
                        <option value="0" @selected(request('is_active') === '0')>ปิดใช้งาน</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="is_under_maintenance" class="form-select">
                        <option value="">ทุกสถานะปรับปรุง</option>
                        <option value="1" @selected(request('is_under_maintenance') === '1')>ปิดปรับปรุงอยู่</option>
                        <option value="0" @selected(request('is_under_maintenance') === '0')>ไม่ได้ปิดปรับปรุง</option>
                    </select>
                </div>
                <div class="col-md-1 d-grid"><button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        @forelse($rooms as $room)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold" style="font-family:'Prompt',sans-serif;">{{ $room->name }}</div>
                                <div class="text-muted small">{{ $room->room_code }} @if ($room->location)
                                        · {{ $room->location }}
                                    @endif
                                </div>
                            </div>
                            <span class="badge {{ $room->statusBadgeClass() }}">{{ $room->statusLabel() }}</span>
                        </div>

                        <div class="d-flex gap-3 mt-2 small text-muted">
                            <span><i class="bi bi-people"></i> ความจุ {{ $room->capacity }} คน</span>
                            <span><i class="bi bi-calendar-check"></i> วันนี้ {{ $room->todayBookingsCount() }} คาบ</span>
                        </div>

                        @if ($room->equipment->count())
                            <div class="mt-2">
                                @foreach ($room->equipment->take(3) as $eq)
                                    <span class="badge text-bg-light border small">{{ $eq->name }}
                                        ×{{ $eq->pivot->quantity }}</span>
                                @endforeach
                                @if ($room->equipment->count() > 3)
                                    <span
                                        class="badge text-bg-light border small">+{{ $room->equipment->count() - 3 }}</span>
                                @endif
                            </div>
                        @endif

                        @if ($room->is_under_maintenance)
                            <div class="alert-info-soft mt-2" style="background:#fdf1e2;color:#8a5a2b;">
                                <i class="bi bi-tools"></i> {{ $room->maintenance_reason }}
                                @if ($room->maintenance_to)
                                    (ถึง {{ $room->maintenance_to->format('d/m/Y') }})
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white d-flex gap-2">
                        <a href="{{ route('rooms.show', $room) }}"
                            class="btn btn-sm btn-outline-secondary flex-grow-1">ดูรายละเอียด</a>
                        <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-outline-primary"><i
                                class="bi bi-pencil"></i></a>
                        <form action="{{ route('rooms.destroy', $room) }}" method="POST"
                            onsubmit="return confirm('ยืนยันการลบห้องเรียนนี้?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center text-muted py-5">ไม่พบข้อมูลห้องเรียน</div>
                </div>
            </div>
        @endforelse
    </div>
    <div class="mt-3">{{ $rooms->links() }}</div>
@endsection

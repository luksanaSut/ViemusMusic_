@extends('layouts.app')
@section('title', 'จัดการคอร์สเรียน')

@section('content')
    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> คอร์สเรียน</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title">จัดการคอร์สเรียน</h1>
            <div class="page-sub">คอร์สทั้งหมด {{ $courses->total() }} คอร์ส</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary"><i class="bi bi-tag"></i> โปรโมชัน /
                คูปอง</a>
            <a href="{{ route('courses.create') }}" class="btn btn-accent"><i class="bi bi-plus-lg"></i> เพิ่มคอร์สเรียน</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาคอร์ส">
                </div>
                <div class="col-md-2">
                    <select name="instrument_id" class="form-select">
                        <option value="">ทุกเครื่อง</option>
                        @foreach ($instruments as $i)
                            <option value="{{ $i->id }}" @selected(request('instrument_id') == $i->id)>{{ $i->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="level_id" class="form-select">
                        <option value="">ทุกระดับ</option>
                        @foreach ($levels as $lv)
                            <option value="{{ $lv->id }}" @selected(request('level_id') == $lv->id)>{{ $lv->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="class_type" class="form-select">
                        <option value="">ทุกประเภทการเรียน</option>
                        <option value="private" @selected(request('class_type') == 'private')>Private</option>
                        <option value="group" @selected(request('class_type') == 'group')>Group</option>
                        <option value="special_activity" @selected(request('class_type') == 'special_activity')>Special Activity</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="is_active" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="1" @selected(request('is_active') === '1')>เปิดใช้งาน</option>
                        <option value="0" @selected(request('is_active') === '0')>ปิดใช้งาน</option>
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
                        <th>รหัส</th>
                        <th>ชื่อคอร์ส</th>
                        <th>เครื่องดนตรี/ระดับ</th>
                        <th>ประเภทการเรียน</th>
                        <th>ครั้ง/ระยะเวลา</th>
                        <th>ราคา</th>
                        <th>ผู้เรียนสูงสุด</th>
                        <th>สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>{{ $course->course_code }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div
                                        style="width:44px;height:44px;border-radius:8px;overflow:hidden;flex-shrink:0;background:var(--accent-soft,#e7ebf1);display:flex;align-items:center;justify-content:center;">
                                        @if ($course->image_path)
                                            <img src="{{ asset('storage/' . $course->image_path) }}"
                                                style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            <i class="bi bi-image text-secondary"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $course->name }}</div>
                                        <div class="d-flex gap-1">
                                            <span
                                                class="badge text-bg-light border small">{{ $course->classTypeLabel() }}</span>
                                            @if ($course->activityTypeLabel())
                                                <span
                                                    class="badge text-bg-light border small">{{ $course->activityTypeLabel() }}</span>
                                            @endif
                                            @if ($course->is_adult_flexi)
                                                <span class="badge text-bg-light border small">Adult Flexi</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ optional($course->instrument)->name ?? '-' }}
                                @if ($course->level)
                                    <div class="text-muted small">{{ $course->level->name }}</div>
                                @endif
                            </td>
                            <td>
                                {{ $course->classTypeLabel() }}
                                @if ($course->activityTypeLabel())
                                    <div class="text-muted small">{{ $course->activityTypeLabel() }}</div>
                                @endif
                            </td>
                            <td>{{ $course->total_sessions }} ครั้ง / {{ $course->duration_months }} เดือน</td>
                            <td class="fw-semibold">{{ number_format($course->price, 2) }}</td>
                            <td>{{ $course->max_students }}</td>
                            <td>
                                <form action="{{ route('courses.toggle-status', $course) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="btn btn-sm {{ $course->is_active ? 'btn-outline-success' : 'btn-outline-secondary' }} border-0 p-0">
                                        <span
                                            class="badge {{ $course->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                            {{ $course->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                        </span>
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-outline-primary"><i
                                        class="bi bi-pencil"></i></a>
                                <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('ยืนยันการลบคอร์สนี้?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">ไม่พบข้อมูลคอร์สเรียน</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $courses->links() }}</div>
    </div>
@endsection

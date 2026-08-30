@extends('layouts.app')
@section('title', 'จัดการคอร์สเรียน')

@section('content')
    <style>
        .filter-card .form-control,
        .filter-card .form-select {
            font-size: .88rem;
        }

        .course-thumb {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent, #1f3350);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .course-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .courses-table th {
            font-size: .76rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--muted, #6b655e);
            font-weight: 600;
            border-bottom-width: 1px;
            white-space: nowrap;
        }

        .courses-table td {
            vertical-align: middle;
        }

        .badge-pill {
            font-weight: 600;
            font-size: .7rem;
            padding: .3rem .6rem;
            border-radius: 8px;
            display: inline-block;
            line-height: 1.2;
        }

        .badge-pill.type-private { background: #e7ebf1; color: #1f3350; }
        .badge-pill.type-group { background: #ece9f6; color: #4b3f8a; }
        .badge-pill.type-special_activity { background: var(--amber-soft, #f3ece2); color: var(--amber, #8a5a2b); }
        .badge-pill.type-flexi { background: #fbeae7; color: #b3392c; }

        .status-toggle {
            border: 0;
            background: none;
            padding: 0;
        }

        .status-pill {
            font-weight: 600;
            font-size: .72rem;
            padding: .35rem .7rem .35rem .6rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }

        .status-pill::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            flex-shrink: 0;
        }

        .status-pill.active { background: var(--success-soft, #e7f2ec); color: var(--success, #2f6f4e); }
        .status-pill.inactive { background: #f1efec; color: #6b655e; }

        .empty-state {
            padding: 3.5rem 1rem;
            text-align: center;
            color: var(--muted, #6b655e);
        }

        .empty-state i {
            font-size: 2.4rem;
            color: var(--border, #e4e1dc);
            margin-bottom: .6rem;
            display: block;
        }
    </style>

    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> คอร์สเรียน</div>
    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
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

    <div class="card mb-3 filter-card">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute"
                            style="left:.8rem; top:50%; transform:translateY(-50%); color:var(--muted);"></i>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control ps-5"
                            placeholder="ค้นหาคอร์ส">
                    </div>
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
                <div class="col-md-1 d-grid">
                    <button class="btn btn-accent"><i class="bi bi-search"></i></button>
                </div>
                @if (request('q') || request('instrument_id') || request('level_id') || request('class_type') || request('is_active') !== null)
                    <div class="col-12">
                        <a href="{{ route('courses.index') }}" class="small text-muted text-decoration-none">
                            <i class="bi bi-x-lg"></i> ล้างตัวกรอง
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 courses-table">
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
                            <td class="text-muted">{{ $course->course_code }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="course-thumb">
                                        @if ($course->image_path)
                                            <img src="{{ asset('storage/' . $course->image_path) }}" alt="">
                                        @else
                                            <i class="bi bi-image"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $course->name }}</div>
                                        @if ($course->is_adult_flexi)
                                            <span class="badge-pill type-flexi">Adult Flexi</span>
                                        @endif
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
                                <span class="badge-pill type-{{ $course->class_type }}">{{ $course->classTypeLabel() }}</span>
                                @if ($course->activityTypeLabel())
                                    <div class="text-muted small mt-1">{{ $course->activityTypeLabel() }}</div>
                                @endif
                            </td>
                            <td>{{ $course->total_sessions }} ครั้ง / {{ $course->duration_months }} เดือน</td>
                            <td class="fw-semibold">{{ number_format($course->price, 2) }}</td>
                            <td>{{ $course->max_students }}</td>
                            <td>
                                <form action="{{ route('courses.toggle-status', $course) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="status-toggle">
                                        <span class="status-pill {{ $course->is_active ? 'active' : 'inactive' }}">
                                            {{ $course->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                        </span>
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-outline-secondary"><i
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
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="bi bi-journal-bookmark"></i>
                                    ไม่พบข้อมูลคอร์สเรียน
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $courses->links() }}</div>
    </div>
@endsection

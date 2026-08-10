@extends('layouts.app')
@section('title', 'จัดการอาจารย์')

@php
    // ธีมสี/ไอคอนของปกการ์ด อิงตามเครื่องดนตรีหลักของอาจารย์ (ใช้ CSS gradient แทนรูปสต็อก)
    $coverThemes = [
        ['grad' => 'linear-gradient(135deg,#1f3350,#3a5578)', 'icon' => 'bi-music-note-beamed'], // น้ำเงินเข้ม
        ['grad' => 'linear-gradient(135deg,#4b3621,#7a5a3a)', 'icon' => 'bi-mic'], // น้ำตาล
        ['grad' => 'linear-gradient(135deg,#2e2a26,#55504a)', 'icon' => 'bi-vinyl'], // เทาเข้ม/ดำอมน้ำตาล
        ['grad' => 'linear-gradient(135deg,#1c1a17,#3d3833)', 'icon' => 'bi-disc'], // ดำ
        ['grad' => 'linear-gradient(135deg,#374151,#6b7280)', 'icon' => 'bi-soundwave'], // เทาสเลท
    ];
    $themeFor = function ($teacher) use ($coverThemes) {
        $seed = $teacher->instruments->first()->id ?? $teacher->id;
        return $coverThemes[$seed % count($coverThemes)];
    };
@endphp

@section('content')
    <div class="breadcrumb-sm">
        บุคคล <i class="bi bi-chevron-right small"></i> อาจารย์</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title">จัดการอาจารย์</h1>
            <div class="page-sub">อาจารย์ {{ $teachers->total() }} ท่าน ·
                {{ number_format($teachers->sum(fn($t) => $t->totalHours(now()->startOfMonth()->toDateString())), 0) }}
                ชั่วโมงสอนเดือนนี้</div>
        </div>
        <a href="{{ route('teachers.create') }}" class="btn btn-accent">
            <i class="bi bi-plus-lg"></i> เพิ่มอาจารย์
        </a>
    </div>

    <ul class="nav tab-pills mb-3">
        <li class="nav-item"><a class="nav-link active" href="{{ route('teachers.index') }}">รายชื่ออาจารย์ <span
                    class="text-muted">{{ $teachers->total() }}</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#" tabindex="-1" title="ยังไม่เปิดใช้งาน">เวลาที่ว่าง
                (Availability)</a></li>
        <li class="nav-item"><a class="nav-link" href="#" tabindex="-1"
                title="ยังไม่เปิดใช้งาน">จับคู่ครู-นักเรียน</a></li>
    </ul>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาอาจารย์">
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
                    <select name="employment_type" class="form-select">
                        <option value="">ทุกประเภทจ้าง</option>
                        <option value="full_time" @selected(request('employment_type') == 'full_time')>Full-time</option>
                        <option value="freelance" @selected(request('employment_type') == 'freelance')>Freelance</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="teaching_type_id" class="form-select">
                        <option value="">ทุกประเภทสอน</option>
                        @foreach ($teachingTypes as $t)
                            <option value="{{ $t->id }}" @selected(request('teaching_type_id') == $t->id)>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="is_active" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="1" @selected(request('is_active') === '1')>ใช้งานอยู่</option>
                        <option value="0" @selected(request('is_active') === '0')>ปิดใช้งาน</option>
                    </select>
                </div>
                <div class="col-md-1 d-grid">
                    <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                </div>
            </form>
            <div class="mt-3">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-accent" id="btn-view-card"><i class="bi bi-grid-3x3-gap"></i>
                        การ์ด</button>
                    <button type="button" class="btn btn-outline-secondary" id="btn-view-table"><i
                            class="bi bi-list-ul"></i> ตาราง</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== มุมมองการ์ด ===== --}}
    <div id="view-card" class="row g-3">
        @forelse($teachers as $teacher)
            @php $theme = $themeFor($teacher); @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 overflow-hidden">
                    <div style="height:110px;background:{{ $theme['grad'] }};position:relative;">
                        <i class="bi {{ $theme['icon'] }}"
                            style="position:absolute;right:14px;top:14px;font-size:2.1rem;color:rgba(255,255,255,.35);"></i>
                        <span class="badge {{ $teacher->hasClassToday() ? 'text-bg-light' : 'bg-secondary' }}"
                            style="position:absolute;left:12px;top:12px;font-size:.68rem;">
                            <i class="bi bi-circle-fill"
                                style="font-size:.5rem;color:{{ $teacher->hasClassToday() ? 'var(--success)' : '#9ca3af' }};"></i>
                            {{ $teacher->hasClassToday() ? 'มีคลาส' : 'ไม่มีคลาสวันนี้' }}
                        </span>

                        <div
                            style="position:absolute;left:16px;bottom:-26px;width:56px;height:56px;border-radius:50%;
                                background:#fff;border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.15);
                                display:flex;align-items:center;justify-content:center;overflow:hidden;">
                            @if ($teacher->photo_path)
                                <img src="{{ asset('storage/' . $teacher->photo_path) }}"
                                    style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <span
                                    style="font-weight:700;color:var(--accent-dark);font-family:'Prompt',sans-serif;">{{ $teacher->initials() }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body pt-4 mt-2">
                        <div class="fw-bold" style="font-family:'Prompt',sans-serif;">
                            {{ $teacher->nickname ?: $teacher->full_name }}</div>
                        <div class="text-muted small mb-2">อ.{{ $teacher->full_name }}</div>

                        <div class="mb-2">
                            <span
                                class="badge {{ $teacher->employment_type == 'full_time' ? 'text-bg-success' : 'text-bg-info' }}">{{ $teacher->employmentTypeLabel() }}</span>
                            @foreach ($teacher->instruments->take(2) as $ins)
                                <span class="badge text-bg-light border">{{ $ins->name }}</span>
                            @endforeach
                            @if ($teacher->instruments->count() > 2)
                                <span class="badge text-bg-light border">+{{ $teacher->instruments->count() - 2 }}</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            @foreach ($teacher->levels->take(3) as $lv)
                                <span class="badge text-bg-light border small">{{ $lv->name }}</span>
                            @endforeach
                            @if ($teacher->levels->count() > 3)
                                <span class="badge text-bg-light border small">+{{ $teacher->levels->count() - 3 }}</span>
                            @endif
                        </div>

                        <div class="row text-center border-top pt-2 g-0 small">
                            <div class="col-4">
                                <div class="text-muted" style="font-size:.7rem;">เดือนนี้</div>
                                <div class="stat-value fw-semibold">
                                    {{ number_format($teacher->totalHours(now()->startOfMonth()->toDateString()), 0) }} ชม.
                                </div>
                            </div>
                            <div class="col-4 border-start">
                                <div class="text-muted" style="font-size:.7rem;">นักเรียน</div>
                                <div class="stat-value fw-semibold">{{ $teacher->studentCount() }}</div>
                            </div>
                            <div class="col-4 border-start">
                                <div class="text-muted" style="font-size:.7rem;">สาขา</div>
                                <div class="stat-value fw-semibold text-truncate" title="{{ $teacher->branch }}">
                                    {{ $teacher->branch ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex gap-2 border-top-0 pt-0">
                        <a href="{{ route('teachers.show', $teacher) }}"
                            class="btn btn-sm btn-outline-secondary flex-grow-1">ดูรายละเอียด</a>
                        <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-primary"><i
                                class="bi bi-pencil"></i></a>
                        <form action="{{ route('teachers.destroy', $teacher) }}" method="POST"
                            onsubmit="return confirm('ยืนยันการลบข้อมูลอาจารย์นี้?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center text-muted py-5">ไม่พบข้อมูลอาจารย์</div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- ===== มุมมองตาราง ===== --}}
    <div id="view-table" class="card d-none">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>รหัส</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>ประเภทการจ้าง</th>
                        <th>เครื่องดนตรี</th>
                        <th>เบอร์โทร</th>
                        <th>สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->teacher_code }}</td>
                            <td><a href="{{ route('teachers.show', $teacher) }}"
                                    class="fw-semibold text-decoration-none">{{ $teacher->full_name }}</a></td>
                            <td><span
                                    class="badge {{ $teacher->employment_type == 'full_time' ? 'text-bg-success' : 'text-bg-info' }}">{{ $teacher->employmentTypeLabel() }}</span>
                            </td>
                            <td>
                                @foreach ($teacher->instruments as $ins)
                                    <span class="badge text-bg-light border">{{ $ins->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $teacher->phone }}</td>
                            <td>{!! $teacher->is_active
                                ? '<span class="badge text-bg-success">ใช้งานอยู่</span>'
                                : '<span class="badge text-bg-secondary">ปิดใช้งาน</span>' !!}</td>
                            <td class="text-end">
                                <a href="{{ route('teachers.show', $teacher) }}"
                                    class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('teachers.edit', $teacher) }}"
                                    class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('ยืนยันการลบข้อมูลอาจารย์นี้?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">ไม่พบข้อมูลอาจารย์</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $teachers->links() }}</div>
@endsection

@section('scripts')
    <script>
        document.getElementById('btn-view-table').addEventListener('click', function() {
            document.getElementById('view-card').classList.add('d-none');
            document.getElementById('view-table').classList.remove('d-none');
            this.classList.replace('btn-outline-secondary', 'btn-accent');
            document.getElementById('btn-view-card').classList.replace('btn-accent', 'btn-outline-secondary');
        });
        document.getElementById('btn-view-card').addEventListener('click', function() {
            document.getElementById('view-table').classList.add('d-none');
            document.getElementById('view-card').classList.remove('d-none');
            this.classList.replace('btn-outline-secondary', 'btn-accent');
            document.getElementById('btn-view-table').classList.replace('btn-accent', 'btn-outline-secondary');
        });
    </script>
@endsection

@extends('layouts.app')
@section('title', 'จัดการผู้ปกครอง')

@section('content')
    <div class="breadcrumb-sm">บุคคล <i class="bi bi-chevron-right small"></i> ผู้ปกครอง</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title">จัดการผู้ปกครอง</h1>
            <div class="page-sub">ผู้ปกครองทั้งหมด {{ $guardians->total() }} คน</div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาชื่อ / เบอร์โทร">
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
                        <th>ชื่อ</th>
                        <th>เบอร์โทร</th>
                        <th>อีเมล</th>
                        <th>นักเรียนที่ดูแล</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guardians as $g)
                        <tr>
                            <td class="fw-semibold">{{ $g->full_name }}</td>
                            <td>{{ $g->phone ?: '-' }}</td>
                            <td>{{ $g->email ?: '-' }}</td>
                            <td>
                                @forelse($g->students as $s)
                                    <a href="{{ route('students.show', $s) }}"
                                        class="badge text-bg-light border text-decoration-none">{{ $s->full_name }}</a>
                                @empty
                                    <span class="text-muted small">ยังไม่ผูกกับนักเรียนคนไหน</span>
                                @endforelse
                            </td>
                            <td class="text-end">
                                @if (!$g->user)
                                    <form action="{{ route('guardians.create-account', $g) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-light"><i class="bi bi-key"></i>
                                            สร้างบัญชีผู้ใช้งาน</button>
                                    </form>
                                @endif
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse"
                                    data-bs-target="#edit-guardian-{{ $g->id }}"><i
                                        class="bi bi-pencil"></i></button>
                                <form action="{{ route('guardians.destroy', $g) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('ลบข้อมูลผู้ปกครองนี้? (จะหลุดจากนักเรียนทุกคนที่ผูกไว้)')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <tr class="collapse" id="edit-guardian-{{ $g->id }}">
                            <td colspan="5" class="bg-light">
                                <form action="{{ route('guardians.update', $g) }}" method="POST" class="row g-2 py-2">
                                    @csrf @method('PUT')
                                    <div class="col-md-3"><input type="text" name="full_name"
                                            class="form-control form-control-sm" value="{{ $g->full_name }}" required>
                                    </div>
                                    <div class="col-md-2"><input type="text" name="phone"
                                            class="form-control form-control-sm" value="{{ $g->phone }}"
                                            placeholder="เบอร์โทร"></div>
                                    <div class="col-md-3"><input type="email" name="email"
                                            class="form-control form-control-sm" value="{{ $g->email }}"
                                            placeholder="อีเมล"></div>
                                    <div class="col-md-2"><input type="text" name="line_id"
                                            class="form-control form-control-sm" value="{{ $g->line_id }}"
                                            placeholder="Line ID"></div>
                                    <div class="col-md-2 d-grid"><button class="btn btn-sm btn-accent">บันทึก</button></div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">ยังไม่มีข้อมูลผู้ปกครอง —
                                เพิ่มได้จากหน้าโปรไฟล์นักเรียน แท็บ "ผู้ปกครอง"</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $guardians->links() }}</div>
    </div>
@endsection

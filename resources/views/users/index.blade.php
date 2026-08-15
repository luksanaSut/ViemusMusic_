@extends('layouts.app')
@section('title', 'จัดการผู้ใช้งานระบบ')

@section('content')
    <div class="breadcrumb-sm">ระบบ <i class="bi bi-chevron-right small"></i> ผู้ใช้งานระบบ</div>
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h1 class="page-title">จัดการผู้ใช้งานระบบ</h1>
        <a href="{{ route('users.create') }}" class="btn btn-accent"><i class="bi bi-person-plus"></i> สร้างบัญชีใหม่</a>
    </div>

    @if (session('generated_password'))
        <div class="alert alert-success">
            <strong><i class="bi bi-key"></i> สร้างบัญชีสำเร็จ!</strong><br>
            อีเมล: <code>{{ session('generated_email') }}</code><br>
            รหัสผ่านชั่วคราว: <code style="font-size:1.1rem;">{{ session('generated_password') }}</code><br>
            <small class="text-muted">กรุณาคัดลอกและส่งให้ผู้ใช้งาน — รหัสนี้จะไม่แสดงซ้ำอีก
                ระบบจะบังคับให้เปลี่ยนรหัสผ่านตอนล็อกอินครั้งแรก</small>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-5"><input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="ค้นหาชื่อ / อีเมล"></div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">ทุกบทบาท</option>
                        <option value="admin" @selected(request('role') == 'admin')>ผู้ดูแลระบบ</option>
                        <option value="teacher" @selected(request('role') == 'teacher')>อาจารย์</option>
                        <option value="student" @selected(request('role') == 'student')>นักเรียน</option>
                        <option value="guardian" @selected(request('role') == 'guardian')>ผู้ปกครอง</option>
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
                        <th>ชื่อ</th>
                        <th>อีเมล</th>
                        <th>บทบาท</th>
                        <th>ผูกกับ</th>
                        <th>สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>{{ $u->displayName() }}</td>
                            <td>{{ $u->email }}</td>
                            <td><span class="badge {{ $u->roleBadgeClass() }}">{{ $u->roleLabel() }}</span></td>
                            <td class="small text-muted">
                                @if ($u->teacher)
                                    อาจารย์: {{ $u->teacher->full_name }}
                                @elseif($u->student)
                                    นักเรียน: {{ $u->student->full_name }}
                                @elseif($u->guardian)
                                    ผู้ปกครอง: {{ $u->guardian->full_name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('users.toggle-active', $u) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm border-0 p-0"
                                        {{ $u->id === auth()->id() ? 'disabled' : '' }}>
                                        <span
                                            class="badge {{ $u->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $u->is_active ? 'ใช้งานได้' : 'ปิดใช้งาน' }}</span>
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('users.reset-password', $u) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('รีเซ็ตรหัสผ่านของ {{ $u->email }}?')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary" title="รีเซ็ตรหัสผ่าน"><i
                                            class="bi bi-key"></i></button>
                                </form>
                                @if ($u->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('ลบบัญชีนี้?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">ยังไม่มีผู้ใช้งานในระบบ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $users->links() }}</div>
    </div>
@endsection

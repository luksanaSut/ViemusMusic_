@extends('layouts.app')
@section('title', 'จัดการสิทธิ์')

@section('content')

    <div class="breadcrumb-sm mb-2">
        ระบบ
        <i class="bi bi-chevron-right small"></i>
        จัดการสิทธิ์
    </div>

    <div class="mb-4">
        <h1 class="page-title mb-1">
            <i class="bi bi-shield-lock me-1"></i>
            จัดการสิทธิ์
        </h1>
        <div class="page-sub">
            กำหนดว่าเจ้าหน้าที่ (Staff) เข้าถึงโมดูลใดได้บ้าง
        </div>
    </div>

    <div class="alert alert-light border small mb-4">
        <i class="bi bi-info-circle"></i>
        ผู้ดูแลระบบ (Admin) มีสิทธิ์เข้าถึงทุกโมดูลเสมอ ไม่สามารถปรับได้ — หน้านี้ปรับเฉพาะสิทธิ์ของ "เจ้าหน้าที่ (Staff)" เท่านั้น
        โมดูล "จัดการผู้ใช้งาน" และ "จัดการสิทธิ์" ถูกล็อกให้เฉพาะแอดมินเข้าถึงได้เสมอ เพื่อป้องกันการยกระดับสิทธิ์ตัวเอง
    </div>

    <form action="{{ route('role-permissions.update') }}" method="POST">
        @csrf

        @foreach ($permissions as $module => $items)
            <div class="card mb-3">
                <div class="card-header bg-white py-3 fw-semibold">
                    {{ $module }}
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <tbody>
                            @foreach ($items as $permission)
                                <tr>
                                    <td class="ps-3">{{ $permission->label }}</td>
                                    <td class="text-end pe-3" style="width:160px;">
                                        <div class="form-check form-switch d-inline-block mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[]" value="{{ $permission->key }}"
                                                id="perm-{{ $permission->key }}"
                                                @checked(in_array($permission->key, $grantedKeys))>
                                            <label class="form-check-label small" for="perm-{{ $permission->key }}">
                                                Staff เข้าถึงได้
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึกสิทธิ์</button>
        </div>
    </form>

@endsection

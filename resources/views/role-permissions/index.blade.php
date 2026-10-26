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
            กำหนดว่าเจ้าหน้าที่ (Staff) ดูหรือแก้ไขโมดูลใดได้บ้าง
        </div>
    </div>



    <form action="{{ route('role-permissions.update') }}" method="POST">
        @csrf

        @foreach ($permissions as $module => $items)
            <div class="card mb-3">
                <div class="card-header bg-white py-3 fw-semibold d-flex">
                    <span class="flex-grow-1">{{ $module }}</span>
                    <span class="small text-muted fw-normal text-center" style="width:110px;">ดูได้</span>
                    <span class="small text-muted fw-normal text-center" style="width:110px;">แก้ไขได้</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <tbody>
                            @foreach ($items as $permission)
                                @php
                                    $canView = array_key_exists($permission->key, $granted);
                                    $canEdit = $granted[$permission->key] ?? false;
                                @endphp
                                <tr>
                                    <td class="ps-3">{{ $permission->label }}</td>
                                    <td class="text-center" style="width:110px;">
                                        <div class="form-check form-switch d-inline-block mb-0">
                                            <input class="form-check-input perm-view" type="checkbox" role="switch"
                                                name="view[]" value="{{ $permission->key }}"
                                                id="view-{{ $permission->key }}" data-key="{{ $permission->key }}"
                                                aria-label="{{ $permission->label }} — ดูได้" @checked($canView)>
                                        </div>
                                    </td>
                                    <td class="text-center pe-3" style="width:110px;">
                                        <div class="form-check form-switch d-inline-block mb-0">
                                            <input class="form-check-input perm-edit" type="checkbox" role="switch"
                                                name="edit[]" value="{{ $permission->key }}"
                                                id="edit-{{ $permission->key }}" data-key="{{ $permission->key }}"
                                                aria-label="{{ $permission->label }} — แก้ไขได้"
                                                @checked($canEdit)>
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

    <script>
        // เปิด "แก้ไขได้" → เปิด "ดูได้" ให้ด้วย; ปิด "ดูได้" → ปิด "แก้ไขได้" ด้วย
        document.querySelectorAll('.perm-edit').forEach(edit => {
            edit.addEventListener('change', () => {
                if (edit.checked) document.getElementById('view-' + edit.dataset.key).checked = true;
            });
        });
        document.querySelectorAll('.perm-view').forEach(view => {
            view.addEventListener('change', () => {
                if (!view.checked) document.getElementById('edit-' + view.dataset.key).checked = false;
            });
        });
    </script>

@endsection

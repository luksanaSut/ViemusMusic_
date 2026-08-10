@extends('layouts.app')
@section('title', 'เพิ่มข้อมูลนักเรียน')

@section('content')
    <div class="breadcrumb-sm">บุคคล <i class="bi bi-chevron-right small"></i> นักเรียน <i
            class="bi bi-chevron-right small"></i> เพิ่ม</div>
    <h1 class="page-title mb-3">เพิ่มข้อมูลนักเรียน</h1>

    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('students._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึก</button>
            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
@endsection

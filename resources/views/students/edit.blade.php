@extends('layouts.app')
@section('title', 'แก้ไขข้อมูลนักเรียน')

@section('content')
    <div class="breadcrumb-sm">บุคคล <i class="bi bi-chevron-right small"></i> นักเรียน <i
            class="bi bi-chevron-right small"></i> แก้ไข</div>
    <h1 class="page-title mb-3">แก้ไขข้อมูลนักเรียน: {{ $student->full_name }}</h1>

    <form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('students._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึกการแก้ไข</button>
            <a href="{{ route('students.show', $student) }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
@endsection

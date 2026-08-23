@extends('layouts.app')
@section('title', 'แก้ไขคอร์สเรียน')

@section('content')
    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> คอร์สเรียน <i
            class="bi bi-chevron-right small"></i> แก้ไข</div>
    <h1 class="page-title mb-3">แก้ไขคอร์สเรียน: {{ $course->name }}</h1>

    <form action="{{ route('courses.update', $course) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('courses._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึกการแก้ไข</button>
            <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
@endsection

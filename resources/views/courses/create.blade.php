@extends('layouts.app')
@section('title', 'เพิ่มคอร์สเรียน')

@section('content')
    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> คอร์สเรียน <i
            class="bi bi-chevron-right small"></i> เพิ่มคอร์ส</div>
    <h1 class="page-title mb-3">เพิ่มคอร์สเรียน</h1>

    <form action="{{ route('courses.store') }}" method="POST">
        @csrf
        @include('courses._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึก</button>
            <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
@endsection

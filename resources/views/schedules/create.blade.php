@extends('layouts.app')
@section('title', 'เพิ่มตารางเรียน')

@section('content')
    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> ตารางเรียน <i
            class="bi bi-chevron-right small"></i> เพิ่ม</div>
    <h1 class="page-title mb-3"><i class="bi bi-calendar-plus"></i> เพิ่มตารางเรียน</h1>

    <form action="{{ route('schedules.store') }}" method="POST">
        @csrf
        @include('schedules._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึกตารางเรียน</button>
            <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
@endsection

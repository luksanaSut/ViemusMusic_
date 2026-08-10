@extends('layouts.app')
@section('title', 'เพิ่มห้องเรียน')

@section('content')
    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> ห้องเรียน <i
            class="bi bi-chevron-right small"></i> เพิ่ม</div>
    <h1 class="page-title mb-3">เพิ่มห้องเรียน</h1>

    <form action="{{ route('rooms.store') }}" method="POST">
        @csrf
        @include('rooms._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึก</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
@endsection

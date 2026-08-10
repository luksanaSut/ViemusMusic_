@extends('layouts.app')
@section('title', 'แก้ไขห้องเรียน')

@section('content')
    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> ห้องเรียน <i
            class="bi bi-chevron-right small"></i> แก้ไข</div>
    <h1 class="page-title mb-3">แก้ไขห้องเรียน: {{ $room->name }}</h1>

    <form action="{{ route('rooms.update', $room) }}" method="POST">
        @csrf @method('PUT')
        @include('rooms._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึกการแก้ไข</button>
            <a href="{{ route('rooms.show', $room) }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
@endsection

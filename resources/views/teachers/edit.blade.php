@extends('layouts.app')
@section('title', 'แก้ไขข้อมูลอาจารย์')

@section('content')
<h4 class="mb-3"><i class="bi bi-pencil-square"></i> แก้ไขข้อมูลอาจารย์: {{ $teacher->full_name }}</h4>

<div class="card">
    <div class="card-body">
        <form action="{{ route('teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('teachers._form')

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary"><i class="bi bi-save"></i> บันทึกการแก้ไข</button>
                <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection

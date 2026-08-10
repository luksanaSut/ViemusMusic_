@extends('layouts.app')
@section('title', 'เพิ่มข้อมูลอาจารย์')

@section('content')
<h4 class="mb-3"><i class="bi bi-person-plus"></i> เพิ่มข้อมูลอาจารย์</h4>

<div class="card">
    <div class="card-body">
        <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('teachers._form')

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary"><i class="bi bi-save"></i> บันทึก</button>
                <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection

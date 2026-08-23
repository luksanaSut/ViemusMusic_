@extends('layouts.app')
@section('title', 'เพิ่มระดับสมาชิก')

@section('content')
    <div class="breadcrumb-sm mb-2">
        งานขาย
        <i class="bi bi-chevron-right small"></i>
        <a href="{{ route('membership-tiers.index') }}" class="text-reset">ระดับสมาชิก</a>
        <i class="bi bi-chevron-right small"></i>
        เพิ่มใหม่
    </div>

    <h1 class="page-title mb-3">
        <i class="bi bi-plus-circle me-1"></i>
        เพิ่มระดับสมาชิกใหม่
    </h1>

    <form action="{{ route('membership-tiers.store') }}" method="POST">
        @csrf
        @include('membership-tiers._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึก</button>
            <a href="{{ route('membership-tiers.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
@endsection

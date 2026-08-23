@extends('layouts.app')
@section('title', 'เพิ่มโปรโมชัน/คูปอง')

@section('content')
    <div class="breadcrumb-sm mb-2">
        งานขาย
        <i class="bi bi-chevron-right small"></i>
        <a href="{{ route('promotions.index') }}" class="text-reset">โปรโมชัน / คูปอง</a>
        <i class="bi bi-chevron-right small"></i>
        เพิ่มใหม่
    </div>

    <h1 class="page-title mb-3">
        <i class="bi bi-plus-circle me-1"></i>
        เพิ่มโปรโมชัน / คูปองใหม่
    </h1>

    <form action="{{ route('promotions.store') }}" method="POST" id="promotionForm">
        @csrf
        @include('promotions._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึก</button>
            <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
@endsection

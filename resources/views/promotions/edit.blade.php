@extends('layouts.app')
@section('title', 'แก้ไขโปรโมชัน/คูปอง')

@section('content')
    <div class="breadcrumb-sm mb-2">
        งานขาย
        <i class="bi bi-chevron-right small"></i>
        <a href="{{ route('promotions.index') }}" class="text-reset">โปรโมชัน / คูปอง</a>
        <i class="bi bi-chevron-right small"></i>
        แก้ไข
    </div>

    <h1 class="page-title mb-3">
        <i class="bi bi-pencil-square me-1"></i>
        แก้ไขโปรโมชัน / คูปอง
    </h1>

    <form action="{{ route('promotions.update', $promotion) }}" method="POST" id="promotionForm">
        @csrf
        @method('PUT')
        @include('promotions._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึก</button>
            <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
@endsection

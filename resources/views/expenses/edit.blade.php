@extends('layouts.app')
@section('title', 'แก้ไขรายจ่าย')

@section('content')
    <div class="breadcrumb-sm mb-2">
        การเงิน
        <i class="bi bi-chevron-right small"></i>
        <a href="{{ route('expenses.index') }}" class="text-reset">บันทึกรายจ่าย</a>
        <i class="bi bi-chevron-right small"></i>
        แก้ไข
    </div>

    <h1 class="page-title mb-3">
        <i class="bi bi-pencil-square me-1"></i>
        แก้ไขรายจ่าย
    </h1>

    <form action="{{ route('expenses.update', $expense) }}" method="POST">
        @csrf
        @method('PUT')
        @include('expenses._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึก</button>
            <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
@endsection

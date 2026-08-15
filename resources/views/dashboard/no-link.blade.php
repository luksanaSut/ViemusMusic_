@extends('layouts.app')
@section('title', 'แดชบอร์ด')

@section('content')
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-circle"></i>
        บัญชีนี้เป็น{{ $role }}แต่ยังไม่ได้ผูกกับข้อมูล{{ $role }}ในระบบ กรุณาติดต่อผู้ดูแลระบบ
    </div>
@endsection

@extends('layouts.app')
@section('title','เพิ่มผู้สนใจ')
@section('content')
<div class="breadcrumb-sm">บุคคล <i class="bi bi-chevron-right small"></i> ผู้สนใจและทดลองเรียน</div>
<div class="d-flex justify-content-between align-items-start mb-3"><div><h1 class="page-title">เพิ่มผู้สนใจ</h1><div class="page-sub">รับข้อมูลเบื้องต้นและนัดทดลองเรียน</div></div><a href="{{ route('trial-leads.index') }}" class="btn btn-outline-secondary">กลับ</a></div>
<form method="POST" action="{{ route('trial-leads.store') }}" enctype="multipart/form-data">@csrf @include('trial-leads._form')<button class="btn btn-accent mb-4"><i class="bi bi-save"></i> บันทึกผู้สนใจ</button></form>
@endsection

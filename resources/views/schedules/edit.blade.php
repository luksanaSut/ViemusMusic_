@extends('layouts.app')
@section('title', 'แก้ไขตารางเรียน')

@section('content')
    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> ตารางเรียน <i
            class="bi bi-chevron-right small"></i> แก้ไข</div>
    <h1 class="page-title mb-3"><i class="bi bi-pencil-square"></i> แก้ไขตารางเรียน</h1>

    {{-- ฟอร์มแก้ไขข้อมูล (แยกออกจากฟอร์มยกเลิกด้านล่างอย่างเด็ดขาด — HTML ไม่รองรับการซ้อนฟอร์ม --}}
    <form action="{{ route('schedules.update', $classSchedule) }}" method="POST" id="editScheduleForm">
        @csrf @method('PUT')
        @include('schedules._form')
        <div class="d-flex gap-2 mb-3">
            <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึกการแก้ไข</button>
            <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">กลับ</a>
        </div>
    </form>

    {{-- ฟอร์มยกเลิกคาบเรียน — วางแยกเป็นฟอร์มอิสระ ไม่อยู่ซ้อนในฟอร์มด้านบน --}}
    <form action="{{ route('schedules.cancel', $classSchedule) }}" method="POST" class="mb-4"
        onsubmit="return confirm('ยกเลิกตารางเรียนคาบนี้?')">
        @csrf @method('PATCH')
        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-x-circle"></i> ยกเลิกคาบเรียนนี้</button>
    </form>
@endsection

@extends('layouts.app')
@section('title', 'เพิ่มสินค้า')

@section('content')
    <div class="breadcrumb-sm">Music Store <i class="bi bi-chevron-right small"></i> จัดการสินค้า <i
            class="bi bi-chevron-right small"></i> เพิ่ม</div>
    <h1 class="page-title mb-3"><i class="bi bi-box-seam"></i> เพิ่มสินค้าใหม่</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">รหัสสินค้า (สร้างอัตโนมัติ)</label>
                        <input type="text" class="form-control" value="{{ $nextSku }}" disabled>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">ชื่อสินค้า *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">รายละเอียดสินค้า</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">หมวดหมู่</label>
                        <select name="category_id" class="form-select">
                            <option value="">ไม่ระบุ</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">ราคาขาย (บาท) *</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">ต้นทุน (บาท)</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">จำนวนสต็อกเริ่มต้น</label>
                        <input type="number" name="initial_stock" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">แจ้งเตือนเมื่อเหลือต่ำกว่า (ชิ้น)</label>
                        <input type="number" name="reorder_level" class="form-control" value="5" min="0"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">สถานะ</label>
                        <select name="status" class="form-select">
                            <option value="active">พร้อมขาย</option>
                            <option value="inactive">ปิดการขาย</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">รูปภาพสินค้า</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึกสินค้า</button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
    </form>
@endsection

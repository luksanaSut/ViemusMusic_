@extends('layouts.app')
@section('title', 'แก้ไขสินค้า')

@section('content')
    <h1 class="page-title mb-3"><i class="bi bi-pencil-square"></i> แก้ไขสินค้า</h1>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">รหัสสินค้า</label>
                        <input type="text" class="form-control" value="{{ $product->sku }}" disabled>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">ชื่อสินค้า *</label>
                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">รายละเอียดสินค้า</label>
                        <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">หมวดหมู่</label>
                        <select name="category_id" class="form-select">
                            <option value="">ไม่ระบุ</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}" @selected($product->category_id == $c->id)>{{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">ราคาขาย (บาท) *</label>
                        <input type="number" step="0.01" name="price" class="form-control"
                            value="{{ $product->price }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">ต้นทุน (บาท)</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control"
                            value="{{ $product->cost_price }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">แจ้งเตือนเมื่อเหลือต่ำกว่า (ชิ้น)</label>
                        <input type="number" name="reorder_level" class="form-control"
                            value="{{ $product->reorder_level }}" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">สถานะ</label>
                        <select name="status" class="form-select">
                            <option value="active" @selected($product->status == 'active')>พร้อมขาย</option>
                            <option value="inactive" @selected($product->status == 'inactive')>ปิดการขาย</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">รูปภาพสินค้า</label>
                        @if ($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" style="height:80px;"
                                class="d-block mb-2 rounded">
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="alert alert-light border small mt-3 mb-0"><i class="bi bi-info-circle"></i> จำนวนสต็อกปัจจุบัน:
                    <strong>{{ $product->stock_quantity }} ชิ้น</strong> — แก้ไขจำนวนสต็อกได้ที่หน้า "รายละเอียดสินค้า"
                    (ปุ่มปรับปรุงสต็อก) เท่านั้น เพื่อเก็บประวัติการเคลื่อนไหวให้ครบถ้วน</div>
            </div>
        </div>
        <button class="btn btn-accent"><i class="bi bi-save"></i> บันทึกการแก้ไข</button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
    </form>
@endsection

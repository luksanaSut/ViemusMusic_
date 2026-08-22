@extends('layouts.app')
@section('title', 'หมวดหมู่สินค้า')

@section('content')
    <div class="breadcrumb-sm">Music Store <i class="bi bi-chevron-right small"></i> หมวดหมู่สินค้า</div>
    <h1 class="page-title mb-3"><i class="bi bi-tags"></i> หมวดหมู่สินค้า</h1>

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('product-categories.store') }}" method="POST" class="row g-2">
                @csrf
                <div class="col-md-4"><input type="text" name="name" class="form-control"
                        placeholder="ชื่อหมวดหมู่ เช่น เครื่องดนตรี, อุปกรณ์เสริม" required></div>
                <div class="col-md-6"><input type="text" name="description" class="form-control"
                        placeholder="คำอธิบาย (ถ้ามี)"></div>
                <div class="col-md-2 d-grid"><button class="btn btn-accent"><i class="bi bi-plus-lg"></i> เพิ่ม</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ชื่อหมวดหมู่</th>
                        <th>จำนวนสินค้า</th>
                        <th>สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td>{{ $cat->name }}<div class="text-muted small">{{ $cat->description }}</div>
                            </td>
                            <td>{{ $cat->products_count }} รายการ</td>
                            <td>
                                <form action="{{ route('product-categories.toggle-active', $cat) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm border-0 p-0">
                                        <span
                                            class="badge {{ $cat->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $cat->is_active ? 'ใช้งาน' : 'ปิดใช้งาน' }}</span>
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('product-categories.destroy', $cat) }}" method="POST"
                                    onsubmit="return confirm('ลบหมวดหมู่นี้?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">ยังไม่มีหมวดหมู่สินค้า</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

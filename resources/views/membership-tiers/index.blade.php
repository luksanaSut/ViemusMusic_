@extends('layouts.app')
@section('title', 'ระดับสมาชิก')

@section('content')

    <div class="breadcrumb-sm mb-2">
        งานขาย
        <i class="bi bi-chevron-right small"></i>
        ระดับสมาชิก
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="page-title mb-1">
                <i class="bi bi-award me-1"></i>
                ระดับสมาชิก
            </h1>
            <div class="page-sub">
                กำหนดระดับสมาชิกและสิทธิประโยชน์ตามยอดใช้จ่ายสะสม
            </div>
        </div>

        <a href="{{ route('membership-tiers.create') }}" class="btn btn-accent">
            <i class="bi bi-plus-lg me-1"></i>
            เพิ่มระดับสมาชิกใหม่
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">
                        <i class="bi bi-award me-1 text-primary"></i>
                        รายการทั้งหมด
                    </div>
                    <div class="text-muted small mt-1">
                        ทั้งหมด {{ number_format($tiers->total()) }} ระดับ
                    </div>
                </div>
                <span class="badge text-bg-light border">
                    {{ number_format($tiers->total()) }} ระดับ
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">ลำดับ</th>
                        <th>ระดับ</th>
                        <th>ยอดใช้จ่ายขั้นต่ำ</th>
                        <th>สิทธิประโยชน์</th>
                        <th>สถานะ</th>
                        <th class="text-end pe-3">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tiers as $tier)
                        <tr>
                            <td class="ps-3">{{ $tier->sort_order }}</td>
                            <td>
                                <span class="badge {{ $tier->badgeClass() }} fs-6">
                                    <i class="bi bi-award me-1"></i>
                                    {{ $tier->name }}
                                </span>
                            </td>
                            <td class="fw-semibold">฿{{ number_format($tier->min_spend, 0) }}</td>
                            <td>
                                @if ($tier->benefitsList())
                                    <ul class="mb-0 ps-3 small">
                                        @foreach ($tier->benefitsList() as $benefit)
                                            <li>{{ $benefit }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('membership-tiers.toggle-active', $tier) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm p-0 border-0">
                                        @if ($tier->is_active)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="bi bi-check-circle me-1"></i> เปิดใช้งาน
                                            </span>
                                        @else
                                            <span class="badge text-bg-secondary">
                                                <i class="bi bi-x-circle me-1"></i> ปิดใช้งาน
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('membership-tiers.edit', $tier) }}" class="btn btn-sm btn-outline-secondary" title="แก้ไข">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('membership-tiers.destroy', $tier) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('ยืนยันการลบระดับ {{ $tier->name }} ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบ">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-award fs-1 text-secondary"></i>
                                <div class="fw-semibold mt-2">ยังไม่มีระดับสมาชิก</div>
                                <div class="text-muted small mt-1">เพิ่มระดับสมาชิกใหม่ได้จากปุ่มด้านบน</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tiers->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <div class="text-muted small">
                        แสดง {{ $tiers->firstItem() }} - {{ $tiers->lastItem() }}
                        จาก {{ $tiers->total() }} รายการ
                    </div>
                    <div>
                        {{ $tiers->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

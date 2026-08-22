@extends('layouts.app')
@section('title', $product->name)

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="page-title mb-0">{{ $product->name }}</h1>
            <div class="page-sub">{{ $product->sku }} @if ($product->category)
                    · {{ $product->category->name }}
                @endif
            </div>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i>
            กลับ</a>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if ($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" class="img-fluid rounded mb-3"
                            style="max-height:200px;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3"
                            style="height:200px;"><i class="bi bi-box-seam fs-1 text-muted"></i></div>
                    @endif
                    <h4 class="fw-bold">฿{{ number_format($product->price, 2) }}</h4>
                    <span class="badge {{ $product->statusBadgeClass() }}">{{ $product->statusLabel() }}</span>
                    <div class="mt-3 p-3 rounded {{ $product->isLowStock() ? 'bg-danger-subtle' : 'bg-success-subtle' }}">
                        <div class="small text-muted">คงเหลือ Real-time</div>
                        <div class="fs-3 fw-bold">{{ $product->stock_quantity }} ชิ้น</div>
                        @if ($product->isLowStock())
                            <div class="small text-danger"><i class="bi bi-exclamation-triangle"></i> ใกล้หมด (เกณฑ์
                                {{ $product->reorder_level }} ชิ้น)</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-2" style="font-family:'Prompt',sans-serif;">ปรับปรุงสต็อก</h6>
                    <form action="{{ route('products.stock.adjust', $product) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <select name="type" id="stockTypeSelect" class="form-select form-select-sm" required>
                                <option value="in">รับสินค้าเข้า</option>
                                <option value="out">ตัดออก (เช่น ชำรุด/สูญหาย)</option>
                                <option value="adjustment">ปรับปรุงยอด (นับสต็อกจริง)</option>
                            </select>
                        </div>
                        <div class="mb-2" id="directionBox" style="display:none;">
                            <select name="direction" class="form-select form-select-sm">
                                <option value="increase">เพิ่มยอด</option>
                                <option value="decrease">ลดยอด</option>
                            </select>
                        </div>
                        <div class="mb-2"><input type="number" name="quantity" class="form-control form-control-sm"
                                placeholder="จำนวน" min="1" required></div>
                        <div class="mb-2"><input type="text" name="reason" class="form-control form-control-sm"
                                placeholder="เหตุผล เช่น รับสินค้าล็อตใหม่" required></div>
                        <button class="btn btn-sm btn-accent w-100">บันทึก</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold mb-2" style="font-family:'Prompt',sans-serif;">ประวัติการเคลื่อนไหวสต็อก</h6>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>ประเภท</th>
                                <th>จำนวน</th>
                                <th>คงเหลือหลังทำรายการ</th>
                                <th>เหตุผล</th>
                                <th>โดย</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->stockMovements as $m)
                                <tr>
                                    <td class="small">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                                    <td><span class="badge {{ $m->typeBadgeClass() }}">{{ $m->typeLabel() }}</span></td>
                                    <td class="{{ $m->quantity < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}</td>
                                    <td>{{ $m->balance_after }}</td>
                                    <td class="small">{{ $m->reason }}</td>
                                    <td class="small">{{ $m->created_by }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">ยังไม่มีประวัติการเคลื่อนไหว</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('stockTypeSelect').addEventListener('change', function() {
            document.getElementById('directionBox').style.display = this.value === 'adjustment' ? 'block' : 'none';
        });
    </script>
@endsection

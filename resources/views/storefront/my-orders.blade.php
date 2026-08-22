@extends('layouts.app')
@section('title', 'คำสั่งซื้อของฉัน')

@section('content')
    <h1 class="page-title mb-3"><i class="bi bi-bag-check"></i> คำสั่งซื้อของฉัน</h1>

    @forelse($orders as $o)
        <div class="card mb-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-bold">{{ $o->sale_no }}</div>
                        <div class="text-muted small">{{ $o->items->count() }} รายการ ·
                            {{ $o->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">฿{{ number_format($o->total_amount, 2) }}</div>
                        <span class="badge {{ $o->statusBadgeClass() }}">{{ $o->statusLabel() }}</span>
                    </div>
                </div>
                @if ($o->status === 'pending_payment')
                    <div class="d-flex gap-2 mt-2">
                        <a href="{{ route('store.edit', $o) }}" class="btn btn-sm btn-outline-secondary"><i
                                class="bi bi-pencil"></i> แก้ไขรายการ</a>
                        <a href="{{ route('store.show', $o) }}" class="btn btn-sm btn-accent">ไปชำระเงิน</a>
                        <form action="{{ route('store.cancel', $o) }}" method="POST"
                            onsubmit="return confirm('ยกเลิกคำสั่งซื้อนี้?')" class="ms-auto">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i>
                                ยกเลิกคำสั่งซื้อ</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <p class="text-muted">ยังไม่มีประวัติการสั่งซื้อ</p>
    @endforelse
    {{ $orders->links() }}
@endsection

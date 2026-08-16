@extends('layouts.app')
@section('title', 'หมวดหมู่ประเมินผล')

@section('content')
    <style>
        .form-section {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.4rem 1.6rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 2px rgba(28, 26, 23, .04);
        }

        .category-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: .9rem 0;
            border-bottom: 1px solid #f0efec;
        }

        .category-item:last-child {
            border-bottom: 0;
        }

        .category-item .name {
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
        }

        .category-item .desc {
            font-size: .8rem;
            color: var(--muted, #6b655e);
        }
    </style>

    <div class="breadcrumb-sm">งานวิชาการ <i class="bi bi-chevron-right small"></i> หมวดหมู่ประเมินผล</div>
    <h1 class="page-title mb-3"><i class="bi bi-list-check"></i> หมวดหมู่ประเมินผลจบคอร์ส</h1>

    <div class="form-section">
        <div class="form-section-title"
            style="display:flex;align-items:center;gap:.7rem;font-weight:700;font-size:1.02rem;margin-bottom:1.2rem;padding-bottom:.9rem;border-bottom:1px solid var(--border,#e4e1dc);font-family:'Prompt',sans-serif;">
            <div class="icon-badge"
                style="width:36px;height:36px;border-radius:10px;background:var(--accent-soft,#e7ebf1);color:var(--accent-dark,#13233a);display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-plus-circle"></i></div>
            เพิ่มหมวดหมู่ใหม่
        </div>
        <form action="{{ route('evaluation-categories.store') }}" method="POST" class="row g-2">
            @csrf
            <div class="col-md-4"><input type="text" name="name" class="form-control"
                    placeholder="ชื่อหมวดหมู่ เช่น เทคนิคการเล่น" required></div>
            <div class="col-md-6"><input type="text" name="description" class="form-control"
                    placeholder="คำอธิบายเพิ่มเติม (ถ้ามี)"></div>
            <div class="col-md-2 d-grid"><button class="btn btn-accent"><i class="bi bi-plus-lg"></i> เพิ่ม</button></div>
        </form>
    </div>

    <div class="form-section">
        <div class="form-section-title"
            style="display:flex;align-items:center;gap:.7rem;font-weight:700;font-size:1.02rem;margin-bottom:1.2rem;padding-bottom:.9rem;border-bottom:1px solid var(--border,#e4e1dc);font-family:'Prompt',sans-serif;">
            <div class="icon-badge"
                style="width:36px;height:36px;border-radius:10px;background:var(--accent-soft,#e7ebf1);color:var(--accent-dark,#13233a);display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-list-ul"></i></div>
            หมวดหมู่ทั้งหมด ({{ $categories->count() }})
        </div>

        @forelse($categories as $cat)
            <div class="category-item">
                <div class="flex-grow-1">
                    <div class="name">{{ $cat->name }}</div>
                    @if ($cat->description)
                        <div class="desc">{{ $cat->description }}</div>
                    @endif
                </div>
                <form action="{{ route('evaluation-categories.toggle-active', $cat) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button class="btn btn-sm border-0 p-0">
                        <span
                            class="badge {{ $cat->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $cat->is_active ? 'ใช้งานอยู่' : 'ปิดใช้งาน' }}</span>
                    </button>
                </form>
                <form action="{{ route('evaluation-categories.destroy', $cat) }}" method="POST"
                    onsubmit="return confirm('ลบหมวดหมู่นี้? หากมีการประเมินผลที่ใช้หมวดหมู่นี้อยู่แล้ว ข้อมูลเก่าจะยังคงอยู่')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        @empty
            <p class="text-muted mb-0">ยังไม่มีหมวดหมู่ประเมินผล — เพิ่มได้จากฟอร์มด้านบน</p>
        @endforelse
    </div>
@endsection

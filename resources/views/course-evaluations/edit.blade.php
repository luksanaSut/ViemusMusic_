@extends('layouts.app')
@section('title', 'ประเมินผลจบคอร์ส')

@section('content')
    <style>
        .form-section {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.4rem 1.6rem;
            margin-bottom: 1.25rem;
        }

        .score-pills {
            display: flex;
            gap: .4rem;
        }

        .score-pill {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1.5px solid var(--border, #e4e1dc);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: 700;
        }

        .score-pill.active {
            background: var(--accent, #1f3350);
            border-color: var(--accent, #1f3350);
            color: #fff;
        }
    </style>

    <h1 class="page-title mb-1"><i class="bi bi-clipboard-data"></i> ประเมินผลจบคอร์ส</h1>
    <div class="page-sub mb-3">{{ $enrollment->student->full_name }} — {{ $enrollment->course->name }}</div>

    <form action="{{ route('course-evaluations.store', $enrollment) }}" method="POST">
        @csrf
        <div class="form-section">
            <div class="form-section-title mb-3"><strong>ให้คะแนนแต่ละหมวดหมู่ (1-5)</strong></div>
            @foreach ($categories as $i => $cat)
                @php $existingItem = $evaluation->items->firstWhere('evaluation_category_id', $cat->id); @endphp
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="fw-semibold">{{ $cat->name }}</div>
                            @if ($cat->description)
                                <div class="text-muted small">{{ $cat->description }}</div>
                            @endif
                        </div>
                        <div class="score-pills" data-index="{{ $i }}">
                            @for ($s = 1; $s <= 5; $s++)
                                <div class="score-pill {{ ($existingItem?->score ?? 0) == $s ? 'active' : '' }}"
                                    data-score="{{ $s }}">{{ $s }}</div>
                            @endfor
                        </div>
                    </div>
                    <input type="hidden" name="items[{{ $i }}][category_id]" value="{{ $cat->id }}">
                    <input type="hidden" name="items[{{ $i }}][score]" class="score-input"
                        value="{{ $existingItem->score ?? '' }}">
                    <input type="text" name="items[{{ $i }}][comment]" class="form-control form-control-sm"
                        placeholder="ความเห็นเพิ่มเติม (ถ้ามี)" value="{{ $existingItem->comment ?? '' }}">
                </div>
            @endforeach
        </div>

        <div class="form-section">
            <label class="form-label">ความเห็นโดยรวม</label>
            <textarea name="overall_comment" class="form-control" rows="3">{{ $evaluation->overall_comment }}</textarea>
        </div>

        <div class="d-flex gap-2 mb-4">
            <button type="submit" name="status" value="draft" class="btn btn-outline-secondary"><i
                    class="bi bi-save"></i> บันทึกฉบับร่าง</button>
            <button type="submit" name="status" value="published" class="btn btn-accent"
                onclick="return confirm('เผยแพร่แล้วนักเรียน/ผู้ปกครองจะเห็นผลประเมินทันที ยืนยันหรือไม่?')"><i
                    class="bi bi-send"></i> บันทึกและเผยแพร่</button>
        </div>
    </form>

    <script>
        document.querySelectorAll('.score-pills').forEach(group => {
            const idx = group.dataset.index;
            const hiddenInput = document.querySelector(`input[name="items[${idx}][score]"]`);
            group.querySelectorAll('.score-pill').forEach(pill => {
                pill.addEventListener('click', () => {
                    group.querySelectorAll('.score-pill').forEach(p => p.classList.remove(
                    'active'));
                    pill.classList.add('active');
                    hiddenInput.value = pill.dataset.score;
                });
            });
        });
        document.querySelector('form').addEventListener('submit', function(e) {
            const missing = [...document.querySelectorAll('.score-input')].some(el => !el.value);
            if (missing) {
                e.preventDefault();
                alert('กรุณาให้คะแนนครบทุกหมวดหมู่');
            }
        });
    </script>
@endsection

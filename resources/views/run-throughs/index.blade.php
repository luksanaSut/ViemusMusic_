@extends('layouts.app')
@section('title', 'Run Through')

@section('content')
    <style>
        .rt-intro { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem; }
        .rt-intro .lead-text { color: var(--muted); font-size: .86rem; margin: 0; }

        .stat-strip {
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--card);
            display: flex;
            margin-bottom: 1.25rem;
            overflow-x: auto;
            box-shadow: 0 8px 28px rgba(28, 26, 23, .04);
        }
        .stat-cell { flex: 1; min-width: 100px; padding: .9rem 1rem; text-align: center; border-left: 1px solid var(--border); }
        .stat-cell:first-child { border-left: 0; }
        .stat-cell .value { font-family: 'Prompt', sans-serif; font-weight: 700; font-size: 1.4rem; line-height: 1.1; }
        .stat-cell .label { font-size: .74rem; color: var(--muted); margin-top: .2rem; }

        .rt-create { display: flex; align-items: center; gap: .9rem; }
        .rt-create .icon-circle {
            width: 40px; height: 40px; border-radius: 12px; flex-shrink: 0;
            background: var(--accent-soft); color: var(--accent); display: flex;
            align-items: center; justify-content: center; font-size: 1.1rem;
        }
        .rt-create .heading { font-family: 'Prompt', sans-serif; font-weight: 700; font-size: .92rem; }
        .rt-create .sub { font-size: .78rem; color: var(--muted); }
        .rt-create form { flex: 1; }

        .rt-toolbar { margin-bottom: 1.1rem; }
        .rt-toolbar .search-box { position: relative; max-width: 360px; }
        .rt-toolbar .search-box input {
            width: 100%; border: 1px solid var(--border); border-radius: 9px;
            padding: .5rem .9rem .5rem 2.2rem; background: var(--surface); font-size: .86rem;
        }
        .rt-toolbar .search-box i { position: absolute; left: .75rem; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: .9rem; }

        .rt-card { border: 1px solid var(--border); border-radius: 14px; background: var(--card); padding: 1.1rem; }
        .rt-card + .rt-card { margin-top: .7rem; }
        .rt-card.st-pending { border-left: 3px solid var(--amber); }
        .rt-card.st-done { border-left: 3px solid var(--success); }

        .rt-avatar {
            width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
            background: var(--accent-soft); color: var(--accent); display: flex;
            align-items: center; justify-content: center; font-family: 'Prompt', sans-serif;
            font-weight: 700; font-size: .85rem;
        }
        .rt-title { font-family: 'Prompt', sans-serif; font-weight: 700; margin-bottom: .1rem; }
        .rt-meta { font-size: .78rem; color: var(--muted); }
        .rt-desc { font-size: .86rem; margin: .6rem 0 0; white-space: pre-line; }
        .rt-files { display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .6rem; }

        .rt-record { margin-top: .9rem; padding-top: .9rem; border-top: 1px dashed var(--border); }
        .rt-record .step-label { font-size: .74rem; color: var(--amber); font-weight: 700; text-transform: uppercase; letter-spacing: .4px; margin-bottom: .5rem; display: flex; align-items: center; gap: .35rem; }

        .rt-result-note { background: var(--surface); border-radius: 10px; padding: .7rem .9rem; margin-top: .9rem; font-size: .85rem; }
        .rt-result-note + .rt-result-note { margin-top: .5rem; }

        .rt-empty { text-align: center; padding: 3rem 1rem; color: var(--muted); }

        @media (max-width: 767.98px) {
            .rt-intro { align-items: flex-start; }
            .stat-strip { display: grid; grid-template-columns: repeat(3, 1fr); overflow: hidden; }
            .stat-cell { min-width: 0; padding: .7rem .4rem; border-left: 1px solid var(--border); }
            .stat-cell:first-child { border-left: 0; }
            .stat-cell .value { font-size: 1.15rem; }
            .rt-create { flex-wrap: wrap; }
            .rt-create form { flex-basis: 100%; }
            .rt-toolbar .search-box { max-width: none; }
        }
    </style>

    <div class="rt-intro">
        <div>
            <div class="breadcrumb-sm">การเรียนการสอน <i class="bi bi-chevron-right small"></i> Run Through</div>
            <h1 class="page-title mb-1"><i class="bi bi-arrow-repeat"></i> ระบบ Run Through</h1>
            <p class="lead-text">สร้างหัวข้อให้นักเรียนไปฝึกซ้อม แล้วกลับมาบันทึกผลเมื่อฝึกเสร็จแล้ว</p>
        </div>
        @if ($pendingCount > 0)
            <span class="badge rounded-pill" style="background:var(--amber-soft); color:var(--amber); padding:.55rem .75rem;"><i class="bi bi-hourglass-split"></i> {{ $pendingCount }} รอบันทึกผล</span>
        @endif
    </div>

    <div class="stat-strip">
        <div class="stat-cell">
            <div class="value">{{ $runThroughs->total() }}</div>
            <div class="label">ทั้งหมด</div>
        </div>
        <div class="stat-cell">
            <div class="value" style="color:var(--amber);">{{ $pendingCount }}</div>
            <div class="label">รอบันทึกผล</div>
        </div>
        <div class="stat-cell">
            <div class="value" style="color:var(--success);">{{ max(0, $runThroughs->total() - $pendingCount) }}</div>
            <div class="label">บันทึกผลแล้ว</div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="rt-create">
                <div class="icon-circle"><i class="bi bi-plus-lg"></i></div>
                @if ($pickerEnrollments->isEmpty())
                    <div>
                        <div class="heading">สร้าง Run Through ใหม่</div>
                        <div class="sub">ยังไม่มีนักเรียนที่กำลังเรียนอยู่ในความดูแลของคุณ จึงยังสร้างไม่ได้</div>
                    </div>
                @else
                    <div>
                        <div class="heading">สร้าง Run Through ใหม่</div>
                        <div class="sub">ขั้นตอนที่ 1 — เลือกนักเรียนและคอร์สที่ต้องการมอบหมาย</div>
                    </div>
                    <form method="GET" action="{{ route('run-throughs.new') }}" class="d-flex gap-2 flex-wrap flex-md-nowrap">
                        <select name="enrollment_id" class="form-select form-select-sm" required style="min-width:220px;">
                            <option value="">— เลือกนักเรียนและคอร์ส —</option>
                            @foreach ($pickerEnrollments as $enr)
                                <option value="{{ $enr->id }}">{{ $enr->student->full_name ?? '-' }} — {{ $enr->course->name ?? '-' }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-accent text-nowrap"><i class="bi bi-arrow-right-circle"></i> ไปสร้าง Run Through</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="rt-toolbar">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="rtSearch" placeholder="ค้นหานักเรียน คอร์ส หรือหัวข้อ">
        </div>
    </div>

    @forelse($runThroughs as $rt)
        @php
            $searchText = mb_strtolower(
                ($rt->title ?? '') . ' ' .
                ($rt->enrollment->student->full_name ?? '') . ' ' .
                ($rt->enrollment->course->name ?? '')
            );
        @endphp
        <div class="rt-card {{ $rt->result_recorded_at ? 'st-done' : 'st-pending' }}" data-search="{{ $searchText }}">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div class="d-flex gap-3">
                    <div class="rt-avatar">{{ mb_substr($rt->enrollment->student->full_name ?? '-', 0, 1) }}</div>
                    <div>
                        <div class="rt-title">{{ $rt->title }}</div>
                        <div class="rt-meta">{{ $rt->enrollment->student->full_name ?? '-' }} — {{ $rt->enrollment->course->name ?? '-' }} · สร้างเมื่อ {{ $rt->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
                <span class="badge {{ $rt->practiceResultBadgeClass() }} text-nowrap">{{ $rt->practiceResultLabel() }}</span>
            </div>

            @if ($rt->description)
                <p class="rt-desc text-muted">{{ $rt->description }}</p>
            @endif

            @if ($rt->attachments->count())
                <div class="rt-files">
                    @foreach ($rt->attachments as $att)
                        <a href="{{ $att->url() }}" target="_blank" class="badge text-bg-light border text-decoration-none"><i class="bi bi-paperclip"></i> {{ $att->original_name }}</a>
                    @endforeach
                </div>
            @endif

            @if (!$rt->result_recorded_at)
                <div class="rt-record">
                    <div class="step-label"><i class="bi bi-2-circle"></i> บันทึกผลการฝึกซ้อม</div>
                    <form action="{{ route('run-throughs.record-result', $rt) }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-md-4">
                            <select name="practice_result" class="form-select form-select-sm" required>
                                <option value="">เลือกผลการฝึกซ้อม</option>
                                <option value="excellent">ดีเยี่ยม</option>
                                <option value="good">ดี</option>
                                <option value="needs_practice">ต้องฝึกเพิ่ม</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="areas_to_improve" class="form-control form-control-sm" placeholder="สิ่งที่ต้องฝึกเพิ่มเติม (ถ้ามี)">
                        </div>
                        <div class="col-12">
                            <textarea name="teacher_comment" class="form-control form-control-sm" rows="2" placeholder="ความคิดเห็นเพิ่มเติมจากอาจารย์"></textarea>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-sm btn-accent">บันทึกผลการฝึกซ้อม</button>
                        </div>
                    </form>
                </div>
            @else
                @if ($rt->areas_to_improve)
                    <div class="rt-result-note" style="background:var(--amber-soft); color:var(--amber);">
                        <i class="bi bi-flag"></i> <strong>ต้องฝึกเพิ่ม:</strong> {{ $rt->areas_to_improve }}
                    </div>
                @endif
                @if ($rt->teacher_comment)
                    <div class="rt-result-note">
                        <strong>ความเห็นอาจารย์:</strong> {{ $rt->teacher_comment }}
                    </div>
                @endif
            @endif
        </div>
    @empty
        <div class="rt-card rt-empty">
            <i class="bi bi-inboxes fs-3 d-block mb-2"></i> ยังไม่มี Run Through ในระบบ
        </div>
    @endforelse

    <p class="rt-empty d-none" id="rtNoMatch">ไม่พบรายการที่ตรงกับคำค้นหา</p>

    <div class="mt-3">{{ $runThroughs->links() }}</div>

    <script>
        (function () {
            const search = document.getElementById('rtSearch');
            const cards = document.querySelectorAll('.rt-card[data-search]');
            const noMatch = document.getElementById('rtNoMatch');
            if (!search) return;

            search.addEventListener('input', function () {
                const term = this.value.trim().toLowerCase();
                let visible = 0;
                cards.forEach(card => {
                    const show = !term || card.dataset.search.includes(term);
                    card.style.display = show ? '' : 'none';
                    if (show) visible++;
                });
                noMatch.classList.toggle('d-none', !term || visible > 0);
            });
        })();
    </script>
@endsection

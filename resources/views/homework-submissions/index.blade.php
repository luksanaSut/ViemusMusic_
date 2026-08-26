@extends('layouts.app')
@section('title', 'ตรวจการบ้าน')

@section('content')
    <style>
        .review-intro { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem; }
        .review-intro .lead-text { color: var(--muted); font-size: .86rem; margin: 0; }

        .stat-strip {
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--card);
            display: flex;
            margin-bottom: 1.5rem;
            overflow-x: auto;
            box-shadow: 0 8px 28px rgba(28, 26, 23, .04);
        }
        .stat-cell { flex: 1; min-width: 100px; padding: .9rem 1rem; text-align: center; border-left: 1px solid var(--border); }
        .stat-cell:first-child { border-left: 0; }
        .stat-cell .value { font-family: 'Prompt', sans-serif; font-weight: 700; font-size: 1.4rem; line-height: 1.1; }
        .stat-cell .label { font-size: .74rem; color: var(--muted); margin-top: .2rem; }

        .review-tabs { display: flex; gap: 1.75rem; border-bottom: 1px solid var(--border); margin-bottom: 1.1rem; }
        .review-tabs button { background: none; border: none; padding: .7rem 0; font-size: .9rem; font-weight: 600; color: var(--muted); border-bottom: 2px solid transparent; margin-bottom: -1px; cursor: pointer; }
        .review-tabs button.active { color: var(--ink); border-bottom-color: var(--ink); }
        .review-tabs button .count { color: var(--muted); font-weight: 400; font-size: .82rem; }
        .review-tabs button.active .count { color: var(--ink); }
        .review-panel.d-none { display: none; }

        .review-toolbar { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: 1.1rem; }
        .review-toolbar .search-box { flex: 1; min-width: 220px; position: relative; }
        .review-toolbar .search-box input {
            width: 100%; border: 1px solid var(--border); border-radius: 9px;
            padding: .5rem .9rem .5rem 2.2rem; background: var(--surface); font-size: .86rem;
        }
        .review-toolbar .search-box i { position: absolute; left: .75rem; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: .9rem; }
        .review-toolbar .btn-toggle-all { flex-shrink: 0; white-space: nowrap; }

        .course-block { border: 1px solid var(--border); border-radius: 14px; background: var(--card); overflow: hidden; }
        .course-block + .course-block { margin-top: .6rem; }
        .course-block summary { cursor: pointer; list-style: none; padding: .8rem 1.1rem; display: flex; align-items: center; gap: .7rem; background: linear-gradient(135deg, var(--accent-soft), var(--card)); }
        .course-block summary::-webkit-details-marker { display: none; }
        .course-block summary .chevron { color: var(--muted); font-size: .8rem; transition: transform .15s; flex-shrink: 0; }
        .course-block[open] summary .chevron { transform: rotate(90deg); }
        .course-block[open] summary { border-bottom: 1px solid var(--border); }
        .course-block summary .name { font-weight: 700; font-family: 'Prompt', sans-serif; font-size: .92rem; flex-grow: 1; }
        .course-block .body { padding: 0 1.1rem; }

        .review-card { padding: 1.1rem 0; border-bottom: 1px solid var(--border); }
        .review-card:last-child { border-bottom: 0; }
        .review-card.st-pending { border-left: 3px solid var(--amber); padding-left: .8rem; }
        .review-card.st-needs_revision { border-left: 3px solid #b3392c; padding-left: .8rem; }
        .review-card.st-approved { border-left: 3px solid var(--success); padding-left: .8rem; }
        .review-card-title { font-family: 'Prompt', sans-serif; font-weight: 700; margin-bottom: .1rem; }
        .review-prompt { background: var(--surface); border-radius: 12px; padding: .8rem 1rem; margin: .8rem 0; white-space: pre-line; font-size: .88rem; }
        .review-files { display: flex; flex-wrap: wrap; gap: .4rem; }
        .review-empty { display: none; }

        @media (max-width: 767.98px) {
            .review-intro { align-items: flex-start; }
            .stat-strip { display: grid; grid-template-columns: repeat(2, 1fr); overflow: hidden; }
            .stat-cell { min-width: 0; padding: .7rem .5rem; border-left: 0; border-bottom: 1px solid var(--border); }
            .stat-cell:nth-child(2n) { border-left: 1px solid var(--border); }
            .stat-cell:nth-last-child(-n + 2) { border-bottom: 0; }
            .stat-cell .value { font-size: 1.2rem; }
            .review-tabs { gap: 1.2rem; overflow-x: auto; }
            .review-tabs button { white-space: nowrap; }
            .review-toolbar .search-box { flex-basis: 100%; }
            .course-block summary, .course-block .body { padding-left: .8rem; padding-right: .8rem; }
            .review-approve-row { flex-direction: column; align-items: stretch !important; }
            .review-approve-row .btn { width: 100%; }
        }
    </style>

    <div class="review-intro">
        <div>
            <div class="breadcrumb-sm">การดำเนินการ <i class="bi bi-chevron-right small"></i> ตรวจการบ้าน</div>
            <h1 class="page-title mb-1"><i class="bi bi-journal-check"></i> ตรวจการบ้าน</h1>
            <p class="lead-text">ตรวจงานที่นักเรียนส่งและให้ feedback ได้ในหน้าเดียว</p>
        </div>
        @if ($stats['pending'] > 0)
            <span class="badge rounded-pill" style="background:var(--amber-soft); color:var(--amber); padding:.55rem .75rem;">{{ $stats['pending'] }} รอตรวจ</span>
        @endif
    </div>

    <div class="stat-strip">
        <div class="stat-cell">
            <div class="value">{{ $stats['total'] }}</div>
            <div class="label">ทั้งหมด</div>
        </div>
        <div class="stat-cell">
            <div class="value" style="color:var(--amber);">{{ $stats['pending'] }}</div>
            <div class="label">รอตรวจ</div>
        </div>
        <div class="stat-cell">
            <div class="value" style="color:var(--success);">{{ $stats['approved'] }}</div>
            <div class="label">ผ่านแล้ว</div>
        </div>
        <div class="stat-cell">
            <div class="value" style="color:#b3392c;">{{ $stats['needs_revision'] }}</div>
            <div class="label">ต้องแก้ไข</div>
        </div>
    </div>

    @php
        $groupByCourse = fn($items) => $items->groupBy(function ($sub) {
            return ($sub->teachingReport->teachingLog->enrollment->course->id ?? 0) . '-' . ($sub->student_id ?? 0);
        });
        $pendingByCourse = $groupByCourse($pendingSubmissions);
        $historyByCourse = $groupByCourse($historySubmissions->getCollection());

        $searchText = fn($sub) => mb_strtolower(
            ($sub->student->full_name ?? '') . ' ' .
            ($sub->teachingReport->teachingLog->enrollment->course->name ?? '') . ' ' .
            ($sub->teachingReport->homework ?? '') . ' ' .
            ($sub->student_note ?? '') . ' ' .
            ($sub->feedback ?? '')
        );
    @endphp

    <div class="card">
        <div class="card-body">
            <div class="review-tabs" role="tablist">
                <button type="button" class="{{ request()->has('history_page') ? '' : 'active' }}" data-tab="pending" aria-selected="{{ request()->has('history_page') ? 'false' : 'true' }}">รอตรวจ <span class="count">{{ $pendingSubmissions->count() }}</span></button>
                <button type="button" class="{{ request()->has('history_page') ? 'active' : '' }}" data-tab="history" aria-selected="{{ request()->has('history_page') ? 'true' : 'false' }}">ประวัติการตรวจ <span class="count">{{ $historySubmissions->total() }}</span></button>
            </div>

            <div class="review-toolbar">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="reviewSearch" placeholder="ค้นหานักเรียน คอร์ส หรือเนื้อหางาน">
                </div>
                @if (($pendingByCourse->count() + $historyByCourse->count()) > 1)
                    <button type="button" id="reviewToggleAll" class="btn btn-sm btn-outline-secondary btn-toggle-all">
                        <i class="bi bi-arrows-collapse"></i> ย่อทั้งหมด
                    </button>
                @endif
            </div>

            {{-- ===== Tab: รอตรวจ ===== --}}
            <div class="review-panel {{ request()->has('history_page') ? 'd-none' : '' }}" data-panel="pending">
                @forelse ($pendingByCourse as $courseSubs)
                    @php
                        $log = $courseSubs->first()->teachingReport->teachingLog;
                        $course = $log->enrollment->course;
                    @endphp
                    <details class="course-block" open>
                        <summary>
                            <i class="bi bi-chevron-right chevron"></i>
                            <span class="name">{{ $course->name ?? 'ไม่ระบุคอร์ส' }} <span class="text-muted fw-normal">· {{ $log->student->full_name ?? '-' }}</span></span>
                            <span class="badge text-bg-light border">{{ $courseSubs->count() }} รายการ</span>
                        </summary>
                        <div class="body">
                            @foreach ($courseSubs as $sub)
                                <div class="review-card st-pending" data-search="{{ $searchText($sub) }}">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <div class="review-card-title">{{ $sub->student->full_name }} · ครั้งที่ {{ $sub->version }}</div>
                                            <div class="text-muted small">ส่งเมื่อ {{ $sub->created_at->format('d/m/Y H:i') }} โดย {{ $sub->submitted_by }}</div>
                                        </div>
                                        <span class="badge {{ $sub->statusBadgeClass() }}">{{ $sub->statusLabel() }}</span>
                                    </div>

                                    <div class="review-prompt"><strong>โจทย์:</strong> {{ $sub->teachingReport->homework }}</div>
                                    @if ($sub->student_note)
                                        <p class="small mb-2"><strong><i class="bi bi-card-text"></i> เนื้อหาคำตอบ:</strong> <span style="white-space:pre-line;">{{ $sub->student_note }}</span></p>
                                    @endif

                                    @if ($sub->files->count())
                                        <div class="review-files mb-2">
                                            @foreach ($sub->files as $f)
                                                <a href="{{ $f->url() }}" target="_blank" class="badge text-bg-light border text-decoration-none"><i class="bi bi-paperclip"></i> {{ $f->original_name }}</a>
                                            @endforeach
                                        </div>
                                    @endif

                                    <form action="{{ route('homework-submissions.review', $sub) }}" method="POST" class="mt-2">
                                        @csrf
                                        <textarea name="feedback" class="form-control form-control-sm mb-2" rows="2" placeholder="Feedback ให้นักเรียน (ถ้ามี)"></textarea>
                                        <div class="d-flex gap-2 review-approve-row">
                                            <button type="submit" name="status" value="approved" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> ผ่าน</button>
                                            <button type="submit" name="status" value="needs_revision" class="btn btn-sm btn-outline-danger"><i class="bi bi-arrow-repeat"></i> ให้แก้ไข</button>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </details>
                @empty
                    <p class="text-muted small text-center py-5 mb-0"><i class="bi bi-check-circle text-success"></i> ไม่มีงานรอตรวจ</p>
                @endforelse
                @if ($pendingByCourse->count())
                    <p class="review-empty text-muted small text-center py-4 mb-0" data-empty-for="pending">ไม่พบรายการที่ตรงกับคำค้นหา</p>
                @endif
            </div>

            {{-- ===== Tab: ประวัติการตรวจ ===== --}}
            <div class="review-panel {{ request()->has('history_page') ? '' : 'd-none' }}" data-panel="history">
                @forelse ($historyByCourse as $courseSubs)
                    @php
                        $log = $courseSubs->first()->teachingReport->teachingLog;
                        $course = $log->enrollment->course;
                    @endphp
                    <details class="course-block" open>
                        <summary>
                            <i class="bi bi-chevron-right chevron"></i>
                            <span class="name">{{ $course->name ?? 'ไม่ระบุคอร์ส' }} <span class="text-muted fw-normal">· {{ $log->student->full_name ?? '-' }}</span></span>
                            <span class="badge text-bg-light border">{{ $courseSubs->count() }} รายการ</span>
                        </summary>
                        <div class="body">
                            @foreach ($courseSubs as $sub)
                                <div class="review-card st-{{ $sub->status }}" data-search="{{ $searchText($sub) }}">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <div class="review-card-title">{{ $sub->student->full_name }} · ครั้งที่ {{ $sub->version }}</div>
                                            <div class="text-muted small">ส่งเมื่อ {{ $sub->created_at->format('d/m/Y H:i') }} โดย {{ $sub->submitted_by }}</div>
                                        </div>
                                        <span class="badge {{ $sub->statusBadgeClass() }}">{{ $sub->statusLabel() }}</span>
                                    </div>

                                    <div class="review-prompt"><strong>โจทย์:</strong> {{ $sub->teachingReport->homework }}</div>
                                    @if ($sub->student_note)
                                        <p class="small mb-2"><strong><i class="bi bi-card-text"></i> เนื้อหาคำตอบ:</strong> <span style="white-space:pre-line;">{{ $sub->student_note }}</span></p>
                                    @endif

                                    @if ($sub->files->count())
                                        <div class="review-files mb-2">
                                            @foreach ($sub->files as $f)
                                                <a href="{{ $f->url() }}" target="_blank" class="badge text-bg-light border text-decoration-none"><i class="bi bi-paperclip"></i> {{ $f->original_name }}</a>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="small text-muted"><i class="bi bi-check-circle"></i> ตรวจโดย {{ $sub->reviewed_by }} เมื่อ {{ $sub->reviewed_at?->format('d/m/Y H:i') }}</div>
                                    @if ($sub->feedback)
                                        <p class="small mt-1 mb-0"><strong>Feedback:</strong> {{ $sub->feedback }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </details>
                @empty
                    <p class="text-muted small text-center py-5 mb-0">ยังไม่มีประวัติการตรวจการบ้าน</p>
                @endforelse
                @if ($historyByCourse->count())
                    <p class="review-empty text-muted small text-center py-4 mb-0" data-empty-for="history">ไม่พบรายการที่ตรงกับคำค้นหา</p>
                @endif
                {{ $historySubmissions->links() }}
            </div>
        </div>
    </div>

    <script>
        (function () {
            const tabs = document.querySelectorAll('.review-tabs button');
            const panels = document.querySelectorAll('.review-panel');
            const search = document.getElementById('reviewSearch');
            const toggleAllBtn = document.getElementById('reviewToggleAll');
            const courseBlocks = document.querySelectorAll('.course-block');

            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    tabs.forEach(t => t.classList.remove('active'));
                    tabs.forEach(t => t.setAttribute('aria-selected', 'false'));
                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');
                    panels.forEach(p => p.classList.toggle('d-none', p.dataset.panel !== this.dataset.tab));
                });
            });

            function applyFilters() {
                const term = (search?.value || '').trim().toLowerCase();
                const visibleByPanel = { pending: 0, history: 0 };

                courseBlocks.forEach(block => {
                    const panelName = block.closest('.review-panel')?.dataset.panel;
                    let visibleInBlock = 0;

                    block.querySelectorAll('.review-card').forEach(card => {
                        const show = !term || card.dataset.search.includes(term);
                        card.style.display = show ? '' : 'none';
                        if (show) visibleInBlock++;
                    });

                    const blockVisible = visibleInBlock > 0;
                    block.style.display = blockVisible ? '' : 'none';
                    if (blockVisible && term) block.open = true;
                    if (panelName) visibleByPanel[panelName] = (visibleByPanel[panelName] || 0) + visibleInBlock;
                });

                document.querySelectorAll('.review-empty').forEach(msg => {
                    const panel = msg.dataset.emptyFor;
                    msg.style.display = (visibleByPanel[panel] || 0) === 0 ? '' : 'none';
                });
            }

            search?.addEventListener('input', applyFilters);

            toggleAllBtn?.addEventListener('click', function () {
                const anyClosed = Array.from(courseBlocks).some(b => !b.open);
                courseBlocks.forEach(b => b.open = anyClosed);
                this.innerHTML = anyClosed
                    ? '<i class="bi bi-arrows-collapse"></i> ย่อทั้งหมด'
                    : '<i class="bi bi-arrows-expand"></i> ขยายทั้งหมด';
            });
        })();
    </script>
@endsection

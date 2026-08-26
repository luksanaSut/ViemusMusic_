@extends('layouts.app')
@section('title', 'การบ้านของฉัน')

@section('content')
    <style>
        .homework-hero { display:flex; justify-content:space-between; align-items:flex-end; gap:1rem; margin-bottom:1.25rem; }
        .homework-tabs { display:flex; gap:1.5rem; border-bottom:1px solid var(--border); margin-bottom:1rem; }
        .homework-tab { border:0; border-bottom:2px solid transparent; background:transparent; color:var(--muted); font-weight:600; padding:.7rem 0; margin-bottom:-1px; }
        .homework-tab.active { color:var(--ink); border-bottom-color:var(--ink); }
        .homework-panel.d-none { display:none; }

        .homework-toolbar { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:1.1rem; }
        .homework-toolbar .search-box { flex:1; min-width:220px; position:relative; }
        .homework-toolbar .search-box input {
            width:100%; border:1px solid var(--border); border-radius:9px;
            padding:.5rem .9rem .5rem 2.2rem; background:var(--surface); font-size:.86rem;
        }
        .homework-toolbar .search-box i { position:absolute; left:.75rem; top:50%; transform:translateY(-50%); color:var(--muted); font-size:.9rem; }
        .homework-toolbar select {
            border:1px solid var(--border); border-radius:9px; padding:.5rem .8rem;
            background:var(--card); font-size:.86rem; color:var(--muted); min-width:170px;
        }
        .homework-toolbar .btn-toggle-all { flex-shrink:0; white-space:nowrap; }

        .course-homework { border:1px solid var(--border); border-radius:16px; overflow:hidden; margin-bottom:1rem; background:var(--card); box-shadow:0 8px 24px rgba(28,26,23,.04); }
        .course-homework-header { display:flex; align-items:center; gap:.75rem; padding:.9rem 1.1rem; background:linear-gradient(135deg,var(--accent-soft),var(--card)); border-bottom:1px solid var(--border); cursor:pointer; list-style:none; }
        .course-homework-header::-webkit-details-marker { display:none; }
        .course-homework-header .chevron { color:var(--muted); font-size:.85rem; transition:transform .15s; flex-shrink:0; }
        .course-homework[open] .course-homework-header .chevron { transform:rotate(90deg); }
        .course-homework-icon { width:38px; height:38px; border-radius:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; background:var(--accent); color:#fff; }
        .course-homework-name { font-family:'Prompt',sans-serif; font-weight:700; }
        .course-homework-list { padding:0 1.1rem; }
        .assignment-card { padding:1.1rem 0 1.1rem .9rem; border-bottom:1px solid var(--border); }
        .assignment-card:last-child { border-bottom:0; }
        .assignment-card.needs-revision { border-left:4px solid #b3392c; }
        .assignment-card.pending { border-left:4px solid var(--amber); }
        .assignment-title { font-family:'Prompt',sans-serif; font-weight:700; margin-bottom:.15rem; }
        .assignment-prompt { background:var(--surface); border-radius:12px; padding:.8rem 1rem; margin:.9rem 0; white-space:pre-line; }
        .submission-files { display:flex; flex-wrap:wrap; gap:.4rem; }
        .homework-filter-empty { display:none; }

        @media (max-width:767.98px) {
            .homework-hero { align-items:flex-start; }
            .homework-tabs { overflow-x:auto; }
            .homework-tab { white-space:nowrap; }
            .homework-toolbar .search-box { flex-basis:100%; }
            .homework-toolbar select { flex:1; min-width:0; }
            .course-homework-header, .course-homework-list { padding-left:.8rem; padding-right:.8rem; }
            .assignment-card { padding:1rem 0 1rem .75rem; }
            .submit-homework-row { flex-direction:column; align-items:stretch !important; }
            .submit-homework-row .btn { width:100%; }
        }
    </style>

    <div class="homework-hero">
        <div>
            <div class="breadcrumb-sm">การเรียนของฉัน <i class="bi bi-chevron-right small"></i> การบ้าน</div>
            <h1 class="page-title mb-1"><i class="bi bi-journal-check"></i> การบ้านของฉัน</h1>
            <p class="text-muted small mb-0">ดูโจทย์ ส่งงาน และติดตามผลตรวจได้ในหน้าเดียว</p>
        </div>
        @if ($actionableAssignments->count())
            <span class="badge rounded-pill text-bg-warning">{{ $actionableAssignments->count() }} งานที่ต้องทำ</span>
        @endif
    </div>

    @php
        $todoByCourse = $actionableAssignments
            ->sortBy(fn($report) => $report->latestHomeworkSubmission()?->status === 'needs_revision' ? 0 : 1)
            ->groupBy(function ($report) {
                $log = $report->teachingLog;
                return ($log->enrollment->course->id ?? 0) . '-' . ($log->student_id ?? 0);
            });
        $historyByCourse = $submissions->getCollection()->groupBy(function ($submission) {
            $log = $submission->teachingReport->teachingLog;
            return ($log->enrollment->course->id ?? 0) . '-' . ($submission->student_id ?? 0);
        });
        $totalGroups = $todoByCourse->count() + $historyByCourse->count();
    @endphp

    @if ($totalGroups)
        <div class="homework-toolbar">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="hwSearch" placeholder="ค้นหาคอร์ส โจทย์ หรือคำตอบ">
            </div>
            @if ($students->count() > 1)
                <select id="hwStudentFilter">
                    <option value="">นักเรียนทุกคน</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}">{{ $student->full_name }}</option>
                    @endforeach
                </select>
            @endif
            @if ($totalGroups > 1)
                <button type="button" id="hwToggleAll" class="btn btn-sm btn-outline-secondary btn-toggle-all">
                    <i class="bi bi-arrows-collapse"></i> ย่อทั้งหมด
                </button>
            @endif
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="homework-tabs" role="tablist">
                <button type="button" class="homework-tab {{ request()->has('history_page') ? '' : 'active' }}" data-tab="todo" aria-selected="{{ request()->has('history_page') ? 'false' : 'true' }}">งานที่ต้องทำ <span class="text-muted">{{ $actionableAssignments->count() }}</span></button>
                <button type="button" class="homework-tab {{ request()->has('history_page') ? 'active' : '' }}" data-tab="history" aria-selected="{{ request()->has('history_page') ? 'true' : 'false' }}">ประวัติการส่ง <span class="text-muted">{{ $submissions->total() }}</span></button>
            </div>

            <div class="homework-panel {{ request()->has('history_page') ? 'd-none' : '' }}" data-panel="todo">
                @forelse ($todoByCourse as $courseAssignments)
                    @php
                        $courseLog = $courseAssignments->first()->teachingLog;
                        $courseSearch = mb_strtolower(($courseLog->enrollment->course->name ?? '') . ' ' . ($courseLog->enrollment->course->course_code ?? '') . ' ' . ($courseLog->student->full_name ?? ''));
                    @endphp
                    <details class="course-homework" open data-student="{{ $courseLog->student_id }}" data-search="{{ $courseSearch }}">
                        <summary class="course-homework-header">
                            <i class="bi bi-chevron-right chevron"></i>
                            <div class="course-homework-icon"><i class="bi bi-music-note-beamed"></i></div>
                            <div class="flex-grow-1">
                                <div class="course-homework-name">{{ $courseLog->enrollment->course->name ?? 'ไม่ระบุคอร์ส' }}</div>
                                <div class="text-muted small">{{ $courseLog->student->full_name ?? '-' }}</div>
                            </div>
                            <span class="badge text-bg-light border">{{ $courseAssignments->count() }} งาน</span>
                        </summary>
                        <div class="course-homework-list">
                        @foreach ($courseAssignments as $report)
                            @php
                                $log = $report->teachingLog;
                                $latestSub = $report->latestHomeworkSubmission();
                                $needsRevision = $latestSub?->status === 'needs_revision';
                                $cardSearch = mb_strtolower(
                                    $log->classSchedule->schedule_date->format('d/m/Y') . ' ' .
                                    ($log->teacher->full_name ?? '') . ' ' .
                                    ($report->homework ?? '') . ' ' .
                                    ($needsRevision ? ($latestSub->feedback ?? '') : '')
                                );
                            @endphp
                            <article class="assignment-card {{ $needsRevision ? 'needs-revision' : 'pending' }}" data-search="{{ $cardSearch }}">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="assignment-title">การบ้านจากคาบวันที่ {{ $log->classSchedule->schedule_date->format('d/m/Y') }}</div>
                                <div class="text-muted small">มอบหมายโดย {{ $log->teacher->full_name ?? 'อาจารย์ผู้สอน' }}</div>
                            </div>
                            <span class="badge {{ $needsRevision ? 'text-bg-danger' : 'text-bg-warning' }}">{{ $needsRevision ? 'ต้องแก้ไข' : 'ยังไม่ได้ส่ง' }}</span>
                        </div>

                        <div class="assignment-prompt"><strong>โจทย์:</strong> {{ $report->homework }}</div>
                        @if ($needsRevision && $latestSub->feedback)
                            <div class="alert alert-warning py-2 small"><strong><i class="bi bi-chat-left-text"></i> Feedback จากอาจารย์:</strong> {{ $latestSub->feedback }}</div>
                        @endif

                        <form action="{{ route('homework-submissions.store', $report) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">เนื้อหาคำตอบ</label>
                                <textarea name="student_note" class="form-control form-control-sm" rows="4"
                                    placeholder="พิมพ์คำตอบ รายละเอียดการฝึก หรือข้อความถึงอาจารย์ได้ที่นี่">{{ old('student_note') }}</textarea>
                            </div>
                            <div class="submit-homework-row d-flex gap-2 align-items-end">
                                <div class="flex-grow-1">
                                    <label class="form-label small fw-semibold">แนบไฟล์งาน <span class="text-muted fw-normal">(ถ้ามี)</span></label>
                                    <input type="file" name="files[]" class="form-control form-control-sm" multiple
                                        accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx,.mp3,.wav,.m4a,.aac,.ogg,.flac,.mp4,.mov,audio/*,application/pdf">
                                    <div class="form-text">ส่งข้อความอย่างเดียวได้ · รองรับ PDF รูปภาพ เอกสาร ไฟล์เสียง และวิดีโอ สูงสุด 10 ไฟล์</div>
                                </div>
                                <button class="btn btn-accent flex-shrink-0"><i class="bi bi-send"></i> {{ $needsRevision ? 'ส่งงานแก้ไข' : 'ส่งการบ้าน' }}</button>
                            </div>
                        </form>
                    </article>
                        @endforeach
                        </div>
                    </details>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle-fill fs-2 text-success"></i>
                        <p class="fw-semibold mt-2 mb-1">ไม่มีการบ้านค้าง</p>
                        <p class="text-muted small mb-0">ส่งงานครบแล้ว เก่งมาก!</p>
                    </div>
                @endforelse
                @if ($todoByCourse->count())
                    <p class="homework-filter-empty text-muted small text-center py-4 mb-0" data-empty-for="todo">ไม่พบรายการที่ตรงกับคำค้นหา</p>
                @endif
            </div>

            <div class="homework-panel {{ request()->has('history_page') ? '' : 'd-none' }}" data-panel="history">
                @forelse($historyByCourse as $courseSubmissions)
                    @php
                        $courseLog = $courseSubmissions->first()->teachingReport->teachingLog;
                        $courseSearch = mb_strtolower(($courseLog->enrollment->course->name ?? '') . ' ' . ($courseLog->enrollment->course->course_code ?? '') . ' ' . ($courseLog->student->full_name ?? ''));
                    @endphp
                    <details class="course-homework" open data-student="{{ $courseLog->student_id }}" data-search="{{ $courseSearch }}">
                        <summary class="course-homework-header">
                            <i class="bi bi-chevron-right chevron"></i>
                            <div class="course-homework-icon"><i class="bi bi-music-note-beamed"></i></div>
                            <div class="flex-grow-1">
                                <div class="course-homework-name">{{ $courseLog->enrollment->course->name ?? 'ไม่ระบุคอร์ส' }}</div>
                                <div class="text-muted small">{{ $courseLog->student->full_name ?? '-' }}</div>
                            </div>
                            <span class="badge text-bg-light border">{{ $courseSubmissions->count() }} รายการส่ง</span>
                        </summary>
                        <div class="course-homework-list">
                        @foreach ($courseSubmissions as $sub)
                            @php
                                $subSearch = mb_strtolower(
                                    $sub->teachingReport->teachingLog->classSchedule->schedule_date->format('d/m/Y') . ' ' .
                                    ($sub->teachingReport->homework ?? '') . ' ' .
                                    ($sub->student_note ?? '') . ' ' .
                                    ($sub->feedback ?? '') . ' ' .
                                    $sub->statusLabel()
                                );
                            @endphp
                            <article class="assignment-card" data-search="{{ $subSearch }}">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="assignment-title">คาบวันที่ {{ $sub->teachingReport->teachingLog->classSchedule->schedule_date->format('d/m/Y') }} · ส่งครั้งที่ {{ $sub->version }}</div>
                                <div class="text-muted small">ส่งเมื่อ {{ $sub->created_at->format('d/m/Y H:i') }} โดย {{ $sub->submitted_by }}</div>
                            </div>
                            <span class="badge {{ $sub->statusBadgeClass() }}">{{ $sub->statusLabel() }}</span>
                        </div>
                        <div class="assignment-prompt small"><strong>โจทย์:</strong> {{ $sub->teachingReport->homework }}</div>
                        @if ($sub->student_note)
                            <div class="small mb-2"><strong><i class="bi bi-card-text"></i> เนื้อหาคำตอบ:</strong><div class="mt-1" style="white-space:pre-line;">{{ $sub->student_note }}</div></div>
                        @endif
                        <div class="submission-files">
                            @foreach ($sub->files as $file)
                                <a href="{{ $file->url() }}" target="_blank" class="badge text-bg-light border text-decoration-none"><i class="bi bi-paperclip"></i> {{ $file->original_name }}</a>
                            @endforeach
                        </div>
                        @if ($sub->feedback)
                            <div class="small mt-2"><strong>Feedback จากอาจารย์:</strong> {{ $sub->feedback }}</div>
                        @endif
                    </article>
                        @endforeach
                        </div>
                    </details>
                @empty
                    <p class="text-muted text-center small py-5 mb-0">ยังไม่มีประวัติการส่งการบ้าน</p>
                @endforelse
                @if ($historyByCourse->count())
                    <p class="homework-filter-empty text-muted small text-center py-4 mb-0" data-empty-for="history">ไม่พบรายการที่ตรงกับคำค้นหา</p>
                @endif
                {{ $submissions->links() }}
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.homework-tab').forEach(tab => {
            tab.addEventListener('click', function () {
                document.querySelectorAll('.homework-tab').forEach(item => {
                    item.classList.remove('active');
                    item.setAttribute('aria-selected', 'false');
                });
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                document.querySelectorAll('.homework-panel').forEach(panel => {
                    panel.classList.toggle('d-none', panel.dataset.panel !== this.dataset.tab);
                });
            });
        });

        (function () {
            const search = document.getElementById('hwSearch');
            const studentSelect = document.getElementById('hwStudentFilter');
            const toggleAllBtn = document.getElementById('hwToggleAll');
            const courseBlocks = document.querySelectorAll('.course-homework');

            function applyFilters() {
                const term = (search?.value || '').trim().toLowerCase();
                const studentVal = studentSelect?.value || '';
                const visibleByPanel = { todo: 0, history: 0 };

                courseBlocks.forEach(block => {
                    const panelName = block.closest('.homework-panel')?.dataset.panel;
                    const courseMatches = !term || block.dataset.search.includes(term);
                    const studentMatches = !studentVal || block.dataset.student === studentVal;
                    let visibleInBlock = 0;

                    block.querySelectorAll('.assignment-card').forEach(card => {
                        const cardMatches = courseMatches || card.dataset.search.includes(term);
                        const show = studentMatches && cardMatches;
                        card.style.display = show ? '' : 'none';
                        if (show) visibleInBlock++;
                    });

                    const blockVisible = visibleInBlock > 0;
                    block.style.display = blockVisible ? '' : 'none';
                    if (blockVisible && (term || studentVal)) block.open = true;
                    if (panelName) visibleByPanel[panelName] = (visibleByPanel[panelName] || 0) + visibleInBlock;
                });

                document.querySelectorAll('.homework-filter-empty').forEach(msg => {
                    const panel = msg.dataset.emptyFor;
                    msg.style.display = (visibleByPanel[panel] || 0) === 0 ? '' : 'none';
                });
            }

            search?.addEventListener('input', applyFilters);
            studentSelect?.addEventListener('change', applyFilters);

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

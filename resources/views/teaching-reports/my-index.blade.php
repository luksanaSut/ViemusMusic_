@extends('layouts.app')
@section('title', 'ผลการสอนย้อนหลัง')

@section('content')
    <style>
        .report-hero { display: flex; justify-content: space-between; align-items: flex-end; gap: 1rem; margin-bottom: 1.1rem; }

        .report-toolbar { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: 1.1rem; }
        .report-toolbar .search-box { flex: 1; min-width: 220px; position: relative; }
        .report-toolbar .search-box input {
            width: 100%; border: 1px solid var(--border); border-radius: 9px;
            padding: .5rem .9rem .5rem 2.2rem; background: var(--surface); font-size: .86rem;
        }
        .report-toolbar .search-box i { position: absolute; left: .75rem; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: .9rem; }
        .report-toolbar select {
            border: 1px solid var(--border); border-radius: 9px; padding: .5rem .8rem;
            background: var(--card); font-size: .86rem; color: var(--muted); min-width: 170px;
        }
        .report-toolbar .btn-toggle-all { flex-shrink: 0; white-space: nowrap; }

        .report-course {
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--card);
            overflow: hidden;
            margin-bottom: .7rem;
            box-shadow: 0 8px 26px rgba(28, 26, 23, .04);
        }

        .report-course-header {
            display: flex;
            align-items: center;
            gap: .8rem;
            padding: 1rem 1.2rem;
            background: linear-gradient(135deg, var(--accent-soft), var(--card));
            cursor: pointer;
            list-style: none;
        }
        .report-course-header::-webkit-details-marker { display: none; }

        .report-course-header .chevron { color: var(--muted); font-size: .85rem; transition: transform .15s; flex-shrink: 0; }
        .report-course[open] .report-course-header .chevron { transform: rotate(90deg); }
        .report-course[open] .report-course-header { border-bottom: 1px solid var(--border); }

        .course-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--accent);
            color: #fff;
            flex-shrink: 0;
        }

        .course-name { font-family: 'Prompt', sans-serif; font-weight: 700; }
        .lesson-list { padding: 0 1.2rem; }
        .lesson-item { padding: 1rem 0; border-bottom: 1px solid var(--border); }
        .lesson-item:last-child { border-bottom: 0; }

        .lesson-date-chip {
            width: 68px;
            flex-shrink: 0;
            text-align: center;
            background: var(--surface);
            border-radius: 10px;
            padding: .55rem .3rem;
        }
        .lesson-date-chip .d { font-family: 'Prompt', sans-serif; font-weight: 700; font-size: .98rem; line-height: 1.1; }
        .lesson-date-chip .y { font-size: .68rem; color: var(--muted); }
        .lesson-date-chip .badge { font-size: .64rem; margin-top: .35rem; }

        .lesson-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .65rem;
            margin-top: .75rem;
        }

        .lesson-detail {
            border-radius: 10px;
            background: var(--surface);
            padding: .7rem .8rem;
            font-size: .84rem;
        }

        .lesson-detail.detail-progress { background: var(--success-soft, #e7f2ec); }
        .lesson-detail.homework { background: var(--amber-soft, #f3ece2); }

        #reportFilterEmpty { display: none; }

        @media (max-width: 767.98px) {
            .report-hero { align-items: flex-start; }
            .report-toolbar .search-box { flex-basis: 100%; }
            .report-toolbar select { flex: 1; min-width: 0; }
            .report-course-header, .lesson-list { padding-left: .9rem; padding-right: .9rem; }
            .lesson-detail-grid { grid-template-columns: 1fr; }
            .lesson-item > .d-flex { flex-direction: column; gap: .35rem !important; }
            .lesson-date-chip { width: auto; display: flex; align-items: center; gap: .5rem; text-align: left; padding: .4rem .6rem; }
            .lesson-date-chip .badge { margin-top: 0; }
        }
    </style>

    <div class="report-hero">
        <div>
            <div class="breadcrumb-sm">การเรียนของฉัน <i class="bi bi-chevron-right small"></i> ผลการสอน</div>
            <h1 class="page-title mb-1"><i class="bi bi-journal-text"></i> ผลการสอนย้อนหลัง</h1>
            <p class="text-muted small mb-0">ติดตามเนื้อหาที่เรียนและความก้าวหน้า แยกตามแต่ละคอร์ส</p>
        </div>
        @if ($reports->total())
            <span class="badge rounded-pill text-bg-light border">{{ $reports->total() }} รายการ</span>
        @endif
    </div>

    @php
        $reportsByCourse = $reports->getCollection()->groupBy(function ($report) {
            $log = $report->teachingLog;
            return ($log->enrollment->course->id ?? 0) . '-' . ($log->student_id ?? 0);
        });
    @endphp

    @if ($reportsByCourse->count())
        <div class="report-toolbar">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="reportSearch" placeholder="ค้นหาคอร์ส เนื้อหา หรือการบ้าน">
            </div>
            @if ($students->count() > 1)
                <select id="reportStudentFilter">
                    <option value="">นักเรียนทุกคน</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}">{{ $student->full_name }}</option>
                    @endforeach
                </select>
            @endif
            @if ($reportsByCourse->count() > 1)
                <button type="button" id="toggleAllBtn" class="btn btn-sm btn-outline-secondary btn-toggle-all">
                    <i class="bi bi-arrows-collapse"></i> ย่อทั้งหมด
                </button>
            @endif
        </div>
    @endif

    @forelse($reportsByCourse as $courseReports)
        @php
            $firstLog = $courseReports->first()->teachingLog;
            $course = $firstLog->enrollment->course;
            $courseSearch = mb_strtolower(($course->name ?? '') . ' ' . ($course->course_code ?? '') . ' ' . ($firstLog->student->full_name ?? ''));
        @endphp
        <details class="report-course" open data-student="{{ $firstLog->student_id }}" data-search="{{ $courseSearch }}">
            <summary class="report-course-header">
                <i class="bi bi-chevron-right chevron"></i>
                <div class="course-icon"><i class="bi bi-music-note-beamed"></i></div>
                <div class="flex-grow-1 min-width-0">
                    <div class="course-name">{{ $course->name ?? 'ไม่ระบุคอร์ส' }}</div>
                    <div class="text-muted small">{{ $firstLog->student->full_name ?? '-' }}
                        @if ($course?->course_code) · {{ $course->course_code }} @endif
                    </div>
                </div>
                <span class="badge text-bg-light border">{{ $courseReports->count() }} คาบ</span>
            </summary>

            <div class="lesson-list">
                @foreach ($courseReports as $report)
                    @php
                        $log = $report->teachingLog;
                        $lessonSearch = mb_strtolower(
                            $log->classSchedule->schedule_date->format('d/m/Y') . ' ' .
                            $log->attendanceStatusLabel() . ' ' .
                            ($report->content_taught ?? '') . ' ' .
                            ($report->progress_notes ?? '') . ' ' .
                            ($report->homework ?? '') . ' ' .
                            ($report->notes ?? '')
                        );
                    @endphp
                    <article class="lesson-item" data-search="{{ $lessonSearch }}">
                        <div class="d-flex align-items-start gap-3">
                            <div class="lesson-date-chip">
                                <div class="d">{{ $log->classSchedule->schedule_date->format('d/m') }}</div>
                                <div class="y">{{ $log->classSchedule->schedule_date->format('Y') }}</div>
                                <span class="badge {{ $log->attendanceStatusBadgeClass() }} d-block">{{ $log->attendanceStatusLabel() }}</span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="lesson-detail-grid mt-0">
                                    @if ($report->content_taught)
                                        <div class="lesson-detail"><strong><i class="bi bi-music-note-list"></i> เนื้อหาที่สอน</strong><div class="mt-1">{{ $report->content_taught }}</div></div>
                                    @endif
                                    @if ($report->progress_notes)
                                        <div class="lesson-detail detail-progress"><strong><i class="bi bi-graph-up-arrow"></i> ความก้าวหน้า</strong><div class="mt-1">{{ $report->progress_notes }}</div></div>
                                    @endif
                                    @if ($report->homework)
                                        <div class="lesson-detail homework"><strong><i class="bi bi-pencil-square"></i> การบ้าน</strong><div class="mt-1">{{ $report->homework }}</div></div>
                                    @endif
                                    @if ($report->notes)
                                        <div class="lesson-detail"><strong><i class="bi bi-chat-left-text"></i> หมายเหตุ</strong><div class="mt-1">{{ $report->notes }}</div></div>
                                    @endif
                                </div>

                                @if ($report->attachments->count() || $log->evidences->count())
                                    <div class="d-flex flex-wrap gap-1 mt-2">
                                        @foreach ($report->attachments as $attachment)
                                            <a href="{{ $attachment->url() }}" target="_blank" class="badge text-bg-light border text-decoration-none"><i class="bi bi-paperclip"></i> {{ $attachment->original_name }}</a>
                                        @endforeach
                                        @foreach ($log->evidences as $evidence)
                                            <a href="{{ route('teaching-evidences.download', $evidence) }}" class="badge text-bg-light border text-decoration-none"><i class="bi {{ $evidence->fileTypeIcon() }}"></i> {{ $evidence->original_name }}</a>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($report->homework)
                                    <a href="{{ route('homework-submissions.my-index') }}" class="btn btn-sm btn-outline-secondary mt-2"><i class="bi bi-journal-check"></i> ไปที่การบ้าน</a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </details>
    @empty
        <div class="card"><div class="card-body text-center py-5"><i class="bi bi-journal-x fs-2 text-muted"></i><p class="text-muted mt-2 mb-0">ยังไม่มีผลการสอนบันทึกไว้</p></div></div>
    @endforelse

    @if ($reportsByCourse->count())
        <p id="reportFilterEmpty" class="text-muted small text-center py-4 mb-0">ไม่พบรายการที่ตรงกับคำค้นหา</p>
    @endif

    {{ $reports->links() }}

    <script>
        (function () {
            const search = document.getElementById('reportSearch');
            const studentSelect = document.getElementById('reportStudentFilter');
            const toggleAllBtn = document.getElementById('toggleAllBtn');
            const courseBlocks = document.querySelectorAll('.report-course');
            const emptyMsg = document.getElementById('reportFilterEmpty');

            function applyFilters() {
                const term = (search?.value || '').trim().toLowerCase();
                const studentVal = studentSelect?.value || '';
                let totalVisible = 0;

                courseBlocks.forEach(block => {
                    const courseMatches = !term || block.dataset.search.includes(term);
                    const studentMatches = !studentVal || block.dataset.student === studentVal;
                    let visibleInBlock = 0;

                    block.querySelectorAll('.lesson-item').forEach(item => {
                        const itemMatches = courseMatches || item.dataset.search.includes(term);
                        const show = studentMatches && itemMatches;
                        item.style.display = show ? '' : 'none';
                        if (show) visibleInBlock++;
                    });

                    const blockVisible = visibleInBlock > 0;
                    block.style.display = blockVisible ? '' : 'none';
                    if (blockVisible && (term || studentVal)) block.open = true;
                    totalVisible += visibleInBlock;
                });

                if (emptyMsg) emptyMsg.style.display = totalVisible === 0 ? '' : 'none';
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

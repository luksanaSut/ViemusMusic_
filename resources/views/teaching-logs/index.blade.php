@extends('layouts.app')
@section('title', 'เช็คชื่อเข้าเรียน')

@section('content')
    <style>
        /* ===== แถบควบคุมด้านบน (วันที่ + ช่วงเวลา) ===== */
        .tl-toolbar-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
            margin-bottom: 1.5rem;
            padding: .85rem 1rem;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--card);
            box-shadow: 0 8px 24px rgba(28, 26, 23, .04);
        }

        .date-nav {
            display: flex;
            align-items: center;
            gap: .75rem;
            flex: 1;
        }

        .date-nav .label {
            font-family: 'Prompt', sans-serif;
            font-weight: 600;
            font-size: 1.05rem;
            min-width: 12rem;
            text-align: center;
        }

        .date-nav .sub {
            display: block;
            font-family: 'Sarabun', sans-serif;
            font-weight: 400;
            font-size: .74rem;
            color: var(--muted);
        }

        .nav-arrow {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink);
            border: 1px solid var(--border);
            background: var(--surface);
            text-decoration: none;
            flex-shrink: 0;
        }

        .nav-arrow:hover {
            background: var(--accent-soft);
            border-color: #c7cfda;
            color: var(--accent-dark);
        }

        .today-shortcut { white-space:nowrap; border-radius:10px; }

        .month-jump {
            display:flex;
            align-items:center;
            gap:.35rem;
            padding:.25rem;
            border:1px solid var(--border);
            border-radius:11px;
            background:var(--surface);
        }

        .month-jump i { color:var(--muted); margin-left:.45rem; }
        .month-jump select { border:0; background:transparent; color:var(--ink); font-family:'Prompt',sans-serif; font-weight:600; font-size:.82rem; padding:.35rem 1.6rem .35rem .35rem; outline:none; }
        .month-jump select + select { border-left:1px solid var(--border); border-radius:0; }

        .tl-right-controls {
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .range-toggle {
            background: var(--surface);
            border-radius: 10px;
            padding: .2rem;
            display: inline-flex;
            gap: .1rem;
        }

        .range-toggle a {
            padding: .35rem .85rem;
            border-radius: 8px;
            font-size: .82rem;
            font-weight: 600;
            color: var(--muted);
            text-decoration: none;
        }

        .range-toggle a.active {
            background: var(--card);
            color: var(--ink);
        }

        /* ===== แถบสรุปสถิติแบบมินิมอล ===== */
        .stat-strip {
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--card);
            display: flex;
            margin-bottom: 1.5rem;
            overflow-x: auto;
            box-shadow: 0 8px 28px rgba(28, 26, 23, .04);
        }

        .stat-cell {
            flex: 1;
            min-width: 100px;
            padding: .9rem 1rem;
            text-align: center;
            border-left: 1px solid var(--border);
        }

        .stat-cell:first-child {
            border-left: 0;
        }

        .stat-cell .value {
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: 1.4rem;
            line-height: 1.1;
        }

        .stat-cell .label {
            font-size: .74rem;
            color: var(--muted);
            margin-top: .2rem;
        }

        /* ===== Tabs (เช็คชื่อ / ประวัติการเช็คชื่อ) ===== */
        .tl-tabs {
            display: flex;
            gap: 1.75rem;
            border-bottom: 1px solid var(--border);
            margin-bottom: 1.1rem;
        }

        .tl-tabs button {
            background: none;
            border: none;
            padding: .7rem 0;
            font-size: .9rem;
            font-weight: 600;
            color: var(--muted);
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            cursor: pointer;
        }

        .tl-tabs button.active {
            color: var(--ink);
            border-bottom-color: var(--ink);
        }

        .tl-tabs button .count {
            color: var(--muted);
            font-weight: 400;
            font-size: .82rem;
        }

        .tl-tabs button.active .count {
            color: var(--ink);
        }

        /* ===== Toolbar ===== */
        .work-toolbar {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            margin-bottom: 1.1rem;
        }

        .work-toolbar .search-box {
            flex: 1;
            min-width: 200px;
            position: relative;
        }

        .work-toolbar .search-box input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: .45rem .8rem .45rem 2.1rem;
            background: var(--surface);
            font-size: .84rem;
        }

        .work-toolbar .search-box i {
            position: absolute;
            left: .7rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: .85rem;
        }

        .work-toolbar select {
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: .45rem .7rem;
            background: var(--card);
            font-size: .84rem;
            color: var(--muted);
            min-width: 130px;
        }

        /* ===== แถวคาบเรียน — ลิสต์เรียบ ไม่ใส่กรอบซ้อนกรอบ ===== */
        .work-row {
            padding: .7rem 0;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            border-bottom: 1px solid var(--border);
        }

        .course-block .body .work-row:last-child {
            border-bottom: 0;
        }

        .work-row .time-chip {
            width: 60px;
            flex-shrink: 0;
            font-family: 'Prompt', sans-serif;
        }

        .work-row .time-chip .t {
            font-weight: 600;
            font-size: .92rem;
            line-height: 1.15;
            color: var(--ink);
        }

        .work-row .time-chip .d {
            font-size: .68rem;
            color: var(--muted);
        }

        .work-row .avatar-md {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--accent-soft);
            color: var(--accent-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: .82rem;
            flex-shrink: 0;
        }

        .work-row .name-line {
            font-weight: 600;
            font-size: .88rem;
        }

        .work-row .name-line .code {
            font-weight: 400;
            color: var(--muted);
        }

        .work-row .meta-line {
            font-size: .74rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: .35rem;
            flex-wrap: wrap;
            margin-top: .1rem;
        }

        .meta-tag {
            display: inline-flex;
            align-items: center;
            gap: .25rem;
        }

        .meta-tag i {
            font-size: .82rem;
        }

        .status-dot {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .78rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-dot::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
            flex-shrink: 0;
        }

        .status-dot.st-present,
        .status-dot.st-checked {
            color: var(--success);
        }

        .status-dot.st-late {
            color: var(--amber);
        }

        .status-dot.st-absent {
            color: #b3392c;
        }

        .status-dot.st-leave {
            color: var(--accent);
        }

        .status-dot.st-pending {
            color: var(--muted);
        }

        /* ===== แบ่งตามคอร์สเรียน (accordion) ===== */
        .course-block {
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--card);
            overflow: hidden;
            transition: box-shadow .18s, border-color .18s;
        }

        .course-block:hover { border-color: #d5d0c8; box-shadow: 0 8px 24px rgba(28, 26, 23, .05); }

        .course-block + .course-block {
            margin-top: .6rem;
        }

        .course-block summary {
            cursor: pointer;
            list-style: none;
            padding: .8rem 1.1rem;
            display: flex;
            align-items: center;
            gap: .7rem;
            border-left: 3px solid transparent;
        }

        .course-block summary::-webkit-details-marker {
            display: none;
        }

        .course-block summary::before {
            content: "\F282";
            font-family: bootstrap-icons;
            color: var(--muted);
            font-size: .8rem;
            transition: transform .15s;
            flex-shrink: 0;
        }

        .course-block[open] summary::before {
            transform: rotate(90deg);
        }

        .course-block summary .name {
            font-weight: 600;
            font-family: 'Prompt', sans-serif;
            font-size: .92rem;
            flex-grow: 1;
        }

        .course-block.has-pending summary {
            border-left-color: var(--amber);
        }

        .course-block .body {
            padding: 0 1.1rem;
            border-top: 1px solid var(--border);
        }

        .course-count-badge {
            font-size: .76rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .course-count-badge.pending {
            color: var(--amber);
            background: var(--amber-soft, #f3ece2);
            border-radius: 999px;
            padding: .25rem .55rem;
        }

        .course-count-badge.done {
            color: var(--muted);
        }

        .attendance-intro {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .attendance-intro .page-title { margin-bottom: .2rem; }

        .attendance-intro .lead-text {
            color: var(--muted);
            font-size: .86rem;
            margin: 0;
        }

        @media (max-width: 767.98px) {
            .attendance-intro { align-items: flex-start; }
            .tl-toolbar-row { align-items: stretch; margin-bottom: 1rem; }
            .date-nav { justify-content: space-between; width: 100%; flex-wrap:wrap; }
            .date-nav .label { min-width: 0; flex: 1; }
            .month-jump { order:4; width:100%; justify-content:center; }
            .today-shortcut { order:5; width:100%; }
            .tl-right-controls, .range-toggle { width: 100%; }
            .range-toggle a { flex: 1; text-align: center; }
            .stat-strip { display: grid; grid-template-columns: repeat(3, 1fr); overflow: hidden; }
            .stat-cell { min-width: 0; padding: .7rem .35rem; border-left: 0; border-bottom: 1px solid var(--border); }
            .stat-cell:nth-child(3n + 2), .stat-cell:nth-child(3n + 3) { border-left: 1px solid var(--border); }
            .stat-cell:nth-last-child(-n + 3) { border-bottom: 0; }
            .stat-cell .value { font-size: 1.2rem; }
            .card > .card-body { padding: 1rem; }
            .tl-tabs { gap: 1.2rem; overflow-x: auto; }
            .tl-tabs button { white-space: nowrap; }
            .work-toolbar .search-box { flex-basis: 100%; }
            .work-toolbar select { flex: 1; min-width: 0; }
            .course-block summary { padding: .8rem; }
            .course-block .body { padding: 0 .8rem; }
            .work-row { display: grid; grid-template-columns: 48px 36px 1fr; gap: .65rem; align-items: center; padding: .85rem 0; }
            .work-row .time-chip { width: 48px; }
            .work-row .flex-grow-1 { min-width: 0 !important; }
            .work-row .name-line .code { display: block; margin-top: .1rem; }
            .work-row > .status-dot { grid-column: 2 / 3; }
            .work-row > .btn { grid-column: 3 / 4; justify-self: stretch; }
        }
    </style>

    <div class="attendance-intro">
        <div>
            <div class="breadcrumb-sm">การดำเนินการ <i class="bi bi-chevron-right small"></i> เช็คชื่อ</div>
            <h1 class="page-title">เช็คชื่อเข้าเรียน</h1>
            <p class="lead-text">จัดการคาบที่รอดำเนินการและดูประวัติได้ในหน้าเดียว</p>
        </div>
        @if ($stats['pending'] > 0)
            <span class="badge rounded-pill" style="background:var(--amber-soft); color:var(--amber); padding:.55rem .75rem;">{{ $stats['pending'] }} รายการรอดำเนินการ</span>
        @endif
    </div>

    <div class="tl-toolbar-row">
        <div class="date-nav">
            <a href="{{ route('teaching-logs.index', ['range' => $range, 'date' => $prevDate->toDateString()]) }}"
                class="nav-arrow" title="ก่อนหน้า"><i class="bi bi-chevron-left"></i></a>
            <div class="label">
                {{ $rangeLabel }}
                <span class="sub">{{ $teacherLabel }}</span>
            </div>
            <a href="{{ route('teaching-logs.index', ['range' => $range, 'date' => $nextDate->toDateString()]) }}"
                class="nav-arrow" title="ถัดไป"><i class="bi bi-chevron-right"></i></a>

            @unless ($isCurrentPeriod)
                <a href="{{ route('teaching-logs.index', ['range' => $range]) }}"
                    class="btn btn-sm btn-outline-secondary today-shortcut"><i class="bi bi-calendar-check"></i> วันนี้</a>
            @endunless

            @if ($range == 'month')
                @php
                    $thaiMonthNames = [1=>'มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];
                @endphp
                <div class="month-jump" title="เลือกเดือนที่ต้องการดู">
                    <i class="bi bi-calendar3"></i>
                    <select id="monthSelect" aria-label="เลือกเดือน">
                        @foreach($thaiMonthNames as $monthNumber=>$monthName)<option value="{{ $monthNumber }}" @selected($rangeStart->month===$monthNumber)>{{ $monthName }}</option>@endforeach
                    </select>
                    <select id="yearSelect" aria-label="เลือกปี">
                        @for($year=$rangeStart->year-3;$year<=$rangeStart->year+3;$year++)<option value="{{ $year }}" @selected($rangeStart->year===$year)>พ.ศ. {{ $year+543 }}</option>@endfor
                    </select>
                </div>
            @endif
        </div>

        <div class="tl-right-controls">
            <div class="range-toggle">
                <a href="{{ route('teaching-logs.index', ['range' => 'day', 'date' => $refDate->toDateString()]) }}"
                    class="{{ $range == 'day' ? 'active' : '' }}">รายวัน</a>
                <a href="{{ route('teaching-logs.index', ['range' => 'week', 'date' => $refDate->toDateString()]) }}"
                    class="{{ $range == 'week' ? 'active' : '' }}">รายสัปดาห์</a>
                <a href="{{ route('teaching-logs.index', ['range' => 'month', 'date' => $refDate->toDateString()]) }}"
                    class="{{ $range == 'month' ? 'active' : '' }}">รายเดือน</a>
            </div>
        </div>
    </div>

    {{-- ===== แถบสรุปสถิติ ===== --}}
    <div class="stat-strip">
        <div class="stat-cell">
            <div class="value">{{ $stats['total'] }}</div>
            <div class="label">
                @if ($range == 'day')
                    คลาสวันนี้
                @elseif($range == 'week')
                    คลาสสัปดาห์นี้
                @else
                    คลาสเดือนนี้
                @endif
            </div>
        </div>
        <div class="stat-cell">
            <div class="value" style="color:var(--amber);">{{ $stats['pending'] }}</div>
            <div class="label">รอเช็คชื่อ</div>
        </div>
        <div class="stat-cell">
            <div class="value" style="color:var(--success);">{{ $stats['checked'] }}</div>
            <div class="label">เช็คชื่อแล้ว</div>
        </div>
        <div class="stat-cell">
            <div class="value" style="color:#b3392c;">{{ $stats['absent'] }}</div>
            <div class="label">ขาดเรียน</div>
        </div>
        <div class="stat-cell">
            <div class="value" style="color:var(--accent);">{{ $stats['leave'] }}</div>
            <div class="label">ลาเรียน</div>
        </div>
        <div class="stat-cell">
            <div class="value">{{ $stats['makeup'] }}</div>
            <div class="label">เรียนชดเชย</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="tl-tabs">
                <button type="button" class="active" data-tab="pending" aria-selected="true">รอดำเนินการ <span
                        class="count">{{ $pendingItems->count() }}</span></button>
                <button type="button" data-tab="history" aria-selected="false">ประวัติการเช็คชื่อ <span
                        class="count">{{ $historyItems->count() }}</span></button>
            </div>

            <div class="work-toolbar">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="workSearch" placeholder="ค้นหานักเรียน / คอร์ส">
                </div>
                @if ($branches->count() > 1)
                    <select id="workBranch">
                        <option value="">ทุกสาขา</option>
                        @foreach ($branches as $b)
                            <option value="{{ $b }}">{{ $b }}</option>
                        @endforeach
                    </select>
                @endif
                <select id="workStatus">
                    <option value="">ทุกสถานะ</option>
                    <option value="pending">รอเช็คชื่อ</option>
                    <option value="checked">มาเรียน</option>
                    <option value="absent">ขาดเรียน</option>
                    <option value="leave">ลาเรียน</option>
                </select>
            </div>

            {{-- ===== Tab: เช็คชื่อ (ยังไม่เสร็จ ต้องดำเนินการต่อ) — แบ่งตามคอร์สเรียน ===== --}}
            <div class="tab-panel" data-panel="pending">
                @php $pendingByCourse = $pendingItems->groupBy(fn($s) => $s->enrollment->course->id ?? 0); @endphp
                @forelse ($pendingByCourse as $courseId => $items)
                    @php $course = $items->first()->enrollment->course; @endphp
                    <details class="course-block has-pending" {{ $loop->first ? 'open' : '' }}>
                        <summary>
                            <span class="name">{{ $course->name ?? 'ไม่ระบุคอร์ส' }}</span>
                            <span class="course-count-badge pending">{{ $items->count() }} รอเช็คชื่อ</span>
                        </summary>
                        <div class="body">
                            @foreach ($items as $s)
                                @include('teaching-logs._session-row', ['s' => $s, 'tab' => 'pending'])
                            @endforeach
                        </div>
                    </details>
                @empty
                    <p class="text-muted small text-center py-4 mb-0"><i class="bi bi-check-circle text-success"></i>
                        เช็คชื่อครบทุกคาบแล้ว</p>
                @endforelse
                <p id="pendingEmpty" class="text-muted small text-center py-3 mb-0 d-none">ไม่พบรายการที่ตรงกับตัวกรอง
                </p>
            </div>

            {{-- ===== Tab: ประวัติการเช็คชื่อ (ยืนยันเวลาสอนจริงแล้ว แก้ไขไม่ได้อีก) — แบ่งตามคอร์สเรียน ===== --}}
            <div class="tab-panel d-none" data-panel="history">
                @php $historyByCourse = $historyItems->groupBy(fn($s) => $s->enrollment->course->id ?? 0); @endphp
                @forelse ($historyByCourse as $courseId => $items)
                    @php $course = $items->first()->enrollment->course; @endphp
                    <details class="course-block">
                        <summary>
                            <span class="name">{{ $course->name ?? 'ไม่ระบุคอร์ส' }}</span>
                            <span class="course-count-badge done">{{ $items->count() }} คาบ</span>
                        </summary>
                        <div class="body">
                            @foreach ($items as $s)
                                @include('teaching-logs._session-row', ['s' => $s, 'tab' => 'history'])
                            @endforeach
                        </div>
                    </details>
                @empty
                    <p class="text-muted small text-center py-4 mb-0">ยังไม่มีประวัติการเช็คชื่อในช่วงนี้</p>
                @endforelse
                <p id="historyEmpty" class="text-muted small text-center py-3 mb-0 d-none">ไม่พบรายการที่ตรงกับตัวกรอง
                </p>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const tabs = document.querySelectorAll('.tl-tabs button');
            const panels = document.querySelectorAll('.tab-panel');
            const search = document.getElementById('workSearch');
            const branch = document.getElementById('workBranch');
            const status = document.getElementById('workStatus');

            const monthSelect = document.getElementById('monthSelect');
            const yearSelect = document.getElementById('yearSelect');
            function goToSelectedMonth() {
                if (!monthSelect?.value || !yearSelect?.value) return;
                const month = String(monthSelect.value).padStart(2, '0');
                window.location.href = `{{ route('teaching-logs.index') }}?range=month&date=${yearSelect.value}-${month}-01`;
            }
            monthSelect?.addEventListener('change', goToSelectedMonth);
            yearSelect?.addEventListener('change', goToSelectedMonth);

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    tabs.forEach(t => t.setAttribute('aria-selected', 'false'));
                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');
                    panels.forEach(p => p.classList.toggle('d-none', p.dataset.panel !== this.dataset.tab));
                });
            });

            function applyFilters() {
                const term = (search?.value || '').trim().toLowerCase();
                const branchVal = branch?.value || '';
                const statusVal = status?.value || '';

                panels.forEach(panel => {
                    let visibleCount = 0;

                    panel.querySelectorAll('.course-block').forEach(block => {
                        let visibleInBlock = 0;
                        block.querySelectorAll('.work-row').forEach(row => {
                            const matchesTerm = !term || row.dataset.search.includes(term);
                            const matchesBranch = !branchVal || row.dataset.branch === branchVal;
                            const matchesStatus = !statusVal || row.dataset.status === statusVal;
                            const show = matchesTerm && matchesBranch && matchesStatus;
                            row.style.display = show ? '' : 'none';
                            if (show) visibleInBlock++;
                        });
                        block.style.display = visibleInBlock === 0 ? 'none' : '';
                        if (visibleInBlock > 0 && (term || branchVal || statusVal)) block.open = true;
                        visibleCount += visibleInBlock;
                    });

                    const emptyMsg = panel.querySelector('#pendingEmpty, #historyEmpty');
                    if (emptyMsg) emptyMsg.classList.toggle('d-none', visibleCount !== 0 || panel.querySelectorAll(
                        '.course-block').length === 0);
                });
            }

            search?.addEventListener('input', applyFilters);
            branch?.addEventListener('change', applyFilters);
            status?.addEventListener('change', applyFilters);
        })();
    </script>
@endsection

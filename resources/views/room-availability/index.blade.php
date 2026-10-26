@extends('layouts.app')
@section('title', 'ตารางห้องว่าง (รายวัน)')

@section('content')
    <style>
        :root {
            --ra-line: #efede9;
            --ra-hour: #dedad3;
            --ra-head: #f7f6f3;
            --ra-class: #2f5aa8;
            --ra-class-soft: #e4ecf8;
            --ra-trial: #c9781a;
            --ra-trial-soft: #fcefdc;
            --ra-booking: #2e7d4f;
            --ra-booking-soft: #e2f2e7;
            --ra-leave: #c0392b;
            --ra-leave-soft: #fde4e1;
            --ra-free: #2e7d4f;
            --ra-free-soft: #e8f5ec;
        }

        /* ===== ส่วนหัว: วันที่ + สรุป ===== */
        .ra-hero {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.1rem 1.3rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 2px rgba(28, 26, 23, .04);
        }

        .ra-date {
            display: flex;
            align-items: center;
            gap: .9rem;
        }

        .ra-date-num {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            background: var(--accent, #1f3350);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            line-height: 1;
            font-family: 'Prompt', sans-serif;
        }

        .ra-date-num .d {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .ra-date-num .m {
            font-size: .68rem;
            opacity: .85;
            margin-top: 3px;
        }

        .ra-date-text .dow {
            font-family: 'Prompt', sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--ink, #1c1a17);
        }

        .ra-date-text .full {
            font-size: .82rem;
            color: var(--muted, #6b655e);
        }

        .ra-today-badge {
            font-size: .68rem;
            padding: .15rem .5rem;
            border-radius: 999px;
            background: var(--ra-free-soft);
            color: var(--ra-free);
            font-weight: 600;
            vertical-align: 3px;
        }

        .ra-nav .btn {
            border-radius: 10px;
        }

        .ra-nav input[type=date] {
            border-radius: 10px;
            width: 160px;
        }

        .ra-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .75rem;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .ra-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .ra-stat {
            border-radius: 12px;
            padding: .7rem .9rem;
            background: var(--ra-head);
            display: flex;
            align-items: center;
            gap: .7rem;
        }

        .ra-stat i {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            background: #fff;
        }

        .ra-stat .v {
            font-family: 'Prompt', sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .ra-stat .l {
            font-size: .74rem;
            color: var(--muted, #6b655e);
        }

        .ra-leaves {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .45rem;
            margin-top: 1rem;
            padding-top: .9rem;
            border-top: 1px dashed var(--border, #e4e1dc);
            font-size: .85rem;
        }

        .leave-chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .28rem .75rem;
            border-radius: 999px;
            background: var(--ra-leave-soft);
            color: var(--ra-leave);
            font-size: .8rem;
            font-weight: 600;
        }

        .leave-chip.pending {
            background: var(--ra-trial-soft);
            color: var(--ra-trial);
        }

        /* ===== การ์ดแท็บ ===== */
        .ra-card {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 2px rgba(28, 26, 23, .04);
            overflow: hidden;
        }

        .ra-card-head {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: .5rem 1rem;
            padding: 0 1.2rem;
            border-bottom: 1px solid var(--border, #e4e1dc);
            background: var(--ra-head);
        }

        .ra-tabs {
            display: flex;
            gap: .3rem;
            border: 0;
        }

        .ra-tabs .nav-link {
            border: 0;
            border-bottom: 3px solid transparent;
            border-radius: 0;
            padding: .95rem .9rem .8rem;
            color: var(--muted, #6b655e);
            font-weight: 600;
            font-size: .92rem;
            background: transparent;
        }

        .ra-tabs .nav-link:hover {
            color: var(--ink, #1c1a17);
        }

        .ra-tabs .nav-link.active {
            color: var(--accent-dark, #13233a);
            border-bottom-color: var(--accent, #1f3350);
            background: transparent;
        }

        .ra-tabs .count {
            display: inline-block;
            min-width: 22px;
            padding: .05rem .45rem;
            margin-left: .3rem;
            border-radius: 999px;
            font-size: .72rem;
            background: #e9e7e3;
            color: var(--muted, #6b655e);
        }

        .ra-tabs .nav-link.active .count {
            background: var(--accent, #1f3350);
            color: #fff;
        }

        .ra-legend {
            display: flex;
            flex-wrap: wrap;
            gap: .35rem .9rem;
            padding: .8rem 0;
            font-size: .76rem;
            color: var(--muted, #6b655e);
        }

        .ra-legend span {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
        }

        .ra-legend .sw {
            width: 12px;
            height: 12px;
            border-radius: 4px;
        }

        .ra-hint {
            font-size: .76rem;
            color: var(--muted, #6b655e);
            padding: .6rem 1.2rem;
            border-bottom: 1px solid var(--ra-line);
        }

        /* ===== ตาราง ===== */
        .ra-wrap {
            overflow-x: auto;
        }

        .ra-table {
            border-collapse: separate;
            border-spacing: 0;
            width: max-content;
            min-width: 100%;
            font-size: .78rem;
            table-layout: fixed;
        }

        .ra-table col.slot {
            width: 60px;
        }

        .ra-table col.room {
            width: 150px;
        }

        .ra-table th,
        .ra-table td {
            padding: 0;
            height: 34px;
            text-align: center;
            white-space: nowrap;
            border-right: 1px solid var(--ra-line);
        }

        .ra-table .hour {
            border-left: 1px solid var(--ra-hour);
        }

        .ra-table thead th {
            background: #fff;
            padding: .55rem .1rem;
            line-height: 1.2;
            border-bottom: 1px solid var(--border, #e4e1dc);
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .ra-table thead th .t1 {
            display: block;
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: .8rem;
            color: var(--ink, #1c1a17);
        }

        .ra-table thead th .t2 {
            display: block;
            font-size: .68rem;
            color: #a29c94;
            font-weight: 500;
        }

        .ra-table thead th.now {
            background: var(--accent, #1f3350);
        }

        .ra-table thead th.now .t1,
        .ra-table thead th.now .t2 {
            color: #fff;
        }

        .ra-table tbody td.now {
            background: rgba(31, 51, 80, .04);
        }

        .ra-table .room-cell {
            position: sticky;
            left: 0;
            z-index: 1;
            background: #fff;
            text-align: left;
            padding: .5rem .9rem;
            white-space: normal;
            line-height: 1.25;
            border-right: 1px solid var(--border, #e4e1dc);
            vertical-align: middle;
        }

        .ra-table thead .room-cell {
            z-index: 3;
            font-size: .76rem;
            color: var(--muted, #6b655e);
            font-weight: 600;
        }

        .ra-table tr.room-start>td {
            border-top: 1px solid var(--border, #e4e1dc);
        }

        .ra-table tbody tr:first-child>td {
            border-top: 0;
        }

        .room-name {
            font-weight: 700;
            font-size: .85rem;
            color: var(--ink, #1c1a17);
        }

        .room-meta {
            font-size: .7rem;
            color: var(--muted, #6b655e);
            margin-top: 2px;
        }

        .room-util {
            height: 4px;
            border-radius: 99px;
            background: var(--ra-free-soft);
            margin-top: 6px;
            overflow: hidden;
        }

        .room-util>div {
            height: 100%;
            background: var(--ra-class);
            border-radius: 99px;
        }

        /* ช่องว่าง */
        a.ra-free-link {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 34px;
            margin: 0 2px;
            border-radius: 8px;
            color: transparent;
            text-decoration: none;
            font-weight: 700;
            transition: background .12s;
        }

        a.ra-free-link:hover {
            background: var(--ra-free-soft);
            color: var(--ra-free);
        }

        .ra-closed {
            background: repeating-linear-gradient(45deg, #faf9f7, #faf9f7 7px, #f1efeb 7px, #f1efeb 14px);
            color: var(--muted, #6b655e);
            font-weight: 600;
        }

        /* คาบเรียน */
        .ra-event {
            padding: 3px 2px !important;
        }

        .ra-block {
            display: flex;
            align-items: center;
            gap: .35rem;
            height: 30px;
            padding: 0 4px 0 .55rem;
            border-radius: 8px;
            border-left: 4px solid var(--ra-class);
            background: var(--ra-class-soft);
            color: #13233a;
            text-decoration: none;
            overflow: hidden;
            transition: transform .1s, box-shadow .1s;
        }

        a.ra-block:hover {
            box-shadow: 0 2px 8px rgba(28, 26, 23, .15);
            transform: translateY(-1px);
            color: #13233a;
        }

        .ra-block .ra-name {
            flex: 1;
            min-width: 0;
            text-align: left;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ra-block .ra-min {
            flex: none;
            padding: .1rem .4rem;
            border-radius: 6px;
            background: var(--ra-class);
            color: #fff;
            font-size: .68rem;
            font-weight: 700;
        }

        .ra-event.trial .ra-block {
            border-left-color: var(--ra-trial);
            background: var(--ra-trial-soft);
            color: #6b3d0a;
        }

        .ra-event.trial .ra-min {
            background: var(--ra-trial);
        }

        .ra-event.booking .ra-block {
            border-left-color: var(--ra-booking);
            background: var(--ra-booking-soft);
            color: #1d4d31;
        }

        .ra-event.booking .ra-min {
            background: var(--ra-booking);
        }

        /* แถบชื่ออาจารย์ */
        .ra-teacher-row td {
            height: 26px;
            padding: 0 2px 5px;
        }

        .ra-teacher-pill {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .3rem;
            height: 21px;
            border-radius: 99px;
            background: #eef1f6;
            color: #3b4a63;
            font-size: .72rem;
            font-weight: 700;
            font-style: italic;
            overflow: hidden;
        }

        .ra-teacher-pill.leave {
            background: var(--ra-leave);
            color: #fff;
            font-style: normal;
        }

        /* ===== ตารางเวลาว่างอาจารย์ ===== */
        .tf-cell {
            padding: 3px 2px !important;
        }

        .tf-block {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .3rem;
            height: 30px;
            border-radius: 8px;
            font-size: .72rem;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 0 .4rem;
        }

        .tf-free .tf-block {
            background: var(--ra-free-soft);
            color: var(--ra-free);
            border: 1px solid #c6e6d0;
        }

        .tf-busy .tf-block {
            background: var(--ra-class-soft);
            color: #13233a;
        }

        .tf-leave .tf-block {
            background: repeating-linear-gradient(45deg, var(--ra-leave-soft), var(--ra-leave-soft) 6px, #fbd3cf 6px, #fbd3cf 12px);
            color: var(--ra-leave);
        }

        .tf-off .tf-block {
            background: transparent;
        }

        .t-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent-dark, #13233a);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex: none;
        }

        .t-avatar.leave {
            background: var(--ra-leave-soft);
            color: var(--ra-leave);
        }

        .t-sub {
            display: inline-block;
            font-size: .68rem;
            padding: .05rem .45rem;
            border-radius: 999px;
            margin-top: 3px;
            font-weight: 600;
        }

        .t-sub.free {
            background: var(--ra-free-soft);
            color: var(--ra-free);
        }

        .t-sub.none {
            background: #f1efeb;
            color: var(--muted, #6b655e);
        }

        .t-sub.leave {
            background: var(--ra-leave-soft);
            color: var(--ra-leave);
        }

        .ra-empty {
            padding: 2.5rem 1rem !important;
            color: var(--muted, #6b655e);
        }

        .ra-online {
            margin: 0 1.2rem 1.2rem;
            padding: .9rem 1rem;
            border-radius: 12px;
            background: var(--ra-head);
            font-size: .82rem;
        }
    </style>

    @php
        $tab = request('tab') === 'teachers' ? 'teachers' : 'rooms';
        $nav = fn($d) => request()->fullUrlWithQuery(['date' => $d->toDateString(), 'tab' => $tab]);
        $thaiDays = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
        $thaiMonths = ['', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        $thaiMonthsShort = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        $createUrl = fn($roomId, $slot) => route('schedules.create', [
            'room_id' => $roomId,
            'schedule_date' => $date->toDateString(),
            'start_time' => $slot['start'],
            'end_time' => $slot['end'],
        ]);

        // แสดงเวลาแบบไฟล์ตารางของแอดมิน เช่น 9.30, 10.00
        $fmt = fn($t) => ltrim(str_replace(':', '.', $t), '0');

        // ช่องเวลาปัจจุบัน (ถ้าดูวันนี้) และเส้นแบ่งชั่วโมง
        $nowTime = now()->format('H:i');
        $nowSlot = $date->isToday() ? $slots->search(fn($s) => $s['start'] <= $nowTime && $nowTime < $s['end']) : false;
        $slotClass = fn($i) => trim((str_ends_with($slots[$i]['start'], ':00') ? 'hour' : '') . ($i === $nowSlot ? ' now' : ''));

        // สรุปภาพรวมของวัน
        $dayMinutes = $slots->count() * 30;
        $openRooms = $roomRows->where('closed', false);
        $freeHours = $openRooms->sum(fn($r) => max(0, $dayMinutes - $r['busy'])) / 60;
        $lessonCount = $roomRows->sum(fn($r) => collect($r['lanes'])->flatten(1)->where('empty', false)->count()) + $noRoomEvents->count();

        // รวมช่องติดกันที่สถานะเดียวกันในตารางอาจารย์ให้เป็นบล็อกเดียว
        $mergeCells = function ($cells) use ($slots) {
            $segments = [];
            foreach ($cells as $i => $c) {
                $key = $c['state'] . '|' . ($c['label'] ?? '') . '|' . ($c['title'] ?? '');
                $last = count($segments) - 1;
                if ($last >= 0 && $segments[$last]['key'] === $key) {
                    $segments[$last]['span']++;
                    $segments[$last]['end'] = $slots[$i]['end'];
                } else {
                    $segments[] = $c + ['key' => $key, 'span' => 1, 'from' => $i, 'start' => $slots[$i]['start'], 'end' => $slots[$i]['end']];
                }
            }
            return $segments;
        };
    @endphp

    <div class="breadcrumb-sm">ตารางเรียน <i class="bi bi-chevron-right small"></i> ตารางห้องว่าง (รายวัน)</div>
    <h1 class="page-title mb-3">ตารางห้องว่าง (รายวัน)</h1>

    {{-- ===== วันที่ + สรุปภาพรวม + อาจารย์ลา ===== --}}
    <div class="ra-hero">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="ra-date">
                <div class="ra-date-num">
                    <span class="d">{{ $date->day }}</span>
                    <span class="m">{{ $thaiMonthsShort[$date->month] }}</span>
                </div>
                <div class="ra-date-text">
                    <div class="dow">
                        วัน{{ $thaiDays[$date->dayOfWeek] }}
                        @if ($date->isToday())
                            <span class="ra-today-badge">วันนี้</span>
                        @endif
                    </div>
                    <div class="full">{{ $date->day }} {{ $thaiMonths[$date->month] }} {{ $date->year + 543 }}</div>
                </div>
            </div>

            <form method="GET" class="ra-nav d-flex align-items-center gap-2">
                <a href="{{ $nav($date->copy()->subDay()) }}" data-tab-link class="btn btn-outline-secondary"
                    title="วันก่อนหน้า"><i class="bi bi-chevron-left"></i></a>
                <input type="date" name="date" value="{{ $date->toDateString() }}" class="form-control"
                    onchange="this.form.submit()">
                <input type="hidden" name="tab" value="{{ $tab }}" data-tab-input>
                <a href="{{ $nav($date->copy()->addDay()) }}" data-tab-link class="btn btn-outline-secondary"
                    title="วันถัดไป"><i class="bi bi-chevron-right"></i></a>
                <a href="{{ $nav(now()) }}" data-tab-link class="btn btn-accent">วันนี้</a>
            </form>
        </div>

        <div class="ra-stats">
            <div class="ra-stat">
                <i class="bi bi-door-open" style="color:var(--accent,#1f3350)"></i>
                <div>
                    <div class="v">{{ $openRooms->count() }}</div>
                    <div class="l">ห้องที่เปิดใช้</div>
                </div>
            </div>
            <div class="ra-stat">
                <i class="bi bi-clock-history" style="color:var(--ra-free)"></i>
                <div>
                    <div class="v">{{ rtrim(rtrim(number_format($freeHours, 1), '0'), '.') }} ชม.</div>
                    <div class="l">เวลาห้องว่างรวม</div>
                </div>
            </div>
            <div class="ra-stat">
                <i class="bi bi-music-note-beamed" style="color:var(--ra-class)"></i>
                <div>
                    <div class="v">{{ $lessonCount }}</div>
                    <div class="l">คาบ / นัดหมายวันนี้</div>
                </div>
            </div>
            <div class="ra-stat">
                <i class="bi bi-person-x" style="color:var(--ra-leave)"></i>
                <div>
                    <div class="v" style="{{ $leaves->isNotEmpty() ? 'color:var(--ra-leave)' : '' }}">{{ $leaves->count() }}</div>
                    <div class="l">อาจารย์ลา</div>
                </div>
            </div>
        </div>

        <div class="ra-leaves">
            <span class="fw-semibold me-1"><i class="bi bi-person-x"></i> อาจารย์ลาวันนี้</span>
            @forelse ($leaves as $leave)
                <span class="leave-chip {{ $leave->status === 'pending' ? 'pending' : '' }}" title="{{ $leave->reason }}">
                    <i class="bi {{ $leave->status === 'pending' ? 'bi-hourglass-split' : 'bi-x-circle' }}"></i>
                    K.{{ $leave->teacher?->nickname ?: $leave->teacher?->full_name }}
                    <small class="fw-normal">· {{ $leave->statusLabel() }}</small>
                </span>
            @empty
                <span class="text-muted">ไม่มีอาจารย์ลา — ทุกคนสอนตามปกติ</span>
            @endforelse
        </div>
    </div>

    {{-- ===== แท็บ ===== --}}
    <div class="ra-card">
        <div class="ra-card-head">
            <ul class="nav ra-tabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link {{ $tab === 'rooms' ? 'active' : '' }}" data-bs-toggle="tab"
                        data-bs-target="#tabRooms" data-tab="rooms">
                        <i class="bi bi-door-open"></i> ห้องเรียน<span class="count">{{ $roomRows->count() }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $tab === 'teachers' ? 'active' : '' }}" data-bs-toggle="tab"
                        data-bs-target="#tabTeachers" data-tab="teachers">
                        <i class="bi bi-person-badge"></i> เวลาว่างของอาจารย์ (สำหรับเสนอขาย)<span
                            class="count">{{ $teacherRows->count() }}</span>
                    </button>
                </li>
            </ul>

            <div class="ra-legend" data-legend="rooms" @if ($tab !== 'rooms') hidden @endif>
                <span><i class="sw" style="background:var(--ra-class)"></i>คาบเรียน</span>
                <span><i class="sw" style="background:var(--ra-trial)"></i>ทดลองเรียน</span>
                <span><i class="sw" style="background:var(--ra-booking)"></i>จองห้อง</span>
                <span><i class="sw" style="background:var(--ra-leave)"></i>อาจารย์ลา</span>
            </div>
            <div class="ra-legend" data-legend="teachers" @if ($tab !== 'teachers') hidden @endif>
                <span><i class="sw" style="background:var(--ra-free-soft); border:1px solid #c6e6d0"></i>ว่าง</span>
                <span><i class="sw" style="background:var(--ra-class-soft)"></i>สอนอยู่ (ชื่อห้อง)</span>
                <span><i class="sw" style="background:var(--ra-leave-soft)"></i>ลา</span>
                <span><i class="sw" style="background:#fff; border:1px solid var(--border,#e4e1dc)"></i>นอกเวลาทำงาน</span>
            </div>
        </div>

        <div class="tab-content">
            {{-- ===== แท็บ: ห้องเรียน ===== --}}
            <div class="tab-pane fade {{ $tab === 'rooms' ? 'show active' : '' }}" id="tabRooms">
                @if ($canEditSchedule)
                    <div class="ra-hint"><i class="bi bi-hand-index"></i> ช่องว่าง = ห้องว่าง · คลิกช่องว่างเพื่อเพิ่มตารางเรียนในเวลานั้นได้ทันที</div>
                @endif

                <div class="ra-wrap">
                    <table class="ra-table">
                        <colgroup>
                            <col class="room">
                            @foreach ($slots as $slot)
                                <col class="slot">
                            @endforeach
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="room-cell">ห้อง / เวลา</th>
                                @foreach ($slots as $i => $slot)
                                    <th class="{{ $slotClass($i) }}">
                                        <span class="t1">{{ $fmt($slot['start']) }}</span>
                                        <span class="t2">{{ $fmt($slot['end']) }}</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roomRows as $row)
                                @php
                                    $rowspan = count($row['lanes']) + 1;
                                    $usedPct = $dayMinutes ? min(100, round($row['busy'] / $dayMinutes * 100)) : 0;
                                    $roomFree = max(0, $dayMinutes - $row['busy']) / 60;
                                @endphp

                                @if ($row['closed'])
                                    <tr class="room-start">
                                        <td class="room-cell">
                                            <div class="room-name">{{ $row['room']->name }}</div>
                                            <div class="room-meta text-warning"><i class="bi bi-tools"></i> ปิดปรับปรุง</div>
                                        </td>
                                        <td colspan="{{ $slots->count() }}" class="ra-closed">
                                            <i class="bi bi-tools"></i> ปิดปรับปรุง{{ $row['room']->maintenance_reason ? ' — ' . $row['room']->maintenance_reason : '' }}
                                        </td>
                                    </tr>
                                    @continue
                                @endif

                                @foreach ($row['lanes'] as $laneIdx => $cells)
                                    <tr class="{{ $laneIdx === 0 ? 'room-start' : '' }}">
                                        @if ($laneIdx === 0)
                                            <td class="room-cell" rowspan="{{ $rowspan }}">
                                                <div class="room-name">{{ $row['room']->name }}</div>
                                                <div class="room-meta">
                                                    @if ($row['room']->capacity)
                                                        <i class="bi bi-people"></i> {{ $row['room']->capacity }} คน ·
                                                    @endif
                                                    ว่าง {{ rtrim(rtrim(number_format($roomFree, 1), '0'), '.') }} ชม.
                                                </div>
                                                <div class="room-util" title="ใช้งาน {{ $usedPct }}%">
                                                    <div style="width: {{ $usedPct }}%"></div>
                                                </div>
                                            </td>
                                        @endif
                                        @foreach ($cells as $cell)
                                            @if ($cell['empty'])
                                                <td class="{{ $slotClass($cell['slot']) }}">
                                                    @if ($canEditSchedule)
                                                        <a class="ra-free-link"
                                                            href="{{ $createUrl($row['room']->id, $slots[$cell['slot']]) }}"
                                                            title="ว่าง {{ $slots[$cell['slot']]['start'] }}-{{ $slots[$cell['slot']]['end'] }} · คลิกเพื่อเพิ่มตารางเรียน">+</a>
                                                    @endif
                                                </td>
                                            @else
                                                @php
                                                    $e = $cell['item'];
                                                    $tip = $e['start'] . '-' . ($e['end'] ?? '') . ' · ' . $e['title']
                                                        . ($e['detail'] ? ' · ' . $e['detail'] : '')
                                                        . ($e['teacher'] ? ' · K.' . $e['teacher'] : '');
                                                @endphp
                                                <td colspan="{{ $cell['span'] }}"
                                                    class="ra-event {{ $e['type'] }} {{ $slotClass($e['from']) }}">
                                                    @if ($e['url'])
                                                        <a href="{{ $e['url'] }}" class="ra-block" title="{{ $tip }}">
                                                            <span class="ra-name">{{ $e['title'] }}</span>
                                                            <span class="ra-min">{{ $e['minutes'] }}</span>
                                                        </a>
                                                    @else
                                                        <div class="ra-block" title="{{ $tip }}">
                                                            <span class="ra-name">{{ $e['title'] }}</span>
                                                            <span class="ra-min">{{ $e['minutes'] }}</span>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach

                                <tr class="ra-teacher-row">
                                    @foreach ($row['teachers'] as $cell)
                                        @if ($cell['empty'])
                                            <td class="{{ $slotClass($cell['slot']) }}"></td>
                                        @else
                                            <td colspan="{{ $cell['span'] }}" class="{{ $slotClass($cell['item']['from']) }}">
                                                <div class="ra-teacher-pill {{ $cell['item']['on_leave'] ? 'leave' : '' }}"
                                                    title="{{ $cell['item']['on_leave'] ? 'อาจารย์ลาวันนี้ — ต้องหาครูแทน/เลื่อนคาบ' : 'K.' . $cell['item']['teacher'] }}">
                                                    @if ($cell['item']['on_leave'])
                                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                                    @endif
                                                    K.{{ $cell['item']['teacher'] }}
                                                    @if ($cell['item']['on_leave'])
                                                        · ลา
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $slots->count() + 1 }}" class="ra-empty">
                                        <i class="bi bi-door-closed fs-4 d-block mb-1"></i> ยังไม่มีห้องเรียนที่เปิดใช้งาน
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($noRoomEvents->isNotEmpty())
                    <div class="ra-online mt-3">
                        <div class="fw-bold mb-2"><i class="bi bi-laptop"></i> คาบที่ไม่ได้ระบุห้อง (เช่น ออนไลน์)</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($noRoomEvents as $e)
                                <span class="badge rounded-pill bg-white text-dark border fw-normal px-3 py-2">
                                    <strong>{{ $e['start'] }}-{{ $e['end'] }}</strong> · {{ $e['title'] }}
                                    ({{ $e['minutes'] }} นาที){{ $e['teacher'] ? ' · K.' . $e['teacher'] : '' }}
                                    @if ($e['on_leave'])
                                        <span class="badge text-bg-danger ms-1">อาจารย์ลา</span>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- ===== แท็บ: เวลาว่างของอาจารย์ (สำหรับฝ่ายขาย) ===== --}}
            <div class="tab-pane fade {{ $tab === 'teachers' ? 'show active' : '' }}" id="tabTeachers">
                <div class="ra-hint"><i class="bi bi-lightbulb"></i> บล็อกสีเขียว = เวลาที่อาจารย์ว่าง เสนอขายลูกค้าได้ ·
                    คำนวณจากเวลาสอนประจำสัปดาห์ หักคาบเรียน / ทดลองเรียน / จองห้อง และวันลา</div>

                <div class="ra-wrap">
                    <table class="ra-table">
                        <colgroup>
                            <col class="room">
                            @foreach ($slots as $slot)
                                <col class="slot">
                            @endforeach
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="room-cell">อาจารย์ / เวลา</th>
                                @foreach ($slots as $i => $slot)
                                    <th class="{{ $slotClass($i) }}">
                                        <span class="t1">{{ $fmt($slot['start']) }}</span>
                                        <span class="t2">{{ $fmt($slot['end']) }}</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($teacherRows as $tr)
                                @php $tName = $tr['teacher']->nickname ?: $tr['teacher']->full_name; @endphp
                                <tr class="room-start">
                                    <td class="room-cell">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="t-avatar {{ $tr['leave'] ? 'leave' : '' }}">{{ mb_substr($tName, 0, 1) }}</div>
                                            <div class="min-w-0">
                                                <div class="room-name text-truncate">K.{{ $tName }}</div>
                                                @if ($tr['leave'])
                                                    <span class="t-sub leave">ลา · {{ $tr['leave']->statusLabel() }}</span>
                                                @elseif ($tr['freeSlots'])
                                                    <span class="t-sub free">ว่าง {{ rtrim(rtrim(number_format($tr['freeSlots'] / 2, 1), '0'), '.') }} ชม.</span>
                                                @else
                                                    <span class="t-sub none">ไม่ว่าง</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    @foreach ($mergeCells($tr['cells']) as $seg)
                                        @switch($seg['state'])
                                            @case('busy')
                                                <td colspan="{{ $seg['span'] }}" class="tf-cell tf-busy {{ $slotClass($seg['from']) }}">
                                                    <div class="tf-block" title="{{ $seg['start'] }}-{{ $seg['end'] }} · {{ $seg['title'] }}">
                                                        <i class="bi bi-door-closed"></i> {{ $seg['label'] }}
                                                    </div>
                                                </td>
                                            @break

                                            @case('free')
                                                <td colspan="{{ $seg['span'] }}" class="tf-cell tf-free {{ $slotClass($seg['from']) }}">
                                                    <div class="tf-block" title="ว่าง {{ $seg['start'] }}-{{ $seg['end'] }}">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                        @if ($seg['span'] >= 2)
                                                            ว่าง {{ $fmt($seg['start']) }}-{{ $fmt($seg['end']) }}
                                                        @endif
                                                    </div>
                                                </td>
                                            @break

                                            @case('leave')
                                                <td colspan="{{ $seg['span'] }}" class="tf-cell tf-leave {{ $slotClass($seg['from']) }}">
                                                    <div class="tf-block"><i class="bi bi-x-circle"></i> ลา</div>
                                                </td>
                                            @break

                                            @default
                                                @for ($k = 0; $k < $seg['span']; $k++)
                                                    <td class="tf-off {{ $slotClass($seg['from'] + $k) }}"></td>
                                                @endfor
                                        @endswitch
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $slots->count() + 1 }}" class="ra-empty">
                                        <i class="bi bi-calendar-x fs-4 d-block mb-1"></i>
                                        ไม่มีอาจารย์ที่ตั้งเวลาสอนไว้ในวัน{{ $thaiDays[$date->dayOfWeek] }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // จำแท็บที่เปิดอยู่ไว้ใน URL เพื่อให้เปลี่ยนวันแล้วยังอยู่แท็บเดิม และสลับคำอธิบายสีตามแท็บ
        document.querySelectorAll('[data-tab]').forEach(btn => btn.addEventListener('shown.bs.tab', () => {
            const tab = btn.dataset.tab;
            const url = new URL(location.href);
            url.searchParams.set('tab', tab);
            history.replaceState(null, '', url);
            document.querySelector('[data-tab-input]').value = tab;
            document.querySelectorAll('[data-tab-link]').forEach(a => {
                const u = new URL(a.href);
                u.searchParams.set('tab', tab);
                a.href = u;
            });
            document.querySelectorAll('[data-legend]').forEach(el => el.hidden = el.dataset.legend !== tab);
        }));
    </script>
@endsection

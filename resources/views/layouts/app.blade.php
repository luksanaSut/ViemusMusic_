<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'จัดการอาจารย์') — VIEMUS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&family=Sarabun:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --ink: #1c1a17;
            --ink-soft: #2b2723;
            --surface: #f4f3f1;
            --card: #ffffff;
            --accent: #1f3350;
            --accent-soft: #e7ebf1;
            --accent-dark: #13233a;
            --success: #2f6f4e;
            --success-soft: #e7f2ec;
            --amber: #8a5a2b;
            --amber-soft: #f3ece2;
            --muted: #6b655e;
            --border: #e4e1dc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--surface);
            font-family: 'Sarabun', sans-serif;
            color: var(--ink);
            margin: 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        .brand,
        .nav-section-label,
        .stat-value {
            font-family: 'Prompt', sans-serif;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 264px;
            background: var(--ink);
            color: #c9c4bb;
            display: flex;
            flex-direction: column;
            z-index: 40;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #4a453e var(--ink);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--ink);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #4a453e;
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #5c574f;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: 1.25rem 1.1rem 1rem;
        }

        .brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent), #4b3d2f);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: .95rem;
            flex-shrink: 0;
        }

        .brand-text {
            line-height: 1.1;
        }

        .brand-text .name {
            color: #fff;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: .3px;
        }

        .brand-text .sub {
            font-size: .62rem;
            letter-spacing: 1.5px;
            color: #8f887e;
        }

        .nav-section-label {
            font-size: .68rem;
            color: #8f887e;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 1rem 1.1rem .35rem;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: .65rem;
            color: #c9c4bb;
            padding: .55rem 1.1rem;
            font-size: .9rem;
            border-left: 3px solid transparent;
            text-decoration: none;
        }

        .sidebar .nav-link i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            background: var(--ink-soft);
            color: #fff;
        }

        .sidebar .nav-link.active {
            background: var(--ink-soft);
            color: #fff;
            border-left-color: var(--accent);
            font-weight: 600;
        }

        .sidebar .nav-link.disabled {
            color: #655f58;
            cursor: default;
        }

        .sidebar .nav-link.disabled:hover {
            background: transparent;
            color: #655f58;
        }

        .sidebar .badge-count {
            margin-left: auto;
            background: var(--ink-soft);
            color: #a89f92;
            font-size: .68rem;
            padding: .1rem .45rem;
            border-radius: 20px;
        }

        .sidebar .nav-link.active .badge-count {
            background: var(--accent);
            color: #fff;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: .9rem 1.1rem;
            border-top: 1px solid #35302a;
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .sidebar-footer .avatar-sm {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--accent-soft);
            color: var(--accent-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .8rem;
            flex-shrink: 0;
        }

        .sidebar-footer .who .role {
            font-size: .7rem;
            color: #8f887e;
        }

        .sidebar-footer a {
            color: #8b8fb8;
            font-size: .78rem;
            text-decoration: none;
        }

        /* ===== Main / Topbar ===== */
        .main-wrap {
            margin-left: 264px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: .65rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .topbar .search-box {
            flex: 1;
            max-width: 480px;
            position: relative;
        }

        .topbar .search-box input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .5rem .9rem .5rem 2.2rem;
            background: var(--surface);
            font-size: .85rem;
        }

        .topbar .search-box i {
            position: absolute;
            left: .75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
        }

        .topbar .kbd {
            position: absolute;
            right: .6rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: .68rem;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 5px;
            padding: .05rem .35rem;
            color: var(--muted);
        }

        .content {
            padding: 1.5rem;
            flex: 1;
        }

        .breadcrumb-sm {
            font-size: .78rem;
            color: var(--muted);
            margin-bottom: .15rem;
        }

        .page-title {
            font-weight: 700;
            font-size: 1.45rem;
            margin: 0;
        }

        .page-sub {
            color: var(--muted);
            font-size: .85rem;
        }

        .btn-accent {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .btn-accent:hover {
            background: var(--accent-dark);
            border-color: var(--accent-dark);
            color: #fff;
        }

        .card {
            border: 1px solid var(--border);
            box-shadow: 0 1px 2px rgba(23, 26, 43, .04);
            border-radius: 14px;
        }

        .tab-pills .nav-link {
            border-radius: 10px;
            padding: .4rem .9rem;
            font-size: .85rem;
            color: var(--muted);
            font-weight: 500;
        }

        .tab-pills .nav-link.active {
            background: var(--accent-soft);
            color: var(--accent-dark);
            font-weight: 600;
        }

        /* ===== Topbar user menu ===== */
        .topbar-user-btn {
            border: 1px solid transparent;
            border-radius: 999px;
            padding: .3rem .5rem .3rem .3rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            background: transparent;
            transition: .15s;
        }

        .topbar-user-btn:hover,
        .topbar-user-btn:focus {
            background: var(--surface);
            border-color: var(--border);
            box-shadow: none;
        }

        .topbar-user-btn .avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: .82rem;
        }

        .topbar-user-btn .chevron {
            color: var(--muted);
            font-size: .75rem;
            transition: .15s;
        }

        .topbar-user-btn[aria-expanded="true"] .chevron {
            transform: rotate(180deg);
        }

        .user-dropdown-menu {
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(28, 26, 23, .12);
            padding: .5rem;
            margin-top: .5rem !important;
        }

        .user-dropdown-menu .user-info {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .5rem .6rem .8rem;
        }

        .user-dropdown-menu .user-info .avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: .9rem;
        }

        .user-dropdown-menu .user-info .name {
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            font-size: .88rem;
            color: var(--ink);
        }

        .user-dropdown-menu .user-info .meta {
            font-size: .72rem;
            color: var(--muted);
        }

        .user-dropdown-menu hr {
            margin: .3rem 0;
            border-color: var(--border);
        }

        .user-dropdown-menu .dropdown-item {
            border-radius: 9px;
            padding: .55rem .7rem;
            font-size: .85rem;
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .user-dropdown-menu .dropdown-item i {
            font-size: .95rem;
            width: 18px;
            text-align: center;
            color: var(--muted);
        }

        .user-dropdown-menu .dropdown-item:hover {
            background: var(--accent-soft);
            color: var(--accent-dark);
        }

        .user-dropdown-menu .dropdown-item:hover i {
            color: var(--accent-dark);
        }

        .user-dropdown-menu .dropdown-item.text-danger:hover {
            background: #fbeae7;
            color: #b3392c;
        }

        .user-dropdown-menu .dropdown-item.text-danger:hover i {
            color: #b3392c;
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-mark">V</div>
            <div class="brand-text">
                <div class="name">VIEMUS</div>
                <div class="sub">INTERNATIONAL SCHOOL OF MUSIC</div>
            </div>
        </div>

        @if (auth()->user()->isAdmin() || auth()->user()->isStaff())
            @php $u = auth()->user(); @endphp

            {{-- ==================== ภาพรวม ==================== --}}
            @if ($u->hasModulePermission('teachers.manage'))
                <div class="nav-section-label">ภาพรวม</div>

                <a href="{{ route('teachers.index') }}" class="nav-link">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
            @endif


            {{-- ==================== บุคคล ==================== --}}
            @if (
                $u->hasModulePermission('students.manage') ||
                    $u->hasModulePermission('guardians.manage') ||
                    $u->hasModulePermission('teachers.manage'))
                <div class="nav-section-label">บุคคล</div>

                @if ($u->hasModulePermission('students.manage'))
                    <a href="{{ route('students.index') }}"
                        class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                        <i class="bi bi-mortarboard"></i> จัดการนักเรียน
                    </a>
                @endif

                @if ($u->hasModulePermission('guardians.manage'))
                    <a href="{{ route('guardians.index') }}"
                        class="nav-link {{ request()->routeIs('guardians.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> จัดการผู้ปกครอง
                    </a>
                @endif

                @if ($u->hasModulePermission('teachers.manage'))
                    <a href="{{ route('teachers.index') }}"
                        class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i> จัดการอาจารย์
                    </a>
                @endif
            @endif


            {{-- ==================== หลักสูตรและตารางเรียน ==================== --}}
            @if (
                $u->hasModulePermission('courses.manage') ||
                    $u->hasModulePermission('rooms.manage') ||
                    $u->hasModulePermission('schedules.manage'))
                <div class="nav-section-label">หลักสูตรและตารางเรียน</div>

                @if ($u->hasModulePermission('courses.manage'))
                    <a href="{{ route('courses.index') }}"
                        class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark"></i> จัดการคอร์สเรียน
                    </a>
                @endif

                @if ($u->hasModulePermission('rooms.manage'))
                    <a href="{{ route('rooms.index') }}"
                        class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                        <i class="bi bi-door-open"></i> จัดการห้องเรียน
                    </a>
                @endif

                @if ($u->hasModulePermission('schedules.manage'))
                    <a href="{{ route('schedules.index') }}"
                        class="nav-link {{ request()->routeIs('schedules.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i> จัดตารางเรียน
                    </a>

                    {{-- <a href="{{ route('schedule.index') }}"
                        class="nav-link {{ request()->routeIs('schedule.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-week"></i> ตารางเรียน
                    </a> --}}
                @endif
            @endif


            {{-- ==================== การเรียนการสอน ==================== --}}
            @if ($u->isAdmin() || $u->hasModulePermission('courses.manage'))
                <div class="nav-section-label">การเรียนการสอน</div>

                @if ($u->isAdmin())
                    <a href="{{ route('teaching-logs.index') }}"
                        class="nav-link {{ request()->routeIs('teaching-logs.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-check"></i> บันทึกการสอน
                    </a>

                    <a href="{{ route('homework-submissions.index') }}"
                        class="nav-link {{ request()->routeIs('homework-submissions.index') ? 'active' : '' }}">
                        <i class="bi bi-pencil-square"></i> ตรวจการบ้าน
                    </a>

                    <a href="{{ route('run-throughs.index') }}"
                        class="nav-link {{ request()->routeIs('run-throughs.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-repeat"></i> Run Through
                    </a>
                @endif

                @if ($u->hasModulePermission('courses.manage'))
                    <a href="{{ route('evaluation-categories.index') }}"
                        class="nav-link {{ request()->routeIs('evaluation-categories.*') ? 'active' : '' }}">
                        <i class="bi bi-list-check"></i> หมวดหมู่ประเมินผล
                    </a>
                @endif
            @endif


            {{-- ==================== คำร้องและการเปลี่ยนแปลง ==================== --}}
            @if ($u->hasModulePermission('makeup_reschedule.manage') || $u->hasModulePermission('teachers.manage'))
                <div class="nav-section-label">คำร้องและการเปลี่ยนแปลง</div>

                @if ($u->hasModulePermission('makeup_reschedule.manage'))
                    <a href="{{ route('reschedule-requests.index') }}"
                        class="nav-link {{ request()->routeIs('reschedule-requests.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-left-right"></i> สลับคลาส
                    </a>
                @endif

                @if ($u->hasModulePermission('teachers.manage'))
                    <a href="{{ route('teacher-leaves.index') }}"
                        class="nav-link {{ request()->routeIs('teacher-leaves.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-x"></i> คำขอลาหยุดสอน
                    </a>
                @endif

                @if ($u->hasModulePermission('makeup_reschedule.manage'))
                    <a href="{{ route('makeup-requests.index') }}"
                        class="nav-link {{ request()->routeIs('makeup-requests.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-plus"></i> จัดการเรียนชดเชย
                    </a>
                @endif
            @endif


            {{-- ==================== งานขาย ==================== --}}
            @if (
                $u->hasModulePermission('sales.manage') ||
                    $u->hasModulePermission('course_transfers.manage') ||
                    $u->hasModulePermission('promotions.manage') ||
                    $u->hasModulePermission('membership.manage'))
                <div class="nav-section-label">งานขาย</div>

                @if ($u->hasModulePermission('sales.manage'))
                    <a href="{{ route('sales.index') }}"
                        class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                        <i class="bi bi-cart-check"></i> ระบบขายคอร์สเรียน
                    </a>
                @endif

                @if ($u->hasModulePermission('course_transfers.manage'))
                    <a href="{{ route('course-transfers.index') }}"
                        class="nav-link {{ request()->routeIs('course-transfers.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-left-right"></i> เปลี่ยนคอร์สเรียน
                    </a>
                @endif

                @if ($u->hasModulePermission('promotions.manage'))
                    <a href="{{ route('promotions.index') }}"
                        class="nav-link {{ request()->routeIs('promotions.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i> โปรโมชัน / คูปอง
                    </a>
                @endif

                @if ($u->hasModulePermission('membership.manage'))
                    <a href="{{ route('membership-tiers.index') }}"
                        class="nav-link {{ request()->routeIs('membership-tiers.*') ? 'active' : '' }}">
                        <i class="bi bi-award"></i> ระดับสมาชิก
                    </a>
                @endif
            @endif


            {{-- ==================== Music Store ==================== --}}
            @if ($u->hasModulePermission('products.manage') || $u->hasModulePermission('store_sales.manage'))
                <div class="nav-section-label">Music Store</div>

                @if ($u->hasModulePermission('products.manage'))
                    <a href="{{ route('products.index') }}"
                        class="nav-link {{ request()->routeIs('products.*') || request()->routeIs('product-categories.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> จัดการสินค้า
                    </a>

                    <a href="{{ route('stock.index') }}"
                        class="nav-link {{ request()->routeIs('stock.*') ? 'active' : '' }}">
                        <i class="bi bi-boxes"></i> ระบบสต็อกสินค้า
                    </a>
                @endif

                @if ($u->hasModulePermission('store_sales.manage'))
                    <a href="{{ route('store-sales.index') }}"
                        class="nav-link {{ request()->routeIs('store-sales.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i> ขายสินค้า
                    </a>
                @endif
            @endif


            {{-- ==================== การเงิน ==================== --}}
            @if (
                $u->hasModulePermission('finance.manage') ||
                    $u->hasModulePermission('payroll.manage') ||
                    $u->hasModulePermission('transport_fees.manage'))
                <div class="nav-section-label">การเงิน</div>

                @if ($u->hasModulePermission('finance.manage'))
                    <a href="{{ route('finance.dashboard') }}"
                        class="nav-link {{ request()->routeIs('finance.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow"></i> ภาพรวมการเงิน
                    </a>

                    <a href="{{ route('expenses.index') }}"
                        class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt-cutoff"></i> บันทึกรายจ่าย
                    </a>

                    <a href="{{ route('finance.report') }}"
                        class="nav-link {{ request()->routeIs('finance.report') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line"></i> รายงานการเงิน
                    </a>
                @endif

                @if ($u->hasModulePermission('payroll.manage'))
                    <a href="{{ route('payroll.index') }}"
                        class="nav-link {{ request()->routeIs('payroll.*') && !request()->routeIs('payroll.my-index') ? 'active' : '' }}">
                        <i class="bi bi-cash-stack"></i> เงินเดือนอาจารย์
                    </a>
                @endif

                @if ($u->hasModulePermission('transport_fees.manage'))
                    <a href="{{ route('transport-fees.index') }}"
                        class="nav-link {{ request()->routeIs('transport-fees.*') && !request()->routeIs('transport-fees.my-index') ? 'active' : '' }}">
                        <i class="bi bi-car-front"></i> ค่ารถอาจารย์
                    </a>
                @endif
            @endif


            {{-- ==================== รายงาน ==================== --}}
            @if ($u->hasModulePermission('reports.view'))
                <div class="nav-section-label">รายงาน</div>

                <a href="{{ route('reports.index') }}"
                    class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> รายงาน
                </a>
            @endif


            {{-- ==================== ระบบ ==================== --}}
            @if ($u->isAdmin() || $u->hasModulePermission('audit_logs.view'))
                <div class="nav-section-label">ระบบ</div>

                @if ($u->isAdmin())
                    <a href="{{ route('users.index') }}"
                        class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> จัดการผู้ใช้งานระบบ
                    </a>

                    <a href="{{ route('role-permissions.index') }}"
                        class="nav-link {{ request()->routeIs('role-permissions.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-lock"></i> จัดการสิทธิ์
                    </a>
                @endif

                @if ($u->hasModulePermission('audit_logs.view'))
                    <a href="{{ route('audit-logs.index') }}"
                        class="nav-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> ประวัติการใช้งาน
                    </a>
                @endif
            @endif
        @else
            {{-- ==================== เมนูหลัก ==================== --}}
            <div class="nav-section-label">เมนูหลัก</div>

            <a href="{{ route('dashboard') }}"
                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> หน้าหลักของฉัน
            </a>


            {{-- ==================================================
        อาจารย์
    =================================================== --}}
            @if (auth()->user()->isTeacher())
                <div class="nav-section-label">การเรียนการสอน</div>

                <a href="{{ route('teacher.schedule') }}"
                    class="nav-link {{ request()->routeIs('teacher.schedule') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i> ตารางสอนของฉัน
                </a>

                <a href="{{ route('teacher.tasks') }}"
                    class="nav-link {{ request()->routeIs('teacher.tasks') ? 'active' : '' }}">
                    <i class="bi bi-list-check"></i> งานที่ต้องทำ
                    @if (($teacherPendingTaskCount ?? 0) > 0)
                        <span class="badge rounded-pill text-bg-danger ms-auto">{{ $teacherPendingTaskCount > 99 ? '99+' : $teacherPendingTaskCount }}</span>
                    @endif
                </a>

                <a href="{{ route('teacher.students') }}"
                    class="nav-link {{ request()->routeIs('teacher.students') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> นักเรียนของฉัน
                </a>

                <a href="{{ route('teaching-logs.index') }}"
                    class="nav-link {{ request()->routeIs('teaching-logs.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-check"></i> บันทึกการสอนของฉัน
                </a>

                <a href="{{ route('homework-submissions.index') }}"
                    class="nav-link {{ request()->routeIs('homework-submissions.index') ? 'active' : '' }}">
                    <i class="bi bi-pencil-square"></i> ตรวจการบ้าน
                </a>

                <a href="{{ route('run-throughs.index') }}"
                    class="nav-link {{ request()->routeIs('run-throughs.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-repeat"></i> Run Through
                </a>


                <div class="nav-section-label">ตารางและคำร้อง</div>

                <a href="{{ route('reschedule-requests.create') }}"
                    class="nav-link {{ request()->routeIs('reschedule-requests.create') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i> ขอเปลี่ยนตารางสอน
                </a>

                <a href="{{ route('makeup-requests.my-index') }}"
                    class="nav-link {{ request()->routeIs('makeup-requests.my-index') || request()->routeIs('makeup-requests.show') ? 'active' : '' }}">
                    <i class="bi bi-calendar-plus"></i> คำขอสอนชดเชย
                </a>

                <a href="{{ route('teacher-leaves.my-index') }}"
                    class="nav-link {{ request()->routeIs('teacher-leaves.my-index') ? 'active' : '' }}">
                    <i class="bi bi-calendar-x"></i> แจ้งลาหยุดสอน
                </a>


                <div class="nav-section-label">รายได้</div>

                <a href="{{ route('payroll.my-index') }}"
                    class="nav-link {{ request()->routeIs('payroll.my-index') || request()->routeIs('transport-fees.my-index') ? 'active' : '' }}">
                    <i class="bi bi-wallet2"></i> รายได้ของฉัน
                </a>
            @endif


            {{-- ==================================================
        นักเรียน / ผู้ปกครอง
    =================================================== --}}
            @if (auth()->user()->isStudent() || auth()->user()->isGuardian())
                <div class="nav-section-label">
                    {{ auth()->user()->isStudent() ? 'การเรียนของฉัน' : 'การเรียนของนักเรียน' }}
                </div>

                <a href="{{ route('enrollments.my-index') }}"
                    class="nav-link {{ request()->routeIs('enrollments.my-index') ? 'active' : '' }}">
                    <i class="bi bi-journal-bookmark"></i> คอร์สเรียนของฉัน
                </a>

                <a href="{{ route('schedules.my-index') }}"
                    class="nav-link {{ request()->routeIs('schedules.my-index') ? 'active' : '' }}">
                    <i class="bi bi-calendar-week"></i> ตารางเรียนของฉัน
                </a>

                <a href="{{ route('teaching-reports.my-index') }}"
                    class="nav-link {{ request()->routeIs('teaching-reports.my-index') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> ผลการสอน
                </a>

                <a href="{{ route('homework-submissions.my-index') }}"
                    class="nav-link {{ request()->routeIs('homework-submissions.my-index') ? 'active' : '' }}">
                    <i class="bi bi-journal-check"></i> การบ้านของฉัน
                </a>

                <a href="{{ route('run-throughs.my-index') }}"
                    class="nav-link {{ request()->routeIs('run-throughs.my-index') ? 'active' : '' }}">
                    <i class="bi bi-arrow-repeat"></i> Run Through
                </a>

                <a href="{{ route('course-evaluations.my-index') }}"
                    class="nav-link {{ request()->routeIs('course-evaluations.my-index') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-data"></i> ผลประเมินจบคอร์ส
                </a>


                <div class="nav-section-label">การลา</div>

                <a href="{{ route('leaves.index') }}"
                    class="nav-link {{ request()->routeIs('leaves.index') ? 'active' : '' }}">
                    <i class="bi bi-calendar-x"></i> แจ้งลาเรียน
                </a>


                <div class="nav-section-label">Music Store</div>

                <a href="{{ route('store.index') }}"
                    class="nav-link {{ request()->routeIs('store.index') ? 'active' : '' }}">
                    <i class="bi bi-shop"></i> ร้านค้า
                </a>

                <a href="{{ route('store.my-orders') }}"
                    class="nav-link {{ request()->routeIs('store.my-orders') ? 'active' : '' }}">
                    <i class="bi bi-bag-check"></i> คำสั่งซื้อของฉัน
                </a>


                <div class="nav-section-label">สมาชิก / แต้มสะสม</div>

                <a href="{{ route('membership.my-index') }}"
                    class="nav-link {{ request()->routeIs('membership.my-index') ? 'active' : '' }}">
                    <i class="bi bi-award"></i> สมาชิกของฉัน
                </a>

                <a href="{{ route('membership.my-points') }}"
                    class="nav-link {{ request()->routeIs('membership.my-points') ? 'active' : '' }}">
                    <i class="bi bi-star"></i> แต้มสะสมของฉัน
                </a>
            @endif


            {{-- ==================== ส่วนตัว ==================== --}}
            <div class="nav-section-label">ส่วนตัว</div>

            <a href="{{ route('notifications.index') }}"
                class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> การแจ้งเตือน
            </a>

        @endif
    </aside>

    <div class="main-wrap">
        <div class="topbar">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="ค้นหาอาจารย์, คอร์ส...">
                <span class="kbd">⌘K</span>
            </div>
            <div class="ms-auto d-flex align-items-center gap-3">
                @php $unreadCount = \App\Models\AppNotification::unreadForUser(auth()->user())->count(); @endphp
                <a href="{{ route('notifications.index') }}" class="position-relative text-muted"
                    style="text-decoration:none;">
                    <i class="bi bi-bell"></i>
                    @if ($unreadCount > 0)
                        <span class="position-absolute badge rounded-pill bg-danger"
                            style="top:-6px; right:-8px; font-size:.6rem;">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <div class="dropdown">
                    <button class="topbar-user-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-circle">{{ mb_substr(auth()->user()->displayName(), 0, 1) }}</div>
                        <i class="bi bi-chevron-down chevron"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                        <li>
                            <div class="user-info">
                                <div class="avatar-circle">{{ mb_substr(auth()->user()->displayName(), 0, 1) }}</div>
                                <div>
                                    <div class="name">{{ auth()->user()->displayName() }}</div>
                                    <div class="meta">{{ auth()->user()->roleLabel() }} ·
                                        {{ auth()->user()->email }}</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <hr>
                        </li>
                        <li>
                            <a href="{{ route('password.change') }}" class="dropdown-item">
                                <i class="bi bi-key"></i> เปลี่ยนรหัสผ่าน
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="dropdown-item text-danger w-100 text-start border-0 bg-transparent">
                                    <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="content">
            @if (session('success'))
                @if (session('generated_password'))
                    <div class="alert alert-success">
                        <strong><i class="bi bi-key"></i> รหัสผ่านชั่วคราว:</strong>
                        <code style="font-size:1.1rem;">{{ session('generated_password') }}</code>
                        (อีเมล: {{ session('generated_email') }}) — คัดลอกเก็บไว้ให้ดี จะไม่แสดงซ้ำอีก
                    </div>
                @endif
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>

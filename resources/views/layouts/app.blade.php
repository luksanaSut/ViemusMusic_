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
            color: #8f887e;
            font-size: .78rem;
            text-decoration: none;
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

        <div class="nav-section-label">ภาพรวม</div>
        <a href="{{ route('teachers.index') }}" class="nav-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>

        <div class="nav-section-label">บุคคล</div>
        <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="bi bi-mortarboard"></i> จัดการนักเรียน
        </a>
        <a href="{{ route('guardians.index') }}"
            class="nav-link {{ request()->routeIs('guardians.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> จัดการผู้ปกครอง
        </a>
        <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> จัดการอาจารย์
        </a>

        <div class="nav-section-label">งานวิชาการ</div>
        <a href="{{ route('courses.index') }}"
            class="nav-link {{ request()->routeIs('courses.*') || request()->routeIs('coupons.*') ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark"></i> จัดการคอร์สเรียน
        </a>
        <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
            <i class="bi bi-door-open"></i> จัดการห้องเรียน
        </a>
        <a href="{{ route('schedules.index') }}"
            class="nav-link {{ request()->routeIs('schedules.*') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i> จัดตารางเรียน
        </a>
        <a href="{{ route('schedule.index') }}"
            class="nav-link {{ request()->routeIs('schedule.*') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i> ตารางเรียน
        </a>


        <div class="nav-section-label">งานขาย</div>
        <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
            <i class="bi bi-cart-check"></i> ระบบขายคอร์สเรียน
        </a>

        <div class="sidebar-footer">
            <div class="avatar-sm">A</div>
            <div class="who">
                <div>ผู้ดูแลระบบ</div>
                <div class="role">Admin</div>
            </div>
        </div>
    </aside>

    <div class="main-wrap">
        <div class="topbar">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="ค้นหาอาจารย์, คอร์ส...">
                <span class="kbd">⌘K</span>
            </div>
            <div class="ms-auto d-flex align-items-center gap-3">
                <i class="bi bi-bell text-muted"></i>
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar-sm"
                        style="background:var(--accent-soft);color:var(--accent-dark);width:30px;height:30px;font-size:.7rem;">
                        A</div>
                </div>
            </div>
        </div>

        <div class="content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
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

@extends('layouts.app')
@section('title', 'การแจ้งเตือน')

@section('content')
    <style>
        .notification-head { display:flex; justify-content:space-between; align-items:flex-end; gap:1rem; margin-bottom:1.1rem; }
        .notification-tabs { display:flex; align-items:center; gap:.35rem; }
        .notification-tabs a { padding:.42rem .8rem; border-radius:999px; color:var(--muted); text-decoration:none; font-size:.82rem; }
        .notification-tabs a.active { background:var(--accent); color:#fff; }
        .notification-shell { overflow:hidden; border:1px solid var(--border); border-radius:20px; background:var(--card); box-shadow:0 10px 30px rgba(28,26,23,.05); }
        .notification-row { position:relative; display:grid; grid-template-columns:64px minmax(0,1fr) 40px; gap:1rem; padding:1.25rem 1.4rem; border-bottom:1px solid var(--border); transition:.15s ease; }
        .notification-row:last-child { border-bottom:0; }
        .notification-row:hover { background:var(--surface); }
        .notification-row.unread { background:linear-gradient(90deg,var(--accent-soft),var(--card) 32%); }
        .notification-icon { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; }
        .notification-icon.leave { background:#fff7e5; color:#b75b0b; }
        .notification-icon.payment { background:#fff0f1; color:#c62345; }
        .notification-icon.makeup { background:#f1efff; color:#6d32df; }
        .notification-icon.schedule { background:#eef2fa; color:#173b68; }
        .notification-icon.homework { background:#eef8f3; color:#247153; }
        .notification-icon.general { background:var(--accent-soft); color:var(--accent); }
        .notification-link { color:inherit; text-decoration:none; min-width:0; }
        .notification-link:hover { color:inherit; }
        .notification-title { font-family:'Prompt',sans-serif; font-weight:700; font-size:.98rem; color:var(--ink); line-height:1.35; }
        .unread-dot { display:inline-block; width:9px; height:9px; border-radius:50%; background:#f04464; margin-left:.4rem; vertical-align:middle; }
        .notification-message { color:#4f5d7a; font-size:.9rem; margin-top:.25rem; line-height:1.5; }
        .notification-meta { display:flex; align-items:center; gap:.9rem; flex-wrap:wrap; color:var(--muted); font-size:.75rem; margin-top:.55rem; }
        .notification-meta span { display:inline-flex; align-items:center; gap:.3rem; }
        .notification-meta .read-state { color:#20a573; }
        .notification-action { width:34px; height:34px; border:0; border-radius:50%; background:transparent; color:#53617d; display:flex; align-items:center; justify-content:center; text-decoration:none; }
        .notification-action:hover { background:#eef0f4; color:var(--ink); }
        .notification-empty { padding:4.5rem 1rem; text-align:center; color:var(--muted); }
        .notification-empty .empty-icon { width:68px; height:68px; border-radius:20px; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; background:var(--accent-soft); color:var(--accent); font-size:1.8rem; }
        .notification-footer { display:flex; justify-content:center; padding:1rem; border-top:1px solid var(--border); }
        @media(max-width:767.98px) {
            .notification-head { align-items:flex-start; flex-direction:column; }
            .notification-head form { width:100%; }
            .notification-head form .btn { width:100%; }
            .notification-row { grid-template-columns:46px minmax(0,1fr) 28px; gap:.7rem; padding:1rem .85rem; }
            .notification-icon { width:42px; height:42px; border-radius:12px; font-size:1.05rem; }
            .notification-title { font-size:.88rem; }
            .notification-message { font-size:.82rem; }
            .notification-meta { gap:.55rem; }
        }
    </style>

    <div class="notification-head">
        <div>
            <div class="breadcrumb-sm">บัญชีของฉัน <i class="bi bi-chevron-right small"></i> การแจ้งเตือน</div>
            <h1 class="page-title mb-1"><i class="bi bi-bell"></i> การแจ้งเตือน</h1>
            <div class="notification-tabs mt-2">
                <a href="{{ route('notifications.index') }}" class="{{ $filter === 'all' ? 'active' : '' }}">ทั้งหมด</a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" class="{{ $filter === 'unread' ? 'active' : '' }}">ยังไม่อ่าน @if($unreadCount)<span class="ms-1">{{ $unreadCount }}</span>@endif</a>
            </div>
        </div>
        @if($unreadCount)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">@csrf
                <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-check2-all"></i> อ่านแล้วทั้งหมด</button>
            </form>
        @endif
    </div>

    <div class="notification-shell">
        @forelse($notifications as $n)
            @php
                $text = mb_strtolower($n->title.' '.$n->message);
                $containsAny = fn(array $words) => collect($words)->contains(fn($word) => str_contains($text, $word));
                [$kind,$icon] = match(true) {
                    $containsAny(['คำขอลา','ลาเรียน','ลาหยุด','แจ้งลา']) => ['leave','bi-file-earmark-check'],
                    $containsAny(['ชำระ','เงิน','ยอด','ใบแจ้งหนี้']) => ['payment','bi-credit-card'],
                    str_contains($text,'ชดเชย') => ['makeup','bi-clock-history'],
                    str_contains($text,'การบ้าน') => ['homework','bi-journal-check'],
                    str_contains($text,'ตาราง') || str_contains($text,'คลาส') || str_contains($text,'นัด') => ['schedule','bi-calendar3'],
                    default => ['general','bi-bell'],
                };
            @endphp
            <article class="notification-row {{ !$n->is_read ? 'unread' : '' }}">
                <div class="notification-icon {{ $kind }}"><i class="bi {{ $icon }}"></i></div>
                <a href="{{ route('notifications.read', $n) }}" class="notification-link">
                    <div class="notification-title">{{ $n->title }}@if(!$n->is_read)<span class="unread-dot" title="ยังไม่อ่าน"></span>@endif</div>
                    <div class="notification-message">{{ $n->message }}</div>
                    <div class="notification-meta">
                        <span><i class="bi bi-clock"></i> {{ $n->created_at->diffForHumans() }}</span>
                        <span><i class="bi bi-bell"></i> ระบบ</span>
                        @if($n->is_read)<span class="read-state"><i class="bi bi-check2"></i> อ่านแล้ว</span>@else<span><i class="bi bi-circle-fill" style="font-size:.4rem"></i> ใหม่</span>@endif
                    </div>
                </a>
                <a href="{{ route('notifications.read', $n) }}" class="notification-action" title="เปิดรายละเอียด" aria-label="เปิดการแจ้งเตือน"><i class="bi bi-three-dots"></i></a>
            </article>
        @empty
            <div class="notification-empty"><div class="empty-icon"><i class="bi {{ $filter === 'unread' ? 'bi-check2-circle' : 'bi-bell-slash' }}"></i></div><h5>{{ $filter === 'unread' ? 'อ่านครบแล้ว' : 'ยังไม่มีการแจ้งเตือน' }}</h5><p class="small mb-0">{{ $filter === 'unread' ? 'ไม่มีรายการใหม่ที่ต้องตรวจสอบ' : 'เมื่อมีความเคลื่อนไหว รายการจะแสดงที่นี่' }}</p></div>
        @endforelse
        @if($notifications->hasPages())<div class="notification-footer">{{ $notifications->links() }}</div>@endif
    </div>
@endsection

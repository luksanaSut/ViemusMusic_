@extends('layouts.app')
@section('title', 'การแจ้งเตือน')

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h1 class="page-title"><i class="bi bi-bell"></i> การแจ้งเตือน</h1>
        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
            @csrf
            <button class="btn btn-outline-secondary btn-sm">ทำเครื่องหมายอ่านแล้วทั้งหมด</button>
        </form>
    </div>

    <div class="card">
        <div class="list-group list-group-flush">
            @forelse($notifications as $n)
                <a href="{{ route('notifications.read', $n) }}"
                    class="list-group-item list-group-item-action {{ !$n->is_read ? 'bg-light' : '' }}">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $n->title }}</strong>
                        <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="text-muted small">{{ $n->message }}</div>
                </a>
            @empty
                <div class="text-center text-muted py-5">ยังไม่มีการแจ้งเตือน</div>
            @endforelse
        </div>
        <div class="card-body">{{ $notifications->links() }}</div>
    </div>
@endsection

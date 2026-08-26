@php
    $log = $s->teachingLog;
    $status = $log->attendance_status ?? null;
    $filterKey = $s->uiBucket;
    $statusLabel = match (true) {
        $filterKey === 'leave' && !$status => 'ลา',
        (bool) $status => $log->attendanceStatusLabel(),
        default => 'รอเช็คชื่อ',
    };
    $searchText = mb_strtolower(($s->enrollment->student->full_name ?? '') . ' ' . ($s->enrollment->course->name ?? '') . ' ' . ($s->enrollment->course->course_code ?? ''));
@endphp
<div class="work-row" data-branch="{{ $s->teacher->branch ?? '' }}" data-status="{{ $filterKey }}"
    data-search="{{ $searchText }}">
    <div class="time-chip">
        <div class="t">{{ $s->start_time }}</div>
        <div class="d">{{ $s->schedule_date->isToday() ? 'วันนี้' : $s->schedule_date->format('d/m') }}</div>
    </div>
    <div class="avatar-md">{{ mb_substr($s->enrollment->student->full_name ?? '-', 0, 1) }}</div>
    <div class="flex-grow-1" style="min-width:200px;">
        <div class="name-line">{{ $s->enrollment->student->full_name ?? '-' }}
            <span class="code">· {{ $s->enrollment->course->course_code ?? $s->enrollment->course->name ?? '-' }}</span>
        </div>
        <div class="meta-line">
            @if ($s->teacher->branch)
                <span>{{ $s->teacher->branch }}</span> <span>·</span>
            @endif
            <span class="meta-tag">
                <i
                    class="bi {{ $s->delivery_mode === 'online' ? 'bi-wifi' : ($s->delivery_mode === 'hybrid' ? 'bi-shuffle' : 'bi-building') }}"></i>
                {{ $s->room->name ?? 'ออนไลน์' }}
            </span>
            @if ($tab === 'pending')
                @if (!is_null($s->enrollment?->remainingSessions()))
                    <span>·</span> <span>คงเหลือ {{ $s->enrollment->remainingSessions() }} ครั้ง</span>
                @endif
            @else
                <span>·</span> <span>{{ $log->durationLabel() }}</span>
            @endif
            @if ($s->isMakeup)
                <span>·</span>
                <span class="meta-tag"><i class="bi bi-arrow-repeat"></i> ชดเชย</span>
            @endif
        </div>
    </div>
    <span class="status-dot st-{{ $filterKey }}">{{ $statusLabel }}</span>
    @if ($tab === 'pending')
        <a href="{{ route('teaching-logs.show', $s) }}"
            class="btn btn-sm {{ $status ? 'btn-outline-secondary' : 'btn-accent' }}">
            <i class="bi {{ $status ? 'bi-pencil' : 'bi-check-lg' }}"></i>
            {{ $status ? 'แก้ไข' : 'เช็คชื่อ' }}
        </a>
    @else
        <a href="{{ route('teaching-logs.show', $s) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-eye"></i> ดู
        </a>
    @endif
</div>

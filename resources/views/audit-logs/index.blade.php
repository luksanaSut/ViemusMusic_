@extends('layouts.app')
@section('title', 'ประวัติการใช้งาน')

@section('content')
    <style>
        .meta-pre {
            font-size: .75rem;
            white-space: pre-wrap;
            word-break: break-word;
            background: #f8f7f5;
            border-radius: 8px;
            padding: .6rem .8rem;
            margin: 0;
            max-height: 200px;
            overflow-y: auto;
        }
    </style>

    <div class="breadcrumb-sm mb-2">
        ระบบ
        <i class="bi bi-chevron-right small"></i>
        ประวัติการใช้งาน
    </div>

    <div class="mb-4">
        <h1 class="page-title mb-1">
            <i class="bi bi-clock-history me-1"></i>
            ประวัติการใช้งาน (Audit Log)
        </h1>
        <div class="page-sub">
            บันทึกการเปลี่ยนแปลงข้อมูลทั้งหมดในระบบโดยอัตโนมัติ
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small">ผู้ใช้งาน</label>
                    <select name="user_id" class="form-select">
                        <option value="">ทุกคน</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>
                                {{ $u->name }} ({{ $u->roleLabel() }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">จากวันที่</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">ถึงวันที่</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">ค้นหา (path / route / ชื่อผู้ใช้)</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="เช่น students, sales.store">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary w-100"><i class="bi bi-search"></i> ค้นหา</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="fw-semibold">
                    <i class="bi bi-list-ul me-1 text-primary"></i>
                    รายการทั้งหมด
                </div>
                <span class="badge text-bg-light border">{{ number_format($logs->total()) }} รายการ</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">เวลา</th>
                        <th>ผู้ใช้งาน</th>
                        <th>การกระทำ</th>
                        <th>IP</th>
                        <th class="text-end pe-3">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td class="ps-3 small">{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                            <td>
                                @if ($log->user_name)
                                    <div class="fw-semibold small">{{ $log->user_name }}</div>
                                    <span class="badge text-bg-light border small">{{ $log->user_role }}</span>
                                @else
                                    <span class="text-muted small">ไม่ระบุ (guest)</span>
                                @endif
                            </td>
                            <td>
                                <div class="small">
                                    <span class="badge text-bg-secondary">{{ $log->method }}</span>
                                    <code class="small">/{{ $log->path }}</code>
                                </div>
                                @if ($log->route_name)
                                    <div class="text-muted small mt-1">{{ $log->route_name }}</div>
                                @endif
                                @if ($log->meta)
                                    <details class="mt-1">
                                        <summary class="small text-muted" style="cursor:pointer;">ดูรายละเอียด</summary>
                                        <pre class="meta-pre">{{ json_encode($log->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </details>
                                @endif
                            </td>
                            <td class="small">{{ $log->ip_address }}</td>
                            <td class="text-end pe-3">
                                <span class="badge {{ $log->status_code < 400 ? 'text-bg-success' : 'text-bg-danger' }}">
                                    {{ $log->status_code }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-clock-history fs-1 text-secondary"></i>
                                <div class="fw-semibold mt-2">ยังไม่มีประวัติการใช้งาน</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <div class="text-muted small">
                        แสดง {{ $logs->firstItem() }} - {{ $logs->lastItem() }} จาก {{ $logs->total() }} รายการ
                    </div>
                    <div>{{ $logs->links() }}</div>
                </div>
            </div>
        @endif
    </div>

@endsection

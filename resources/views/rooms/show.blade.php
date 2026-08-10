@extends('layouts.app')
@section('title', $room->name)

@section('content')
    <style>
        .form-section {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.4rem 1.6rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 2px rgba(28, 26, 23, .04);
            transition: box-shadow .2s;
        }

        .form-section:hover {
            box-shadow: 0 4px 14px rgba(28, 26, 23, .06);
        }

        .form-section-title {
            display: flex;
            align-items: center;
            gap: .7rem;
            font-weight: 700;
            font-size: 1.02rem;
            margin-bottom: 1.2rem;
            padding-bottom: .9rem;
            border-bottom: 1px solid var(--border, #e4e1dc);
            font-family: 'Prompt', sans-serif;
            color: var(--ink, #1c1a17);
        }

        .form-section-title .icon-badge {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent-dark, #13233a);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .form-label {
            font-size: .82rem;
            font-weight: 600;
            color: #40382f;
            margin-bottom: .35rem;
        }

        .form-control,
        .form-select {
            border-color: var(--border, #e4e1dc);
            font-size: .9rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent, #1f3350);
            box-shadow: 0 0 0 .2rem rgba(31, 51, 80, .1);
        }

        .table-clean thead th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--muted, #6b655e);
            border-top: 0;
        }

        .table-clean td,
        .table-clean th {
            vertical-align: middle;
        }

        .empty-state {
            text-align: center;
            padding: 2.2rem 1rem;
            color: var(--muted, #6b655e);
        }

        .empty-state i {
            font-size: 1.8rem;
            opacity: .5;
            display: block;
            margin-bottom: .5rem;
        }

        .profile-cover {
            height: 110px;
            border-radius: 14px 14px 0 0;
            background: linear-gradient(135deg, #1c1a17, var(--accent, #1f3350));
            position: relative;
            display: flex;
            align-items: flex-end;
            padding: 1rem 1.4rem;
        }

        .profile-cover .room-icon-badge {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: #fff;
            border: 4px solid #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            left: 24px;
            bottom: -32px;
            font-size: 1.6rem;
            color: var(--accent-dark, #13233a);
        }

        .stat-card {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 14px;
            padding: 1.1rem 1.3rem;
            display: flex;
            align-items: center;
            gap: .9rem;
            height: 100%;
            box-shadow: 0 1px 2px rgba(28, 26, 23, .04);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .booking-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: .75rem 0;
            border-bottom: 1px solid #f0efec;
        }

        .booking-row:last-child {
            border-bottom: 0;
        }

        .booking-time {
            min-width: 110px;
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            color: var(--accent-dark, #13233a);
            font-size: .88rem;
        }

        .booking-info {
            flex: 1;
        }

        .booking-info .purpose {
            font-weight: 600;
            font-size: .9rem;
        }

        .booking-info .meta {
            font-size: .78rem;
            color: var(--muted, #6b655e);
        }

        .equip-item {
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 12px;
            padding: .7rem 1rem;
            display: flex;
            align-items: center;
            gap: .7rem;
            background: #faf9f7;
            margin-bottom: .5rem;
        }

        .equip-item .icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: var(--accent-soft, #e7ebf1);
            color: var(--accent-dark, #13233a);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            flex-shrink: 0;
        }

        .equip-item .name {
            font-weight: 600;
            flex: 1;
        }

        .equip-item .qty {
            font-size: .8rem;
            color: var(--muted, #6b655e);
        }

        .alert-maintenance {
            background: #fdf1e2;
            border: 1px solid #e6d9c3;
            color: #8a5a2b;
            border-radius: 12px;
            padding: 1rem 1.2rem;
            display: flex;
            gap: .8rem;
            align-items: flex-start;
        }

        #equipmentPicker {
            background: #faf9f7;
            border: 1px solid var(--border, #e4e1dc) !important;
            border-radius: 12px;
            padding: .7rem !important;
        }

        #roomEquipmentDropdown {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border, #e4e1dc);
        }

        #roomEquipmentDropdown .list-group-item {
            border: 0;
            border-bottom: 1px solid #f0efec;
        }

        #roomEquipmentDropdown .list-group-item:hover {
            background: var(--accent-soft, #e7ebf1);
        }
    </style>

    {{-- ===== Header ===== --}}
    <div class="card mb-3 overflow-hidden">
        <div class="profile-cover">
            <div class="room-icon-badge"><i class="bi bi-door-open"></i></div>
            <div class="ms-auto d-flex gap-2">
                <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i> แก้ไข</a>
                <a href="{{ route('rooms.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
            </div>
        </div>
        <div class="card-body pt-5 mt-2">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <h4 class="mb-0" style="font-family:'Prompt',sans-serif;">{{ $room->name }}</h4>
                <span class="text-muted small">({{ $room->room_code }})</span>
            </div>
            <div class="mt-2 d-flex flex-wrap gap-1">
                <span class="badge {{ $room->statusBadgeClass() }}">{{ $room->statusLabel() }}</span>
                @if ($room->location)
                    <span class="badge text-bg-light border"><i class="bi bi-geo-alt"></i> {{ $room->location }}</span>
                @endif
            </div>
            @if ($room->description)
                <p class="text-muted small mt-2 mb-0">{{ $room->description }}</p>
            @endif
        </div>
    </div>

    {{-- ===== สถิติสรุป ===== --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--accent-soft,#e7ebf1);color:var(--accent-dark,#13233a);"><i
                        class="bi bi-people"></i></div>
                <div>
                    <div class="text-muted small">ความจุห้อง</div>
                    <div class="fs-4 fw-bold" style="font-family:'Prompt',sans-serif;">{{ $room->capacity }} <span
                            class="fs-6 fw-normal text-muted">คน</span></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--success-soft,#e9f9ef);color:var(--success,#2f6f4e);"><i
                        class="bi bi-calendar-check"></i></div>
                <div>
                    <div class="text-muted small">คาบที่จองวันนี้</div>
                    <div class="fs-4 fw-bold" style="font-family:'Prompt',sans-serif;">{{ $room->todayBookingsCount() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:#efe6da;color:#6b4a2b;"><i class="bi bi-tools"></i></div>
                <div>
                    <div class="text-muted small">อุปกรณ์ในห้อง</div>
                    <div class="fs-4 fw-bold" style="font-family:'Prompt',sans-serif;">{{ $room->equipment->count() }}
                        <span class="fs-6 fw-normal text-muted">รายการ</span></div>
                </div>
            </div>
        </div>
    </div>

    @if ($room->is_under_maintenance)
        <div class="alert-maintenance mb-3">
            <i class="bi bi-tools fs-5"></i>
            <div>
                <strong>ห้องนี้ปิดปรับปรุงอยู่</strong> — {{ $room->maintenance_reason }}
                <div class="small">{{ optional($room->maintenance_from)->format('d/m/Y') }} ถึง
                    {{ optional($room->maintenance_to)->format('d/m/Y') ?: 'ไม่ระบุ' }}</div>
            </div>
        </div>
    @endif

    {{-- ===== แท็บ ===== --}}
    <ul class="nav tab-pills mb-3">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#booking"><i
                    class="bi bi-calendar-plus"></i> จองห้อง / ตรวจสอบห้องว่าง</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#equipment"><i
                    class="bi bi-tools"></i> อุปกรณ์ภายในห้อง</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#maintenance"><i
                    class="bi bi-wrench"></i> ปิดปรับปรุง</button></li>
    </ul>

    <div class="tab-content">

        {{-- จองห้อง / ตรวจห้องว่าง --}}
        <div class="tab-pane fade show active" id="booking">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-calendar-plus"></i></div> จองห้องเรียน
                </div>
                <form action="{{ route('rooms.bookings.store', $room) }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-2"><input type="date" name="booking_date" class="form-control form-control-sm"
                            required></div>
                    <div class="col-md-2"><input type="time" name="start_time" class="form-control form-control-sm"
                            required></div>
                    <div class="col-md-2"><input type="time" name="end_time" class="form-control form-control-sm"
                            required></div>
                    <div class="col-md-2"><input type="number" name="attendees_count" class="form-control form-control-sm"
                            min="1" max="{{ $room->capacity }}" placeholder="จำนวนคน" required></div>
                    <div class="col-md-3"><input type="text" name="purpose" class="form-control form-control-sm"
                            placeholder="วัตถุประสงค์"></div>
                    <div class="col-md-1 d-grid"><button class="btn btn-sm btn-accent"><i
                                class="bi bi-plus-lg"></i></button></div>
                </form>
                <div class="d-flex align-items-center gap-1 mt-2" style="font-size:.78rem; color:var(--muted,#6b655e);">
                    <i class="bi bi-info-circle"></i> ห้องนี้รองรับได้สูงสุด <strong>{{ $room->capacity }} คน</strong>
                    ระบบจะปฏิเสธอัตโนมัติถ้าจำนวนคนเกินความจุ
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-calendar-check"></i></div> ตารางการจองของห้องนี้
                </div>
                <form method="GET" class="row g-2 mb-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">เลือกวันที่ดู</label>
                        <input type="date" name="date" value="{{ $date }}"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2"><button class="btn btn-sm btn-outline-secondary">ดูวันนี้</button></div>
                </form>

                @forelse($bookingsOnDate as $b)
                    <div class="booking-row">
                        <div class="booking-time"><i class="bi bi-clock"></i> {{ $b->start_time }} - {{ $b->end_time }}
                        </div>
                        <div class="booking-info">
                            <div class="purpose">{{ $b->purpose ?: 'ไม่ระบุวัตถุประสงค์' }}</div>
                            <div class="meta">
                                <i class="bi bi-people"></i> {{ $b->attendees_count }} คน
                                @if ($b->teacher)
                                    · <i class="bi bi-person-badge"></i> {{ $b->teacher->full_name }}
                                @endif
                                @if ($b->course)
                                    · <i class="bi bi-journal-bookmark"></i> {{ $b->course->name }}
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('rooms.bookings.cancel', [$room, $b]) }}" method="POST"
                            onsubmit="return confirm('ยกเลิกการจองนี้?')">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i></button>
                        </form>
                    </div>
                @empty
                    <div class="empty-state"><i class="bi bi-calendar2-check"></i>ห้องว่างทั้งวัน ไม่มีการจอง</div>
                @endforelse
            </div>
        </div>

        {{-- อุปกรณ์ --}}
        <div class="tab-pane fade" id="equipment">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-plus-circle"></i></div> เพิ่มอุปกรณ์ในห้อง
                </div>
                <div id="roomEquipmentPicker" class="border rounded p-2 mb-2" style="background:#faf9f7;">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute"
                            style="left:.7rem; top:50%; transform:translateY(-50%); color:var(--muted,#6b655e); font-size:.85rem;"></i>
                        <input type="text" id="roomEquipmentSearch" class="form-control form-control-sm"
                            style="padding-left:2rem;" placeholder="พิมพ์ค้นหาหรือเพิ่มอุปกรณ์ใหม่..."
                            autocomplete="off">
                        <div id="roomEquipmentDropdown" class="list-group position-absolute shadow-sm d-none"
                            style="z-index:20; max-height:200px; overflow-y:auto; width:100%;"></div>
                    </div>
                </div>
                <form action="{{ route('rooms.equipment.store', $room) }}" method="POST" class="row g-2"
                    id="roomEquipmentForm">
                    @csrf
                    <input type="hidden" name="equipment_type_id" id="roomEquipmentIdInput" required>
                    <div class="col-md-3"><input type="number" name="quantity" class="form-control form-control-sm"
                            placeholder="จำนวน" min="1" value="1" required></div>
                    <div class="col-md-4"><input type="text" name="condition" class="form-control form-control-sm"
                            placeholder="สภาพ เช่น ใช้งานได้"></div>
                    <div class="col-md-1 d-grid"><button class="btn btn-sm btn-accent"><i
                                class="bi bi-plus-lg"></i></button></div>
                </form>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-list-check"></i></div> รายการอุปกรณ์
                </div>
                @forelse($room->equipment as $eq)
                    <div class="equip-item">
                        <div class="icon"><i class="bi bi-tools"></i></div>
                        <div class="name">{{ $eq->name }}</div>
                        <div class="qty">จำนวน {{ $eq->pivot->quantity }} ชิ้น @if ($eq->pivot->condition)
                                · {{ $eq->pivot->condition }}
                            @endif
                        </div>
                        <form action="{{ route('rooms.equipment.destroy', [$room, $eq]) }}" method="POST"
                            onsubmit="return confirm('นำอุปกรณ์นี้ออกจากห้อง?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                @empty
                    <div class="empty-state"><i class="bi bi-tools"></i>ยังไม่มีอุปกรณ์ในห้องนี้</div>
                @endforelse
            </div>
        </div>

        {{-- ปิดปรับปรุง --}}
        <div class="tab-pane fade" id="maintenance">
            <div class="form-section">
                <div class="form-section-title">
                    <div class="icon-badge"><i class="bi bi-wrench"></i></div> สถานะการปรับปรุงห้อง
                </div>
                @if ($room->is_under_maintenance)
                    <div class="alert-maintenance mb-3">
                        <i class="bi bi-tools fs-5"></i>
                        <div>
                            <strong>ห้องนี้ปิดปรับปรุงอยู่</strong>: {{ $room->maintenance_reason }}
                            <div class="small">{{ optional($room->maintenance_from)->format('d/m/Y') }} -
                                {{ optional($room->maintenance_to)->format('d/m/Y') ?: 'ไม่ระบุ' }}</div>
                        </div>
                    </div>
                    <form action="{{ route('rooms.maintenance', $room) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="btn btn-accent btn-sm"><i class="bi bi-check-circle"></i>
                            เปิดใช้งานห้องอีกครั้ง</button>
                    </form>
                @else
                    <p class="text-muted small mb-3"><i class="bi bi-info-circle"></i> ใช้ตอนห้องต้องปิดชั่วคราว เช่น
                        ซ่อมแอร์ ทาสีใหม่ ระหว่างนี้จะจองห้องนี้ไม่ได้อัตโนมัติ</p>
                    <form action="{{ route('rooms.maintenance', $room) }}" method="POST" class="row g-2">
                        @csrf @method('PATCH')
                        <div class="col-md-4"><input type="text" name="maintenance_reason"
                                class="form-control form-control-sm" placeholder="เหตุผล เช่น ซ่อมแอร์" required></div>
                        <div class="col-md-3"><input type="date" name="maintenance_from"
                                class="form-control form-control-sm" required></div>
                        <div class="col-md-3"><input type="date" name="maintenance_to"
                                class="form-control form-control-sm" placeholder="ถึงวันที่ (ถ้าทราบ)"></div>
                        <div class="col-md-2 d-grid"><button class="btn btn-sm btn-outline-danger">ปิดปรับปรุง</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script id="roomEquipmentCatalog" type="application/json">
    {!! $equipmentTypes->map(fn($e) => ['id' => $e->id, 'name' => $e->name])->values()->toJson() !!}
</script>
    <script>
        (function() {
            let catalog = JSON.parse(document.getElementById('roomEquipmentCatalog').textContent);
            const searchInput = document.getElementById('roomEquipmentSearch');
            const dropdown = document.getElementById('roomEquipmentDropdown');
            const hiddenInput = document.getElementById('roomEquipmentIdInput');

            function renderDropdown(query) {
                const q = query.trim().toLowerCase();
                const matches = catalog.filter(e => e.name.toLowerCase().includes(q)).slice(0, 8);
                const exactExists = catalog.some(e => e.name.toLowerCase() === q);

                dropdown.innerHTML = '';
                if (q === '' && matches.length === 0) {
                    dropdown.classList.add('d-none');
                    return;
                }

                matches.forEach(e => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'list-group-item list-group-item-action py-1 px-2 small';
                    item.textContent = e.name;
                    item.addEventListener('click', () => select(e));
                    dropdown.appendChild(item);
                });

                if (q !== '' && !exactExists) {
                    const addItem = document.createElement('button');
                    addItem.type = 'button';
                    addItem.className = 'list-group-item list-group-item-action py-1 px-2 small fw-semibold';
                    addItem.style.color = 'var(--accent-dark,#13233a)';
                    addItem.textContent = `+ เพิ่ม "${query.trim()}" เป็นอุปกรณ์ใหม่`;
                    addItem.addEventListener('click', () => addNew(query.trim()));
                    dropdown.appendChild(addItem);
                }
                dropdown.classList.toggle('d-none', matches.length === 0 && (q === '' || exactExists));
            }

            function select(e) {
                hiddenInput.value = e.id;
                searchInput.value = e.name;
                dropdown.classList.add('d-none');
            }

            async function addNew(name) {
                const res = await fetch('{{ route('equipment-types.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name
                    }),
                });
                const body = await res.json();
                if (!res.ok) {
                    alert(body.errors?.name?.[0] || 'เพิ่มอุปกรณ์ไม่สำเร็จ');
                    return;
                }
                catalog.push(body);
                select(body);
            }

            searchInput.addEventListener('input', () => renderDropdown(searchInput.value));
            searchInput.addEventListener('focus', () => renderDropdown(searchInput.value));
            document.addEventListener('click', e => {
                if (!document.getElementById('roomEquipmentPicker').contains(e.target)) dropdown.classList.add(
                    'd-none');
            });
        })();
    </script>
@endsection

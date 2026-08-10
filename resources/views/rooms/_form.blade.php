{{-- ใช้ร่วมกันระหว่าง create.blade.php และ edit.blade.php --}}
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

    .form-section-title .step-no {
        margin-left: auto;
        font-size: .7rem;
        color: var(--muted, #6b655e);
        font-weight: 500;
        letter-spacing: .5px;
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

    .form-check-input:checked {
        background-color: var(--accent, #1f3350);
        border-color: var(--accent, #1f3350);
    }

    .form-switch .form-check-input {
        width: 2.2em;
    }

    .field-hint {
        font-size: .78rem;
        color: var(--muted, #6b655e);
        margin-top: .35rem;
    }

    /* ===== ตัวเลือกอุปกรณ์แบบค้นหา + chip ===== */
    #equipmentPicker {
        background: #faf9f7;
        border: 1px solid var(--border, #e4e1dc) !important;
        border-radius: 12px;
        padding: .7rem !important;
    }

    #equipmentSearch {
        border: 1px solid var(--border, #e4e1dc);
    }

    #equipmentDropdown {
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--border, #e4e1dc);
    }

    #equipmentDropdown .list-group-item {
        border: 0;
        border-bottom: 1px solid #f0efec;
    }

    #equipmentDropdown .list-group-item:hover {
        background: var(--accent-soft, #e7ebf1);
    }

    .equip-chip {
        border: 1px solid var(--border, #e4e1dc);
        border-radius: 12px;
        padding: .5rem .7rem;
        display: flex;
        align-items: center;
        gap: .6rem;
        background: #fff;
        margin-bottom: .5rem;
    }

    .equip-chip .name {
        flex: 1;
        font-weight: 600;
        font-size: .88rem;
    }

    .equip-chip input[type=number] {
        width: 70px;
    }

    .equip-chip .btn-remove {
        color: #b3392c;
        border: 0;
        background: transparent;
    }
</style>

{{-- ===== 1. ข้อมูลห้องเรียน ===== --}}
<div class="form-section">
    <div class="form-section-title">
        <div class="icon-badge"><i class="bi bi-door-open"></i></div>
        ข้อมูลห้องเรียน
        <span class="step-no">ขั้นตอน 1</span>
    </div>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">รหัสห้อง *</label>
            <input type="text" name="room_code" id="roomCode" class="form-control" maxlength="20"
                pattern="[A-Za-z0-9\-]+" value="{{ old('room_code', $room->room_code ?? '') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">ชื่อห้อง *</label>
            <input type="text" name="name" class="form-control" maxlength="150"
                placeholder="เช่น ห้องเปียโน 1, ห้องซ้อมวง" value="{{ old('name', $room->name ?? '') }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">ตำแหน่งที่ตั้ง</label>
            <input type="text" name="location" class="form-control" maxlength="150" placeholder="เช่น ชั้น 2"
                value="{{ old('location', $room->location ?? '') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label"><i class="bi bi-people"></i> ความจุห้อง (คน) *</label>
            <input type="number" name="capacity" class="form-control" min="1" max="500"
                value="{{ old('capacity', $room->capacity ?? '') }}" required>
            <div class="field-hint"><i class="bi bi-info-circle"></i> ระบบจะไม่อนุญาตให้จองเกินจำนวนนี้</div>
        </div>
        <div class="col-md-4 d-flex align-items-center">
            <div class="form-check form-switch mt-4">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1"
                    {{ old('is_active', $room->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label">เปิดใช้งานห้องนี้</label>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label">รายละเอียดเพิ่มเติม</label>
            <textarea name="description" class="form-control" rows="2" maxlength="1000"
                placeholder="เช่น เหมาะสำหรับสอนกลุ่มเล็ก, มีกระจกฝึกท่าทาง">{{ old('description', $room->description ?? '') }}</textarea>
        </div>
    </div>
</div>

@unless (isset($room))
    {{-- ===== 2. อุปกรณ์ภายในห้อง ===== --}}
    <div class="form-section">
        <div class="form-section-title">
            <div class="icon-badge"><i class="bi bi-tools"></i></div>
            อุปกรณ์ภายในห้อง
            <span class="step-no">ขั้นตอนสุดท้าย</span>
        </div>

        <div id="equipmentChips" class="mb-2"></div>

        <div id="equipmentPicker" class="border rounded p-2">
            <div class="position-relative">
                <input type="text" id="equipmentSearch" class="form-control form-control-sm"
                    placeholder="พิมพ์ค้นหาหรือเพิ่มอุปกรณ์ใหม่ เช่น เปียโน, กระดานไวท์บอร์ด..." autocomplete="off">
                <div id="equipmentDropdown" class="list-group position-absolute w-100 shadow-sm d-none"
                    style="z-index:20; max-height:220px; overflow-y:auto; top:100%;"></div>
            </div>
        </div>
        <div id="equipmentHiddenInputs"></div>
        <div class="field-hint">พิมพ์ชื่ออุปกรณ์ที่มีอยู่แล้วเพื่อเลือก หรือพิมพ์ชื่อใหม่แล้วกด "+ เพิ่ม..."
            เพื่อสร้างอุปกรณ์ใหม่ในระบบ</div>
    </div>

    <script id="equipmentTypesCatalog" type="application/json">
    {!! $equipmentTypes->map(fn($e) => ['id' => $e->id, 'name' => $e->name])->values()->toJson() !!}
</script>

    <script>
        (function() {
            let catalog = JSON.parse(document.getElementById('equipmentTypesCatalog').textContent); // [{id,name}]
            let selected = []; // [{id, name, quantity}]

            const chipsBox = document.getElementById('equipmentChips');
            const hiddenBox = document.getElementById('equipmentHiddenInputs');
            const searchBox = document.getElementById('equipmentSearch');
            const dropdown = document.getElementById('equipmentDropdown');

            function renderChips() {
                chipsBox.innerHTML = '';
                selected.forEach((eq, idx) => {
                    const chip = document.createElement('div');
                    chip.className = 'equip-chip';
                    chip.innerHTML = `
                <i class="bi bi-tools text-secondary"></i>
                <span class="name">${eq.name}</span>
                <input type="number" min="1" max="500" class="form-control form-control-sm qty-input" value="${eq.quantity}">
                <span class="text-muted small">ชิ้น</span>
                <button type="button" class="btn-remove"><i class="bi bi-x-lg"></i></button>
            `;
                    chip.querySelector('.qty-input').addEventListener('input', function() {
                        selected[idx].quantity = parseInt(this.value, 10) || 1;
                        renderHiddenInputs();
                    });
                    chip.querySelector('.btn-remove').addEventListener('click', () => {
                        selected.splice(idx, 1);
                        renderChips();
                    });
                    chipsBox.appendChild(chip);
                });
                renderHiddenInputs();
            }

            function renderHiddenInputs() {
                hiddenBox.innerHTML = '';
                selected.forEach((eq, idx) => {
                    hiddenBox.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="equipment[${idx}][equipment_type_id]" value="${eq.id}">
                <input type="hidden" name="equipment[${idx}][quantity]" value="${eq.quantity}">
            `);
                });
            }

            function renderDropdown(query) {
                const q = query.trim().toLowerCase();
                const selectedIds = selected.map(s => s.id);
                const matches = catalog.filter(e => !selectedIds.includes(e.id) && e.name.toLowerCase().includes(q))
                    .slice(0, 8);
                const exactExists = catalog.some(e => e.name.toLowerCase() === q);

                dropdown.innerHTML = '';
                if (q === '' && matches.length === 0) {
                    dropdown.classList.add('d-none');
                    return;
                }

                matches.forEach(eq => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'list-group-item list-group-item-action py-1 px-2 small';
                    item.textContent = eq.name;
                    item.addEventListener('click', () => selectEquipment(eq));
                    dropdown.appendChild(item);
                });

                if (q !== '' && !exactExists) {
                    const addItem = document.createElement('button');
                    addItem.type = 'button';
                    addItem.className = 'list-group-item list-group-item-action py-1 px-2 small fw-semibold';
                    addItem.style.color = 'var(--accent-dark,#13233a)';
                    addItem.textContent = `+ เพิ่ม "${query.trim()}" เป็นอุปกรณ์ใหม่`;
                    addItem.addEventListener('click', () => addNewEquipment(query.trim()));
                    dropdown.appendChild(addItem);
                }

                dropdown.classList.toggle('d-none', matches.length === 0 && (q === '' || exactExists));
            }

            function selectEquipment(eq) {
                if (!selected.some(s => s.id === eq.id)) {
                    selected.push({
                        ...eq,
                        quantity: 1
                    });
                    renderChips();
                }
                searchBox.value = '';
                dropdown.classList.add('d-none');
            }

            async function addNewEquipment(name) {
                try {
                    const res = await fetch('{{ route('equipment-types.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
                    selectEquipment(body);
                } catch (e) {
                    alert('เกิดข้อผิดพลาด กรุณาลองใหม่');
                }
            }

            searchBox.addEventListener('input', () => renderDropdown(searchBox.value));
            searchBox.addEventListener('focus', () => renderDropdown(searchBox.value));
            document.addEventListener('click', e => {
                if (!document.getElementById('equipmentPicker').contains(e.target)) dropdown.classList.add(
                    'd-none');
            });

            renderChips();
        })();
    </script>
@endunless

<script>
    document.getElementById('roomCode').addEventListener('input', function() {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');
    });
</script>

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\ClassSchedule;
use App\Models\Instrument;
use App\Models\Level;
use App\Models\Teacher;
use App\Models\TeacherRate;
use App\Models\TeacherTransportFee;
use App\Models\TeachingType;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    // GET /teachers  -- ค้นหา / กรอง / แสดงรายการอาจารย์
    public function index(Request $request)
    {
        $teachers = Teacher::query()
            ->with(['instruments', 'teachingTypes', 'levels'])
            ->search($request->get('q'))
            ->employmentType($request->get('employment_type'))
            ->teachingType($request->get('teaching_type_id'))
            ->instrument($request->get('instrument_id'))
            ->when($request->filled('is_active'), fn($q) => $q->where('is_active', $request->boolean('is_active')))
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        $teachingTypes = TeachingType::all();
        $instruments   = Instrument::where('is_active', true)->get();

        return view('teachers.index', compact('teachers', 'teachingTypes', 'instruments'));
    }

    // GET /teachers/create
    public function create()
    {
        $instruments      = Instrument::where('is_active', true)->orderBy('name')->get();
        $teachingTypes    = TeachingType::all();
        $levels           = Level::orderBy('sort_order')->get();
        $nextTeacherCode  = $this->generateNextTeacherCode();

        return view('teachers.create', compact('instruments', 'teachingTypes', 'levels', 'nextTeacherCode'));
    }

    // สร้างรหัสอาจารย์ถัดไปอัตโนมัติ รูปแบบ VMT000
    private function generateNextTeacherCode(): string
    {
        $maxNumber = 0;
        foreach (Teacher::where('teacher_code', 'like', 'VMT%')->pluck('teacher_code') as $code) {
            if (preg_match('/^VMT(\d+)$/i', $code, $matches)) {
                $maxNumber = max($maxNumber, (int) $matches[1]);
            }
        }

        return 'VMT' . str_pad((string) ($maxNumber + 1), 4, '0', STR_PAD_LEFT);
    }

    // POST /teachers
    public function store(StoreTeacherRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher = Teacher::create($data);

        $teacher->teachingTypes()->sync($data['teaching_type_ids'] ?? []);
        $teacher->levels()->sync($data['level_ids'] ?? []);

        $instrumentIds = $data['instrument_ids'] ?? [];
        $syncData = [];
        foreach ($instrumentIds as $id) {
            $syncData[$id] = ['is_primary' => $id == ($data['primary_instrument_id'] ?? null)];
        }
        $teacher->instruments()->sync($syncData);

        // เรทค่าจ้างเริ่มต้น (แยกตามเครื่องดนตรีที่เลือก) + เงื่อนไขพิเศษ
        foreach ($data['rates'] as $rateRow) {
            TeacherRate::create([
                'teacher_id'     => $teacher->id,
                'instrument_id'  => $rateRow['instrument_id'] ?? null,
                'rate_type'      => $rateRow['rate_type'],
                'rate_amount'    => $rateRow['rate_amount'],
                'note'           => $data['rate_note'] ?? null,
                'effective_from' => now()->toDateString(),
                'is_active'      => true,
            ]);
        }

        // ค่ารถ (ถ้ามีการกรอก)
        if (!empty($data['transport_fee_amount'])) {
            TeacherTransportFee::create([
                'teacher_id'     => $teacher->id,
                'fee_type'       => $data['transport_fee_type'] ?? 'fixed_per_day',
                'fee_amount'     => $data['transport_fee_amount'],
                'effective_from' => now()->toDateString(),
                'is_active'      => true,
            ]);
        }

        // Availability (เวลาที่พร้อมสอน) — บันทึกเฉพาะวันที่ติ๊กว่าง
        foreach ($data['availabilities'] ?? [] as $row) {
            if (!isset($row['is_available'])) {
                continue; // ข้ามวันที่ไม่ได้ติ๊กว่าง
            }
            $teacher->availabilities()->create([
                'day_of_week'  => $row['day_of_week'],
                'start_time'   => $row['start_time'],
                'end_time'     => $row['end_time'],
                'is_available' => true,
            ]);
        }

        return redirect()->route('teachers.show', $teacher)
            ->with('success', 'เพิ่มข้อมูลอาจารย์เรียบร้อยแล้ว');
    }

    // GET /teachers/{teacher}
    public function show(Request $request, Teacher $teacher)
    {
        $teacher->load(['instruments', 'teachingTypes', 'levels', 'rates', 'transportFees', 'availabilities']);

        $from = $request->get('from', now()->subMonths(1)->toDateString());
        $to   = $request->get('to', now()->toDateString());

        $sessions = $teacher->teachingSessions()
            ->with(['instrument', 'teachingType', 'level'])
            ->whereBetween('session_date', [$from, $to])
            ->orderByDesc('session_date')
            ->paginate(10, ['*'], 'sessions_page');

        $totalHours  = $teacher->totalHours($from, $to);
        $totalIncome = $teacher->totalIncome($from, $to);

        // ที่มา: ตารางสอนจริง (class_schedules.teacher_id) ไม่ใช่ enrollments.teacher_id
        // เพราะ enrollments.teacher_id เป็นแค่อาจารย์ที่เลือกไว้ตอนสมัคร/ซื้อคอร์ส และไม่ถูกอัปเดตเมื่อมีการจัดตาราง/เปลี่ยนอาจารย์ภายหลัง
        $coursesEnrollments = ClassSchedule::forTeacher($teacher->id)
            ->whereHas('enrollment', fn($q) => $q->whereIn('status', ['active', 'paused']))
            ->with(['enrollment.course', 'enrollment.student'])
            ->get()
            ->pluck('enrollment')
            ->unique('id')
            ->groupBy('course_id');

        return view('teachers.show', compact('teacher', 'sessions', 'from', 'to', 'totalHours', 'totalIncome', 'coursesEnrollments'));
    }

    // GET /teachers/{teacher}/edit
    public function edit(Teacher $teacher)
    {
        $teacher->load(['instruments', 'teachingTypes', 'levels', 'activeRates']);
        $instruments   = Instrument::where('is_active', true)->orderBy('name')->get();
        $teachingTypes = TeachingType::all();
        $levels        = Level::orderBy('sort_order')->get();

        return view('teachers.edit', compact('teacher', 'instruments', 'teachingTypes', 'levels'));
    }

    // PUT /teachers/{teacher}
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->update($data);

        $teacher->teachingTypes()->sync($data['teaching_type_ids'] ?? []);
        $teacher->levels()->sync($data['level_ids'] ?? []);

        $instrumentIds = $data['instrument_ids'] ?? [];
        $syncData = [];
        foreach ($instrumentIds as $id) {
            $syncData[$id] = ['is_primary' => $id == ($data['primary_instrument_id'] ?? null)];
        }
        $teacher->instruments()->sync($syncData);

        $this->syncRates($teacher, $data);
        $this->syncTransportFee($teacher, $data);

        return redirect()->route('teachers.show', $teacher)
            ->with('success', 'แก้ไขข้อมูลอาจารย์เรียบร้อยแล้ว');
    }

    // ปรับเรทค่าจ้างให้ตรงกับที่ส่งมาจากฟอร์มแก้ไข โดยเก็บประวัติเรทเดิมไว้ (ปิดใช้งานแทนการลบ)
    private function syncRates(Teacher $teacher, array $data): void
    {
        $submittedRates = $data['rates'] ?? [];
        $existingActiveRates = $teacher->activeRates()->get()
            ->keyBy(fn($r) => (string) ($r->instrument_id ?? 'general'));

        $submittedKeys = [];
        foreach ($submittedRates as $key => $row) {
            $key = (string) $key;
            $submittedKeys[] = $key;
            $instrumentId = $row['instrument_id'] ?? null;
            $existing = $existingActiveRates->get($key);

            $unchanged = $existing
                && $existing->rate_type === $row['rate_type']
                && (float) $existing->rate_amount == (float) $row['rate_amount'];

            if ($unchanged) {
                continue;
            }

            if ($existing) {
                $existing->update(['is_active' => false, 'effective_to' => now()->toDateString()]);
            }

            TeacherRate::create([
                'teacher_id'     => $teacher->id,
                'instrument_id'  => $instrumentId,
                'rate_type'      => $row['rate_type'],
                'rate_amount'    => $row['rate_amount'],
                'note'           => $data['rate_note'] ?? null,
                'effective_from' => now()->toDateString(),
                'is_active'      => true,
            ]);
        }

        // ปิดเรทของเครื่องดนตรีที่ไม่ได้เลือกแล้ว (ถูกเอาออกจาก "เครื่องดนตรีที่สอนได้")
        foreach ($existingActiveRates as $key => $rate) {
            if (!in_array($key, $submittedKeys, true)) {
                $rate->update(['is_active' => false, 'effective_to' => now()->toDateString()]);
            }
        }
    }

    // ปรับค่ารถให้ตรงกับที่ส่งมาจากฟอร์มแก้ไข โดยเก็บประวัติค่ารถเดิมไว้ (ปิดใช้งานแทนการลบ)
    private function syncTransportFee(Teacher $teacher, array $data): void
    {
        $activeFee = $teacher->activeTransportFee();

        if (empty($data['transport_fee_amount'])) {
            if ($activeFee) {
                $teacher->transportFees()->where('is_active', true)->update(['is_active' => false]);
            }
            return;
        }

        $feeType = $data['transport_fee_type'] ?? 'fixed_per_day';
        $unchanged = $activeFee
            && $activeFee->fee_type === $feeType
            && (float) $activeFee->fee_amount == (float) $data['transport_fee_amount'];

        if ($unchanged) {
            return;
        }

        $teacher->transportFees()->where('is_active', true)->update(['is_active' => false]);

        TeacherTransportFee::create([
            'teacher_id'     => $teacher->id,
            'fee_type'       => $feeType,
            'fee_amount'     => $data['transport_fee_amount'],
            'effective_from' => now()->toDateString(),
            'is_active'      => true,
        ]);
    }

    // DELETE /teachers/{teacher}
    public function destroy(Teacher $teacher)
    {
        $teacher->delete(); // soft delete

        return redirect()->route('teachers.index')
            ->with('success', 'ลบข้อมูลอาจารย์เรียบร้อยแล้ว');
    }
}

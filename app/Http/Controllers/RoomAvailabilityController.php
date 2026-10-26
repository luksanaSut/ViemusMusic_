<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\Teacher;
use App\Models\TeacherAvailability;
use App\Models\TeacherLeave;
use App\Models\TrialLead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RoomAvailabilityController extends Controller
{
    private const SLOT_MINUTES = 30;
    private const DEFAULT_START = 9 * 60 + 30; // 09:30 (ตามไฟล์ตารางของแอดมิน)
    private const DEFAULT_END   = 19 * 60;      // 19:00

    // GET /room-availability — ตารางห้องว่างรายวัน (ห้อง x ช่วงเวลา 30 นาที) แบบเดียวกับไฟล์ตารางของแอดมิน
    // ใช้ดูว่าวันนี้ทำงานกับอาจารย์คนไหน ห้องไหนว่างเวลาไหน และอาจารย์คนไหนลา — สำหรับแอดมินและฝ่ายขาย
    public function index(Request $request)
    {
        $date = Carbon::parse($request->get('date', now()->toDateString()))->startOfDay();
        $dateStr = $date->toDateString();

        $canEditSchedule = (bool) $request->user()?->canEditModule('schedules.manage');

        // อาจารย์ที่ลาในวันนี้ (อนุมัติแล้ว + รออนุมัติ เพื่อให้ฝ่ายขายเห็นล่วงหน้า)
        $leaves = TeacherLeave::with('teacher')
            ->whereIn('status', ['approved', 'pending'])
            ->whereDate('leave_date_from', '<=', $dateStr)
            ->whereDate('leave_date_to', '>=', $dateStr)
            ->get();
        $leaveByTeacher = $leaves->keyBy('teacher_id');

        $teacherName = fn ($t) => $t ? ($t->nickname ?: $t->full_name) : null;

        $classes = ClassSchedule::with([
            'enrollment' => fn ($q) => $q->with([
                'student' => fn ($q2) => $q2->withTrashed(),
                'course'  => fn ($q2) => $q2->withTrashed(),
            ]),
            'teacher',
        ])
            ->whereDate('schedule_date', $dateStr)
            ->whereIn('status', ['scheduled', 'completed'])
            ->get()
            ->map(fn ($s) => [
                'type'       => 'class',
                'room_id'    => $s->room_id,
                'teacher_id' => $s->teacher_id,
                'teacher'    => $teacherName($s->teacher),
                'start'      => substr($s->start_time, 0, 5),
                'end'        => substr($s->end_time, 0, 5),
                'title'      => $s->enrollment?->student?->nickname ?: ($s->enrollment?->student?->full_name ?: 'คาบเรียน'),
                'detail'     => $s->enrollment?->course?->name,
                'url'        => $canEditSchedule ? route('schedules.edit', $s) : null,
            ]);

        $trials = TrialLead::with('teacher')
            ->whereDate('trial_date', $dateStr)
            ->whereNotNull('trial_start_time')
            ->whereNotIn('status', ['converted', 'lost'])
            ->get()
            ->map(fn ($t) => [
                'type'       => 'trial',
                'room_id'    => $t->room_id,
                'teacher_id' => $t->teacher_id,
                'teacher'    => $teacherName($t->teacher),
                'start'      => substr($t->trial_start_time, 0, 5),
                'end'        => $t->trial_end_time ? substr($t->trial_end_time, 0, 5) : null,
                'title'      => $t->student_name,
                'detail'     => 'ทดลองเรียน',
                'url'        => route('trial-leads.show', $t),
            ]);

        $bookings = RoomBooking::with('teacher')
            ->whereDate('booking_date', $dateStr)
            ->where('status', 'confirmed')
            ->get()
            ->map(fn ($b) => [
                'type'       => 'booking',
                'room_id'    => $b->room_id,
                'teacher_id' => $b->teacher_id,
                'teacher'    => $teacherName($b->teacher),
                'start'      => substr($b->start_time, 0, 5),
                'end'        => substr($b->end_time, 0, 5),
                'title'      => $b->purpose ?: 'จองห้อง',
                'detail'     => 'จองห้อง',
                'url'        => null,
            ]);

        $events = $classes->concat($trials)->concat($bookings)
            ->map(function ($e) use ($leaveByTeacher) {
                $e['startMin'] = $this->toMinutes($e['start']);
                // ทดลองเรียนที่ไม่ระบุเวลาจบ ถือว่า 30 นาที
                $e['endMin'] = $e['end'] ? $this->toMinutes($e['end']) : $e['startMin'] + self::SLOT_MINUTES;
                $e['minutes'] = $e['endMin'] - $e['startMin'];
                $e['on_leave'] = $e['teacher_id'] && $leaveByTeacher->has($e['teacher_id']);
                return $e;
            })
            ->filter(fn ($e) => $e['endMin'] > $e['startMin'])
            ->sortBy('startMin')
            ->values();

        // ช่วงเวลาที่แสดง: ค่าเริ่มต้น 09:30-19:00 ขยายอัตโนมัติถ้ามีคาบนอกช่วง (ปัดเป็นช่องละ 30 นาที)
        $dayStart = min(self::DEFAULT_START, (int) (floor(($events->min('startMin') ?? self::DEFAULT_START) / self::SLOT_MINUTES) * self::SLOT_MINUTES));
        $dayEnd   = max(self::DEFAULT_END, (int) (ceil(($events->max('endMin') ?? self::DEFAULT_END) / self::SLOT_MINUTES) * self::SLOT_MINUTES));
        $slotCount = intdiv($dayEnd - $dayStart, self::SLOT_MINUTES);
        $slots = collect(range(0, $slotCount - 1))->map(fn ($i) => [
            'start' => $this->toTime($dayStart + $i * self::SLOT_MINUTES),
            'end'   => $this->toTime($dayStart + ($i + 1) * self::SLOT_MINUTES),
        ]);

        $slotRange = fn ($e) => [
            max(0, intdiv($e['startMin'] - $dayStart, self::SLOT_MINUTES)),
            min($slotCount, (int) ceil(($e['endMin'] - $dayStart) / self::SLOT_MINUTES)),
        ];

        $rooms = Room::where('is_active', true)->orderBy('name')->get();
        $eventsByRoom = $events->whereNotNull('room_id')->groupBy('room_id');

        $roomRows = $rooms->map(function ($room) use ($eventsByRoom, $slotRange, $slotCount, $date) {
            $roomEvents = $eventsByRoom->get($room->id, collect());

            $closed = $room->is_under_maintenance
                && (!$room->maintenance_from || $room->maintenance_from->lte($date))
                && (!$room->maintenance_to || $room->maintenance_to->gte($date));

            return [
                'room'     => $room,
                'closed'   => $closed,
                'lanes'    => $this->buildLanes($roomEvents, $slotRange, $slotCount),
                'teachers' => $this->buildTeacherBars($roomEvents, $slotRange, $slotCount),
                'busy'     => $roomEvents->sum('minutes'),
            ];
        });

        // คาบที่ไม่ได้ระบุห้อง (เช่น เรียนออนไลน์) แสดงแยกไว้ด้านล่าง
        $noRoomEvents = $events->whereNull('room_id')->values();

        $teacherRows = $this->buildTeacherRows($date, $events, $leaveByTeacher, $slots, $dayStart, $rooms);

        return view('room-availability.index', compact(
            'date', 'slots', 'roomRows', 'noRoomEvents', 'leaves', 'teacherRows', 'canEditSchedule'
        ));
    }

    // จัดคาบลงแถว (lane) ของห้อง ปกติห้องหนึ่งมีแถวเดียว ถ้ามีคาบทับเวลากันจะแตกเป็นหลายแถว
    private function buildLanes(Collection $events, callable $slotRange, int $slotCount): array
    {
        $lanes = [];
        foreach ($events as $e) {
            [$from, $to] = $slotRange($e);
            if ($to <= $from) continue;

            $placed = false;
            foreach ($lanes as &$lane) {
                if ($lane['end'] <= $from) {
                    $lane['items'][] = $e + ['from' => $from, 'to' => $to];
                    $lane['end'] = $to;
                    $placed = true;
                    break;
                }
            }
            unset($lane);

            if (!$placed) {
                $lanes[] = ['end' => $to, 'items' => [$e + ['from' => $from, 'to' => $to]]];
            }
        }

        return array_map(fn ($lane) => $this->toCells($lane['items'], $slotCount), $lanes ?: [['items' => []]]);
    }

    // แถบชื่ออาจารย์ใต้คาบเรียน: รวมคาบต่อเนื่องของอาจารย์คนเดียวกันในห้องเป็นแถบเดียว
    private function buildTeacherBars(Collection $events, callable $slotRange, int $slotCount): array
    {
        $bars = [];
        foreach ($events->whereNotNull('teacher_id') as $e) {
            [$from, $to] = $slotRange($e);
            if ($to <= $from) continue;
            $last = count($bars) - 1;

            if ($last >= 0 && $bars[$last]['teacher_id'] === $e['teacher_id'] && $from >= $bars[$last]['from']) {
                $bars[$last]['to'] = max($bars[$last]['to'], $to);
                continue;
            }

            // คาบทับแถบเดิม (อาจารย์คนละคนในห้องเดียวกัน) ตัดแถบเดิมให้จบก่อน
            if ($last >= 0 && $bars[$last]['to'] > $from) {
                $bars[$last]['to'] = $from;
                if ($bars[$last]['to'] <= $bars[$last]['from']) array_pop($bars);
            }

            $bars[] = [
                'teacher_id' => $e['teacher_id'],
                'teacher'    => $e['teacher'],
                'on_leave'   => $e['on_leave'],
                'from'       => $from,
                'to'         => $to,
            ];
        }

        return $this->toCells($bars, $slotCount);
    }

    // แปลงรายการที่มี from/to (index ช่องเวลา) เป็นลิสต์ช่องสำหรับ render ตาราง: ช่องว่าง หรือ ช่องที่ colspan
    private function toCells(array $items, int $slotCount): array
    {
        $cells = [];
        $cursor = 0;
        foreach ($items as $item) {
            for (; $cursor < $item['from']; $cursor++) {
                $cells[] = ['empty' => true, 'slot' => $cursor];
            }
            $cells[] = ['empty' => false, 'span' => $item['to'] - $item['from'], 'item' => $item];
            $cursor = $item['to'];
        }
        for (; $cursor < $slotCount; $cursor++) {
            $cells[] = ['empty' => true, 'slot' => $cursor];
        }

        return $cells;
    }

    // ตารางเวลาว่างของอาจารย์ในวันนั้น: ว่าง / สอนอยู่ (ห้องไหน) / ลา / นอกเวลาทำงาน
    private function buildTeacherRows(Carbon $date, Collection $events, Collection $leaveByTeacher, Collection $slots, int $dayStart, Collection $rooms): Collection
    {
        $availability = TeacherAvailability::where('day_of_week', $date->dayOfWeek)
            ->where('is_available', true)
            ->get()
            ->groupBy('teacher_id');

        $eventsByTeacher = $events->whereNotNull('teacher_id')->groupBy('teacher_id');
        $roomNames = $rooms->pluck('name', 'id');

        $teacherIds = $availability->keys()
            ->merge($eventsByTeacher->keys())
            ->merge($leaveByTeacher->keys())
            ->unique();

        return Teacher::whereIn('id', $teacherIds)->where('is_active', true)
            ->get()
            ->sortBy(fn ($t) => $t->nickname ?: $t->full_name)
            ->map(function ($teacher) use ($availability, $eventsByTeacher, $leaveByTeacher, $slots, $dayStart, $roomNames) {
                $windows = $availability->get($teacher->id, collect());
                $teacherEvents = $eventsByTeacher->get($teacher->id, collect());
                $leave = $leaveByTeacher->get($teacher->id);

                $cells = $slots->keys()->map(function ($i) use ($windows, $teacherEvents, $leave, $dayStart, $roomNames) {
                    $s = $dayStart + $i * self::SLOT_MINUTES;
                    $e = $s + self::SLOT_MINUTES;

                    $busy = $teacherEvents->first(fn ($ev) => $ev['startMin'] < $e && $ev['endMin'] > $s);
                    if ($busy) {
                        return ['state' => 'busy', 'label' => $roomNames[$busy['room_id']] ?? 'ไม่ระบุห้อง', 'title' => $busy['title']];
                    }
                    if ($leave) {
                        return ['state' => 'leave'];
                    }

                    $inWindow = $windows->contains(fn ($w) => $this->toMinutes($w->start_time) <= $s && $this->toMinutes($w->end_time) >= $e);

                    return ['state' => $inWindow ? 'free' : 'off'];
                });

                return [
                    'teacher'   => $teacher,
                    'leave'     => $leave,
                    'cells'     => $cells,
                    'freeSlots' => $cells->where('state', 'free')->count(),
                ];
            })
            ->values();
    }

    private function toMinutes(string $time): int
    {
        [$h, $m] = array_map('intval', explode(':', $time));
        return $h * 60 + $m;
    }

    private function toTime(int $minutes): string
    {
        return sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
    }
}

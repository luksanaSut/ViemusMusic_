<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\TrialLead;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class RoomScheduleController extends Controller
{
    // GET /rooms-schedule — ตารางการใช้งานห้องเรียนรวมทุกห้อง แบบปฏิทิน รายวัน/สัปดาห์/เดือน
    public function index(Request $request)
    {
        $view = in_array($request->get('view'), ['day', 'week', 'month']) ? $request->get('view') : 'week';
        $date = Carbon::parse($request->get('date', now()->toDateString()));

        [$from, $to] = match ($view) {
            'day'   => [$date->copy()->startOfDay(), $date->copy()->endOfDay()],
            'month' => [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()],
            default => [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()],
        };

        // ช่วงที่แสดงบนปฏิทิน (รายเดือนขยายให้เต็มสัปดาห์ อาทิตย์-เสาร์ เหมือนหน้าระบบจัดตารางเรียน)
        [$gridFrom, $gridTo] = $view === 'month'
            ? [$from->copy()->startOfWeek(Carbon::SUNDAY), $to->copy()->endOfWeek(Carbon::SATURDAY)]
            : [$from->copy(), $to->copy()];
        $range = [$gridFrom->toDateString(), $gridTo->toDateString()];
        $roomFilter = fn ($q) => $q->where('room_id', $request->room_id);

        $bookings = RoomBooking::with(['room', 'teacher', 'course'])
            ->where('status', 'confirmed')
            ->whereBetween('booking_date', $range)
            ->when($request->filled('room_id'), $roomFilter)
            ->get()
            ->map(fn ($b) => [
                'date'  => $b->booking_date->toDateString(),
                'start' => substr($b->start_time, 0, 5),
                'end'   => substr($b->end_time, 0, 5),
                'type'  => 'booking',
                'room'  => $b->room,
                'title'   => $b->purpose ?: 'จองห้อง',
                'teacher' => $b->teacher?->nickname ?: $b->teacher?->full_name,
                'course'  => $b->course?->name,
                'url'   => route('rooms.show', $b->room),
            ]);

        $trialLeads = TrialLead::with(['room', 'teacher', 'course'])
            ->whereNotNull('room_id')
            ->whereNotNull('trial_date')
            ->whereBetween('trial_date', $range)
            ->whereNotIn('status', ['converted', 'lost'])
            ->when($request->filled('room_id'), $roomFilter)
            ->get()
            ->map(fn ($t) => [
                'date'  => $t->trial_date->toDateString(),
                'start' => $t->trial_start_time ? substr($t->trial_start_time, 0, 5) : '',
                'end'   => $t->trial_end_time ? substr($t->trial_end_time, 0, 5) : '',
                'type'  => 'trial',
                'room'  => $t->room,
                'title'   => $t->student_name,
                'teacher' => $t->teacher?->nickname ?: $t->teacher?->full_name,
                'course'  => $t->course?->name,
                'url'   => route('trial-leads.show', $t),
            ]);

        $canEditSchedule = (bool) $request->user()?->hasModulePermission('schedules.manage');

        // คาบเรียนจากตารางเรียน (class_schedules) ที่ระบุห้อง
        $classSchedules = ClassSchedule::with([
            'enrollment' => fn ($q) => $q->with([
                'student' => fn ($q2) => $q2->withTrashed(),
                'course'  => fn ($q2) => $q2->withTrashed(),
            ]),
            'teacher',
            'room',
        ])
            ->whereNotNull('room_id')
            ->whereIn('status', ['scheduled', 'completed'])
            ->whereBetween('schedule_date', $range)
            ->when($request->filled('room_id'), $roomFilter)
            ->get()
            ->map(fn ($s) => [
                'date'  => $s->schedule_date->toDateString(),
                'start' => substr($s->start_time, 0, 5),
                'end'   => substr($s->end_time, 0, 5),
                'type'  => 'class',
                'room'  => $s->room,
                'title'   => $s->enrollment?->student?->full_name ?: 'คาบเรียน',
                'teacher' => $s->teacher?->nickname ?: $s->teacher?->full_name,
                'course'  => $s->enrollment?->course?->name,
                'url'     => $canEditSchedule ? route('schedules.edit', $s) : route('rooms.show', $s->room),
            ]);

        $events = $classSchedules->concat($bookings)->concat($trialLeads)
            ->sortBy(fn ($e) => $e['date'] . ' ' . $e['start'])
            ->groupBy('date');

        $days = collect(CarbonPeriod::create($gridFrom->copy()->startOfDay(), $gridTo->copy()->startOfDay()));

        [$prev, $next] = match ($view) {
            'day'   => [$date->copy()->subDay(), $date->copy()->addDay()],
            'month' => [$date->copy()->subMonthNoOverflow(), $date->copy()->addMonthNoOverflow()],
            default => [$date->copy()->subWeek(), $date->copy()->addWeek()],
        };

        $rooms = Room::where('is_active', true)->orderBy('name')->get();

        return view('rooms.schedule', compact('events', 'days', 'view', 'date', 'from', 'to', 'prev', 'next', 'rooms'));
    }
}

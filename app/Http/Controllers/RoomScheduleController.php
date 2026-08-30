<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\TrialLead;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoomScheduleController extends Controller
{
    // GET /rooms-schedule — ตารางการใช้งานห้องเรียนรวมทุกห้อง รายวัน/สัปดาห์/เดือน
    public function index(Request $request)
    {
        $view = $request->get('view', 'week');
        $date = Carbon::parse($request->get('date', now()->toDateString()));

        [$from, $to] = match ($view) {
            'day'   => [$date->copy()->startOfDay(), $date->copy()->endOfDay()],
            'month' => [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()],
            default => [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()],
        };

        $bookings = RoomBooking::with(['room', 'teacher', 'course'])
            ->where('status', 'confirmed')
            ->whereBetween('booking_date', [$from->toDateString(), $to->toDateString()])
            ->when($request->filled('room_id'), fn($q) => $q->where('room_id', $request->room_id))
            ->orderBy('booking_date')->orderBy('start_time')
            ->get()
            ->groupBy(fn($b) => $b->booking_date->toDateString());

        $trialLeads = TrialLead::with(['room', 'teacher', 'course'])
            ->whereNotNull('room_id')
            ->whereNotNull('trial_date')
            ->whereBetween('trial_date', [$from->toDateString(), $to->toDateString()])
            ->whereNotIn('status', ['converted', 'lost'])
            ->when($request->filled('room_id'), fn ($q) => $q->where('room_id', $request->room_id))
            ->orderBy('trial_date')->orderBy('trial_start_time')
            ->get()
            ->groupBy(fn ($t) => $t->trial_date->toDateString());

        $dateKeys = $bookings->keys()->merge($trialLeads->keys())->unique()->sort()->values();
        $rooms = Room::where('is_active', true)->orderBy('name')->get();

        return view('rooms.schedule', compact('bookings', 'trialLeads', 'dateKeys', 'view', 'date', 'from', 'to', 'rooms'));
    }
}

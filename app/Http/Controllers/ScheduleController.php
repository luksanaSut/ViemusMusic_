<?php

namespace App\Http\Controllers;

use App\Models\TeachingSession;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // ตารางสอนรวมทุกอาจารย์ แบบ รายวัน / รายสัปดาห์ / รายเดือน
    public function index(Request $request)
    {
        $view = $request->get('view', 'week'); // day | week | month
        $date = Carbon::parse($request->get('date', now()->toDateString()));

        [$from, $to] = match ($view) {
            'day'   => [$date->copy()->startOfDay(), $date->copy()->endOfDay()],
            'month' => [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()],
            default => [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()],
        };

        $sessions = TeachingSession::with(['teacher', 'instrument', 'teachingType'])
            ->whereBetween('session_date', [$from->toDateString(), $to->toDateString()])
            ->when($request->filled('teacher_id'), fn ($q) => $q->where('teacher_id', $request->teacher_id))
            ->orderBy('session_date')->orderBy('start_time')
            ->get()
            ->groupBy(fn ($s) => $s->session_date->toDateString());

        return view('schedule.index', compact('sessions', 'view', 'date', 'from', 'to'));
    }
}

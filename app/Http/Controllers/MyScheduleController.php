<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use Illuminate\Http\Request;

class MyScheduleController extends Controller
{
    private function myStudents(Request $request)
    {
        $user = $request->user();

        if ($user->isStudent() && $user->student) {
            return collect([$user->student]);
        }
        if ($user->isGuardian() && $user->guardian) {
            return $user->guardian->students;
        }

        return collect();
    }

    // GET /my-schedule
    public function index(Request $request)
    {
        $students = $this->myStudents($request);
        $studentIds = $students->pluck('id');

        $dateFrom = $request->filled('date_from') ? $request->date('date_from') : now()->startOfWeek();
        $dateTo = $request->filled('date_to') ? $request->date('date_to') : now()->addWeeks(2)->endOfWeek();

        $schedules = ClassSchedule::whereHas('enrollment', fn ($q) => $q->whereIn('student_id', $studentIds))
            ->when($request->filled('student_id'), fn ($q) => $q->whereHas('enrollment', fn ($qq) => $qq->where('student_id', $request->integer('student_id'))))
            ->whereBetween('schedule_date', [$dateFrom->toDateString(), $dateTo->toDateString()])
            ->with(['teacher', 'room', 'enrollment.course', 'enrollment.student'])
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn ($s) => $s->schedule_date->toDateString());

        return view('my-schedule.index', compact('students', 'schedules', 'dateFrom', 'dateTo'));
    }
}

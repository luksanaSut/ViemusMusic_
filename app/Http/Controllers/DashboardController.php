<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\MakeupRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // GET /dashboard — แยกหน้าตามบทบาทผู้ใช้
    public function index(Request $request)
    {
        $user = $request->user();

        return match ($user->role) {
            'admin', 'staff' => redirect()->route('teachers.index'),
            'teacher'  => $this->teacherDashboard($user),
            'student'  => $this->studentDashboard($user),
            'guardian' => $this->guardianDashboard($user),
            default    => redirect()->route('login'),
        };
    }

    private function teacherDashboard($user)
    {
        $teacher = $user->teacher;
        if (!$teacher) {
            return view('dashboard.no-link', ['role' => 'อาจารย์']);
        }

        $teacher->load(['instruments', 'teachingTypes']);
        $upcoming = $teacher->teachingSessions()
            ->where('status', 'scheduled')
            ->where('session_date', '>=', now()->toDateString())
            ->orderBy('session_date')->orderBy('start_time')
            ->limit(10)->get();

        $calendarStart = now()->startOfWeek();
        $calendarEnd = $calendarStart->copy()->addWeeks(4)->subDay();

        $scheduleUpcoming = ClassSchedule::where('teacher_id', $teacher->id)
            ->where('status', 'scheduled')
            ->whereBetween('schedule_date', [$calendarStart->toDateString(), $calendarEnd->toDateString()])
            ->with(['enrollment.student', 'enrollment.course', 'room'])
            ->orderBy('schedule_date')->orderBy('start_time')
            ->get();

        $pendingMakeups = MakeupRequest::where('teacher_id', $teacher->id)
            ->where('instructor_approval_status', 'pending')
            ->with(['student', 'enrollment.course', 'room'])
            ->orderBy('makeup_date')
            ->get();

        $thisWeekSchedules = $scheduleUpcoming->filter(
            fn ($schedule) => $schedule->schedule_date->betweenIncluded($calendarStart, $calendarStart->copy()->endOfWeek())
        );

        $dashboardStats = [
            'today_classes' => $scheduleUpcoming->filter(fn ($schedule) => $schedule->schedule_date->isToday())->count(),
            'week_classes' => $thisWeekSchedules->count(),
            'week_hours' => round($thisWeekSchedules->sum(function ($schedule) {
                return \Carbon\Carbon::parse($schedule->start_time)->diffInMinutes(\Carbon\Carbon::parse($schedule->end_time)) / 60;
            }), 1),
            'students' => $scheduleUpcoming->pluck('enrollment.student_id')->filter()->unique()->count(),
            'pending_makeups' => $pendingMakeups->count(),
        ];

        return view('dashboard.teacher', compact(
            'teacher', 'upcoming', 'scheduleUpcoming', 'pendingMakeups',
            'calendarStart', 'dashboardStats'
        ));
    }

    private function studentDashboard($user)
    {
        $student = $user->student;
        if (!$student) {
            return view('dashboard.no-link', ['role' => 'นักเรียน']);
        }

        $student->load([
            'enrollments' => fn($q) => $q->with('course')->where('status', 'active'),
            'payments' => fn($q) => $q->orderByDesc('due_date'),
            'leaves' => fn($q) => $q->with('makeupRequest')->orderByDesc('created_at'),
        ]);

        $scheduleUpcoming = \App\Models\ClassSchedule::whereHas('enrollment', fn($q) => $q->where('student_id', $student->id))
            ->where('status', 'scheduled')
            ->where('schedule_date', '>=', now()->toDateString())
            ->with(['teacher', 'room', 'enrollment.course'])
            ->orderBy('schedule_date')->orderBy('start_time')
            ->limit(10)->get();

        return view('dashboard.student', compact('student', 'scheduleUpcoming'));
    }

    private function guardianDashboard($user)
    {
        $guardian = $user->guardian;
        if (!$guardian) {
            return view('dashboard.no-link', ['role' => 'ผู้ปกครอง']);
        }

        $guardian->load([
            'students.enrollments' => fn($q) => $q->with('course')->where('status', 'active'),
            'students.payments',
            'students.leaves' => fn($q) => $q->with('makeupRequest')->orderByDesc('created_at'),
        ]);

        return view('dashboard.guardian', compact('guardian'));
    }
}

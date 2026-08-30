<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Course;
use App\Models\MakeupRequest;
use App\Models\Payment;
use App\Models\RescheduleRequest;
use App\Models\SaleOrder;
use App\Models\Student;
use App\Models\StudentLeave;
use App\Models\Teacher;
use App\Models\TeacherLeave;
use App\Models\TrialLead;
use App\Services\FinanceService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // GET /dashboard — แยกหน้าตามบทบาทผู้ใช้
    public function index(Request $request)
    {
        $user = $request->user();

        return match ($user->role) {
            'admin', 'staff' => $this->adminDashboard($user),
            'teacher'  => $this->teacherDashboard($user),
            'student'  => $this->studentDashboard($user),
            'guardian' => $this->guardianDashboard($user),
            default    => redirect()->route('login'),
        };
    }

    private function adminDashboard($user)
    {
        $today = now()->toDateString();

        $stats = [
            'students_active' => Student::where('status', 'active')->count(),
            'teachers_total'  => Teacher::count(),
            'courses_active'  => Course::where('is_active', true)->count(),
            'today_classes'   => ClassSchedule::whereDate('schedule_date', $today)
                ->whereIn('status', ['scheduled', 'completed'])->count(),
        ];

        $pending = [
            'teacher_leaves'      => TeacherLeave::where('status', 'pending')->count(),
            'student_leaves'      => StudentLeave::where('status', 'pending')->count(),
            'reschedule_requests' => RescheduleRequest::where('status', 'pending')->count(),
            'makeup_requests'     => MakeupRequest::where('overall_status', 'pending')->count(),
        ];

        $finance = null;
        if ($user->hasModulePermission('finance.manage')) {
            $service = app(FinanceService::class);
            [$start, $end] = $service->resolvePeriod('monthly');
            $finance = $service->summary($start, $end);
        }

        $overduePaymentsCount = 0;
        $overduePaymentsAmount = 0.0;
        if ($user->hasModulePermission('students.manage')) {
            $overdueQuery = Payment::whereIn('status', ['pending', 'partial', 'overdue'])
                ->where(function ($q) use ($today) {
                    $q->where('status', 'overdue')->orWhere('due_date', '<', $today);
                });
            $overduePaymentsCount = (clone $overdueQuery)->count();
            $overduePaymentsAmount = (clone $overdueQuery)->get()
                ->sum(fn ($payment) => $payment->outstandingAmount());
        }

        $recentSales = collect();
        if ($user->hasModulePermission('sales.manage')) {
            $recentSales = SaleOrder::with(['student', 'course'])
                ->latest()->limit(5)->get();
        }

        $todaySchedules = ClassSchedule::whereDate('schedule_date', $today)
            ->whereIn('status', ['scheduled', 'completed'])
            ->with(['teacher', 'room', 'enrollment.student', 'enrollment.course'])
            ->orderBy('start_time')
            ->limit(8)
            ->get();

        $todayTrialLeads = TrialLead::whereDate('trial_date', $today)
            ->whereNotIn('status', ['converted', 'lost'])
            ->with(['teacher', 'room', 'course'])
            ->orderBy('trial_start_time')
            ->get();
        $stats['today_trials'] = $todayTrialLeads->count();

        return view('dashboard.admin', compact(
            'stats', 'pending', 'finance', 'overduePaymentsCount', 'overduePaymentsAmount',
            'recentSales', 'todaySchedules', 'todayTrialLeads'
        ));
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

        $trialsUpcoming = TrialLead::where('teacher_id', $teacher->id)
            ->whereNotNull('trial_date')
            ->whereBetween('trial_date', [$calendarStart->toDateString(), $calendarEnd->toDateString()])
            ->whereNotIn('status', ['converted', 'lost'])
            ->with(['course', 'room'])
            ->orderBy('trial_start_time')
            ->get();
        $todayTrialLeads = $trialsUpcoming->filter(fn ($trial) => $trial->trial_date->isToday());

        $dashboardStats = [
            'today_classes' => $scheduleUpcoming->filter(fn ($schedule) => $schedule->schedule_date->isToday())->count(),
            'week_classes' => $thisWeekSchedules->count(),
            'week_hours' => round($thisWeekSchedules->sum(function ($schedule) {
                return \Carbon\Carbon::parse($schedule->start_time)->diffInMinutes(\Carbon\Carbon::parse($schedule->end_time)) / 60;
            }), 1),
            'students' => $scheduleUpcoming->pluck('enrollment.student_id')->filter()->unique()->count(),
            'pending_makeups' => $pendingMakeups->count(),
            'today_trials' => $todayTrialLeads->count(),
        ];

        return view('dashboard.teacher', compact(
            'teacher', 'upcoming', 'scheduleUpcoming', 'pendingMakeups',
            'calendarStart', 'dashboardStats', 'todayTrialLeads', 'trialsUpcoming'
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

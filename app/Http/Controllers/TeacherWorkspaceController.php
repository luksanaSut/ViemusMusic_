<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Enrollment;
use App\Models\HomeworkSubmission;
use App\Models\MakeupRequest;
use App\Models\TeachingLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeacherWorkspaceController extends Controller
{
    public function schedule(Request $request)
    {
        $teacher = $this->teacher($request);
        $display = in_array($request->get('view'), ['day', 'week', 'month'], true)
            ? $request->get('view') : 'week';

        try {
            $focusDate = $request->filled('date') ? Carbon::parse($request->get('date'))->startOfDay() : now()->startOfDay();
        } catch (\Throwable) {
            $focusDate = now()->startOfDay();
        }

        [$rangeStart, $rangeEnd] = match ($display) {
            'day' => [$focusDate->copy(), $focusDate->copy()],
            'month' => [$focusDate->copy()->startOfMonth()->startOfWeek(), $focusDate->copy()->endOfMonth()->endOfWeek()],
            default => [$focusDate->copy()->startOfWeek(), $focusDate->copy()->endOfWeek()],
        };

        $schedules = ClassSchedule::with(['enrollment.student', 'enrollment.course', 'room'])
            ->where('teacher_id', $teacher->id)
            ->whereBetween('schedule_date', [$rangeStart->toDateString(), $rangeEnd->toDateString()])
            ->whereIn('status', ['scheduled', 'completed', 'no_show'])
            ->orderBy('schedule_date')->orderBy('start_time')->get();

        $makeupScheduleIds = MakeupRequest::whereIn('class_schedule_id', $schedules->pluck('id'))
            ->pluck('class_schedule_id')->flip();
        $previousDate = match ($display) {
            'day' => $focusDate->copy()->subDay()->toDateString(),
            'month' => $focusDate->copy()->subMonth()->toDateString(),
            default => $focusDate->copy()->subWeek()->toDateString(),
        };
        $nextDate = match ($display) {
            'day' => $focusDate->copy()->addDay()->toDateString(),
            'month' => $focusDate->copy()->addMonth()->toDateString(),
            default => $focusDate->copy()->addWeek()->toDateString(),
        };

        return view('teacher-workspace.schedule', compact(
            'teacher', 'display', 'focusDate', 'rangeStart', 'rangeEnd', 'schedules',
            'makeupScheduleIds', 'previousDate', 'nextDate'
        ));
    }

    public function tasks(Request $request)
    {
        $teacher = $this->teacher($request);
        $attendanceTasks = ClassSchedule::with(['enrollment.student', 'enrollment.course'])
            ->where('teacher_id', $teacher->id)->whereDate('schedule_date', '<=', now())
            ->whereDate('schedule_date', '>=', now()->subDays(30))->whereIn('status', ['scheduled', 'completed'])
            ->whereDoesntHave('teachingLog', fn ($q) => $q->whereNotNull('confirmed_at'))->orderBy('schedule_date')->get();
        $reportTasks = TeachingLog::with(['student', 'enrollment.course', 'classSchedule'])
            ->where('teacher_id', $teacher->id)->whereNotNull('confirmed_at')->whereDoesntHave('teachingReport')->latest('confirmed_at')->get();
        $homeworkTasks = HomeworkSubmission::where('status', 'submitted')
            ->whereHas('teachingReport.teachingLog', fn ($q) => $q->where('teacher_id', $teacher->id))->get();
        $makeupTasks = MakeupRequest::where('teacher_id', $teacher->id)->where('instructor_approval_status', 'pending')->get();
        return view('teacher-workspace.tasks', compact('attendanceTasks', 'reportTasks', 'homeworkTasks', 'makeupTasks'));
    }

    public function students(Request $request)
    {
        $teacher = $this->teacher($request);
        $search = trim((string) $request->get('q'));
        $enrollments = Enrollment::with(['student', 'course', 'courseEvaluation.items'])
            ->withCount('leaves')->where('teacher_id', $teacher->id)->where('status', 'active')
            ->when($search, fn ($q) => $q->where(fn ($n) => $n
                ->whereHas('student', fn ($s) => $s->search($search))
                ->orWhereHas('course', fn ($c) => $c->search($search))))
            ->orderByDesc('enrolled_date')->paginate(18)->withQueryString();
        $ids = $enrollments->pluck('id');
        $lastSchedules = ClassSchedule::whereIn('enrollment_id', $ids)->whereDate('schedule_date', '<=', now())
            ->orderByDesc('schedule_date')->get()->unique('enrollment_id')->keyBy('enrollment_id');
        $nextSchedules = ClassSchedule::whereIn('enrollment_id', $ids)->where('status', 'scheduled')->whereDate('schedule_date', '>=', now())
            ->orderBy('schedule_date')->orderBy('start_time')->get()->unique('enrollment_id')->keyBy('enrollment_id');
        return view('teacher-workspace.students', compact('enrollments', 'lastSchedules', 'nextSchedules', 'search'));
    }

    private function teacher(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 404, 'บัญชีนี้ยังไม่ได้ผูกกับข้อมูลอาจารย์');
        return $teacher;
    }
}

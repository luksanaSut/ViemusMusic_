<?php

namespace App\Providers;

use App\Models\ClassSchedule;
use App\Models\HomeworkSubmission;
use App\Models\MakeupRequest;
use App\Models\TeachingLog;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        View::composer('layouts.app', function ($view) {
            $user = auth()->user();
            if (!$user?->isTeacher() || !$user->teacher_id) return;

            $teacherId = $user->teacher_id;
            $attendance = ClassSchedule::where('teacher_id', $teacherId)
                ->whereDate('schedule_date', '<=', now())->whereDate('schedule_date', '>=', now()->subDays(30))
                ->whereIn('status', ['scheduled', 'completed'])
                ->whereDoesntHave('teachingLog', fn ($q) => $q->whereNotNull('confirmed_at'))->count();
            $reports = TeachingLog::where('teacher_id', $teacherId)->whereNotNull('confirmed_at')->whereDoesntHave('teachingReport')->count();
            $homework = HomeworkSubmission::where('status', 'submitted')
                ->whereHas('teachingReport.teachingLog', fn ($q) => $q->where('teacher_id', $teacherId))->count();
            $makeups = MakeupRequest::where('teacher_id', $teacherId)->where('instructor_approval_status', 'pending')->count();
            $view->with('teacherPendingTaskCount', $attendance + $reports + $homework + $makeups);
        });
    }

    public function boot(): void
    {
        //
    }
}

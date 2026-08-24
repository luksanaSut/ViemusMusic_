<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;

class MyCoursesController extends Controller
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

    // GET /my-courses
    public function index(Request $request)
    {
        $students = $this->myStudents($request);
        $studentIds = $students->pluck('id');

        $enrollments = Enrollment::whereIn('student_id', $studentIds)
            ->with(['course', 'teacher', 'student'])
            ->orderByRaw("FIELD(status, 'active', 'paused', 'completed', 'cancelled')")
            ->orderByDesc('enrolled_date')
            ->get()
            ->groupBy('student_id');

        return view('my-courses.index', compact('students', 'enrollments'));
    }
}

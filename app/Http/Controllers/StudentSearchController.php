<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentSearchController extends Controller
{
    // GET /students-search?q=
    public function __invoke(Request $request)
    {
        $term = $request->get('q', '');
        $students = Student::search($term)->limit(8)->get(['id', 'student_code', 'full_name', 'nickname']);

        return response()->json($students);
    }
}

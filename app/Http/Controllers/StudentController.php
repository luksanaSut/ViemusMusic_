<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // GET /students
    public function index(Request $request)
    {
        $students = Student::query()
            ->search($request->get('q'))
            ->status($request->get('status'))
            ->withCount(['enrollments as active_enrollments_count' => fn($q) => $q->where('status', 'active')])
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('students', 'public');
        }

        $student = Student::create($data);

        return redirect()->route('students.show', $student)->with('success', 'เพิ่มข้อมูลนักเรียนเรียบร้อยแล้ว');
    }

    // GET /students/{student}
    public function show(Student $student)
    {
        $student->load([
            'guardians',
            'enrollments.course',
            'payments' => fn($q) => $q->orderByDesc('due_date'),
            'creditTransactions' => fn($q) => $q->latest(),
            'skillLevels.instrument',
            'skillLevels.level',
            'examResults' => fn($q) => $q->orderByDesc('exam_date'),
            'leaves.enrollment.course',
        ]);

        $courses = Course::where('is_active', true)->orderBy('name')->get();

        return view('students.show', compact('student', 'courses'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($data);

        return redirect()->route('students.show', $student)->with('success', 'แก้ไขข้อมูลนักเรียนเรียบร้อยแล้ว');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'ลบข้อมูลนักเรียนเรียบร้อยแล้ว');
    }
}

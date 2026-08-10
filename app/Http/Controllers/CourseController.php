<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\Instrument;
use App\Models\Level;
use App\Models\Teacher;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // GET /courses — ค้นหา/กรอง/แสดงรายการคอร์ส
    public function index(Request $request)
    {
        $courses = Course::query()
            ->with(['instrument', 'level', 'teachers'])
            ->search($request->get('q'))
            ->instrument($request->get('instrument_id'))
            ->level($request->get('level_id'))
            ->classType($request->get('class_type'))
            ->when($request->filled('is_active'), fn($q) => $q->where('is_active', $request->boolean('is_active')))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $instruments = Instrument::where('is_active', true)->orderBy('name')->get();
        $levels      = Level::orderBy('sort_order')->get();

        return view('courses.index', compact('courses', 'instruments', 'levels'));
    }

    // GET /courses/create
    public function create()
    {
        $instruments = Instrument::where('is_active', true)->orderBy('name')->get();
        $levels      = Level::orderBy('sort_order')->get();
        $teachers    = Teacher::where('is_active', true)->with('instruments')->orderBy('full_name')->get();

        return view('courses.create', compact('instruments', 'levels', 'teachers'));
    }


    // POST /courses
    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('courses', 'public');
        }

        $course = Course::create($data);
        $course->teachers()->sync($data['teacher_ids'] ?? []);

        return redirect()->route('courses.index')
            ->with('success', 'เพิ่มคอร์สเรียนเรียบร้อยแล้ว');
    }

    // GET /courses/{course}/edit
    public function edit(Course $course)
    {
        $course->load('teachers');
        $instruments = Instrument::where('is_active', true)->orderBy('name')->get();
        $levels      = Level::orderBy('sort_order')->get();
        $teachers    = Teacher::where('is_active', true)->with('instruments')->orderBy('full_name')->get();

        return view('courses.edit', compact('course', 'instruments', 'levels', 'teachers'));
    }

    // PUT /courses/{course}
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($data);
        $course->teachers()->sync($data['teacher_ids'] ?? []);

        return redirect()->route('courses.index')
            ->with('success', 'แก้ไขคอร์สเรียนเรียบร้อยแล้ว');
    }

    // DELETE /courses/{course}
    public function destroy(Course $course)
    {
        $course->delete(); // soft delete

        return redirect()->route('courses.index')
            ->with('success', 'ลบคอร์สเรียนเรียบร้อยแล้ว');
    }

    // PATCH /courses/{course}/toggle-status — เปิด/ปิดการใช้งานคอร์ส
    public function toggleStatus(Course $course)
    {
        $course->update(['is_active' => !$course->is_active]);

        return back()->with('success', $course->is_active
            ? 'เปิดใช้งานคอร์ส "' . $course->name . '" แล้ว'
            : 'ปิดใช้งานคอร์ส "' . $course->name . '" แล้ว');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // GET /users
    public function index(Request $request)
    {
        $users = User::with(['teacher', 'student', 'guardian'])
            ->when($request->filled('q'), fn($q) => $q->where('name', 'like', '%' . $request->q . '%')->orWhere('email', 'like', '%' . $request->q . '%'))
            ->when($request->filled('role'), fn($q) => $q->where('role', $request->role))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    // GET /users/create
    public function create()
    {
        $teachers = Teacher::whereDoesntHave('user')->where('is_active', true)->orderBy('full_name')->get();
        $students = Student::whereDoesntHave('user')->orderBy('full_name')->get();
        $guardians = Guardian::whereDoesntHave('user')->orderBy('full_name')->get();

        return view('users.create', compact('teachers', 'students', 'guardians'));
    }

    // POST /users
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'email'       => ['required', 'email', 'max:150', 'unique:users,email'],
            'role'        => ['required', 'in:admin,staff,teacher,student,guardian'],
            'teacher_id'  => ['nullable', 'exists:teachers,id', 'unique:users,teacher_id'],
            'student_id'  => ['nullable', 'exists:students,id', 'unique:users,student_id'],
            'guardian_id' => ['nullable', 'exists:guardians,id', 'unique:users,guardian_id'],
        ]);

        $password = Str::password(12);

        $user = User::create([
            'name'                 => trim(strip_tags($data['name'])),
            'email'                => $data['email'],
            'password'             => Hash::make($password),
            'role'                 => $data['role'],
            'teacher_id'           => $data['role'] === 'teacher' ? ($data['teacher_id'] ?? null) : null,
            'student_id'           => $data['role'] === 'student' ? ($data['student_id'] ?? null) : null,
            'guardian_id'          => $data['role'] === 'guardian' ? ($data['guardian_id'] ?? null) : null,
            'is_active'            => true,
            'must_change_password' => true,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'สร้างบัญชีเรียบร้อยแล้ว')
            ->with('generated_password', $password)
            ->with('generated_email', $user->email);
    }

    // POST /users/{user}/reset-password
    public function resetPassword(User $user)
    {
        $password = Str::password(12);
        $user->update(['password' => Hash::make($password), 'must_change_password' => true]);

        return back()->with('success', 'รีเซ็ตรหัสผ่านเรียบร้อยแล้ว')
            ->with('generated_password', $password)
            ->with('generated_email', $user->email);
    }

    // PATCH /users/{user}/toggle-active
    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'ไม่สามารถปิดใช้งานบัญชีของตัวเองได้');
        }

        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', $user->is_active ? 'เปิดใช้งานบัญชีแล้ว' : 'ปิดใช้งานบัญชีแล้ว');
    }

    // DELETE /users/{user}
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'ไม่สามารถลบบัญชีของตัวเองได้');
        }

        $user->delete();

        return back()->with('success', 'ลบบัญชีผู้ใช้งานเรียบร้อยแล้ว');
    }

    // ===== สร้างบัญชีแบบเร็วจากหน้าโปรไฟล์อาจารย์/นักเรียน/ผู้ปกครอง =====

    // POST /teachers/{teacher}/create-account
    public function quickCreateForTeacher(Teacher $teacher)
    {
        return $this->quickCreate($teacher, 'teacher', $teacher->email, $teacher->full_name, 'teacher_id');
    }

    // POST /students/{student}/create-account
    public function quickCreateForStudent(Student $student)
    {
        return $this->quickCreate($student, 'student', $student->email, $student->full_name, 'student_id');
    }

    // POST /guardians/{guardian}/create-account
    public function quickCreateForGuardian(Guardian $guardian)
    {
        return $this->quickCreate($guardian, 'guardian', $guardian->email, $guardian->full_name, 'guardian_id');
    }

    private function quickCreate($model, string $role, ?string $email, string $name, string $foreignKeyColumn)
    {
        if ($model->user) {
            return back()->with('error', 'มีบัญชีผู้ใช้งานของคนนี้อยู่แล้ว');
        }
        if (!$email) {
            return back()->with('error', 'ต้องมีอีเมลก่อนถึงจะสร้างบัญชีผู้ใช้งานได้ กรุณาเพิ่มอีเมลในข้อมูลก่อน');
        }
        if (User::where('email', $email)->exists()) {
            return back()->with('error', 'อีเมลนี้ถูกใช้สร้างบัญชีอื่นไปแล้ว');
        }

        $password = Str::password(12);

        User::create([
            'name'                 => $name,
            'email'                => $email,
            'password'             => Hash::make($password),
            'role'                 => $role,
            $foreignKeyColumn      => $model->id,
            'is_active'            => true,
            'must_change_password' => true,
        ]);

        return back()->with('success', 'สร้างบัญชีผู้ใช้งานเรียบร้อยแล้ว')
            ->with('generated_password', $password)
            ->with('generated_email', $email);
    }
}

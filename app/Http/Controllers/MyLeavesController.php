<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;

class MyLeavesController extends Controller
{
    private function myStudents(Request $request)
    {
        $user = $request->user();

        if ($user->isStudent() && $user->student) {
            return \App\Models\Student::where('id', $user->student->id)->get();
        }
        if ($user->isGuardian() && $user->guardian) {
            return $user->guardian->students;
        }

        return \App\Models\Student::whereRaw('1=0')->get(); // Eloquent collection ว่างเปล่า แทน collect() เฉยๆ
    }

    // GET /my-leaves — หน้าสรุปสถานะคำขอลา + เรียนชดเชย
    public function index(Request $request)
    {
        $students = $this->myStudents($request);
        $studentIds = $students->pluck('id');

        $students->load([
            'enrollments' => fn($q) => $q->with('course')->where('status', 'active'),
        ]);

        $leaves = \App\Models\StudentLeave::whereIn('student_id', $studentIds)
            ->with(['student', 'enrollment.course', 'makeupRequest'])
            ->orderByDesc('created_at')
            ->get();

        $tab = $request->get('tab', 'pending');
        $filtered = match ($tab) {
            'approved' => $leaves->where('status', 'approved'),
            'rejected' => $leaves->where('status', 'rejected'),
            'all'      => $leaves,
            default    => $leaves->where('status', 'pending'),
        };

        // ===== สถิติสรุป (คำนวณจากข้อมูลจริงในระบบเท่านั้น) =====
        $pendingCount = $leaves->where('status', 'pending')->count();

        $approvedThisMonth = $leaves->where('status', 'approved')
            ->filter(fn($l) => $l->reviewed_at && $l->reviewed_at->isCurrentMonth());
        $approvedNormalCount = $approvedThisMonth->where('leave_type', 'normal')->count()
            + $approvedThisMonth->where('leave_type', 'no_makeup')->count();
        $approvedEmergencyCount = $approvedThisMonth->where('leave_type', 'emergency')->count();

        // คอร์สที่ใช้สิทธิ์ลาฉุกเฉินครบโควตาแล้ว (โควตาจริงตั้งไว้ที่ระดับคอร์ส)
        $emergencyFullEnrollments = collect();
        foreach ($students as $s) {
            foreach ($s->enrollments as $enr) {
                if ($enr->emergencyLeaveRemaining() <= 0 && $enr->emergencyLeaveQuota() > 0) {
                    $emergencyFullEnrollments->push($enr);
                }
            }
        }

        // ชั่วโมงเรียนชดเชยที่ยังไม่เสร็จสิ้น (pending/approved แต่ยังไม่ completed)
        $pendingMakeupHours = $leaves->pluck('makeupRequest')->filter()
            ->whereIn('overall_status', ['pending', 'approved'])
            ->sum(function ($m) {
                $start = \Carbon\Carbon::parse($m->start_time);
                $end = \Carbon\Carbon::parse($m->end_time);
                return $end->diffInMinutes($start) / 60;
            });

        $overdueCount = $leaves->pluck('makeupRequest')->filter()->where('is_overdue', true)->count();

        return view('leaves.my-index', compact(
            'students',
            'filtered',
            'leaves',
            'tab',
            'pendingCount',
            'approvedNormalCount',
            'approvedEmergencyCount',
            'emergencyFullEnrollments',
            'pendingMakeupHours',
            'overdueCount'
        ));
    }

    // GET /my-leaves/create — หน้าฟอร์มแจ้งลา (แยกออกมาต่างหาก)
    public function create(Request $request)
    {
        $students = $this->myStudents($request);
        $students->load([
            'enrollments' => fn($q) => $q->with(['course', 'teacher'])->where('status', 'active'),
        ]);

        $preselectedStudentId = $request->get('student_id', $students->first()?->id);

        return view('leaves.create', compact('students', 'preselectedStudentId'));
    }
}
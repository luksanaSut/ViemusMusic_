<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\LoyaltyService;

class StudentMembershipController extends Controller
{
    // POST /students/{student}/membership/recalculate
    public function recalculate(Student $student, LoyaltyService $loyalty)
    {
        $loyalty->recalculateMembership($student);

        return back()->with('success', 'คำนวณสถานะสมาชิกใหม่เรียบร้อยแล้ว');
    }
}

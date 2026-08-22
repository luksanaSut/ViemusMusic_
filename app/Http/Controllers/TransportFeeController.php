<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeachingSession;
use App\Models\TransportCompensation;
use Illuminate\Http\Request;

class TransportFeeController extends Controller
{
    // GET /transport-fees — ประวัติค่ารถทุกอาจารย์ (Admin)
    public function index(Request $request)
    {
        $periodStart = $request->get('period_start', now()->startOfMonth()->toDateString());
        $periodEnd = $request->get('period_end', now()->endOfMonth()->toDateString());

        $teachers = Teacher::where('is_active', true)->orderBy('full_name')->get();

        $summaries = $teachers->map(function ($t) use ($periodStart, $periodEnd) {
            $sessionFees = TeachingSession::where('teacher_id', $t->id)
                ->where('status', 'completed') // Business rule: เฉพาะคลาสที่สอนจริง
                ->whereBetween('session_date', [$periodStart, $periodEnd])
                ->sum('transport_fee_applied');

            $compensations = TransportCompensation::where('teacher_id', $t->id)
                ->whereBetween('compensation_date', [$periodStart, $periodEnd])
                ->sum('amount');

            return [
                'teacher'       => $t,
                'session_fees'  => $sessionFees,
                'compensations' => $compensations,
                'total'         => $sessionFees + $compensations,
            ];
        });

        return view('transport-fees.index', compact('teachers', 'summaries', 'periodStart', 'periodEnd'));
    }

    // GET /transport-fees/{teacher} — รายละเอียดค่ารถของอาจารย์คนหนึ่ง
    public function show(Request $request, Teacher $teacher)
    {
        $periodStart = $request->get('period_start', now()->startOfMonth()->toDateString());
        $periodEnd = $request->get('period_end', now()->endOfMonth()->toDateString());

        $sessions = TeachingSession::where('teacher_id', $teacher->id)
            ->where('status', 'completed')
            ->where('transport_fee_applied', '>', 0)
            ->whereBetween('session_date', [$periodStart, $periodEnd])
            ->orderBy('session_date')
            ->get();

        $compensations = TransportCompensation::where('teacher_id', $teacher->id)
            ->whereBetween('compensation_date', [$periodStart, $periodEnd])
            ->orderBy('compensation_date')
            ->get();

        $activeFee = $teacher->activeTransportFee();

        return view('transport-fees.show', compact('teacher', 'sessions', 'compensations', 'periodStart', 'periodEnd', 'activeFee'));
    }

    // POST /transport-fees/{teacher}/compensations — เพิ่มค่าชดเชยเพิ่มเติม
    public function storeCompensation(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'compensation_date' => ['required', 'date'],
            'amount'             => ['required', 'numeric', 'min:0.01'],
            'reason'             => ['required', 'string', 'max:255'],
        ]);

        $data['teacher_id'] = $teacher->id;
        $data['created_by'] = auth()->user()->name ?? 'แอดมิน';

        TransportCompensation::create($data);

        return back()->with('success', 'เพิ่มค่าชดเชยเพิ่มเติมเรียบร้อยแล้ว');
    }

    public function destroyCompensation(TransportCompensation $transportCompensation)
    {
        $transportCompensation->delete();
        return back()->with('success', 'ลบรายการค่าชดเชยแล้ว');
    }

    // ===== อาจารย์ดูประวัติค่ารถของตัวเอง =====
    public function myIndex(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 404, 'บัญชีนี้ยังไม่ได้ผูกกับข้อมูลอาจารย์');

        $periodStart = $request->get('period_start', now()->startOfMonth()->toDateString());
        $periodEnd = $request->get('period_end', now()->endOfMonth()->toDateString());

        $sessions = TeachingSession::where('teacher_id', $teacher->id)
            ->where('status', 'completed')
            ->where('transport_fee_applied', '>', 0)
            ->whereBetween('session_date', [$periodStart, $periodEnd])
            ->orderBy('session_date')
            ->get();

        $compensations = TransportCompensation::where('teacher_id', $teacher->id)
            ->whereBetween('compensation_date', [$periodStart, $periodEnd])
            ->orderBy('compensation_date')
            ->get();

        return view('transport-fees.my-index', compact('teacher', 'sessions', 'compensations', 'periodStart', 'periodEnd'));
    }
}
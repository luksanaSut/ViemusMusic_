<?php

namespace App\Http\Controllers;

use App\Models\PayrollRun;
use App\Models\PayrollRunItem;
use App\Models\Teacher;
use App\Models\TeachingSession;
use App\Models\TransportCompensation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PayrollController extends Controller
{
    // GET /payroll — ภาพรวมทุกอาจารย์ในรอบที่เลือก + ประวัติการจ่ายย้อนหลัง
    public function index(Request $request)
    {
        $periodStart = $request->get('period_start', now()->startOfMonth()->toDateString());
        $periodEnd = $request->get('period_end', now()->endOfMonth()->toDateString());

        $teachers = Teacher::where('is_active', true)->orderBy('full_name')->get();

        $runs = PayrollRun::with('teacher')
            ->where('period_start', $periodStart)
            ->where('period_end', $periodEnd)
            ->get()
            ->keyBy('teacher_id');

        // สรุปยอดคาบที่สอนจริงของแต่ละอาจารย์ในรอบนี้ (ยังไม่ได้สร้างรอบจ่ายก็แสดงตัวเลขคาดการณ์ให้ดูก่อน)
        $summaries = $teachers->map(function ($t) use ($periodStart, $periodEnd, $runs) {
            $sessionsQuery = TeachingSession::where('teacher_id', $t->id)
                ->where('status', 'completed') // Business rule: เฉพาะคลาสที่สอนจริง
                ->whereBetween('session_date', [$periodStart, $periodEnd]);

            return [
                'teacher'        => $t,
                'session_count'  => $sessionsQuery->count(),
                'income_estimate' => $sessionsQuery->sum('income_amount'),
                'run'            => $runs->get($t->id),
            ];
        });

        $history = PayrollRun::with('teacher')->orderByDesc('period_start')->paginate(15);

        return view('payroll.index', compact('teachers', 'summaries', 'periodStart', 'periodEnd', 'history'));
    }

    // GET /payroll/{teacher}/generate?period_start=&period_end= — สร้าง/ดูรายละเอียดรอบจ่ายของอาจารย์คนหนึ่ง
    public function show(Request $request, Teacher $teacher)
    {
        $periodStart = $request->get('period_start', now()->startOfMonth()->toDateString());
        $periodEnd = $request->get('period_end', now()->endOfMonth()->toDateString());

        $run = PayrollRun::with('items.teachingSession')
            ->where('teacher_id', $teacher->id)
            ->where('period_start', $periodStart)
            ->where('period_end', $periodEnd)
            ->first();

        // ยังไม่เคยสร้างรอบจ่าย -> สร้างฉบับร่างให้ทันที (Business rule: ดึงเฉพาะคลาสที่สอนจริง)
        if (!$run) {
            $sessions = TeachingSession::where('teacher_id', $teacher->id)
                ->where('status', 'completed')
                ->whereBetween('session_date', [$periodStart, $periodEnd])
                ->orderBy('session_date')
                ->get();

            DB::transaction(function () use ($teacher, $periodStart, $periodEnd, $sessions, &$run) {
                $teachingIncomeTotal = $sessions->sum('income_amount');
                $transportTotal = $sessions->sum('transport_fee_applied')
                    + \App\Models\TransportCompensation::where('teacher_id', $teacher->id)
                    ->whereBetween('compensation_date', [$periodStart, $periodEnd])
                    ->sum('amount');

                $run = PayrollRun::create([
                    'teacher_id'             => $teacher->id,
                    'period_start'           => $periodStart,
                    'period_end'             => $periodEnd,
                    'teaching_income_total'  => $teachingIncomeTotal,
                    'transport_fee_total'    => $transportTotal,
                    'total_amount'           => $teachingIncomeTotal + $transportTotal,
                    'status'                 => 'draft',
                    'generated_by'           => auth()->user()->name ?? 'แอดมิน',
                ]);

                foreach ($sessions as $s) {
                    PayrollRunItem::create([
                        'payroll_run_id'       => $run->id,
                        'teaching_session_id'  => $s->id,
                        'income_amount'        => $s->income_amount,
                    ]);
                }
            });

            $run->load('items.teachingSession');
        }

        return view('payroll.show', compact('teacher', 'run'));
    }

    // POST /payroll/{payrollRun}/adjust — Admin ปรับแก้ยอดเงิน
    public function adjust(Request $request, PayrollRun $payrollRun)
    {
        if ($payrollRun->status === 'paid') {
            return back()->with('error', 'รอบนี้จ่ายเงินไปแล้ว ไม่สามารถแก้ไขยอดได้');
        }

        $data = $request->validate([
            'adjustment_amount' => ['required', 'numeric'],
            'adjustment_reason' => ['required', 'string', 'max:500'],
        ]);

        $payrollRun->update([
            'adjustment_amount' => $data['adjustment_amount'],
            'adjustment_reason' => $data['adjustment_reason'],
            'adjusted_by'       => auth()->user()->name ?? 'แอดมิน',
        ]);
        $payrollRun->recalculateTotal();

        return back()->with('success', 'ปรับแก้ยอดเงินเรียบร้อยแล้ว');
    }

    // POST /payroll/{payrollRun}/recalculate — คำนวณยอดค่าสอน/ค่ารถใหม่จากข้อมูลสดปัจจุบัน
    // ใช้เมื่อมีการเพิ่มค่าชดเชยเพิ่มเติม หรือตั้ง/แก้เงื่อนไขค่ารถ หลังจากสร้างรอบจ่ายไปแล้ว
    public function recalculate(PayrollRun $payrollRun)
    {
        if ($payrollRun->status !== 'draft') {
            return back()->with('error', 'คำนวณยอดใหม่ได้เฉพาะรอบที่ยังเป็นฉบับร่างเท่านั้น');
        }

        $teacher = $payrollRun->teacher;
        $periodStart = $payrollRun->period_start->toDateString();
        $periodEnd = $payrollRun->period_end->toDateString();

        DB::transaction(function () use ($payrollRun, $teacher, $periodStart, $periodEnd) {
            $sessions = TeachingSession::where('teacher_id', $teacher->id)
                ->where('status', 'completed')
                ->whereBetween('session_date', [$periodStart, $periodEnd])
                ->get();

            $teachingIncomeTotal = $sessions->sum('income_amount');
            $transportFromSessions = $sessions->sum('transport_fee_applied');
            $transportCompensations = TransportCompensation::where('teacher_id', $teacher->id)
                ->whereBetween('compensation_date', [$periodStart, $periodEnd])
                ->sum('amount');

            $payrollRun->update([
                'teaching_income_total' => $teachingIncomeTotal,
                'transport_fee_total'   => $transportFromSessions + $transportCompensations,
            ]);
            $payrollRun->recalculateTotal();

            // อัปเดตรายการ items ให้ตรงกับ session ปัจจุบัน (เผื่อมีคาบใหม่เกิดขึ้นหลังสร้างรอบจ่าย)
            $payrollRun->items()->delete();
            foreach ($sessions as $s) {
                PayrollRunItem::create([
                    'payroll_run_id'      => $payrollRun->id,
                    'teaching_session_id' => $s->id,
                    'income_amount'       => $s->income_amount,
                ]);
            }
        });

        return back()->with('success', 'คำนวณยอดใหม่เรียบร้อยแล้ว');
    }

    // POST /payroll/{payrollRun}/confirm — ยืนยันยอด (ล็อกไม่ให้แก้ไขต่อจนกว่าจะยกเลิกยืนยัน)
    public function confirm(PayrollRun $payrollRun)
    {
        $payrollRun->update(['status' => 'confirmed']);
        return back()->with('success', 'ยืนยันยอดเงินเดือนรอบนี้แล้ว พร้อมนำไปจ่ายจริง');
    }

    // POST /payroll/{payrollRun}/mark-paid
    public function markPaid(Request $request, PayrollRun $payrollRun)
    {
        $data = $request->validate([
            'paid_date'      => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:100'],
        ]);

        $payrollRun->update([
            'status'         => 'paid',
            'paid_date'      => $data['paid_date'],
            'payment_method' => $data['payment_method'],
            'paid_by'        => auth()->user()->name ?? 'แอดมิน',
        ]);

        return back()->with('success', 'บันทึกการจ่ายเงินเรียบร้อยแล้ว');
    }

    // GET /payroll/{payrollRun}/export-pdf
    public function exportPdf(PayrollRun $payrollRun)
    {
        $payrollRun->load(['teacher', 'items.teachingSession']);
        $pdf = Pdf::loadView('payroll.pdf', compact('payrollRun'))->setPaper('a4');

        return $pdf->download("payroll-{$payrollRun->teacher->teacher_code}-{$payrollRun->period_start->format('Ym')}.pdf");
    }

    // GET /payroll/{payrollRun}/export-excel — CSV เปิดได้ด้วย Excel
    public function exportExcel(PayrollRun $payrollRun)
    {
        $payrollRun->load(['teacher', 'items.teachingSession']);

        $filename = "payroll-{$payrollRun->teacher->teacher_code}-{$payrollRun->period_start->format('Ym')}.csv";

        return Response::streamDownload(function () use ($payrollRun) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF"); // BOM กัน Excel อ่านภาษาไทยเพี้ยน

            fputcsv($handle, ['วันที่สอน', 'นักเรียน', 'ชั่วโมง', 'เรทที่ใช้', 'ค่าเดินทาง', 'รายได้']);
            foreach ($payrollRun->items as $item) {
                $s = $item->teachingSession;
                fputcsv($handle, [
                    optional($s->session_date)->format('d/m/Y'),
                    $s->student_name,
                    $s->hours,
                    $s->rate_applied,
                    $s->transport_fee_applied,
                    $item->income_amount,
                ]);
            }
            fputcsv($handle, []);
            fputcsv($handle, ['', '', '', '', 'รวมค่าสอน', $payrollRun->teaching_income_total]);
            fputcsv($handle, ['', '', '', '', 'รวมค่าเดินทาง', $payrollRun->transport_fee_total]);
            fputcsv($handle, ['', '', '', '', 'ปรับปรุงยอด (' . $payrollRun->adjustment_reason . ')', $payrollRun->adjustment_amount]);
            fputcsv($handle, ['', '', '', '', 'ยอดรวมสุทธิ', $payrollRun->total_amount]);

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    // ===== อาจารย์ดูประวัติการจ่ายของตัวเอง =====
    public function myIndex(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 404, 'บัญชีนี้ยังไม่ได้ผูกกับข้อมูลอาจารย์');

        $runs = PayrollRun::where('teacher_id', $teacher->id)
            ->orderByDesc('period_start')
            ->paginate(15);

        return view('payroll.my-index', compact('teacher', 'runs'));
    }
}
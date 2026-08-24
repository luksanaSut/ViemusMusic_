<?php

namespace App\Http\Controllers;

use App\Exports\RevenueReportExport;
use App\Exports\StudentReportExport;
use App\Exports\TeacherPerformanceReportExport;
use App\Models\Teacher;
use App\Services\FinanceService;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    // GET /reports
    public function index()
    {
        return view('reports.index');
    }

    // GET /reports/students
    public function students(Request $request, ReportService $report)
    {
        [$start, $end] = $this->resolveDateRange($request);

        $data = $this->buildStudentReportData($report, $start, $end);

        return view('reports.students', $data);
    }

    public function exportStudentsExcel(Request $request, ReportService $report)
    {
        [$start, $end] = $this->resolveDateRange($request);
        $data = $this->buildStudentReportData($report, $start, $end);

        return Excel::download(new StudentReportExport($data), 'student-report-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.xlsx');
    }

    public function exportStudentsPdf(Request $request, ReportService $report)
    {
        [$start, $end] = $this->resolveDateRange($request);
        $data = $this->buildStudentReportData($report, $start, $end);

        return Pdf::loadView('reports.students-pdf', $data)->setPaper('a4')
            ->download('student-report-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.pdf');
    }

    // GET /reports/revenue
    public function revenue(Request $request, ReportService $report, FinanceService $finance)
    {
        [$start, $end, $period] = $this->resolveRequestPeriod($request, $finance);

        $data = $this->buildRevenueReportData($report, $finance, $start, $end, $period);

        return view('reports.revenue', $data);
    }

    public function exportRevenueExcel(Request $request, ReportService $report, FinanceService $finance)
    {
        [$start, $end, $period] = $this->resolveRequestPeriod($request, $finance);
        $data = $this->buildRevenueReportData($report, $finance, $start, $end, $period);

        return Excel::download(new RevenueReportExport($data), 'revenue-report-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.xlsx');
    }

    public function exportRevenuePdf(Request $request, ReportService $report, FinanceService $finance)
    {
        [$start, $end, $period] = $this->resolveRequestPeriod($request, $finance);
        $data = $this->buildRevenueReportData($report, $finance, $start, $end, $period);

        return Pdf::loadView('reports.revenue-pdf', $data)->setPaper('a4')
            ->download('revenue-report-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.pdf');
    }

    // GET /reports/teacher-performance
    public function teacherPerformance(Request $request, ReportService $report)
    {
        [$start, $end] = $this->resolveDateRange($request);

        $data = $this->buildTeacherPerformanceData($report, $request, $start, $end);

        return view('reports.teacher-performance', $data);
    }

    public function exportTeacherPerformanceExcel(Request $request, ReportService $report)
    {
        [$start, $end] = $this->resolveDateRange($request);
        $data = $this->buildTeacherPerformanceData($report, $request, $start, $end);

        return Excel::download(new TeacherPerformanceReportExport($data), 'teacher-performance-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.xlsx');
    }

    public function exportTeacherPerformancePdf(Request $request, ReportService $report)
    {
        [$start, $end] = $this->resolveDateRange($request);
        $data = $this->buildTeacherPerformanceData($report, $request, $start, $end);

        return Pdf::loadView('reports.teacher-performance-pdf', $data)->setPaper('a4')
            ->download('teacher-performance-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.pdf');
    }

    private function buildStudentReportData(ReportService $report, Carbon $start, Carbon $end): array
    {
        return [
            'start'       => $start,
            'end'         => $end,
            'summary'     => $report->studentSummary($start, $end),
            'byCourse'    => $report->studentsByCourse(),
            'byInstrument' => $report->studentsByInstrument(),
            'byBranch'    => $report->studentsByBranch(),
        ];
    }

    private function buildRevenueReportData(ReportService $report, FinanceService $finance, Carbon $start, Carbon $end, ?string $period): array
    {
        return [
            'start'         => $start,
            'end'           => $end,
            'period'        => $period,
            'courseIncome'  => $finance->courseIncome($start, $end),
            'productIncome' => $finance->productIncome($start, $end),
            'byMethod'      => $report->revenueByPaymentMethod($start, $end),
            'byBranch'      => $report->revenueByBranch($start, $end),
        ];
    }

    private function buildTeacherPerformanceData(ReportService $report, Request $request, Carbon $start, Carbon $end): array
    {
        $branch = $request->get('branch');

        return [
            'start'    => $start,
            'end'      => $end,
            'branch'   => $branch,
            'branches' => Teacher::whereNotNull('branch')->distinct()->orderBy('branch')->pluck('branch'),
            'rows'     => $report->teacherPerformance($start, $end, $branch),
        ];
    }

    // ช่วงวันที่แบบง่าย (date_from/date_to) — ใช้กับรายงานนักเรียน/Performance อาจารย์ ค่าเริ่มต้นเดือนปัจจุบัน
    private function resolveDateRange(Request $request): array
    {
        $start = $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : now()->startOfMonth();
        $end = $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : now()->endOfMonth();

        return [$start, $end];
    }

    // ช่วงเวลาแบบ preset (วัน/สัปดาห์/เดือน/ปี) + custom — ใช้กับรายงานรายได้ (มิเรอร์ FinanceController::resolveRequestPeriod)
    private function resolveRequestPeriod(Request $request, FinanceService $finance): array
    {
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $start = Carbon::parse($request->date_from)->startOfDay();
            $end = Carbon::parse($request->date_to)->endOfDay();

            return [$start, $end, null];
        }

        $period = $request->get('period', 'monthly');
        [$start, $end] = $finance->resolvePeriod($period, $request->get('ref_date'));

        return [$start, $end, $period];
    }
}

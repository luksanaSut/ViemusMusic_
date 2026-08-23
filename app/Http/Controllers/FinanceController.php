<?php

namespace App\Http\Controllers;

use App\Services\FinanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FinanceController extends Controller
{
    // GET /finance — ภาพรวมกำไร/ขาดทุนของเดือนปัจจุบัน
    public function dashboard(FinanceService $finance)
    {
        [$start, $end] = $finance->resolvePeriod('monthly');
        $summary = $finance->summary($start, $end);

        return view('finance.dashboard', compact('summary', 'start', 'end'));
    }

    // GET /finance/report — รายงานเลือกช่วงเวลาได้ (วัน/สัปดาห์/เดือน/ปี หรือกำหนดเอง)
    public function report(Request $request, FinanceService $finance)
    {
        [$start, $end, $period] = $this->resolveRequestPeriod($request, $finance);
        $summary = $finance->summary($start, $end);

        return view('finance.report', compact('summary', 'start', 'end', 'period'));
    }

    // GET /finance/report/export — ส่งออกรายงานตามช่วงเดียวกับหน้ารายงานเป็น CSV
    public function exportCsv(Request $request, FinanceService $finance)
    {
        [$start, $end] = $this->resolveRequestPeriod($request, $finance);
        $summary = $finance->summary($start, $end);

        $filename = 'finance-report-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.csv';

        return Response::streamDownload(function () use ($summary, $start, $end) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF"); // BOM กัน Excel อ่านภาษาไทยเพี้ยน

            fputcsv($handle, ['รายงานการเงิน', $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y')]);
            fputcsv($handle, []);

            fputcsv($handle, ['รายรับ', 'จำนวนเงิน']);
            fputcsv($handle, ['รายได้จากคอร์สเรียน', $summary['income']['course']]);
            fputcsv($handle, ['รายได้จากการขายสินค้า', $summary['income']['product']]);
            fputcsv($handle, ['รวมรายรับ', $summary['income']['total']]);
            fputcsv($handle, []);

            fputcsv($handle, ['รายจ่าย', 'จำนวนเงิน']);
            fputcsv($handle, ['คอร์สเรียน', $summary['expense']['course']]);
            fputcsv($handle, ['ค่าซื้อสินค้า', $summary['expense']['product_cost']]);
            fputcsv($handle, ['ค่าเช่า', $summary['expense']['rent']]);
            fputcsv($handle, ['ค่าพนักงาน (รวมเงินเดือนอาจารย์)', $summary['expense']['staff']]);
            fputcsv($handle, ['ค่าซ่อมบำรุง', $summary['expense']['maintenance']]);
            fputcsv($handle, ['ค่าใช้จ่ายอื่นๆ', $summary['expense']['other']]);
            fputcsv($handle, ['รวมรายจ่าย', $summary['expense']['total']]);
            fputcsv($handle, []);

            fputcsv($handle, ['กำไร/ขาดทุนสุทธิ', $summary['net_profit']]);

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

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

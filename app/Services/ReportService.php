<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\SaleOrder;
use App\Models\Student;
use App\Models\StoreSale;
use App\Models\Teacher;
use App\Models\TeacherLeave;
use App\Models\TeachingSession;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    private const UNSPECIFIED_BRANCH = 'ไม่ระบุสาขา';

    public function __construct(private FinanceService $finance)
    {
    }

    // ===== FR-RP-001 รายงานนักเรียน =====

    public function studentSummary(Carbon $start, Carbon $end): array
    {
        return [
            'total' => Student::count(),
            'new'   => Student::whereBetween('created_at', [$start, $end])->count(),
        ];
    }

    public function studentsByCourse(): Collection
    {
        return Enrollment::where('enrollments.status', 'active')
            ->join('courses', 'courses.id', '=', 'enrollments.course_id')
            ->selectRaw('courses.name as label, COUNT(DISTINCT enrollments.student_id) as total')
            ->groupBy('courses.id', 'courses.name')
            ->orderByDesc('total')
            ->get();
    }

    public function studentsByInstrument(): Collection
    {
        return Enrollment::where('enrollments.status', 'active')
            ->join('courses', 'courses.id', '=', 'enrollments.course_id')
            ->leftJoin('instruments', 'instruments.id', '=', 'courses.instrument_id')
            ->selectRaw('COALESCE(instruments.name, "ไม่ระบุเครื่องดนตรี") as label, COUNT(DISTINCT enrollments.student_id) as total')
            ->groupBy('instruments.id', 'instruments.name')
            ->orderByDesc('total')
            ->get();
    }

    // อนุมานสาขาของนักเรียนจาก SaleOrder (paid) ล่าสุดของนักเรียนคนนั้น — ไม่มีก็ "ไม่ระบุสาขา"
    public function studentsByBranch(): Collection
    {
        $latestOrderIds = SaleOrder::where('status', 'paid')
            ->selectRaw('MAX(id) as id')
            ->groupBy('student_id');

        $branchCounts = SaleOrder::whereIn('id', $latestOrderIds)
            ->selectRaw('COALESCE(branch, "' . self::UNSPECIFIED_BRANCH . '") as label, COUNT(*) as total')
            ->groupBy('label')
            ->pluck('total', 'label');

        $studentsWithOrder = SaleOrder::where('status', 'paid')->distinct('student_id')->count('student_id');
        $noOrderCount = max(0, Student::count() - $studentsWithOrder);

        $result = collect($branchCounts)->map(fn ($total, $label) => ['label' => $label, 'total' => (int) $total])->values();

        if ($noOrderCount > 0) {
            $existing = $result->firstWhere('label', self::UNSPECIFIED_BRANCH);
            if ($existing) {
                $result = $result->map(fn ($row) => $row['label'] === self::UNSPECIFIED_BRANCH
                    ? ['label' => $row['label'], 'total' => $row['total'] + $noOrderCount]
                    : $row);
            } else {
                $result->push(['label' => self::UNSPECIFIED_BRANCH, 'total' => $noOrderCount]);
            }
        }

        return $result->sortByDesc('total')->values();
    }

    // ===== FR-RP-002 รายงานรายได้ =====

    public function revenueByPeriodType(string $type, ?string $refDate = null): array
    {
        [$start, $end] = $this->finance->resolvePeriod($type, $refDate);

        return [
            'start'   => $start,
            'end'     => $end,
            'course'  => $this->finance->courseIncome($start, $end),
            'product' => $this->finance->productIncome($start, $end),
        ];
    }

    public function revenueByPaymentMethod(Carbon $start, Carbon $end): array
    {
        $methods = ['cash', 'transfer', 'credit_card', 'promptpay', 'other'];

        $courseByMethod = SaleOrder::where('status', 'paid')
            ->whereBetween('updated_at', [$start, $end])
            ->selectRaw('payment_method, SUM(net_payable) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $productByMethod = StoreSale::where('status', 'completed')
            ->whereBetween('updated_at', [$start, $end])
            ->selectRaw('payment_method, SUM(COALESCE(net_payable, total_amount)) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $result = [];
        foreach ($methods as $method) {
            $result[$method] = (float) ($courseByMethod[$method] ?? 0) + (float) ($productByMethod[$method] ?? 0);
        }

        return $result;
    }

    // รายได้คอร์สแยกตามสาขาได้จริง; รายได้สินค้าไม่มีสาขาเลย รวมเข้ากอง "ไม่ระบุสาขา"
    public function revenueByBranch(Carbon $start, Carbon $end): array
    {
        $courseByBranch = SaleOrder::where('status', 'paid')
            ->whereBetween('updated_at', [$start, $end])
            ->selectRaw('COALESCE(branch, "' . self::UNSPECIFIED_BRANCH . '") as label, SUM(net_payable) as total')
            ->groupBy('label')
            ->pluck('total', 'label');

        $result = [];
        foreach ($courseByBranch as $label => $total) {
            $result[$label] = (float) $total;
        }

        $productTotal = $this->finance->productIncome($start, $end);
        $result[self::UNSPECIFIED_BRANCH] = ($result[self::UNSPECIFIED_BRANCH] ?? 0) + $productTotal;

        return $result;
    }

    // ===== FR-RP-003 รายงาน Performance อาจารย์ =====

    public function teacherPerformance(Carbon $start, Carbon $end, ?string $branch = null): Collection
    {
        $teachers = Teacher::where('is_active', true)
            ->when($branch, fn ($q) => $q->where('branch', $branch))
            ->orderBy('full_name')
            ->get();

        return $teachers->map(function (Teacher $teacher) use ($start, $end) {
            $sessions = TeachingSession::where('teacher_id', $teacher->id)
                ->where('status', 'completed')
                ->whereBetween('session_date', [$start->toDateString(), $end->toDateString()]);

            return [
                'teacher'       => $teacher,
                'hours'         => (float) (clone $sessions)->sum('hours'),
                'class_count'   => (clone $sessions)->count(),
                'student_count' => Enrollment::where('teacher_id', $teacher->id)
                    ->where('status', 'active')
                    ->distinct('student_id')
                    ->count('student_id'),
                'leave_count'   => TeacherLeave::where('teacher_id', $teacher->id)
                    ->where('status', 'approved')
                    ->whereBetween('leave_date_from', [$start->toDateString(), $end->toDateString()])
                    ->count(),
            ];
        });
    }
}

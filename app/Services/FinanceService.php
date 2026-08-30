<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\PayrollRun;
use App\Models\SaleOrder;
use App\Models\StoreSale;
use App\Models\TrialPayment;
use Carbon\Carbon;

class FinanceService
{
    public function courseIncome(Carbon $start, Carbon $end): float
    {
        return (float) SaleOrder::where('status', 'paid')
            ->whereBetween('updated_at', [$start, $end])
            ->sum('net_payable');
    }

    public function productIncome(Carbon $start, Carbon $end): float
    {
        // net_payable เป็น null ได้สำหรับออเดอร์เก่าก่อนมีคอลัมน์นี้ — fallback เป็น total_amount (ยอดก่อนหักส่วนลด ซึ่งตอนนั้นยังไม่มีส่วนลดอยู่แล้ว)
        return (float) StoreSale::where('status', 'completed')
            ->whereBetween('updated_at', [$start, $end])
            ->selectRaw('COALESCE(SUM(COALESCE(net_payable, total_amount)), 0) as total')
            ->value('total');
    }

    public function trialIncome(Carbon $start, Carbon $end): float
    {
        return (float) TrialPayment::where('status', 'confirmed')
            ->whereBetween('transaction_at', [$start, $end])
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'refund' THEN -amount ELSE amount END), 0) as total")
            ->value('total');
    }

    public function teacherPayrollExpense(Carbon $start, Carbon $end): float
    {
        return (float) PayrollRun::where('status', 'paid')
            ->whereBetween('paid_date', [$start->toDateString(), $end->toDateString()])
            ->sum('total_amount');
    }

    // รายจ่ายบันทึกมือ แยกตามหมวด — คืนทุกหมวดแม้ยอดเป็น 0 เพื่อให้ปลายทางวนแสดงผลครบทุกหมวดเสมอ
    public function manualExpensesByCategory(Carbon $start, Carbon $end): array
    {
        $categories = ['course', 'product_cost', 'rent', 'staff', 'maintenance', 'other'];

        $sums = Expense::query()
            ->between($start->toDateString(), $end->toDateString())
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $result = [];
        foreach ($categories as $category) {
            $result[$category] = (float) ($sums[$category] ?? 0);
        }

        return $result;
    }

    public function summary(Carbon $start, Carbon $end): array
    {
        $courseIncome = $this->courseIncome($start, $end);
        $productIncome = $this->productIncome($start, $end);
        $trialIncome = $this->trialIncome($start, $end);

        $manual = $this->manualExpensesByCategory($start, $end);
        $teacherPayroll = $this->teacherPayrollExpense($start, $end);

        $expense = $manual;
        $expense['staff'] = $manual['staff'] + $teacherPayroll;

        $incomeTotal = $courseIncome + $productIncome + $trialIncome;
        $expenseTotal = array_sum($expense);

        return [
            'income' => [
                'course'  => $courseIncome,
                'product' => $productIncome,
                'trial'   => $trialIncome,
                'total'   => $incomeTotal,
            ],
            'expense' => [
                ...$expense,
                'teacher_payroll' => $teacherPayroll,
                'staff_manual'    => $manual['staff'],
                'total'           => $expenseTotal,
            ],
            'net_profit' => $incomeTotal - $expenseTotal,
        ];
    }

    // แปลง period type + วันที่อ้างอิง เป็นช่วง [start, end]
    public function resolvePeriod(string $type, ?string $refDate = null): array
    {
        $ref = $refDate ? Carbon::parse($refDate) : now();

        return match ($type) {
            'daily'   => [$ref->copy()->startOfDay(), $ref->copy()->endOfDay()],
            'weekly'  => [$ref->copy()->startOfWeek(), $ref->copy()->endOfWeek()],
            'yearly'  => [$ref->copy()->startOfYear(), $ref->copy()->endOfYear()],
            default   => [$ref->copy()->startOfMonth(), $ref->copy()->endOfMonth()],
        };
    }
}

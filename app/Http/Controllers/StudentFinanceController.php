<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentCreditTransaction;
use Illuminate\Http\Request;

class StudentFinanceController extends Controller
{
    // POST /students/{student}/payments
    public function storePayment(Request $request, Student $student)
    {
        $data = $request->validate([
            'invoice_no'   => ['required', 'string', 'max:50', 'unique:payments,invoice_no'],
            'enrollment_id' => ['nullable', 'exists:enrollments,id'],
            'amount'       => ['required', 'numeric', 'min:0'],
            'paid_amount'  => ['nullable', 'numeric', 'min:0'],
            'due_date'     => ['nullable', 'date'],
            'paid_date'    => ['nullable', 'date'],
            'method'       => ['nullable', 'in:cash,transfer,credit_card,other'],
            'note'         => ['nullable', 'string', 'max:1000'],
        ]);

        $paidAmount = $data['paid_amount'] ?? 0;
        $data['status'] = $paidAmount <= 0
            ? 'pending'
            : ($paidAmount >= $data['amount'] ? 'paid' : 'partial');

        $data['student_id'] = $student->id;

        $student->payments()->create($data);

        return back()->with('success', 'บันทึกรายการชำระเงินเรียบร้อยแล้ว');
    }

    // POST /students/{student}/credits — เติม/ปรับ/คืนเครดิต
    public function storeCredit(Request $request, Student $student)
    {
        $data = $request->validate([
            'type'   => ['required', 'in:topup,use,refund,adjustment'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        // use = หักออกจากยอด, ที่เหลือเป็นการเพิ่ม
        $signedAmount = $data['type'] === 'use' ? -abs($data['amount']) : abs($data['amount']);
        $newBalance = $student->creditBalance() + $signedAmount;

        if ($newBalance < 0) {
            return back()->with('error', 'ยอดเครดิตไม่พอสำหรับการหักครั้งนี้');
        }

        StudentCreditTransaction::create([
            'student_id'    => $student->id,
            'type'          => $data['type'],
            'amount'        => $signedAmount,
            'balance_after' => $newBalance,
            'reason'        => $data['reason'] ?? null,
        ]);

        return back()->with('success', 'บันทึกรายการเครดิตเรียบร้อยแล้ว');
    }
}

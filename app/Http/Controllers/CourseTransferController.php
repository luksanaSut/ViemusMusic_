<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseTransferRequest;
use App\Models\Course;
use App\Models\CourseTransfer;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseTransferController extends Controller
{
    // GET /course-transfers — ประวัติการเปลี่ยนคอร์สทั้งหมด
    public function index(Request $request)
    {
        $transfers = CourseTransfer::with(['student', 'oldCourse', 'newCourse'])
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->q;
                $q->where('transfer_no', 'like', "%{$term}%")
                    ->orWhereHas('student', fn($qq) => $qq->where('full_name', 'like', "%{$term}%"));
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('course-transfers.index', compact('transfers'));
    }

    // GET /course-transfers/create?enrollment_id=
    public function create(Request $request)
    {
        $enrollment = Enrollment::with(['student', 'course'])->findOrFail($request->enrollment_id);

        if ($enrollment->status !== 'active') {
            return redirect()->route('students.show', $enrollment->student)
                ->with('error', 'เปลี่ยนคอร์สได้เฉพาะคอร์สที่กำลังเรียนอยู่ (สถานะ Active) เท่านั้น');
        }

        $courses = Course::where('is_active', true)->where('id', '!=', $enrollment->course_id)
            ->with('teachers')->orderBy('name')->get();

        return view('course-transfers.create', compact('enrollment', 'courses'));
    }

    // POST /course-transfers — สร้างรายการเปลี่ยนคอร์ส (คำนวณส่วนต่างอัตโนมัติ)
    public function store(StoreCourseTransferRequest $request)
    {
        $data = $request->validated();
        $oldEnrollment = Enrollment::with(['student', 'course'])->findOrFail($data['old_enrollment_id']);
        $newCourse = Course::findOrFail($data['new_course_id']);

        if ($oldEnrollment->status !== 'active') {
            return back()->with('error', 'เปลี่ยนคอร์สได้เฉพาะคอร์สที่กำลังเรียนอยู่เท่านั้น');
        }

        // ตรวจที่นั่งคงเหลือของคอร์สใหม่ (ใช้ business rule เดียวกับตอนสมัครเรียน)
        if ($newCourse->class_type !== 'private') {
            $used = Enrollment::where('course_id', $newCourse->id)->where('status', 'active')->count();
            if (($newCourse->max_students ?? 0) - $used <= 0) {
                return back()->withInput()->with('error', 'คอร์สใหม่ที่เลือกเต็มแล้ว ไม่สามารถย้ายไปได้');
            }
        }

        $remainingValue = $oldEnrollment->remainingValue();
        $teacherFee = (float) ($data['teacher_change_fee'] ?? 0);
        $newPrice = (float) $newCourse->price;
        $priceDifference = round(($newPrice + $teacherFee) - $remainingValue, 2);

        // Business rule: ราคาต่ำกว่า -> เก็บเครดิตทันที / ราคาสูงกว่า -> ต้องจ่ายก่อนยืนยัน (ยังไม่ finalize enrollment)
        $paymentStatus = $priceDifference > 0 ? 'pending_payment' : 'not_required';
        $status = $priceDifference > 0 ? 'pending_payment' : 'completed';

        $transfer = null;

        DB::transaction(function () use (
            $data,
            $oldEnrollment,
            $newCourse,
            $remainingValue,
            $teacherFee,
            $newPrice,
            $priceDifference,
            $paymentStatus,
            $status,
            &$transfer
        ) {
            $transfer = CourseTransfer::create([
                'transfer_no'                => 'CT-' . now()->format('Ymd') . '-' . str_pad(CourseTransfer::whereDate('created_at', now())->count() + 1, 4, '0', STR_PAD_LEFT),
                'student_id'                 => $oldEnrollment->student_id,
                'old_enrollment_id'          => $oldEnrollment->id,
                'old_course_id'              => $oldEnrollment->course_id,
                'new_course_id'              => $newCourse->id,
                'new_teacher_id'             => $data['new_teacher_id'] ?? null,
                'old_course_remaining_value' => $remainingValue,
                'new_course_price'           => $newPrice,
                'teacher_change_fee'         => $teacherFee,
                'price_difference'           => $priceDifference,
                'payment_status'             => $paymentStatus,
                'status'                     => $status,
                'reason'                     => $data['reason'] ?? null,
                'notes'                      => $data['notes'] ?? null,
                'transferred_by'             => auth()->user()->name ?? 'แอดมิน',
            ]);

            // ถ้าไม่ต้องจ่ายเพิ่ม (ราคาเท่ากันหรือต่ำกว่า) -> ทำรายการให้เสร็จทันที
            if ($status === 'completed') {
                $this->finalizeTransfer($transfer);
            }
        });

        $msg = $status === 'completed'
            ? 'เปลี่ยนคอร์สเรียนเรียบร้อยแล้ว'
            : 'สร้างรายการเปลี่ยนคอร์สแล้ว กรุณาชำระส่วนต่างเพิ่มเพื่อยืนยันการเปลี่ยนคอร์ส';

        return redirect()->route('course-transfers.show', $transfer)->with('success', $msg);
    }

    // GET /course-transfers/{courseTransfer}
    public function show(CourseTransfer $courseTransfer)
    {
        $courseTransfer->load(['student', 'oldCourse', 'newCourse', 'newTeacher', 'oldEnrollment', 'newEnrollment']);

        return view('course-transfers.show', compact('courseTransfer'));
    }

    // POST /course-transfers/{courseTransfer}/confirm-payment — Business rule: ต้องจ่ายส่วนต่างก่อนยืนยัน
    public function confirmPayment(Request $request, CourseTransfer $courseTransfer)
    {
        if ($courseTransfer->status !== 'pending_payment') {
            return back()->with('error', 'รายการนี้ไม่ได้อยู่ในสถานะรอชำระเงิน');
        }

        $data = $request->validate([
            'payment_method'    => ['required', 'in:promptpay,transfer,credit_card,cash'],
            'payment_reference' => ['required_if:payment_method,credit_card', 'nullable', 'string', 'max:100'],
            'payment_proof'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        DB::transaction(function () use ($request, $data, $courseTransfer) {
            if ($request->hasFile('payment_proof')) {
                $courseTransfer->payment_proof_path = $request->file('payment_proof')->store('payment-proofs', 'public');
            }
            $courseTransfer->payment_method = $data['payment_method'];
            $courseTransfer->payment_reference = $data['payment_reference'] ?? null;
            $courseTransfer->payment_status = 'paid';
            $courseTransfer->save();

            // บันทึกเป็นรายการชำระเงินจริงของนักเรียน (ใช้ pattern เดียวกับระบบขายคอร์ส)
            $payment = Payment::create([
                'student_id'    => $courseTransfer->student_id,
                'invoice_no'    => $courseTransfer->transfer_no,
                'amount'        => $courseTransfer->price_difference,
                'paid_amount'   => $courseTransfer->price_difference,
                'paid_date'     => now()->toDateString(),
                'method'        => $data['payment_method'] === 'promptpay' ? 'other' : $data['payment_method'],
                'status'        => 'paid',
                'note'          => 'ชำระส่วนต่างค่าเปลี่ยนคอร์ส (' . $courseTransfer->transfer_no . ')',
            ]);
            $courseTransfer->payment_id = $payment->id;
            $courseTransfer->save();

            $this->finalizeTransfer($courseTransfer);
        });

        return back()->with('success', 'ยืนยันชำระส่วนต่างและเปลี่ยนคอร์สเรียบร้อยแล้ว');
    }

    // PATCH /course-transfers/{courseTransfer}/cancel
    public function cancel(CourseTransfer $courseTransfer)
    {
        if ($courseTransfer->status !== 'pending_payment') {
            return back()->with('error', 'ยกเลิกได้เฉพาะรายการที่ยังรอชำระเงินเท่านั้น');
        }

        $courseTransfer->update(['status' => 'cancelled']);

        return back()->with('success', 'ยกเลิกรายการเปลี่ยนคอร์สเรียบร้อยแล้ว');
    }

    // ===== ทำรายการเปลี่ยนคอร์สให้เสร็จสมบูรณ์: ปิดคอร์สเดิม + เปิดคอร์สใหม่ + จัดการเครดิต =====
    // Business rule: การเปลี่ยนคอร์สถือเป็นการทำรายการใหม่ (สร้าง Enrollment ใหม่ ไม่ใช่แก้ไขของเดิม)
    private function finalizeTransfer(CourseTransfer $courseTransfer): void
    {
        $oldEnrollment = $courseTransfer->oldEnrollment;
        $newCourse = $courseTransfer->newCourse;
        $student = $courseTransfer->student;

        // ปิดคอร์สเดิม
        $oldEnrollment->update(['status' => 'cancelled', 'actual_end_date' => now()->toDateString()]);

        // เปิดคอร์สใหม่ (อัปเดตตารางเรียน + จำนวนครั้งเรียนคงเหลืออัตโนมัติ เพราะเป็น enrollment ใหม่ sessions_used=0)
        $newEnrollment = Enrollment::create([
            'student_id'        => $student->id,
            'course_id'         => $newCourse->id,
            'enrolled_date'     => now()->toDateString(),
            'expected_end_date' => $newCourse->duration_months ? now()->addMonths($newCourse->duration_months)->toDateString() : null,
            'status'            => 'active',
        ]);

        // Business rule: คอร์สใหม่ราคาต่ำกว่า -> เก็บเครดิตส่วนต่างคืนให้นักเรียน
        $creditIssued = 0;
        if ($courseTransfer->price_difference < 0) {
            $creditIssued = abs($courseTransfer->price_difference);
            $newBalance = $student->creditBalance() + $creditIssued;
            $student->creditTransactions()->create([
                'type'          => 'refund',
                'amount'        => $creditIssued,
                'balance_after' => $newBalance,
                'reason'        => 'เครดิตจากการเปลี่ยนคอร์ส ' . $courseTransfer->transfer_no,
            ]);
        }

        $courseTransfer->update([
            'new_enrollment_id' => $newEnrollment->id,
            'credit_issued'     => $creditIssued,
            'status'            => 'completed',
            'completed_at'      => now(),
        ]);
    }
}

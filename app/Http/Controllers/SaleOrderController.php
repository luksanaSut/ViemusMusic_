<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleOrderRequest;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\SaleOrder;
use App\Models\Student;
use App\Models\TaxInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleOrderController extends Controller
{
    // GET /sales — ประวัติการสมัครเรียนย้อนหลัง (ทุกนักเรียน)
    public function index(Request $request)
    {
        $orders = SaleOrder::with(['student', 'course', 'teacher'])
            ->search($request->get('q'))
            ->status($request->get('status'))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('sales.index', compact('orders'));
    }

    // GET /sales/create
    public function create(Request $request)
    {
        $courses = Course::where('is_active', true)->with('teachers')->orderBy('name')->get();
        $preselectedStudent = $request->filled('student_id') ? Student::find($request->student_id) : null;

        return view('sales.create', compact('courses', 'preselectedStudent'));
    }

    // GET /sales/course-availability?course_id= — ตรวจสอบคอร์สคงเหลือ (AJAX)
    public function courseAvailability(Request $request)
    {
        $request->validate(['course_id' => ['required', 'exists:courses,id']]);
        $course = Course::findOrFail($request->course_id);

        if ($course->class_type === 'private') {
            return response()->json(['unlimited' => true, 'remaining' => null, 'max' => null]);
        }

        $used = Enrollment::where('course_id', $course->id)->where('status', 'active')->count();
        $max = $course->max_students ?? 0;

        return response()->json([
            'unlimited' => false,
            'remaining' => max(0, $max - $used),
            'max'       => $max,
            'used'      => $used,
        ]);
    }

    // POST /sales
    public function store(StoreSaleOrderRequest $request)
    {
        $data = $request->validated();
        $course = Course::findOrFail($data['course_id']);

        // Business rule: ต้องตรวจสอบจำนวนที่นั่งคงเหลือก่อนจองเรียน (ยกเว้นคอร์สแบบ Private ที่ไม่จำกัด)
        if ($course->class_type !== 'private') {
            $used = Enrollment::where('course_id', $course->id)->where('status', 'active')->count();
            $remaining = ($course->max_students ?? 0) - $used;
            if ($remaining <= 0) {
                return back()->withInput()->with('error', 'คอร์สนี้เต็มแล้ว (ที่นั่งคงเหลือ 0) ไม่สามารถสมัครเพิ่มได้');
            }
        }

        // คำนวณราคาอัตโนมัติ — สมมติราคาคอร์สเป็นราคารวม VAT แล้ว
        $totalAmount = (float) $course->price;
        $vatRate = 7.00;
        $subtotal = round($totalAmount / (1 + $vatRate / 100), 2);
        $vatAmount = round($totalAmount - $subtotal, 2);

        $order = null;

        DB::transaction(function () use ($request, $data, $course, $totalAmount, $vatRate, $vatAmount, $subtotal, &$order) {
            $order = SaleOrder::create([
                'order_no'              => 'SO-' . now()->format('Ymd') . '-' . str_pad((SaleOrder::whereDate('created_at', now())->count() + 1), 4, '0', STR_PAD_LEFT),
                'student_id'            => $data['student_id'],
                'course_id'             => $data['course_id'],
                'teacher_id'            => $data['teacher_id'] ?? null,
                'branch'                => $data['branch'] ?? null,
                'delivery_mode'         => $data['delivery_mode'] ?? $course->delivery_mode,
                'preferred_day_of_week' => $data['preferred_day_of_week'] ?? null,
                'preferred_start_time'  => $data['preferred_start_time'] ?? null,
                'preferred_end_time'    => $data['preferred_end_time'] ?? null,
                'base_price'            => $subtotal,
                'vat_rate'              => $vatRate,
                'vat_amount'            => $vatAmount,
                'total_amount'          => $totalAmount,
                'net_payable'           => $totalAmount,
                'status'                => 'pending_payment',
                'notes'                 => $data['notes'] ?? null,
                'sold_by'               => auth()->user()->name ?? 'แอดมิน',
            ]);

            TaxInvoice::create([
                'sale_order_id' => $order->id,
                'invoice_no'    => '-', // จะ generate เลขจริงตอนยืนยันชำระเงิน
                'invoice_type'  => $data['invoice_type'],
                'is_company'    => $request->boolean('is_company'),
                'buyer_name'    => $data['buyer_name'],
                'buyer_tax_id'  => $data['buyer_tax_id'] ?? null,
                'buyer_address' => $data['buyer_address'] ?? null,
                'buyer_phone'   => $data['buyer_phone'] ?? null,
                'subtotal'      => $subtotal,
                'vat_rate'      => $vatRate,
                'vat_amount'    => $vatAmount,
                'total_amount'  => $totalAmount,
                'issued_date'   => now()->toDateString(),
            ]);
        });

        return redirect()->route('sales.show', $order)->with('success', 'สร้างคำสั่งสมัครเรียนเรียบร้อยแล้ว กรุณาตรวจสอบสรุปข้อมูลก่อนชำระเงิน');
    }

    // POST /sales/{saleOrder}/apply-discount — ใช้คูปอง / แต้มสะสม / เครดิต
    public function applyDiscount(Request $request, SaleOrder $saleOrder)
    {
        if ($saleOrder->status !== 'pending_payment') {
            return back()->with('error', 'คำสั่งนี้ไม่สามารถแก้ไขส่วนลดได้แล้ว');
        }

        $data = $request->validate([
            'coupon_code' => ['nullable', 'string', 'max:30'],
            'use_points'  => ['nullable', 'boolean'],
            'use_credit'  => ['nullable', 'boolean'],
        ]);

        $student = $saleOrder->student;
        $running = (float) $saleOrder->total_amount;

        // ===== คูปอง / โปรโมชั่น =====
        $couponDiscount = 0;
        $couponId = null;
        $couponCode = null;

        if (!empty($data['coupon_code'])) {
            $coupon = Coupon::where('code', strtoupper(trim($data['coupon_code'])))->first();

            if (!$coupon || !$coupon->isCurrentlyValid()) {
                return back()->with('error', 'โค้ดคูปองไม่ถูกต้อง หมดอายุ หรือใช้ครบจำนวนสิทธิ์แล้ว');
            }
            if (!$coupon->applies_to_all_courses && !$coupon->courses()->where('courses.id', $saleOrder->course_id)->exists()) {
                return back()->with('error', 'คูปองนี้ใช้ไม่ได้กับคอร์สที่เลือก');
            }

            $couponDiscount = $coupon->discount_type === 'percent'
                ? round($running * $coupon->discount_value / 100, 2)
                : min($coupon->discount_value, $running);

            $couponId = $coupon->id;
            $couponCode = $coupon->code;
            $running -= $couponDiscount;
        }

        // ===== แต้มสะสม =====
        $pointsDiscount = 0;
        $pointsUsed = 0;
        if ($request->boolean('use_points')) {
            $pointsDiscount = $student->maxPointsRedeemableValue($running);
            $pointsUsed = (int) ($pointsDiscount * 10);
            $running -= $pointsDiscount;
        }

        // ===== เครดิตคงเหลือ =====
        $creditUsed = 0;
        if ($request->boolean('use_credit')) {
            $creditUsed = min($student->creditBalance(), $running);
            $running -= $creditUsed;
        }

        $saleOrder->update([
            'coupon_id'              => $couponId,
            'coupon_code'            => $couponCode,
            'discount_amount'        => $couponDiscount,
            'points_used'            => $pointsUsed,
            'points_discount_amount' => $pointsDiscount,
            'credit_used'            => $creditUsed,
            'net_payable'            => max(0, round($running, 2)),
        ]);

        return back()->with('success', 'อัปเดตส่วนลดเรียบร้อยแล้ว');
    }

    // GET /sales/{saleOrder} — หน้าสรุปข้อมูลการสมัครเรียนก่อนชำระเงิน / หรือดูผลหลังชำระแล้ว
    public function show(SaleOrder $saleOrder)
    {
        $saleOrder->load(['student', 'course', 'teacher', 'taxInvoice', 'enrollment', 'payment']);

        return view('sales.show', compact('saleOrder'));
    }

    // POST /sales/{saleOrder}/confirm-payment
    public function confirmPayment(Request $request, SaleOrder $saleOrder)
    {
        if ($saleOrder->status !== 'pending_payment') {
            return back()->with('error', 'คำสั่งนี้ถูกดำเนินการไปแล้ว ไม่สามารถยืนยันซ้ำได้');
        }

        $data = $request->validate([
            'payment_method' => ['required', 'in:promptpay,transfer,credit_card'],
            'payment_proof'  => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $course = $saleOrder->course;

        if ($course->class_type !== 'private') {
            $used = Enrollment::where('course_id', $course->id)->where('status', 'active')->count();
            if ($used >= ($course->max_students ?? 0)) {
                return back()->with('error', 'คอร์สนี้เต็มแล้ว ณ ขณะนี้ ไม่สามารถยืนยันการสมัครได้ กรุณาติดต่อนักเรียนเพื่อเปลี่ยนคอร์ส');
            }
        }

        $netPayable = $saleOrder->net_payable ?? $saleOrder->total_amount;

        DB::transaction(function () use ($request, $data, $saleOrder, $course, $netPayable) {
            if ($request->hasFile('payment_proof')) {
                $saleOrder->payment_proof_path = $request->file('payment_proof')->store('payment-proofs', 'public');
            }
            $saleOrder->payment_method = $data['payment_method'];
            $saleOrder->save();

            $enrollment = Enrollment::create([
                'student_id'         => $saleOrder->student_id,
                'course_id'          => $saleOrder->course_id,
                'enrolled_date'      => now()->toDateString(),
                'expected_end_date'  => $course->duration_months ? now()->addMonths($course->duration_months)->toDateString() : null,
                'status'             => 'active',
            ]);

            $payment = Payment::create([
                'student_id'    => $saleOrder->student_id,
                'enrollment_id' => $enrollment->id,
                'invoice_no'    => $saleOrder->order_no,
                'amount'        => $netPayable,
                'paid_amount'   => $netPayable,
                'paid_date'     => now()->toDateString(),
                'method'        => $data['payment_method'] === 'promptpay' ? 'other' : $data['payment_method'],
                'status'        => 'paid',
                'note'          => 'ชำระผ่านระบบขายคอร์สเรียน (' . $saleOrder->order_no . ')',
            ]);

            if ($saleOrder->coupon_id) {
                $saleOrder->coupon()->increment('used_count');
            }

            if ($saleOrder->credit_used > 0) {
                $student = $saleOrder->student;
                $newBalance = $student->creditBalance() - $saleOrder->credit_used;
                $student->creditTransactions()->create([
                    'type' => 'use',
                    'amount' => -$saleOrder->credit_used,
                    'balance_after' => $newBalance,
                    'reason' => 'ใช้ชำระค่าคอร์ส ' . $saleOrder->order_no,
                ]);
            }

            if ($saleOrder->points_used > 0) {
                $student = $saleOrder->student;
                $newBalance = $student->pointBalance() - $saleOrder->points_used;
                $student->pointTransactions()->create([
                    'sale_order_id' => $saleOrder->id,
                    'type' => 'redeem',
                    'points' => -$saleOrder->points_used,
                    'balance_after' => $newBalance,
                    'reason' => 'แลกแต้มเป็นส่วนลดคำสั่ง ' . $saleOrder->order_no,
                ]);
            }

            // สะสมแต้มใหม่จากยอดที่ชำระจริง (ทุก 100 บาท = 1 แต้ม)
            $student = $saleOrder->student;
            $earned = (int) floor($netPayable / 100);
            if ($earned > 0) {
                $newBalance = $student->pointBalance() + $earned;
                $student->pointTransactions()->create([
                    'sale_order_id' => $saleOrder->id,
                    'type' => 'earn',
                    'points' => $earned,
                    'balance_after' => $newBalance,
                    'reason' => 'สะสมแต้มจากการซื้อคอร์ส ' . $saleOrder->order_no,
                ]);
            }

            $invoiceNo = ($saleOrder->taxInvoice->invoice_type === 'tax_invoice' ? 'TAX' : 'RCP')
                . '-' . now()->format('Ymd') . '-' . str_pad(TaxInvoice::whereDate('created_at', now())->count() + 1, 4, '0', STR_PAD_LEFT);

            $saleOrder->taxInvoice->update([
                'invoice_no'   => $invoiceNo,
                'total_amount' => $netPayable,
                'issued_date'  => now()->toDateString(),
            ]);

            $saleOrder->update([
                'status'        => 'paid',
                'enrollment_id' => $enrollment->id,
                'payment_id'    => $payment->id,
            ]);
        });

        return back()->with('success', 'ยืนยันการชำระเงินเรียบร้อยแล้ว ออกใบเสร็จ/ใบกำกับภาษี บันทึกแต้มสะสม และลงทะเบียนเรียนให้นักเรียนแล้ว');
    }

    // PATCH /sales/{saleOrder}/cancel
    public function cancel(SaleOrder $saleOrder)
    {
        if ($saleOrder->status !== 'pending_payment') {
            return back()->with('error', 'ยกเลิกได้เฉพาะคำสั่งที่ยังรอตรวจสอบการชำระเงินเท่านั้น');
        }

        $saleOrder->update(['status' => 'cancelled']);

        return back()->with('success', 'ยกเลิกคำสั่งสมัครเรียนเรียบร้อยแล้ว');
    }
}

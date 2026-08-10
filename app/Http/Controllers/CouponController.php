<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    // GET /coupons
    public function index()
    {
        $coupons = Coupon::with('courses')->orderByDesc('created_at')->paginate(15);
        $courses = Course::where('is_active', true)->orderBy('name')->get();

        return view('courses.coupons', compact('coupons', 'courses'));
    }

    // POST /coupons
    public function store(Request $request)
    {
        $data = $request->validate([
            'code'                    => ['required', 'string', 'max:30', 'regex:/^[A-Z0-9\-]+$/', Rule::unique('coupons', 'code')],
            'name'                    => ['required', 'string', 'max:150'],
            'discount_type'           => ['required', 'in:percent,fixed'],
            'discount_value'          => ['required', 'numeric', 'min:0'],
            'max_uses'                => ['nullable', 'integer', 'min:1'],
            'valid_from'              => ['nullable', 'date'],
            'valid_to'                => ['nullable', 'date', 'after_or_equal:valid_from'],
            'applies_to_all_courses'  => ['nullable', 'boolean'],
            'course_ids'              => ['nullable', 'array'],
            'course_ids.*'            => ['integer', 'exists:courses,id'],
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['applies_to_all_courses'] = $request->boolean('applies_to_all_courses');

        $coupon = Coupon::create($data);

        if (!$coupon->applies_to_all_courses) {
            $coupon->courses()->sync($data['course_ids'] ?? []);
        }

        return back()->with('success', 'เพิ่มโปรโมชัน/คูปองเรียบร้อยแล้ว');
    }

    // PATCH /coupons/{coupon}/toggle-status
    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        return back()->with('success', $coupon->is_active ? 'เปิดใช้งานคูปองแล้ว' : 'ปิดใช้งานคูปองแล้ว');
    }

    // DELETE /coupons/{coupon}
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return back()->with('success', 'ลบคูปองเรียบร้อยแล้ว');
    }
}

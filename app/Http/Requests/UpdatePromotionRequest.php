<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => $this->code ? strtoupper(trim($this->code)) : null,
            'name' => $this->name ? trim(strip_tags($this->name)) : null,
        ]);
    }

    public function rules(): array
    {
        $promotionId = $this->route('promotion')->id ?? null;

        return [
            'code'                => ['nullable', 'string', 'max:30', 'regex:/^[A-Z0-9\-]+$/', Rule::unique('promotions', 'code')->ignore($promotionId)],
            'name'                => ['required', 'string', 'max:150'],
            'discount_type'       => ['required', 'in:percent,fixed,spend_get'],
            'discount_value'      => ['required', 'numeric', 'min:0'],
            'min_spend'           => ['required_if:discount_type,spend_get', 'nullable', 'numeric', 'min:0'],
            'max_uses'            => ['nullable', 'integer', 'min:1'],
            'per_customer_limit'  => ['nullable', 'integer', 'min:1'],
            'valid_from'          => ['nullable', 'date'],
            'valid_to'            => ['nullable', 'date', 'after_or_equal:valid_from'],
            'scope'               => ['required', 'in:course,product,both'],
            'applies_to_all'      => ['nullable', 'boolean'],
            'course_ids'          => ['nullable', 'array'],
            'course_ids.*'        => ['integer', 'exists:courses,id'],
            'product_ids'         => ['nullable', 'array'],
            'product_ids.*'       => ['integer', 'exists:products,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'       => 'กรุณากรอกข้อมูลในช่องนี้',
            'required_if'    => 'กรุณากรอกข้อมูลในช่องนี้',
            'unique'         => 'โค้ดนี้มีอยู่ในระบบแล้ว',
            'regex'          => 'ใช้ได้เฉพาะตัวอักษร A-Z, ตัวเลข, และ - เท่านั้น',
            'after_or_equal' => 'วันที่สิ้นสุดต้องไม่ก่อนวันที่เริ่ม',
        ];
    }
}

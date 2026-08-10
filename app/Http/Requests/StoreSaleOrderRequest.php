<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'branch'        => $this->branch ? trim(strip_tags($this->branch)) : null,
            'buyer_name'    => $this->buyer_name ? trim(strip_tags($this->buyer_name)) : null,
            'buyer_address' => $this->buyer_address ? trim(strip_tags($this->buyer_address)) : null,
            'notes'         => $this->notes ? trim(strip_tags($this->notes)) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'course_id'  => ['required', 'exists:courses,id'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],

            'branch'                => ['nullable', 'string', 'max:100'],
            'delivery_mode'         => ['nullable', 'in:onsite,online,hybrid'],
            'preferred_day_of_week' => ['nullable', 'integer', 'between:0,6'],
            'preferred_start_time'  => ['nullable'],
            'preferred_end_time'    => ['nullable', 'after:preferred_start_time'],

            'notes' => ['nullable', 'string', 'max:1000'],

            // ข้อมูลใบกำกับภาษี / ใบเสร็จ
            'invoice_type'  => ['required', 'in:receipt,tax_invoice'],
            'is_company'    => ['nullable', 'boolean'],
            'buyer_name'    => ['required', 'string', 'max:150'],
            'buyer_tax_id'  => ['required_if:is_company,1', 'nullable', 'digits:13'],
            'buyer_address' => ['nullable', 'string', 'max:500'],
            'buyer_phone'   => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'    => 'กรุณากรอกข้อมูลในช่องนี้',
            'required_if' => 'กรุณากรอกเลขผู้เสียภาษี (13 หลัก) เมื่อออกในนามนิติบุคคล',
            'digits'      => 'เลขผู้เสียภาษีต้องเป็นตัวเลข 13 หลัก',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * ทำความสะอาดข้อมูลก่อนเข้า validate
     * - ตัด HTML tag ออกจากข้อความอิสระ กัน stored XSS
     * - normalize เบอร์โทร/รหัสอาจารย์/Line ID ให้อยู่ในรูปแบบที่คาดไว้
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'teacher_code' => $this->teacher_code ? strtoupper(trim($this->teacher_code)) : null,
            'full_name'    => $this->full_name ? trim(strip_tags($this->full_name)) : null,
            'nickname'     => $this->nickname ? trim(strip_tags($this->nickname)) : null,
            'phone'        => $this->phone ? preg_replace('/\D/', '', $this->phone) : null, // เก็บแต่ตัวเลข
            'line_id'      => $this->line_id ? preg_replace('/[^a-zA-Z0-9._\-]/', '', $this->line_id) : null,
            'address'      => $this->address ? trim(strip_tags($this->address)) : null,
            'bio'          => $this->bio ? trim(strip_tags($this->bio)) : null,
            'notes'        => $this->notes ? trim(strip_tags($this->notes)) : null,
            'rate_note'    => $this->rate_note ? trim(strip_tags($this->rate_note)) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'teacher_code'    => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9\-]+$/', 'unique:teachers,teacher_code'],
            'full_name'       => ['required', 'string', 'max:150'],
            'nickname'        => ['nullable', 'string', 'max:50'],
            'email'           => ['nullable', 'email:rfc,dns', 'max:150', 'unique:teachers,email'],
            'phone'           => ['nullable', 'digits_between:9,10'],
            'line_id'         => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-Z0-9._\-]+$/'],
            'address'         => ['nullable', 'string', 'max:500'],
            'photo'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'employment_type' => ['required', 'in:full_time,freelance'],
            'branch'          => ['nullable', 'string', 'max:100'],
            'bio'             => ['nullable', 'string', 'max:2000'],
            'notes'           => ['nullable', 'string', 'max:2000'],
            'is_active'       => ['nullable', 'boolean'],
            'start_date'      => ['nullable', 'date', 'before_or_equal:today'],

            'teaching_type_ids'   => ['nullable', 'array'],
            'teaching_type_ids.*' => ['integer', 'exists:teaching_types,id'],

            'instrument_ids'        => ['nullable', 'array'],
            'instrument_ids.*'      => ['integer', 'exists:instruments,id'],
            'primary_instrument_id' => ['nullable', 'integer', 'exists:instruments,id'],

            'level_ids'   => ['nullable', 'array'],
            'level_ids.*' => ['integer', 'exists:levels,id'],

            'rate_type'   => ['required', 'in:per_hour,per_session,monthly_fixed'],
            'rate_amount' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'rate_note'   => ['nullable', 'string', 'max:1000'],

            'transport_fee_type'   => ['nullable', 'in:fixed_per_day,per_km'],
            'transport_fee_amount' => ['nullable', 'numeric', 'min:0', 'max:100000'],

            'availabilities'                => ['nullable', 'array'],
            'availabilities.*.day_of_week'  => ['required_with:availabilities', 'integer', 'between:0,6'],
            'availabilities.*.start_time'   => ['required_with:availabilities', 'date_format:H:i'],
            'availabilities.*.end_time'     => ['required_with:availabilities', 'date_format:H:i', 'after:availabilities.*.start_time'],
            'availabilities.*.is_available' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'      => 'กรุณากรอกข้อมูลในช่องนี้',
            'unique'        => 'ข้อมูลนี้มีอยู่ในระบบแล้ว',
            'email'         => 'รูปแบบอีเมลไม่ถูกต้อง',
            'numeric'       => 'กรุณากรอกเป็นตัวเลข',
            'digits_between' => 'เบอร์โทรต้องเป็นตัวเลข 9-10 หลัก',
            'regex'         => 'รูปแบบข้อมูลไม่ถูกต้อง',
            'mimes'         => 'รองรับเฉพาะไฟล์ JPG, PNG, WEBP เท่านั้น',
            'max'           => 'ข้อมูลยาวเกินกำหนด หรือไฟล์มีขนาดใหญ่เกินไป',
        ];
    }
}

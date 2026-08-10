<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'course_code'    => $this->course_code ? strtoupper(trim($this->course_code)) : null,
            'name'           => $this->name ? trim(strip_tags($this->name)) : null,
            'description'    => $this->description ? trim(strip_tags($this->description)) : null,
            // Private = ไม่จำกัดจำนวนผู้เรียน บังคับเป็น null เสมอไม่ว่าฝั่งหน้าเว็บจะส่งอะไรมา
            'max_students'   => $this->class_type === 'private' ? null : $this->max_students,
            // แบบพิเศษไม่ใช้ delivery_mode (ฟอร์มซ่อนฟิลด์นี้ไว้) ตั้งค่า default ให้เสมอกัน DB ไม่ error
            'delivery_mode'  => $this->structure_type === 'special' ? 'onsite' : $this->delivery_mode,
        ]);
    }

    public function rules(): array
    {
        return [
            'course_code'   => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9\-]+$/', 'unique:courses,course_code'],
            'name'          => ['required', 'string', 'max:150'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'structure_type' => ['required', 'in:regular,special'],
            'class_type'     => ['required', 'in:private,group,special_activity'],
            'delivery_mode'  => ['required_if:structure_type,regular', 'nullable', 'in:onsite,online,hybrid'],
            'activity_type'  => ['required_if:class_type,special_activity', 'nullable', 'in:camp,workshop,master_class'],

            'instrument_id' => ['nullable', 'integer', 'exists:instruments,id'],
            'level_id'      => ['nullable', 'integer', 'exists:levels,id'],

            // แบบปกติ
            'total_sessions'  => ['required_if:structure_type,regular', 'nullable', 'integer', 'min:1', 'max:500'],
            'duration_months' => ['required_if:structure_type,regular', 'nullable', 'integer', 'min:1', 'max:36'],

            // แบบพิเศษ
            'days_count'        => ['required_if:structure_type,special', 'nullable', 'integer', 'min:1', 'max:60'],
            'hours_per_day'     => ['required_if:structure_type,special', 'nullable', 'numeric', 'min:0.5', 'max:12'],
            'course_start_date' => ['required_if:structure_type,special', 'nullable', 'date'],
            'course_end_date'   => ['required_if:structure_type,special', 'nullable', 'date', 'after_or_equal:course_start_date'],

            'price'        => ['required', 'numeric', 'min:0', 'max:1000000'],
            'max_students' => ['nullable', 'integer', 'min:1', 'max:100'],

            'allow_makeup_class'     => ['nullable', 'boolean'],
            'emergency_leave_quota'  => ['required', 'integer', 'min:0', 'max:10'],
            'is_adult_flexi'         => ['nullable', 'boolean'],
            'is_active'              => ['required', 'in:1,0'],

            'teacher_ids'   => ['nullable', 'array'],
            'teacher_ids.*' => ['integer', 'exists:teachers,id'],
        ];
    }

    // Business rule: Group ต้องระบุจำนวน >= 2, Special Activity ต้องระบุ >= 1
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($this->class_type === 'group' && (int) $this->max_students < 2) {
                $validator->errors()->add('max_students', 'คอร์สแบบ Group ต้องกำหนดจำนวนผู้เรียนสูงสุดอย่างน้อย 2 คน');
            }
            if ($this->class_type === 'special_activity' && !$this->max_students) {
                $validator->errors()->add('max_students', 'คอร์สแบบ Special Activity ต้องกำหนดจำนวนผู้เข้าร่วมสูงสุด');
            }
        });
    }

    public function messages(): array
    {
        return [
            'required'        => 'กรุณากรอกข้อมูลในช่องนี้',
            'required_if'     => 'กรุณากรอกข้อมูลในช่องนี้',
            'unique'          => 'ข้อมูลนี้มีอยู่ในระบบแล้ว',
            'regex'           => 'ใช้ได้เฉพาะตัวอักษร A-Z, ตัวเลข, และ - เท่านั้น',
            'mimes'           => 'รองรับเฉพาะไฟล์ JPG, PNG, WEBP เท่านั้น',
            'after_or_equal'  => 'วันที่สิ้นสุดต้องไม่ก่อนวันที่เริ่ม',
        ];
    }
}

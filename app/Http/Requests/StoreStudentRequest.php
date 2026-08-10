<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'student_code'      => $this->student_code ? strtoupper(trim($this->student_code)) : null,
            'full_name'         => $this->full_name ? trim(strip_tags($this->full_name)) : null,
            'nickname'          => $this->nickname ? trim(strip_tags($this->nickname)) : null,
            'phone'             => $this->phone ? preg_replace('/\D/', '', $this->phone) : null,
            'line_id'           => $this->line_id ? preg_replace('/[^a-zA-Z0-9._\-]/', '', $this->line_id) : null,
            'address'           => $this->address ? trim(strip_tags($this->address)) : null,
            'notes'             => $this->notes ? trim(strip_tags($this->notes)) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'student_code'   => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9\-]+$/', 'unique:students,student_code'],
            'full_name'      => ['required', 'string', 'max:150'],
            'nickname'       => ['nullable', 'string', 'max:50'],
            'date_of_birth'  => ['nullable', 'date', 'before:today'],
            'gender'         => ['nullable', 'in:male,female,other'],
            'phone'          => ['nullable', 'digits_between:9,10'],
            'email'          => ['nullable', 'email:rfc,dns', 'max:150'],
            'line_id'        => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-Z0-9._\-]+$/'],
            'address'        => ['nullable', 'string', 'max:500'],
            'photo'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'status' => ['required', 'in:active,paused,cancelled'],
            'notes'  => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'       => 'กรุณากรอกข้อมูลในช่องนี้',
            'unique'         => 'ข้อมูลนี้มีอยู่ในระบบแล้ว',
            'regex'          => 'รูปแบบข้อมูลไม่ถูกต้อง',
            'digits_between' => 'กรอกเป็นตัวเลข 9-10 หลัก',
        ];
    }
}

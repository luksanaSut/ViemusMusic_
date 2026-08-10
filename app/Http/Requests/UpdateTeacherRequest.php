<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'teacher_code' => $this->teacher_code ? strtoupper(trim($this->teacher_code)) : null,
            'full_name'    => $this->full_name ? trim(strip_tags($this->full_name)) : null,
            'nickname'     => $this->nickname ? trim(strip_tags($this->nickname)) : null,
            'phone'        => $this->phone ? preg_replace('/\D/', '', $this->phone) : null,
            'line_id'      => $this->line_id ? preg_replace('/[^a-zA-Z0-9._\-]/', '', $this->line_id) : null,
            'address'      => $this->address ? trim(strip_tags($this->address)) : null,
            'bio'          => $this->bio ? trim(strip_tags($this->bio)) : null,
            'notes'        => $this->notes ? trim(strip_tags($this->notes)) : null,
        ]);
    }

    public function rules(): array
    {
        $teacherId = $this->route('teacher')->id ?? null;

        return [
            'teacher_code'    => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9\-]+$/', Rule::unique('teachers', 'teacher_code')->ignore($teacherId)],
            'full_name'       => ['required', 'string', 'max:150'],
            'nickname'        => ['nullable', 'string', 'max:50'],
            'email'           => ['nullable', 'email:rfc,dns', 'max:150', Rule::unique('teachers', 'email')->ignore($teacherId)],
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
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'reason' => $this->reason ? trim(strip_tags($this->reason)) : null,
            'notes'  => $this->notes ? trim(strip_tags($this->notes)) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'old_enrollment_id'  => ['required', 'exists:enrollments,id'],
            'new_course_id'      => ['required', 'exists:courses,id'],
            'new_teacher_id'     => ['nullable', 'exists:teachers,id'],
            'teacher_change_fee' => ['nullable', 'numeric', 'min:0'],
            'reason'             => ['nullable', 'string', 'max:500'],
            'notes'              => ['nullable', 'string', 'max:1000'],
        ];
    }
}

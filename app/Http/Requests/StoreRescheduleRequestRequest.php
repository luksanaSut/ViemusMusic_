<?php

namespace App\Http\Requests;

use App\Models\ClassSchedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreRescheduleRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['reason' => $this->reason ? trim(strip_tags($this->reason)) : null]);
    }

    public function rules(): array
    {
        $rules = [
            'type'               => ['required', 'in:change,swap'],
            'class_schedule_id'  => ['required', 'exists:class_schedules,id'],
            'reason'             => ['nullable', 'string', 'max:500'],
        ];

        if ($this->type === 'swap') {
            $rules['swap_with_class_schedule_id'] = ['required', 'exists:class_schedules,id', 'different:class_schedule_id'];
        } else {
            $rules += [
                'new_teacher_id'   => ['nullable', 'exists:teachers,id'],
                'new_room_id'      => ['nullable', 'exists:rooms,id'],
                'new_date'         => ['required', 'date'],
                'new_start_time'   => ['required'],
                'new_end_time'     => ['required', 'after:new_start_time'],
            ];
        }

        return $rules;
    }

    // Business rule: ต้องตรวจว่าง (นักเรียน/อาจารย์/ห้อง) ก่อนอนุญาตให้ยื่นคำขอ
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty() || $this->type !== 'change') return;

            $schedule = ClassSchedule::with('enrollment')->find($this->class_schedule_id);
            if (!$schedule) return;

            $teacherId = $this->new_teacher_id ?: $schedule->teacher_id;
            $roomId = $this->new_room_id ?: $schedule->room_id;

            $conflicts = ClassSchedule::findConflicts(
                $this->new_date,
                $this->new_start_time,
                $this->new_end_time,
                $schedule->enrollment->student_id,
                $teacherId,
                $roomId,
                $schedule->id // ไม่นับตัวเองเป็นคู่ชนกัน
            );

            foreach ($conflicts as $message) {
                $validator->errors()->add('new_date', $message);
            }
        });
    }

    public function messages(): array
    {
        return ['required' => 'กรุณากรอกข้อมูลในช่องนี้', 'different' => 'ต้องเลือกคาบเรียนคนละคาบกับตัวเอง'];
    }
}

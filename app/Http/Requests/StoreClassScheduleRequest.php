<?php

namespace App\Http\Requests;

use App\Models\ClassSchedule;
use App\Models\Enrollment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreClassScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['notes' => $this->notes ? trim(strip_tags($this->notes)) : null]);
    }

    public function rules(): array
    {
        return [
            'enrollment_id'   => ['required', 'exists:enrollments,id'],
            'teacher_id'      => ['nullable', 'exists:teachers,id'],
            'room_id'         => ['nullable', 'exists:rooms,id'],
            'schedule_date'   => ['required', 'date'],
            'start_time'      => ['required'],
            'end_time'        => ['required', 'after:start_time'],
            'delivery_mode'   => ['required', 'in:onsite,online,hybrid'],
            'status'          => ['required', 'in:scheduled,completed,cancelled,no_show'],
            'notes'           => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) return;

            $enrollment = Enrollment::with('course')->find($this->enrollment_id);
            if (!$enrollment || $enrollment->status !== 'active') {
                $validator->errors()->add('enrollment_id', 'เลือกได้เฉพาะการลงทะเบียนเรียนที่มีสถานะ "กำลังเรียน" เท่านั้น');
                return;
            }

            // Business rule: คอร์สแบบพิเศษ ต้องจัดตารางอยู่ในช่วงวันที่คอร์สกำหนดไว้เท่านั้น
            if ($enrollment->course && $enrollment->course->structure_type === 'special') {
                $courseStart = $enrollment->course->course_start_date;
                $courseEnd = $enrollment->course->course_end_date;
                $scheduleDate = \Carbon\Carbon::parse($this->schedule_date);

                if ($courseStart && $scheduleDate->lt($courseStart)) {
                    $validator->errors()->add('schedule_date', "คอร์สนี้เป็นแบบพิเศษ กำหนดวันเรียนตั้งแต่ {$courseStart->format('d/m/Y')} — ไม่สามารถจัดตารางก่อนวันเริ่มคอร์สได้");
                }
                if ($courseEnd && $scheduleDate->gt($courseEnd)) {
                    $validator->errors()->add('schedule_date', "คอร์สนี้เป็นแบบพิเศษ กำหนดวันเรียนถึง {$courseEnd->format('d/m/Y')} — ไม่สามารถจัดตารางหลังวันสิ้นสุดคอร์สได้");
                }
            }

            $excludeId = $this->route('classSchedule')?->id;

            // Validation rule: ห้ามนักเรียน / อาจารย์ / ห้องเรียน เวลาซ้ำ
            $conflicts = ClassSchedule::findConflicts(
                $this->schedule_date,
                $this->start_time,
                $this->end_time,
                $enrollment->student_id,
                $this->teacher_id,
                $this->room_id,
                $excludeId
            );
            foreach ($conflicts as $message) {
                $validator->errors()->add('schedule_date', $message);
            }

            // Business rule: จัดตารางได้ไม่เกินจำนวนครั้งที่ซื้อไว้ในแพ็กเกจ (เฉพาะคอร์สแบบนับจำนวนครั้ง)
            if ($this->status !== 'cancelled' && $enrollment->course && $enrollment->course->total_sessions) {
                $usedCount = ClassSchedule::where('enrollment_id', $enrollment->id)
                    ->whereIn('status', ['scheduled', 'completed'])
                    ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                    ->count();

                if ($usedCount >= $enrollment->course->total_sessions) {
                    $validator->errors()->add(
                        'enrollment_id',
                        "คอร์สนี้จัดตารางครบ {$enrollment->course->total_sessions} ครั้งแล้วตามแพ็กเกจที่สมัครไว้ ไม่สามารถเพิ่มคาบใหม่ได้ (ต้องต่ออายุคอร์สก่อน)"
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return ['required' => 'กรุณากรอกข้อมูลในช่องนี้', 'after' => 'เวลาสิ้นสุดต้องอยู่หลังเวลาเริ่ม'];
    }
}

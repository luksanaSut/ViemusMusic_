<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'room_code'  => $this->room_code ? strtoupper(trim($this->room_code)) : null,
            'name'       => $this->name ? trim(strip_tags($this->name)) : null,
            'location'   => $this->location ? trim(strip_tags($this->location)) : null,
            'description' => $this->description ? trim(strip_tags($this->description)) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'room_code' => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9\-]+$/', 'unique:rooms,room_code'],
            'name'      => ['required', 'string', 'max:150'],
            'location'  => ['nullable', 'string', 'max:150'],
            'capacity'  => ['required', 'integer', 'min:1', 'max:500'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['required', 'in:1,0'],

            'is_under_maintenance' => ['nullable', 'boolean'],
            'maintenance_reason'   => ['required_if:is_under_maintenance,1', 'nullable', 'string', 'max:500'],
            'maintenance_from'     => ['required_if:is_under_maintenance,1', 'nullable', 'date'],
            'maintenance_to'       => ['nullable', 'date', 'after_or_equal:maintenance_from'],

            'equipment'            => ['nullable', 'array'],
            'equipment.*.equipment_type_id' => ['required_with:equipment', 'exists:equipment_types,id'],
            'equipment.*.quantity' => ['required_with:equipment', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'    => 'กรุณากรอกข้อมูลในช่องนี้',
            'unique'      => 'ข้อมูลนี้มีอยู่ในระบบแล้ว',
            'regex'       => 'ใช้ได้เฉพาะตัวอักษร A-Z, ตัวเลข, และ - เท่านั้น',
            'required_if' => 'กรุณากรอกข้อมูลในช่องนี้',
        ];
    }
}

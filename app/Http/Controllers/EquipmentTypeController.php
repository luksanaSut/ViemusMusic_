<?php

namespace App\Http\Controllers;

use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EquipmentTypeController extends Controller
{
    // POST /equipment-types — เพิ่มชนิดอุปกรณ์ใหม่แบบ inline
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('equipment_types', 'name')],
        ], [
            'name.required' => 'กรุณากรอกชื่ออุปกรณ์',
            'name.unique'   => 'มีอุปกรณ์นี้อยู่ในระบบแล้ว',
        ]);

        $equipment = EquipmentType::create(['name' => trim(strip_tags($data['name']))]);

        return response()->json(['id' => $equipment->id, 'name' => $equipment->name], 201);
    }
}

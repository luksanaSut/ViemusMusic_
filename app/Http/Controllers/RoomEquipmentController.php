<?php

namespace App\Http\Controllers;

use App\Models\EquipmentType;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomEquipmentController extends Controller
{
    // POST /rooms/{room}/equipment
    public function store(Request $request, Room $room)
    {
        $data = $request->validate([
            'equipment_type_id' => ['required', 'exists:equipment_types,id'],
            'quantity'          => ['required', 'integer', 'min:1', 'max:200'],
            'condition'         => ['nullable', 'string', 'max:100'],
            'note'              => ['nullable', 'string', 'max:500'],
        ]);

        $room->equipment()->syncWithoutDetaching([
            $data['equipment_type_id'] => [
                'quantity'  => $data['quantity'],
                'condition' => $data['condition'] ?? null,
                'note'      => $data['note'] ?? null,
            ],
        ]);

        return back()->with('success', 'เพิ่มอุปกรณ์ในห้องเรียบร้อยแล้ว');
    }

    // DELETE /rooms/{room}/equipment/{equipmentType}
    public function destroy(Room $room, EquipmentType $equipmentType)
    {
        $room->equipment()->detach($equipmentType->id);

        return back()->with('success', 'นำอุปกรณ์ออกจากห้องแล้ว');
    }
}

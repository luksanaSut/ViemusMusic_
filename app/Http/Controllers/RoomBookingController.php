<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomBooking;
use Illuminate\Http\Request;

class RoomBookingController extends Controller
{
    // POST /rooms/{room}/bookings
    public function store(Request $request, Room $room)
    {
        $data = $request->validate([
            'booking_date'    => ['required', 'date'],
            'start_time'      => ['required'],
            'end_time'        => ['required', 'after:start_time'],
            'purpose'         => ['nullable', 'string', 'max:255'],
            'attendees_count' => ['required', 'integer', 'min:1'],
            'teacher_id'      => ['nullable', 'exists:teachers,id'],
            'course_id'       => ['nullable', 'exists:courses,id'],
            'booked_by'       => ['nullable', 'string', 'max:150'],
        ]);

        // Business rule: ห้ามจองเกินความจุห้อง
        if ($data['attendees_count'] > $room->capacity) {
            return back()->with('error', "ห้องนี้รองรับได้สูงสุด {$room->capacity} คน ไม่สามารถจองเกินความจุที่กำหนดได้");
        }

        if ($room->is_under_maintenance) {
            return back()->with('error', 'ห้องนี้ปิดปรับปรุงอยู่ ไม่สามารถจองได้');
        }

        // ตรวจห้องว่าง (ไม่ให้จองทับซ้อนเวลาเดิม)
        if (!$room->isAvailable($data['booking_date'], $data['start_time'], $data['end_time'])) {
            return back()->with('error', 'ช่วงเวลานี้มีการจองห้องนี้ไว้แล้ว กรุณาเลือกเวลาอื่น');
        }

        $data['room_id'] = $room->id;
        $data['status'] = 'confirmed';

        RoomBooking::create($data);

        return back()->with('success', 'จองห้องเรียนเรียบร้อยแล้ว');
    }

    // PATCH /rooms/{room}/bookings/{booking}/cancel
    public function cancel(Room $room, RoomBooking $booking)
    {
        abort_if($booking->room_id !== $room->id, 404);

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'ยกเลิกการจองห้องเรียบร้อยแล้ว');
    }
}

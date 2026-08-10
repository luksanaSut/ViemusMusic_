<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\EquipmentType;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // GET /rooms
    public function index(Request $request)
    {
        $rooms = Room::query()
            ->with('equipment')
            ->search($request->get('q'))
            ->minCapacity($request->get('min_capacity'))
            ->when($request->filled('is_active'), fn($q) => $q->where('is_active', $request->boolean('is_active')))
            ->when($request->filled('is_under_maintenance'), fn($q) => $q->where('is_under_maintenance', $request->boolean('is_under_maintenance')))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        $equipmentTypes = EquipmentType::orderBy('name')->get();
        return view('rooms.create', compact('equipmentTypes'));
    }

    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();
        $room = Room::create($data);

        foreach ($data['equipment'] ?? [] as $eq) {
            $room->equipment()->attach($eq['equipment_type_id'], ['quantity' => $eq['quantity']]);
        }

        return redirect()->route('rooms.show', $room)->with('success', 'เพิ่มห้องเรียนเรียบร้อยแล้ว');
    }

    // GET /rooms/{room}
    public function show(Request $request, Room $room)
    {
        $room->load(['equipment', 'bookings' => fn($q) => $q->orderByDesc('booking_date')->orderByDesc('start_time')]);

        $date = $request->get('date', now()->toDateString());
        $bookingsOnDate = $room->bookings()
            ->where('booking_date', $date)
            ->where('status', 'confirmed')
            ->orderBy('start_time')
            ->get();

        $equipmentTypes = EquipmentType::orderBy('name')->get();

        return view('rooms.show', compact('room', 'date', 'bookingsOnDate', 'equipmentTypes'));
    }

    public function edit(Room $room)
    {
        $equipmentTypes = EquipmentType::orderBy('name')->get();
        return view('rooms.edit', compact('room', 'equipmentTypes'));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $data = $request->validated();
        $room->update($data);

        return redirect()->route('rooms.show', $room)->with('success', 'แก้ไขข้อมูลห้องเรียนเรียบร้อยแล้ว');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'ลบห้องเรียนเรียบร้อยแล้ว');
    }

    // PATCH /rooms/{room}/maintenance — ปิด/เปิดปรับปรุงห้องชั่วคราว
    public function toggleMaintenance(Request $request, Room $room)
    {
        if ($room->is_under_maintenance) {
            $room->update([
                'is_under_maintenance' => false,
                'maintenance_reason'   => null,
                'maintenance_from'     => null,
                'maintenance_to'       => null,
            ]);

            return back()->with('success', 'เปิดใช้งานห้องเรียนแล้ว');
        }

        $data = $request->validate([
            'maintenance_reason' => ['required', 'string', 'max:500'],
            'maintenance_from'   => ['required', 'date'],
            'maintenance_to'     => ['nullable', 'date', 'after_or_equal:maintenance_from'],
        ]);

        $room->update([
            'is_under_maintenance' => true,
            'maintenance_reason'   => trim(strip_tags($data['maintenance_reason'])),
            'maintenance_from'     => $data['maintenance_from'],
            'maintenance_to'       => $data['maintenance_to'] ?? null,
        ]);

        return back()->with('success', 'ปิดปรับปรุงห้องเรียนเรียบร้อยแล้ว');
    }

    // GET /rooms/availability-check — ตรวจสอบห้องว่างทั้งหมดในช่วงเวลาที่ระบุ (ใช้ทั้งหน้า UI ปกติ และ AJAX)
    public function availabilityCheck(Request $request)
    {
        $data = $request->validate([
            'date'       => ['required', 'date'],
            'start_time' => ['required'],
            'end_time'   => ['required', 'after:start_time'],
        ]);

        $rooms = Room::where('is_active', true)->orderBy('name')->get();

        $results = $rooms->map(function ($room) use ($data) {
            return [
                'id'        => $room->id,
                'name'      => $room->name,
                'capacity'  => $room->capacity,
                'available' => $room->isAvailable($data['date'], $data['start_time'], $data['end_time']),
            ];
        });

        if ($request->wantsJson()) {
            return response()->json($results);
        }

        return view('rooms.availability', ['results' => $results, 'filters' => $data]);
    }
}

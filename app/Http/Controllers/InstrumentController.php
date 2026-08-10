<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InstrumentController extends Controller
{
    // GET /instruments — คืนรายการเครื่องดนตรีทั้งหมด (เผื่อใช้ต่อยอดหน้าอื่นในอนาคต)
    public function index()
    {
        return response()->json(
            Instrument::where('is_active', true)->orderBy('name')->get(['id', 'name'])
        );
    }

    // POST /instruments — เพิ่มเครื่องดนตรีใหม่แบบ inline จากหน้าเพิ่ม/แก้ไขอาจารย์
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('instruments', 'name')],
        ], [
            'name.required' => 'กรุณากรอกชื่อเครื่องดนตรี',
            'name.unique'   => 'มีเครื่องดนตรีนี้อยู่ในระบบแล้ว',
        ]);

        $instrument = Instrument::create([
            'name'      => trim(strip_tags($data['name'])), // กัน HTML/script แฝงมาในชื่อ
            'is_active' => true,
        ]);

        return response()->json([
            'id'   => $instrument->id,
            'name' => $instrument->name,
        ], 201);
    }
}

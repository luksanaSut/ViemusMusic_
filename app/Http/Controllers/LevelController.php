<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LevelController extends Controller
{
    // POST /levels — เพิ่มระดับใหม่แบบ inline (เหมือน InstrumentController@store)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('levels', 'name')],
        ], [
            'name.required' => 'กรุณากรอกชื่อระดับ',
            'name.unique'   => 'มีระดับนี้อยู่ในระบบแล้ว',
        ]);

        $level = Level::create([
            'name'       => trim(strip_tags($data['name'])),
            'sort_order' => (Level::max('sort_order') ?? 0) + 1,
        ]);

        return response()->json(['id' => $level->id, 'name' => $level->name], 201);
    }
}

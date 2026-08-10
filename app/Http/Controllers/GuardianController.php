<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use Illuminate\Http\Request;

class GuardianController extends Controller
{
    // GET /guardians — หน้าเมนู "จัดการผู้ปกครอง"
    public function index(Request $request)
    {
        $guardians = Guardian::query()
            ->withCount('students')
            ->with('students:id,full_name,student_code')
            ->search($request->get('q'))
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        return view('guardians.index', compact('guardians'));
    }

    // GET /guardians/search?q= — สำหรับ autocomplete ตอนเพิ่มผู้ปกครองจากหน้านักเรียน
    public function search(Request $request)
    {
        $term = $request->get('q', '');
        $guardians = Guardian::search($term)->limit(8)->get(['id', 'full_name', 'phone']);

        return response()->json($guardians);
    }

    // POST /guardians — เพิ่มผู้ปกครองใหม่ (ใช้ทั้งจากหน้านี้ และแบบ inline จากหน้านักเรียน)
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'email'     => ['nullable', 'email', 'max:150'],
            'line_id'   => ['nullable', 'string', 'max:50'],
            'address'   => ['nullable', 'string', 'max:500'],
            'notes'     => ['nullable', 'string', 'max:1000'],
        ]);

        $data['full_name'] = trim(strip_tags($data['full_name']));
        $data['phone'] = $data['phone'] ? preg_replace('/\D/', '', $data['phone']) : null;

        $guardian = Guardian::create($data);

        if ($request->wantsJson()) {
            return response()->json($guardian, 201);
        }

        return back()->with('success', 'เพิ่มข้อมูลผู้ปกครองเรียบร้อยแล้ว');
    }

    // PUT /guardians/{guardian}
    public function update(Request $request, Guardian $guardian)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'email'     => ['nullable', 'email', 'max:150'],
            'line_id'   => ['nullable', 'string', 'max:50'],
            'address'   => ['nullable', 'string', 'max:500'],
            'notes'     => ['nullable', 'string', 'max:1000'],
        ]);

        $guardian->update($data);

        return back()->with('success', 'แก้ไขข้อมูลผู้ปกครองเรียบร้อยแล้ว');
    }

    // DELETE /guardians/{guardian}
    public function destroy(Guardian $guardian)
    {
        $guardian->delete();

        return back()->with('success', 'ลบข้อมูลผู้ปกครองเรียบร้อยแล้ว');
    }
}

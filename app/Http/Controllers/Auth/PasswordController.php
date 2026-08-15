<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    // GET /change-password
    public function edit(Request $request)
    {
        return view('auth.change-password', [
            'isForced' => $request->user()->must_change_password,
        ]);
    }

    // PUT /change-password
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง',
            'password.confirmed'                => 'ยืนยันรหัสผ่านใหม่ไม่ตรงกัน',
            'password.min'                       => 'รหัสผ่านใหม่ต้องมีอย่างน้อย 8 ตัวอักษร',
            'password.different'                 => 'รหัสผ่านใหม่ต้องไม่ซ้ำกับรหัสผ่านเดิม',
        ]);

        $user->update([
            'password'             => Hash::make($data['password']),
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
    }
}

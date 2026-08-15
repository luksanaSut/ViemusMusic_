<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // ถ้าถูกบังคับให้เปลี่ยนรหัสผ่าน (สร้างบัญชีใหม่/ถูกรีเซ็ต) ให้ไปหน้าเปลี่ยนรหัสผ่านก่อนเสมอ
        // ยกเว้นหน้าเปลี่ยนรหัสผ่านเอง และการออกจากระบบ ไม่งั้นจะ redirect วนไม่จบ
        if (
            $user && $user->must_change_password
            && !$request->routeIs('password.change')
            && !$request->routeIs('password.update')
            && !$request->routeIs('logout')
        ) {
            return redirect()->route('password.change');
        }

        return $next($request);
    }
}

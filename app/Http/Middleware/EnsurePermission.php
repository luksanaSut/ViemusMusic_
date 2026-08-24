<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    // โมดูลที่ล็อกไว้ที่ admin เสมอ ไม่สนใจค่าใน role_permissions เลย กัน privilege escalation
    private const LOCKED_KEYS = ['users.manage', 'role_permissions.manage'];

    public function handle(Request $request, Closure $next, string $key): Response
    {
        $user = $request->user();

        if (in_array($key, self::LOCKED_KEYS, true)) {
            if (!$user || !$user->isAdmin()) {
                abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
            }

            return $next($request);
        }

        if (!$user || !$user->hasModulePermission($key)) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        return $next($request);
    }
}

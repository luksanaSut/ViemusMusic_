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

        // สิทธิ์ "ดูได้" อย่างเดียว: เปิดได้เฉพาะหน้าดูข้อมูล — กันทุกคำขอที่เปลี่ยนข้อมูล และหน้าฟอร์มสร้าง/แก้ไข
        if ($this->isEditRequest($request) && !$user->canEditModule($key)) {
            abort(403, 'คุณมีสิทธิ์ดูข้อมูลอย่างเดียว ไม่สามารถเพิ่มหรือแก้ไขได้');
        }

        // ให้ layout รู้ว่าหน้านี้เป็นโหมดดูอย่างเดียว เพื่อแสดงแถบแจ้งและปิดปุ่มบันทึก
        if (!$user->canEditModule($key)) {
            $request->attributes->set('readOnlyModule', true);
        }

        return $next($request);
    }

    private function isEditRequest(Request $request): bool
    {
        if (!$request->isMethodSafe()) {
            return true;
        }

        // หน้าฟอร์ม เช่น schedules.create, schedules.bulk-create, sales.edit
        return (bool) preg_match('/[.\-](create|edit)$/', (string) $request->route()?->getName());
    }
}

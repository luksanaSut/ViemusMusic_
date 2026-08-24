<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogAuditTrail
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    // ทำงานหลังตอบ response แล้ว ไม่หน่วง user
    public function terminate(Request $request, Response $response): void
    {
        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return;
        }

        $user = $request->user();
        $input = $request->except(['password', 'password_confirmation', 'current_password', '_token', '_method', 'payment_proof']);

        AuditLog::create([
            'user_id'     => $user?->id,
            'user_name'   => $user?->displayName(),
            'user_role'   => $user?->role,
            'method'      => $request->method(),
            'path'        => $request->path(),
            'route_name'  => $request->route()?->getName(),
            'status_code' => $response->getStatusCode(),
            'ip_address'  => $request->ip(),
            'meta'        => $input ?: null,
        ]);
    }
}

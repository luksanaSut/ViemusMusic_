<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    // GET /role-permissions
    public function index()
    {
        $grantedKeys = RolePermission::where('role', 'staff')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->pluck('permissions.key')
            ->all();

        $permissions = Permission::where('is_locked', false)
            ->orderBy('module')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('module');

        return view('role-permissions.index', compact('permissions', 'grantedKeys'));
    }

    // POST /role-permissions
    public function update(Request $request)
    {
        $data = $request->validate([
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ]);

        // เฉพาะโมดูลที่ไม่ล็อกเท่านั้นที่แก้ได้ (กันแก้ผ่าน request ปลอมด้วย)
        $grantableIds = Permission::where('is_locked', false)
            ->whereIn('key', $data['permissions'] ?? [])
            ->pluck('id');

        $rows = $grantableIds->map(fn ($id) => [
            'role'          => 'staff',
            'permission_id' => $id,
        ]);

        RolePermission::where('role', 'staff')->delete();
        if ($rows->isNotEmpty()) {
            RolePermission::insert($rows->map(fn ($r) => [
                ...$r,
                'created_at' => now(),
                'updated_at' => now(),
            ])->all());
        }

        return back()->with('success', 'บันทึกสิทธิ์เรียบร้อยแล้ว');
    }
}

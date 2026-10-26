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
        // key => can_edit ของ staff (มี key = ดูได้)
        $granted = RolePermission::where('role', 'staff')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->pluck('role_permissions.can_edit', 'permissions.key')
            ->map(fn ($canEdit) => (bool) $canEdit)
            ->all();

        $permissions = Permission::where('is_locked', false)
            ->orderBy('module')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('module');

        return view('role-permissions.index', compact('permissions', 'granted'));
    }

    // POST /role-permissions
    public function update(Request $request)
    {
        $data = $request->validate([
            'view'   => ['nullable', 'array'],
            'view.*' => ['string'],
            'edit'   => ['nullable', 'array'],
            'edit.*' => ['string'],
        ]);

        // แก้ไขได้ = ดูได้ด้วยเสมอ
        $editKeys = $data['edit'] ?? [];
        $viewKeys = array_unique([...($data['view'] ?? []), ...$editKeys]);

        // เฉพาะโมดูลที่ไม่ล็อกเท่านั้นที่แก้ได้ (กันแก้ผ่าน request ปลอมด้วย)
        $grantable = Permission::where('is_locked', false)
            ->whereIn('key', $viewKeys)
            ->pluck('id', 'key');

        $rows = $grantable->map(fn ($id, $key) => [
            'role'          => 'staff',
            'permission_id' => $id,
            'can_edit'      => in_array($key, $editKeys, true),
            'created_at'    => now(),
            'updated_at'    => now(),
        ])->values();

        RolePermission::where('role', 'staff')->delete();
        if ($rows->isNotEmpty()) {
            RolePermission::insert($rows->all());
        }

        return back()->with('success', 'บันทึกสิทธิ์เรียบร้อยแล้ว');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    // GET /audit-logs
    public function index(Request $request)
    {
        $logs = AuditLog::with('user')
            ->search($request->get('q'))
            ->forUser($request->get('user_id') ? (int) $request->get('user_id') : null)
            ->between($request->get('date_from'), $request->get('date_to'))
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        $users = User::orderBy('name')->get(['id', 'name', 'email', 'role']);

        return view('audit-logs.index', compact('logs', 'users'));
    }
}

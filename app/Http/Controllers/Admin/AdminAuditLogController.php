<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class AdminAuditLogController extends Controller
{
    public function index(Request $request)
    {
        $action = $request->query('action');
        $search = $request->query('q');

        $query = AuditLog::with('user');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($action && $action !== 'all') {
            $query->where('action', $action);
        }

        $logs = $query->latest('created_at')->paginate(25)->withQueryString();
        $actions = AuditLog::distinct()->pluck('action');

        return view('admin.audit_logs.index', compact('logs', 'actions', 'action', 'search'));
    }
}

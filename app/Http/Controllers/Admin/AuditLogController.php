<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $auditLogs = AdminAuditLog::query()
            ->with('adminUser')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('action', 'like', '%' . $search . '%')
                        ->orWhere('summary', 'like', '%' . $search . '%')
                        ->orWhere('target_type', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(20)
            ->appends($request->query());

        return view('admin.audit-logs.index', compact('auditLogs', 'search'));
    }
}

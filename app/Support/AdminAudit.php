<?php

namespace App\Support;

use App\Models\AdminAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AdminAudit
{
    public static function log(string $action, Model|string $target, string $summary, array $metadata = []): void
    {
        if (! Schema::hasTable('admin_audit_logs')) {
            return;
        }

        $targetType = is_string($target) ? $target : class_basename($target);
        $targetId = is_string($target) ? null : $target->getKey();

        AdminAuditLog::create([
            'admin_user_id' => Auth::id(),
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'summary' => $summary,
            'metadata' => $metadata,
        ]);
    }
}

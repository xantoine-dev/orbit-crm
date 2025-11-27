<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public function log(Model $subject, string $action, array $changes = []): ActivityLog
    {
        $userId = Auth::id();

        return ActivityLog::create([
            'subject_type' => $subject->getMorphClass(),
            'subject_id' => $subject->getKey(),
            'causer_id' => $userId,
            'action' => $action,
            'changes' => $changes,
        ]);
    }
}

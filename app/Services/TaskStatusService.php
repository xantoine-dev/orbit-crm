<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskStatusService
{
    public function __construct(protected ActivityLogger $logger)
    {
    }

    public function update(Task $task, string $status): Task
    {
        $previous = $task->status;
        $task->status = $status;
        $task->save();

        $this->logger->log($task, 'status_changed', [
            'from' => $previous,
            'to' => $status,
            'by' => Auth::id(),
        ]);

        return $task;
    }
}

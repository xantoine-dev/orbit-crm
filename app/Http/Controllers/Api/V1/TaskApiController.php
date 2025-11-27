<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\TaskStatusRequest;
use App\Models\Task;
use App\Services\TaskStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskApiController extends Controller
{
    public function __construct(protected TaskStatusService $statusService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Task::with(['project', 'assignee'])
            ->status(request('status'))
            ->dueBefore(request('due_before'))
            ->dueAfter(request('due_after'));

        if (! Auth::user()->isAdmin()) {
            $query->where('assigned_to', Auth::id());
        }

        return $query->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $this->authorize('create', Task::class);

        $task = Task::create($request->validated());

        return response()->json($task->load(['project', 'assignee']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return $task->load(['project', 'assignee']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->update($request->validated());

        return $task->load(['project', 'assignee']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return response()->noContent();
    }

    public function updateStatus(TaskStatusRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $updated = $this->statusService->update($task, $request->validated()['status']);

        return response()->json($updated);
    }
}

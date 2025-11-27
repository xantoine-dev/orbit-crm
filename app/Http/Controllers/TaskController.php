<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Requests\TaskStatusRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\TaskStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct(
        protected ActivityLogger $logger,
        protected TaskStatusService $statusService
    ) {
        $this->authorizeResource(Task::class, 'task');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with(['project', 'assignee'])
            ->status(request('status'))
            ->assignedTo(request('assigned_to'))
            ->dueBefore(request('due_before'))
            ->dueAfter(request('due_after'))
            ->search(request('q'))
            ->latest()
            ->paginate(10);

        $users = User::orderBy('name')->get();
        $projects = Project::orderBy('title')->get();
        $trashed = Auth::user()->isAdmin()
            ? Task::onlyTrashed()->with(['project', 'assignee'])->latest()->get()
            : collect();

        return view('tasks.index', compact('tasks', 'users', 'projects', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::orderBy('title')->get();
        $users = User::orderBy('name')->get();

        return view('tasks.create', compact('projects', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $task = Task::create($request->validated());
        $this->logger->log($task, 'task_created');

        return redirect()->route('tasks.index')->with('status', 'Task created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load(['project', 'assignee']);

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $projects = Project::orderBy('title')->get();
        $users = User::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        $this->logger->log($task, 'task_updated');

        return redirect()->route('tasks.index')->with('status', 'Task updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        $this->logger->log($task, 'task_deleted');

        return redirect()->route('tasks.index')->with('status', 'Task moved to trash.');
    }

    public function restore(int $taskId): RedirectResponse
    {
        $task = Task::withTrashed()->findOrFail($taskId);
        $this->authorize('restore', $task);

        $task->restore();
        $this->logger->log($task, 'task_restored');

        return redirect()->route('tasks.index')->with('status', 'Task restored.');
    }

    public function updateStatus(TaskStatusRequest $request, Task $task)
    {
        $this->statusService->update($task, $request->validated()['status']);

        if ($request->wantsJson()) {
            return response()->json(['status' => $task->status]);
        }

        return redirect()->back()->with('status', 'Task status updated.');
    }
}

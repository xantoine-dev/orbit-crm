<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(FilterRequest $request)
    {
        $user = Auth::user();
        $filters = $request->validated();

        if ($user->isAdmin()) {
            $stats = [
                'clients' => Client::count(),
                'projects_total' => Project::count(),
                'projects_active' => Project::where('status', 'active')->count(),
                'tasks_open' => Task::whereIn('status', ['todo', 'in_progress'])->count(),
                'tasks_overdue' => Task::where('status', '!=', 'done')
                    ->whereDate('due_date', '<', now()->toDateString())
                    ->count(),
            ];

            $overdueTasks = Task::with(['project', 'assignee'])
                ->where('status', '!=', 'done')
                ->whereDate('due_date', '<', now()->toDateString())
                ->orderBy('due_date')
                ->limit(10)
                ->get();

            $recentActivity = ActivityLog::with('causer')
                ->latest()
                ->limit(10)
                ->get();

            return view('dashboard', compact('stats', 'overdueTasks', 'recentActivity', 'filters'));
        }

        $tasks = Task::with('project')
            ->where('assigned_to', $user->id)
            ->status($filters['status'] ?? null)
            ->dueBefore($filters['due_before'] ?? null)
            ->dueAfter($filters['due_after'] ?? null)
            ->orderBy('due_date')
            ->paginate(10);

        return view('dashboard', compact('tasks', 'filters'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reports)
    {
        $this->middleware(function ($request, $next) {
            abort_if(! auth()->user()?->isAdmin(), 403);

            return $next($request);
        });
    }

    public function index()
    {
        $projects = Project::orderBy('title')->get();
        $users = User::orderBy('name')->get();

        return view('reports.index', compact('projects', 'users'));
    }

    public function export(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'in:project,user,overdue'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'format' => ['required', 'in:csv,pdf'],
        ]);

        switch ($data['type']) {
            case 'project':
                $tasks = $this->reports->tasksByProject((int) $data['project_id']);
                $title = 'Tasks for Project';
                break;
            case 'user':
                $tasks = $this->reports->tasksByUser((int) $data['user_id']);
                $title = 'Tasks by User';
                break;
            default:
                $tasks = $this->reports->overdueTasks();
                $title = 'Overdue Tasks';
                break;
        }

        if ($data['format'] === 'csv') {
            return $this->reports->toCsv(str($title)->snake()->toString(), $tasks);
        }

        return $this->reports->toPdf($title, $tasks);
    }
}

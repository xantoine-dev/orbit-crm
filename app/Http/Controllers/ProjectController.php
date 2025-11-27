<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Client;
use App\Models\Project;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct(protected ActivityLogger $logger)
    {
        $this->authorizeResource(Project::class, 'project');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with(['client', 'creator'])
            ->status(request('status'))
            ->search(request('q'))
            ->latest()
            ->paginate(10);

        $trashed = Auth::user()->isAdmin()
            ? Project::onlyTrashed()->with('client')->latest()->get()
            : collect();

        return view('projects.index', compact('projects', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();

        return view('projects.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();

        $project = Project::create($data);
        $this->logger->log($project, 'project_created');

        return redirect()->route('projects.index')->with('status', 'Project created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['client', 'tasks.assignee']);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $clients = Client::orderBy('name')->get();

        return view('projects.edit', compact('project', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        $this->logger->log($project, 'project_updated');

        return redirect()->route('projects.index')->with('status', 'Project updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        $this->logger->log($project, 'project_deleted');

        return redirect()->route('projects.index')->with('status', 'Project moved to trash.');
    }

    public function restore(int $projectId)
    {
        $project = Project::withTrashed()->findOrFail($projectId);
        $this->authorize('restore', $project);

        $project->restore();
        $this->logger->log($project, 'project_restored');

        return redirect()->route('projects.index')->with('status', 'Project restored.');
    }
}

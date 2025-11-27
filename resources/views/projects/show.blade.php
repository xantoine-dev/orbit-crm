<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0">{{ $project->title }}</h2>
                <div class="text-muted small">{{ $project->client->name ?? 'No client' }} · Due {{ optional($project->due_date)->toFormattedDateString() }}</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn btn-sm btn-outline-secondary">New Task</a>
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-primary">Edit</a>
            </div>
        </div>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-header fw-semibold">Tasks</div>
            <div class="list-group list-group-flush">
                @forelse($project->tasks as $task)
                    <a href="{{ route('tasks.show', $task) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $task->title }}</div>
                            <div class="text-muted small">Assigned to {{ $task->assignee->name ?? 'Unassigned' }} · Due {{ optional($task->due_date)->toFormattedDateString() ?? 'n/a' }}</div>
                        </div>
                        <span class="badge text-bg-secondary">{{ ucfirst(str_replace('_',' ', $task->status)) }}</span>
                    </a>
                @empty
                    <div class="list-group-item text-muted">No tasks for this project yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

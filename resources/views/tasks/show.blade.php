<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0">{{ $task->title }}</h2>
                <div class="text-muted small">{{ $task->project->title ?? 'No project' }} · {{ $task->assignee->name ?? 'Unassigned' }}</div>
            </div>
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-primary">Edit</a>
        </div>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small mb-2">Due: {{ optional($task->due_date)->toFormattedDateString() ?? 'n/a' }}</div>
                <div class="mb-3">Status: <span class="badge text-bg-secondary">{{ ucfirst(str_replace('_',' ', $task->status)) }}</span></div>
                <div class="border-top pt-3">
                    <p class="mb-0">{{ $task->description }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

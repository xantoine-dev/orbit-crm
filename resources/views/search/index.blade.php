<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Search</h2>
    </x-slot>

    <div class="container">
        <form method="get" class="d-flex gap-2 mb-3">
            <input type="text" name="q" value="{{ $query }}" placeholder="Search clients, projects, tasks" class="form-control" />
            <button class="btn btn-primary">Search</button>
        </form>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header fw-semibold">Clients</div>
                    <ul class="list-group list-group-flush">
                        @forelse($results['clients'] as $client)
                            <li class="list-group-item"><a class="link-primary" href="{{ route('clients.show', $client) }}">{{ $client->name }}</a></li>
                        @empty
                            <li class="list-group-item text-muted">No clients</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header fw-semibold">Projects</div>
                    <ul class="list-group list-group-flush">
                        @forelse($results['projects'] as $project)
                            <li class="list-group-item"><a class="link-primary" href="{{ route('projects.show', $project) }}">{{ $project->title }}</a></li>
                        @empty
                            <li class="list-group-item text-muted">No projects</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header fw-semibold">Tasks</div>
                    <ul class="list-group list-group-flush">
                        @forelse($results['tasks'] as $task)
                            <li class="list-group-item"><a class="link-primary" href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></li>
                        @empty
                            <li class="list-group-item text-muted">No tasks</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

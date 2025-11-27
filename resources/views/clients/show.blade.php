<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0">{{ $client->name }}</h2>
                <div class="text-muted small">{{ $client->contact_email }} · {{ $client->contact_phone }}</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-primary">Edit</a>
                <a href="{{ route('projects.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-outline-secondary">New Project</a>
            </div>
        </div>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-header fw-semibold">Projects</div>
            <div class="list-group list-group-flush">
                @forelse($client->projects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $project->title }}</div>
                            <div class="text-muted small">Due {{ optional($project->due_date)->toFormattedDateString() ?? 'n/a' }}</div>
                        </div>
                        <span class="badge text-bg-secondary">{{ ucfirst($project->status) }}</span>
                    </a>
                @empty
                    <div class="list-group-item text-muted">No projects yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0">Projects</h2>
                <div class="text-muted small">Across all clients.</div>
            </div>
            <div class="d-flex gap-2">
                <form method="get" class="d-flex gap-2">
                    <input name="q" value="{{ request('q') }}" placeholder="Search" class="form-control form-control-sm" />
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Any status</option>
                        @foreach(['planned','active','on_hold','completed'] as $status)
                            <option value="{{ $status }}" @selected(request('status')===$status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-outline-secondary">Filter</button>
                </form>
                <a href="{{ route('projects.create') }}" class="btn btn-sm btn-primary">New Project</a>
            </div>
        </div>
    </x-slot>

    <div class="container">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="card mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Client</th>
                            <th>Due</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td class="fw-semibold"><a href="{{ route('projects.show', $project) }}">{{ $project->title }}</a></td>
                                <td class="small text-muted">{{ $project->client->name ?? 'N/A' }}</td>
                                <td class="small text-muted">{{ optional($project->due_date)->toFormattedDateString() ?? 'n/a' }}</td>
                                <td><span class="badge text-bg-secondary">{{ ucfirst($project->status) }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('projects.destroy', $project) }}" method="post" class="d-inline" onsubmit="return confirm('Delete project?')">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No projects found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $projects->links() }}</div>
        </div>

        @if($trashed->count())
            <div class="card">
                <div class="card-header fw-semibold">Trash</div>
                <div class="card-body">
                    @foreach($trashed as $project)
                        <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                            <div>{{ $project->title }}</div>
                            <form action="{{ route('projects.restore', $project->id) }}" method="post">
                                @csrf
                                <button class="btn btn-sm btn-outline-primary">Restore</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

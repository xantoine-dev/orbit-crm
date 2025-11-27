<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0">Tasks</h2>
                <div class="text-muted small">Track and update work.</div>
            </div>
            <div class="d-flex gap-2">
                <form method="get" class="d-flex gap-2">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search" class="form-control form-control-sm" />
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Any status</option>
                        @foreach(['todo','in_progress','done'] as $status)
                            <option value="{{ $status }}" @selected(request('status')===$status)>{{ ucfirst(str_replace('_',' ', $status)) }}</option>
                        @endforeach
                    </select>
                    <select name="assigned_to" class="form-select form-select-sm">
                        <option value="">Anyone</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(request('assigned_to')==$user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-outline-secondary">Filter</button>
                </form>
                <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-primary">New Task</a>
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
                            <th>Task</th>
                            <th>Project</th>
                            <th>Assignee</th>
                            <th>Due</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td class="fw-semibold"><a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></td>
                                <td class="small text-muted">{{ $task->project->title ?? 'n/a' }}</td>
                                <td class="small text-muted">{{ $task->assignee->name ?? 'Unassigned' }}</td>
                                <td class="small {{ $task->is_overdue ? 'text-danger fw-semibold' : 'text-muted' }}">{{ optional($task->due_date)->toFormattedDateString() ?? 'n/a' }}</td>
                                <td>
                                    <form action="{{ route('tasks.status', $task) }}" method="post" class="d-inline">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                            @foreach(['todo','in_progress','done'] as $status)
                                                <option value="{{ $status }}" @selected($task->status === $status)>{{ ucfirst(str_replace('_',' ', $status)) }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="post" class="d-inline" onsubmit="return confirm('Delete task?')">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No tasks found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $tasks->links() }}</div>
        </div>

        @if($trashed->count())
            <div class="card">
                <div class="card-header fw-semibold">Trash</div>
                <div class="card-body">
                    @foreach($trashed as $task)
                        <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                            <div>{{ $task->title }}</div>
                            <form action="{{ route('tasks.restore', $task->id) }}" method="post">
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

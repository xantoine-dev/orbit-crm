<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Dashboard</h2>
            @unless(auth()->user()->isAdmin())
                <form method="get" action="{{ route('dashboard') }}" class="d-flex gap-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All statuses</option>
                        <option value="todo" @selected(request('status')==='todo')>To Do</option>
                        <option value="in_progress" @selected(request('status')==='in_progress')>In Progress</option>
                        <option value="done" @selected(request('status')==='done')>Done</option>
                    </select>
                    <input type="date" name="due_before" value="{{ request('due_before') }}" class="form-control form-control-sm">
                    <input type="date" name="due_after" value="{{ request('due_after') }}" class="form-control form-control-sm">
                    <button class="btn btn-sm btn-primary">Filter</button>
                </form>
            @endunless
        </div>
    </x-slot>

    <div class="container">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if(auth()->user()->isAdmin())
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted small">Clients</div>
                            <div class="fs-3 fw-semibold">{{ $stats['clients'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted small">Projects (Active/Total)</div>
                            <div class="fs-3 fw-semibold">{{ $stats['projects_active'] ?? 0 }} / {{ $stats['projects_total'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted small">Tasks (Open/Overdue)</div>
                            <div class="fs-3 fw-semibold">{{ $stats['tasks_open'] ?? 0 }} / {{ $stats['tasks_overdue'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Overdue Tasks</span>
                            <a href="{{ route('tasks.index', ['status' => 'todo']) }}" class="btn btn-link btn-sm">View all</a>
                        </div>
                        <div class="card-body">
                            @forelse($overdueTasks ?? [] as $task)
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                    <div>
                                        <div class="fw-semibold">{{ $task->title }}</div>
                                        <div class="text-muted small">{{ $task->project->title ?? 'No project' }} · Due {{ optional($task->due_date)->toFormattedDateString() }}</div>
                                    </div>
                                    <span class="badge text-bg-danger">Overdue</span>
                                </div>
                            @empty
                                <p class="text-muted mb-0">All clear! No overdue tasks.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Recent Activity</span>
                            <a href="{{ route('activity-logs.index') }}" class="btn btn-link btn-sm">View all</a>
                        </div>
                        <div class="card-body">
                            @forelse($recentActivity ?? [] as $activity)
                                <div class="border rounded p-2 mb-2">
                                    <div class="fw-semibold small">{{ $activity->action }}</div>
                                    <div class="text-muted small">by {{ $activity->causer->name ?? 'System' }} · {{ $activity->created_at->diffForHumans() }}</div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No activity yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-header fw-semibold">My Tasks</div>
                <div class="card-body p-0">
                    @forelse($tasks as $task)
                        <div class="d-flex justify-content-between align-items-center border-bottom px-3 py-3">
                            <div>
                                <div class="fw-semibold">{{ $task->title }}</div>
                                <div class="text-muted small">{{ $task->project->title ?? 'No project' }} · Due {{ optional($task->due_date)->toFormattedDateString() ?? 'n/a' }}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge text-bg-{{ $task->status === 'done' ? 'success' : 'secondary' }}">{{ ucfirst(str_replace('_',' ', $task->status)) }}</span>
                                <form action="{{ route('tasks.status', $task) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $task->status === 'done' ? 'in_progress' : 'done' }}">
                                    <button class="btn btn-sm btn-outline-primary">{{ $task->status === 'done' ? 'Reopen' : 'Mark done' }}</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted px-3 py-3 mb-0">No tasks assigned — create one now.</p>
                    @endforelse
                </div>
                <div class="card-footer">{{ $tasks->links() }}</div>
            </div>
        @endif
    </div>
</x-app-layout>

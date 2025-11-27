<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Reports</h2>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('reports.export') }}" method="get" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Report Type</label>
                        <select name="type" class="form-select" required>
                            <option value="project">Tasks per project</option>
                            <option value="user">Tasks per user</option>
                            <option value="overdue">Overdue tasks</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Format</label>
                        <select name="format" class="form-select">
                            <option value="csv">CSV</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Project</label>
                        <select name="project_id" class="form-select">
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-select">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary">Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

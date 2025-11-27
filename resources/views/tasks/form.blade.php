@csrf
<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Title</label>
            <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}" class="mt-1 w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Project</label>
            <select name="project_id" class="mt-1 w-full rounded border-gray-300" required>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @selected(old('project_id', $task->project_id ?? request('project_id')) == $project->id)>{{ $project->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium">Description</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded border-gray-300">{{ old('description', $task->description ?? '') }}</textarea>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium">Assignee</label>
            <select name="assigned_to" class="mt-1 w-full rounded border-gray-300">
                <option value="">Unassigned</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('assigned_to', $task->assigned_to ?? '') == $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium">Due Date</label>
            <input type="date" name="due_date" value="{{ old('due_date', optional($task->due_date ?? null)->toDateString()) }}" class="mt-1 w-full rounded border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Status</label>
            <select name="status" class="mt-1 w-full rounded border-gray-300">
                @foreach(['todo','in_progress','done'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $task->status ?? 'todo') === $status)>{{ ucfirst(str_replace('_',' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="mt-4 flex justify-end space-x-2">
    <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
    <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
</div>

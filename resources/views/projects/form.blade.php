@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Title</label>
        <input type="text" name="title" value="{{ old('title', $project->title ?? '') }}" class="mt-1 w-full rounded border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium">Client</label>
        <select name="client_id" class="mt-1 w-full rounded border-gray-300" required>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $project->client_id ?? request('client_id')) == $client->id)>{{ $client->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium">Description</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded border-gray-300">{{ old('description', $project->description ?? '') }}</textarea>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium">Start Date</label>
            <input type="date" name="start_date" value="{{ old('start_date', optional($project->start_date ?? null)->toDateString()) }}" class="mt-1 w-full rounded border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Due Date</label>
            <input type="date" name="due_date" value="{{ old('due_date', optional($project->due_date ?? null)->toDateString()) }}" class="mt-1 w-full rounded border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Status</label>
            <select name="status" class="mt-1 w-full rounded border-gray-300">
                @foreach(['planned','active','on_hold','completed'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $project->status ?? 'planned') === $status)>{{ ucfirst(str_replace('_',' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="mt-4 flex justify-end space-x-2">
    <a href="{{ route('projects.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
    <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
</div>

@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Name</label>
        <input type="text" name="name" value="{{ old('name', $client->name ?? '') }}" class="mt-1 w-full rounded border-gray-300" required>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Contact Email</label>
            <input type="email" name="contact_email" value="{{ old('contact_email', $client->contact_email ?? '') }}" class="mt-1 w-full rounded border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Contact Phone</label>
            <input type="text" name="contact_phone" value="{{ old('contact_phone', $client->contact_phone ?? '') }}" class="mt-1 w-full rounded border-gray-300">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium">Notes</label>
        <textarea name="notes" rows="3" class="mt-1 w-full rounded border-gray-300">{{ old('notes', $client->notes ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4 flex justify-end space-x-2">
    <a href="{{ route('clients.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
    <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
</div>

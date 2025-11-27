@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="mt-1 w-full rounded border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="mt-1 w-full rounded border-gray-300" required>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Role</label>
            <select name="role" class="mt-1 w-full rounded border-gray-300">
                @foreach(['admin','staff'] as $role)
                    <option value="{{ $role }}" @selected(old('role', $user->role ?? 'staff') === $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium">Password</label>
            <input type="password" name="password" class="mt-1 w-full rounded border-gray-300" {{ isset($user) ? '' : 'required' }}>
            @isset($user)
                <p class="text-xs text-gray-500">Leave blank to keep current password.</p>
            @endisset
        </div>
    </div>
</div>
<div class="mt-4 flex justify-end space-x-2">
    <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
    <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
</div>

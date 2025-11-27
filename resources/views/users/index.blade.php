<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Users</h2>
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">New User</a>
        </div>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td class="small text-muted">{{ $user->email }}</td>
                                <td><span class="badge text-bg-secondary">{{ ucfirst($user->role) }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('users.destroy', $user) }}" method="post" class="d-inline" onsubmit="return confirm('Delete user?')">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $users->links() }}</div>
        </div>
    </div>
</x-app-layout>

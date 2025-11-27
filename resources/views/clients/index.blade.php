<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0">Clients</h2>
                <div class="text-muted small">Manage customers and related projects.</div>
            </div>
            <div class="d-flex gap-2">
                <form method="get" class="d-flex gap-2">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search" class="form-control form-control-sm" />
                    <button class="btn btn-sm btn-outline-secondary">Filter</button>
                </form>
                @can('create', App\Models\Client::class)
                    <a href="{{ route('clients.create') }}" class="btn btn-sm btn-primary">New Client</a>
                @endcan
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
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Projects</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td><a href="{{ route('clients.show', $client) }}" class="fw-semibold">{{ $client->name }}</a></td>
                                <td class="small text-muted">{{ $client->contact_email }}<br>{{ $client->contact_phone }}</td>
                                <td class="small text-muted">{{ $client->projects()->count() }}</td>
                                <td class="text-end">
                                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('clients.destroy', $client) }}" method="post" class="d-inline" onsubmit="return confirm('Delete client?')">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No clients found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $clients->links() }}</div>
        </div>

        @if($trashed->count())
            <div class="card">
                <div class="card-header fw-semibold">Trash</div>
                <div class="card-body">
                    @foreach($trashed as $client)
                        <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                            <div>{{ $client->name }}</div>
                            <form action="{{ route('clients.restore', $client->id) }}" method="post">
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

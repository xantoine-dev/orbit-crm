<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Activity Log</h2>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-body">
                @forelse($logs as $log)
                    <div class="border rounded p-2 mb-2">
                        <div class="fw-semibold small">{{ $log->action }}</div>
                        <div class="text-muted small">{{ $log->created_at->toDayDateTimeString() }} by {{ $log->causer->name ?? 'System' }}</div>
                        @if($log->changes)
                            <pre class="bg-light p-2 rounded small mb-0">{{ json_encode($log->changes, JSON_PRETTY_PRINT) }}</pre>
                        @endif
                    </div>
                @empty
                    <p class="text-muted mb-0">No activity yet.</p>
                @endforelse
                <div class="mt-3">{{ $logs->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>

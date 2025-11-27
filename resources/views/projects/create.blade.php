<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">New Project</h2>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('projects.store') }}" method="post">
                    @include('projects.form', ['project' => new \App\Models\Project(), 'clients' => $clients])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

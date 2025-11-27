<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Edit Project</h2>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('projects.update', $project) }}" method="post">
                    @method('put')
                    @include('projects.form', ['project' => $project, 'clients' => $clients])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

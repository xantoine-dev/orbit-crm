<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">New Task</h2>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('tasks.store') }}" method="post">
                    @include('tasks.form', ['task' => new \App\Models\Task(), 'projects' => $projects, 'users' => $users])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

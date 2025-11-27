<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Edit Task</h2>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('tasks.update', $task) }}" method="post">
                    @method('put')
                    @include('tasks.form', ['task' => $task, 'projects' => $projects, 'users' => $users])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

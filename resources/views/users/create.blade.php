<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">New User</h2>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="post">
                    @include('users.form', ['user' => new \App\Models\User()])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

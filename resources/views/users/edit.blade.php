<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Edit User</h2>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="post">
                    @method('put')
                    @include('users.form', ['user' => $user])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

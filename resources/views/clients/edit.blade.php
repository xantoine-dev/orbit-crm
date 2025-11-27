<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Edit Client</h2>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('clients.update', $client) }}" method="post">
                    @method('put')
                    @include('clients.form', ['client' => $client])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

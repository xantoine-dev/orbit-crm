<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">New Client</h2>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('clients.store') }}" method="post">
                    @include('clients.form', ['client' => new \App\Models\Client()])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

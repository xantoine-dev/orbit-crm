<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function __construct(protected ActivityLogger $logger)
    {
        $this->authorizeResource(Client::class, 'client');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Client::query()->search(request('q'))->with('creator');
        $clients = $query->latest()->paginate(10);
        $trashed = Auth::user()->isAdmin()
            ? Client::onlyTrashed()->latest()->get()
            : collect();

        return view('clients.index', compact('clients', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();

        $client = Client::create($data);
        $this->logger->log($client, 'client_created');

        return redirect()->route('clients.index')->with('status', 'Client created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load(['projects.tasks']);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->validated());
        $this->logger->log($client, 'client_updated');

        return redirect()->route('clients.index')->with('status', 'Client updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        $this->logger->log($client, 'client_deleted');

        return redirect()->route('clients.index')->with('status', 'Client moved to trash.');
    }

    public function restore(int $clientId)
    {
        $client = Client::withTrashed()->findOrFail($clientId);
        $this->authorize('restore', $client);

        $client->restore();
        $this->logger->log($client, 'client_restored');

        return redirect()->route('clients.index')->with('status', 'Client restored.');
    }
}

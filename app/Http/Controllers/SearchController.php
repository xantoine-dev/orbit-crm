<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Services\SearchService;

class SearchController extends Controller
{
    public function __construct(protected SearchService $service)
    {
    }

    public function __invoke(SearchRequest $request)
    {
        $results = $this->service->search($request->validated()['q'] ?? '');

        return view('search.index', [
            'results' => $results,
            'query' => $request->validated()['q'] ?? '',
        ]);
    }
}

<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;

class SearchService
{
    public function search(?string $term): array
    {
        $term = trim((string) $term);

        return [
            'clients' => Client::search($term)->latest()->limit(5)->get(),
            'projects' => Project::search($term)->latest()->limit(5)->get(),
            'tasks' => Task::search($term)->latest()->limit(5)->get(),
        ];
    }
}

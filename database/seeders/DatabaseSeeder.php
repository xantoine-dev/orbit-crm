<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $staff = User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'role' => 'staff',
            'password' => Hash::make('password'),
        ]);

        $clients = [
            Client::create([
                'name' => 'Acme Corp',
                'contact_email' => 'contact@acme.test',
                'contact_phone' => '555-111-2222',
                'notes' => 'Priority customer with long-term projects.',
                'created_by' => $admin->id,
            ]),
            Client::create([
                'name' => 'Globex Inc',
                'contact_email' => 'hello@globex.test',
                'contact_phone' => '555-333-4444',
                'notes' => 'Interested in recurring engagements.',
                'created_by' => $admin->id,
            ]),
        ];

        foreach ($clients as $index => $client) {
            $project = Project::create([
                'client_id' => $client->id,
                'title' => 'Project ' . ($index + 1),
                'description' => 'Kickoff project for ' . $client->name,
                'start_date' => now()->subDays(10),
                'due_date' => now()->addDays(20 + $index * 5),
                'status' => 'active',
                'created_by' => $admin->id,
            ]);

            $assignees = [$admin->id, $staff->id];

            foreach (range(1, 3) as $taskIndex) {
                Task::create([
                    'project_id' => $project->id,
                    'title' => "Task {$taskIndex} for {$project->title}",
                    'description' => 'Auto generated seed task',
                    'assigned_to' => $assignees[$taskIndex % count($assignees)],
                    'due_date' => now()->addDays($taskIndex * 3 - $index),
                    'status' => $taskIndex === 3 ? 'todo' : 'in_progress',
                ]);
            }
        }
    }
}

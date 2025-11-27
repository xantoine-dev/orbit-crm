<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => fn () => \App\Models\Project::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->sentence(),
            'assigned_to' => fn () => \App\Models\User::factory(),
            'due_date' => now()->addDays(3)->toDateString(),
            'status' => 'todo',
        ];
    }
}

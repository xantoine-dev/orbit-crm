<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrmFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_login(): void
    {
        $response = $this->post('/register', [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $this->post('/logout');

        $this->post('/login', [
            'email' => 'new@example.com',
            'password' => 'password',
        ])->assertRedirect('/dashboard');
    }

    public function test_admin_can_create_edit_delete_client(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('clients.store'), [
                'name' => 'Client A',
                'contact_email' => 'client@example.com',
                'contact_phone' => '123',
                'notes' => 'note',
            ])->assertRedirect(route('clients.index'));

        $client = Client::first();
        $this->assertNotNull($client);

        $this->actingAs($admin)
            ->put(route('clients.update', $client), [
                'name' => 'Client B',
                'contact_email' => 'clientb@example.com',
                'contact_phone' => '123',
                'notes' => 'note',
            ])->assertRedirect(route('clients.index'));

        $this->actingAs($admin)
            ->delete(route('clients.destroy', $client))
            ->assertRedirect(route('clients.index'));

        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    }

    public function test_admin_can_create_and_update_project(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin)
            ->post(route('projects.store'), [
                'client_id' => $client->id,
                'title' => 'Project 1',
                'description' => 'Desc',
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'due_date' => now()->addDays(5)->toDateString(),
            ])->assertRedirect(route('projects.index'));

        $project = Project::first();

        $this->actingAs($admin)
            ->put(route('projects.update', $project), [
                'client_id' => $client->id,
                'title' => 'Project Updated',
                'description' => 'Updated',
                'status' => 'on_hold',
                'start_date' => now()->toDateString(),
                'due_date' => now()->addDays(10)->toDateString(),
            ])->assertRedirect(route('projects.index'));
    }

    public function test_task_assignment_and_status_change(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);
        $client = Client::factory()->create(['created_by' => $admin->id]);
        $project = Project::factory()->create([
            'client_id' => $client->id,
            'created_by' => $admin->id,
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->post(route('tasks.store'), [
                'project_id' => $project->id,
                'title' => 'Task 1',
                'description' => 'Test',
                'assigned_to' => $staff->id,
                'status' => 'todo',
                'due_date' => now()->addDay()->toDateString(),
            ])->assertRedirect(route('tasks.index'));

        $task = Task::first();

        $this->actingAs($staff)
            ->post(route('tasks.status', $task), [
                'status' => 'done',
            ])->assertRedirect();

        $this->assertEquals('done', $task->fresh()->status);
    }

    public function test_search_returns_results(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create(['name' => 'Alpha Client', 'created_by' => $admin->id]);
        $project = Project::factory()->create(['title' => 'Alpha Project', 'client_id' => $client->id, 'created_by' => $admin->id]);
        Task::factory()->create(['title' => 'Alpha Task', 'project_id' => $project->id, 'status' => 'todo']);

        $this->actingAs($admin)
            ->get(route('search', ['q' => 'Alpha']))
            ->assertSee('Alpha Client')
            ->assertSee('Alpha Project')
            ->assertSee('Alpha Task');
    }
}

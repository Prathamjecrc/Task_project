<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class TaskTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_list_tasks()
{
    $user = User::factory()->create(); // Create a user for testing
    $this->actingAs($user, 'api'); // Authenticate as the user using API tokens

    $response = $this->get('/api/task'); // Send a GET request to the tasks endpoint

    $response->assertStatus(200); // Check if the response status code is 200 (OK)
    // Add more assertions as needed to verify the response.
}

public function test_authenticated_user_can_create_task()
{
    $user = User::factory()->create();
    $this->actingAs($user, 'api');

    $taskData = [
        'title' => 'Sample Task',
        'description' => 'This is a test task',
        'due_date' => '2023-12-31',
        'priority' => 'medium',
    ];

    $response = $this->post('/api/task', $taskData);

    $response->assertStatus(201); // Check if the response status code is 201 (Created)
    $this->assertDatabaseHas('task', $taskData); // Check if the task data is stored in the database
}

}

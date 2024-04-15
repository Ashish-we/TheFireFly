<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

//to test the command 
// important!!! => first remove the validation constrain => 'after:' . Date::tomorrow() from the TaskController
// so that we can add the task with past date to test the ageout task deletion command

class PurgeOldItemsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_it_deletes_old_tasks()
    {
        //create user
        $user = User::factory()->create();
        //login user
        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();
        // Create tasks with due dates older than the specified age
        $response = $this->post(route('task.add'), [
            'title' => 'hello',
            'description' => 'hello123',
            'due_date' => now()->subDays(10)->format('Y/m/d')
        ]);
        $oldTask1 = Task::latest()->first();
        $response = $this->post(route('task.add'), [
            'title' => 'hello321',
            'description' => 'hello abc',
            'due_date' => now()->subDays(8)->format('Y/m/d')
        ]);
        $oldTask2 = Task::latest()->first();

        // Run the artisan command to purge old tasks with an age limit of 7 days
        Artisan::call('app:purge-old-items', ['age' => 7]);

        // Assert that the old tasks have been deleted
        $this->assertDatabaseMissing('tasks', ['id' => $oldTask1->id]);
        $this->assertDatabaseMissing('tasks', ['id' => $oldTask2->id]);
    }

    public function test_it_does_not_delete_tasks_within_age_limit()
    {
        //create user
        $user = User::factory()->create();
        //login user
        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();
        // Create tasks with due dates within the age limit
        $response = $this->post(route('task.add'), [
            'title' => 'hello',
            'description' => 'hello123',
            'due_date' => now()->subDays(5)->format('Y/m/d')
        ]);
        $recentTask1 = Task::latest()->first();
        $response = $this->post(route('task.add'), [
            'title' => 'hello321',
            'description' => 'hello abc',
            'due_date' => now()->subDays(6)->format('Y/m/d')
        ]);
        $recentTask2 = Task::latest()->first();


        // Run the artisan command to purge old tasks with an age limit of 7 days
        Artisan::call('app:purge-old-items', ['age' => 7]);

        // Assert that none of the tasks have been deleted
        $this->assertDatabaseHas('tasks', ['id' => $recentTask1->id]);
        $this->assertDatabaseHas('tasks', ['id' => $recentTask2->id]);
    }
    public function test_it_handles_invalid_age_parameter()
    {
        // Run the artisan command with an invalid age parameter
        Artisan::call('app:purge-old-items', ['age' => -5]);

        // Assert that the command output contains an error message
        $output = Artisan::output();
        $this->assertStringContainsString('Invalid age parameter', $output);
    }
}

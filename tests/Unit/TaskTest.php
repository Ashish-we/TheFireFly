<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class TaskTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    // public function test_example(): void
    // {
    //     $this->assertTrue(true);
    // }

    public function test_new_task_form()
    {
        //create user
        $user = User::factory()->create();
        //login user
        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        //test task form
        $response = $this->get('/task/add');

        $response->assertStatus(200);
    }

    public function test_task_update_form()
    {
        //create user
        $user = User::factory()->create();
        //login user
        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();
        //test task creation
        $response = $this->post(route('task.add'), [
            'title' => 'hello',
            'description' => 'hello123',
            'due_date' => now()->addDays(2)->format('Y/m/d')
        ]);
        //get the task to be updated
        $taskId = Task::latest()->first()->id;

        //access update form
        $response = $this->get('/task/edit/' . $taskId);
        //check status
        if ($user->id == Task::latest()->first()->user_id) {
            $response->assertStatus(200);
        } else {
            $response->assertRedirect('/dashboard');
            $response->assertStatus(302);
        }
    }

    public function test_new_task_creation()
    {
        //create user
        $user = User::factory()->create();
        //login user
        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();
        //test task creation
        $response = $this->post(route('task.add'), [
            'title' => 'hello',
            'description' => 'hello123',
            'due_date' => now()->addDays(2)->format('Y/m/d')
        ]);
        // check response
        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('tasks', ['title' => 'hello']);
        $response->assertStatus(302);
    }

    public function test_read_of_just_created_task()
    {
        //create user
        $user = User::factory()->create();
        //login user
        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();
        //test task creation
        $response = $this->post(route('task.add'), [
            'title' => 'hello',
            'description' => 'hello123',
            'due_date' => now()->addDays(2)->format('Y/m/d')
        ]);
        $taskId = Task::latest()->first()->id;
        //read task
        $response = $this->get('/task/show/' . $taskId);

        //check response
        if ($user->id == Task::latest()->first()->user_id) {
            $response->assertStatus(200);
        } else {
            $response->assertRedirect('/dashboard');
            $response->assertStatus(302);
        }
    }



    public function test_task_update()
    {
        //create user
        $user = User::factory()->create();
        //login user
        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();
        //test task creation
        $response = $this->post(route('task.add'), [
            'title' => 'hello',
            'description' => 'hello123',
            'due_date' => now()->addDays(2)->format('Y/m/d')
        ]);
        //get the task to be updated
        $taskId = Task::latest()->first()->id;

        //update task
        $response = $this->post('/task/update/' . $taskId, [
            'title' => 'hello1',
            'description' => 'hello everyone!',
            'due_date' => now()->addDays(3)->format('Y/m/d')
        ]);

        //check response
        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('tasks', ['title' => 'hello1']);
        $response->assertStatus(302);
    }

    public function test_task_delete()
    {
        //create user
        $user = User::factory()->create();
        //login user
        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();
        //test task creation
        $response = $this->post(route('task.add'), [
            'title' => 'hello',
            'description' => 'hello123',
            'due_date' => now()->addDays(2)->format('Y/m/d')
        ]);
        //get the task to be deleted
        $taskId = Task::where('user_id', $user->id)->first()->id;
        //delete task
        $response = $this->post('/task/delete/' . $taskId);

        //check response
        $response->assertRedirect('/dashboard');
        $response->assertStatus(302);
    }
}

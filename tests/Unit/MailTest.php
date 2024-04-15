<?php

namespace Tests\Unit;

use App\Mail\TaskMail;
use App\Models\Task;
use App\Models\User;
use App\Notifications\ItemUpdatedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_add_task_sends_email()
    {
        Mail::fake();
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

        //check response and email queue
        $response->assertRedirect(route('dashboard'));
        Mail::assertQueued(TaskMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_update_task_sends_notification()
    {
        Notification::fake();
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
        $taskId = Task::where('user_id', $user->id)->first()->id;

        //update task
        $response = $this->post('/task/update/' . $taskId, [
            'title' => 'hello123',
            'description' => 'hello everyone!',
            'due_date' => now()->addDays(3)->format('Y/m/d')
        ]);
        $task = Task::where('user_id', $user->id)->first();
        $this->assertDatabaseHas('tasks', ['title' => 'hello123']);
        //check response and notification
        $response->assertRedirect(route('dashboard'));
        Notification::assertSentTo(
            $user,
            ItemUpdatedNotification::class,
            function ($notification, $channels) use ($task) {
                return $notification->task->id === $task->id;
            }
        );
    }
}

<?php

namespace App\Console;

use App\Mail\TaskDueDateReminder;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\PurgeOldItems::class,
    ];
    /**
     * Define the application's command schedule.
     */

    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {

            $tasks = Task::where('due_date', '<=', Carbon::now()->addDay()) // Due date is within the next 24 hours or less
                ->where('due_date', '>', Carbon::now())            // Due date is not in the past
                ->get();
            foreach ($tasks as $task) {
                $user = User::find($task->user_id);
                Mail::to($user->email)->send(new TaskDueDateReminder($task))->delay(5);
            }
        })->everyday(); //if done less then everyday then we should use '=' instead of '<=' and '>' in where clause above
    }

    /**
     * Register the commands for the application.
     */


    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');


        require base_path('routes/console.php');
    }
}

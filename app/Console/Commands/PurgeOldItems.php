<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class PurgeOldItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge-old-items {age}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete task with given max age';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $age = $this->argument('age');

        if (!is_numeric($age) || $age < 0) {
            $this->error('Invalid age parameter. Please provide a valid non-negative number.');
            return;
        }

        $tasksToDelete = Task::where('due_date', '<=', now()->subDays($age))->get();

        $count = $tasksToDelete->count();

        if ($count === 0) {
            $this->info('No tasks found to delete.');
            return;
        }

        $this->info("Deleting $count old tasks...");

        $tasksToDelete->each->delete();

        $this->info('Old tasks successfully deleted.');
    }
}

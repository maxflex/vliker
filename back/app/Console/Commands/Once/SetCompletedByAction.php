<?php

namespace App\Console\Commands\Once;

use App\Models\Task;
use Illuminate\Console\Command;

class SetCompletedByAction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:once:set-completed-by-action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the new "action_id" field in "actions" table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(Task::count());
        Task::chunk(500, function ($tasks) use ($bar) {
            foreach ($tasks as $task) {
                foreach ($task->actionsFrom as $i => $actionFrom) {
                    if (!isset($task->actionsTo[$i])) {
                        break;
                    }
                    $actionFrom->update([
                        'action_id' => $task->actionsTo[$i]->id
                    ]);
                }
                $bar->advance();
            }
        });
        $bar->finish();
    }
}

<?php

namespace App\Console\Commands;

use App\Enums\BanReason;
use App\Models\Task;
use Illuminate\Console\Command;

class BanCheatTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:ban-cheat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ban cheat tasks';

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
        $bannedTaskIds = [];
        $tasks = Task::where('is_active', true)->get();

        $bar = $this->output->createProgressBar(count($tasks));
        foreach ($tasks as $task) {
            $previousAction = null;
            $warnings = 0;
            foreach ($task->actionsFrom as $action) {
                if ($previousAction !== null) {
                    // если между событиями менее 2 секунд
                    $secondsBetweenActions = $action->created_at->getTimestamp() - $previousAction->created_at->getTimestamp();
                    if ($secondsBetweenActions <= 2) {
                        $warnings++;
                    }
                }
                if ($warnings >= 3) {
                    $task->ban(BanReason::Cheat);
                    $bannedTaskIds[] = $task->id;
                    break;
                }
                $previousAction = $action;
            }
            $bar->advance();
        }
        $bar->finish();

        $this->line("\n");
        $this->info("Banned tasks: " . implode(", ", $bannedTaskIds));
    }
}

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
        $limit = config('ban.limit');
        $bannedTaskIds = [];
        $tasks = Task::query()
            ->where('ban_reason', '<>', BanReason::Cheat)
            // ->notBanned()
            // ->where('is_active', true)
            ->get();

        $bar = $this->output->createProgressBar(count($tasks));
        foreach ($tasks as $task) {
            $previousAction = null;
            $seconds = config('ban.seconds.' . $task->type);
            $warnings = 0;

            foreach ($task->actionsFrom as $action) {
                if ($previousAction !== null) {
                    // если между событиями менее N секунд
                    $secondsBetweenActions = $action->created_at->getTimestamp() - $previousAction->created_at->getTimestamp();
                    if ($secondsBetweenActions <= $seconds) {
                        $warnings++;
                    } else {
                        // иначе обнуляем, т.к. считается только подряд
                        // т.к. кеш очищается через $seconds
                        $warnings = 0;
                    }
                }
                if ($warnings >= $limit) {
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
        if (count($bannedTaskIds) > 0) {
            $this->info("Tasks banned: " . count($bannedTaskIds));
            $this->line(implode(", ", $bannedTaskIds));
        } else {
            $this->info("No tasks banned");
        }
    }
}

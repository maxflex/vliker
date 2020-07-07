<?php

use App\Enums\TaskType;
use Illuminate\Database\Seeder;
use App\Utils\Url;
use App\Models\{Task, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = collect();

        foreach (TaskType::getValues() as $taskType) {
            $urls = explode("\n", file_get_contents(database_path("seeds/urls/{$taskType}.txt")));
            foreach ($urls as $url) {
                if ($url) {
                    $tasks->push(factory(Task::class)->create([
                        'type' => $taskType,
                        'url' => $url,
                        'user_id' => User::inRandomOrder()->first()->id,
                    ]));
                }
            }
        }

        // Making each task active
        foreach ($tasks as $task) {
            $task->actionsFrom()->create([
                'task_id_to' => $tasks
                    ->where('type', $task->type)
                    ->where('id', '!=', $task->id)
                    ->random()
                    ->id,
            ]);
        }
    }
}

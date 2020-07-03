<?php

use App\Enums\TaskType;
use Illuminate\Database\Seeder;
use App\Models\{Task\Task, Task\CompletedTask, User};
use App\Utils\Url;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TasksSeeder extends Seeder
{
    const URLS = [
        'https://vk.com/tmaranina?z=photo13788597_456241331%2Falbum13788597_0%2Frev',
        'https://vk.com/id195816556?z=photo195816556_331512427%2Falbum195816556_0%2Frev',
        'https://vk.com/id312431407?z=photo312431407_456239581%2Falbum312431407_0%2Frev',
        'https://vk.com/genumalinka777?z=photo13226505_374553681%2Falbum13226505_0%2Frev',
        'https://vk.com/roma_acorn?z=photo11481439_456242014%2Falbum11481439_0%2Frev',
        'https://vk.com/roma_acorn?z=photo11481439_352120444%2Falbum11481439_0%2Frev',
        'https://vk.com/id4451?z=photo4451_438124211%2Falbum4451_0%2Frev',
        'https://vk.com/id28758010?z=photo28758010_456239023%2Falbum28758010_0%2Frev',
        'https://vk.com/zornov?z=photo138728600_456239462%2Falbum138728600_0%2Frev',
        'https://vk.com/idim7pulse?z=photo345683401_456239017%2Falbum345683401_0%2Frev',
        'https://vk.com/shil55?z=photo12875569_456240844%2Falbum12875569_0%2Frev',
        'https://vk.com/yarikker?z=photo107818367_456239807%2Falbum107818367_0%2Frev',
        'https://vk.com/nastaisha?z=photo17410965_456250292%2Falbum17410965_0%2Frev',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Task::truncate();

        $userIds = User::pluck('id');
        $urls = collect(self::URLS);

        $taskIds = [];
        foreach (range(1, 100) as $i) {
            $taskIds[] = DB::table('tasks')->insertGetId([
                'url' => Url::shorten($urls->random()),
                'user_id' => $userIds->random(),
                // 'type' => $taskTypes->random(),
                'type' => TaskType::Like,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        };

        // Making each task active
        $taskIds = collect($taskIds);
        foreach ($taskIds as $taskId) {
            foreach ($taskIds->filter(function ($value) use ($taskId) {
                return $value !== $taskId;
            })->random(3) as $targetTaskId) {
                DB::table('completed_tasks')->insert([
                    'completed_by_task_id' => $taskId,
                    'target_task_id' => $targetTaskId,
                ]);
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}

<?php

namespace Tests\Feature;

use App\Enums\BanReason;
use App\Models\{Action, User, Task};
use Illuminate\Support\Facades\DB;

class TasksTest extends FeatureTestCase
{
    public function testExcludeLikedByUserScope()
    {
        $user2 = $this->createUser();

        // Создаем 2 задачи
        $user2_task1 = $this->createTask($user2);
        $user2_task2 = $this->createTask($user2);

        // До лайка должны получить все 2 задачи
        $ids1 = Task::query()
            ->excludeUser($this->me)
            ->pluck('id')
            ->all();

        $ids2 = $user2->tasks()->pluck('id')->all();

        $this->assertTrue($ids1 === $ids2);

        // лайкаем одну из задач
        $this->myTask->likeTask($user2_task1);

        // теперь должна вернуться 1 нелайкнутая
        $ids = Task::query()
            ->excludeUser($this->me)
            ->excludeLikedByUser($this->me)
            ->pluck('id')
            ->all();

        $this->assertEquals($ids, [$user2_task2->id]);
    }

    public function testCheckQueue()
    {
        $n = 15; // actions to seed
        $user2 = $this->createUser();
        $actions = [];
        $tasks = [];

        foreach (range(1, $n) as $i) {
            $task = $this->createTask($user2);
            $actions[] = $task->likeTask($this->myTask);
            $tasks[] = $task;
        }

        $myTask2 = $this->createTask($this->me);
        $myTask2->likeTask($this->myTask);
        $this->assertEquals($myTask2->checkQueue(), $n);

        // dd(DB::table('actions')->get()->all());
        // поочередно лайкаем все actions перед $myTask2
        // очередь должна уменьшаться на 1 каждый шаг
        foreach (range(1, $n) as $i) {
            $tasks[$i - 1]->likeAction($actions[$i - 1]);
            $this->assertEquals($n - $i, $myTask2->checkQueue(true), "Step: {$i}");
        }
    }
}

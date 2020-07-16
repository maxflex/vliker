<?php

namespace Tests\Feature;

use App\Enums\BanReason;
use App\Models\{User, Task};

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
}

<?php

namespace Tests\Feature;

use App\Models\{User, Task};

class TasksTest extends FeatureTestCase
{
    public function testExcludeLikedByUserScope()
    {
        // Создаем 2 задачи
        $otherUserTask1 = $this->createOtherUserTask();
        $otherUserTask2 = $this->createOtherUserTask();

        // До лайка должны получить все 2 задачи
        $ids1 = Task::query()
            ->excludeUser($this->me)
            ->pluck('id')
            ->all();

        $ids2 = $this->otherUser->tasks()->pluck('id')->all();

        $this->assertTrue($ids1 === $ids2);

        // лайкаем одну из задач
        $this->myTask->actionsFrom()->create([
            'task_id_to' => $otherUserTask1->id,
        ]);

        // теперь должна вернуться 1 нелайкнутая
        $ids = Task::query()
            ->excludeUser($this->me)
            ->excludeLikedByUser($this->me)
            ->pluck('id')
            ->all();

        $this->assertTrue($ids === [$otherUserTask2->id]);
    }

    /**
     * Задача должна становится активной, если кол-во накрученных > кол-во поставленных
     * и наоборот, деактивироваться, если кол-во поставленных становится > кол-во накрученных
     */
    public function testActivatesAndDeactivates()
    {
        $otherUserTask = $this->createOtherUserTask();

        $this->assertFalse($otherUserTask->fresh()->is_active);

        $otherUserTask->actionsFrom()->create([
            'task_id_to' => $this->myTask->id
        ]);

        $this->assertTrue($otherUserTask->fresh()->is_active);

        $this->myTask->actionsFrom()->create([
            'task_id_to' => $otherUserTask->id
        ]);

        $this->assertFalse($otherUserTask->fresh()->is_active);
    }
}

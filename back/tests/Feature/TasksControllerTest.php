<?php

namespace Tests\Feature;

use App\Models\{User, Task};

class TasksControllerTest extends FeatureTestCase
{
    public function testNext()
    {
        // Создаем 4 задачи $otherUserTask1 – $otherUserTask4
        // и накручиваем им по одному лайку, чтобы участвовали в подборе
        foreach (range(1, 4) as $i) {
            $varName = 'otherUserTask' . $i;
            $$varName = $this->createOtherUserTask();
            $$varName->actionsFrom()->create([
                'task_id_to' => $this->myTask->id
            ]);
        }

        // Делаем 2 из них завершенными
        // (кол-во полученных лайков >= кол-во накрученных)
        $otherUserTask2->actionsTo()->create([
            'task_id_from' => $otherUserTask1->id
        ]);
        $otherUserTask4->actionsTo()->create([
            'task_id_from' => $otherUserTask1->id
        ]);

        // Теперь мы делаем запросы на /next
        // Сначала должны получить активные задачи, а потом завершенные
        // Порядок активных должен быть: по возрастанию
        // Порядок завершенных должен быть: по убыванию
        // $otherUserTask2, $otherUserTask4 – завершенные
        // $otherUserTask1, $otherUserTask3 – активные
        // т.е. порядок должен быть: 1, 3, 4, 2
        $response = $this->post(route('tasks.next'), [
            'task_id_from' => $this->myTask->id,
        ]);

        $response->assertJson([
            'id' => $otherUserTask1->id
        ]);

        // Лайкаем задачу & получаем следующую
        $response = $this->post(route('tasks.next'), [
            'task_id_from' => $this->myTask->id,
            'task_id_to' => $otherUserTask1->id,
        ]);

        $response->assertJson([
            'id' => $otherUserTask3->id
        ]);

        $response = $this->post(route('tasks.next'), [
            'task_id_from' => $this->myTask->id,
            'task_id_to' => $otherUserTask3->id,
        ]);

        $response->assertJson([
            'id' => $otherUserTask4->id
        ]);

        $response = $this->post(route('tasks.next'), [
            'task_id_from' => $this->myTask->id,
            'task_id_to' => $otherUserTask4->id,
        ]);

        $response->assertJson([
            'id' => $otherUserTask2->id
        ]);
    }
}

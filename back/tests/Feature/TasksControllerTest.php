<?php

namespace Tests\Feature;

use App\Enums\BanReason;
use App\Enums\TaskType;
use App\Models\{Action, User, Task};
use App\Utils\Url;
use Illuminate\Support\Facades\DB;

class TasksControllerTest extends FeatureTestCase
{
    public function testNext()
    {
        $user2 = $this->createUser();
        $user2_task = $this->createTask($user2);
        $action1 = $user2_task->likeTask($this->myTask);

        $user3 = $this->createUser();
        $user3_task = $this->createTask($user3);
        $action2 = $user3_task->likeTask($this->myTask);

        // Среди 2х действий оба сейчас невыполнены
        // Невыполненные должны идтии по порядку
        $this
            ->nextRequest($this->myTask->id)
            ->assertJson([
                'id' => $action1->id,
            ]);

        // Лайкаем 1е действие, должно теперь вернуться второе
        $this
            ->nextRequest($this->myTask->id, $action1->id)
            ->assertJson([
                'id' => $action2->id,
            ]);

        // Лайкаем второе действие – нелайкнутых действий больше нет
        $this
            ->nextRequest($this->myTask->id, $action2->id)
            ->assertNotFound();


        // Переключаемся на нового пользователя
        $user4 = $this->createUser();
        $user4_task = $this->createTask($user4);
        $this->actingAs($user4, 'api');

        // dd(DB::table('actions')->get()->all());
        // dd(Action::orderByActiveFirst()->get()->map(fn ($i) => $i->toArray())->all());

        // Теперь должны идти 2 выше лайкнутые actions с ID 3, 4 по порядку
        $this
            ->nextRequest($user4_task->id)
            ->assertJson([
                'id' => 3,
            ]);

        // ВНИМАНИЕ: тут должна вернуться action_id=2, а не 4
        // Потому что мы лайкаем action_id=3, потом срабатывает excludeLikedByUser
        // и исключается action_id=4, потому что это одна и та же задача
        $this
            ->nextRequest($user4_task->id, 3)
            ->assertJson([
                'id' => 2
            ]);

        // Дальше должен появиться старый пользователь,
        // создать action и его action должен быть честно в конце
        $user2_task->likeTask($user3_task);

        // Создаем нового пользователя и лайкаем все в соответствии с:
        // dd(DB::table('actions')->get()->all())
        $user5 = $this->createUser();
        $user5_task = $this->createTask($user5);
        $this->actingAs($user5, 'api');

        $this->nextRequest($user5_task->id)->assertJson([
            'id' => 4,
        ]);
        $this->nextRequest($user5_task->id, 4)->assertJson([
            'id' => 5,
        ]);
        $this->nextRequest($user5_task->id, 5)->assertJson([
            'id' => 6,
        ]);
        $this->nextRequest($user5_task->id, 6)->assertJson([
            'id' => 2,
        ]);
        $this->nextRequest($user5_task->id, 2)->assertNotFound();

        // Пробуем забанить задачу и убеждаемся,
        // что снятый лайк будет в приоритете
        // (все лайки забаненной задачи снимаются)
        // По идее, след невыполненный action_id=7,
        // но после бана след невыполненный должен быть action_id=1, action_id=2
        $this->myTask->ban(BanReason::Cheat);

        $user6 = $this->createUser();
        $user6_task = $this->createTask($user6);
        // dd($user6_task->toArray());
        $this->actingAs($user6, 'api');

        $this->nextRequest($user6_task->id)->assertJson([
            'id' => 1
        ]);
        $this->nextRequest($user6_task->id, 1)->assertJson([
            'id' => 2
        ]);
        $this->nextRequest($user6_task->id, 2)->assertJson([
            'id' => 7
        ]);

        // dd(DB::table('actions')->get()->all());
    }

    public function testCheckQueue()
    {
        $user2 = $this->createUser();
        $user2_task1 = $this->createTask($user2);
        $user2_task2 = $this->createTask($user2);
        $myTask2 = $this->createTask($this->me);

        $user2_task1->likeTask($this->myTask);

        $this->checkQueue($myTask2->id, 1);

        $user2_task2->likeTask($this->myTask);
        $user2_task2->likeTask($myTask2);

        $this->checkQueue($myTask2->id, 2);

        $user2_task1->likeTask($user2_task2);

        $this->checkQueue($myTask2->id, 1);

        $user2_task2->likeTask($user2_task1);

        $this->checkQueue($myTask2->id, 0);
    }

    public function testStore()
    {
        $params = [
            'url' => 'https://vk.com/club19711522',
            'type' => TaskType::Subscribe,
        ];

        $response = $this->post(route('tasks.store'), $params);

        $response
            ->assertOk()
            ->assertJson([
                'url' => $params['url'],
                'type' => $params['type'],
            ]);
    }

    /**
     * Тест на бан задач из-за читов
     * Тест выполняется долго из-за sleep, поэтому запускать отдельно
     */
    public function testCheatControl()
    {
        // Закомменить это, если понадобится запустить тест
        $this->assertTrue(true);
        return;

        config(['cheat-control-test' => true]);

        $user2 = $this->createUser();
        foreach (TaskType::getValues() as $taskType) {
            $myTask = $this->createTask($this->me, $taskType);
            $user2_task = $this->createTask($user2, $taskType);
            $user2_task->likeTask($myTask);
            $myTask->likeTask($this->myTask);
            $limit = config('ban.limit');
            $seconds = config('ban.seconds.' . $taskType);

            foreach (range(1, $limit) as $i) {
                dump($taskType, $i);
                $response = $this->post(route('tasks.next'), [
                    'task_id_from' => $myTask->id,
                ]);
                sleep($seconds);
                if ($i === $limit) {
                    $response->assertStatus(429);
                    $this->assertEquals($myTask->fresh()->ban_reason, BanReason::Cheat);
                    $this->assertCount(0, $myTask->fresh()->actionsFrom);
                } else {
                    $response->assertOk();
                    $this->assertFalse($myTask->fresh()->is_banned);
                    $this->assertCount(1, $myTask->fresh()->actionsFrom);
                }
            }
        }
    }

    private function checkQueue($taskId, $assertSee)
    {
        return $this
            ->get(route('tasks.check-queue', [
                'task' => $taskId
            ]))
            ->assertSee($assertSee);
    }

    private function nextRequest($taskIdFrom, $actionId = null)
    {
        $params = [
            'task_id_from' => $taskIdFrom,
        ];

        if ($actionId !== null) {
            $params['action_id'] = $actionId;
        }

        return $this->post(route('tasks.next'), $params);
    }
}

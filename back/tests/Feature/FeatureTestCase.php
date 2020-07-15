<?php

namespace Tests\Feature;

use App\Enums\TaskType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\{User, Task};

class FeatureTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->me = factory(User::class)->create();
        $this->myTask = $this->me->tasks()->save(
            factory(Task::class)->make()
        );
        $this->otherUser = factory(User::class)->create();
        $this->actingAs($this->me, 'api');
    }

    protected function createOtherUserTask(
        User $user = null,
        string $taskType = TaskType::Like
    ) {
        if ($user === null) {
            $user = $this->otherUser;
        }
        return $user->tasks()->save(
            factory(Task::class)->make([
                'type' => $taskType,
            ])
        );
    }
}

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

        $this->me = $this->createUser();
        $this->myTask = $this->createTask($this->me);
        $this->actingAs($this->me, 'api');
    }

    protected function createTask(User $user, string $taskType = TaskType::Like)
    {
        return $user->tasks()->save(
            factory(Task::class)->make([
                'type' => $taskType,
            ])
        );
    }

    protected function createUser()
    {
        return factory(User::class)->create();
    }
}

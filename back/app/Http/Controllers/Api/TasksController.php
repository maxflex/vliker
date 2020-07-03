<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task\{Task, CompletedTask};
use App\Http\Requests\Task\{
    StoreRequest,
    NextRequest
};
use App\Http\Resources\TaskResource;
use App\Utils\Url;

class TasksController extends Controller
{
    public function index(Request $request)
    {
        return TaskResource::collection(
            auth()->user()->tasks
        );
    }

    public function store(StoreRequest $request)
    {
        // если такая задача уже есть, то подгружаем её
        $item = Task::where([
            'url' => Url::shorten($request->url),
            'type' => $request->type,
            'user_id' => auth()->id(),
        ])->first();

        // если задачи нет, создаём новую...
        if ($item === null) {
            $item = auth()->user()->tasks()->create($request->all());
        }

        return new TaskResource($item->fresh());
    }

    public function show($id)
    {
        return new TaskResource(
            Task::find($id)
        );
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    /**
     * Получить следующую задачу для пользователя
     * И засчитать лайк предыдущей задачи, если передан target_task_id
     */
    public function next(NextRequest $request)
    {
        // На эту задачу мы в данный момент накручиваем
        $myTask = Task::find($request->completed_by_task_id);

        if (isset($request->target_task_id)) {
            $myTask->completed()->create([
                'target_task_id' => $request->target_task_id
            ]);
        }

        $nextTask = Task::query()
            ->where('type', $myTask->type)
            ->excludeReported()
            ->excludeBanned()
            ->excludeLiked(auth()->user())
            ->excludeOwn(auth()->user())
            ->orderByActiveFirst()
            ->firstOrFail();

        return new TaskResource($nextTask);
    }

    /**
     * Проверить какая задача в очереди
     * (сколько задач передо мной, у которых received=0)
     */
    public function checkQueue(Task $task)
    {
        return Task::query()
            ->where('type', $task->type)
            ->where('id', '<', $task->id)
            ->whereRaw("
                (select count(*) from completed_tasks where target_task_id = tasks.id) = 0
            ")
            ->count();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Enums\BanReason;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\{
    StoreRequest,
    NextRequest
};
use App\Http\Resources\{ActionResource, TaskResource, StatsTaskResource};
use App\Models\{Action, Task};
use App\Utils\Url;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{
    /**
     * Мои задачи
     */
    public function index(Request $request)
    {
        // Отметить уведомления прочитанными
        auth()->user()->update([
            'last_seen_action_id' => Action::query()
                ->whereHas(
                    'taskTo',
                    fn ($query) => $query->where('user_id', auth()->id())
                )
                ->max('id')
        ]);

        return StatsTaskResource::collection(
            auth()->user()->tasks()->orderByLatestAction()->get()
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

    /**
     * Получить следующую задачу для пользователя
     * И засчитать лайк предыдущей задачи, если передан task_id_to
     *
     * Задачи с минимум 1 лайком участвуют в подборе
     */
    public function next(NextRequest $request)
    {
        // На эту задачу мы в данный момент накручиваем
        $myTask = Task::find($request->task_id_from);

        // Если передан action_id – лайкаем задачу
        if (isset($request->action_id)) {
            $myTask->likeAction(Action::find($request->action_id));
        }



        $tasksQuery = Task::query()
            ->notBanned()
            ->excludeUser(auth()->user())
            ->excludeLikedByUser(auth()->user())
            ->where('type', $myTask->type);

        $nextAction = Action::query()
            ->select('actions.*')
            ->joinSub($tasksQuery, 'tasks', 'tasks.id', '=', 'actions.task_id_from')
            ->orderByActiveFirst()
            ->firstOrFail();

        if ($this->cheatControl($myTask)) {
            return response(null, 429);
        }

        return new ActionResource($nextAction);
    }


    /**
     * Бан за быстрые лайки
     */
    private function cheatControl(Task $task): bool
    {
        if (app()->environment('testing') && config('cheat-control-test') !== true) {
            return false;
        }

        $key = cache_key(BanReason::Cheat, auth()->id());
        $seconds = config('ban.seconds.' . $task->type);
        $limit = config('ban.limit');

        cache()->put($key, cache()->increment($key), $seconds);

        if (cache($key) >= $limit) {
            $task->ban(BanReason::Cheat);
            // очистить кеш
            cache()->forget($key);
            return true;
        }

        return false;
    }

    /**
     * Проверить какая задача в очереди
     * (сколько невыполненных действий передо мной)
     * (точнее, перед первым моим действием)
     */
    public function checkQueue(Task $task)
    {
        return Action::query()
            ->whereHas(
                'taskFrom',
                fn ($query) => $query
                    ->where('type', $task->type)
                    ->whereNull('ban_reason')
            )
            ->where('id', '<', $task->actionsFrom()->value('id'))
            ->active()
            ->count();
    }
}

<?php

namespace App\Models;

use App\Enums\BanReason;
use App\Scopes\TaskScopes;
use App\Utils\Url;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    use TaskScopes;

    protected $fillable = [
        'task_id_from', 'task_id_to', 'type', 'url'
    ];

    protected $appends = [
        'is_banned'
    ];

    /**
     * Поставлено задачей (я поставил)
     */
    public function actionsFrom()
    {
        return $this->hasMany(Action::class, 'task_id_from');
    }

    /**
     * Поставлено задаче (мне поставили)
     */
    public function actionsTo()
    {
        return $this->hasMany(Action::class, 'task_id_to');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * "Лайкнуть" задачу (засчитать действие)
     */
    public function likeTask(Task $task): Action
    {
        return $this->actionsFrom()->create([
            'task_id_to' => $task->id,
        ]);
    }

    public function likeAction(Action $action)
    {
        $newAction = $this->likeTask($action->taskFrom);

        // устанавливаем единожды, храним оригинальный action_id
        // не перезаписываем by ID сверхурочных лайков
        if ($action->action_id === null) {
            $action->update([
                'action_id' => $newAction->id
            ]);
        }
    }

    /**
     * Проверить, является ли задача активной
     * Когда поставленных задачей лайков больше, чем накрученных ей
     */
    public function isActive()
    {
        return $this->actionsFrom->count() > $this->actionsTo->count();
    }

    public function ban(string $banReason)
    {
        if (BanReason::hasValue($banReason)) {
            if ($banReason === BanReason::Cheat) {
                // Archive all actions
                DB::table("_actions")->insert(json_redecode($this->actionsFrom, true));
                $this->actionsFrom()->delete();
            }
            $this->ban_reason = $banReason;
            return $this->save();
        }
        throw new Exception("Wrong ban reason");
    }

    /**
     * Проверить место в очереди
     *
     * Сколько невыполненных действий между первым action_id моей задачи
     * и первым выполненным action_id, который меньше action_id моей задачи
     */
    public function checkQueue()
    {
        $query = Action::query()
            ->whereHas(
                'taskFrom',
                fn ($query) => $query
                    ->where('type', $this->type)
                    ->whereNull('ban_reason')
            )
            ->where('id', '<', $this->actionsFrom()->value('id'));

        $latestLikedAction = (clone $query)
            ->finished()
            ->latest('id')
            ->first();

        return $query
            ->when(
                $latestLikedAction,
                fn ($query) => $query->where('id', '>', $latestLikedAction->id)
            )
            ->count();
    }

    public function getUrlAttribute($value)
    {
        return Url::vk($value);
    }

    public function setUrlAttribute($value)
    {
        $this->attributes['url'] = Url::shorten($value);
    }

    public function getIsBannedAttribute()
    {
        return $this->ban_reason !== null;
    }
}

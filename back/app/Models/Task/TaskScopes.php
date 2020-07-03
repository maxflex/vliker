<?php

namespace App\Models\Task;

use App\Models\User;
use DB;

trait TaskScopes
{
    /**
     * Активные задачи
     *
     * всего накручено = все записи, где task_id=completed_by_task_id
     * уже поставлено = все записи, где task_id=target_task_id
     * Условие активности задачи: всего - уже поставлено > 0
     */
    public function scopeRequireIsActiveField($query)
    {
        return $query
            ->addSelect(DB::raw("
                tasks.*,
                (select count(*) from completed_tasks where completed_by_task_id = tasks.id) -
                (select count(*) from completed_tasks where target_task_id = tasks.id) > 0
                as is_active
            "));
    }

    /**
     * Исключить задачи, на которые оставили жалобы
     */
    public function scopeExcludeReported($query)
    {
        return $query->whereRaw("(select count(*) from reported_tasks where reported_task_id = tasks.id) < " . ReportedTask::$limit);
    }

    /**
     * Сортировка отображения задач
     *
     * Сначала идут активные, сначала старые
     * Потом уже выполненные, сначала новые
     *
     * Выполненные отображаются для того, чтобы задачи не кончались
     * и у пользователя была возможность продолжать накручивать
     */
    public function scopeOrderByActiveFirst($query)
    {
        return $query
            ->requireIsActiveField()
            ->orderByRaw("
                is_active desc,
                case when is_active = 1 then tasks.id end asc,
                case when is_active = 0 then tasks.id end desc
            ");
    }

    /**
     * Исключить уже лайкнутые мной задачи
     */
    public function scopeExcludeLiked($query, User $user)
    {
        return $query
            ->whereRaw("
                not exists(
                    select 1 from completed_tasks
                    where completed_tasks.target_task_id = tasks.id
                    and (
                        select user_id from tasks
                        where tasks.id = completed_tasks.completed_by_task_id
                    ) = {$user->id}
                )
            ");
    }

    /**
     * Заблокированные задачи
     *
     * Кто налайкал > 150
     */
    public function scopeExcludeBanned($query)
    {
        return $query
            ->whereRaw("(select count(*) from completed_tasks where completed_by_task_id = tasks.id) <= " . Task::$maxCompletedAllowed);
    }

    /**
     * Задачи за исключением пользователя
     */
    public function scopeExcludeOwn($query, User $user)
    {
        return $query->where('tasks.user_id', '<>', $user->id);
    }

    /**
     * Только активные задачи
     */
    public function scopeActive($query)
    {
        return $query->whereRaw("
            (select count(*) from completed_tasks where completed_by_task_id = tasks.id) -
            (select count(*) from completed_tasks where target_task_id = tasks.id) > 0
        ");
    }
}

<?php

namespace App\Scopes;

trait ActionScopes
{
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
        return $query->orderByRaw("
            ISNULL(actions.action_id) DESC,
            CASE WHEN actions.action_id IS NULL THEN actions.id END ASC,
            CASE WHEN actions.action_id IS NOT NULL THEN actions.id END DESC
        ");
    }

    /**
     * Действие еще не лайкнули
     */
    public function scopeActive($query)
    {
        return $query->whereNull('action_id');
    }

    /**
     * Действие уже лайкнули
     */
    public function scopeFinished($query)
    {
        return $query->whereNotNull('action_id');
    }
}

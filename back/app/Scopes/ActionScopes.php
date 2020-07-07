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
            tasks.is_active DESC,
            CASE WHEN tasks.is_active = 1 THEN actions.id END ASC,
            CASE WHEN tasks.is_active = 0 THEN actions.id END DESC
        ");
    }
}

<?php

namespace App\Scopes;

use App\Models\User;

trait TaskScopes
{
    public function scopeNotBanned($query)
    {
        return $query->whereNull('ban_reason');
    }

    public function scopeExcludeUser($query, User $user)
    {
        return $query->where('user_id', '<>', $user->id);
    }

    public function scopeExcludeLikedByUser($query, User $user)
    {
        return $query->whereDoesntHave(
            'actionsTo',
            fn ($query) => $query->whereHas(
                'taskFrom',
                fn ($query) => $query->where('user_id', $user->id)
            )
        );
    }

    public function scopeOrderByLatestAction($query)
    {
        $query
            ->selectRaw("
                tasks.*,
                (select max(created_at) from actions where actions.task_id_from = tasks.id) as latest_action_created_at
            ")
            ->orderBy("latest_action_created_at", "desc");
    }
}

<?php

namespace App\Scopes;

use App\Models\User;

trait TaskScopes
{
    public function scopeNotBanned($query)
    {
        return $query->where('is_banned', false);
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
}

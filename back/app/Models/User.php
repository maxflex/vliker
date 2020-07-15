<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'last_seen_action_id'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Кол-во новых уведомлений
     * new_notifications = кол-во actions c момента сохраненного last_seen_action_id
     */
    public function getNewNotificationsAttribute()
    {
        return Action::query()
            ->selectRaw('task_id_to, count(*) as total')
            ->whereHas(
                'taskTo',
                fn ($query) => $query->where('user_id', $this->id)
            )
            ->where('id', '>', (int) $this->last_seen_action_id)
            ->groupBy('task_id_to')
            ->pluck('total', 'task_id_to');
    }
}
